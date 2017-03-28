<?php
  error_reporting(E_ALL & ~E_NOTICE);

  require_once('d2lib.inc.php');

  $jsonNoticia = $_REQUEST['jsonNoticia'];

  $jsonNoticia = json2Array($jsonNoticia);

  echo "<hr />jsonNoticia=", var_dump($jsonNoticia);
