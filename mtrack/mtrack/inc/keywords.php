<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackKeyword {
  public $kid;
  public $keyword;

  static function loadByWord($word)
  {
    foreach (MTrackDB::q('select kid from keywords where keyword = ?', $word)
        ->fetchAll() as $row) {
      return new MTrackKeyword($row[0]);
    }
    return null;
  }

  function __construct($id = null)
  {
    if ($id !== null) {
      list($row) = MTrackDB::q('select keyword from keywords where kid = ?',
          $id)->fetchAll();
      $this->kid = $id;
      $this->keyword = $row[0];
      return;
    }
  }

  function save(MTrackChangeset $CS)
  {
    if ($this->kid === null) {
      MTrackDB::q('insert into keywords (keyword) values (?)', $this->keyword);
      $this->kid = MTrackDB::lastInsertId('keywords', 'kid');
      $CS->add("keywords:keyword", null, $this->keyword);
    } else {
      throw new Exception("not allowed to rename keywords");
    }
  }
}

