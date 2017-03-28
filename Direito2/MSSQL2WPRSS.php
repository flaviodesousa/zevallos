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

define('msUser', 'ZevallosComBR');
define('msPassword', 'Zev452%');
define('msHost', 'sql.direito2.com.br');
define('msDatabase', 'ruben_zevallos_com_br');

$yearStart  = intval($_REQUEST['y']);
$iteracao   = intval($_REQUEST['i']);
$time       = $_REQUEST['t'];
$start      = $_REQUEST['s'];
$limit      = intval($_REQUEST['l']);
$pages      = intval($_REQUEST['p']);
$pagesStart = intval($_REQUEST['ps']);

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

if (!$yearStart) {
  $sql = "SELECT TOP 1 YEAR(pagInclusao) yearStart FROM pubPaginas ORDER BY YEAR(pagInclusao)";

  $msQueryCom = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if ($msRow = mssql_fetch_assoc($msQueryCom)) $yearStart = $msRow['yearStart'];

  mssql_free_result($msQueryCom);

  $sql = "SELECT count(pagCodigo) pagesStart FROM pubPaginas	WHERE pagAtivo = 1 AND YEAR(pagInclusao) = $yearStart";

  $msQueryCom = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if ($msRow = mssql_fetch_assoc($msQueryCom)) $pagesStart = $msRow['pagesStart'];

  mssql_free_result($msQueryCom);
}

echo "<h1>Exportador do Onyx Manager para o RSS 2.0 do WordPress</h1>";
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

generate2RSS();

msDisconnet();

function generate2RSS() {
  global $limit, $pages, $yearStart;

  // INSERT INTO wp_oldurls (oldurl, newurl) SELECT CONCAT('/', YEAR(post_date), '/',  MONTH(post_date), '/', DAY(post_date), '/', 'Pagina', id, '.htm'), CONCAT('/?p=', id) FROM wp_posts

	$sql = "SELECT
    P2.pagNome fonte
  , P1.pagCodigo
  , P1.pagNome
  , P1.pagResumo
  , P1.pagDescricao
  , RIGHT('0000' + CONVERT(varchar(4), DATEPART(YEAR, P1.pagInclusao)), 4)
    + '/' + RIGHT('00' + CONVERT(varchar(2), DATEPART(MONTH, P1.pagInclusao)), 2)
    + '/' + RIGHT('00' + CONVERT(varchar(2), DATEPART(DAY, P1.pagInclusao)), 2) pasta
  , 'Pagina' + CONVERT(varchar(4), P1.pagCodigo) nome
  , RIGHT('0000' + CONVERT(varchar(4), DATEPART(YEAR, P1.pagInclusao)), 4)
    + '-' + RIGHT('00' + CONVERT(varchar(2), DATEPART(MONTH, P1.pagInclusao)), 2)
    + '-' + RIGHT('00' + CONVERT(varchar(2), DATEPART(DAY, P1.pagInclusao)), 2)
    + ' ' + RIGHT('00' + CONVERT(varchar(2), DATEPART(HOUR, P1.pagInclusao)), 2)
    + ':' + RIGHT('00' + CONVERT(varchar(2), DATEPART(MINUTE, P1.pagInclusao)), 2)
    + ':' + RIGHT('00' + CONVERT(varchar(2), DATEPART(SECOND, P1.pagInclusao)), 2) post_date
  , RIGHT('0000' + CONVERT(varchar(4), DATEPART(YEAR, P1.pagInclusao)), 4)
    + '-' + RIGHT('00' + CONVERT(varchar(2), DATEPART(MONTH, P1.pagInclusao)), 2)
    + '-' + RIGHT('00' + CONVERT(varchar(2), DATEPART(DAY, P1.pagInclusao)), 2)
    + ' ' + RIGHT('00' + CONVERT(varchar(2), DATEPART(HOUR, P1.pagInclusao)), 2)
    + ':' + RIGHT('00' + CONVERT(varchar(2), DATEPART(MINUTE, P1.pagInclusao)), 2)
    + ':' + RIGHT('00' + CONVERT(varchar(2), DATEPART(SECOND, P1.pagInclusao)), 2) post_date_gmt
  , CASE WHEN P1.pagAtivo IS NULL THEN 0 ELSE P1.pagAtivo END ativo
  , P1.pagVisualizacaoVezes visualizacaovezes
  , CONVERT(VARCHAR, P1.pagVisualizacao, 121) visualizacao
  , P1.pagPalavrasChave palavraschave
	FROM pubPaginas P1
	LEFT JOIN pubPaginas P2 ON P1.pagPai = P2.pagCodigo
	LEFT JOIN pubPaginas P3 ON P2.pagPai = P3.pagCodigo
	LEFT JOIN pubPaginas P4 ON P3.pagPai = P4.pagCodigo
	LEFT JOIN pubPaginas P5 ON P4.pagPai = P5.pagCodigo
	-- WHERE YEAR(P1.pagInclusao) = $yearStart
	AND P5.pagCodigo = 1
	ORDER BY P1.pagInclusao, P1.pagCodigo";

	$msQuery = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br /><pre>$sql</pre>");

  $xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>
<!-- generator=\"WordPress/3.1.2\" created=\"2011-05-09 03:03\" -->
<rss version=\"2.0\"
	xmlns:excerpt=\"http://wordpress.org/export/1.1/excerpt/\"
	xmlns:content=\"http://purl.org/rss/1.0/modules/content/\"
	xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\"
	xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
	xmlns:wp=\"http://wordpress.org/export/1.1/\"
>

<channel>
	<title>Ruben Zevallos Jr</title>
	<link>http://ruben.d2.net.br</link>
	<description>Idéias, pensamentos e tudo mais</description>
	<pubDate>Mon, 09 May 2011 03:03:52 +0000</pubDate>
	<language>pt-br</language>
	<wp:wxr_version>1.1</wp:wxr_version>
	<wp:base_site_url>http://ruben.d2.net.br</wp:base_site_url>
	<wp:base_blog_url>http://ruben.d2.net.br</wp:base_blog_url>

	<generator>http://wordpress.org/?v=3.1.2</generator>";

  while($msRow = mssql_fetch_assoc($msQuery)) {

  $date = DateTime::createFromFormat('Y-m-d G:i:s', $msRow[post_date]);

  /*
  echo "<br />msRow[post_date]=", $msRow[post_date];

  var_dump($date);

  echo "<br />date_format=", date_format($date, 'D, d M Y G:i:s O');
  */

  /*
		<wp:post_name>procure-somente-afinidades</wp:post_name>
		<link>http://ruben.d2.net.br/2011/05/relacionamento/procure-somente-afinidades/</link>
  */

  echo $msRow[pagCodigo], ", ";

  $msRow[pagDescricao] = preg_replace('%<a href="/ApresentaSite\.asp\?o=110&txtFind=[^"]+"><strong>[^<]+</strong></a>%i', '', $msRow[pagDescricao]);

  if ($msRow[palavraschave]) {
    // Piada:Azarados
    preg_match_all("/(?P<tag1>[^:]+):(?P<tag2>.*)$/", $msRow[palavraschave], $Matches, PREG_OFFSET_CAPTURE);

    $tag = "";

    foreach ($Matches[0] as $key => $value) {
      $tag1 = $Matches["tag1"][$key][0];
      $tag2 = $Matches["tag2"][$key][0];

      $tag .= '<category domain="post_tag" nicename="'.NormalizeAccent($tag1).'"><![CDATA['.$tag1.']]></category>
<category domain="post_tag" nicename="'.NormalizeAccent($tag2).'"><![CDATA['.$tag2.']]></category>
            ';
    }
  }
  // <category domain="category" nicename="ladroes"><![CDATA[Ladrões]]></category>


  $xml .= "<item>
		<title>".$msRow[pagNome]."</title>
		<pubDate>".$date->format('D, d M Y G:i:s O')."</pubDate>
		<dc:creator>zevallos</dc:creator>
		<guid isPermaLink=\"false\">http://ruben.zevallos.com.br/?p=".$msRow[pagCodigo]."</guid>
		<description></description>
		<content:encoded><![CDATA[".$msRow[pagDescricao]."]]></content:encoded>
		<excerpt:encoded><![CDATA[]]></excerpt:encoded>
		<wp:post_id>".$msRow[pagCodigo]."</wp:post_id>
		<wp:post_date>".$msRow[post_date]."</wp:post_date>
		<wp:post_date_gmt>".$msRow[post_date_gmt]."</wp:post_date_gmt>
		<wp:comment_status>open</wp:comment_status>
		<wp:ping_status>open</wp:ping_status>
		<wp:status>publish</wp:status>
		<wp:post_parent>0</wp:post_parent>
		<wp:menu_order>0</wp:menu_order>
		<wp:post_type>post</wp:post_type>
		<wp:post_password></wp:post_password>
		<wp:is_sticky>0</wp:is_sticky>
		<category domain=\"category\" nicename=\"".$msRow[fonte]."\"><![CDATA[".$msRow[fonte]."]]></category>
		$tag
		<wp:postmeta>
			<wp:meta_key>_edit_last</wp:meta_key>
			<wp:meta_value><![CDATA[1]]></wp:meta_value>
		</wp:postmeta>
	</item>";

    ob_flush(); flush();
  }

  mssql_free_result($msQuery);

  $xml .= "</channel>
  </rss>";

  $pathInfo = pathinfo(__FILE__);
  $siteRootDir = str_replace('\\', '/', $pathInfo["dirname"]).'/';

  $fileName = $siteRootDir."Ruben-$yearStart.xml";

  $file = @fopen($fileName, 'w') or die("<br />Cannot open $fileName");

  fwrite($file, $xml, strlen($xml)) or die("<br />Cannot write on $fileName bytes".strlen($xml));

  fclose($file);

  die();

}


$url = 'http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"]."?y=$yearStart&l=$limit&i=$iteracao&s=$start&t=$time&p=$pages&ps=$pagesStart";

echo "<br /><br />Próxima iteração (<a href=\"$url\">$iteracao</a>)<br />";
echo date('Y/m/d H:i:s');

// die('<br />Arrghhhh! &lt;-- I just died here!');

// http://old.d2.net.br:8080/MSSQL2MySQL.php?i=21&p=0&s=1303520185&t=1303520185.0526&hs=913947;;;;;;;;;;;;;;;;;;;;

echo "<script type=\"text/javascript\">
  location.href='$url';
</script>";
