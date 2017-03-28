<?php
// ================================================================x
// /Index.php
// ----------------------------------------------------------------
// Nome     : Início do sistema
// Home     : ruben.zevallos.com.br
// Criacao  : 11/14/2008 8:35:02 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");

// if (!$_SESSION["Logged"]) header("Location: /logOn.php");

?>
<html>
  <head>

    <title>SIP</title>
    <link rel="stylesheet" type="text/css" href="/formAjax.css" />
  </head>
  <body bgcolor="#ffffff">
    <br /><br />
    <center>
      <div style="width:350px"><img src="/Images/InSystems_Logo.gif" border="0">
        <br /><br />
        <h3>Merlim 2.0</h3>
        <br /><br /><div style="text-align:left"><? echo $_SESSION["PEpesNome"] ?>
        <br /><br />Último Acesso: <? echo $_SESSION["pelLogonUltimo"] ?>
        <br />IP: <? echo $_SESSION["pelLogonIP"] ?>
        <br />A sua sessão tem o limite de <? echo $sintCacheExpire ?> minutos
        <br /><br />Opções de acesso:
        <!--? foreach( $_SESSION["PEpesOpcoes"] as $id => $opcao ) echo "<br />&nbsp;&nbsp;&nbsp;$id: " . $opcao["opcSigla"]; ?-->
      </div>
    </center>
  </body>
</html>

