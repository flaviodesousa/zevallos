<?php
// ================================================================x
// /generateInserts.php
// ----------------------------------------------------------------
// Nome     : Processa as requisições dos formulários em AJAX
// Home     : http://ruben.zevallos.com.br
// Criacao  : 11/14/2008 8:35:02 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.10
// Local    : Fortaleza - CE, Brasília - DF, Belém - PA, São Luís - MA
// Copyright: 97-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");

$sstrCurrentScript = "generateInserts.php";

ConnectMSSQL();

main();

DisconnectMSSQL();

if ($objSQL) mssql_free_result($objSQL);

// ================================================================
//
// ----------------------------------------------------------------
function Main() {
  global $sobjMSSQLConn;

  if (parTarget) {
    $sql = "SELECT *".
           " FROM ".parTarget;

    $objSQL = @mssql_query($sql, $sobjMSSQLConn) or die("<br />Main: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");

    $intCols = mssql_num_fields ($objSQL) or die("Num Fields Failed");
    $intRows = mssql_num_rows ($objSQL);

    $blnInsert = true;

    $strInsert = "";

    echo "SET IDENTITY_INSERT ".parTarget." ON<br />";

    while ($objRS = mssql_fetch_array($objSQL, MSSQL_BOTH)) {
      $strResult = "";

      for ($i = 0; $i < $intCols; $i++) {
        if ($blnInsert) $strInsert .= ",".mssql_field_name($objSQL, $i);

        $strValue = trim($objRS[$i]);

        if (strlen($strValue) > 0) {
          switch (mssql_field_type($objSQL, $i)) {
            case "char":
            case "varchar":
            case "nvarchar":
            case "text":
            case "ntext":
              $strResult .= ",'".str_replace("\r\n", "'+CHAR(13)+CHAR(10)+'", $strValue)."'";
              break;

            case "datetime":
            case "smalldatetime":
              $strResult .= ",'".date("Y/m/d H:i:s", strtotime($strValue))."'";
              break;

            default:
              $strResult .= ",".$objRS[$i];
          }
        } else {
          $strResult .= ",NULL";
        }

        // $strResult .= "(".mssql_field_length($objSQL, $i).")";
      }

      if (substr($strResult, 0, 1) == ",") $strResult = substr($strResult, 1);

      if ($blnInsert) {
        $blnInsert = false;

        if (substr($strInsert, 0, 1) == ",") $strInsert = substr($strInsert, 1);

        $strInsert = "INSERT ".parTarget." ($strInsert)";
      }

      echo "$strInsert VALUES ($strResult);<br />";
    }

    echo "SET IDENTITY_INSERT ".parTarget." OFF";
  }
}

?>