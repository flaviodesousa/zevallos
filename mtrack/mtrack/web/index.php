<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

if (MTrackAuth::whoami() === 'anonymous') {
  header("Location: {$ABSWEB}wiki.php");
  exit;
}

mtrack_head("Today");
echo MTrackWiki::format_wiki_page('Today');

mtrack_foot();

