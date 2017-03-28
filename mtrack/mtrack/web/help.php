<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';

$topic = mtrack_get_pathinfo();
$helpdir = dirname(__FILE__) . '/../defaults/help';
if (strpos($topic, '.') !== false) {
  throw new Exception("invalid help topic");
}
$name = $helpdir . '/' . $topic;

if (!strlen($topic)) {
  mtrack_head("Help topics");

  echo "<h1>Help topics</h1>";
  echo "<ul>\n";
  foreach (glob("$helpdir/*") as $topic) {
    $topic = basename($topic);
    echo "<li><a href='{$ABSWEB}help.php/$topic'>$topic</a></li>\n";
  }
  echo "</ul>\n";

} elseif (!file_exists($name)) {
  mtrack_head("no help topic $topic");

  echo "<h1>No Help topic ", htmlentities($topic), "</h1>";
} else {
  mtrack_head("Help: $topic");
  echo MTrackWiki::format_to_html(file_get_contents($name));
}

mtrack_foot();
