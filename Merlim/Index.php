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

$strSession = "/Empty.php";

if (!$_SESSION["Requested"]) $strSession = $_SESSION["Request"] ? "/".$_SESSION["Request"] : "/Empty.php";

$_SESSION["Requested"] = 1;

?>
<html>
<head>
	<title>Merlim 2.0 - <? echo $_SESSION["PEpesNome"] ?> (<? echo $_SESSION["pelCodigo"] ?>)</title>
</head>
<frameset rows="91,*" style="border: solid 1px #999999;">
	<frame name="Top" src="Top.php" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" noresize marginwidth=0 marginheight=0>
  <frameset cols="180,*">
  	<frame name="Menu" src="MenuAJAX.htm" scrolling="auto" marginheight="0" marginwidth="0">
  	<frame name="Body" src="<? echo $strSession; ?>" scrolling="auto" marginheight="0" marginwidth="0">
  </frameset>
</frameset>
</html>
