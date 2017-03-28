<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackMilestone {
  public $mid = null;
  public $pmid = null;
  public $name = null;
  public $description = null;
  public $duedate = null;
  public $startdate = null;
  public $deleted = null;
  public $completed = null;
  public $created = null;

  static function loadByName($name)
  {
    foreach (MTrackDB::q('select mid from milestones where lower(name) = lower(?)', $name)
        ->fetchAll() as $row) {
      return new self($row[0]);
    }
    return null;
  }

  static function loadByID($id)
  {
    foreach (MTrackDB::q('select mid from milestones where mid = ?', $id)
        ->fetchAll() as $row) {
      return new self($row[0]);
    }
    return null;
  }

  static function enumMilestones($all = false)
  {
    if ($all) {
      $q = MTrackDB::q('select mid, name from milestones where deleted != 1');
    } else {
      $q = MTrackDB::q('select mid, name from milestones where completed is null and deleted != 1');
    }
    $res = array();
    foreach ($q->fetchAll(PDO::FETCH_NUM) as $row) {
      $res[$row[0]] = $row[1];
    }
    return $res;
  }

  function __construct($id = null)
  {
    if ($id !== null) {
      $this->mid = $id;

      list($row) = MTrackDB::q('select * from milestones where mid = ?', $id)
        ->fetchAll(PDO::FETCH_ASSOC);
      foreach ($row as $k => $v) {
        $this->$k = $v;
      }
    }
    $this->deleted = false;
  }

  function save(MTrackChangeset $CS)
  {
    $this->updated = $CS->cid;

    if ($this->mid === null) {
      $this->created = $CS->cid;

      MTrackDB::q('insert into milestones
          (name, description, startdate, duedate, completed, created,
            pmid, updated, deleted)
          values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        $this->name,
        $this->description,
        $this->startdate,
        $this->duedate,
        $this->completed,
        $this->created,
        $this->pmid,
        $this->updated,
        (int)$this->deleted);

      $this->mid = MTrackDB::lastInsertId('milestones', 'mid');
    } else {
      list($old) = MTrackDB::q(
          'select * from milestones where mid = ?', $this->mid)->fetchAll();
      foreach ($old as $k => $v) {
        if ($k == 'mid' || $k == 'created' || $k == 'updated') {
          continue;
        }
        $CS->add("milestone:$this->mid:$k", $v, $this->$k);
      }
      MTrackDB::q('update milestones set name = ?,
          description = ?, startdate = ?, duedate = ?, completed = ?,
          updated = ?, deleted = ?, pmid = ?
          WHERE mid = ?',
        $this->name,
        $this->description,
        $this->startdate,
        $this->duedate,
        $this->completed,
        $this->updated,
        (int)$this->deleted,
        $this->pmid,
        $this->mid);
    }
  }

  static function macro_BurnDown() {
    global $ABSWEB;

    $args = func_get_args();

    if (!count($args) || (count($args) == 1 && $args[0] == '')) {
      # Special case for allowing burndown to NOP in the milestone summary
      return '';
    }

    $params = array(
      'width' => '75%',
      'height' => '250px',
    );

    foreach ($args as $arg) {
      list($name, $value) = explode('=', $arg, 2);
      $params[$name] = $value;
    }

    $m = MTrackMilestone::loadByName($params['milestone']);
    if (!$m) {
      return "BurnDown: milestone $params[milestone] is invalid<br>\n";
    }
    if (!MTrackACL::hasAllRights("milestone:" . $m->mid, 'read')) {
      return "Not authorized to view milestone $name<br>\n";
    }
 
    /* step 1: find all changes on this milestone and its children */
    $effort = MTrackDB::q("
      select expended, remaining, changedate
      from
        ticket_milestones tm
      left join
        effort e on (tm.tid = e.tid)
      left join
        changes c on (e.cid = c.cid)
      where (mid = ? 
        or (mid in (select mid from milestones where pmid = ?))
      )
         and c.changedate is not null
      order by c.changedate",
      $m->mid, $m->mid)->fetchAll(PDO::FETCH_NUM);

    /* estimated hours by day */
    $estimate_by_day = array();
    /* accumulated work spent by day */
    $accum_spent_by_day = array();
    /* accumulated remaining hours by day */
    $accum_remain_by_day = array();

    $current_estimate = null;
    $min_day = null;
    $max_value = 0;
    $total_exp = 0;

    foreach ($effort as $info) {
      list($exp, $rem, $date) = $info;
      list($day, $rest) = explode('T', $date, 2);

      /* previous day estimate carries over to today */
      if (!isset($estimate_by_day[$day])) {
        $estimate_by_day[$day] = $current_estimate;
      }

      /* previous accumulation carries over */
      if (!isset($accum_spent_by_day[$day])) {
        $accum_spent_by_day[$day] = $total_exp;
      }

      /* revise the estimate for today; also applies
       * to the number we carry over to tomorrow */
      if ($rem !== null) {
        $estimate_by_day[$day] += $rem;
        $current_estimate = $estimate_by_day[$day];
      }

      if ($exp !== null) {
        if ($exp != 0 && $min_day === null) {
          $min_day = strtotime($date);
        }
        $accum_spent_by_day[$day] += $exp;
        $total_exp += $exp;
      }
      $accum_remain_by_day[$day] = $current_estimate - $total_exp;
      $max_value = max($max_value, $current_estimate);
    }

    $init_estimate = 0;
    foreach ($estimate_by_day as $v) {
      if ($v) {
        $init_estimate = $v;
        break;
      }
    }

    /* limit the view to the past 3 weeks */
    $earliest = strtotime('-3 week');
    if ($min_day < $earliest) {
//      $min_day = $earliest;
    }
    $min_day *= 1000;

    if ($m->duedate) {
      $maxday = strtotime($m->duedate);
    } else {
      $maxday = time();
    }
    $maxday = strtotime('1 week', $maxday) * 1000;

    /* step 3: compute the day by day remaining value,
     * and produce data series for remaining and expended time */

    $js_remain = array();
    $js_estimate = array();
    $trend = array();
    foreach ($accum_remain_by_day as $day => $remaining) {

      /* compute javascript timestamp */
      list($year, $month, $dayno) = explode('-', $day);
      $ts = gmmktime(0, 0, 0, $month, $dayno, $year) * 1000;

      $js_remain[] = "[$ts, $remaining]";
      $est = (int)$estimate_by_day[$day];
      $js_estimate[] = "[$ts, $est]";
      $trend[$ts] = $remaining;
    }

    $js_remain = join(',', $js_remain);
    $js_estimate = join(',', $js_estimate);

    $flot = "bd_graph_" . sha1(join(':', $args) . time());

    $max_value *= 1.2;

    $height = (int)$params['height'];

    $html = "
<div id='$flot' class='flotgraph'
  style='width: $params[width]; height: $params[height];'></div>
<script id='source_$flot' language='javascript' type='text/javascript'>
\$(function () {
  var p = \$('#$flot');
  // Not sure what's up here, but somehow the height for the element
  // shows up as 0 in safari, despite the style setting above... so let's
  // just force the height here.
  if (p.height() == 0) {
    p.height($height);
  }
  \$.plot(p, [
    { label: \"estimated\", data: [$js_estimate], yaxis: 1 },
    { label: \"remaining\", data: [$js_remain] }
    ], {
     xaxis: {
       mode: \"time\",
       timeformat: '%b %d',
       min: $min_day,
       max: $maxday
     },
     yaxis: {
      max: $max_value
     },
     legend: {
      position: 'sw'
     },
     grid: {
      hoverable: true
     }
    }
  );
});
</script>
";

    $delta = $init_estimate - $total_exp;

    return
      "<div class='burndown'>Initial estimate: $init_estimate, Work expended: $total_exp<br>\n"
      . $html . "</div>";
  }

  static function macro_MilestoneSummary($name) {
    global $ABSWEB;

    $m = self::loadByName($name);
    if (!$m) {
      return "milestone: " . htmlentities($name) . " not found<br>\n";
    }

    if (!MTrackACL::hasAllRights("milestone:" . $m->mid, 'read')) {
      return "Not authorized to view milestone $name<br>\n";
    }
   
    $completed = mtrack_date($m->completed);
    $description = $m->description;
    if (strpos($description, "[[BurnDown(") === false) {
      $description = "[[BurnDown(milestone=$name,width=50%,height=150)]]\n" .
        $description;
    }
    $desc = MTrackWiki::format_to_html($description);
    $pname = $name;
    if ($m->completed !== NULL) {
      $pname = "<del>$name</del>";
      $due = "Completed";
    } elseif ($m->duedate) {
      $due = "Due " . mtrack_date($m->duedate);
    } else {
      $due = null;
    }

    $watch = MTrackWatch::getWatchUI('milestone', $m->mid);

    $html = <<<HTML
<div class="milestone">
<h2><a href="{$ABSWEB}milestone.php/$name">$pname</a></h2>
$watch
<div class="due">$due</div>
$desc<br/>
HTML;

    $estimated = 0;
    $remaining = 0;
    $open = 0;
    $total = 0;

    foreach (MTrackDB::q('select status, estimated, estimated - spent as remaining from ticket_milestones tm left join tickets t on (tm.tid = t.tid) where mid = ?',
        $m->mid)->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $total++;
      if ($row['status'] != 'closed') {
        $open++;
      }
      $estimated += $row['estimated'];
      $remaining += $row['remaining'];
    }

    $closed = $total - $open;
    if ($total) {
      $apct = (int)($open / $total * 100);
    } else {
      $apct = 0;
    }
    $cpct = 100 - $apct;
    $html .= <<<HTML
<table class='progress'>
<tr>
  <td class='closed' style='width:$cpct%;'><a href='#'></a></td>
HTML;

    if ($open) {
      $html .= <<<HTML
  <td class='open' style='width:$apct%;'><a href='#'> </a></td>
HTML;
    }

    $ms = urlencode($name);

    $html .= <<<HTML
</tr>
</table>
<a href='{$ABSWEB}query.php?milestone=$ms&status!=closed'>$open open</a>,
<a href='{$ABSWEB}query.php?milestone=$ms&status=closed'>$closed closed</a>,
<a href='{$ABSWEB}query.php?milestone=$ms'>$total total</a> ($cpct % complete)
</div>
HTML;
    return $html;
  }
}

MTrackWiki::register_macro('MilestoneSummary',
  array('MTrackMilestone', 'macro_MilestoneSummary'));

MTrackWiki::register_macro('BurnDown',
  array('MTrackMilestone', 'macro_BurnDown'));

MTrackACL::registerAncestry('milestone', 'Roadmap');
MTrackWatch::registerEventTypes('milestone', array(
  'ticket' => 'Tickets',
  'changeset' => 'Code changes'
));

