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
?>
<html>
<head>
<title>TCP</title>
<link rel="stylesheet" href="/library/FormValidationTableLess.css" type="text/css" />
<style type="text/css">
body {margin:2px 0px 0px 12px;
	padding:0px;
  font-family: verdana, arial, helvetica;
  font-size: 100%;
  background-color: #FFFFFF;}

.barra {
	color: #ffffff;
	background-repeat: no-repeat;
	font-family: verdana, arial, helvetica, sans-serif;
	font-Size: 1em}

.corpo {
	background-position: 0px 75px;
	font-size: 9px;
	background-image: url(TopBarraRepete.gif);
	margin: 0px;
	color: #333333;
	background-repeat:
	repeat-x;
	font-family: verdana, arial, helvetica, sans-serif
}

</style>
</head>
<body class="corpo">

<!-- O nome da logo está estático até o momento em que for corretamente populada as tabelas de pessoas e integrados.-->

<div style="float:left;margin-left:20px"><img src="/Images/<? /*echo $_SESSION["PEIpesApelido"]*/ ?>ISTCartoes_Logo.gif" border="0" alt="<? echo $_SESSION["PEIpesNome"]; ?>"></div>
<div style="width:500px;float:left;margin-top:75px;">
  Usuário:
  <b><? echo $_SESSION["pelApelido"]; ?></b>
  (<a href="/Result/sipUsuario.htm?t=<? echo $_SESSION["pelCodigo"]; ?>" target="Body" /> <? echo $_SESSION["pelCodigo"]; ?></a>)
     - <b><? echo $_SESSION["PEIpesNome"]; ?> </b>
     (<a href="/Result/sipIntegrado.htm?t=<? echo $_SESSION["peiCodigo"]; ?>" target="Body" /><? echo $_SESSION["peiCodigo"]; ?></a>)
      [<? echo $_SESSION["pelLogonIP"] ?>]
</div>
</body>
</html>
