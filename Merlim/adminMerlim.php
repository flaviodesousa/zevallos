<?php
// ================================================================
// /adminMerlim.php
// ----------------------------------------------------------------
// Nome     : Administra o Merlim
// Criacao  : 23/08/2010 14:32:0 PM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 2.0.10
// Local    : Fortaleza - CE
// Copyright: 2007-2010 by Ruben Zevallos Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");
require_once("LibraryForm.inc.php");

$sstrCurrentScript = "adminMerlim.php";

ConnectMSSQL();

main();

DisconnectMSSQL();

// ================================================================
//
// ----------------------------------------------------------------
function main() {
	switch(parOption) {
		case 100:
			formSite();
			break;

		case 200:
			formFonte();
			break;
			
		case 300:
			formPagina();
			break;

		case 23:
			ConnectOracle();
			formOracle();
			DisconnectOracle();
			break;
			
	}
}

// ================================================================
//
// ----------------------------------------------------------------
function formSite() {
	global $formTitle;

	// Cria o dicionário e a ordem dos campos
	formMSSQLDictionary($form, "merSite", 1, 3);
	
	// Criar função para ler o dicionário e formato de um template
					
	// echo "<pre>";
	// print_r($form);
	// echo "</pre>";
	// die;
	
	$form["S"]["sitCodigo"]["caption"]   = "&Código"; // Identificação do Label
  
  $form["S"]["sitSigla"]["caption"]   = "&Sigla";
  
  $form["S"]["sitNome"]["caption"]   = "&Nome"; 
  $form["F"]["sitNome"]["size"] = "50"; 
  
  $form["S"]["sitURL"]["caption"]   = "&URL"; 
  $form["F"]["sitURL"]["size"] = "50"; 
  
  $form["S"]["sitIdAlternativo"]["caption"]   = "Id &Alternativo"; 
  
  $form["S"]["sitResumo"]["caption"]   = "&Resumo"; 
  
  $form["S"]["sitInclusao"]["caption"]   = "&Inclusão"; 
  $form["S"]["sitInclusao"]["captionbr"] = 1; 
  $form["S"]["sitInclusao"]["brafter"]   = 0; 
  
  $form["S"]["sitAlteracao"]["caption"]   = "&Alteração"; 
  $form["S"]["sitAlteracao"]["captionbr"] = 1; 
  $form["S"]["sitAlteracao"]["brafter"]   = 0; 
  
  $form["S"]["sitAtivo"]["caption"]   = "Ati&vo"; 
  $form["S"]["sitAtivo"]["captionbr"] = 1; 
  $form["S"]["sitAtivo"]["brafter"]   = 0; 

	// echo "<br />parTarget=".parTarget;
  
  if (parTarget) {
  	$form["PK"]["sitCodigo"] = parTarget;

		formMSSQLFetch($form);  

		// echo "<pre>";
		// print_r($form);
		// echo "</pre>";
		// die;
		
  } else {
  	formRequest($form);
  	
  } 

	unset($form["O"][1]); // Não apresenta a coluna código
  
	$formTitle = "Site";
	
	$intType = 0;
	
	formBegin("355px", 20, "", $intType);
	
	// Modo - Visualizar
	// Read Only
	// Incluir, Editar, Excluir
	
	// Modo - Incluir
	// Salvar, Cancelar
	
	// Modo - Editar
	// Salvar, Cancelar
		
	?><fieldset><?
	
	echo formCreate($form);
		
	?></fieldset><?
	
	formEnd($intType);
}

// ================================================================
//
// ----------------------------------------------------------------
function formOracle() {
	global $formTitle;

	// Cria o dicionário e a ordem dos campos
	formOracleDictionary($form, "TB_RZJ", 1, 3);
					
	// echo "<pre>";
	// print_r($form);
	// echo "</pre>";
	// die;
	  
  $form["S"]["NM_NOME"]["caption"]   = "&Nome"; 
  $form["F"]["NM_NOME"]["size"] = "50"; 
  
  $form["S"]["VL_SALARIO"]["caption"]   = "&Salário"; 
  $form["F"]["VL_SALARIO"]["size"] = "10"; 
    
  $form["S"]["DT_INCLUSAO"]["caption"]   = "&Inclusão"; 
  $form["S"]["DT_INCLUSAO"]["captionbr"] = 1; 
  $form["S"]["DT_INCLUSAO"]["brafter"]   = 0; 
    
  $form["S"]["TP_ATIVO"]["caption"]   = "Ati&vo"; 
  $form["S"]["TP_ATIVO"]["captionbr"] = 1; 
  $form["S"]["TP_ATIVO"]["brafter"]   = 0; 

	// echo "<br />parTarget=".parTarget;
  
  if (parTarget) {
  	$form["PK"]["NU_CODIGO"] = parTarget;

		formOracleFetch($form);  

		// echo "<pre>";
		// print_r($form);
		// echo "</pre>";
		// die;
		
  } else {
  	formRequest($form);
  	
  } 

	unset($form["O"][1]); // Não apresenta a coluna código
  
	$formTitle = "Table RZJ";
	
	$intType = 0;
	
	formBegin("355px", 20, "", $intType);
			
	?><fieldset><?
	
	echo formCreate($form);
		
	?></fieldset><?
	
	formEnd($intType);
}
?>