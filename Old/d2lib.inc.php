<?php
// =========================================================================
// Conecta com o SQL ativo - MSSQL
// -------------------------------------------------------------------------
function myQuery($sql) {
  if (!$myQuery = @mysql_query($sql, myConn)) {
    echo "<span style=\"text-align: left;\"><pre><b>MySQL - Query</b>:"
        ."<br /><b>Host</b>: ".myHost
        ."<br /><b>Database</b>: ".myDatabase
        ."<br /><b>Message</b>: [".mysql_error(myConn)."]";
    preg_match_all("%^(?P<sql>.*?)(?P<r>\r)?(?P<n>\n)%sm", $sql."\r\n", $Matches, PREG_OFFSET_CAPTURE);

    if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

    $i = 0;

    $errorColumn -= 8;

    foreach ($Matches[0] as $key => $value) {
      $i++;

      $sql = $Matches["sql"][$key][0];

      echo "<br />";

      if ($i != $errorLine) {
        echo htmlentities($sql);
      } else {
        echo htmlentities($sql)." &lt-- <font color=\"red\"\><b>Error</b> </font>";
      }
    }

    echo "<hr />".debugBackTrace();
    echo "</pre></span>";
    die();
  }

  return $myQuery;

}

// =========================================================================
// Conecta com o SQL ativo - MSSQL
// -------------------------------------------------------------------------
function myConnect() {
  global $myConn;

  // $myConn = @mysql_pconnect(ConnectionStringHost, ConnectionStringUser, ConnectionStringPassword) or die("O MySQL parece estar desativado.");

  // if (ConnectionStringDatabase!="" and !@mysql_select_db(ConnectionStringDatabase)) die("O MySQL está indisponível.");

  // Conexão com o MSSQL
  // $myConn = sqlsrv_connect(myHost,array("Database"=>"credito_istcartoes_com_br"));

  if (!$myConn = @mysql_connect(myHost, myUser, myPassword)) {
    echo "<span style=\"text-align: left;\"><pre><b>MySQL - Connect</b>:"
        ."<br /><b>Connection</b>: ".myHost
        ."<br /><b>Database</b>: ".myDatabase
        ."<br /><b>Message</b>: The MySQL connection failed";

    echo "<hr />".debugBackTrace();
    echo "</pre></span>";
    die();
  }

  if (!@mysql_select_db(myDatabase, $myConn)) {
    echo "<span style=\"text-align: left;\"><pre><b>MySQL - SelectDB</b>:"
        ."<br /><b>Connection</b>: ".myHost
        ."<br /><b>Database</b>: ".myDatabase
        ."<br /><b>Message</b>: [".mysql_error($myConn)."]";

    echo "<hr />".debugBackTrace();
    echo "</pre></span>";
    die();
  }

	define('myConn', $myConn);

}

// =========================================================================
// Libera os recursos usados pela conexão
// -------------------------------------------------------------------------
function myDisconnet() {

  if (myConn) {
    mysql_close(myConn);
  }
}

/*
SHOW VARIABLES LIKE 'have_innodb'
SHOW VARIABLES LIKE 'have_bdb'

BEGIN WORK
START TRANSACTION;
ROLLBACK
COMMIT

PASSWORD('i283kh')

SET AUTOCOMMIT=0;
EXPLAIN [EXTENDED]

*/

function debugError($varContent, $blnBackTrace=0) {

  if ($varContent) {
    $debugBackTrace = debug_backtrace();

    $strFile     = basename($debugBackTrace[0]["file"]);
    $strFunction = $debugBackTrace[1]["function"];
    $intLine     = $debugBackTrace[0]["line"];

    echo "<pre style=\"text-align: left;\"><b>$strFile($intLine)-&gt;$strFunction</b><br />";

    if (is_array($varContent)) {
      print_r($varContent);
    } else {
      echo $varContent;
    }

    if ($blnBackTrace) echo "<hr />".debugBackTrace();

    echo "</pre>";

    ob_flush(); flush();

  }
}

function debugBackTrace() {
  $debugBackTrace = debug_backtrace();

  // print_r($debugBackTrace);

  $intDebugArray = count($debugBackTrace);

  for ($i = 1; $i < $intDebugArray; $i++) {
    if ($i > 1) $return .= "-&gt;".$debugBackTrace[$i]["function"].", ";

    $return .= basename($debugBackTrace[$i]["file"])."(".$debugBackTrace[$i]["line"].")";

    $strFunction = $debugBackTrace[$i]["function"];
  }

  return $return;
}

// =========================================================================
// Conecta com o SQL ativo - MSSQL
// -------------------------------------------------------------------------
function msConnect() {
  global $msConn;

  // $msConn = @mysql_pconnect(ConnectionStringHost, ConnectionStringUser, ConnectionStringPassword) or die("O MySQL parece estar desativado.");

  // if (ConnectionStringDatabase!="" and !@mysql_select_db(ConnectionStringDatabase)) die("O MySQL está indisponível.");

  // Conexão com o MSSQL
  // $msConn = sqlsrv_connect(msHost,array("Database"=>"credito_istcartoes_com_br"));

  $msConn = @mssql_connect(msHost, msUser, msPassword) or die("<br />CONNECT: O MSSQL ".msHost." parece estar desativado.<br />[".mssql_get_last_message()."]");

  @mssql_select_db(msDatabase, $msConn) or die("<br />SELECT: O database ".msDatabase." do MSSQL ".msHost." está indisponível.<br />[".mssql_get_last_message()."]");

	define('msConn', $msConn);

}

// =========================================================================
// Libera os recursos usados pela conexão
// -------------------------------------------------------------------------
function msDisconnet() {

  if (msConn) {
    mssql_close(msConn);
  }
}

class curl {
  var $cURL;
  var $info;
  var $agent;
  var $baseURL;
  var $baseDir;
  var $referer;
  var $step;

  var $result;

  var $pattern;
  var $matches;

  function close() {
    curl_close($this->cURL);
  }

  function open() {
    $this->cURL = curl_init();

    curl_setopt($this->cURL, CURLOPT_AUTOREFERER, true);

    // you might want the headers for http codes
    // curl_setopt($this->cURL, CURLOPT_HEADER, true);

    // you may need to set the http useragent for curl to operate as
    curl_setopt($this->cURL, CURLOPT_USERAGENT, $this->agent);
    //curl_setopt($this->cURL, CURLOPT_HEADER, 1);

    // you wanna follow stuff like meta and location headers
    curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->cURL, CURLOPT_MAXREDIRS, 1);

    curl_setopt($this->cURL, CURLOPT_CRLF, true);
    // curl_setopt($this->cURL, CURLOPT_FRESH_CONNECT, true);


    curl_setopt($this->cURL, CURLOPT_COOKIEJAR, $this->baseDir.$this->cookieFile);
    curl_setopt($this->cURL, CURLOPT_COOKIEFILE, $this->baseDir.$this->cookieFile);

    // curl_setopt($this->cURL, CURLOPT_VERBOSE, 2);
    // curl_setopt($this->cURL, CURLOPT_STDERR, $this->baseDir.'verbose.txt');

  }

  function post($page, $vars) {
    if ($this->step) echo "$this->step".$this->step = '';

    if ($this->referer) curl_setopt($this->cURL, CURLOPT_REFERER, $this->referer);

    $this->referer = $this->baseURL.$page;

    // if(is_array($vars)) $vars = implode('&', $vars);

    curl_setopt($this->cURL, CURLOPT_URL, $this->baseURL.$page);

    curl_setopt($this->cURL, CURLOPT_POST, true);
    curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $vars);

    $this->result = curl_exec($this->cURL);

    if (curl_errno($this->cURL)) {
      print "Error: " . curl_error($this->cURL);

    } else {
      $this->info = curl_getinfo($this->cURL);

      if ($this->step) echo ' - '.$this->info['http_code'].' ['.$this->info['size_upload'].'('.$this->info['speed_upload'].'), '.$this->info['size_download'].'('.$this->info['speed_download'].')] '.$this->info['total_time'].'s';

      $this->matches = array();

      if ($this->pattern) {
				preg_match_all($this->pattern, $this->result, $this->matches, PREG_OFFSET_CAPTURE);

				$this->pattern = null;

				if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();
      }
    }

    return $this->result;
  }

  function get($page) {
    if ($this->step) echo "$this->step [<a href=\"$this->baseURL$page\" target=\"_blank\">URL</a>]".$this->step = '';

    if ($this->referer) curl_setopt($this->cURL, CURLOPT_REFERER, $this->referer);

    $this->referer = $this->baseURL.$page;

    curl_setopt($this->cURL, CURLOPT_URL, $this->baseURL.$page);

    $this->result = curl_exec($this->cURL);

    if (curl_errno($this->cURL)) {
      print "Error: " . curl_error($this->cURL);

    } else {
      $this->info = curl_getinfo($this->cURL);
      echo ' - '.$this->info['http_code'].' ['.$this->info['size_upload'].'('.$this->info['speed_upload'].'), '.$this->info['size_download'].'('.$this->info['speed_download'].')] '.$this->info['total_time'].'s';

      $this->matches = array();

      if ($this->pattern) {
				preg_match_all($this->pattern, $this->result, $this->matches, PREG_OFFSET_CAPTURE);

				$this->pattern = null;

				if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();
      }
    }

    return $this->result;
  }
}

$sarrLatin1 = array('&brvbar;', '&pound;', '&laquo;', '&raquo;', '&acute;', '&times;', '&divide;', '&cent;', '&ordf;', '&ordm;', '&deg;', '&amp;', '&#41;', '&#40;', '&lt;', '&gt;', '&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&acirc;', '&ecirc;', '&icirc;', '&ocirc;', '&ucirc;', '&agrave;', '&egrave;', '&igrave;', '&ograve;', '&ugrave;', '&auml;', '&euml;', '&iuml;', '&ouml;', '&uuml;', '&atilde;', '&otilde;', '&ccedil;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&Acirc;', '&Ecirc;', '&Icirc;', '&Ocirc;', '&Ucirc;', '&Agrave;', '&Egrave;', '&Igrave;', '&Ograve;', '&Ugrave;', '&Auml;', '&Euml;', '&Iuml;', '&Ouml;', '&Uuml;', '&Atilde;', '&Otilde;', '&Ccedil;', '&copy;', '&reg;');
$sarrAccent = array('¦', '£', '«', '»', '´', '×', '÷', '¢' , 'ª', 'º', '°', '&', ')', '(', '<', '>', 'á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'î', 'ô', 'û', 'à', 'è', 'ì', 'ò', 'ù', 'ä', 'ë', 'ï', 'ö', 'ü', 'ã', 'õ', 'ç', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Î', 'Ô', 'Û', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü', 'Ã', 'Õ', 'Ç', '©', '®');

function accentString2Latin1($strString, $blnQuot = 0) {
  global $sarrLatin1, $sarrAccent;

  $strStringNew = str_replace($sarrAccent, $sarrLatin1, $strString);

  if ($blnQuot) $strStringNew = preg_replace('%(”|“|\"|\x93|\x94|\x147|\x148)%', "&quot;", $strStringNew);

  return $strStringNew;

}

function latin12AccentString($strString, $blnQuot = 0) {
  global $sarrLatin1, $sarrAccent;

  $strStringNew = str_replace($sarrLatin1, $sarrAccent, $strString);

  if ($blnQuot) $strStringNew = str_replace('&quot', '"', $strStringNew);

  return $strStringNew;

}

function array2JSON($array, $compress = 1, $base64 = 1) {
  if (is_array($array) && count($array)) {
    foreach ($array as &$a)
      $a = utf8_encode(accentString2Latin1($a, 1));

    $eJSON = json_encode($array);

    if ($compress) $eJSON = gzcompress($eJSON, 9);

    if ($base64) $eJSON = strtr(base64_encode($eJSON), '+/', '-_');

    return $eJSON;
  }
}

function json2Array($eJSON, $html = 1, $uncompress = 1, $base64 = 1) {
  if (strlen($eJSON)) {
    if ($base64) $eJSON = base64_decode(strtr($eJSON, '-_', '+/'));

    // echo "<br /><b>base64_decode</b><br />";
    // var_dump($eJSON);

    if ($uncompress) $eJSON = gzuncompress($eJSON);

    // echo "<br /><b>gzuncompress</b><br />";
    // var_dump($eJSON);

    $array = json_decode($eJSON);

    if ($html) {
      foreach ($array as &$a)
        $a = str_replace('\'', '\\\'', str_replace('&quot;', '"', html_entity_decode($a, 1)));
    }

    return $array;
  }
}

function microtime_float() {
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}

function strTime($s, $showms=0) {
  $d = intval($s/86400);
  $s -= $d*86400;

  $h = intval($s/3600);
  $s -= $h*3600;

  $m = intval($s/60);
  $s -= $m*60;

  $ms = ($s % 1000) * 1000;

  if ($d) $str = $d.'d ';
  if ($h) $str .= $h.'h ';
  if ($m) $str .= $m.'m ';
  if ($s) $str .= intval($s).'s';
  if ($ms && $showms) $str .= intval($ms).'ms';

  return $str;
}

function is_base64_encoded($data) {
  if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
    return 1;
  } else {
    return 0;
  }
}
