<?php
// ================================================================
// /formGenerator.php
// ----------------------------------------------------------------
// Nome     : Gera o HTML dos formulários com base nas tableas do MSSQL
// Home     : istcartoes.com.br
// Criacao  : 10/11/2008 19:39:21
// Autor    : Ruben Zevallos Jr. <ruben@istcartoes.com.br>
// Versao   : 1.0.0.1
// Local    : Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");

$sstrCurrentScript = "formGenerator.php";

ConnectMSSQL();

main();

DisconnectMSSQL();

if ($objSQL) mssql_free_result($objSQL);

// ================================================================
//
// ----------------------------------------------------------------
function main() {
  ?>

  <html>
  <head>
  <title>Gerador de Fomulários - 4.0</title>
  <meta http-equiv="pics-label" content="(pics-1.1 "http://www.rsac.org/ratingsv01.html" l gen true comment "rsaci north america server" by "webmaster@istcartoes.com.br" for "http://www.zevallos.com.br" on "1997.06.26t21:24-0500" r (n 0 s 0 v 0 l 0))"><meta name="generator" content="hipertools® v3.0 - rc6">

  <basefont face="Verdana, Arial, Helvetica, sans-serif">

  <link REL="stylesheet" TYPE="text/css" HREF="/library/FormFieldManager.css"/>

  <style type="text/css">
  body {
    margin:10px 10px 0px 10px;
  	padding:0px;
    font-family: verdana, arial, helvetica;
    font-size: 10px}

  .point {padding: 5px; cursor: default; overflow: auto; position: absolute; display: none; width: 150px; height: 150px; background-color: #EFEFEF;}

  input {font-size: 8pt; background-color: #FFFFFF; font-family: verdana; border: 1px solid #000080}

  #footertable {border-top:1px solid #192985;
    margin-top: 10px;
    font-size: 90%;
    white-space: nowrap;
    width:100%;
    text-align: center}

  #footertable .left {float: left;
    width:10%}

  #footertable .right {float: right;
    width:10%}

  a:link, a:visited, a:active {
    color:navy;
    text-decoration:none;
    font-size: 7.5pt;
    font-family: verdana;
    }
  a:hover {
    color:red;
    text-decoration:none;
    font-size: 7.5pt;
    font-family: verdana;
    }

  -->
  </style>

  </HEAD>
  <body bgcolor="White" text=black link=red vlink=navy alink=red topmargin=0 leftmargin=0>
  <?
  extract($GLOBALS);

  if ( $_SESSION["PEpesOpcoes"][900] || 1) {
  
    switch ($sparOption) {
      case 10:
      case 11:
        createForm($sparOption);
        break;
  
      default:
        listTables();
    }
  }
  else {
    echo "<b><font color=\"#FF0000\">Usuário sem permissão de acesso a esta função!</font></b>";
  }

  ?>
<div id="footertable">
  <div class="left">&nbsp;</div>
  <div class="right"><a href="http://www.w3.org/TR/REC-CSS2/" target="_blank"><img src="/library/W3CSS.gif" border="0" alt="W3 CSS" /></a><br /><img src="/library/TableLess.gif" border="0" alt="TableLess"/></div>
  <div class="center">Desenvolvido por Ruben <a href="http://www.zevallos.com.br" target="_blank">Zevallos</a><sup>®</sup> Jr.<br />Sugestões ou problemas encaminhar para o <a href="mailto:info@zevallos.com.br"><img src="/Library/Icones/mailto.gif" border="0" alt="Mail To" /> &lt;info@zevallos.com.br&gt;</a><br />© 1995 - 2010 Ruben Zevallos<sup>®</sup> Jr. todos os direitos reservados.</div>
</div>
  <?
}

// ================================================================
//
// ----------------------------------------------------------------
function listTables() {
  extract($GLOBALS);

  $sql = "SELECT name, object_id, create_date, modify_date".
         " FROM sys.all_objects".
         " WHERE type = 'U'".
         " ORDER BY name";

  $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>listTables</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  ?><table border="0" cellpadding="0" cellspacing="2">
    <tr bgcolor="#C0C0C0"><th>ID</th><th>Nome</th><th>Criação</th><th>Alteração</th></tr>
  <?

  while ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
    $name = $objRS[name];
    $object_id = $objRS[object_id];
    $create_date = $objRS[create_date];
    $modify_date = $objRS[modify_date];


    ?><tr><td align="right"><? echo $object_id ?></td><td><a href="?o=10&t=<? echo $name ?>"><? echo $name ?></a></td><td><? echo $create_date ?></td><td><? echo $modify_date ?></td></tr>
    <?

  }

  ?></table><?

  if ($objSQL) mssql_free_result($objSQL);

}

// ================================================================
//
// ----------------------------------------------------------------
function createForm( $sparOption ) {
  extract($GLOBALS);

  $bmtButton = 0;
  $intFSMax = 6;

  if ($_POST["bmtButton"]) $bmtButton = $_POST["bmtButton"];

  $sql = "SELECT object_id, name".
         " FROM sys.all_objects".
         " WHERE name = '".$sparTarget."'";

  $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
    $intTable = $objRS[object_id];
    $strTable = $objRS[name];

    $strTableName = $strTable;

  }

  $intTableCodigo = 0;

  if ($bmtButton) {
    if ($_POST["intTableCodigo"]) $intTableCodigo = $_POST["intTableCodigo"];
    if (trim($_POST["txtTitulo"])) $strTable = trim($_POST["txtTitulo"]);

    if ($intTableCodigo) {
      $sql = "UPDATE sipTabela".
             " SET tabNome = '".$strTableName."'".
             ", tabTitle = '".$strTable."'".
             ", tabAlterado = 1".
             ", tabAlteracao = GetDate()".
             " WHERE tabCodigo = ".$intTableCodigo;

      $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    } else {
      $sql = "INSERT sipTabela".
             " (tabNome, tabTitle)".
             " VALUES".
             " ('".$strTableName."', '".$strTable."')".
             " SELECT @@IDENTITY AS tabCodigo";

      $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) $intTableCodigo = $objRS["tabCodigo"];

    }
  } else {
    $sql = "SELECT tabCodigo, tabNome, tabTitle".
           " FROM sipTabela".
           " WHERE tabNome = '".$sparTarget."'";

    $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
      $intTableCodigo = $objRS[tabCodigo];
      $strTable       = $objRS[tabTitle];

    }
  }

  $strFieldsetTable = "<fieldset title=\"\">".
                      "  <legend>Fieldset</legend>".
                      "    <table border=\"0\" cellpadding=\"0\" cellspacing=\"2\">".
                      "    <tr bgcolor=\"#C0C0C0\">".
                      "      <th title=\"Fieldset ID\">ID</th><th title=\"Title\">Title</th><th title=\"Legend\">Legend</th>".
                      "    </tr>";

  $arrFS = Array();

  for ($intFS = 1; $intFS <= $intFSMax; $intFS++) {
    $strFSTitle  = "";
    $strFSLegend = "";
    $intFSCodigo = "";

    if ($bmtButton) {
      if ($_POST["intFSCodigo".$intFS]) $intFSCodigo = $_POST["intFSCodigo".$intFS];

      if (trim($_POST["strFSTitle".$intFS]))  $strFSTitle  = trim($_POST["strFSTitle".$intFS]);
      if (trim($_POST["strFSLegend".$intFS])) $strFSLegend = trim($_POST["strFSLegend".$intFS]);

      $arrFS[$intFS][0] = $strFSTitle;
      $arrFS[$intFS][1] = $strFSLegend;

      if ($intFSCodigo) {
        $sql = "UPDATE sipTabelaFieldset".
               " SET tfsTitle = '".$strFSTitle."'".
               ", tfsLegend = '".$strFSLegend."'".
               ", tfsAlterado = 1".
               ", tfsAlteracao = GetDate()".
               " WHERE tfsCodigo = ".$intFSCodigo;

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      } else {
        if (!$strFSTitle && $strFSLegend) $strFSTitle = $strFSLegend;

        if ($strFSTitle && !$strFSLegend) $strFSLegend = $strFSTitle;

        if ($strFSLegend) {
          $sql = "INSERT sipTabelaFieldset".
                 " (tfsTabela, tfsID, tfsTitle, tfsLegend)".
                 " VALUES".
                 " (".$intTableCodigo. ", ".$intFS.", '".$strFSTitle."', '".$strFSLegend."')".
                 " SELECT @@IDENTITY AS tfsCodigo";

          $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

          if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) $intFSCodigo = $objRS["tfsCodigo"];
        }
      }
    } else {
      $sql = "SELECT tfsCodigo, tfsTitle, tfsLegend".
             " FROM sipTabelaFieldset".
             " WHERE tfsTabela = ".$intTableCodigo." AND tfsID = ".$intFS;

      $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
        $strFSTitle = $objRS[tfsTitle];
        $strFSLegend = $objRS[tfsLegend];
        $intFSCodigo = $objRS[tfsCodigo];

        $arrFS[$intFS][0] = $strFSTitle;
        $arrFS[$intFS][1] = $strFSLegend;

      }
    }

    $strFieldsetTable .= "<tr>".
                         "  <td>".$intFS."<input type=\"hidden\" name=\"intFSCodigo".$intFS."\" value=\"".$intFSCodigo."\" /></td>".
                         "  <td><input type=\"text\" size=\"30\" name=\"strFSTitle".$intFS."\" id=\"strFSTitle".$intFS."\" value=\"".$strFSTitle."\" /></td>".
                         "  <td><input type=\"text\" size=\"30\" name=\"strFSLegend".$intFS."\" id=\"strFSLegend".$intFS."\" value=\"".$strFSLegend."\" /></td>".
                         "</tr>";

  }

  $strFieldsetTable .= "  </table>".
                       "</fieldset>";


  $sql = "SELECT DISTINCT FK.object_id AS FKobject_id".
         " , FK.name AS FKname".
         " , LFK.parent_column_id AS LFKparent_column_id".
         " , FKC.name AS FKCname".
         " , FKC.system_type_id AS FKCsystem_type_id".
         " , FKC.is_nullable AS FKCis_nullable".
         " , RK.object_id AS RKobject_id".
         " , RK.name AS RKname".
         " , LFK.referenced_column_id AS LFKreferenced_column_id".
         " , FKR.name AS FKRname".
         " , FKR.system_type_id AS FKRsystem_type_id".
         " , FKR.is_nullable AS FKRis_nullable".
         " FROM sys.foreign_key_columns	LFK".
         " LEFT JOIN sys.all_objects FK ON LFK.parent_object_id = FK.object_id".
         " LEFT JOIN sys.all_columns FKC ON LFK.parent_object_id = FKC.object_id AND LFK.parent_column_id = FKC.column_id".
         " LEFT JOIN sys.all_objects RK ON LFK.referenced_object_id = RK.object_id".
         " LEFT JOIN sys.all_columns FKR ON LFK.referenced_object_id = FKR.object_id AND LFK.referenced_column_id = FKR.column_id".
         " WHERE LFK.parent_object_id = ".$intTable.
         " ORDER BY FK.name";

  // echo "<br /><b>sql</b>=$sql";

  $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  ?><h3><? echo $strTableName." - ".$strTable ?></h3>
    <a href="/<? echo $sstrScriptName ?>">Voltar para a lista de tabelas</a><br />
    <form method="post" autocomplete="off" onkeydown="arrows(event)";>
    <input type="hidden" name="bmtButton" id="bmtButton" value="1" />
    <input type="hidden" name="intTableCodigo" id="intTableCodigo" value="<? echo $intTableCodigo ?>" />
    <input type="hidden" name="t" value="<? echo $sparTarget ?>" />
    <fieldset title="">
      <legend>Dados do Formulário</legend>
      <b>Título</b>: <input type="text" size="30" name="txtTitulo" value="<? echo $strTable ?>" />

    </fieldset>
    <fieldset title="">
      <legend>Foreign Key</legend>
      <table border="0" cellpadding="0" cellspacing="2">
      <tr bgcolor="#C0C0C0"><th>ID</th><th>Coluna</th><th>FK</th><th>ID</th><th>PK</th><th>Select</th><th>Where</th><th>Order By</th></tr>
  <?

  $objSQLFK = @mssql_query($sql, objMSSQLConn) or die("<br /><b>BilheteEnd</b> - QUERY: A query ".$sql." do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  while ($objRSFK = mssql_fetch_array($objSQLFK, MSSQL_BOTH)) {
    $FKobject_id             = $objRSFK[FKobject_id];
    $FKname                  = $objRSFK[FKname];
    $LFKparent_column_id     = $objRSFK[LFKparent_column_id];
    $FKCname                 = $objRSFK[FKCname];
    $FKCsystem_type_id       = $objRSFK[FKCsystem_type_id];
    $FKCis_nullable          = $objRSFK[FKCis_nullable];
    $RKobject_id             = $objRSFK[RKobject_id];
    $RKname                  = $objRSFK[RKname];
    $LFKreferenced_column_id = $objRSFK[LFKreferenced_column_id];
    $FKRname                 = $objRSFK[FKRname];
    $FKRsystem_type_id       = $objRSFK[FKRsystem_type_id];
    $FKRis_nullable          = $objRSFK[FKRis_nullable];

    $arrFK[$FKCname][0] = $FKCname;
    $arrFK[$FKCname][1] = $RKname;
    $arrFK[$FKCname][2] = $FKRname;

    $intFK = 0;

    $strSelect  = substr($FKRname, 0, 3)."Nome";
    $strWhere   = substr($FKRname, 0, 3)."Nome";
    $strOrderBy = substr($FKRname, 0, 3)."Nome, ".$FKRname;

    if (trim($_POST[$FKCname."FK"])) $intFK = trim($_POST[$FKCname."FK"]);
    if (trim($_POST[$FKCname."Select"])) $strSelect = trim($_POST[$FKCname."Select"]);
    if (trim($_POST[$FKCname."Where"])) $strWhere = trim($_POST[$FKCname."Where"]);
    if (trim($_POST[$FKCname."OrderBy"])) $strOrderBy =trim( $_POST[$FKCname."OrderBy"]);

    $arrFK[$FKCname][3] = $strSelect;
    $arrFK[$FKCname][4] = $strWhere;
    $arrFK[$FKCname][5] = $strOrderBy;
    $arrFK[$FKCname][6] = $intFK;

    // Salvar ou Atualizar no BD
    if ($bmtButton) {
      if ($intFK) {
        $sql = "UPDATE sipTabelaFK"
               ." SET tafColuna = '".$FKCname."'"
               .", tafTable = '".$RKname."'"
               .", tafSelect = '".$strSelect."'"
               .", tafWhere = '".$strWhere."'"
               .", tafOrderBy = '".$strOrderBy."'"
               ." WHERE tafCodigo = ".$intFK;

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      } else {
        $sql = "INSERT sipTabelaFK".
               " (tafTabela".
               ", tafColuna".
               ", tafTable".
               ", tafSelect".
               ", tafWhere".
               ", tafOrderBy)".
               " VALUES (".
               "  ".$intTableCodigo.
               ", '".$FKCname."'".
               ", '".$RKname."'".
               ", '".$strSelect."'".
               ", '".$strWhere."'".
               ", '".$strOrderBy."'".
               ")".
               " SELECT @@IDENTITY AS tacCodigo";

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

        if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) $intID = $objRS["tacCodigo"];

      }
    } else {
      // Ou ler do BD se tiver algo

      $sql = "SELECT".
             "  tafCodigo".
             ", tafTabela".
             ", tafColuna".
             ", tafTable".
             ", tafSelect".
             ", tafWhere".
             ", tafOrderBy".
             ", tafInclusao".
             " FROM sipTabelaFK".
             " WHERE tafTabela = ".$intTableCodigo." AND tafTable = '".$RKname."'";

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

        if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
           $strSelect   = $objRS[tafSelect];
           $strWhere    = $objRS[tafWhere];
           $strOrderBy  = $objRS[tafOrderBy];

           $intFK       = $objRS[tafCodigo];

      }
    }

    ?><tr><?
    ?><td align="right"><? echo $LFKparent_column_id ?></td><td><? echo $FKCname ?></td><td><? echo $RKname ?></td><td align="right"><? echo $LFKreferenced_column_id ?></td><td><? echo $FKRname ?></td><?
    ?><td><input type="hidden" name="<? echo $FKCname ?>FK" id="<? echo $FKCname ?>IdFK" value="<? echo $intFK ?>" /><input type="text" name="<? echo $FKCname ?>Select" id="<? echo $FKCname ?>Select" value="<? echo $strSelect ?>" /></td><?
    ?><td><input type="text" name="<? echo $FKCname ?>Where" id="<? echo $FKCname ?>Where" value="<? echo $strWhere ?>" /></td><?
    ?><td><input type="text" name="<? echo $FKCname ?>OrderBy" id="<? echo $FKCname ?>OrderBy" value="<? echo $strOrderBy ?>" /></td><?
    ?></tr>
    <?

  }

  ?></table>
  </fieldset>
  <?

  $sql = "SELECT Co.column_id AS Cocolumn_id".
         " , Co.name AS CoName".
         " , Co.column_id AS Cocolumn_id".
         " , Ty.name AS Tyname".
         " , Co.system_type_id AS Cosystem_type_id".
         " , Co.max_length AS Comax_length".
         " , Co.precision AS Coprecision".
         " , Co.is_nullable AS Cois_nullable".
         " , Co.is_identity AS Cois_identity".
         " FROM sys.all_columns Co".
         " LEFT JOIN sys.types Ty ON Co.system_type_id = Ty.system_type_id".
         " WHERE Co.object_id = ".$intTable.
         " ORDER BY Co.column_id";

  // echo "<br /><b>sql</b>=$sql";

  $objSQLCo = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

  ?>
  <fieldset title="">
    <legend>Campos</legend>
    <table border="0" cellpadding="0" cellspacing="2">
      <tr bgcolor="#C0C0C0">
        <th title="Used">U</td>
        <th title="Order">Ord</td>
        <th title="Fieldset">FS</td>
        <th>Field</td>
        <th>Label</td>
        <th>Type</td>
        <th>Size</td>
        <th title="Max size">Max</td>
        <th>Rows</td>
        <th>Validation</td>
        <th title="Required">R</td>
        <th title="Read Only">RO</td>
        <th>Title</td>
        <th title="Find">Fi</td>
        <th title="List">Li</td>
        <th title="List order">LO</td>
        <th title="BR Clear All">BR</td>
      </tr>
    <?

  $strField = "";

  $intTypeLast = 0;

  $OptionSelect = "";
  $intFieldsetOld = 0;

  $strFieldsAll = "";

  $strFindInput = "";
  $blnListInput = false;

  $strListInput = "";
  $blnFindInput = false;

  while ($objRSCo = mssql_fetch_array($objSQLCo, MSSQL_BOTH)) {
    $Cocolumn_id      = $objRSCo[Cocolumn_id];
    $CoName           = $objRSCo[CoName];
    $Cocolumn_id      = $objRSCo[Cocolumn_id];
    $Tyname           = $objRSCo[Tyname];
    $Cosystem_type_id = $objRSCo[Cosystem_type_id];
    $Comax_length     = $objRSCo[Comax_length];
    $Coprecision      = $objRSCo[Coprecision];
    $Cois_nullable    = $objRSCo[Cois_nullable];
    $Cois_identity    = $objRSCo[Cois_identity];

    if ($strFieldsAll) $strFieldsAll .= ";";

    $strFieldsAll .= $CoName;

    $intRequired = 1;

    if ($Cois_nullable) $intRequired = 0;

    $strRequired = "";
    $strRequiredF = "";

    $intReadOnly = 0;
    $strReadOnly = "";
    $strReadOnlyF = "";

    $intUsed = 1;
    $strUsed = "";

    if ($Cois_identity) $intUsed = 0;

    $intMinSize = 0;
    $strMinSize = "";

    $intMaxSize = 0;
    $strMaxSize = "";

    $stronClick = "";
    $stronKeyUp = "";

    $intFind = 0;
    $strFind = "";

    $intList = 0;
    $strList = "";

    $strListOrder = "";

    $intBR = 1;
    $strBR = "";

    $strName = substr($CoName, 3);

    // Acentua
    $strName2E = substr($strName, -2);
    $strName2S = substr($strName, 0, strlen($strName) - 2);

    $strName3E = substr($strName, -3);
    $strName3S = substr($strName, 0, strlen($strName) - 3);

    $strName4E = substr($strName, -4);
    $strName4S = substr($strName, 0, strlen($strName) - 4);

    $strName5E = substr($strName, -5);
    $strName5S = substr($strName, 0, strlen($strName) - 5);

    $strName6E = substr($strName, -6);
    $strName6S = substr($strName, 0, strlen($strName) - 6);

    if ($strName2E == "ao") $strName = $strName2S."ão";

    if ($strName3E == "cao") $strName = $strName3S."ção";

    if ($strName4E == "ivel") $strName = $strName4S."ível";
    if ($strName4E == "icio") $strName = $strName4S."ício";
    if ($strName4E == "ario") $strName = $strName4S."ário";

    if ($strName5E == "odigo") $strName = $strName5S."ódigo";
    if ($strName5E == "encia") $strName = $strName5S."ência";
    if ($strName5E == "igito") $strName = $strName5S."ígito";
    if ($strName5E == "umero") $strName = $strName5S."úmero";

    if ($strName6E == "ultimo") $strName = $strName6S."último";
    if ($strName6E == "Ultimo") $strName = $strName6S."Último";
    if ($strName6E == "ublico") $strName = $strName6S."úblico";

    $intValidation = 0;

    $strFKPK      = $arrFK[$CoName][2];
    $strFKSelect  = $arrFK[$CoName][3];
    $strFKFrom    = $arrFK[$CoName][1];
    $strFKWhere   = $arrFK[$CoName][4];
    $strFKOrderBy = $arrFK[$CoName][5];

    $intType = 1;

    $strTextAreaSize = "";
    $strTextAreaMinSize = "";
    $strTextAreaIncrement = "";

    $intTextAreaSize = "";
    $intTextAreaMinSize = "";
    $intTextAreaIncrement = "";

    // Decide o tipo e acordo com o tipo do dado
    switch ($Tyname) {
      case "tinyint":
        $intType = 3;

        $intMinSize = 1;

        $intMaxSize = 3;

        $intValidation = 7;

        $strRangeBegin = 0;
        $strRangeEnd = 256;

        break;

      case "smallint":
        $intMinSize = 3;

        $intMaxSize = 5;

        $intValidation = 7;

        $strRangeBegin = 0;
        $strRangeEnd = 256;

        break;

      case "int":
        $intMinSize = 12;

        $intMaxSize = 10;

        $intValidation = 7;

        $strRangeBegin = 0;
        $strRangeEnd = 4294967296;

        break;

      case "bigint":
        $intMinSize = 20;

        $intMaxSize = 20;

        break;
      case "money":
        $intValidation = 9;

        $intMinSize = 20;

        $intMaxSize = 20;
        break;

      case "numeric":
        $intMinSize = 9;

        $intMaxSize = 10;

        $intValidation = 7;

        break;

      case "datetime":
      case "smalldatetime":
        $intMinSize = 12;

        $intMaxSize = 12;

        $intValidation = 1;

        break;

      case "varchar":
      case "nvarchar":
      case "char":
      case "nchar":
        $intMinSize = $Comax_length;
        $intMaxSize = $Comax_length;

        if ($intMinSize > 40) $intMinSize = 40;

        $intType = 1;

        break;

      case "text":
      case "ntext":
        $intType = 2; // TEXTAREA

        $intCols = 40;
        $intRows = 4;

        $intTextAreaSize = 1000;
        $intTextAreaMinSize = 10;
        $intTextAreaIncrement = 0;

        $intValidation = 1;

        break;

      default:
        $intMinSize = 10;
        $intMaxSize = $Comax_length;
        $intType = 1;

    }

    // Decide qual tipo de campo será mostrado

    switch (strtolower($strName)) {
      case "inclusão":
      case "alteração":
      case "exclusão":

        if ($intType == 1 && ($Tyname == "int" || $Tyname == "bigint")) {
          $intType = 7;
          $intReadOnly = 1;
          $intRequired = 0;

          $strFKPK      = "sesCodigo";
          $strFKSelect  = "sesInicio";
          $strFKFrom    = "sipSessao";
          $strFKWhere   = "sesInicio";
          $strFKOrderBy = "sesInicio, sesCodigo";

        }
        break;

      case "ativo":
      case "alterado":
        $intType = 3;
        break;

      case "resumo":
      case "descricao":
        $intFind = 1;
        $intList = 1;

        $intType = 2; // TEXTAREA

        $intCols = 40;
        $intRows = 4;

        $intTextAreaSize = 1000;
        $intTextAreaMinSize = 10;
        $intTextAreaIncrement = 0;

        break;

      default:
        $intType = 0; // INPUT

    }

    //  34 - image
    //  35 - text
    //  36 - uniqueidentifier
    //  48 - tinyint
    //  52 - smallint
    //  56 - int
    //  58 - smalldatetime
    //  59 - real
    //  60 - money
    //  61 - datetime
    //  62 - float
    //  98 - sql_variant
    //  99 - ntext
    // 104 - bit
    // 106 - decimal
    // 108 - numeric
    // 122 - smallmoney
    // 127 - bigint
    // 165 - varbinary
    // 167 - varchar
    // 173 - binary
    // 175 - char
    // 189 - timestamp
    // 231 - nvarchar
    // 239 - nchar
    // 241 - xml
    // 231 - sysname

    $blnPublish = true;

    if ($Cois_identity) {
      $strPKField = $CoName;
      $intType    = 6;

      $blnPublish = false;

    }

    $intFieldCount++;

    $strOrder = $intFieldCount;
    $strTitle = "Digite o nome do ".$strName;

    $strNameClean = $strName;

    $strName = "&".$strName;

    $intFieldset = 1;

    if ($bmtButton) {
      $intUsed = 0;
      $intBR = 0;
    }

    $intListOrder = 0;
    $intOrder = 0;
    $intRows = 0;

    $intID = 0;


    if (trim($_POST["intID".$CoName])) $intID = trim($_POST["intID".$CoName]);
    if (trim($_POST[$CoName."Used"])) $intUsed = trim($_POST[$CoName."Used"]);
    if (trim($_POST[$CoName."Order"])) $intOrder= trim($_POST[$CoName."Order"]);
    if (trim($_POST[$CoName."Fieldset"])) $intFieldset = trim($_POST[$CoName."Fieldset"]);
    if (trim($_POST[$CoName."Label"])) $strName = trim($_POST[$CoName."Label"]);
    if (trim($_POST[$CoName."Type"])) $intType = trim($_POST[$CoName."Type"]);
    if (trim($_POST[$CoName."MinSize"])) $intMinSize = trim($_POST[$CoName."MinSize"]);
    if (trim($_POST[$CoName."MaxSize"])) $intMaxSize = trim($_POST[$CoName."MaxSize"]);
    if (trim($_POST[$CoName."Rows"])) $intRows = trim($_POST[$CoName."Rows"]);
    if (trim($_POST[$CoName."Validation"])) $intValidation = trim($_POST[$CoName."Validation"]);
    if (trim($_POST[$CoName."Required"])) $intRequired = trim($_POST[$CoName."Required"]);
    if (trim($_POST[$CoName."ReadOnly"])) $intReadOnly = trim($_POST[$CoName."ReadOnly"]);
    if (trim($_POST[$CoName."Title"])) $strTitle = trim($_POST[$CoName."Title"]);
    if (trim($_POST[$CoName."Find"])) $intFind = trim($_POST[$CoName."Find"]);
    if (trim($_POST[$CoName."List"])) $intList = trim($_POST[$CoName."List"]);
    if (trim($_POST[$CoName."ListOrder"])) $intListOrder = trim($_POST[$CoName."ListOrder"]);
    if (trim($_POST[$CoName."BR"])) $intBR = trim($_POST[$CoName."BR"]);


    // Salvar ou Atualizar no BD
    if ($bmtButton) {
      if ($intID) {
        $sql = "UPDATE sipTabelaCampo".
               " SET tacUsed    = ".$intUsed.
               ", tacOrder      = ".$intOrder.
               ", tacFieldset   = ".$intFieldset.
               ", tacNome       = '".$CoName."'".
               ", tacLabel      = '".$strName."'".
               ", tacType       = ".$intType.
               ", tacSize       = ".$intMinSize.
               ", tacMaxSize    = ".$intMaxSize.
               ", tacRows       = ".$intRows.
               ", tacValidation = ".$intValidation.
               ", tacRequired   = ".$intRequired.
               ", tacReadOnly   = ".$intReadOnly.
               ", tacTitle      = '".$strTitle."'".
               ", tacFind       = ".$intFind.
               ", tacList       = ".$intList.
               ", tacListOrder  = ".$intListOrder.
               ", tacBR         = ".$intBR.
               " WHERE tacTabela = ".$intTableCodigo." AND tacCodigo = ".$intID;

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

      } else {
        $sql = "INSERT sipTabelaCampo".
               " (tacTabela".
               ", tacUsed".
               ", tacOrder".
               ", tacFieldset".
               ", tacNome".
               ", tacLabel".
               ", tacType".
               ", tacSize".
               ", tacMaxSize".
               ", tacRows".
               ", tacValidation".
               ", tacRequired".
               ", tacReadOnly".
               ", tacTitle".
               ", tacFind".
               ", tacList".
               ", tacListOrder".
               ", tacBR)".
               " VALUES (".
               "  ".$intTableCodigo.
               ", ".$intUsed.
               ", ".$intOrder.
               ", ".$intFieldset.
               ", '".$CoName."'".
               ", '".$strName."'".
               ", ".$intType.
               ", ".$intMinSize.
               ", ".$intMaxSize.
               ", ".$intRows.
               ", ".$intValidation.
               ", ".$intRequired.
               ", ".$intReadOnly.
               ", '".$strTitle."'".
               ", ".$intFind.
               ", ".$intList.
               ", ".$intListOrder.
               ", ".$intBR.
               ")".
               " SELECT @@IDENTITY AS tacCodigo";

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

        if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) $intID = $objRS["tacCodigo"];

      }
    } else {
      // Ou ler do BD se tiver algo

      $sql = "SELECT".
             "  tacCodigo".
             ", tacUsed".
             ", tacOrder".
             ", tacFieldset".
             ", tacNome".
             ", tacLabel".
             ", tacType".
             ", tacSize".
             ", tacMaxSize".
             ", tacRows".
             ", tacValidation".
             ", tacRequired".
             ", tacReadOnly".
             ", tacTitle".
             ", tacFind".
             ", tacList".
             ", tacListOrder".
             ", tacBR".
             " FROM siPTabelaCampo".
             " WHERE tacTabela = ".$intTableCodigo." AND tacNome = '".$CoName."'";

        $objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>createForm</b> - QUERY: A query do MSSQL (local) não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

        if ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
           $intUsed       = $objRS[tacUsed];
           $intOrder      = $objRS[tacOrder];
           $intFieldset   = $objRS[tacFieldset];
           $strName       = $objRS[tacNome];
           $strName       = $objRS[tacLabel];
           $intType       = $objRS[tacType];
           $intMinSize    = $objRS[tacSize];
           $intMaxSize    = $objRS[tacMaxSize];
           $intRows       = $objRS[tacRows];
           $intValidation = $objRS[tacValidation];
           $intRequired   = $objRS[tacRequired];
           $intReadOnly   = $objRS[tacReadOnly];
           $strTitle      = $objRS[tacTitle];
           $intFind       = $objRS[tacFind];
           $intList       = $objRS[tacList];
           $intListOrder  = $objRS[tacListOrder];
           $intBR         = $objRS[tacBR];

           $intID         = $objRS[tacCodigo];

      }
    }

    $strLabelFor = $CoName;

    if ($arrFK[$CoName][0] == $CoName) {
      $intType = 7;

      $strLabelFor .= "_AC";
    }

    $strNameClean = str_replace("&", "", $strName);

    if ($intRequired) $strRequiredF = "\r\n      required=\"O $strNameClean é obrigatório!\"";
    if ($intReadOnly) $strReadOnlyF = "\r\n      readonly=\"true\"";
    if ($intMinSize) $strMinSize = "\r\n      size=\"$intMinSize\"";
    if ($intMaxSize) $strMaxSize = "\r\n      maxlength=\"$intMaxSize\"";

    if ($intCols) $strCols = "\r\n      cols=\"$intCols\"";
    if ($intRows) $strRows = "\r\n      rows=\"$intRows\"";

    $strTextAreaSize = "";
    $strTextAreaMinSize = "";
    $strTextAreaIncrement = "";

    if ($intTextAreaSize) $strTextAreaSize = "\r\n      size=\"$intTextAreaSize\"";
    if ($intTextAreaMinSize) $strTextAreaMinSize = "\r\n      minsize=\"$intTextAreaSize\"";
    if ($intTextAreaIncrement) $strTextAreaIncrement = "\r\n      increment=\"$intTextAreaIncrement\"";

    $strDisabled = " disabled=\"true\"";

    $strTRStyle = " bgcolor=\"#c7d0dc\"";

    $stronKeyUp = "";
    $stronClick = "";
    $strInputText = "";

    $strDateBr = "";
    $strInputText = "";

    switch ($intType) {
      case 1: // Text
        $strInputText = "text";
        break;

      case 2: // TextArea
        $stronKeyUp = "\r\n      onKeyUp=\"FieldDelimiter(); FieldCounter();\"";

        break;

      case 3: // CheckBox
        $strInputText = "checkbox"; // CHECKBOX
        $stronClick = "\r\n      onClick=\"this.value = ((this.checked) ? '1' : '0');FieldChanged(event);\"";

        break;

      case 4: // Select
        break;

      case 5: // Radio
        $strInputText = "radio";
        break;

      case 6: // Hidden
        $strInputText = "hidden";
        break;

      case 7: // Select AJAX
        $strInputText = "text";
        break;

      case 8: // Password
        $strInputText = "password";
        break;

    }

    $strDateBr = "";
    $strDateUs = "";
    $strMoney  = "";

    $strCEP    = "";

    $strCPF     = "";
    $strCNPJ    = "";
    $strCPFCNPJ = "";

    $strEMail = "";

    $strPhone = "";
    $strPhoneArea = "";
    $strIPAddress = "";

    switch ($intValidation) {
      case 1: // Date BR
        $strDateBr = "\r\n      datebr=\"true\"";
        break;

      case 2: // Date US
        $strDateUs = "\r\n      dateus=\"true\"";
        break;

      case 3: // CPF
        $strCPF = "\r\n      cpf=\"true\"";
        break;

      case 4: // CNPJ
        $strCNPJ = "\r\n      cnpj=\"true\"";
        break;

      case 5: // CEP
        $strCEP = "\r\n      cep=\"true\"";
        break;

      case 6: // E-Mail
        $strEMail = "\r\n      email=\"true\"";
        break;

      case 7: // Inteiro
        break;

      case 8: // Letras
        break;

      case 9: // Money
        $strMoney = "\r\n      money=\"true\"";
        break;

      case 10: // CPF/CNPJ
        $strCPFCNPJ = "\r\n      cpfcnpj=\"true\"";
        break;

      case 11: // Phone
        $strPhone = "\r\n      phone=\"true\"";
        break;

      case 12: // Phone
        $strPhoneArea = "\r\n      phonearea=\"true\"";
        break;

      case 13: // IPAddress
        $strIPAddress = "\r\n      ipaddress=\"true\"";
        break;

    }

    if ($intUsed) {
      $strUsed = " checked";
      $strDisabled = "";
      $strTRStyle = "";
    } else {
      $blnPublish = false;
    }

    echo "<tr id=\"objTable".$CoName."\"".$strTRStyle.">";

    echo "<td align=\"right\">".
         "<input type=\"hidden\" value=\"".$intID."\" name=\"intID".$CoName."\" id=\"intID".$CoName."\" />".
         "<input class=\"FieldCheckBox\" type=\"checkbox\" value=\"1\" name=\"".$CoName."Used\"".$strUsed.
                        " onClick=\"this.value = ((this.checked) ? '1' : '0');".
                                 " objTable".$CoName.".bgColor = ((this.checked) ? '' : '#c7d0dc');".
                                 " blnType = ((this.checked) ? false : true);".
                                 $CoName."Order.disabled = blnType;".
                                 $CoName."Fieldset.disabled = blnType;".
                                 $CoName."Label.disabled = blnType;".
                                 $CoName."Type.disabled = blnType;".
                                 $CoName."MinSize.disabled = blnType;".
                                 $CoName."MaxSize.disabled = blnType;".
                                 $CoName."Rows.disabled = blnType;".
                                 $CoName."Validation.disabled = blnType;".
                                 $CoName."Required.disabled = blnType;".
                                 $CoName."Readonly.disabled = blnType;".
                                 $CoName."Title.disabled = blnType;".
                                 $CoName."Find.disabled = blnType;".
                                 $CoName."List.disabled = blnType;".
                                 "ListOrder.disabled = blnType;".
                                 $CoName."BR.disabled = blnType;\">".
         "</td>";

    if ($intOrder) $strOrder = $intOrder;

    echo "<td><input type=\"text\" value=\"".$strOrder."\" name=\"".$CoName."Order\" id=\"".$CoName."Order\" size=\"2\" maxlength=\"4\"".$strDisabled."></td>";

    echo "<td><input type=\"text\" value=\"".$intFieldset."\" name=\"".$CoName."Fieldset\" id=\"".$CoName."Fieldset\" size=\"2\" maxlength=\"4\"".$strDisabled."></td>";

    $strFieldPK = "";

    if ($Cois_identity) $strFieldPK = " (PK)";

    echo "<td title=\"".$Tyname."(".$Comax_length.")\">".$CoName.$strFieldPK."</td>";

    echo "<td><input type=\"text\" value=\"".$strName."\" name=\"".$CoName."Label\" id=\"".$CoName."Label\" size=\"20\" maxlength=\"40\"".$strDisabled."></td>";

    echo "<td>".
         "  <select name=\"".$CoName."Type\" id=\"".$CoName."Type\" value=\"".$intType."\"".$strDisabled.
         "    onchange=\"".$CoName."Rows.disabled = ((this.value == '2') ? false : true)\"".
         "              ".$CoName."Rows.value = ((this.value == '2') ? '4' : '')\">".
         "    <option value=\"1\"".(($intType == 1) ? "SELECTED" : "").">Text</option>".
         "    <option value=\"2\"".(($intType == 2) ? "SELECTED" : "").">TextArea</option>".
         "    <option value=\"3\"".(($intType == 3) ? "SELECTED" : "").">CheckBox</option>".
         "    <option value=\"4\"".(($intType == 4) ? "SELECTED" : "").">Select</option>".
         "    <option value=\"5\"".(($intType == 5) ? "SELECTED" : "").">Radio</option>".
         "    <option value=\"6\"".(($intType == 6) ? "SELECTED" : "").">Hidden</option>".
         "    <option value=\"7\"".(($intType == 7) ? "SELECTED" : "").">Select AJAX</option>".
         "    <option value=\"8\"".(($intType == 8) ? "SELECTED" : "").">Password</option>".
         "    <option value=\"9\"".(($intType == 9) ? "SELECTED" : "").">Select FK</option>".
         "  </select>".
         "</td>";

    echo "<td><input type=\"text\" value=\"".$intMinSize."\" name=\"".$CoName."MinSize\" id=\"".$CoName."MinSize\" size=\"3\" maxlength=\"6\"".$strDisabled."></td>";

    echo "<td><input type=\"text\" value=\"".$intMaxSize."\" name=\"".$CoName."MaxSize\" id=\"".$CoName."MaxSize\" size=\"3\" maxlength=\"6\"".$strDisabled."></td>";

    echo "<td><input type=\"text\" value=\"".$intRows."\" name=\"".$CoName."Rows\" id=\"".$CoName."Rows\" size=\"3\" maxlength=\"6\"".$strDisabledRows."></td>";

    echo "<td>".
         "  <select name=\"".$CoName."Validation\" id=\"".$CoName."Validation\" value=\"".$intValidation."\"".$strDisabled.">".
         "    <option value=\"\"".(($intValidation == 0) ? "SELECTED" : "")."></option>".
         "    <option value=\"1\"".(($intValidation == 1) ? "SELECTED" : "").">Date BR</option>".
         "    <option value=\"2\"".(($intValidation == 2) ? "SELECTED" : "").">Date US</option>".
         "    <option value=\"3\"".(($intValidation == 3) ? "SELECTED" : "").">CPF</option>".
         "    <option value=\"4\"".(($intValidation == 4) ? "SELECTED" : "").">CNPJ</option>".
         "    <option value=\"10\"".(($intValidation == 10) ? "SELECTED" : "").">CPF/CNPJ</option>".
         "    <option value=\"5\"".(($intValidation == 5) ? "SELECTED" : "").">CEP</option>".
         "    <option value=\"6\"".(($intValidation == 6) ? "SELECTED" : "").">E-Mail</option>".
         "    <option value=\"7\"".(($intValidation == 7) ? "SELECTED" : "").">Inteiro</option>".
         "    <option value=\"8\"".(($intValidation == 8) ? "SELECTED" : "").">Letras</option>".
         "    <option value=\"9\"".(($intValidation == 9) ? "SELECTED" : "").">Money</option>".
         "    <option value=\"11\"".(($intValidation == 11) ? "SELECTED" : "").">Telefone sem DDD</option>".
         "    <option value=\"12\"".(($intValidation == 12) ? "SELECTED" : "").">Telefone com DDD</option>".
         "    <option value=\"13\"".(($intValidation == 13) ? "SELECTED" : "").">Endereço IP</option>".
         "  </select>".
         "</td>";

    if ($intRequired) $strRequired = " checked";

    echo "  <td align=\"center\"><input type=\"checkbox\" value=\"1\" name=\"".$CoName."Required\" id=\"".$CoName."Required\" ".$strRequired." onClick=\"this.value = ((this.checked) ? '1' : '0');\"".$strDisabled."></td>";

    if ($intReadOnly) $strReadOnly = " checked";

    echo "  <td align=\"center\"><input type=\"checkbox\" value=\"1\" name=\"".$CoName."ReadOnly\" id=\"".$CoName."ReadOnly\" ".$strReadOnly." onClick=\"this.value = ((this.checked) ? '1' : '0');".$CoName."Required.checked = false;\"".$strDisabled."></td>";

    echo "  <td><input type=\"text\" value=\"".$strTitle."\" name=\"".$CoName."Title\" id=\"".$CoName."Title\" size=\"30\" maxlength=\"100\"".$strDisabled."></td>";

    if ($intFind) $strFind = "checked";

    echo "  <td align=\"center\"><input type=\"checkbox\" value=\"1\" name=\"".$CoName."Find\" id=\"".$CoName."Find\" ".$strFind." onClick=\"this.value = ((this.checked) ? '1' : '0');\"".$strDisabled."></td>";

    if ($intList) $strList = "checked";

    echo "  <td align=\"center\"><input type=\"checkbox\" value=\"1\" name=\"".$CoName."List\" id=\"".$CoName."List\" ".$strList." onClick=\"this.value = ((this.checked) ? '1' : '0');\"".$strDisabled."></td>";

    if ($intListOrder == $Cocolumn_id) $strListOrder = "checked";

    echo "  <td align=\"center\"><input type=\"radio\" name=\"ListOrder\" value=\"".$Cocolumn_id."\" ".$strListOrder.$strDisabled."></td>";

    if ($intBR) $strBR = "checked";

    echo "  <td align=\"center\"><input type=\"checkbox\" name=\"".$CoName."BR\" id=\"".$CoName."BR\" value=\"1\" ".$strBR.$strDisabled."></td>";

    echo "</tr>";

    if ($blnPublish) {
      if ($intType != 6) {
        if ($intFieldsetOld != $intFieldset) {
          if ($intFieldsetOld) $strField .= "\r\n  </fieldset>\r\n";

          if ($arrFS[$intFieldset][0]) {
            $strField .= "  <fieldset title=\"".$arrFS[$intFieldset][0]."\">";
          } else {
            $strField .= "  <fieldset>";
          }

          if ($arrFS[$intFieldset][1]) $strField .= "\r\n    <legend>".$arrFS[$intFieldset][1]."</legend>";

          $intFieldsetOld = $intFieldset;

        } else {
          if (strlen($strField) > 0 && $intBR) $strField .= "\r\n    <br clear=\"all\" />";
        }

        $strField .= "\r\n";

        $intAK = strpos(" ".$strName, "&");

        $strName = str_replace("&", "", $strName);

        $strAK = "";

        $strAKPrefix = "";
        $strAKSufix = "";

        if ($intAK) {
          $intAK--;

          $strAK = substr($strName, $intAK, 1);

          if ($intAK > 1) $strAKPrefix = substr($strName, 0, $intAK);

          if ($intAK < strlen($strName)) $strAKSufix = substr($strName, $intAK + 1);
        }

        if ($intType == 3) {
          if ($strAK) {
            $strField .= "    <label accesskey=\"".$strAK."\" for=\"".$strLabelFor."\">";
          } else {
            $strField .= "    <label for=\"".$strLabelFor."\">";
          }
        } else {
          if ($strAK) {
            $strField .= "    <label accesskey=\"".$strAK."\" for=\"".$strLabelFor."\">".$strAKPrefix."<u>".$strAK."</u>".$strAKSufix.":".
                             "<br />";
          } else {
            $strField .= "    <label for=\"".$strLabelFor."\">".$strName.":".
                             "<br />";
          }
        }
      }

      $strLabelAfter = "";

      switch ($intType) {
        case 2: // Textarea
          $strField .= "\r\n    <span class=\"FormFieldCounter\">(<span id=\"obj$CoName\" TABINDEX=\"-1\">1000</span>)</span>".
                       "\r\n    <textarea".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      name=\"$CoName\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       $stronKeyUp.
                       $strCols.
                       $strRows.
                       $strTextAreaSize.
                       $strTextAreaMinSize.
                       $strTextAreaIncrement.
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);\"".
                       "\r\n      onblur=\"fieldOnBlur(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\" /></textarea>";

          break;

        case 3: // CheckBox
          $strField .= "\r\n    <input".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      type=\"".$strInputText."\"".
                       "\r\n      value=\"\"".
                       "\r\n      name=\"$CoName\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       $stronClick.
                       $strMinSize.
                       $strMaxSize.
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);\"".
                       "\r\n      onblur=\"fieldOnBlur(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\"".
                       $strDateBr.
                       $strDateUs.
                       $strMoney.
                       $strCPF.
                       $strCNPJ.
                       $strCPFCNPJ.
                       $strCEP.
                       $strPhone.
                       $strPhoneArea.
                       $strIPAddress." />";

          break;

        case 4: // SELECT
          $strField .= "\r\n    <select".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      name=\"$CoName\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       "\r\n      value=\"\"".
                       "\r\n      onblur=\"fieldOnBlur(event);SelectKeyDownClear()\"".
                       "\r\n      onclick=\"SelectKeyDownClear()\"".
                       "\r\n      onkeydown=\"SelectKeyDown(this);\"".
                       "\r\n      optionvaluedefault=\"\"".
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\" /></select>";
          break;

        case 6:
          $strField .= "    <input".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      type=\"".$strInputText."\"".
                       "\r\n      value=\"\"".
                       "\r\n      name=\"$CoName\" />\r\n";

          break;

        case 7: // Select AJAX
          $strField .= "\r\n    <input".
                       "\r\n      id=\"".$CoName."_AC\"".
                       "\r\n      type=\"".$strInputText."\"".
                       "\r\n      value=\"\"".
                       "\r\n      name=\"".$CoName."_AC\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       $stronClick.
                       $strMinSize.
                       $strMaxSize.
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);startAutoComplete(this);\"".
                       "\r\n      onblur=\"fieldOnBlur(event);stopAutoComplete(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\"".
                       "\r\n      onkeydown=\"lastKeyPressed(event)\"".
                       "\r\n      fkpk=\"".$strFKPK."\"".
                       "\r\n      fkselect=\"".$strFKSelect."\"".
                       "\r\n      fkfrom=\"".$strFKFrom."\"".
                       "\r\n      fkwhere=\"".$strFKWhere."\"".
                       "\r\n      fkorderby=\"".$strFKOrderBy."\"".
                       $strDateBr.
                       $strDateUs.
                       $strMoney.
                       $strCPF.
                       $strCNPJ.
                       $strCPFCNPJ.
                       $strCEP.
                       $strPhone.
                       $strPhoneArea.
                       $strIPAddress." />";

          $strLabelAfter = "\r\n    <input datatype=\"$Tyname\" datalength=\"$Comax_length\" type=\"hidden\" id=\"".$CoName."\" name=\"".$CoName."\" readonly=\"true\" />".
                           "\r\n    <dl class=\"autoComplete\" id=\"".$CoName."_ACList\" onclick=\"clickAutoComplete(event);\" onmouseover=\"mouseouverAutoComplete(event);\" onmouseout=\"mouseoutAutoComplete(event);\"></dl>";

          break;

        case 9: // SELECT FK
          $strField .= "\r\n    <select".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      name=\"$CoName\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       "\r\n      value=\"\"".
                       "\r\n      onblur=\"fieldOnBlur(event);SelectKeyDownClear()\"".
                       "\r\n      onclick=\"SelectKeyDownClear()\"".
                       "\r\n      onkeydown=\"SelectKeyDown(this);\"".
                       "\r\n      optionvaluedefault=\"\"".
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\"";
                       "\r\n      fkpk=\"".$strFKPK."\"".
                       "\r\n      fkselect=\"".$strFKSelect."\"".
                       "\r\n      fkfrom=\"".$strFKFrom."\"".
                       "\r\n      fkwhere=\"".$strFKWhere."\"".
                       "\r\n      fkorderby=\"".$strFKOrderBy."\" /></select>";


          break;

        default: // 1 - Text
          $strField .= "\r\n    <input".
                       "\r\n      datatype=\"$Tyname\"".
                       "\r\n      datalength=\"$Comax_length\"".
                       "\r\n      id=\"$CoName\"".
                       "\r\n      type=\"".$strInputText."\"".
                       "\r\n      value=\"\"".
                       "\r\n      name=\"$CoName\"".
                       "\r\n      onhelp=\"window.showHelp('FormTemplate.php?o=2&f=$strTableName&t=$CoName'); return false;\"".
                       $stronClick.
                       $strMinSize.
                       $strMaxSize.
                       $strRequiredF.
                       $strReadOnlyF.
                       "\r\n      onfocus=\"StatusTitle(event);fieldOnFocus(event);\"".
                       "\r\n      onblur=\"fieldOnBlur(event);\"".
                       "\r\n      title=\"Digite o $strNameClean\"".
                       "\r\n      onchange=\"FieldChanged()\"".
                       $strDateBr.
                       $strDateUs.
                       $strMoney.
                       $strCPF.
                       $strCNPJ.
                       $strCPFCNPJ.
                       $strCEP.
                       $strPhone.
                       $strPhoneArea.
                       $strIPAddress." />";

      }

      $intTypeLast = $intType;

      if ($intType != 6) {
        switch ($intType) {
          case 3:
            if ($strAK) {
              $strField .= $strAKPrefix."<u>".$strAK."</u>".$strAKSufix;

            } else {
              $strField .= $strName;
            }

            $strField .= "\r\n    </label>".$strLabelAfter;
            break;

          default:
            $strField .= "\r\n    </label>".$strLabelAfter;
        }
      }
    }

    if ($intList && $intUsed) {
      if ($blnListInput) $strListInput .= ",";

      $blnListInput = true;

      $strListInput .= $CoName;
    }

    if ($intFind && $intUsed) {
      if ($blnFindInput) $strFindInput .= ",";

      $blnFindInput = true;

      $strFindInput .= $CoName;
    }

  }

  if ($intFieldsetOld) $strField .= "  </fieldset>";

  ?></table>
  </fieldset>
  <?

  echo $strFieldsetTable;

  ?>
  <input type="hidden" id="objFields" value="<? echo $strFieldsAll ?>" />
  <input type="hidden" id="bmtValue" name="bmtValue" />
  <button type="submit" onClick="bmtValue.value=11;" title="Publicar e Salvar as opções atuais"> Salvar e Publicar </button>
  </form>
  <script type="text/javascript" language="Javascript1.2">
  function arrows(event) {
  	if (window.event) {
  	  var objField = window.event.srcElement;

      event.cancelBubble = true;

  	} else {
  	  var objField = event.target;
    }

    // 38 - Up Arrow
    // 40 - Down Arrow
    // 39 - Right Arrow
    // 37 - Left Arrow

    if (event.keyCode == 38 || event.keyCode == 40) {
      objFields = document.getElementById("objFields");

      arrFields = objFields.value.split(";");

      intI = 0;

      for (i = 0;i < arrFields.length; i++) {
        if (arrFields[i] == objField.name.substr(0, arrFields[i].length)) intI = i;
      }

      if (intI) {
        strSufix = objField.name.substr(arrFields[intI].length);

        if (event.keyCode == 38 && intI > 1) {
          strNext = arrFields[(intI - 1)] + strSufix;

          document.getElementById(strNext).focus();

          return true;
        }

        if (event.keyCode == 40 && intI < arrFields.length) {
          strNext = arrFields[(intI + 1)] + strSufix;

          document.getElementById(strNext).focus();

          return true;
        }
      } else if (objField.name.substr(0, 5) == "strFS") {
        strField = objField.name.substr(5);

        intFS = parseInt(strField.substr(-1));

        strField = "strFS" + strField.substr(0, (strField.length - 1));

        if (event.keyCode == 38 && intFS > 1) {
          strNext = strField + (intFS - 1);

          document.getElementById(strNext).focus();

          return true;
        }

        if (event.keyCode == 40 && intFS < 6) {
          strNext = strField + (intFS + 1);

          document.getElementById(strNext).focus();

          return true;
        }
      }
    }
  }
  </script>
  <?

  $strTemplateFile = $sstrSiteRootDir."Templates\\CreateForm.htm";

  if (file_exists($strTemplateFile)) {
    $strTemplate = ZReadFile($strTemplateFile);

  } else {
    echo "<h3 align=\"center\">O template CreateForm.htm não foi encontrado!</h3>";

  }

  $strConfigurationFields =
        "    <input type=\"hidden\" id=\"f\" name=\"f\" value=\"/Result/".$strTableName."\" />".
    "\r\n".
    "\r\n    <input datatype=\"int\" datalength=\"4\" type=\"hidden\" id=\"".$strPKField."\" name=\"".$strPKField."\" value=\"\" />".
    "\r\n".
    "\r\n    <input type=\"Layout\" id=\"objFormLayout\" name=\"FormLayout\" value=\"Formulario.htm\" />".
    "\r\n".
    "\r\n    <input type=\"formfield\" id=\"objTable\" name=\"Table\" value=\"".$strTableName."\" />".
    "\r\n    <input type=\"formfield\" id=\"objTablePK\" name=\"TablePK\" value=\"".$strPKField."\" />".
    "\r\n".
    "\r\n    <input type=\"navigation\" id=\"objNavigationWhere\" name=\"NavigationWhere\" value=\"".$strPKField."\" />".
    "\r\n    <input type=\"navigation\" id=\"objNavigationOrderBy\" name=\"NavigationOrderBy\" value=\"".$strPKField."\" />".
    "\r\n".
    "\r\n    <input type=\"buttons\" id=\"objButtons\" name=\"Buttons\" value=\"bmtFirst,bmtMoveLast,bmtSave,bmtEdit,bmtCancel,bmtInclude,bmtList,bmtExclude,bmtClear,bmtReload,bmtMoveNext,bmtLast\" />".
    "\r\n".
    "\r\n    <input type=\"List\" id=\"objList\" name=\"List\" value=\"".$strListInput."\" />".
    "\r\n    <input type=\"Find\" id=\"objFind\" name=\"Find\" value=\"".$strFindInput."\" />";

    //echo"<pre>";
    //var_dump ($_POST);

  $strTemplate = str_replace("<ZTagConfigurationFields />", $strConfigurationFields, $strTemplate);

  $strTemplate = str_replace("<ZTagFields />", $strField, $strTemplate);

  $strTemplate = str_replace("<ZTagOptionSelect />", $OptionSelect, $strTemplate);

  $strTemplate = str_replace("<ZtagFormTitle />", $strTable, $strTemplate);

  if ( $_POST[bmtValue] == 11 ) {
    $strFileName = CheckDir($sstrSiteRootDir."Result");

    $sobjFile = fopen($strFileName."\\".$strTableName.".htm", "w+");

    fputs($sobjFile, $strTemplate);
  }

                    ?>
  <br /><a href="/Result/<? echo $strTableName ?>.htm?b=<? echo time() ?>" target="Form">Formulário gerado</a>

  <br />
  <?

  if ($objSQLCo) mssql_free_result($objSQLCo);

//  if ($objSQL) mssql_free_result($objSQL);

  ?><hr />
  <iframe src="/Result/<? echo $strTableName ?>.htm?b=<? echo time() ?>" width="100%" height="600" frameborder="0" scrolling="yes" title="<? echo $strTable ?>"></iframe>
  <?
}

/*
Lista tipos dos campos
SELECT *
 FROM sys.types

 34 - image
 35 - text
 36 - uniqueidentifier
 48 - tinyint
 52 - smallint
 56 - int
 58 - smalldatetime
 59 - real
 60 - money
 61 - datetime
 62 - float
 98 - sql_variant
 99 - ntext
104 - bit
106 - decimal
108 - numeric
122 - smallmoney
127 - bigint
165 - varbinary
167 - varchar
173 - binary
175 - char
189 - timestamp
231 - nvarchar
239 - nchar
241 - xml
231 - sysname

Lista todas as tabelas do usuário
SELECT name, object_id, create_date, modify_date
 FROM sys.all_objects
 WHERE type = 'U'

Lista colunas de uma tabela
SELECT Co.name, Co.column_id, Ty.name, Co.system_type_id, Co.max_length, Co.precision, Co.is_nullable, Co.is_identity
 FROM sys.all_columns Co

 LEFT JOIN sys.types Ty ON Co.system_type_id = Ty.system_type_id

 WHERE Co.object_id IN (SELECT object_id FROM sys.all_objects WHERE type = 'U' AND name = 'sipPOS')


Lista as Foreign Keys para uma tabela
SELECT FK.object_id, FK.name, LFK.parent_column_id, FKC.name, FKC.system_type_id, FKC.is_nullable
 , RK.object_id, RK.name, LFK.referenced_column_id, FKR.name, FKR.system_type_id, FKR.is_nullable

 FROM sys.foreign_key_columns	LFK

 LEFT JOIN sys.all_objects FK ON LFK.parent_object_id = FK.object_id
 LEFT JOIN sys.all_columns FKC ON LFK.parent_object_id = FKC.object_id AND LFK.parent_column_id = FKC.column_id

 LEFT JOIN sys.all_objects RK ON LFK.referenced_object_id = RK.object_id
 LEFT JOIN sys.all_columns FKR ON LFK.referenced_object_id = FKR.object_id AND LFK.referenced_column_id = FKR.column_id

 WHERE LFK.parent_object_id IN (SELECT object_id FROM sys.all_objects WHERE type = 'U' AND name = 'sipPOS')
 ORDER BY FK.name

*/
?>
