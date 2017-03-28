<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackEnumeration {
  public $tablename;
  protected $fieldname;
  protected $fieldvalue;

  public $name = null;
  public $value = null;
  public $deleted = null;
  public $new = true;

  function enumerate($all = false) {
    $res = array();
    if ($all) {
      foreach (MTrackDB::q(sprintf("select %s, %s, deleted from %s order by %s",
            $this->fieldname, $this->fieldvalue, $this->tablename,
            $this->fieldvalue))
            ->fetchAll(PDO::FETCH_NUM)
          as $row) {
        $res[$row[0]] = array(
          'name' => $row[0],
          'value' => $row[1],
          'deleted' => $row[2] == '1' ? true : false
        );
      }
    } else {
      foreach (MTrackDB::q(sprintf("select %s from %s where deleted != '1'",
              $this->fieldname, $this->tablename))->fetchAll(PDO::FETCH_NUM)
          as $row) {
        $res[$row[0]] = $row[0];
      }
    }
    return $res;
  }

  function __construct($name = null) {
    if ($name !== null) {
      list($row) = MTrackDB::q(sprintf(
          "select %s, deleted from %s where %s = ?",
          $this->fieldvalue, $this->tablename, $this->fieldname),
          $name)
          ->fetchAll();
      if (isset($row[0])) {
        $this->name = $name;
        $this->value = $row[0];
        $this->deleted = $row[1];
        $this->new = false;
        return;
      }
      throw new Exception("unable to find $this->tablename with name = $name");
    }

    $this->deleted = false;
  }

  function save(MTrackChangeset $CS) {
    if ($this->new) {
      MTrackDB::q(sprintf('insert into %s (%s, %s, deleted) values (?, ?, ?)',
        $this->tablename, $this->fieldname, $this->fieldvalue),
            $this->name, $this->value, (int)$this->deleted);
      $old = null;
    } else {
      list($row) = MTrackDB::q(
        sprintf('select %s, deleted from %s where %s = ?',
          $this->fieldname, $this->tablename, $this->fieldvalue),
          $this->name)->fetchAll();
      $old = $row[0];
      MTrackDB::q(sprintf('update %s set %s = ?, deleted = ? where %s = ?',
        $this->tablename, $this->fieldvalue, $this->fieldname),
            $this->value, (int)$this->deleted, $this->name);
    }
    $CS->add($this->tablename . ":" . $this->name . ":" . $this->fieldvalue,
      $old, $this->value);

  }
}

class MTrackTicketState extends MTrackEnumeration {
  public $tablename = 'ticketstates';
  protected $fieldname = 'statename';
  protected $fieldvalue = 'ordinal';

  static function loadByName($name) {
    return new MTrackTicketState($name);
  }
}


class MTrackPriority extends MTrackEnumeration {
  public $tablename = 'priorities';
  protected $fieldname = 'priorityname';
  protected $fieldvalue = 'value';

  static function loadByName($name) {
    return new MTrackPriority($name);
  }
}

class MTrackSeverity extends MTrackEnumeration {
  public $tablename = 'severities';
  protected $fieldname = 'sevname';
  protected $fieldvalue = 'ordinal';

  static function loadByName($name) {
    return new MTrackSeverity($name);
  }
}

class MTrackResolution extends MTrackEnumeration {
  public $tablename = 'resolutions';
  protected $fieldname = 'resname';
  protected $fieldvalue = 'ordinal';

  static function loadByName($name) {
    return new MTrackResolution($name);
  }
}

class MTrackClassification extends MTrackEnumeration {
  public $tablename = 'classifications';
  protected $fieldname = 'classname';
  protected $fieldvalue = 'ordinal';

  static function loadByName($name) {
    return new MTrackClassification($name);
  }
}

class MTrackComponent {
  public $compid = null;
  public $name = null;
  public $deleted = null;
  protected $projects = null;
  protected $origprojects = null;

  static function loadById($id) {
    return new MTrackComponent($id);
  }

  static function loadByName($name) {
    $rows = MTrackDB::q('select compid from components where name = ?',
      $name)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (isset($rows[0])) {
      return self::loadById($rows[0]);
    }
    return null;
  }

  function __construct($id = null) {
    if ($id !== null) {
      list($row) = MTrackDB::q(
                    'select name, deleted from components where compid = ?',
                    $id)->fetchAll();
      if (isset($row[0])) {
        $this->compid = $id;
        $this->name = $row[0];
        $this->deleted = $row[1];
        return;
      }
      throw new Exception("unable to find component with id = $id");
    }
    $this->deleted = false;
  }

  function getProjects() {
    if ($this->origprojects === null) {
      $this->origprojects = array();
      foreach (MTrackDB::q('select projid from components_by_project where compid = ? order by projid', $this->compid) as $row) {
        $this->origprojects[] = $row[0];
      }
      $this->projects = $this->origprojects;
    }
    return $this->projects;
  }

  function setProjects($projlist) {
    $this->projects = $projlist;
  }

  function save(MTrackChangeset $CS) {
    if ($this->compid) {
      list($row) = MTrackDB::q(
                    'select name, deleted from components where compid = ?',
                    $id)->fetchAll();
      $old = $row;
      MTrackDB::q(
          'update components set name = ?, deleted = ? where compid = ?',
          $this->name, (int)$this->deleted, $this->compid);
    } else {
      MTrackDB::q('insert into components (name, deleted) values (?, ?)',
        $this->name, (int)$this->deleted);
      $this->compid = MTrackDB::lastInsertId('components', 'compid');
      $old = null;
    }
    $CS->add("component:" . $this->compid . ":name", $old['name'], $this->name);
    $CS->add("component:" . $this->compid . ":deleted", $old['deleted'], $this->deleted);
    if ($this->projects !== $this->origprojects) {
      $old = is_array($this->origprojects) ?
              join(",", $this->origprojects) : '';
      $new = is_array($this->projects) ?
              join(",", $this->projects) : '';
      MTrackDB::q('delete from components_by_project where compid = ?',
          $this->compid);
      if (is_array($this->projects)) {
        foreach ($this->projects as $pid) {
          MTrackDB::q(
            'insert into components_by_project (compid, projid) values (?, ?)',
            $this->compid, $pid);
        }
      }
      $CS->add("component:$this->compid:projects", $old, $new);
    }
  }
}

class MTrackProject {
  public $projid = null;
  public $ordinal = 5;
  public $name = null;
  public $shortname = null;
  public $notifyemail = null;

  static function loadById($id) {
    return new MTrackProject($id);
  }

  static function loadByName($name) {
    list($row) = MTrackDB::q('select projid from projects where shortname = ?',
      $name)->fetchAll();
    if (isset($row[0])) {
      return self::loadById($row[0]);
    }
    return null;
  }

  function __construct($id = null) {
    if ($id !== null) {
      list($row) = MTrackDB::q(
                    'select * from projects where projid = ?',
                    $id)->fetchAll();
      if (isset($row[0])) {
        $this->projid = $row['projid'];
        $this->ordinal = $row['ordinal'];
        $this->name = $row['name'];
        $this->shortname = $row['shortname'];
        $this->notifyemail = $row['notifyemail'];
        return;
      }
      throw new Exception("unable to find project with id = $id");
    }
  }

  function save(MTrackChangeset $CS) {
    if ($this->projid) {
      list($row) = MTrackDB::q(
                    'select * from projects where projid = ?',
                    $this->projid)->fetchAll();
      $old = $row;
      MTrackDB::q(
          'update projects set ordinal = ?, name = ?, shortname = ?,
            notifyemail = ? where projid = ?',
          $this->ordinal, $this->name, $this->shortname,
          $this->notifyemail, $this->projid);
    } else {
      MTrackDB::q('insert into projects (ordinal, name,
          shortname, notifyemail) values (?, ?, ?, ?)',
        $this->ordinal, $this->name, $this->shortname,
        $this->notifyemail);
      $this->projid = MTrackDB::lastInsertId('projects', 'projid');
      $old = null;
    }
    $CS->add("project:" . $this->projid . ":name", $old['name'], $this->name);
    $CS->add("project:" . $this->projid . ":ordinal", $old['ordinal'], $this->ordinal);
    $CS->add("project:" . $this->projid . ":shortname", $old['shortname'], $this->shortname);
    $CS->add("project:" . $this->projid . ":notifyemail", $old['notifyemail'], $this->notifyemail);
  }

  function _adjust_ticket_link($M) {
    $tktlimit = MTrackConfig::get('trac_import', "max_ticket:$this->shortname");
    if ($M[1] <= $tktlimit) {
      return "#$this->shortname$M[1]";
    }
    return $M[0];
  }

  function adjust_links($reason, $use_ticket_prefix)
  {
    if (!$use_ticket_prefix) {
      return $reason;
    }

    $tktlimit = MTrackConfig::get('trac_import', "max_ticket:$this->shortname");
    if ($tktlimit !== null) {
      $reason = preg_replace_callback('/#(\d+)/',
        array($this, '_adjust_ticket_link'), $reason);
    } else {
//      don't do this if the number is outside the valid ranges
//      may need to be clever about this during trac imports
//      $reason = preg_replace('/#(\d+)/', "#$this->shortname\$1", $reason);
    }
// FIXME: this and the above need to be more intelligent
    $reason = preg_replace('/\[(\d+)\]/', "[$this->shortname\$1]", $reason);
    return $reason;
  }
}

/* The listener protocol is to return true if all is good,
 * or to return either a string or an array of strings that
 * detail why a change is not allowed to proceed */
interface IMTrackIssueListener {
  function vetoMilestone(MTrackIssue $issue,
            MTrackMilestone $ms, $assoc = true);
  function vetoKeyword(MTrackIssue $issue,
            MTrackKeyword $kw, $assoc = true);
  function vetoComponent(MTrackIssue $issue,
            MTrackComponent $comp, $assoc = true);
  function vetoProject(MTrackIssue $issue,
            MTrackProject $proj, $assoc = true);
  function vetoComment(MTrackIssue $issue, $comment);
  function vetoSave(MTrackIssue $issue, $oldFields);

  function augmentFormFields(MTrackIssue $issue, &$fieldset);
  function applyPOSTData(MTrackIssue $issue, $data);
  function augmentSaveParams(MTrackIssue $issue, &$params);
  function augmentIndexerFields(MTrackIssue $issue, &$idx);
}

class MTrackVetoException extends Exception {
  public $reasons;

  function __construct($reasons) {
    $this->reasons = $reasons;
    parent::__construct(join("\n", $reasons));
  }
}

class MTrackIssue {
  public $tid = null;
  public $nsident = null;
  public $summary = null;
  public $description = null;
  public $created = null;
  public $updated = null;
  public $owner = null;
  public $priority = null;
  public $severity = null;
  public $classification = null;
  public $resolution = null;
  public $status = null;
  public $estimated = null;
  public $spent = null;
  public $changelog = null;
  public $cc = null;
  protected $components = null;
  protected $origcomponents = null;
  protected $milestones = null;
  protected $origmilestones = null;
  protected $comments_to_add = array();
  protected $keywords = null;
  protected $origkeywords = null;
  protected $effort = array();

  static $_listeners = array();

  static function loadById($id) {
    try {
      return new MTrackIssue($id);
    } catch (Exception $e) {
    }
    return null;
  }

  static function loadByNSIdent($id) {
    static $cache = array();
    if (!isset($cache[$id])) {
      $ids = MTrackDB::q('select tid from tickets where nsident = ?', $id)
            ->fetchAll(PDO::FETCH_COLUMN, 0);
      if (count($ids) == 1) {
        $cache[$id] = $ids[0];
      } else {
        return null;
      }
    }
    return new MTrackIssue($cache[$id]);
  }

  static function registerListener(IMTrackIssueListener $l)
  {
    self::$_listeners[] = $l;
  }

  function __construct($tid = null) {
    if ($tid === null) {
      $this->components = array();
      $this->origcomponents = array();
      $this->milestones = array();
      $this->origmilestones = array();
      $this->keywords = array();
      $this->origkeywords = array();
      $this->status = 'new';

      foreach (array('classification', 'severity', 'priority') as $f) {
        $this->$f = MTrackConfig::get('ticket', "default.$f");
      }
    } else {
      $data =  MTrackDB::q('select * from tickets where tid = ?',
                        $tid)->fetchAll();
      if (isset($data[0])) {
        $row = $data[0];
      } else {
        $row = null;
      }
      if (!is_array($row)) {
        throw new Exception("no such issue $tid");
      }
      foreach ($row as $k => $v) {
        $this->$k = $v;
      }
    }
  }

  function applyPOSTData($data) {
    foreach (self::$_listeners as $l) {
      $l->applyPOSTData($this, $data);
    }
  }

  function augmentFormFields(&$FIELDSET) {
    foreach (self::$_listeners as $l) {
      $l->augmentFormFields($this, $FIELDSET);
    }
  }
  function augmentIndexerFields(&$idx) {
    foreach (self::$_listeners as $l) {
      $l->augmentIndexerFields($this, $idx);
    }
  }
  function augmentSaveParams(&$params) {
    foreach (self::$_listeners as $l) {
      $l->augmentSaveParams($this, $params);
    }
  }

  function checkVeto()
  {
    $args = func_get_args();
    $method = array_shift($args);
    $veto = array();

    foreach (self::$_listeners as $l) {
      $v = call_user_func_array(array($l, $method), $args);
      if ($v !== true) {
        $veto[] = $v;
      }
    }
    if (count($veto)) {
      $reasons = array();
      foreach ($veto as $r) {
        if (is_array($r)) {
          foreach ($r as $m) {
            $reasons[] = $m;
          }
        } else {
          $reasons[] = $r;
        }
      }
      throw new MTrackVetoException($reasons);
    }
  }

  function save(MTrackChangeset $CS)
  {
    $db = MTrackDB::get();
    $reindex = false;

    if ($this->tid === null) {
      $this->created = $CS->cid;
      $oldrow = array();
      $reindex = true;
    } else {
      list($oldrow) = MTrackDB::q('select * from tickets where tid = ?',
                        $this->tid)->fetchAll();
    }

    $this->checkVeto('vetoSave', $this, $oldrow);

    $this->updated = $CS->cid;

    $params = array(
      'summary' => $this->summary,
      'description' => $this->description,
      'created' => $this->created,
      'updated' => $this->updated,
      'owner' => $this->owner,
      'changelog' => $this->changelog,
      'priority' => $this->priority,
      'severity' => $this->severity,
      'classification' => $this->classification,
      'resolution' => $this->resolution,
      'status' => $this->status,
      'estimated' => (float)$this->estimated,
      'spent' => (float)$this->spent,
      'nsident' => $this->nsident,
      'cc' => $this->cc,
    );

    $this->augmentSaveParams($params);

    if ($this->tid === null) {
      $sql = 'insert into tickets ';
      $keys = array();
      $values = array();

      $new_tid = new OmniTI_Util_UUID;
      $new_tid = $new_tid->toRFC4122String(false);

      $keys[] = "tid";
      $values[] = "'$new_tid'";

      foreach ($params as $key => $value) {
        $keys[] = $key;
        $values[] = ":$key";
      }

      $sql .= "(" . join(', ', $keys) . ") values (" .
              join(', ', $values) . ")";
    } else {
      $sql = 'update tickets set ';
      $values = array();
      foreach ($params as $key => $value) {
        $values[] = "$key = :$key";
      }
      $sql .= join(', ', $values) . " where tid = :tid";

      $params['tid'] = $this->tid;
    }

    $q = $db->prepare($sql);
    $q->execute($params);

    if ($this->tid === null) {
      $this->tid = $new_tid;
      $created = true;
    } else {
      $created = false;
    }

    foreach ($params as $key => $value) {
      if ($key == 'created' || $key == 'updated' || $key == 'tid') {
        continue;
      }
      if ($key == 'changelog' || $key == 'description' || $key == 'summary') {
        if (!isset($oldrow[$key]) || $oldrow[$key] != $value) {
          $reindex = true;
        }
      }
      if (!isset($oldrow[$key])) {
        $oldrow[$key] = null;
      }
      $CS->add("ticket:$this->tid:$key", $oldrow[$key], $value);
    }

    $this->compute_diff($CS, 'components', 'ticket_components', 'compid',
        $this->components, $this->origcomponents);
    $this->compute_diff($CS, 'keywords', 'ticket_keywords', 'kid',
        $this->keywords, $this->origkeywords);
    $this->compute_diff($CS, 'milestones', 'ticket_milestones', 'mid',
        $this->milestones, $this->origmilestones);

    foreach ($this->comments_to_add as $text) {
      $CS->add("ticket:$this->tid:@comment", null, $text);
    }

    foreach ($this->effort as $effort) {
      MTrackDB::q('insert into effort (tid, cid, expended, remaining)
        values (?, ?, ?, ?)',
        $this->tid, $CS->cid, $effort[0], $effort[1]);
    }
    $this->effort = array();
  }

  static function index_issue($object)
  {
    list($ignore, $ident) = explode(':', $object, 2);
    $i = MTrackIssue::loadById($ident);
    if (!$i) return;
    echo "Ticket #$i->nsident\n";

    $CS = MTrackChangeset::get($i->updated);
    $CSC = MTrackChangeset::get($i->created);

    $kw = join(' ', array_values($i->getKeywords()));
    $idx = array(
            'summary' => $i->summary,
            'description' => $i->description,
            'changelog' => $i->changelog,
            'keyword' => $kw,
            'stored:date' => $CS->when,
            'who' => $CS->who,
            'creator' => $CSC->who,
            'stored:created' => $CSC->when,
            'owner' => $i->owner
            );
    $i->augmentIndexerFields($idx);
    MTrackSearchDB::add("ticket:$i->tid", $idx, true);

    foreach (MTrackDB::q('select value, changedate, who from
        change_audit left join changes using (cid) where fieldname = ?',
        "ticket:$ident:@comment") as $row) {
      list($text, $when, $who) = $row;
      $start = time();
      $id = sha1($text);
      $elapsed = time() - $start;
      if ($elapsed > 4) {
        echo "  - comment $who $when took $elapsed to hash\n";
      }
      $start = time();
      if (strlen($text) > 8192) {
        // A huge paste into a ticket
        $text = substr($text, 0, 8192);
      }
      MTrackSearchDB::add("ticket:$ident:comment:$id", array(
        'description' => $text,
        'stored:date' => $when,
        'who' => $who,
      ), true);

      $elapsed = time() - $start;
      if ($elapsed > 4) {
        echo "  - comment $who $when took $elapsed to index\n";
      }
    }
  }

  private function compute_diff(MTrackChangeset $CS, $label,
        $tablename, $keyname, $current, $orig) {
    if (!is_array($current)) {
      $current = array();
    }
    if (!is_array($orig)) {
      $orig = array();
    }
    $added = array_keys(array_diff_key($current, $orig));
    $removed = array_keys(array_diff_key($orig, $current));

    $db = MTrackDB::get();
    $ADD = $db->prepare(
      "insert into $tablename (tid, $keyname) values (?, ?)");
    $DEL = $db->prepare(
      "delete from $tablename where tid = ? AND $keyname = ?");
    foreach ($added as $key) {
      $ADD->execute(array($this->tid, $key));
    }
    foreach ($removed as $key) {
      $DEL->execute(array($this->tid, $key));
    }
    if (count($added) + count($removed)) {
      $old = join(',', array_keys($orig));
      $new = join(',', array_keys($current));
      $CS->add(
        "ticket:$this->tid:@$label", $old, $new);
    }
  }
  function getComponents()
  {
    if ($this->components === null) {
      $comps = MTrackDB::q('select tc.compid, name from ticket_components tc left join components using (compid) where tid = ?', $this->tid)->fetchAll();
      $this->origcomponents = array();
      foreach ($comps as $row) {
        $this->origcomponents[$row[0]] = $row[1];
      }
      $this->components = $this->origcomponents;
    }
    return $this->components;
  }

  private function resolveComponent($comp)
  {
    if ($comp instanceof MTrackComponent) {
      return $comp;
    }
    if (ctype_digit($comp)) {
      return MTrackComponent::loadById($comp);
    }
    return MTrackComponent::loadByName($comp);
  }

  function assocComponent($comp)
  {
    $comp = $this->resolveComponent($comp);
    $this->getComponents();
    $this->checkVeto('vetoComponent', $this, $comp, true);
    $this->components[$comp->compid] = $comp->name;
  }

  function dissocComponent($comp)
  {
    $comp = $this->resolveComponent($comp);
    $this->getComponents();
    $this->checkVeto('vetoComponent', $this, $comp, false);
    unset($this->components[$comp->compid]);
  }

  function getMilestones()
  {
    if ($this->milestones === null) {
      $comps = MTrackDB::q('select tc.mid, name from ticket_milestones tc left join milestones using (mid) where tid = ? order by duedate, name', $this->tid)->fetchAll();
      $this->origmilestones = array();
      foreach ($comps as $row) {
        $this->origmilestones[$row[0]] = $row[1];
      }
      $this->milestones = $this->origmilestones;
    }
    return $this->milestones;
  }

  private function resolveMilestone($ms)
  {
    if ($ms instanceof MTrackMilestone) {
      return $ms;
    }
    if (ctype_digit($ms)) {
      return MTrackMilestone::loadById($ms);
    }
    return MTrackMilestone::loadByName($ms);
  }

  function assocMilestone($M)
  {
    $ms = $this->resolveMilestone($M);
    if ($ms === null) {
      throw new Exception("unable to resolve milestone $M");
    }
    $this->getMilestones();
    $this->checkVeto('vetoMilestone', $this, $ms, true);
    $this->milestones[$ms->mid] = $ms->name;
  }

  function dissocMilestone($M)
  {
    $ms = $this->resolveMilestone($M);
    if ($ms === null) {
      throw new Exception("unable to resolve milestone $M");
    }
    $this->getMilestones();
    $this->checkVeto('vetoMilestone', $this, $ms, false);
    unset($this->milestones[$ms->mid]);
  }

  function addComment($comment)
  {
    $comment = trim($comment);
    if (strlen($comment)) {
      $this->checkVeto('vetoComment', $this, $comment);
      $this->comments_to_add[] = $comment;
    }
  }

  private function resolveKeyword($kw)
  {
    if ($kw instanceof MTrackKeyword) {
      return $kw;
    }
    $k = MTrackKeyword::loadByWord($kw);
    if ($k === null) {
      if (ctype_digit($kw)) {
        return MTrackKeyword::loadById($kw);
      }
      throw new Exception("unknown keyword $kw");
    }
    return $k;
  }

  function assocKeyword($kw)
  {
    $kw = $this->resolveKeyword($kw);
    $this->getKeywords();
    $this->checkVeto('vetoKeyword', $this, $kw, true);
    $this->keywords[$kw->kid] = $kw->keyword;
  }

  function dissocKeyword($kw)
  {
    $kw = $this->resolveKeyword($kw);
    $this->getKeywords();
    $this->checkVeto('vetoKeyword', $this, $kw, false);
    unset($this->keywords[$kw->kid]);
  }

  function getKeywords()
  {
    if ($this->keywords === null) {
      $comps = MTrackDB::q('select tc.kid, keyword from ticket_keywords tc left join keywords using (kid) where tid = ?', $this->tid)->fetchAll();
      $this->origkeywords = array();
      foreach ($comps as $row) {
        $this->origkeywords[$row[0]] = $row[1];
      }
      $this->keywords = $this->origkeywords;
    }
    return $this->keywords;
  }

  function addEffort($amount, $revised = null)
  {
    $diff = null;
    if ($revised !== null) {
      $diff = $revised - $this->estimated;
      $this->estimated = $revised;
    }
    $this->effort[] = array($amount, $diff);
    $this->spent += $amount;
  }

  function close()
  {
    $this->status = 'closed';
    $this->addEffort(0, 0);
  }

  function isOpen()
  {
    switch ($this->status) {
      case 'closed':
        return false;
      default:
        return true;
    }
  }

  function reOpen()
  {
    $this->status = 'reopened';
    $this->resolution = null;
  }

}

MTrackSearchDB::register_indexer('ticket', array('MTrackIssue', 'index_issue'));
MTrackACL::registerAncestry('enum', 'Enumerations');
MTrackACL::registerAncestry("component", 'Components');
MTrackACL::registerAncestry("project", 'Projects');
MTrackACL::registerAncestry("ticket", "Tickets");
MTrackWatch::registerEventTypes('ticket', array(
  'ticket' => 'Tickets'
));
