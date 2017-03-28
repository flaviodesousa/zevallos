<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('d2lib.inc.php');

session_cache_limiter('MSSQL2MySQL');
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

set_time_limit(20000);

define('msUser', 'Direito2ComBR');
define('msPassword', 'Direito2913947&');
define('msHost', 'sql.direito2.com.br');
define('msDatabase', 'direito2_com_br');

$iteracao   = intval($_REQUEST['i']);
$time       = $_REQUEST['t'];
$start      = $_REQUEST['s'];
$limit      = intval($_REQUEST['l']);
$pages      = intval($_REQUEST['p']);
$pagesStart = intval($_REQUEST['ps']);

$hs         = intval($_REQUEST['hs']);

if (!$limit) $limit = 300;
if (!$pagesTotal) $pagesTotal = 0;
if (!$iteracao) $iteracao = 0;

if ($time) {
  $timeD = microtime_float() - $time;
} else {
  $time = microtime_float();
  $start = time();
}

msConnect();

if (!$pagesStart) {
  $sql = "SELECT count(A1.pagCodigo) pagesStart FROM pubPaginas A1
					LEFT JOIN pubPaginas A2 ON A2.pagCodigo = A1.pagPai
					LEFT JOIN pubPaginas A3 ON A3.pagCodigo = A2.pagPai 
					LEFT JOIN pubPaginas A4 ON A4.pagCodigo = A3.pagPai 
					LEFT JOIN pubPaginas A5 ON A5.pagCodigo = A4.pagPai 
					LEFT JOIN pubPaginas A6 ON A6.pagCodigo = A5.pagPai 
					LEFT JOIN pubFonte ON A1.pagFonte = fonCodigo
					WHERE A6.pagCodigo = 1 AND A1.pagMySQL IS NULL";

  $msQueryCom = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if ($msRow = mssql_fetch_assoc($msQueryCom)) $pagesStart = $msRow['pagesStart'];
}

echo "<h1>Exportador do Direito 2</h1>";
echo "Exporta do MSSQL para o MySQL via POST usando JSON<hr />";
echo "Início: ", date('Y/m/d H:i:s', $start);
if ($pagesStart && $iteracao && $limit) echo " estimativa para o fim: ", strTime(($timeD / $iteracao) * ($pagesStart / $limit)),  "<br />";
echo "Iterações: ", number_format($iteracao), "<br />";
echo "Páginas iniciais: ", number_format($pagesStart), "<br />";
echo "Páginas processadas: ", number_format($pages), "<br />";
echo "Tempo gasto: ", strTime($timeD), "<br />";
if ($iteracao) echo "Tempo médio por iteração: ", strTime($timeD / $iteracao), "<br />";
// if ($pages) echo "Tempo médio por página: ", strTime($timeD / $pages, 1), "<br />";

if ($pagesStart && $limit) echo "Quantidade estimada de iterações: ", number_format($pagesStart / $limit), "<br />";
if ($pagesStart && $limit && $iteracao) echo "Tempo estimado: ", strTime(($timeD / $iteracao) * (($pagesStart - $pages) / $limit)), "<br />";

if ($_REQUEST['d']) echo "<pre>";

$iteracao++;

if ($_REQUEST['o'] == 2023) {
  echo '<br />Zerando os MySQLHash';

  $sql = "UPDATE pubPaginas
            SET pagMySQLHash = NULL
              , pagMySQL = NULL";

  @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
}

send2MySQL();

msDisconnet();

function send2MySQL() {
  global $limit, $pages, $hs;

	$sql = "SELECT TOP $limit
					A2.pagNome fonSigla
					, fonNome
					, A1.pagCodigo
					, A1.pagNome
					-- , P1.pagResumo
					, A1.pagDescricao
					, CONVERT(VARCHAR, A1.pagInclusao, 121) pagInclusao
					, DATEPART(YEAR, A1.pagInclusao) pagInclusao_year
					, DATEPART(MONTH, A1.pagInclusao) pagInclusao_month
					, DATEPART(DAY, A1.pagInclusao) pagInclusao_day
					, A1.pagArquivoPasta
					, A1.pagArquivoNome
					, A1.pagURL
					, A1.pagMySQLHash
					, CASE WHEN A1.pagAtivo IS NULL THEN 0 ELSE A1.pagAtivo END pagAtivo
					, A1.pagVisualizacaoVezes
					, CONVERT(VARCHAR, A1.pagVisualizacao, 121) pagVisualizacao
					FROM pubPaginas A1
					LEFT JOIN pubPaginas A2 ON A2.pagCodigo = A1.pagPai 
					LEFT JOIN pubPaginas A3 ON A3.pagCodigo = A2.pagPai 
					LEFT JOIN pubPaginas A4 ON A4.pagCodigo = A3.pagPai 
					LEFT JOIN pubPaginas A5 ON A5.pagCodigo = A4.pagPai 
					LEFT JOIN pubPaginas A6 ON A6.pagCodigo = A5.pagPai 
					LEFT JOIN pubFonte ON A1.pagFonte = fonCodigo
					WHERE A6.pagCodigo = 1 AND A1.pagMySQL IS NULL
					-- AND A1.pagReferencia IN (4)
					ORDER BY A1.pagInclusao, A1.pagCodigo";

	$msQuery = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  $curl = new curl();

  $pathInfo = pathinfo(__FILE__);
  $siteRootDir = str_replace('\\', '/', $pathInfo["dirname"]).'/';

  $curl->agent      = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0';
  $curl->baseDir    = $siteRootDir;

  $doUpdate = 1;

  // echo "<br />SERVER_NAME=", $_SERVER["SERVER_NAME"];

  switch ($_SERVER["SERVER_NAME"]) {
    case 'direito21.zyc.com.br':
      $doUpdate = 0;
      $curl->baseURL    = 'http://old2.d2.net.br';
      $curl->cookieFile = 'old2.d2.net.br';
      break;

    default:
      $curl->baseURL    = 'http://update.d2.net.br';
      $curl->cookieFile = 'update.d2.net.br';

  }

  $curl->open();

  $curl->step = '<br />Iniciando a sessão com '.$curl->baseURL;
  $curl->post('/d2Update.php', 'hs='.$hs);

  if ($_REQUEST['d']) echo " &lt-- $curl->result";

  $i = 0;

  while($msRow = mssql_fetch_assoc($msQuery)) {
  	$msRow[pagNome]      = accentString2Latin1($msRow[pagNome], 0, 1);
  	$msRow[pagDescricao] = accentString2Latin1($msRow[pagDescricao], 0, 1);
  	$msRow[fonNome]      = accentString2Latin1($msRow[fonNome], 0, 1);
  	
    $jsonNoticia = array2JSON($msRow);

    // var_dump(json2Array($jsonNoticia));
    // die('<br />Arrghhhh! &lt;-- I just died here!');

  	$sql = "SELECT
    	comCodigo
    	, comUsuario
    	, comOrigem
    	, comNome
    	, comEMail
    	, comURL
    	, comCidade
    	, comEstado
    	, comAtividade
    	, comEMailMostrar
    	, comComentario
    	, CASE WHEN comNotificar IS NULL THEN 0 ELSE comNotificar END comNotificar
    	, comModerado
    	, comIP
    	, comSessao
    	, CASE WHEN comAtivo IS NULL THEN 0 ELSE comAtivo END comAtivo
      , CONVERT(VARCHAR, comValidado, 121) comValidado
      , CONVERT(VARCHAR, comInclusao, 121) comInclusao
  	FROM pubComentarios
  	WHERE comPagina = ".$msRow["pagCodigo"]."
  	ORDER BY comCodigo";

  	$msQueryCom = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    $jsonComentarios = array();

    while($msRowCom = mssql_fetch_assoc($msQueryCom)) {
    	$msRowCom[comNome]       = accentString2Latin1($msRowCom[comNome], 0, 1);
  		$msRowCom[comUsuario]    = accentString2Latin1($msRowCom[comUsuario], 0, 1);
  		$msRowCom[comCidade]     = accentString2Latin1($msRowCom[comCidade], 0, 1);
  		$msRowCom[comOrigem]     = accentString2Latin1($msRowCom[comOrigem], 0, 1);
  		$msRowCom[comComentario] = accentString2Latin1($msRowCom[comComentario], 0, 1);
  		$msRowCom[comEMail]      = accentString2Latin1($msRowCom[comEMail], 0, 1);
  		
    	$jsonComentarios[] = array2JSON($msRowCom);
    }

      $jsonComentarios = array2JSON($jsonComentarios);

    mssql_free_result($msQueryCom);

  	$sql = "SELECT
    	puvAno
    	, puvMes
    	, puvDia
    	, puvHora
    	, puvQuarter
    	, puvDayOfYear
    	, puvWeek
    	, puvWeekDay
    	, puvQuantidade
      , CONVERT(VARCHAR, puvInclusao, 121) puvInclusao
    FROM pubPaginasVisualizacoes
  	WHERE puvPagina = ".$msRow["pagCodigo"]."
  	ORDER BY puvCodigo";

  	$msQueryView = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    $jsonVisualicoes = array();

    while($msRowView = mssql_fetch_assoc($msQueryView))
      $jsonVisualicoes[] = array2JSON($msRowView);

    $jsonVisualicoes = array2JSON($jsonVisualicoes);

    mssql_free_result($msQueryView);

    $i++;
    $pages++;

    if ($_REQUEST['d']) {
      $curl->step = "<br />$i - ".$msRow['fonSigla']." - ".$msRow['pagCodigo'];

    } else {
      if ($i > 1) {
        echo ", ";

      } else {
        echo "<br />";
      }

      echo $i;
    }

    $curl->post('/d2Update.php', "n=$jsonNoticia&c=$jsonComentarios&v=$jsonVisualicoes");

    if ($_REQUEST['d']) echo " &lt-- $curl->result";

    $arrResult = explode(";", $curl->result);

    // die('<br />Arrghhhh! &lt;-- I just died here!');

    if ($arrResult[0] && $doUpdate) {
      $sql = "UPDATE pubPaginas
              SET pagMySQLHash = '".$arrResult[1]."'
              , pagMySQL = GetDate()
              WHERE pagCodigo =".$arrResult[0];

    	@mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
    }

    // echo "<br /><pre>".print_r($arrResult, 1);

    ob_flush(); flush();
  }

  mssql_free_result($msQuery);
}

$url = 'http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"]."?hs=$hs&i=$iteracao&l=$limit&s=$start&t=$time&p=$pages&ps=$pagesStart";

echo "<br /><br />Próxima iteração (<a href=\"$url\">$iteracao</a>)<br />";
echo date('Y/m/d H:i:s');

// die('<br />Arrghhhh! &lt;-- I just died here!');

// http://old.d2.net.br:8080/MSSQL2MySQL.php?i=21&p=0&s=1303520185&t=1303520185.0526&hs=913947;;;;;;;;;;;;;;;;;;;;

echo "<script type=\"text/javascript\">
  location.href='$url';
</script>";
