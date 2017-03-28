<?php
require_once('d2lib.inc.php');

define('msUser', 'Direito2ComBR');
define('msPassword', 'Direito2913947&');
define('msHost', 'sql.direito2.com.br');
define('msDatabase', 'direito2_com_br');

msConnect();

$sql = "SELECT TOP 1
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
				WHERE A6.pagCodigo = 1 -- AND A1.pagMySQL IS NULL
				AND A1.pagArquivoPasta = '/asen/2006/set/27'
				AND A1.pagArquivoNome = '/principais-pontos-do-estatuto-do-idoso.htm'
				ORDER BY A1.pagInclusao, A1.pagCodigo";

$msQuery = @mssql_query($sql, msConn) or die("<br /><b>".ScriptName."</b>: A query do MSSQL ".msHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

if($msRow = mssql_fetch_assoc($msQuery)) {
	$msRow['pagNome']      = accentString2Latin1($msRow['pagNome'], 0, 1);
	$msRow['pagDescricao'] = accentString2Latin1($msRow['pagDescricao'], 0, 1);
	$msRow['fonNome']      = accentString2Latin1($msRow['fonNome'], 0, 1);

  $eJSON = json_encode($msRow);

  if (json_last_error()) {
    echo "<br />", json_last_error();

  	switch(json_last_error()) {
  		case JSON_ERROR_DEPTH:
  			echo ' - The maximum stack depth has been exceeded';
  			break;

  		case JSON_ERROR_CTRL_CHAR:
  			echo ' - Control character error, possibly incorrectly encoded';
  			break;

  		case JSON_ERROR_SYNTAX:
  			echo ' - Syntax error';
  			break;

  		case JSON_ERROR_NONE:
  			echo ' - No error has occurred';
  			break;

  		case JSON_ERROR_STATE_MISMATCH:
  			echo ' - Invalid or malformed JSON';
  			break;

  		case JSON_ERROR_UTF8:
  			echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
  			break;
  	}
  	echo '<pre>'.var_dump($array, 1);
  	die();
  }

  echo "<pre>";
  var_dump($eJSON);
  var_dump($msRow);
}

die();

?>

<h1>SQL Injection</h1>

<?php
$name_bad = "' OR 1'";

$name_bad = mysql_real_escape_string($name_bad);

$query_bad = "SELECT * FROM customers WHERE username = '$name_bad'";
echo "Escaped Bad Injection: <br />" . $query_bad . "<br />";


$name_evil = "'; DELETE FROM customers WHERE 1 or username = '";

$name_evil = mysql_real_escape_string($name_evil);

$query_evil = "SELECT * FROM customers WHERE username = '$name_evil'";
echo "Escaped Evil Injection: <br />" . $query_evil;

die();

mysql_real_escape_string();

settype($offset, 'integer');
$query = "SELECT id, name FROM products ORDER BY name LIMIT 20 OFFSET $offset;";

// please note %d in the format string, using %s would be meaningless
$query = sprintf("SELECT id, name FROM products ORDER BY name LIMIT 20 OFFSET %d;", $offset);