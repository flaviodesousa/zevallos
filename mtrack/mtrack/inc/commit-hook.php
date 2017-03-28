<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

interface IMTrackCommitHookBridge {
  function enumChangedOrModifiedFileNames();
  function getFileStream($filename);
  function getCommitMessage();
  /* returns a tracklink describing the change (eg: [123]) */
  function getChangesetDescriptor();
}

class MTrackCommitHookChangeEvent {
  /** Revision or changeset identifier for this particular item,
   * in wiki syntax */
  public $rev;

  /** commit message associated with this revision */
  public $changelog;

  /** who committed this revision */
  public $changeby;

  /** when this revision was committed */
  public $ctime;

  /** a hash value that will be consistent when being merged from multiple
   * repos */
  public $hash;
}

interface IMTrackCommitHookBridge2 extends IMTrackCommitHookBridge {
  /* returns an array; each element is an MTrackCommitHookChangeEvent */
  function getChanges();
}

/* The listener protocol is to return true if all is good,
 * or to return either a string or an array of strings that
 * detail why a change is not allowed to proceed */
interface IMTrackCommitListener {
  function vetoCommit($msg, $files, $actions);
  function postCommit($msg, $files, $actions);
}

class MTrackCommitCheck_NoEmptyLogMessage implements IMTrackCommitListener {
  function __construct() {
    MTrackCommitChecker::registerListener($this);
  }

  function vetoCommit($msg, $files, $actions) {
    if (!strlen(trim($msg))) {
      return "Empty log messages are not allowed.\n";
    }
    return true;
  }

  function postCommit($msg, $files, $actions) {
    return true;
  }
}

class MTrackCommitCheck_RequiresTimeReference implements IMTrackCommitListener {
  function __construct() {
    MTrackCommitChecker::registerListener($this);
  }

  function vetoCommit($msg, $files, $actions) {
    $spent = false;
    foreach ($actions as $act) {
      if (isset($act[2])) {
        return true;
      }
    }
    return "You must include at least one ticket and time reference in your\n".
      "commit message, using the \"refs #123 (spent 2.5)\" notation.\n"
      ;
  }

  function postCommit($msg, $files, $actions) {
    return true;
  }
}

class MTrackCommitChecker {
  static $fileChecks = array(
    'php' => 'checkPHP',
  );
  static $listeners = array();
  var $repo;

  static function registerListener(IMTrackCommitListener $l)
  {
    self::$listeners[] = $l;
  }

  function checkVeto()
  {
    $args = func_get_args();
    $method = array_shift($args);
    $reasons = array();

    foreach (self::$listeners as $l) {
      $v = call_user_func_array(array($l, $method), $args);
      if ($v !== true) {
        if ($v === null || $v === false) {
          $reasons[] = sprintf("%s:%s() returned %s",
            get_class($l), $method, $v === null ? 'null' : 'false');
        } elseif (is_array($v)) {
          foreach ($v as $m) {
            $reasons[] = $m;
          }
        } else {
          $reasons[] = $v;
        }
      }
    }
    if (count($reasons)) {
      throw new MTrackVetoException($reasons);
    }
  }

  function __construct($repo) {
    $this->repo = $repo;
  }

  function parseCommitMessage($msg) {
    // Parse the commit message and look for commands;
    // returns each recognized command and its args in an array

    $close = array('resolves', 'resolved', 'close', 'closed',
                   'closes', 'fix', 'fixed', 'fixes');
    $refs = array('addresses', 'references', 'referenced',
                  'refs', 'ref', 'see', 're');

    $cmds = join('|', $close) . '|' . join('|', $refs);
    $timepat = '(?:\s*\((?:spent|sp)\s*(-?[0-9]*(?:\.[0-9]+)?)\s*(?:hours?|hrs)?\s*\))?';
    $tktref = "(?:#|(?:(?:ticket|issue|bug):?\s*))([a-z]*[0-9]+)$timepat";

    $pat = "(?P<action>(?:$cmds))\s*(?P<ticket>$tktref(?:(?:[, &]*|\s+and\s+)$tktref)*)";

    $M = array();
    $actions = array();

    if (preg_match_all("/$pat/smi", $msg, $M, PREG_SET_ORDER)) {
      foreach ($M as $match) {
        if (in_array($match['action'], $close)) {
          $action = 'close';
        } else {
          $action = 'ref';
        }
        $tickets = array();
        $T = array();
        if (preg_match_all("/$tktref/smi", $match['ticket'],
            $T, PREG_SET_ORDER)) {

          foreach ($T as $tmatch) {
            if (isset($tmatch[2])) {
              // [ action, ticket, spent ]
              $actions[] = array($action, $tmatch[1], $tmatch[2]);
            } else {
              // [ action, ticket ]
              $actions[] = array($action, $tmatch[1]);
            }
          }
        }
      }
    }
    return $actions;
  }

  function preCommit(IMTrackCommitHookBridge $bridge) {
    MTrackACL::requireAllRights("repo:" . $this->repo->repoid, 'commit');
    $files = $bridge->enumChangedOrModifiedFileNames();
    $fqfiles = array();
    foreach ($files as $filename) {
      $fqfiles[] = $this->repo->shortname . '/' . $filename;
      $pi = pathinfo($filename);
      if (isset(self::$fileChecks[$pi['extension']])) {
        $lint = self::$fileChecks[$pi['extension']];
        $fp = $bridge->getFileStream($filename);
        $this->$lint($filename, $fp);
        $fp = null;
      }
    }
    $changes = $this->_getChanges($bridge);
    foreach ($changes as $c) {
      $log = $c->changelog;
      $actions = $this->parseCommitMessage($log);

      // check permissions on the tickets
      $tickets = array();
      foreach ($actions as $act) {
        $tkt = $act[1];
        $tickets[$tkt] = $tkt;
      }
      $reasons = array();
      foreach ($tickets as $tkt) {
        if (strlen($tkt) == 32) {
          $T = MTrackIssue::loadById($tkt);
        } else {
          $T = MTrackIssue::loadByNSIdent($tkt);
        }

        if ($T === null) {
          $reasons[] = "#$tkt is not a valid ticket\n";
          continue;
        }

        $accounted = false;
        if ($c->hash !== null) {
          list($accounted) = MTrackDB::q(
              'select count(hash) from ticket_changeset_hashes
              where tid = ? and hash = ?',
            $T->tid, $c->hash)->fetchAll(PDO::FETCH_COLUMN, 0);
          if ($accounted) {
            continue;
          }
        }

        if (!MTrackACL::hasAllRights("ticket:$T->tid", "modify")) {
          $reasons[] = MTrackAuth::whoami() . " does not have permission to modify #$tkt\n";
        } else if (!$T->isOpen()) {
          $reasons[] = " ** #$tkt is already closed.\n ** You must either re-open it (if it has not already shipped)\n ** or open a new ticket to track this issue\n";
        }
      }
    }
    if (count($reasons) > 0) {
      throw new MTrackVetoException($reasons);
    }
    $this->checkVeto('vetoCommit', $log, $files, $actions);
  }

  private function _getChanges(IMTrackCommitHookBridge $bridge)
  {
    $changes = array();
    if ($bridge instanceof IMTrackCommitHookBridge2) {
      $changes = $bridge->getChanges();
    } else {
      $c = new MTrackCommitHookChangeEvent;
      $c->rev = $bridge->getChangesetDescriptor();
      $c->changelog = $bridge->getCommitMessage();
      $c->changeby = MTrackAuth::whoami();
      $c->ctime = time();
      $changes[] = $c;
    }
    return $changes;
  }

  function postCommit(IMTrackCommitHookBridge $bridge)
  {
    $files = $bridge->enumChangedOrModifiedFileNames();
    $fqfiles = array();
    foreach ($files as $filename) {
      $fqfiles[] = $this->repo->shortname . '/' . $filename;
    }

    // build up overall picture of what needs to be applied to tickets
    $changes = $this->_getChanges($bridge);

    // Deferred by tid
    $deferred = array();
    $T_by_tid = array();
    $hashed = array();

    // For correct attribution of spent time
    $spent_by_tid_by_user = array();

    // Changes that didn't ref a ticket; we want to show something
    // on the timeline
    $no_ticket = array();

    $me = mtrack_canon_username(MTrackAuth::whoami());

    foreach ($changes as $c) {
      $tickets = array();
      $log = $c->changelog;

      $actions = $this->parseCommitMessage($log);
      foreach ($actions as $act) {
        $what = $act[0];
        $tkt = $act[1];
        $tickets[$tkt][$what] = $what;
        if (isset($act[2])) {
          $tickets[$tkt]['spent'] += $act[2];
        }
      }
      if (count($tickets) == 0) {
        $no_ticket[] = $c;
        continue;
      }
      // apply changes to tickets
      foreach ($tickets as $tkt => $act) {
        if (strlen($tkt) == 32 && isset($T_by_tid[$tkt])) {
          $T = $T_by_tid[$tkt];
        } else {
          if (strlen($tkt) == 32) {
            $T = MTrackIssue::loadById($tkt);
          } else {
            $T = MTrackIssue::loadByNSIdent($tkt);
          }
          $T_by_tid[$T->tid] = $T;
        }

        $accounted = false;
        if ($c->hash !== null) {
          if (isset($hashed[$T->tid][$c->hash])) {
            $accounted = true;
          } else {
            list($accounted) = MTrackDB::q(
              'select count(hash) from ticket_changeset_hashes
              where tid = ? and hash = ?',
              $T->tid, $c->hash)->fetchAll(PDO::FETCH_COLUMN, 0);
            if (!$accounted) {
              $hashed[$T->tid][$c->hash] = $c->hash;
            }
          }
        }

        if ($accounted) {
          $deferred[$T->tid]['comments'][] =
            "(In $c->rev) merged to [repo:" . 
              $this->repo->getBrowseRootName() . "]";
          continue;
        }
        $log = "(In " . $c->rev . ") ";
        if ($c->changeby != $me) {
          $log .= " (on behalf of [user:$c->changeby]) ";
        }
        $log .= $c->changelog;
        $deferred[$T->tid]['comments'][] = $log;
        if (isset($act['spent']) && $c->changeby != $me) {
          $spent_by_tid_by_user[$T->tid][$c->changeby][] = $act['spent'];
          unset($act['spent']);
        }
        $deferred[$T->tid]['act'][] = $act;

      }
      $this->checkVeto('postCommit', $log, $fqfiles, $actions);
    }

    foreach ($deferred as $tid => $info) {
      $T = $T_by_tid[$tid];

      $log = join("\n\n", $info['comments']);

      $CS = MTrackChangeset::begin("ticket:" . $T->tid, $log);

      if (isset($hashed[$T->tid])) {
        foreach ($hashed[$T->tid] as $hash) {
          MTrackDB::q(
            'insert into ticket_changeset_hashes(tid, hash) values (?, ?)',
            $T->tid, $hash);
        }
      }

      $T->addComment($log);
      if (isset($info['act'])) foreach ($info['act'] as $act) {
        if (isset($act['close'])) {
          $T->resolution = 'fixed';
          $T->close();
        }
        if (isset($act['spent'])) {
          $T->addEffort($act['spent']);
        }
      }
      $T->save($CS);
      $CS->commit();
    }
    foreach ($spent_by_tid_by_user as $tid => $sdata) {
      // Load it fresh here, as there seems to be an issue with saving
      // a second set of changes on a pre-existing object
      $T = MTrackIssue::loadById($tid);
      foreach ($sdata as $user => $time) {
        MTrackAuth::su($user);
        $CS = MTrackChangeset::begin("ticket:" . $T->tid,
          "Tracking time from prior push");
        MTrackAuth::drop();
        foreach ($time as $spent) {
          $T->addEffort($spent);
        }
        $T->save($CS);
        $CS->commit();
      }
    }
    $log = '';
    foreach ($no_ticket as $c) {
      $log .= "(In " . $c->rev . ") ";
      if ($c->changeby != $me) {
        $log .= " (on behalf of [user:$c->changeby]) ";
      }
      $log .= $c->changelog . "\n\n";
    }
    $CS = MTrackChangeset::begin("repo:" . $this->repo->repoid, $log);
    $CS->commit();
  }

  function checkPHP($filename, $fp) {
    $pipes = null;
    $proc = proc_open(MTrackConfig::get('tools', 'php') . " -l", array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w')
      ), $pipes);

    // send in data
    stream_copy_to_stream($fp, $pipes[0]);
    $fp = null;
    $pipes[0] = null;

    $output = stream_get_contents($pipes[1]);
    $output .= stream_get_contents($pipes[2]);
    $st = proc_get_status($proc);
    if ($st['running']) {
      proc_terminate($proc);
      sleep(1);
      $st = proc_get_status($proc);
    }
    if ($st['exitcode'] != 0) {
      throw new Exception("$filename: $output");
    }
    return true;
  }
}

