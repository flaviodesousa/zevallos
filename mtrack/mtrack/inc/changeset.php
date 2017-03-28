<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackChangeset {
  public $cid = null;
  public $who = null;
  public $object = null;
  public $reason = null;
  public $when = null;
  private $count = 0;

  /* used by the import script to allow batching */
  static $use_txn = true;

  static function get($cid) {
    foreach (MTrackDB::q('select * from changes where cid = ?', $cid)
        ->fetchAll() as $row) {
      $CS = new MTrackChangeset;
      $CS->cid = $cid;
      $CS->who = $row['who'];
      $CS->object = $row['object'];
      $CS->reason = $row['reason'];
      $CS->when = $row['changedate'];
      return $CS;
    }
    throw new Exception("invalid changeset id $cid");
  }

  static function begin($object, $reason = '', $when = null) {
    $CS = new MTrackChangeset;

    $db = MTrackDB::get();
    if (self::$use_txn) {
      $db->beginTransaction();
    }

    $CS->who = MTrackAuth::whoami();
    $CS->object = $object;
    $CS->reason = $reason;

    if ($when === null) {
      $CS->when = MTrackDB::unixtime(time());
      $q = MTrackDB::q(
        "INSERT INTO changes (who, object, reason, changedate)
          values (?,?,?,?)",
        $CS->who, $CS->object, $CS->reason, $CS->when);
    } else {
      $CS->when = MTrackDB::unixtime($when);
      $q = MTrackDB::q(
        "INSERT INTO changes (who, object, reason, changedate)
        values (?,?,?,?)",
        $CS->who, $CS->object, $CS->reason, $CS->when);
    }

    $CS->cid = MTrackDB::lastInsertId('changes', 'cid');

    return $CS;
  }

  function commit()
  {
    if ($this->count == 0) {
//      throw new Exception("no changes were made as part of this changeset");
    }
    if (self::$use_txn) {
      $db = MTrackDB::get();
      $db->commit();
    }
  }

  function addentry($fieldname, $action, $old, $value = null)
  {
    MTrackDB::q("INSERT INTO change_audit 
      (cid, fieldname, action, oldvalue, value)
      VALUES (?, ?, ?, ?, ?)",
      $this->cid, $fieldname, $action, $old, $value);
    $this->count++;
  }

  function add($fieldname, $old, $new)
  {
    if ($old == $new) {
      return;
    }
    if (!strlen($old)) {
      $this->addentry($fieldname, 'set', $old, $new);
      return;
    }
    if (!strlen($new)) {
      $this->addentry($fieldname, 'deleted', $old, $new);
      return;
    }
    $this->addentry($fieldname, 'changed', $old, $new);
  }

  function setObject($object)
  {
    $this->object = $object;
    MTrackDB::q('update changes set object = ? where cid = ?',
      $this->object, $this->cid);
  }

  function setReason($reason)
  {
    $this->reason = $reason;
    MTrackDB::q('update changes set reason = ? where cid = ?',
      $this->reason, $this->cid);
  }

}
