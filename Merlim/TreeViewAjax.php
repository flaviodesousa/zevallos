<?php
// ================================================================x
// /TreeViewAjax.php
// ----------------------------------------------------------------
// Nome     : TreeView usando AJAX
// Home     : ruben.zevallos.com.br
// Criacao  : 11/30/2008 1:21:34 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

//  <ul>
//    <li class="off nobackgrond"><img src="/Library/TreeView2/Plus.gif" onclick="TreeviewToggle(event);" />4 - Other Things</li>
//    <li class="onhover"><img src="/Library/TreeView2/Middle.gif" />3.1.1 - Dados do Integrado</li>
//    <li class="onhover nobackgrond"><img src="/Library/TreeView2/MiddleEnd.gif" />3.2.1 - Tabelas do Integrado<br />Tabelas do Integrado</li>
//  </ul>

  $sparTarget = $_REQUEST["t"];

  $sparTarget++;

  $strLI = "0||4 - Other Things|"."\r\n".
           "0||5 - Dados do Integrado|/Teste.php?o=4"."\r\n".
           "1|".$sparTarget."|".$sparTarget.".6 - Tabelas do Integrado|/Teste.php?o=4";

  echo $strLI;

?>