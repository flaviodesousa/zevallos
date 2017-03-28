<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('config.inc.php');
require_once('d2lib.inc.php');

session_cache_limiter('d2Update');
$sintCacheLimiter = session_cache_limiter();

session_cache_expire(20);
$sintCacheExpire = session_cache_expire();

session_start();

ob_start("ob_gzhandler");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ob_end_flush();

set_time_limit(300);

define('msUser', 'ZevallosComBR');
define('msPassword', 'Zev452%');
define('msHost', '(local)');
define('msDatabase', 'zevallos_com_br');

switch (php_uname('n')) {
  case 'hm4204': // Locaweb
    define("myUser", "zyc9");
    define("myPassword", "D2432*");
    define("myHost", "mysql09.zyc.com.br");
    define("myDatabase", "zyc9");
    break;

  default:
    // echo php_uname('n');
    define("myUser", "root");
    define("myPassword", "SuperRuben99");
    define("myHost", "localhost");
    define("myDatabase", "direito2");

}

msConnect();

myConnect();

$sql = "SELECT munCode, munName, munLatitude, munLongitude, munAltitude, munArea, munState
        , UFCOD, MUNCOD, MUNCODDV, SITUACAO, ANOINST, MUNSINP, MUNSIAFI, MUNNOMEX, LATITUDE, LONGITUDE, AREA, MESOCOD, MICROCOD
        , CASE WHEN CAPITAL = 'S' THEN 1 ELSE 0 END CAPITAL
        , CASE WHEN AMAZONIA = 'S' THEN 1 ELSE 0 END AMAZONIA
        , CASE WHEN FRONTEIRA = 'S' THEN 1 ELSE 0 END FRONTEIRA
        , CASE SITUACAO WHEN 'ATIVO' THEN 1 WHEN 'IGNOR' THEN 2 ELSE 0 END SITUACAO
        FROM geoBRMunicipios
        LEFT JOIN CADMUN ON MUNCOD = munCode
        ORDER BY munState, munNome";

$msQuery = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br /><pre>$sql</pre>");

while($msRow = mssql_fetch_object($msQuery)) {
  echo "<br />$msRow->munCode - $msRow->munName";

  $sql = "INSERT INTO tb_cidade
  (nm_cidade, nu_latitude, nu_longitude, nu_altitude, nu_area, nu_instalacao, fl_amazonia, fl_fronteira, fl_capital, fl_mesoarea, fl_microarea, fl_ativo, sg_estado, nu_estado, nu_id, nu_iddv) VALUES
  ('$msRow->munName'
  , $msRow->munLatitude
  , $msRow->munLongitude
  , $msRow->munAltitude
  , $msRow->munArea
  , $msRow->ANOINST
  , $msRow->AMAZONIA
  , $msRow->FRONTEIRA
  , $msRow->CAPITAL
  , $msRow->MESOCOD
  , $msRow->MICROCOD
  , $msRow->SITUACAO
  , '$msRow->munState'
  , $msRow->UFCO
  , $msRow->MUNCOD
  , $msRow->MUNCODDV)";

  myQuery($sql);
}

myDisconnect();

msDisconnect();
