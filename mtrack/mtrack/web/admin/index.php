<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

mtrack_head("Administration");

$cat_titles = array(
  'tickets' => 'Configure Tickets',
  'projects' => 'Configure Projects &amp; Notifications',
  'repo' => 'Configure Repositories',
  'user' => 'User Administration &amp; Authentication',
  'logs' => 'Review Logs',
);

$by_cat = array();

function add_cat($url) {
  global $by_cat;
  $cats = func_get_args();
  array_shift($cats);
  foreach ($cats as $cat) {
    $by_cat[$cat][] = $url;
  }
}

if (MTrackACL::hasAnyRights('Projects', 'modify')) {
  add_cat("<a href='{$ABSWEB}admin/project.php'>Projects</a> and their notification settings", 'projects');
}

if (MTrackACL::hasAnyRights('Enumerations', 'modify')) {
  $eurl = $ABSWEB . 'admin/enum.php';
  add_cat("<a href='$eurl/Priority'>Priority</a>, <a href='$eurl/TicketState'>TicketState</a>, <a href='$eurl/Severity'>Severity</a>, <a href='$eurl/Resolution'>Resolution</a> and <a href='$eurl/Classification'>Classification</a> fields used in tickets", 'tickets');
  add_cat("<a href='{$ABSWEB}admin/customfield.php'>Custom Fields</a>", 'tickets');
}

if (MTrackACL::hasAnyRights('Components', 'modify')) {
  add_cat("<a href='{$ABSWEB}admin/component.php'>Components</a> and their associations with Projects", 'tickets', 'projects');
}

if (MTrackACL::hasAnyRights('Tickets', 'create')) {
  add_cat("<a href='{$ABSWEB}admin/importcsv.php'>Import Tickets</a> from a CSV file", 'tickets');
}

if (MTrackACL::hasAnyRights('Browser', 'modify')) {
  add_cat("Configure <a href='{$ABSWEB}admin/repo.php'>Repositories</a> and their links to Projects", 'repo');
}

if (MTrackACL::hasAllRights('User', 'modify')) {
  add_cat("Administer <a href='{$ABSWEB}admin/auth.php'>Authentication</a>", 'user');
  add_cat("Administer <a href='{$ABSWEB}admin/user.php'>Users</a>", 'user');
}

if (MTrackACL::hasAllRights('Browser', 'modify')) {
  add_cat("<a href='{$ABSWEB}admin/logs.php'>Indexer logs</a>", 'logs');
}

foreach ($cat_titles as $cat => $title) {
  $links = $by_cat[$cat];
  if (count($links) == 0) {
    continue;
  }
  echo "<h2>$title</h2>";
  foreach ($links as $link) {
    echo $link, "<br>\n";
  }
}

mtrack_foot();

