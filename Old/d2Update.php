<?PHP
error_reporting(E_ALL & ~E_NOTICE);

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

switch (php_uname('n')) {
  case 'hm4204': // Locaweb
    define("myUser", "zyc9");
    define("myPassword", "D2432*");
    define("myHost", "mysql09.zyc.com.br");
    define("myDatabase", "zyc9");
    break;

  default:
    echo php_uname('n');
    define("myUser", "root");
    define("myPassword", "SuperRuben99");
    define("myHost", "localhost");
    define("myDatabase", "direito2");

}

myConnect();

$hs = $_REQUEST['hs'];

if ($hs) {
  if ($hs == '913947') {
    $sql = "SELECT SQL_CACHE cd_fornecedor
            FROM tb_fornecedor
            WHERE hs_fornecedor = '$hs'";

    $myQuery = myQuery($sql);

    if (!$myRow = mysql_fetch_object($myQuery)) {
      mysql_free_result($myQuery);

      $sql = "INSERT INTO tb_fornecedor
      (hs_fornecedor, nm_fornecedor, sg_fornecedor) VALUES
      ('$hs', 'Ruben Zevallos Jr.', 'RZJ')";

      $myQuery = myQuery($sql);
    } else {
      mysql_free_result($myQuery);
    }
  }

  $sql = "SELECT SQL_CACHE cd_fornecedor
          FROM tb_fornecedor
          WHERE hs_fornecedor = '$hs'";

  $myQuery = myQuery($sql);

  if ($myRow = mysql_fetch_object($myQuery)) {
    $cd_fornecedor = $myRow->cd_fornecedor;

    echo "cd_fornecedor=$cd_fornecedor";
    echo ";hs=$hs";

    mysql_free_result($myQuery);

    // Cria uma sessão
    $sql = "INSERT INTO tb_sessao
    (cd_fornecedor, ds_sessao, ds_ip4) VALUES
    ($cd_fornecedor, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."')";

    $myQuery = myQuery($sql);

    $_SESSION['cd_sessao'] = mysql_insert_id();

    echo ";cd_sessao=".$_SESSION['cd_sessao'].";";

  } else {
    mysql_free_result($myQuery);

    echo "Fatal Error - Chave do fornecedor errada!";
  }

} else {
  if ($_SESSION['cd_sessao']) {
    update();
  } else {
    echo "Fatal Error - Sem sessão!";
  }
}

myDisconnet();

function update() {
  $noticia     = json2Array($_REQUEST['n']);
  $comentarios = json2Array($_REQUEST['c'], 0);
  $visualicoes = json2Array($_REQUEST['v'], 0);

  /*

  echo '<pre>var_dump - <b>noticia</b><br />';
  var_dump(json2Array($_REQUEST['n']));
  echo "<br /><b>pagNome</b> = $noticia->pagNome";
  echo "<br /><b>pagArquivoPasta</b> = $noticia->pagArquivoPasta";
  echo "<br /><b>pagArquivoNome</b> = $noticia->pagArquivoNome";

  die('<br />Arrghhhh! &lt;-- I just died here!');

  echo '<pre>var_dump - <b>noticia</b><br />';
  var_dump($noticia);
  echo '<br /><b>pagNome</b> = ', $noticia->pagNome;
  echo '<br /><b>pagArquivoPasta</b> = ', $noticia->pagArquivoPasta;
  echo '<br /><b>pagArquivoNome</b> = ', $noticia->pagArquivoNome;

  echo '<hr />var_dump - <b>comentarios</b><br />';
  var_dump($comentarios);
  echo '<hr />var_dump - <b>visualicoes</b><br />';
  var_dump($visualicoes);
  die('here');
  */

  // Ajusta as barras "/" na pasta e nome do arquivo
  if (substr($noticia->pagArquivoPasta, -1) == "/") $noticia->pagArquivoPasta = substr($noticia->pagArquivoPasta, 0, strlen($noticia->pagArquivoPasta) - 1);
  if (substr($noticia->pagArquivoNome, 0, 1) == "/") $noticia->pagArquivoNome = substr($noticia->pagArquivoNome, 1);

  if (substr($noticia->pagArquivoPasta, 0, 1) != "/") $noticia->pagArquivoPasta = "/$noticia->pagArquivoPasta";

  // Hash MD5 da fonte tem a sigla da fonte + URL sem o dominio
  $md5Fonte  = md5("$noticia->fonSigla;$noticia->pagURL");

  // Hash MD5 da página tem a sigla do site + URL sem o dominio
  $md5Pagina = md5("D2;$noticia->pagArquivoPasta/$noticia->pagArquivoNome");

  echo $noticia->pagCodigo, ';', $md5Fonte;

  /*
  myQuery("DELETE FROM tb_comentario");
  myQuery("DELETE FROM tb_noticia_fullsearch");
  myQuery("DELETE FROM tb_noticia_mssql");
  myQuery("DELETE FROM tb_noticia_update");
  myQuery("DELETE FROM tb_noticia_visualizacoes");
  myQuery("DELETE FROM tb_noticia_hash");
  myQuery("DELETE FROM tb_noticia");

  myQuery("DELETE FROM tb_usuario");
  myQuery("DELETE FROM tb_fonte");
  myQuery("DELETE FROM tb_sessao");
  */

  myQuery('SET autocommit=0');
  myQuery('START TRANSACTION');

  $sql = "SELECT SQL_CACHE cd_noticia
          FROM tb_noticia_hash";

  if (strlen($noticia->pagMySQLHash)) {
    $sql .= " WHERE hs_noticia = '$noticia->pagMySQLHash'";
  } else {
    $sql .= " WHERE ds_url = '$noticia->pagURL'";
  }

  $myQuery = myQuery($sql);

  if ($myRow = mysql_fetch_object($myQuery)) {
    myQuery("DELETE FROM tb_comentario WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia_fullsearch WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia_mssql WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia_update WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia_visualizacoes WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia_hash WHERE cd_noticia = $myRow->cd_noticia");

    myQuery("DELETE FROM tb_noticia WHERE cd_noticia = $myRow->cd_noticia");
  }

  mysql_free_result($myQuery);

  $sql = "SELECT SQL_CACHE cd_noticia
          FROM tb_noticia_hash";

  if (strlen($noticia->pagMySQLHash)) {
    $sql .= " WHERE hs_noticia = '$noticia->pagMySQLHash'";
  } else {
    $sql .= " WHERE ds_url = '$noticia->pagURL'";
  }

  $myQuery = myQuery($sql);

  if ($myRow = mysql_fetch_object($myQuery)) {
    mysql_free_result($myQuery);

    $cd_noticia = $myRow->cd_noticia;

    echo ';0';

  } else {
    mysql_free_result($myQuery);

    echo ';1';

    // SELECT SQL_CACHE codigo, nome FROM tabela WHERE codigo > 0;

    // Localiza a fonte
    $sql = "SELECT SQL_CACHE cd_fonte
            FROM tb_fonte
            WHERE sg_fonte = '$noticia->fonSigla'";

    $myQuery = myQuery($sql);

    if ($myRow = mysql_fetch_object($myQuery)) {
      $cd_fonte = $myRow->cd_fonte;

      mysql_free_result($myQuery);

    } else {
      mysql_free_result($myQuery);

      // Inclui a notícia
      $sql = "INSERT INTO tb_fonte
      (nm_fonte, sg_fonte) VALUES
      ('$noticia->fonNome', '$noticia->fonSigla')";

      myQuery($sql);

      $cd_fonte = mysql_insert_id();
    }

    // Inclui a notícia
    $sql = "INSERT INTO tb_noticia
    (nm_noticia, ds_noticia, dt_referencia, cd_fonte, fl_ativo, qt_visualizacao, dt_visualizacao, dt_inclusao_year, dt_inclusao_month, dt_inclusao_day, dt_referencia_year, dt_referencia_month, dt_referencia_day) VALUES
    ('$noticia->pagNome', '$noticia->pagDescricao', '$noticia->pagInclusao', $cd_fonte, $noticia->pagAtivo, $noticia->pagVisualizacaoVezes, '$noticia->pagVisualizacao', YEAR(NOW()), MONTH(NOW()), DAY(NOW()), $noticia->pagInclusao_year, $noticia->pagInclusao_month, $noticia->pagInclusao_day)";

    myQuery($sql);

    $cd_noticia = mysql_insert_id();

    // Inclui a MSSQL
    $sql = "INSERT INTO tb_noticia_mssql
    (cd_noticia, cd_mssql) VALUES
    ($cd_noticia, $noticia->pagCodigo)";

    myQuery($sql);

    // Inclui a notícia
    $sql = "INSERT INTO tb_noticia_fullsearch
    (cd_noticia, ds_noticia) VALUES
    ($cd_noticia, '$noticia->pagNome\r\n$noticia->pagDescricao')";

    myQuery($sql);

    // Inclui os Hash - Fonte
    $sql = "INSERT INTO tb_noticia_hash
    (cd_noticia, hs_noticia, ds_url) VALUES
    ($cd_noticia, '$md5Fonte', '$noticia->pagURL')";

    myQuery($sql);

    // Inclui os Hash - Página
    $sql = "INSERT INTO tb_noticia_hash
    (cd_noticia, hs_noticia, ds_url) VALUES
    ($cd_noticia, '$md5Pagina', '$noticia->pagArquivoPasta/$noticia->pagArquivoNome')";

    myQuery($sql);

  }

  // Inclui o Update
  $sql = "INSERT INTO tb_noticia_update
  (cd_noticia, cd_sessao) VALUES
  ($cd_noticia, ".$_SESSION['cd_sessao'].")";

  myQuery($sql);

  if (count($comentarios)) {
    myQuery("DELETE FROM tb_comentario WHERE cd_noticia = $cd_noticia");

    $i = 0;
    $u = 0;

    foreach ($comentarios as $key => $value) {
      $i++;

      $rec = json2Array($value);

      $rec->comEMail = strtolower($rec->comEMail);

      $md5Usuario = md5($rec->comEMail);

      // Localiza o usuário
      $sql = "SELECT SQL_CACHE cd_usuario
              FROM tb_usuario
              WHERE hs_usuario = '$md5Usuario'";

      $myQuery = myQuery($sql);

      if ($myRow = mysql_fetch_object($myQuery)) {
        $cd_usuario = $myRow->cd_usuario;

        mysql_free_result($myQuery);

      } else {
        $u++;

        mysql_free_result($myQuery);

        // Inclui a usuário
        $sql = "INSERT INTO tb_usuario
        (nm_usuario, ds_email, hs_usuario, ds_senha) VALUES
        ('$rec->comNome', '$rec->comEMail', '$md5Usuario', '".substr($md5Usuario, 1, 10)."')";

        $myQuery = myQuery($sql);

        $cd_usuario = mysql_insert_id();
      }

      $rec->comComentario = preg_replace('%(\r\n|\r|\n)%', '<br />', $rec->comComentario);
      $rec->comComentario = preg_replace('%<br />%', '\\r\\n', $rec->comComentario);

      $sql = "INSERT INTO tb_comentario
      (cd_noticia, cd_sessao, cd_usuario, ds_url, ds_cidade, ds_estado, ds_atividade, ds_comentario, dt_validado, dt_inclusao, ds_ip4, fl_ativo, fl_notificar) VALUES
      ($cd_noticia, ".$_SESSION['cd_sessao'].", $cd_usuario, '$rec->comURL', '$rec->comCidade', '$rec->comEstado', '$rec->comAtividade', '$rec->comComentario', '$rec->comValidado', '$rec->comInclusao', '$rec->comIP', $rec->comAtivo, $rec->comNotificar)";

      $myQuery = myQuery($sql);

    }

    echo ";u($u);c($i)";
  }

  if (count($visualicoes)) {
    $i = 0;

    myQuery("DELETE FROM tb_noticia_visualizacoes WHERE cd_noticia = $cd_noticia");

    foreach ($visualicoes as $key => $value) {
      $i++;

      $rec = json2Array($value);

      $sql = "INSERT INTO tb_noticia_visualizacoes
      (cd_noticia, nm_ano, nm_mes, nm_dia, nm_hora, nm_quarter, nm_day_of_year, nm_week, nm_week_day, nm_quantidade, dt_inclusao) VALUES
      ($cd_noticia, $rec->puvAno, $rec->puvMes, $rec->puvDia, $rec->puvHora, $rec->puvQuarter, $rec->puvDayOfYear, $rec->puvWeek, $rec->puvWeekDay, $rec->puvQuantidade, '$rec->puvInclusao')";

      $myQuery = myQuery($sql);

    }

    echo ";v($i)";
  }

  myQuery('COMMIT');
  myQuery('SET autocommit=1');
}
