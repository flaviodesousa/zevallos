<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackSCMEvent {
  /** Revision or changeset identifier for this particular file */
  public $rev;

  /** commit message associated with this revision */
  public $changelog;

  /** who committed this revision */
  public $changeby;

  /** when this revision was committed */
  public $ctime;

  /** files affected in this event; may be null, but otherwise
   * will be an array of MTrackSCMFileEvent */
  public $files;
}

class MTrackSCMFileEvent {
  /** Name of affected file */
  public $name;
  /** Change status indicator */
  public $status;

  /** when used in a string context, just return the filename.
   * This simplifies explicit object vs. string interpretation
   * throughout the SCM layer */
  function __toString() {
    return $this->name;
  }
}

class MTrackSCMAnnotation {
  /** Revision of changeset identifier for when line was changed */
  public $rev;

  /** who made the change */
  public $changeby;

  /** the content from that line of the file.
   * This is null unless $include_line_content was set to true when annotate()
   * was called */
  public $line;
}

abstract class MTrackSCMFile {
  /** reference to the associated MTrackSCM object */
  public $repo;

  /** full path to file, with a leading slash (which represents
   * the root of its respective repo */
  public $name;

  /** if true, this file represents a directory */
  public $is_dir = false;

  /** revision */
  public $rev;

  function __construct(MTrackSCM $repo, $name, $rev, $is_dir = false)
  {
    $this->repo = $repo;
    $this->name = $name;
    $this->rev = $rev;
    $this->is_dir = $is_dir;
  }

  /** Returns an MTrackSCMEvent corresponding to this revision of
   * the file */
  abstract public function getChangeEvent();

  /** Returns a stream representing the contents of the file at
   * this revision */
  abstract public function cat();

  /** Returns an array of MTrackSCMAnnotation objects that correspond to
   * each line of file content, annotating when the line was last
   * changed.  The array is keyed by line number, 1-based. */
  abstract public function annotate($include_line_content = false);
}

abstract class MTrackSCMWorkingCopy {
  public $dir;

  /** returns the root dir of the working copy */
  function getDir() {
    return $this->dir;
  }

  /** add a file to the working copy */
  abstract function addFile($path);
  /** removes a file from the working copy */
  abstract function delFile($path);
  /** commit changes that are pending in the working copy */
  abstract function commit(MTrackChangeset $CS);
  /** get an MTrackSCMFile representation of a file */
  abstract function getFile($path);

  /** enumerates files in a path in the working copy */
  function enumFiles($path)
  {
    return scandir($this->dir . DIRECTORY_SEPARATOR . $path);
  }

  /** determines if a file exists in the working copy */
  function file_exists($path)
  {
    return file_exists($this->dir . DIRECTORY_SEPARATOR . $path);
  }

  function __destruct()
  {
    if (strlen($this->dir) > 1) {
      mtrack_rmdir($this->dir);
    }
  }
}

abstract class MTrackSCM {
  static $repos = array();

  static function factory(&$repopath) {
    /* [ / owner type rest ] */
    $bits = explode('/', $repopath, 4);
    if (count($bits) < 3) {
      throw new Exception("Invalid repo $repopath");
    }
    array_shift($bits);
    list($owner, $type) = $bits;
    $repo = "$owner/$type";

    $r = MTrackRepo::loadByName($repo);
    if (!$r) {
      throw new Exception("invalid repo $repo");
    }
    $repopath = isset($bits[2]) ? $bits[2] : '';
    return $r;
  }

  /** Returns an array keyed by possible branch names.
   * The data associated with the branches is implementation
   * defined.
   * If the SCM does not have a concept of first-class branch
   * objects, this function returns null */
  abstract public function getBranches();

  /** Returns an array keyed by possible tag names.
   * The data associated with the tags is implementation
   * defined.
   * If the SCM does not have a concept of first-class tag
   * objects, this function returns null */
  abstract public function getTags();

  /** Enumerates the files/dirs that are present in the specified
   * location of the repository that match the specified revision,
   * branch or tag information.  If no revision, branch or tag is
   * specified, then the appropriate default is assumed.
   *
   * The second and third parameters are optional; the second
   * parameter is one of 'rev', 'branch', or 'tag', and if specifed
   * the third parameter must be the corresponding revision, branch
   * or tag identifier.
   *
   * The return value is an array of MTrackSCMFile objects present
   * at that location/revision of the repository.
   */
  abstract public function readdir($path, $object = null, $ident = null);

  /** Queries information on a specific file in the repository.
   *
   * Parameters are as for readdir() above.
   *
   * This function returns a single MTrackSCMFile for the location
   * in question.
   */
  abstract public function file($path, $object = null, $ident = null);

  /** Queries history for a particular location in the repo.
   *
   * Parameters are as for readdir() above, except that path can be
   * left unspecified to query the history for the entire repo.
   *
   * The limit parameter limits the number of entries returned; it it is
   * a number, it specifies the number of events, otherwise it is assumed
   * to be a date in the past; only events since that date will be returned.
   *
   * Returns an array of MTrackSCMEvent objects.
   */
  abstract public function history($path, $limit = null, $object = null,
    $ident = null);

  /** Obtain the diff text representing a change to a file.
   *
   * You may optionally provide one or two revisions as context.
   *
   * If no revisions are passed in, then the change associated
   * with the location will be assumed.
   *
   * If one revision is passed, then the change associated with
   * that event will be assumed.
   *
   * If two revisions are passed, then the difference between
   * the two events will be assumed.
   */
  abstract public function diff($path, $from = null, $to = null);

  /** Determine the next and previous revisions for a given
   * changeset.
   *
   * Returns an array: the 0th element is an array of prior revisions,
   * and the 1st element is an array of successor revisions.
   *
   * There will usually be one prior and one successor revision for a
   * given change, but some SCMs will return multiples in the case of
   * merges.
   */
  abstract public function getRelatedChanges($revision);

  /** Returns a working copy object for the repo
   *
   * The intended purpose is to support wiki page modifications, and
   * as such, is not meant to be an especially efficient means to do so.
   */
  abstract public function getWorkingCopy();

  /** Returns the default 'root' location in the repository.
   * For SCMs that have a concept of branches, this is the empty string.
   * For SCMs like SVN, this is the trunk dir */
  public function getDefaultRoot() {
    return '';
  }

  /** Returns meta information about the SCM type; this is used in the
   * UI and tooling to let the user know their options.
   *
   * Returns an array with the following keys:
   * 'name' => 'Mercurial', // human displayable name
   * 'tools' => array('hg'), // list of tools to find during setup
   */
  abstract public function getSCMMetaData();

  /* takes an MTrackSCM as a parameter because in some bootstrapping
   * cases, we're actually MTrackRepo and not the end-class.
   * MTrackRepo calls the end-class method and passes itself in for
   * context */
  public function reconcileRepoSettings(MTrackSCM $r) {
    throw new Exception(
      "Creating/updating a repo of type $this->scmtype is not implemented");
  }

  static function makeBreadcrumbs($pi) {
    if (!strlen($pi)) {
      $pi = '/';
    }
    if ($pi == '/') {
      $crumbs = array('');
    } else {
      $crumbs = explode('/', $pi);
    }
    return $crumbs;
  }

  static function makeDisplayName($data) {
    $parent = '';
    $name = '';
    if (is_object($data)) {
      $parent = $data->parent;
      $name = $data->shortname;
    } else if (is_array($data)) {
      $parent = $data['parent'];
      $name = $data['shortname'];
    }
    if ($parent) {
      list($type, $owner) = explode(':', $parent);
      return "$owner/$name";
    }
    return "default/$name";
  }

  public function getBrowseRootName() {
    return self::makeDisplayName($this);
  }

  public function resolveRevision($rev, $object, $ident) {
    if ($rev !== null) {
      return $rev;
    }
    if ($object === null) {
      return null;
    }
    switch ($object) {
      case 'rev':
        $rev = $ident;
        break;
      case 'branch':
        $branches = $this->getBranches();
        $rev = isset($branches[$ident]) ? $branches[$ident] : null;
        break;
      case 'tag':
        $tags = $this->getTags();
        $rev = isset($tags[$ident]) ? $tags[$ident] : null;
        break;
    }
    if ($rev === null) {
      throw new Exception(
        "don't know which revision to use ($rev,$object,$ident)");
    }
    return $rev;
  }
}
MTrackACL::registerAncestry('repo', 'Browser');
MTrackWatch::registerEventTypes('repo', array(
  'ticket' => 'Tickets',
  'changeset' => 'Code changes'
));

class MTrackRepo extends MTrackSCM {
  public $repoid = null;
  public $shortname = null;
  public $scmtype = null;
  public $repopath = null;
  public $browserurl = null;
  public $browsertype = null;
  public $description = null;
  public $parent = '';
  public $clonedfrom = null;
  public $serverurl = null;
  private $links_to_add = array();
  private $links_to_remove = array();
  private $links = null;
  static $scms = array();

  static function registerSCM($scmtype, $classname) {
    self::$scms[$scmtype] = $classname;
  }
  static function getAvailableSCMs() {
    $ret = array();
    foreach (self::$scms as $t => $classname) {
      $o = new $classname;
      $ret[$t] = $o;
    }
    return $ret;
  }

  public function reconcileRepoSettings() {
    if (!isset(self::$scms[$this->scmtype])) {
      throw new Exception("invalid scm type $this->scmtype");
    }
    $c = self::$scms[$this->scmtype];
    $s = new $c;
    $s->reconcileRepoSettings($this);
  }

  public function getSCMMetaData() {
    return null;
  }

  static function loadById($id) {
    list($row) = MTrackDB::q(
      'select repoid, scmtype from repos where repoid = ?',
      $id)->fetchAll();
    if (isset($row[0])) {
      $type = $row[1];
      if (isset(self::$scms[$type])) {
        $class = self::$scms[$type];
        return new $class($row[0]);
      }
      throw new Exception("unsupported repo type $type");
    }
    return null;
  }

  static function loadByName($name) {
    $bits = explode('/', $name);
    if (count($bits) > 1 && $bits[0] == 'default') {
      array_shift($bits);
      $name = $bits[0];
    }
    if (count($bits) > 1) {
      /* wez/reponame -> per user repo */
      $u = "user:$bits[0]";
      $p = "project:$bits[0]";
      $rows = MTrackDB::q(
        'select repoid, scmtype from repos where shortname = ? and (parent = ? OR parent = ?)',
        $bits[1], $u, $p)->fetchAll();
    } else {
      $rows = MTrackDB::q(
        "select repoid, scmtype from repos where shortname = ? and parent =''",
        $name)->fetchAll();
    }
    if (is_array($rows) && isset($rows[0])) {
      $row = $rows[0];
      if (isset($row[0])) {
        $type = $row[1];
        if (isset(self::$scms[$type])) {
          $class = self::$scms[$type];
          return new $class($row[0]);
        }
        throw new Exception("unsupported repo type $type");
      }
    }
    return null;
  }

  function getServerURL() {
    if ($this->serverurl) {
      return $this->serverurl;
    }
    $url = MTrackConfig::get('repos', "$this->scmtype.serverurl");
    if ($url) {
      return $url . $this->getBrowseRootName();
    }
    return null;
  }

  function getCheckoutCommand() {
    $url = $this->getServerURL();
    if (strlen($url)) {
      return $this->scmtype . ' clone ' . $this->getServerURL();
    }
    return null;
  }

  function canFork() {
    return false;
  }

  static function loadByLocation($path) {
    list($row) = MTrackDB::q('select repoid, scmtype from repos where repopath = ?', $path)->fetchAll();
    if (isset($row[0])) {
      $type = $row[1];
      if (isset(self::$scms[$type])) {
        $class = self::$scms[$type];
        return new $class($row[0]);
      }
      throw new Exception("unsupported repo type $type");
    }
    return null;
  }

  public function getWorkingCopy() {
    throw new Exception("cannot getWorkingCopy from a generic repo object");
  }

  function __construct($id = null) {
    if ($id !== null) {
      list($row) = MTrackDB::q(
                    'select * from repos where repoid = ?',
                    $id)->fetchAll();
      if (isset($row[0])) {
        $this->repoid = $row['repoid'];
        $this->shortname = $row['shortname'];
        $this->scmtype = $row['scmtype'];
        $this->repopath = $row['repopath'];
        $this->browserurl = $row['browserurl'];
        $this->browsertype = $row['browsertype'];
        $this->description = $row['description'];
        $this->parent = $row['parent'];
        $this->clonedfrom = $row['clonedfrom'];
        $this->serverurl = $row['serverurl'];
        return;
      }
      throw new Exception("unable to find repo with id = $id");
    }
  }

  function deleteRepo(MTrackChangeset $CS) {
    MTrackDB::q('delete from repos where repoid = ?', $this->repoid);
    mtrack_rmdir($this->repopath);
  }

  function save(MTrackChangeset $CS) {
    if (!isset(self::$scms[$this->scmtype])) {
      throw new Exception("unsupported repo type " . $this->scmtype);
    }

    if ($this->repoid) {
      list($row) = MTrackDB::q(
                    'select * from repos where repoid = ?',
                    $this->repoid)->fetchAll();
      $old = $row;
      MTrackDB::q(
          'update repos set shortname = ?, scmtype = ?, repopath = ?,
            browserurl = ?, browsertype = ?, description = ?,
            parent = ?, serverurl = ?, clonedfrom = ? where repoid = ?',
          $this->shortname, $this->scmtype, $this->repopath,
          $this->browserurl, $this->browsertype, $this->description,
          $this->parent, $this->serverurl, $this->clonedfrom, $this->repoid);
    } else {
      $acl = null;

      if (!strlen($this->repopath)) {
        if (!MTrackConfig::get('repos', 'allow_user_repo_creation')) {
          throw new Exception("configuration does not allow repo creation");
        }
        $repodir = MTrackConfig::get('repos', 'basedir');
        if ($repodir == null) {
          $repodir = MTrackConfig::get('core', 'vardir') . '/repos';
        }
        if (!is_dir($repodir)) {
          mkdir($repodir);
        }

        if (!$this->parent) {
          $owner = mtrack_canon_username(MTrackAuth::whoami());
          $this->parent = 'user:' . $owner;
        } else {
          list($type, $owner) = explode(':', $this->parent, 2);
          switch ($type) {
            case 'project':
              $P = MTrackProject::loadByName($owner);
              if (!$P) {
                throw new Exception("invalid project $owner");
              }
              MTrackACL::requireAllRights("project:$P->projid", 'modify');
              break;
            case 'user':
              if ($owner != mtrack_canon_username(MTrackAuth::whoami())) {
                throw new Exception("can't make a repo for another user");
              }
              break;
            default:
              throw new Exception("invalid parent ($this->parent)");
          }
        }
        if (preg_match("/[^a-zA-Z0-9_.-]/", $owner)) {
          throw new Exception("$owner must not contain special characters");
        }
        $this->repopath = $repodir . DIRECTORY_SEPARATOR . $owner;
        if (!is_dir($this->repopath)) {
          mkdir($this->repopath);
        }
        $this->repopath .= DIRECTORY_SEPARATOR . $this->shortname;

        /* default ACL is allow user all rights, block everybody else */
        $acl = array(
          array($owner, 'read', 1),
          array($owner, 'modify', 1),
          array($owner, 'delete', 1),
          array($owner, 'checkout', 1),
          array($owner, 'commit', 1),
          array('*', 'read', 0),
          array('*', 'modify', 0),
          array('*', 'delete', 0),
          array('*', 'checkout', 0),
          array('*', 'commit', 0),
        );
      }

      MTrackDB::q('insert into repos (shortname, scmtype,
          repopath, browserurl, browsertype, description, parent,
          serverurl, clonedfrom)
          values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
          $this->shortname, $this->scmtype, $this->repopath,
          $this->browserurl, $this->browsertype, $this->description,
          $this->parent, $this->serverurl, $this->clonedfrom);

      $this->repoid = MTrackDB::lastInsertId('repos', 'repoid');
      $old = null;

      if ($acl !== null) {
        MTrackACL::setACL("repo:$this->repoid", 0, $acl);
        $me = mtrack_canon_username(MTrackAuth::whoami());
        foreach (array('ticket', 'changeset') as $e) {
          MTrackDB::q(
            'insert into watches (otype, oid, userid, medium, event, active) values (?, ?, ?, ?, ?, 1)',
          'repo', $this->repoid, $me, 'email', $e);
        }
      }
    }
    $this->reconcileRepoSettings();
    if (!$this->parent) {
      /* for SSH access, populate a symlink from the repos basedir to the
       * actual path for this repo */
      $repodir = MTrackConfig::get('repos', 'basedir');
      if ($repodir == null) {
        $repodir = MTrackConfig::get('core', 'vardir') . '/repos';
      }
      if (!is_dir($repodir)) {
        mkdir($repodir);
      }
      $repodir .= '/default';
      if (!is_dir($repodir)) {
        mkdir($repodir);
      }
      $repodir .= '/' . $this->shortname;
      if (!file_exists($repodir)) {
        symlink($this->repopath, $repodir);
      } else if (is_link($repodir) && readlink($repodir) != $this->repopath) {
        unlink($repodir);
        symlink($this->repopath, $repodir);
      }
    }
    $CS->add("repo:" . $this->repoid . ":shortname", $old['shortname'], $this->shortname);
    $CS->add("repo:" . $this->repoid . ":scmtype", $old['scmtype'], $this->scmtype);
    $CS->add("repo:" . $this->repoid . ":repopath", $old['repopath'], $this->repopath);
    $CS->add("repo:" . $this->repoid . ":browserurl", $old['browserurl'], $this->browserurl);
    $CS->add("repo:" . $this->repoid . ":browsertype", $old['browsertype'], $this->browsertype);
    $CS->add("repo:" . $this->repoid . ":description", $old['description'], $this->description);
    $CS->add("repo:" . $this->repoid . ":parent", $old['parent'], $this->parent);
    $CS->add("repo:" . $this->repoid . ":clonedfrom", $old['clonedfrom'], $this->clonedfrom);
    $CS->add("repo:" . $this->repoid . ":serverurl", $old['serverurl'], $this->serverurl);

    foreach ($this->links_to_add as $link) {
      MTrackDB::q('insert into project_repo_link (projid, repoid, repopathregex) values (?, ?, ?)', $link[0], $this->repoid, $link[1]);
    }
    foreach ($this->links_to_remove as $linkid) {
      MTrackDB::q('delete from project_repo_link where repoid = ? and linkid = ?', $this->repoid, $linkid);
    }
  }

  function getLinks()
  {
    if ($this->links === null) {
      $this->links = array();
      foreach (MTrackDB::q('select linkid, projid, repopathregex
          from project_repo_link where repoid = ? order by repopathregex',
          $this->repoid)->fetchAll() as $row) {
        $this->links[$row[0]] = array($row[1], $row[2]);
      }
    }
    return $this->links;
  }

  function addLink($proj, $regex)
  {
    if ($proj instanceof MTrackProject) {
      $this->links_to_add[] = array($proj->projid, $regex);
    } else {
      $this->links_to_add[] = array($proj, $regex);
    }
  }

  function removeLink($linkid)
  {
    $this->links_to_remove[$linkid] = $linkid;
  }

  public function getBranches() {}
  public function getTags() {}
  public function readdir($path, $object = null, $ident = null) {}
  public function file($path, $object = null, $ident = null) {}
  public function history($path, $limit = null, $object = null, $ident = null){}
  public function diff($path, $from = null, $to = null) {}
  public function getRelatedChanges($revision) {}

  function projectFromPath($filename) {
    static $links = array();
    if (!isset($links[$this->repoid]) || $links[$this->repoid] === null) {
      $links[$this->repoid] = array();
      foreach (MTrackDB::q(
        'select projid, repopathregex from project_repo_link where repoid = ?',
            $this->repoid) as $row) {
        $re = str_replace('/', '\\/', $row[1]);
        $links[$this->repoid][] = array($row[0], "/$re/");
      }
    }
    if (is_array($filename)) {
      $proj_incidence = array();
      foreach ($filename as $file) {
        $proj = $this->projectFromPath($file);
        if ($proj === null) continue;
        if (isset($proj_incidence[$proj])) {
          $proj_incidence[$proj]++;
        } else {
          $proj_incidence[$proj] = 1;
        }
      }
      $the_proj = null;
      $the_proj_count = 0;
      foreach ($proj_incidence as $proj => $count) {
        if ($count > $the_proj_count) {
          $the_proj_count = $count;
          $the_proj = $proj;
        }
      }
      return $the_proj;
    }

    if ($filename instanceof MTrackSCMFileEvent) {
      $filename = $filename->name;
    }

    // walk through the regexes; take the longest match as definitive
    $longest = null;
    $longest_id = null;
    if ($filename[0] != '/') {
      $filename = '/' . $filename;
    }
    foreach ($links[$this->repoid] as $link) {
      if (preg_match($link[1], $filename, $M)) {
        if (strlen($M[0]) > strlen($longest)) {
          $longest = $M[0];
          $longest_id = $link[0];
        }
      }
    }
    return $longest_id;
  }
}
