<?php
// ================================================================
// /Config.inc.php
// ----------------------------------------------------------------
// Nome     : Arquivo de configuração
// Home     : unidental.com.br
// Criacao  : 01/04/2010 11:00:00
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF
// Copyright: 2007-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE);

//require_once("libraryError.inc.php");

// @TODO Verificar porque os set_error_handler e o set_exception_handler não funcionam

if (function_exists("libraryErrorHandler")) $sysErrorHandler = set_error_handler("libraryErrorHandler", E_ALL);
if (function_exists("libraryExceptionHandler")) $sysExceptionHandler = set_exception_handler('libraryExceptionHandler');

session_cache_limiter('SG');
$sintCacheLimiter = session_cache_limiter();

session_cache_expire(20);
$sintCacheExpire = session_cache_expire();

session_start();

$sstrSessionID = session_id();

ob_start("ob_gzhandler");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("Pragma: no-cache");

// Dados do sistema
define("sysName", "RRPro");
define("sysSign", "RRPro");
define("sysLogo", "/Images/RRPro.jpg");
define("sysLogoTop", "/Images/RRPro40.bmp");

define("sysCompanySign", "RRPro");
define("sysCompanyName", "RRPro");
define("sysCompanySuportEMail", "suporte@RRPro.com.br");
define("sysCompanyURL", "www.RRPro.com.br");
define("sysCompanyCopyright", "2010 - ".date("Y", time()));

define("sysLoginBlockAfter", 3); // fará o bloqueio após 3 erros
define("sysLoginBlockedTime", 1801); // 30 minutos + 1 segundo

// ob_end_flush();

// set_time_limit(300);

$sintExecucao = microtime();

if (strtolower(PHP_OS) == "linux") {
  define('ZLIBRARY_DIR', "/var/www/html/Library");

  // ini_set("/var/www/html/Library");

} else {
  define('ZLIBRARY_DIR', "C:\Web\ZLibrary");

}


if (get_magic_quotes_gpc()) {
  $_REQUEST = array_map('stripslashes', $_REQUEST);
  $_GET = array_map('stripslashes', $_GET);
  $_POST = array_map('stripslashes', $_POST);
  $_COOKIE = array_map('stripslashes', $_COOKIE);
}

if (ini_get("safe_mode")) {
  echo "<br />safe_mode=".ini_get("safe_mode");
  echo "<br />safe_mode_exec_dir=".ini_get("safe_mode_exec_dir");
  echo "<br />safe_mode_gid=".ini_get("safe_mode_gid");
  echo "<br />safe_mode_include_dir=".ini_get("safe_mode_include_dir");
  echo "<br />safe_mode_allowed_env_vars=".ini_get("safe_mode_allowed_env_vars");
  echo "<br />dirname=".dirname(__FILE__);
  echo "<pre>";
  print_r($arrPathInfo);
  echo "</pre>";
  echo "<br />FILE=".__FILE__;
  echo "<br />open_basedir=".ini_get("open_basedir");
  echo "<br />PHP_SELF=".$_SERVER["PHP_SELF"];
  echo "<br />DOCUMENT_ROOT=".$_SERVER["DOCUMENT_ROOT"];

}

$arrPathInfo = pathinfo(__FILE__);

$sstrSiteRootDir = $arrPathInfo["dirname"].DIRECTORY_SEPARATOR;
$sstrSiteRootDir = str_replace("\\", "/", $sstrSiteRootDir);
define('SiteRootDir', $sstrSiteRootDir);

$sstrScriptName = basename($_SERVER["PHP_SELF"]);
define('ScriptName', $sstrScriptName);

$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

$sparOption = intval($_REQUEST["o"]);
define('parOption', $sparOption);

$sparAction = intval($_REQUEST["a"]);
define('parAction', $sparAction);

$sparTarget = $_REQUEST["t"];
define('parTarget', $sparTarget);

$sparType   = intval($_REQUEST["y"]);
define('parType', $sparType);

$sparPrint = intval($_REQUEST["p"]);
define('parPrint', $sparPrint);

$sparWhere = $_REQUEST["w"];
define('parWhere', $sparWhere);

$sparDateBegin = $_REQUEST["db"];
define('parDateBegin', $sparDateBegin);

$sparDateEnd = $_REQUEST["de"];
define('parDateEnd', $sparDateEnd);

if (strlen($_REQUEST["debugfile"])) $spardebugFile = $_REQUEST["debugfile"];
define('debugFile', $spardebugFile);

if (strlen($_REQUEST["debugfunction"])) $spardebugFunction = $_REQUEST["debugfunction"];
define('debugFunction', $spardebugFunction);

if (strlen($_REQUEST["debuglevel"])) $spardebugLevel= $_REQUEST["debuglevel"];
define('debugLevel', $spardebugLevel);

if (strlen($_REQUEST["debug"])) {
	$sparDebug         = $_REQUEST["debug"];
	$_SESSION["debug"] = $sparDebug;
}

if (strlen($_REQUEST["debugon"])) {
	$sparDebugON         = $_REQUEST["debugon"];
	$_SESSION["debugON"] = $sparDebugON;
}

if ($sparDebugON) $_SESSION["debugON"] = $sparDebugON;

if ($sparDebugON && $sparDebug) $_SESSION["debug"] = $sparDebug;

if ($_SESSION["debugON"] && $_SESSION["debug"]) $sparDebug = $_SESSION["debug"];

define('parDebug', $sparDebug);

// define('parDebug', 1);

$sparTemplateFile = $_REQUEST["temp"];
define('templateFile', $sparTemplateFile);

$sbmtButton = $_REQUEST["bmtbutton"];

$sparLog = intval($_REQUEST["l"]);

$sparLog = 1;

$sstrDomain        = strtolower($_SERVER["SERVER_NAME"]);
define('Domain', $sstrDomain);

$sstrRemoteAddress = strtolower($_SERVER["REMOTE_ADDR"]);
define('RemoteAddress', $sstrRemoteAddress);

$sstrRequestMethod = strtolower($_SERVER["REQUEST_METHOD"]);
define('RequestMethod', $sstrRequestMethod);

$sstrHTTPHost      = strtolower($_SERVER["HTTP_HOST"]);
define('HTTPHost', $sstrHTTPHost);

$sstrHTTPReferer   = strtolower($_SERVER["HTTP_REFERER"]);
define('HTTPReferer', $sstrHTTPReferer);

$sstrHTTPUserAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
define('HTTPUserAgent', $sstrHTTPUserAgent);

$sstrServerPort    = strtolower($_SERVER["SERVER_PORT"]);
define('ServerPort', $sstrServerPort);

define("CrLf", "\r\n");

if ($sparAjax) {
  define("webCrLf", "\r\n");
} else {
  define("webCrLf", "<br />");
}

// echo "<br />".Domain.":".ServerPort;

switch (Domain.":".ServerPort) {
	default:
    // mssql - Connection
    define("mssqlHost", "mysql01.rrpro.com.br");
    define("mssqlDatabase", "rrpro");

    define("mssqlUser", "rrpro");
    define("mssqlPassword", "RRPro432");

}
?>
