<?php
// ================================================================
// /menuAjax.php
// ----------------------------------------------------------------
// Nome     : TreeView usando AJAX - Asynchronous JavaScript and XML
// Home     : ruben.zevallos.com.br
// Criacao  : 26/08/2010 1:21:34 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.3
// Local    : Fortaleza - CE, Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");
require_once("LibraryMenu.inc.php");

$sstrCurrentScript = "menuAjax.php";

$sblnCrLf = false;
$sstrMenu = "";

ConnectMSSQL();

main();

DisconnectMSSQL();

if ($objSQL) mssql_free_result($objSQL);

// ================================================================
//
// ----------------------------------------------------------------
function Main() {
  global $sparOption;

  global $sblnCrLf, $sstrMenu;

  extract($GLOBALS);

  $arrTargets = explode("-", $sparTarget);

  switch ($arrTargets[0]) {
    case 100:
      menuSite();
      break;

    case 200:
      menuFonte($arrTargets[1]);
      break;
      
    case 300:
      menuPagina($arrTargets[1]);
      break;

    case 2000: // Tabelas do Form Generator
      formGeneratorTabelas();
      break;
      
    case 1000:
      generateInsertsTabelas();
      break;
      
    case 3000:
      addItem("Listagem geral", "/pessoaCadastro.php?o=1");
      addItem("Novo Funcionário", "/cadastraFuncionario.php?o=10");
      addItem("Editar Funcionário", "/editaFuncionario.php?o=10");
      //addItem("Cadastro de Vendedor", "/pessoaComercial.php?o=10");
      addItem("Relação de Vendedores", "/pessoaCadastro.php?o=100");
      //addItem("Cadastro de Login", "/Result/sipPessoaLogin.htm");
      break;
     
  }

  echo $sstrMenu;
}

// ================================================================
// Monta o menu de Sites
// ----------------------------------------------------------------
function menuSite() {

	$sql= "SELECT sitCodigo
	, sitSigla
	, sitNome
	, sitURL
	, sitIdAlternativo
	FROM merSite
	ORDER BY sitAtivo DESC, sitNome";
  
	$objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>".ScriptName." - processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
    
  while($objRSRow = mssql_fetch_array($objSQL, MSSQL_ASSOC) ) {
	    addItem($objRSRow["sitSigla"], "/Result/sipRotaArea.htm?t=".$objRSRow["sitCodigo"], "200-".$objRSRow["sitCodigo"], $objRSRow["sitNome"]);

    addSubItem(" (");
    addSubItem("I", "manageMerlin.php?o=200&t=".$objRSRow["sitCodigo"], "Inclui uma nova fonte no ".$objRSRow["sitSigla"]." - ".$objRSRow["sitNome"]);
    addSubItem(", ");
    addSubItem("A", "adminMerlim.php?o=100&t=".$objRSRow["sitCodigo"], "Entra no modo de alteração do Site ".$objRSRow["sitSigla"]." - ".$objRSRow["sitNome"]);
    addSubItem(", ");
    addSubItem("P", "processMerlim.php?t=".$objRSRow["sitIdAlternativo"], "Processa a fonte ".$objRSRow["sitSigla"]." - ".$objRSRow["sitNome"]);
    addSubItem(")");
  	
  	
  }

  mssql_free_result($objSQL);

}

// ================================================================
// Monta o menu de Fontes
// ----------------------------------------------------------------
function menuFonte($strWhere) {

	$sql= "SELECT sifCodigo
		, sifSigla
		, sifNome
		, sifURL
		, sifIdAlternativo
		, sifcURL
		, sifTidyHTML
		, sifRegEx
		FROM merSiteFonte
		WHERE sifAtivo = 1 ".(($strWhere) ? " AND sifSite = ".$strWhere : "")."
		ORDER BY sifAtivo DESC, sifNome";
  
	$objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>".ScriptName." - processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
    
  while($objRSRow = mssql_fetch_array($objSQL, MSSQL_ASSOC) ) {
	    addItem($objRSRow["sifSigla"]." - ".$objRSRow["sifNome"], "/Result/sipRotaArea.htm?t=".$objRSRow["sifCodigo"], "300-".$objRSRow["sifCodigo"]);

    addSubItem(" (");
    addSubItem("I", "manageMerlin.php?o=200&t=".$objRSRow["sifCodigo"], "Inclui uma nova fonte no ".$objRSRow["sifSigla"]." - ".$objRSRow["sifNome"]);
    addSubItem(", ");
    addSubItem("A", "manageMerlin.php?o=100&t=".$objRSRow["sifCodigo"], "Entra no modo de alteração do Site ".$objRSRow["sifSigla"]." - ".$objRSRow["sifNome"]);
    addSubItem(", ");
    addSubItem("P", "processMerlim.php?t=".$objRSRow["sifIdAlternativo"], "Processa a fonte ".$objRSRow["sifSigla"]." - ".$objRSRow["sifNome"]);
    addSubItem(")");
  	
  	
  }

  mssql_free_result($objSQL);

}

// ================================================================
// Monta o menu de Fontes
// ----------------------------------------------------------------
function menuPagina($strWhere) {

	$sql= "SELECT sfpCodigo
			, sfpTidyHTML
			, sfpcURL
			, sfpURL
			, sfpTituloRegEx
			, sfpTituloNormalizeString
			, sfpTituloSentence
			
			, sfpDataRegEx
			, sfpDataMesFromText
			, sfpDataAnoReplace
			
			, sfpHoraRegEx
			
			, sfpTextoRegEx
			, sfpTextoPre
			, sfpTextoPos
			
			, sfpResumoRegEx
			
			, sfpAutorRegEx
			
			, sfpFonteRegEx
			
			FROM merSiteFontePagina
			WHERE sfpAtivo = 1 ".(($strWhere) ? " AND sfpFonte = ".$strWhere : "")."
			ORDER BY sfpURL";
  
	$objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>".ScriptName." - processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
    
  while($objRSRow = mssql_fetch_array($objSQL, MSSQL_ASSOC) ) {
	    addItem($objRSRow["sfpCodigo"]." - ".$objRSRow["sfpURL"], "/Result/sipRotaArea.htm?t=".$objRSRow["sfpCodigo"], "300-".$objRSRow["sfpCodigo"]);

    addSubItem(" (");
    addSubItem("I", "manageMerlin.php?o=200&t=".$objRSRow["sfpCodigo"], "Inclui uma nova fonte no ".$objRSRow["sifSigla"]." - ".$objRSRow["sifNome"]);
    addSubItem(", ");
    addSubItem("A", "manageMerlin.php?o=100&t=".$objRSRow["sfpCodigo"], "Entra no modo de alteração do Site ".$objRSRow["sifSigla"]." - ".$objRSRow["sifNome"]);
    addSubItem(")");
  	
  	
  }

  mssql_free_result($objSQL);

}
// ================================================================
// Monta o menu com a lista de tabelas do Database corrente para o
// FormGenerator
// ----------------------------------------------------------------
function formGeneratorTabelas() {

  $sql = "SELECT name, object_id, create_date, modify_date".
         " FROM sys.all_objects".
         " WHERE type = 'U'".
         " ORDER BY name";

  $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br />formGeneratorTabelas: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  while ($objRS = mssql_fetch_array($objSQL, MSSQL_ASSOC)) {
    addItem($objRS[name], "/FormGenerator.php?o=10&t=".$objRS[name]);

  }

  mssql_free_result($objSQL);

}

// ================================================================
// Monta o menu com os Integrados ativos
// ----------------------------------------------------------------
function generateInsertsTabelas() {

  $sql = "SELECT name, object_id, create_date, modify_date".
         " FROM sys.all_objects".
         " WHERE type = 'U'".
         " ORDER BY name";

  $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br />generateInsertsTabelas: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  while ($objRS = mssql_fetch_array($objSQL, MSSQL_ASSOC)) {
    addItem($objRS[name], "/generateInserts.php?t=".$objRS[name]);

  }

  mssql_free_result($objSQL);

}

?>