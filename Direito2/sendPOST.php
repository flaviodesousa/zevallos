<?php
  error_reporting(E_ALL & ~E_NOTICE);

  require_once('d2lib.inc.php');

  define('msUser', 'Direito2ComBR');
  define('msPassword', 'Direito2913947&');
  define('msHost', 'sql.direito2.com.br');
  define('msDatabase', 'direito2_com_br');

  msConnect();

	$sql = "SELECT TOP 1
    pagCodigo
  , pagNome
  -- , P1.pagResumo
  , pagDescricao
  , CONVERT(VARCHAR, pagInclusao, 121) pagInclusao
  , CONVERT(VARCHAR, pagReferencia, 121) pagReferencia
  , pagArquivoPasta
  , pagArquivoNome
  , pagURL
  , pagMySQLHash
  , fonNome
  , fonSigla
  , CASE WHEN pagAtivo IS NULL THEN 0 ELSE pagAtivo END pagAtivo
	FROM pubPaginas
	LEFT JOIN pubFonte ON pagFonte = fonCodigo
	WHERE pagCodigo = 302426";

	$msQuery = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if ($msRow = mssql_fetch_assoc($msQuery))
    $jsonNoticia = array2JSON($msRow);

  foreach ($msRow as &$a)
    $a = utf8_encode(accentString2Latin1($a, 1));

  $eJSON = json_encode($msRow);

  $curl = new curl();

  $pathInfo = pathinfo(__FILE__);
  $siteRootDir = str_replace('\\', '/', $pathInfo["dirname"]).'/';

  $curl->agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0';
  $curl->baseURL = 'http://update.d2.net.br';
  $curl->baseDir = $siteRootDir;
  $curl->cookieFile = 'sendPOST';
  // urlencode()

  $curl->open();

  echo "<hr /><b>Windows</b>";
  echo $eJSON;

  $curl->post('/receivePOST.php', "jsonNoticia=$jsonNoticia");

  echo "<hr /><b>Linux</b>";
  echo $curl->result;

