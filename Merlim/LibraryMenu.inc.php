<?php
// ================================================================
// /LibraryMenu.inc.php
// ----------------------------------------------------------------
// Nome     : Biblioteca para Menus com AJAX
// Home     : ruben.zevallos.com.br
// Criacao  : 12/2/2008 1:41:54 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

// ================================================================
// Monta a linha do link que será enviado
// ----------------------------------------------------------------
function addItem($strCaption = "No Caption", $strURL = "", $intId = "", $strTitle = "", $strTarget = "") {
  global $sblnCrLf, $sstrMenu;

  if ($sblnCrLf) $sstrMenu .= "\r\n";

  $sblnCrLf = true;

  switch ($intId) {
    case "+":
      $blnNode = "+";
      $intId = "";
      break;

    case 0:
      $blnNode = 0;
      break;

    default:
      $blnNode = 1;

  }

  $sstrMenu .= "$blnNode|$intId|".accentString2Latin1($strCaption)."|".accentString2Latin1($strTitle)."|$strURL|$strTarget";
}

// ================================================================
// Monta a linha do sub-link que será enviado
// ----------------------------------------------------------------
function addSubItem($strCaption = "No Caption", $strURL = "", $strTitle = "", $strTarget = "") {
  addItem($strCaption, $strURL, "+", $strTitle, $strTarget);

}
?>