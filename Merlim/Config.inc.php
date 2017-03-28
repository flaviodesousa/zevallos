<?php
// ================================================================
// /Config.inc.php
// ----------------------------------------------------------------
// Nome     : Arquivo de configuração
// Home     : merlim.d2.net.br
// Criacao  : 25/08/2010 11:00:00
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF
// Copyright: 2007-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE);

session_cache_limiter('Merlim');
$sintCacheLimiter = session_cache_limiter();

session_cache_expire(20);
$sintCacheExpire = session_cache_expire();

session_start();

$sstrSessionID = session_id();

ob_start("ob_gzhandler");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ob_end_flush();

set_time_limit(300);

$sintExecucao = microtime();

if (strtolower(PHP_OS) == "linux") {
  define('ZLIBRARY_DIR', "/var/www/html/new/Library");

} else {
  define('ZLIBRARY_DIR', "C:\Web\ZLibrary");

}

if (get_magic_quotes_gpc()) {
  $_REQUEST = array_map('stripslashes', $_REQUEST);
  $_GET = array_map('stripslashes', $_GET);
  $_POST = array_map('stripslashes', $_POST);
  $_COOKIE = array_map('stripslashes', $_COOKIE);
}

$sstrSiteRootDir = dirname(__FILE__).DIRECTORY_SEPARATOR;
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

$sparEmulate = intval($_REQUEST["e"] == 1);

if (strlen($_REQUEST["debug"])) $sparDebug = $_REQUEST["debug"];

define('parDebug', $sparDebug);

// define('parDebug', 1);

$sparTemplate = $_REQUEST["temp"];

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

define('preLogin', "sgp");
define('preProgram', "log");

define('imgLogoLogon', '/Images/UnidentalLogon.jpg');

switch ($sstrServerPort) {
  case 9083:
    if (!defined("SQLUser")) define("SQLUser", "sipbeta");
    if (!defined("SQLPassword")) define("SQLPassword", "SIPbeta4321%");

    if (!defined("SQLHost")) define("SQLHost", "localhost");
    if (!defined("SQLDatabase")) define("SQLDatabase", "beta_istcartoes_com_br");

    if (!defined("FBUser")) define("FBUser", "PDA");
    if (!defined("FBPassword")) define("FBPassword", "ppaa5021");

    if (!defined("FBDatabase")) define("FBDatabase", "192.168.200.55:C:\FDBCorporate\ISTCORP.FDB");

  case 8023:
    if (!defined("SQLUser")) define("SQLUser", "Unidental");
    if (!defined("SQLPassword")) define("SQLPassword", "Un1dental2010");

    if (!defined("SQLHost")) define("SQLHost", "192.168.0.3");
    if (!defined("SQLDatabase")) define("SQLDatabase", "sg_unidental_com_br");

		// putenv("ORACLE_HOME=C:\orant");
		// putenv("ORACLE_SID=xe");
		// putenv("TNS_ADMIN=C:\orant\NET80\ADMIN");
		// putenv("NLS_CHARACTERSET=WE8MSWIN1252");
		// putenv("NLS_LANG=ENGLISH_UNITED KINGDOM.WE8MSWIN1252");
		// putenv("NLS_DATE_FORMAT=DD/MM/YYYY");
		// $_SESSION["conf_mg"]=200;

    if (!defined("OracleUser")) define("OracleUser", "unidental");
    if (!defined("OraclePassword")) define("OraclePassword", "Un1dental2010");

    if (!defined("OracleServer")) define("OracleServer", "(DESCRIPTION=
		          (ADDRESS_LIST=
		            (ADDRESS=(PROTOCOL=TCP)
		              (HOST=192.168.0.84)(PORT=1521)
		            )
		          )
		          (CONNECT_DATA=(SERVICE_NAME=xe))
		     )");

	default:
    // if (!defined("SQLHost")) define("SQLHost", "192.168.0.3");
		
		if (!defined("SQLHost")) define("SQLHost", "sql.direito2.com.br");
		if (!defined("SQLDatabase")) define("SQLDatabase", "merlim_d2_net_br");
    
    if (!defined("SQLUser")) define("SQLUser", "D2Merlim");
    if (!defined("SQLPassword")) define("SQLPassword", "D2432$");

		// putenv("ORACLE_HOME=C:\orant");
		// putenv("ORACLE_SID=xe");
		// putenv("TNS_ADMIN=C:\orant\NET80\ADMIN");
		// putenv("NLS_CHARACTERSET=WE8MSWIN1252");
		// putenv("NLS_LANG=ENGLISH_UNITED KINGDOM.WE8MSWIN1252");
		// putenv("NLS_DATE_FORMAT=DD/MM/YYYY");
		// $_SESSION["conf_mg"]=200;

    if (!defined("OracleUser")) define("OracleUser", "unidental");
    if (!defined("OraclePassword")) define("OraclePassword", "Un1dental2010");

    if (!defined("OracleServer")) define("OracleServer", "(DESCRIPTION=
		          (ADDRESS_LIST=
		            (ADDRESS=(PROTOCOL=TCP)
		              (HOST=192.168.0.84)(PORT=1521)
		            )
		          )
		          (CONNECT_DATA=(SERVICE_NAME=xe))
		     )");

    // 'localhost:c:/firebird/test_db/test.fdb'

}
?>
