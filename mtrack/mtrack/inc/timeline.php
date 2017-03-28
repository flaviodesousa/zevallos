<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

function mtrack_timeline_order_events_newest_first($a, $b)
{
  return strcmp($b['changedate'], $a['changedate']);
}

function mtrack_get_timeline($start_time = '-2 weeks',
  $only_users = null, $limit = 50)
{
  if (is_string($start_time)) {
    $date_limit = strtotime($start_time);
  } else {
    $date_limit = $start_time; // assume that it's a timestamp
  }
  /* round back to earlier minute (aids caching) */
  $date_limit -= $date_limit % 60;
  $db_date_limit = MTrackDB::unixtime($date_limit);
  $last_date = null;

  $filter_users = null;
  if (is_string($only_users)) {
    $filter_users = array(mtrack_canon_username($only_users));
  } else if (is_array($only_users)) {
    $filter_users = array();
    foreach ($only_users as $user) {
      $filter_users[] = mtrack_canon_username($user);
    }
  }

  $proj_by_id = array();
  foreach (MTrackDB::q('select projid from projects')->fetchAll() as $r) {
    $proj_by_id[$r[0]] = MTrackProject::loadById($r[0]);
  }
  $events = array();

  foreach (MTrackDB::q('select repoid from repos')->fetchAll() as $row) {
    list($repoid) = $row;
    $repo = MTrackRepo::loadById($repoid);
    $reponame = $repo->getBrowseRootName();
    if ($reponame == 'default/wiki') continue;
    $checker = new MTrackCommitChecker($repo);

    $hist = $repo->history(null, $db_date_limit);
    if (is_array($hist)) foreach ($hist as $e) {
      if (is_array($filter_users)) {
        $wanted_user = false;
        foreach ($filter_users as $fuser) {
          if (mtrack_canon_username($e->changeby) === $fuser) {
            $wanted_user = true;
            break;
          }
        }
        if (!$wanted_user) {
          continue;
        }
      }
      /* we want to include changesets that do not reference tickets */
      $pid = $repo->projectFromPath($e->files);
      if ($pid > 1) {
        $proj = $proj_by_id[$pid];
        $e->changelog = $proj->adjust_links($e->changelog, true);
      }
      $actions = $checker->parseCommitMessage($e->changelog);
      $tickets = array();
      foreach ($actions as $act) {
        $tkt = $act[1];
        $tickets[$tkt] = $tkt;
        $repo_changes_by_ticket[$tkt][$reponame][$e->rev] = $e->rev;
      }
      if (count($tickets) == 0) {
        $events[] = array(
            'changedate' => $e->ctime,
            'who' => $e->changeby,
            'object' => "changeset:$reponame:$e->rev",
            'reason' => $e->changelog,
            );
      }
    }
  }
  foreach (MTrackDB::q("select 
      changedate, who, object, reason from changes
      where changedate > ?
      order by changedate desc
      ", $db_date_limit)->fetchAll(PDO::FETCH_ASSOC) as $row) {
    if (is_array($filter_users)) {
      $wanted_user = false;
      foreach ($filter_users as $fuser) {
        if (mtrack_canon_username($row['who']) === $fuser) {
          $wanted_user = true;
          break;
        }
      }
      if (!$wanted_user) {
        continue;
      }
    }
    $events[] = $row;
  }

  usort($events, 'mtrack_timeline_order_events_newest_first');
  return $events;
}

function _mtrack_timeline_is_repo_visible($reponame)
{
  static $cache = array();
  $me = MTrackAuth::whoami();
  if (isset($cache[$me][$reponame])) {
    return $cache[$me][$reponame];
  }

  if (ctype_digit($reponame)) {
    $oid = "repo:$reponame";
  } else {
    $repo = MTrackRepo::loadByName($reponame);
    if ($repo) {
      $oid = "repo:$repo->repoid";
    } else {
      $oid = null;
    }
  }
  if ($oid) {
    $ok = MTrackACL::hasAnyRights($oid, array(
    'read', 'checkout'));
  } else {
    $ok = false;
  }
  $cache[$me][$reponame] = $ok;
  return $ok;
}

function mtrack_render_timeline($user = null)
{
  global $ABSWEB;

  $limit = 50;
  $events = mtrack_cache('mtrack_get_timeline',
    array('-2 weeks', $user, $limit), 300, array('Timeline', $user));

  echo "<div class='timeline'>";
  $last_date = null;
  foreach ($events as $row) {
    if (--$limit == 0) {
      break;
    }

    $d = date_create($row['changedate'], new DateTimeZone('UTC'));
    date_timezone_set($d, new DateTimeZone(date_default_timezone_get()));
    $time = $d->format('H:i');
    $day = $d->format('D, M d Y');

    if ($last_date != $day) {
      echo "<h1 class='timelineday'>$day</h1>\n";
      $last_date = $day;
    }

    // figure out an event type based on the object and the reason
    if (strpos($row['object'], ':') !== false) {
      list($object, $id) = explode(':', $row['object'], 3);
    } else {
      $id = 0;
      $object = $row['object'];
    }
    $eventclass = ''; 
    $item = $row['object'];
    switch ($object) {
      case 'ticket':
        if (!strncmp($row['reason'], 'created ', 8)) {
          $eventclass = ' newticket';
        } elseif (!strncmp($row['reason'], 'closed ', 7)) {
          $eventclass = ' closedticket';
        } else {
          $eventclass = ' editticket';
        }
        $item = "Ticket " . mtrack_ticket($id);
        break;
      case 'wiki':
        $eventclass = ' editwiki';
        $item = "Wiki " . mtrack_wiki($id);
        break;
      case 'milestone':
        $eventclass = ' editmilestone';
        $item = "Milestone <span class='milestone'><a href='{$ABSWEB}milestone.php/$id'>$id</a></span>";
        break;
      case 'changeset':
        $eventclass = ' newchangeset';
        preg_match("/^changeset:(.*):([^:]+)$/", $row['object'], $M);
        $repo = $M[1];
        if (!_mtrack_timeline_is_repo_visible($repo)) {
          continue 2;
        }
        $id = $M[2];
        $item = "<a href='{$ABSWEB}browse.php/$repo'>$repo</a> change " . mtrack_changeset($id, $repo);
        break;
      case 'snippet':
        $item = "<a href='{$ABSWEB}snippet.php/$id'>View Snippet</a>";
        break;
      case 'repo':
        static $repos = null;
        if ($repos === null) {
          $repos = array();
          foreach (MTrackDB::q(
              'select repoid, shortname, parent from repos')->fetchAll()
              as $r) {
            $repos[$r[0]] = $r;
          }
        }
        if (!_mtrack_timeline_is_repo_visible($id)) {
          continue 2;
        }
        if (isset($repos[$id])) {
          $name = MTrackRepo::makeDisplayName($repos[$id]);
          $item = "<a href='{$ABSWEB}browse.php/$name'>$name</a>";
        } else {
          $item = "&lt;item has been deleted&gt;";
        }
        break;
    }

    $reason = MTrackWiki::format_to_oneliner($row['reason']);

    echo "<div class='timelineevent'>",
      mtrack_username($row['who'], array(
        'no_name' => true,
        'size' => 48,
        'class' => 'timelineface'
        )),
      "<div class='timelinetext'>",
      "<div class='timelinereason'>",
      "$reason</div>\n",
      "<span class='time'>$time</span> $item by ",
      mtrack_username($row['who'], array('no_image' => true)),
      "</div>\n";
    echo "</div>\n";
  }
  echo "</div>\n";
}

