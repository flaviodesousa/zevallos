<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

$age = 3600;

header('Content-Type: text/css');
header("Cache-Control: public, max-age=$age, pre-check=$age");
header('Expires: ' . date(DATE_COOKIE, time() + $age));

$scripts = array(
  'css/smoothness/jquery-ui-1.7.2.custom.css',
  'mtrack.css',
  'css/markitup/markitup-simple.css',
  'css/markitup/wiki.css',
  '../inc/hyperlight/vibrant-ink.css',
  '../inc/hyperlight/zenburn.css',
  '../inc/hyperlight/wezterm.css',
);

foreach ($scripts as $name) {
  echo "\n/* $name */\n";
  $dir = dirname($name);
  $data = file_get_contents($name);
  $data = preg_replace('@url\(([^)]+)\)@', "url($dir/\\1)", $data);
  echo "$data\n";
}


