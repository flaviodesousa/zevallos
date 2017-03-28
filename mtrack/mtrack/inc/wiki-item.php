<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackWikiItem {
  public $pagename = null;
  public $filename = null;
  public $version = null;
  public $file = null;
  static $wc = null;

  function __get($name) {
    if ($name == 'content') {
      $this->content = stream_get_contents($this->file->cat());
      return $this->content;
    }
  }

  static function commitNow() {
    /* force any delayed push to invoke right now */
    self::$wc = null;
    putenv("MTRACK_WIKI_COMMIT=");
  }

  static function loadByPageName($name) {
    $w = new MTrackWikiItem($name);
    if ($w->file) {
      return $w;
    }
    return null;
  }

  static function getWC() {
    if (self::$wc === null) {
      self::getRepoAndRoot($repo);
      self::$wc = $repo->getWorkingCopy();
    }
    return self::$wc;
  }

  static function getRepoAndRoot(&$repo) {
    $repo = MTrackRepo::loadByName('default/wiki');
    return $repo->getDefaultRoot();
  }

  function __construct($name, $version = null) {
    $this->pagename = $name;
    $this->filename = self::getRepoAndRoot($repo) . $name;
    $suf = MTrackConfig::get('core', 'wikifilenamesuffix');
    if ($suf) {
      $this->filename .= $suf;
    }

    if ($version !== null) {
      $this->file = $repo->file($this->filename, 'rev', $version);
    } else {
      $this->file = $repo->file($this->filename);
    }
    if ($this->file && $repo->history($this->filename, 1)) {
      $this->version = $this->file->rev;
    } else {
      $this->file = null;
    }
  }

  function save(MTrackChangeset $changeset) {
    $wc = self::getWC();
    $lfilename = $this->pagename;
    $suf = MTrackConfig::get('core', 'wikifilenamesuffix');
    if ($suf) {
      $lfilename .= $suf;
    }

    if (!strlen(trim($this->content))) {
      if ($wc->file_exists($lfilename)) {
        // removing
        $wc->delFile($lfilename);
      }
    } else {
      if (!$wc->file_exists($lfilename)) {
        // handle dirs
        $elements = explode('/', $lfilename);
        $accum = array();
        while (count($elements) > 1) {
          $ent = array_shift($elements);
          $accum[] = $ent;
          $base = join(DIRECTORY_SEPARATOR, $accum);
          if (!$wc->file_exists($base)) {
            if (!mkdir($wc->getDir() . DIRECTORY_SEPARATOR . $base)) {
              throw new Exception(
                  "unable to mkdir(" . $wc->getDir() .
                  DIRECTORY_SEPARATOR . "$base)");
            }
            $wc->addFile($base);
          } else if (!is_dir($wc->getDir() . DIRECTORY_SEPARATOR . $base)) {
            throw new Exception("$base is not a dir; cannot create $lfilename");
          }
        }
        file_put_contents($wc->getDir() . DIRECTORY_SEPARATOR . $lfilename,
            $this->content);
        $wc->addFile($lfilename);
      } else {
        file_put_contents($wc->getDir() . DIRECTORY_SEPARATOR . $lfilename,
            $this->content);
      }
    }
    /* use an env var to signal to the commit hook that it does not
     * need to make a changeset for this commit */
    putenv("MTRACK_WIKI_COMMIT=1");
    $wc->commit($changeset);
  }

  static function index_item($object)
  {
    list($ignore, $ident) = explode(':', $object, 2);
    $w = MTrackWikiItem::loadByPageName($ident);

    MTrackSearchDB::add("wiki:$w->pagename", array(
      'wiki' => $w->content,
      'who' => $w->who,
      ), true);
  }
  static function _get_parent_for_acl($objectid) {
    if (preg_match("/^(wiki:.*)\/([^\/]+)$/", $objectid, $M)) {
      return $M[1];
    }
    if (preg_match("/^wiki:.*$/", $objectid, $M)) {
      return 'Wiki';
    }
    return null;
  }
}

class MTrackWikiCommitListener implements IMTrackCommitListener {
  function vetoCommit($msg, $files, $actions) {
    return true;
  }

  function postCommit($msg, $files, $actions) {
    /* is this affecting the wiki? */
    $wiki = array();
    $suf = MTrackConfig::get('core', 'wikifilenamesuffix');
    foreach ($files as $name) {
      list($repo, $fname) = explode('/', $name, 2);
      if ($repo == 'wiki') {
        if ($suf && substr($fname, -strlen($suf)) == $suf) {
          $fname = substr($fname, 0, -strlen($suf));
        }
        $wiki[] = $fname;
      }
    }
    /* MTRACK_WIKI_COMMIT is set by MTrackWikiItem when it commits,
     * so we check for the absence of it to determine if mtrack has
     * recorded a changeset record */
    if (count($wiki) && getenv("MTRACK_WIKI_COMMIT") != "1") {
      /* wiki being changed outside of the MTrackWikiItem class, so
       * let's create a changeset record for the search engine to
       * pick up and index this change */
      foreach ($wiki as $name) {
        $CS = MTrackChangeset::begin("wiki:$name", $msg);
        $CS->commit();
      }
    }
    return true;
  }

  static function register() {
    $l = new MTrackWikiCommitListener;
    MTrackCommitChecker::registerListener($l);
  }

};

MTrackSearchDB::register_indexer('wiki', array('MTrackWikiItem', 'index_item'));
MTrackWikiCommitListener::register();
MTrackACL::registerAncestry('wiki', array('MTrackWikiItem', '_get_parent_for_acl'));

