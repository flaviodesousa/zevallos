<?php
// ================================================================
// /ProcessPagina.php
// ----------------------------------------------------------------
// Nome     : Processa e indexa todas as páginas
// Home     : direito2.com.br
// Criacao  : 4/11/2008 10:21:43 PM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE);

session_start();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

ob_end_flush();

set_time_limit(3000000);

if (strtolower(PHP_OS) == "linux") {
  define('ZLIBRARY_DIR', "/var/www/html/new/Library");
  require_once(ZLIBRARY_DIR.'/domit/xml_domit_include.php');

  // define('ADODB_DIR', "/var/www/html/new/Library/adodb/", true);

  // $sstrSiteRootDir = dirname($_SERVER["PATH_TRANSLATED"]).DIRECTORY_SEPARATOR;
  //
  // $sstrScriptName  = basename($_SERVER["PATH_TRANSLATED"]);

} else {
  define('ZLIBRARY_DIR', "C:\Web\ZLibrary");
  // require_once('C:\Web\ZLibrary\domit\xml_domit_include.php');

  // define('ADODB_DIR', "C:\Program Files\PHP\adodb\\", true);

  // $sstrSiteRootDir = dirname(__FILE__).DIRECTORY_SEPARATOR;
  //
  // $sstrScriptName  = basename(__FILE__);

}

// include_once(ADODB_DIR."adodb.inc.php");

if (get_magic_quotes_gpc()) {
  $_REQUEST = array_map('stripslashes', $_REQUEST);
  $_GET = array_map('stripslashes', $_GET);
  $_POST = array_map('stripslashes', $_POST);
  $_COOKIE = array_map('stripslashes', $_COOKIE);
}

$sstrSiteRootDir = dirname(__FILE__).DIRECTORY_SEPARATOR;
$sstrScriptName  = basename($_SERVER["PHP_SELF"]);

$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

$stxtFind = trim($_REQUEST["txtfind"]);

$sparOption = intval($_REQUEST["o"]);
if (empty($sparOption)) $sparOption = intval($_REQUEST["O"]);

$sparAction = intval($_REQUEST["a"]);
if (empty($sparAction)) $sparAction = intval($_REQUEST["A"]);

$sparTarget = $_REQUEST["t"];
if (empty($sparTarget)) $sparTarget = intval($_REQUEST["T"]);

$sparType   = intval($_REQUEST["y"]);
if (empty($sparType)) $sparType = intval($_REQUEST["Y"]);

$sparDebug   = intval($_REQUEST["d"]) == 1;
if (empty($sparDebug)) $sparDebug = intval($_REQUEST["D"] == 1);

$sparTemplate = $_REQUEST["temp"];
if (empty($sparTemplate)) $sparTemplate = $_REQUEST["Temp"];

$sparPrint  = intval($_REQUEST["intprint"]);
$sparComplement = $_REQUEST["C"];

$sstrDomain      = strtolower($_SERVER["SERVER_NAME"]);

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$ADODB_FORCE_TYPE = ADODB_FORCE_NULL;

switch (strtolower($_SERVER["SERVER_NAME"])) {
  case "www.direito2.com.br":
  case "buscar.direito2.com.br":
  case "index.direito2.com.br":
  case "www.direito2.com.br":
    define("ConnectionStringUser", "root");
    define("ConnectionStringPassword", "SuperWeZ%");

    define("ConnectionStringHost", "localhost");
    define("ConnectionStringDatabase", "direito2_com_br");

  default:
    define("MSSQLUser", "sa");
    define("MSSQLPassword", "SuperRuben99");

    define("MSSQLHost", "(local)");
    define("MSSQLDatabase", "direito2_com_br");

    define("MySQLUser", "root");
    define("MySQLPassword", "SuperRuben99");

    define("MySQLHost", "localhost");
    define("MySQLDatabase", "direito2_com_br");

}

// print "\r\nMySQL=".MySQLHost." - ".MySQLUser." - ".MySQLPassword;
// print "\r\nMSSQL=".MSSQLHost." - ".MSSQLUser." - ".MSSQLPassword;

$sobjMSSQLConnMYSQL = @mysql_connect(MySQLHost, MySQLUser, MySQLPassword) or die("O MySQL parece estar desativado.");

if (MySQLDatabase!="" and !@mysql_select_db(MySQLDatabase)) die("O MySQL está indisponível.");

$sobjMSSQLConnMSSQL = sqlsrv_connect(MSSQLHost, array("UID"=>MSSQLUser, "PWD"=>MSSQLPassword, "Database"=>MSSQLDatabase)) or die("Unable to connect".FormatSQLErrors(sqlsrv_errors()));

Main();

sqlsrv_close($sobjMSSQLConnMSSQL);

$sobjMSSQLConnMSSQL = null;

// ================================================================
//
// ----------------------------------------------------------------

function Main() {
  global $sobjMSSQLConnMSSQL;

  MainBody();


}
// ----------------------------------------------------------------
// Final da function Main
// ================================================================

//=========================================================================
// Converte a data para o formato correto
//-------------------------------------------------------------------------
function FormatSQLErrors( $errors ) {
    /* Display errors. */
    echo "Error information: <br/>";
    foreach ( $errors as $error )
    {
          echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";
          echo "Code: ".$error['code']."<br/>";
          echo "Message: ".$error['message']."<br/>";
    }
}


//=========================================================================
// Converte a data para o formato correto
//-------------------------------------------------------------------------
function ConvertDateSQL($strDate) {
  $ConvertDateSQL = null;

  // print "<br />strDate=".$strDate;
  // print "<br />mktime=".substr($strDate, 11, 2).
  //                      ".".substr($strDate, 14, 2).
  //                      ".".substr($strDate, 17, 2).
  //                      ".".substr($strDate, 3, 2).
  //                      ".".substr($strDate, 0, 2).
  //                      ".".substr($strDate, 6, 4);
  //
  // print "<br />mktime=".intval(substr($strDate, 11, 2)).
  //                      ".".intval(substr($strDate, 14, 2)).
  //                      ".".intval(substr($strDate, 17, 2)).
  //                      ".".intval(substr($strDate, 3, 2)).
  //                      ".".intval(substr($strDate, 0, 2)).
  //                      ".".intval(substr($strDate, 6, 4));

  if (isset($strDate) && trim($strDate) > "") {
    $ConvertDateSQL = mktime(intval(substr($strDate, 11, 2))
                       , intval(substr($strDate, 14, 2))
                       , intval(substr($strDate, 17, 2))
                       , intval(substr($strDate, 3, 2))
                       , intval(substr($strDate, 0, 2))
                       , intval(substr($strDate, 6, 4)));

  }

  // print "<br />ConvertDateSQL=".date("d/m/Y", $ConvertDateSQL);
  // print "<br />";

  return $ConvertDateSQL;
}
// -------------------------------------------------------------------------
// Fim do ConvertDateSQL
// =========================================================================

// =========================================================================
//
// -------------------------------------------------------------------------
function DateDiff($interval, $dateTimeBegin, $dateTimeEnd) {
  //Parse about any English textual datetime
  //$dateTimeBegin, $dateTimeEnd

  $dif = $dateTimeEnd - $dateTimeBegin;

  switch($interval) {
    case "s"://seconds
      return($dif);

    case "n"://minutes
        return(floor($dif/60)); //60s=1m

    case "h"://hours
      return(floor($dif/3600)); //3600s=1h

    case "d"://days
      return(floor($dif/86400)); //86400s=1d

    case "ww"://Week
      return(floor($dif/604800)); //604800s=1week=1semana

    case "m": //similar result "m" dateDiff Microsoft
      $monthBegin=(date("Y",$dateTimeBegin)*12)+
        date("n",$dateTimeBegin);
      $monthEnd=(date("Y",$dateTimeEnd)*12)+
        date("n",$dateTimeEnd);
      $monthDiff=$monthEnd-$monthBegin;
      return($monthDiff);

    case "yyyy": //similar result "yyyy" dateDiff Microsoft
      return(date("Y",$dateTimeEnd) - date("Y",$dateTimeBegin));

    default:
      return(floor($dif/86400)); //86400s=1d
  }
}

// -------------------------------------------------------------------------
// Final da function DateDiff
// =========================================================================

// -------------------------------------------------------------------------
// Retorna somente as letras de A-Z a-z - e 0-9
// =========================================================================
function NormalizeString($strString) {

  if ($strString > "") {
    for ($i = 1; $i <= strlen($strString); $i++) {
      $strOne = substr($strString,$i-1,1);

      if (($strOne >= "a" && $strOne <= "z") || ($strOne >= "A" && $strOne <= "Z") || ($strOne >= "0" && $strOne <= "9") || $strOne == "-" || $strOne == " ") {
        $NormalizeString = $NormalizeString.$strOne;
      }
    }
  }

  return $NormalizeString;
}
// =========================================================================
// Final da NormalizeString
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// Formata o número no formato Brasileiro
// =========================================================================
function FormatNumberBR($dblValue, $intCasas) {

  $FormatNumberBR = str_replace(";", ".", str_replace(".", ",", str_replace(",", ";", number_format($dblValue, $intCasas))));

  return $FormatNumberBR;
}
// =========================================================================
// Final da FormatNumberBR
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// Formata e reduz o número dentro do padrão KB, MB e GB
// =========================================================================
function FormatSize($dblValue) {

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="KB";
  }

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="MB";
  }

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="GB";
  }

  $FormatSize = str_replace(";", ".", str_replace(".", ",", str_replace(",", ";", number_format($dblValue, 0)))).$strSulfix;

  return $FormatSize;
}
// =========================================================================
// Final da FormatSize
// -------------------------------------------------------------------------

// ----------------------------------------------------------------
//
// ================================================================

function NormalizeAccent($strString) {

  $arrNormal = explode(",","a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,o,c,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,I,Ç,a");
  $arrAccent = explode(",","á,é,í,ó,ú,â,ê,î,ô,û,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ã,õ,ç,Á,É,Í,Ó,Ú,Â,Ê,Î,Ô,Û,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Ã,Õ,Ç,ª");

  if ($strString > "") {
    $x=0;

    $NormalizeAccent = $strString;

    $NormalizeAccent = str_replace(chr(170),"a",$NormalizeAccent);

    foreach ($arrAccent as $i) {
      $NormalizeAccent = str_replace($arrAccent[$x],$arrNormal[$x],$NormalizeAccent);

      $x = $x+1;

    }
  }

  return $NormalizeAccent;
}
// ================================================================
// NormalizeAccent
// ----------------------------------------------------------------

// ----------------------------------------------------------------
//
// ================================================================

function DecodeUTF8($s) {

  $i=1;

  while($i <= strlen($s)) {
    $c = ord(substr($s,$i-1,1));
    if ($c && 0x80) {

      $n=1;
      while($i+$n<strlen($s)) {

        if ((ord(substr($s, $i + $n-1, 1)) && 0xC0)!= 0x80) {
          break;

        }

        $n = $n+1;
      }
      if ($n==2 && (($c && 0xE0)==0xC0)) {

        $c = ord(substr($s,$i+1-1,1))+0x40*($c & 0x01);
      }
        else
      {

        $c=191;
      }

      $s = substr($s,0,$i-1)+chr($c)+substr($s,$i+$n-1);
    }

    $i = $i+1;
  }

  return $s;
}
// ================================================================
//
// ----------------------------------------------------------------

function MainBody() {
  global $sobjMSSQLConnMSSQL, $sstrSiteRootDir, $sstrScriptName;

  $datInicioAll = time();

  print "<b>Início</b> - ".date("m/d/y H:i:s", $datInicioAll);
  print "<br /><pre>";

  session_unset();
  session_destroy();

  print "<br />Memória=".number_format(memory_get_usage());

  print "<br /><br />Atualiza contador do pubPalavra";
// Para o MySQL
// objConn.Execute "UPDATE pubPalavra" & vbCrLf & _
//                 ", (" & vbCrLf & _
//                 "  SELECT papPalavra, Count(*) AS papQuantidade" & vbCrLf & _
//                 "  FROM pubPalavraPagina" & vbCrLf & _
//                 "  GROUP BY papPalavra" & vbCrLf & _
//                 ") AS PP" & vbCrLf & _
//                 "SET palPagina = 1" & vbCrLf & _
//                 ", palPaginas = PP.papQuantidade" & vbCrLf & _
//                 "WHERE palCodigo = PP.papPalavra"

// Para o MSSQL


  // $sql = "UPDATE pubPalavra"."\r\n".
  //        " SET palPagina = 1"."\r\n".
  //        " , palPaginas = PP.papQuantidade"."\r\n".
  //        " FROM ("."\r\n".
  //        "   SELECT papPalavra, Count(*) AS papQuantidade"."\r\n".
  //        "   FROM pubPalavraPagina"."\r\n".
  //        "   GROUP BY papPalavra"."\r\n".
  //        " ) AS PP"."\r\n".
  //        " WHERE palCodigo = PP.papPalavra";
  //
  // if (!sqlsrv_query($sql)) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s");

  print "<br /><br />Atualiza contador do pubPaginas";

  // Para o MySQL
  // sobjMSSQLConn.Execute "UPDATE pubPaginas" & vbCrLf & _
  //                  " , (" & vbCrLf & _
  //                  "  SELECT papPagina" & vbCrLf & _
  //                  "  FROM pubPalavraPagina" & vbCrLf & _
  //                  "  GROUP BY papPagina" & vbCrLf & _
  //                  " ) AS PP" & vbCrLf & _
  //                  " SET pagPalavra = 1" & vbCrLf & _
  //                  " WHERE pagCodigo = PP.papPagina"

  // Para o MSSQL

  // $sql = "UPDATE pubPaginas"."\r\n".
  //        " SET pagPalavra = 1"."\r\n".
  //        " FROM ("."\r\n".
  //        "   SELECT papPagina"."\r\n".
  //        "   FROM pubPalavraPagina"."\r\n".
  //        "   GROUP BY papPagina"."\r\n".
  //        " ) AS PP"."\r\n".
  //        " WHERE pagCodigo = PP.papPagina";
  //
  // if (!sqlsrv_query($sql)) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s");

  print "<br />Preparando No Words";

  $_SESSION['SE.live'] = 1;
  $_SESSION['SE.altavista'] = 2;
  $_SESSION['SE.google'] = 0;
  $_SESSION['SE.yahoo'] = 3;

  print "<br />Apagando o Cache anterior";

  foreach ($_SESSION as $i) {
    // print "\r\ni=$i=".$_SESSION[$i];

    if ((strpos("N.W.P",substr($i, 0, 1)) ? strpos("N.W.P", substr($i,0,1))+1 : 0)>0) unset($_SESSION[$i]);

  }

  foreach (explode(" ","do da de dos das para que pro por um uma uns umas e a ao à é no na nos nas as às sobre em outras aos p/ s/ como tem tá foi bem sim não com quem $i $ii $iii") as $i) {
    $_SESSION["N".$i] = true;

  }

  print "<br />";

  $sql = "SELECT pagCodigo, pagNome, pagTitulo, pagResumo, pagDescricao, pagPalavrasChave, pagPalavra, pagInclusao"."\r\n".
         " FROM pubPaginas"."\r\n".
         " WHERE pagAtivo = 1 AND pagPalavra IS NULL AND pagReferencia IN (4, 5, 8, 9)"."\r\n".
         " ORDER BY pagCodigo";

  if( !($objResult = sqlsrv_query($sobjMSSQLConnMSSQL, $sql))) die("Erro na execução da query\n".FormatSQLErrors(sqlsrv_errors()));

  $intPaginas = 0;

  $datInicioPage = time();

  while ($objRSPagina = sqlsrv_fetch_array($objResult, SQLSRV_FETCH_ASSOC)) {
    $intPaginas++;
    $intWords=0;

    $datInicio = time();

    $pagCodigo = $objRSPagina["pagCodigo"];
    $pagInclusao = $objRSPagina["pagInclusao"];

    $pasInclusao = $objRSPagina["pagInclusao"];

    $sql = "UPDATE pubPaginas".
           " SET pagPalavra = 1".
           " WHERE pagCodigo = $pagCodigo";

    if(!($objSQL = sqlsrv_prepare($sobjMSSQLConnMSSQL, $sql))) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s".FormatSQLErrors(sqlsrv_errors()));

    echo "\nRuben 1<br />";

    if(!(sqlsrv_execute($objSQL))) die("\r\nErro na execução\r\n".$sql."\r\n".(time() - $datInicioAll)."s".FormatSQLErrors(sqlsrv_errors()));

    echo "\nRuben 2<br />";

    sqlsrv_free_stmt($objSQL);

    $pasAno = strftime("%Y", $pasInclusao);
    $pasMes = strftime("%m", $pasInclusao);
    $pasDia = strftime("%d", $pasInclusao);

    $pasSemana = strftime("%W", $pasInclusao);

    if (($intPaginas == 1) || (($intPaginas % 100) == 0)) {
      print "\r\n".$intPaginas."[".(time() - $datInicioPage)."s"."], ".$objRSPagina["pagCodigo"]." [".FormatSize(memory_get_usage())."] | ".$objRSPagina["pagInclusao"]." - ".$objRSPagina["pagNome"]."(".FormatSize(strlen($objRSPagina["pagNome"].$objRSPagina["pagResumo"].$objRSPagina["pagDescricao"])).")";

      $datInicioPage = time();

    }

    if (($intPaginas == 1) || (($intPaginas % 1000) == 0)) print "\r\n";

    $pagConteudo = $objRSPagina["pagNome"]."\r\n";
    $pagConteudo = $pagConteudo.$objRSPagina["pagTitulo"]."\r\n";
    $pagConteudo = $pagConteudo.$objRSPagina["pagResumo"]."\r\n";
    $pagConteudo = $pagConteudo.$objRSPagina["pagDescricao"]."\r\n";
    $pagConteudo = $pagConteudo.$objRSPagina["pagPalavrasChave"]."\r\n";

    if ($pagConteudo>"") {
      $pagConteudo = str_replace("\r\n", " ", $pagConteudo);

      // Tira tudo que é Tag
      $pagConteudo = preg_replace("/<[^>]+>/i", " ", $pagConteudo);

      // Limpa caracteres estranhos
      $pagConteudo = preg_replace("/[?.!:=\"'\/\\,;*)({}`´[\\]*$#@^%<>&|\\^]+/i", " ", $pagConteudo);

      // Tira espaços desnecessários
      $pagConteudo = preg_replace("/[ ]+/i", " ", $pagConteudo);

      if (preg_match("/[ ]*?([^ ]+)[ ]*?/i", $pagConteudo)) {
        preg_match_all("/[ ]*?([^ ]+)[ ]*?/i", $pagConteudo, $Matches, PREG_SET_ORDER);

        foreach ($Matches as $Match) {
          $intWordTotal++;
          $strPalavra = "";

          $strPalavraUTF8 = rtrim(ltrim($Match[0]));

          $strPalavra = $strPalavraUTF8;

          $intPalavra++;

          $strAuxA = NormalizeString(NormalizeAccent($strPalavra));

          // print "\r\n\r\n$intPalavra - Match[0]=$Match[0]";
          // print "<br />strPalavra=$strPalavra";
          // print "<br />strAuxA=$strAuxA";

          if (strlen($strAuxA) > 2 && strlen($strAuxA) < 51 && preg_match("/[A-Za-z0-9]+/i", $strAuxA)) {
            if (!$_SESSION["N".$strPalavra]) {
              $sql = "SELECT palCodigo FROM pubPalavra WHERE palNomeNormalized = '$strPalavra';";

              if( !($objResult = sqlsrv_query($sobjMSSQLConnMSSQL, $sql))) die("Erro na execução da query\n".$sql."\r\n".(time() - $datInicioAll)."s ".FormatSQLErrors(sqlsrv_errors()));

              $palCodigo = 0;

              if ($objRS = sqlsrv_fetch_array($objResult, SQLSRV_FETCH_ASSOC)) $palCodigo = $objRS["palCodigo"];

              if ($palCodigo) {
                // If Not Session("P" & palCodigo & "." & pagCodigo) > "" Then
                // sobjMSSQLConn.Execute "UPDATE pubPalavra SET palPaginas = palPaginas + 1" & _
                //                  " , palAlteracao = GetDate()" & _
                //                  " WHERE palCodigo = " & Session("W" & strAuxA) {
              } else {
                $intWords++;

                if (($intPaginas == 1) || (($intPaginas % 1000) == 0)) print " ".$strPalavra."+";

                $sql = "INSERT pubPalavra"."\r\n".
                       " (palNome, palNomeCorreto, palNomeNormalized,palPagina,palInclusao"."\r\n".
                       ") VALUES ('".substr($strPalavra, 0, 50)."', '".substr($strPalavra, 0, 50)."', '".substr($strAuxA, 0, 50)."', 1, '".$pagInclusao."')";


                if (!sqlsrv_query($sobjMSSQLConnMSSQL, $sql)) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s");

                $sql = "SELECT TOP 1 palCodigo FROM pubPalavra ORDER BY palCodigo DESC";

                if( !($objIResult = sqlsrv_query($sobjMSSQLConnMSSQL, $sql))) die("Erro na execução da query\n".FormatSQLErrors(sqlsrv_errors()));

                if (!($objRS = sqlsrv_fetch_array($objIResult, SQLSRV_FETCH_ASSOC))) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s");

                $palCodigo = sqlsrv_result($objRS, 0, 'palCodigo');

              }

              $sql = "SELECT papPalavra FROM pubPalavraPagina WHERE papPalavra = $palCodigo AND papPagina = $pagCodigo;";

              if( !($objIResult = sqlsrv_query($sobjMSSQLConnMSSQL, $sql))) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s ".FormatSQLErrors(sqlsrv_errors()));

              $papPalavra = 0;

              if (!($objRS = sqlsrv_fetch_array($objIResult, SQLSRV_FETCH_ASSOC))) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s");

              $papPalavra = $objRS["papPalavra"];

              if (!$papPalavra) {
                $sql = "INSERT pubPalavraPagina"."\r\n".
                       " (papPalavra, papPagina, papInclusao"."\r\n".
                       ") VALUES (".$palCodigo.", ".$pagCodigo.", '".$pagInclusao."')";

                // print "\r\n$sql";

                if(!($objSQL = sqlsrv_prepare($sobjMSSQLConnMSSQL, $sql))) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s".FormatSQLErrors(sqlsrv_errors()));

                if(!sqlsrv_execute($objSQL)) die("\r\nErro na query\r\n".$sql."\r\n".(time() - $datInicioAll)."s".FormatSQLErrors(sqlsrv_errors()));

                sqlsrv_free_stmt($objSQL);

              }
            }
          }
        }
      }
    }

    $pagConteudo = null;

    if (($intPaginas == 1) || (($intPaginas % 100) == 0)) print "(".number_format($intPalavra).";".$intWords.") ".(time() - $datInicio)."s";
  }

  print "</pre>";

  print "<br />Tempo Total: ".number_format(time() - $datInicioAll)."s"."\r\n";

  $objRS = null;

  $objRSPagina = null;
}

?>

