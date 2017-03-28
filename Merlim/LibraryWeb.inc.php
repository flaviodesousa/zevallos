<?php
// ================================================================
// /LibraryWeb.inc.php
// ----------------------------------------------------------------
// Nome     : Biblioteca genérica para Web
// Home     : ruben.zevallos.com.br
// Criacao  : 10/13/2008 2:21:26 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF
// Copyright: 2007-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

$sstrLocalTemplateFile = "Default.htm";
$sstrLocalTemplate = "";
$stimeRefresh = 20; // define tempo de refresh das telas de monitoramento
$formTitle = "";

$screenTimeout = $_REQUEST["screenTimeout"];
$screenTimeout = (($screenTimeout) ? $screenTimeout : 20);
define('screenTimeout', $screenTimeout);

// ================================================================
// Prepara e lê template para a página corrente
// ----------------------------------------------------------------
function BeginTemplate($strTemplate = "", $strTitle = ""){
  global $sstrSiteRootDir;
  global $sstrLocalTemplateFile, $sstrLocalTemplate;

  if (!$strTemplate) $strTemplate = $sstrLocalTemplateFile;

  $sstrLocalTemplate = ZReadFile($sstrSiteRootDir."Templates/".$strTemplate);

  if (strlen($strTitle)) $sstrLocalTemplate = preg_replace("%<title>.*?</title>%i", "<title>".$strTitle."</title>", $sstrLocalTemplate);

  preg_match_all('%<body[^>]*>%si', $sstrLocalTemplate, $Matches, PREG_OFFSET_CAPTURE);

  echo substr($sstrLocalTemplate, 0, $Matches[0][0][1] + strlen($Matches[0][0][0]));
}


// ================================================================
// Finaliza a carga do template da página corrente
// ----------------------------------------------------------------
function EndTemplate() {
  global $sstrLocalTemplate;

  preg_match_all('%</body>%si', $sstrLocalTemplate, $Matches, PREG_OFFSET_CAPTURE);

  echo substr($sstrLocalTemplate, $Matches[0][0][1]);
}


// =================================================================
// Valida e cria uma linha na linha da Sessão
// ----------------------------------------------------------------
function BeginSession( $CheckLogon = 1 ) {
  global $sobjMSSQLConn;

  global $sstrCurrentScript, $sstrScriptName;

  global $sstrRemoteAddress, $sstrHTTPReferer;

  if (!$_SESSION["sesCodigo"]) {
    $sql = "INSERT ".preProgram."Sessao".
         " (sesPrograma, sesIP, sesReferer)".
         " VALUES (".$_SESSION["pgmCodigo"].", '$sstrRemoteAddress', '".$sstrHTTPReferer."')".
         " SELECT @@IDENTITY AS sesCodigo";

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>BeginSession</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
      $_SESSION["sesCodigo"] = $objRS["sesCodigo"];

    }
  }

  if ($CheckLogon) {
      $_SESSION["Request"] = $sstrScriptName."?".encode($_GET);

      if (!$_SESSION["Logged"] ) header("Location: /logOn.php");
  }
}


// ================================================================
// Atualiza a sessão com os dados do Login
// ----------------------------------------------------------------
function EndSession() {
  global $sobjMSSQLConn;

  if ($_SESSION["sesCodigo"] && $_SESSION["inuCodigo"]) {
    $sql = "UPDATE ".preProgram."Sessao".
           " SET sesFim = '".date("Y/m/d H:i:s")."'".
           " WHERE sesCodigo = ".$_SESSION["sesCodigo"];

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>EndSession: </b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  }
}


// ================================================================
// Atualiza a sessão com os dados do Login
// ----------------------------------------------------------------
function UpdateSession() {
  global $sobjMSSQLConn;

  if ($_SESSION["sesCodigo"] && $_SESSION["pelCodigo"]) {
    $sql = "UPDATE ".preProgram."Sessao".
           " SET sesLogin = ".$_SESSION["pelCodigo"].
           " WHERE sesCodigo = ".$_SESSION["sesCodigo"];

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>UpdateSession: </b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  }
}


// ================================================================
// Atualiza a sessão com os dados do Login
// ----------------------------------------------------------------
function DropSession() {
  global $sobjMSSQLConn;

  UpdateSession();

  $_SESSION["sesCodigo"] = null;
  $_SESSION["pelCodigo"] = null;

  session_destroy();

}


// ================================================================
// Check o Programa corrente e retorna o ID dele
// ----------------------------------------------------------------
function CheckProgram() {
  global $sobjMSSQLConn;
  global $sstrCurrentScript;
  global $sparDebug;

  if (!$_SESSION["pgmCodigo"]) {
    $sql = "SELECT pgmCodigo".
           " FROM ".preProgram."Programa".
           " WHERE pgmNome = '$sstrCurrentScript'";

    if ($sparDebug) echo "<br />sql=$sql";

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>CheckProgram</b> - QUERY: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
      $_SESSION["pgmCodigo"] = $objRS["pgmCodigo"];

    } else {
      $sql = "INSERT ".preProgram."Programa".
           " (pgmNome)".
           " VALUES ('$sstrCurrentScript')".
           " SELECT @@IDENTITY AS pgmCodigo";

      $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>CheckProgram</b> - QUERY: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
        $_SESSION["pgmCodigo"] = $objRS["pgmCodigo"];

      }
    }
  }
}


// ================================================================
// Adiciona para o log corrente aberto
// ----------------------------------------------------------------
function Add2Log($strMessage, $strAux, $logAtividade, $logTabela = "null", $logTabelaPK = "null") {
  global $sobjMSSQLConn;

  if ($_SESSION["sesCodigo"]) {
    $sql = "INSERT ".preProgram."Log".
         " (logSessao, logPrograma, logAtividade, logTabela, logTabelaPK, logDescricao)".
         " VALUES (".$_SESSION["sesCodigo"].", ".$_SESSION["pgmCodigo"].", $logAtividade, $logTabela, $logTabelaPK, '".$strMessage."\r\n".$strAux."')".
         " SELECT @@IDENTITY AS logCodigo";

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br /><b>Add2Log</b> - QUERY: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  }

  // 1  - Login
}

/* Monta Combo
* @function montaCombo() monta dinamicamente opções de caixas de seleção
* @param $strTable refere-se a tabela que será montada
* @param $strFieldValue refere-se ao campo que será o VALUE do <option>
* @param $strFieldSelec refere-se ao campo que apresetará o <option> $strFieldSelec </option>
* @param $$strCurrentName será preenchido se estive sendo usado em um formuário para editar dados.
  esse parametro monta dentro da combo com o atributo selectd="selected" no valor que se encontra na base. deve ser compativel com $strFieldSelec
* @param $$strCurrentValue será preenchido se estive sendo usado em um formuário para editar dados.
  esse paramentro tem o mesmo efeito do parametro anterior, com a diferença que será usado o código do value ao invés do nome encontrado.
  Se ambos paramentros forem usados, a função retornará false.
  Se os campos divergire com os existentes na tabela, a funcção retorna false;
* @Author Ériton Ribeiro Sampaio Frós <eritonf@gmail.com>
*
*/

function montaCombo($strTable, $strFieldValue, $strFieldSelect, $strCurrentName="", $strCurrentValue=""){
  global $sobjMSSQLConn;

  if($strCurrentName && $strCurrentValue){
    return false;

  }else if($strCurrentName){
    $strCurrent = $strCurrentName;

  }else if($strCurrentValue){
    $strCurrent = $strCurrentValue;

  }

  $sql = "SELECT ".$strFieldValue.
         ", ".$strFieldSelect.
         " FROM ".$strTable.
         " ORDER BY ".$strFieldSelect;

  $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br />Monta Combo ".$strTable.": A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if(!mssql_num_rows) return false;

  //verifica se algum valor vem a ser preenchido
  if(!$strCurrent) $option = "<option value=\"\" selected=\"selected\"> Selecione </option>";
  else $option = "<option value=\"\" > Selecione </option>";

  while($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)){
    if($strCurrent){
      if(($objRS["$strFieldSelect"] == $strCurrent) || ($objRS["$strFieldValue"] == $strCurrent)){
        $option .= "<option value=\"".$objRS["$strFieldValue"]."\" selected=\"selected\">".$objRS["$strFieldSelect"]."</option>";

      }else{
        $option .= "<option value=\"".$objRS["$strFieldValue"]."\">".$objRS["$strFieldSelect"]."</option>";

      }

    }else{
      $option .= "<option value=\"".$objRS["$strFieldValue"]."\">".$objRS["$strFieldSelect"]."</option>";

    }
  }

  echo $option;

}

/*
* @function verifica_cpf
*/
function verifica_cpf($cpf){

  $allowed = "#[^0-9]#";
  $cpf= preg_replace( $allowed, '', $cpf);

  if (strlen($cpf)<>11){
    return false;

  }else{
    $soma1=($cpf[0]*10)+($cpf[1]*9)+($cpf[2]*8)+($cpf[3]*7)+($cpf[4]*6)+($cpf[5]*5)+($cpf[6]*4)+($cpf[7]*3)+($cpf[8]*2);
    $resto=$soma1%11;
    $digito1 = ($resto < 2 ? 0 : 11 - $resto);
    $soma2=($cpf[0]*11)+($cpf[1]*10)+($cpf[2]*9)+($cpf[3]*8)+($cpf[4]*7)+($cpf[5]*6)+($cpf[6]*5)+($cpf[7]*4)+($cpf[8]*3)+($cpf[9]*2);
    $resto=$soma2%11;

    $digito2 = ($resto < 2 ? 0 : 11 - $resto);
    if (($digito1<>$cpf[9]) || ($digito2<>$cpf[10])){
      return false;

    }else{
      return true;

    }
  }

}

/*
*@function validaData()
*/
function validaData($data){
  if($data){
    if(!strpos($data, "/")){
      $data = substr($data, 3)."/".substr($data, 2, 2)."/".substr($data, 0, 2);

      return $data;

    }else{
      $data = explode("/", $data);
      if(array_key_exists(0, $data)){
        $data = $data[2]."/".$data[1]."/".$data[0];
        return $data;
      }else{
        $data = "NULL";
        return $data;

      }


    }
  }else{
    $data = "NULL";
    return $data;
  }

}
/*
* @function checkCNPJ()
*/
function checkCNPJ($cnpj){
  $cnpj = str_pad(ereg_replace('[^0-9]', '', $cnpj), 14, '0', STR_PAD_LEFT);
    if (strlen($cnpj) != 14) {
      return false;
    } else {
      for ($t = 12; $t < 14; $t++) {
        for ($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++) {
            $d += $cnpj{$c} * $p;
              $p   = ($p < 3) ? 9 : --$p;
        }

        $d = ((10 * $d) % 11) % 10;

          if ($cnpj{$c} != $d) {
            return false;
          }
      }

      return true;
    }
}
/**
 * Ante injection
 * @function antInjection() função que trata strings maliciosas
 * @param $strInjection recebe a string a ser tratada
 *
 * @Author Ériton Ribeiro Sampaio Frós
 *
 */
function antInjection($strInjection){
    $strInjection = str_replace("'", "''", $strInjection);

  return $strInjection;

}
/**
 * Combo fixa
 * @function comboValuesFixo() função que monta combo com valores fixos e marca selected para o valor que vier do banco.
 * Esta como monta o value com mesmo valor do option
 * @param $listOption recebe um array com a lista de opções que irá conter a combo. Values e Opção
 * @param $strValue recebe o valor que vem do banco
 *
 * Exemplo:$categoria = array("option" => array("A", "B", "C", "D", "E", "AB", "AC", "AD", "AE"));
 * comboValuesFixo($categoria);
 *
 * @Author Ériton Ribeiro Sampaio Frós
 *
 */

function comboValuesFixo($listOption, $strValue=""){
  $option = "<option value\"\">Selecione</option>";
  foreach($listOption AS $key => $opcoes){
    foreach($opcoes AS $value => $opcoes1){
      if($strValue){
        if($strValue == $opcoes1){
          $option .= "<option value=\"".$opcoes1."\" selected=\"selected\">".$opcoes1."</option>";

        }else{
          $option .= "<option value=\"".$opcoes1."\" >".$opcoes1."</option>";

        }

      }else{
        $option .= "<option value=\"".$opcoes1."\" >".$opcoes1."</option>";

      }

    }

  }
  echo $option;
}

?>