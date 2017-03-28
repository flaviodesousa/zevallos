<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include MTRACK_INC_DIR . '/search/lucene.php';
include MTRACK_INC_DIR . '/search/solr.php';

class MTrackSearchResult {
  /** object identifier of result */
  public $objectid;
  /** result ranking; higher is more relevant */
  public $score;
  /** excerpt of matching text */
  public $excerpt;

  /* some implementations may need the caller to provide the context
   * text; the default just returns what is there */
  function getExcerpt($text) {
    return $this->excerpt;
  }
}

interface IMTrackSearchEngine {
  public function setBatchMode();
  public function commit($optimize = false);
  public function add($object, $fields, $replace = false);
  /** returns an array of MTrackSearchResult objects corresponding
   * to matches to the supplied query string */
  public function search($query);
}

class MTrackSearchDB {
  static $index = null;
  static $engine = null;

  static function getEngine() {
    if (self::$engine === null) {
      $name = MTrackConfig::get('core', 'search_engine');
      if (!$name) $name = 'MTrackSearchEngineLucene';
      self::$engine = new $name;
    }
    return self::$engine;
  }

  /* functions that can perform indexing */
  static $funcs = array();

  static function register_indexer($id, $func)
  {
    self::$funcs[$id] = $func;
  }

  static function index_object($id)
  {
    $key = $id;
    while (strlen($key)) {
      if (isset(self::$funcs[$key])) {
        break;
      }
      $new_key = preg_replace('/:[^:]+$/', '', $key);
      if ($key == $new_key) {
        break;
      }
      $key = $new_key;
    }

    if (isset(self::$funcs[$key])) {
      $func = self::$funcs[$key];
      return call_user_func($func, $id);
    }
    return false;
  }

  static function get() {
    return self::getEngine()->getIdx();
  }

  static function setBatchMode() {
    self::getEngine()->setBatchMode();
  }

  static function commit($optimize = false) {
    self::getEngine()->commit($optimize);
  }

  static function add($object, $fields, $replace = false) {
    self::getEngine()->add($object, $fields, $replace);
  }

  static function search($query) {
    return self::getEngine()->search($query);
  }
}
