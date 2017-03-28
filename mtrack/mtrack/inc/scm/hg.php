<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

/* Mercurial SCM browsing */

class MTrackSCMFileHg extends MTrackSCMFile {
  public $name;
  public $rev;
  public $is_dir;
  public $repo;

  function __construct(MTrackSCM $repo, $name, $rev, $is_dir = false)
  {
    $this->repo = $repo;
    $this->name = $name;
    $this->rev = $rev;
    $this->is_dir = $is_dir;
  }

  public function _determineFileChangeEvent($repoid, $filename, $rev)
  {
    $repo = MTrackRepo::loadById($repoid);
    $ents = $repo->history($filename, 1, 'rev', "$rev:0");
    if (!count($ents)) {
      throw new Exception("$filename is invalid");
    }
    return $ents[0];
  }

  public function getChangeEvent()
  {
    return mtrack_cache(
      array('MTrackSCMFileHg', '_determineFileChangeEvent'),
      array($this->repo->repoid, $this->name, $this->rev),
      864000);
  }

  function cat()
  {
    return $this->repo->hg('cat', '-r', $this->rev, $this->name);
  }

  function annotate($include_line_content = false)
  {
    $i = 1;
    $ann = array();
    $fp = $this->repo->hg('annotate', '-r', $this->rev, '-uvc', $this->name);
    while ($line = fgets($fp)) {
      preg_match("/^\s*([^:]*)\s+([0-9a-fA-F]+): (.*)$/", $line, $M);
      $A = new MTrackSCMAnnotation;
      $A->changeby = $M[1];
      $A->rev = $M[2];
      if ($include_line_content) {
        $A->line = $M[3];
      }
      $ann[$i++] = $A;
    }
    return $ann;
  }
}

class MTrackWCHg extends MTrackSCMWorkingCopy {
  private $repo;

  function __construct(MTrackRepo $repo) {
    $this->dir = mtrack_make_temp_dir();
    $this->repo = $repo;

    stream_get_contents($this->hg('init', $this->dir));
    stream_get_contents($this->hg('pull', $this->repo->repopath));
    stream_get_contents($this->hg('up'));
  }

  function __destruct() {

    $a = array("-y", "--cwd", $this->dir, 'push', $this->repo->repopath);

    list($proc, $pipes) = mtrack_run_tool('hg', 'proc', $a);

    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    $st = proc_close($proc);

    if ($st) {
      throw new Exception("push failed with status $st: $err $out");
    }
    mtrack_rmdir($this->dir);
  }

  function getFile($path)
  {
    return $this->repo->file($path);
  }

  function addFile($path)
  {
    // nothing to do; we use --addremove
  }

  function delFile($path)
  {
    // we use --addremove when we commit for this to take effect
    unlink($this->dir . DIRECTORY_SEPARATOR . $path);
  }

  function commit(MTrackChangeset $CS)
  {
    $hg_date = (int)strtotime($CS->when) . ' 0';
    $reason = trim($CS->reason);
    if (!strlen($reason)) {
      $reason = 'Changed';
    }
    $out = $this->hg('ci', '--addremove',
      '-m', $reason,
      '-d', $hg_date,
      '-u', $CS->who);
    $data = stream_get_contents($out);
    $st = pclose($out);
    if ($st != 0) {
      throw new Exception("commit failed $st $data");
    }
  }

  function hg()
  {
    $args = func_get_args();
    $a = array("-y", "--cwd", $this->dir);
    foreach ($args as $arg) {
      $a[] = $arg;
    }

    return mtrack_run_tool('hg', 'read', $a);
  }
}

class MTrackSCMHg extends MTrackRepo {
  protected $hg = 'hg';
  protected $branches = null;
  protected $tags = null;

  public function getSCMMetaData() {
    return array(
      'name' => 'Mercurial',
      'tools' => array('hg'),
    );
  }

  public function reconcileRepoSettings(MTrackSCM $r = null) {
    if ($r == null) {
      $r = $this;
    }
    $description = substr(preg_replace("/\r?\n/m", ' ', $r->description), 0, 64);
    $description = trim($description);
    if (!is_dir($r->repopath)) {
      if ($r->clonedfrom) {
        $S = MTrackRepo::loadById($r->clonedfrom);
        $stm = mtrack_run_tool('hg', 'read', array(
          'clone', $S->repopath, $r->repopath));
      } else {
        $stm = mtrack_run_tool('hg', 'read', array('init', $r->repopath));
      }
      $out = stream_get_contents($stm);
      $st = pclose($stm);
      if ($st) {
        throw new Exception("hg: failed $out");
      }
    }

    $php = MTrackConfig::get('tools', 'php');
    $conffile = realpath(MTrackConfig::getLocation());

    $install = realpath(dirname(__FILE__) . '/../../');

    /* fixup config */
    $apply = array(
      "hooks" => array(
        "changegroup.mtrack" =>
          "$php $install/bin/hg-commit-hook changegroup $conffile",
        "commit.mtrack" =>
          "$php $install/bin/hg-commit-hook commit $conffile",
        "pretxncommit.mtrack" =>
          "$php $install/bin/hg-commit-hook pretxncommit $conffile",
        "pretxnchangegroup.mtrack" =>
          "$php $install/bin/hg-commit-hook pretxnchangegroup $conffile",
      ),
      "web" => array(
        "description" => $description,
      )
    );

    $cfg = @file_get_contents("$r->repopath/.hg/hgrc");
    $adds = array();

    foreach ($apply as $sect => $opts) {
      foreach ($opts as $name => $value) {
        if (preg_match("/^$name\s*=/m", $cfg)) {
          $cfg = preg_replace("/^$name\s*=.*$/m", "$name = $value", $cfg);
        } else {
          $adds[$sect][$name] = $value;
        }
      }
    }

    foreach ($adds as $sect => $opts) {
      $cfg .= "[$sect]\n";
      foreach ($opts as $name => $value) {
        $cfg .= "$name = $value\n";
      }
    }
    file_put_contents("$r->repopath/.hg/hgrc", $cfg, LOCK_EX);
    system("chmod -R 02777 $r->repopath");
  }

  function canFork() {
    return true;
  }

  function getServerURL() {
    $url = parent::getServerURL();
    if ($url) return $url;
    $url = MTrackConfig::get('repos', 'serverurl');
    if ($url) {
      return "ssh://$url/" . $this->getBrowseRootName();
    }
    return null;
  }

  public function getBranches()
  {
    if ($this->branches !== null) {
      return $this->branches;
    }
    $this->branches = array();
    $fp = $this->hg('branches');
    while ($line = fgets($fp)) {
      list($branch, $revstr) = preg_split('/\s+/', $line);
      list($num, $rev) = explode(':', $revstr, 2);
      $this->branches[$branch] = $rev;
    }
    $fp = null;
    return $this->branches;
  }

  public function getTags()
  {
    if ($this->tags !== null) {
      return $this->tags;
    }
    $this->tags = array();
    $fp = $this->hg('tags');
    while ($line = fgets($fp)) {
      list($tag, $revstr) = preg_split('/\s+/', $line);
      list($num, $rev) = explode(':', $revstr, 2);
      $this->tags[$tag] = $rev;
    }
    $fp = null;
    return $this->tags;
  }

  public function readdir($path, $object = null, $ident = null)
  {
    $res = array();

    if ($object === null) {
      $object = 'branch';
      $ident = 'default';
    }
    $rev = $this->resolveRevision(null, $object, $ident);

    $fp = $this->hg('manifest', '-r', $rev);

    if (strlen($path)) {
      $path .= '/';
    }
    $plen = strlen($path);

    $dirs = array();
    $exists = false;

    while ($line = fgets($fp)) {
      $name = trim($line);

      if (!strncmp($name, $path, $plen)) {
        $exists = true;
        $ent = substr($name, $plen);
        if (strpos($ent, '/') === false) {
          $res[] = new MTrackSCMFileHg($this, "$path$ent", $rev);
        } else {
          list($d) = explode('/', $ent, 2);
          if (!isset($dirs[$d])) {
            $dirs[$d] = $d;
            $res[] = new MTrackSCMFileHg($this, "$path$d", $rev, true);
          }
        }
      }
    }

    if (!$exists) {
      throw new Exception("location $path does not exist");
    }
    return $res;
  }

  public function file($path, $object = null, $ident = null)
  {
    if ($object == null) {
      $branches = $this->getBranches();
      if (isset($branches['default'])) {
        $object = 'branch';
        $ident = 'default';
      } else {
        // fresh/empty repo
        $object = 'tag';
        $ident = 'tip';
      }
    }
    $rev = $this->resolveRevision(null, $object, $ident);
    return new MTrackSCMFileHg($this, $path, $rev);
  }

  public function history($path, $limit = null, $object = null, $ident = null)
  {
    $res = array();

    $args = array();
    if ($object !== null) {
      $rev = $this->resolveRevision(null, $object, $ident);
      $args[] = '-r';
      $args[] = $rev;
    }
    if ($limit !== null) {
      if (is_int($limit)) {
        $args[] = '-l';
        $args[] = $limit;
      } else {
        $t = strtotime($limit);
        $args[] = '-d';
        $args[] = ">$t 0";
      }
    }

    $sep = uniqid();
    $fp = $this->hg('log',
      '--template', $sep . '\n{node|short}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', $args,
      $path);

    fgets($fp); # discard leading $sep

    // corresponds to the file_adds, file_copies, file_modes, file_dels
    // in the template above
    static $file_status_order = array('A', 'C', 'M', 'D');

    while (true) {
      $ent = new MTrackSCMEvent;
      $ent->rev = trim(fgets($fp));
      if (!strlen($ent->rev)) {
        break;
      }

      $ent->branches = array();
      foreach (preg_split('/\s+/', trim(fgets($fp))) as $b) {
        if (strlen($b)) {
          $ent->branches[] = $b;
        }
      }
      if (!count($ent->branches)) {
        $ent->branches[] = 'default';
      }

      $ent->tags = array();
      foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
        if (strlen($t)) {
          $ent->tags[] = $t;
        }
      }

      $ent->files = array();

      foreach ($file_status_order as $status) {
        foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
          if (strlen($t)) {
            $f = new MTrackSCMFileEvent;
            $f->name = $t;
            $f->status = $status;
            $ent->files[] = $f;
          }
        }
      }

      $ent->changeby = trim(fgets($fp));
      list($ts) = preg_split('/\s+/', fgets($fp));
      $ent->ctime = MTrackDB::unixtime((int)$ts);
      $changelog = array();
      while (($line = fgets($fp)) !== false) {
        $line = rtrim($line, "\r\n");
        if ($line == $sep) {
          break;
        }
        $changelog[] = $line;
      }
      $ent->changelog = join("\n", $changelog);

      $res[] = $ent;

      if ($line === false) {
        break;
      }
    }
    $fp = null;
    return $res;
  }

  public function diff($path, $from = null, $to = null)
  {
    if ($path instanceof MTrackSCMFile) {
      if ($from === null) {
        $from = $path->rev;
      }
      $path = $path->name;
    }
    if ($to !== null) {
      return $this->hg('diff', '-r', $from, '-r', $to,
        '--git', $path);
    }
    return $this->hg('diff', '-c', $from, '--git', $path);
  }

  public function getWorkingCopy()
  {
    return new MTrackWCHg($this);
  }

  public function getRelatedChanges($revision)
  {
    $parents = array();
    $kids = array();

    foreach (preg_split('/\s+/',
          stream_get_contents($this->hg('parents', '-r', $revision,
              '--template', '{node|short}\n'))) as $p) {
      if (strlen($p)) {
        $parents[] = $p;
      }
    }

    foreach (preg_split('/\s+/',
        stream_get_contents($this->hg('--config',
          'extensions.children=',
          'children', '-r', $revision,
          '--template', '{node|short}\n'))) as $p) {
      if (strlen($p)) {
        $kids[] = $p;
      }
    }
    return array($parents, $kids);
  }

  function hg()
  {
    $args = func_get_args();
    $a = array("-y", "-R", $this->repopath, "--cwd", $this->repopath);
    foreach ($args as $arg) {
      $a[] = $arg;
    }

    return mtrack_run_tool('hg', 'read', $a);
  }
}

MTrackRepo::registerSCM('hg', 'MTrackSCMHg');

