<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';
MTrackACL::requireAllRights('Browser', 'read');

$pi = mtrack_get_pathinfo(true);
$crumbs = MTrackSCM::makeBreadcrumbs($pi);
if (!strlen($pi) || $pi == '/') {
  $pi = '/';
  $repo = null;
} else {
  $repo = MTrackSCM::factory($pi);
}

if ($repo === null) {
  throw new Exception("Cannot determine what to log from $pi");
}

MTrackACL::requireAllRights("repo:$repo->repoid", 'read');
mtrack_head("Log $pi");

/* Render a bread-crumb enabled location indicator */
echo "<div class='browselocation'>Location: ";
$location = null;
if (isset($_GET['jump'])) {
  $jump = '?jump=' . urlencode($_GET['jump']);
  list($object, $ident) = explode(':', $_GET['jump'], 2);
} else {
  $_GET['jump'] = '';
  $jump = '';
  $object = null;
  $ident = null;
}
$last = array_pop($crumbs);
foreach ($crumbs as $path) {
  if (!strlen($path)) {
    $path = '[root]';
    echo "<a href='{$ABSWEB}browse.php$jump'>$path</a> / ";
  } else {
    $location .= '/' . htmlentities(urlencode($path), ENT_QUOTES, 'utf-8');
    echo "<a href='{$ABSWEB}log.php$location$jump'>$path</a> / ";
  }
}

echo "$last";
$branches = $repo->getBranches();
$tags = $repo->getTags();
if (count($branches) + count($tags)) {
  $jumps = array("" => "- Select Branch / Tag - ");
  if (is_array($branches)) {
    foreach ($branches as $name => $notcare) {
      $jumps["branch:$name"] = "Branch: $name";
    }
  }
  if (is_array($tags)) {
    foreach ($tags as $name => $notcare) {
      $jumps["tag:$name"] = "Tag: $name";
    }
  }
  echo "<form>";
  echo mtrack_select_box("jump", $jumps, $_GET['jump']);
  echo "<button type='submit'>Choose</button></form>\n";
}
echo "</div>";

$last_day = null;
$even = 1;
$hist = $repo->history($pi, null, $object, $ident);
if (!count($hist)) {
  echo "<em>No history for the requested path</em>";
} else {
  echo "<div class='changesets'>\n";
  foreach ($hist as $ent) {
    $class = ($even++ % 2) ? '' : 'odd';

    $ts = strtotime($ent->ctime);
    $day = date('D, M d Y', $ts);
    $time = date('H:m', $ts);

    if ($day !== $last_day) {
      echo "<div class='changesetday'>$day</div>\n";
      $last_day = $day;
    }
    echo "<div class='changeset$class'>\n<div class='changelog'>";
    echo MTrackWiki::format_to_html($ent->changelog);
    echo "</div>\n";

    echo "<div class='changeinfo'>\n";
    echo mtrack_username($ent->changeby, array(
          'no_name' => true,
          'size' => 32
          ));

    echo mtrack_changeset($ent->rev, $repo);
    foreach ($ent->branches as $b) {
      echo " " . mtrack_branch($b);
    }
    foreach ($ent->tags as $t) {
      echo " " . mtrack_tag($t);
    }
    echo "<br>\n";
    echo mtrack_username($ent->changeby, array('no_image' => true)) . "<br>\n";
    echo "$time <span class='time'>" . mtrack_date($ent->ctime) . "</span>\n";
    echo "</div></div>\n";
  }
  echo "</div>\n";
}


mtrack_foot();

