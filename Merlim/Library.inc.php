<?php
// ================================================================
// /Library.inc.php
// ----------------------------------------------------------------
// Nome     : Biblioteca genérica para POS e Web
// Home     : ruben.zevallos.com.br
// Criacao  : 10/13/2008 2:21:26 AM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 1.0.0.1
// Local    : Fortaleza - CE, Brasília - DF
// Copyright: 2007-2010 by Ruben Zevallos(r) Jr.
// ----------------------------------------------------------------

// ini_set  ( string $varname  , string $newvalue  )

$sstrResultDate = date("d/m/Y H:i:s", time());

// Globais da conexão do banco de dados
$sobjOracleConn = null;
$sobjMSSQLConn = null;
$sobjFireBirdConn = null;

// Globais do FormatNumber
$sFormatNumberBRSeparator = ",";
$sFormatNumberBRDigit = ".";

// Globais do Sistema de Log
$sobjLogFile = null;
$sstrLogFileName = null;

// Variável do Piece
$sstrPiece = "";
$sintPieceMaxSize = 0;

$sstrGET  = encode($_GET);
$sstrPOST = encode($_POST);

$sstrIntegrado = 1; // Valor temporário para o sistema

$sstrISOSE = "";

$sstrMapaSE = "";
$sintMapaSE = 0;

$sarrLatin1 = array("&amp;", "&#41;", "&#40;", "&lt;", "&gt;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&acirc;", "&ecirc;", "&icirc;", "&ocirc;", "&ucirc;", "&agrave;", "&egrave;", "&igrave;", "&ograve;", "&ugrave;", "&auml;", "&euml;", "&iuml;", "&ouml;", "&uuml;", "&atilde;", "&otilde;", "&ccedil;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Acirc;", "&Ecirc;", "&Icirc;", "&Ocirc;", "&Ucirc;", "&Agrave;", "&Egrave;", "&Igrave;", "&Ograve;", "&Ugrave;", "&Auml;", "&Euml;", "&Iuml;", "&Ouml;", "&Uuml;", "&Atilde;", "&Otilde;", "&Ccedil;", "&copy;", "&reg;");
$sarrAccent = array("&", ")", "(", "<", ">", "á", "é", "í", "ó", "ú", "â", "ê", "î", "ô", "û", "à", "è", "ì", "ò", "ù", "ä", "ë", "ï", "ö", "ü", "ã", "õ", "ç", "Á", "É", "Í", "Ó", "Ú", "Â", "Ê", "Î", "Ô", "Û", "À", "È", "Ì", "Ò", "Ù", "Ä", "Ë", "Ï", "Ö", "Ü", "Ã", "Õ", "Ç", "©", "®");

// =========================================================================
// Conecta com o SQL ativo - Oracle
// -------------------------------------------------------------------------
function ConnectOracle() {
  global $sobjOracleConn;

	$sobjOracleConn = oci_connect(OracleUser, OraclePassword, OracleServer);

	if (!$sobjOracleConn) {
	    $e = oci_error();

			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

	    // trigger_error("<br />CONNECT: O Oracle ".OracleServer." parece estar desativado.<br />[".htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR)."]");
	}

	define('objOracleConn', $sobjOracleConn);

}

// =========================================================================
// Libera os recursos usados pela conexão
// -------------------------------------------------------------------------
function DisconnectOracle() {

  if (objOracleConn) {
    oci_close(objOracleConn);
  }
}

// =========================================================================
// Conecta com o SQL ativo - MSSQL
// -------------------------------------------------------------------------
function ConnectMSSQL() {
  global $sobjMSSQLConn;

  // $sobjMSSQLConn = @mysql_pconnect(ConnectionStringHost, ConnectionStringUser, ConnectionStringPassword) or die("O MySQL parece estar desativado.");

  // if (ConnectionStringDatabase!="" and !@mysql_select_db(ConnectionStringDatabase)) die("O MySQL está indisponível.");

  // Conexão com o MSSQL
  // $sobjMSSQLConn = sqlsrv_connect(SQLHost,array("Database"=>"credito_istcartoes_com_br"));

  $sobjMSSQLConn = @mssql_connect(SQLHost, SQLUser, SQLPassword) or die("<br />CONNECT: O MSSQL ".SQLHost." parece estar desativado.<br />[".mssql_get_last_message()."]");

  @mssql_select_db(SQLDatabase, $sobjMSSQLConn) or die("<br />SELECT: O database ".SQLDatabase." do MSSQL ".SQLHost." está indisponível.<br />[".mssql_get_last_message()."]");

	define('objMSSQLConn', $sobjMSSQLConn);

}

// =========================================================================
// Libera os recursos usados pela conexão
// -------------------------------------------------------------------------
function DisconnectMSSQL() {

  if (objMSSQLConn) {
    mssql_close(objMSSQLConn);
  }
}

// =========================================================================
// Conecta com o SQL ativo - MSSQL
// -------------------------------------------------------------------------
function ConnectFireBird() {
  global $sobjFireBirdConn;

  // 'localhost:c:/firebird/test_db/test.fdb'

  // Conexão com o FireBird
  $sobjFireBirdConn = @ibase_connect(FBDatabase, FBUser, FBPassword) or die("<br />ConnectFireBird: O FireBird ".FBDatabase." parece estar desativado.<br />[".ibase_errmsg()."]");

}

// =========================================================================
// Libera os recursos usados pela conexão
// -------------------------------------------------------------------------
function DisconnectFireBird() {
  global $sobjFireBirdConn;

  if ($sobjFireBirdConn) {
    //
  }
}

// =========================================================================
// Retorna somente as letras de A-Z a-z - e 0-9
// -------------------------------------------------------------------------
function NormalizeString($strString) {

  if ($strString > "") {
    for ($i = 1; $i <= strlen($strString); $i++) {
      $strOne = substr($strString,$i-1,1);

      if (($strOne >= "a" && $strOne <= "z") || ($strOne >= "A" && $strOne <= "Z") || ($strOne >= "0" && $strOne <= "9") || $strOne == "-" || $strOne == " ") {
        $NormalizeString = $NormalizeString.$strOne;
      }
    }
  }

  return $NormalizeString;
}
// =========================================================================
// Final da NormalizeString
// -------------------------------------------------------------------------

// =========================================================================
// Retorna somente os número de 0-9
// -------------------------------------------------------------------------
function NormalizeNumber($strString) {

  if ($strString) {
    for ($i = 1; $i <= strlen($strString); $i++) {
      $strOne = substr($strString,$i-1,1);

      if ($strOne >= "0" && $strOne <= "9") $NormalizeNumber .= $strOne;
    }
  }

  return $NormalizeNumber;
}
// =========================================================================
// Final da NormalizeString
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// Formata o número no formato Brasileiro
// =========================================================================
function FormatNumberBRSetSource($strSeparator = ",", $strDigit = ".") {
  global $sFormatNumberBRSeparator, $sFormatNumberBRDigit;
	
  $sFormatNumberBRSeparator = $strSeparator;
  $sFormatNumberBRDigit = $strDigit;
}
// =========================================================================
// Final da NormalizeString
// -------------------------------------------------------------------------

/**
 * Formata o número no formato Brasileiro
 * @param Value float <p>
 * O valor numérico para formatar
 * </p>
 * @param Valor int <p>
 * Quantidade de casas decimais. Padrão 2.
 * </p>
 * @param Casas int <p>
 * Número de casas. Padrão 2
 * </p>
 * @return string valor formatado.
 * Retorna o valor no formato brasileiro.
 */
function FormatNumberBR($dblValue, $intCasas = 2, $blnNumero = 0, $strComma = ".") {
  global $sFormatNumberBRSeparator, $sFormatNumberBRDigit;
	  
  if ($dblValue) {
  	if (is_numeric($dblValue)) {
  		$dblValue = round(strval($dblValue), $intCasas);
  		
      $dblValue = strval(number_format($dblValue, 2));
      
    } else {
    	
      $dblValue = str_replace($sFormatNumberBRDigit, ".", $dblValue);
    	$dblValue = round(strval($dblValue), $intCasas);
    	
    }

    $dblValue = ltrim($dblValue, "0");

    // echo "<br />dblValue=".$dblValue;
    
    // "10,00" = "1000" / 100 = 10.00 
    
    if (strpos($dblValue, $sFormatNumberBRDigit)) {
      $dblValue = str_replace($sFormatNumberBRDigit, "", $dblValue);
      $dblValue = str_replace($sFormatNumberBRSeparator, "", $dblValue);
      
      $dblValue = (strval($dblValue) / 100);
      
    }
  }

  return number_format($dblValue, $intCasas, ",", ".");
}
// =========================================================================
// Final da FormatNumberBR
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
// Formata e reduz o número dentro do padrão KB, MB e GB
// =========================================================================
function FormatSize($dblValue) {

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="KB";
  }

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="MB";
  }

  if ($dblValue>1000) {
    $dblValue = $dblValue/1000;

    $strSulfix="GB";
  }

  $FormatSize = str_replace(";", ".", str_replace(".", ",", str_replace(",", ";", number_format($dblValue, 0)))).$strSulfix;

  return $FormatSize;
}
// =========================================================================
// Final da FormatSize
// -------------------------------------------------------------------------

// ----------------------------------------------------------------
// Retira todos os acentos de uma string
// ================================================================
function NormalizeAccent($strString) {

  $arrNormal = explode(",","a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,o,c,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,I,Ç,a");
  $arrAccent = explode(",","á,é,í,ó,ú,â,ê,î,ô,û,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ã,õ,ç,Á,É,Í,Ó,Ú,Â,Ê,Î,Ô,Û,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Ã,Õ,Ç,ª");

  if ($strString > "") {
    $x=0;

    $NormalizeAccent = $strString;

    $NormalizeAccent = str_replace(chr(170),"a",$NormalizeAccent);

    foreach ($arrAccent as $i) {
      $NormalizeAccent = str_replace($arrAccent[$x],$arrNormal[$x],$NormalizeAccent);

      $x = $x+1;

    }
  }

  return $NormalizeAccent;
}

// ================================================================
// Prepara o Log, caso tenha sido optado por ele
// ----------------------------------------------------------------
function CheckDir($strDir) {
  if (!file_exists($strDir)) {
    mkdir($strDir, 0777);

    // print "<br />".$strDir." <-- Criado";
  }

  return $strDir;

}
// ================================================================
// CheckDir
// ----------------------------------------------------------------

// ================================================================
//
// ----------------------------------------------------------------
function encode($var){
    if (is_array($var)) {
      $code = '';

      foreach ($var as $key => $value) {
        $code .= $key."=".encode($value).'&amp;';
      }

      $code = chop($code, '&amp;');

      return $code;
    } else {
      if (is_string($var)) {
        return $var;

      } elseif (is_bool($code)) {
        return ($code ? 'TRUE' : 'FALSE');

      } else {
        return 'NULL';
      }
    }
}

// ================================================================
//
// ----------------------------------------------------------------
function decode($string){
    return eval('return '.$string.';');
}

// =========================================================================
//
// -------------------------------------------------------------------------
function ReadXMLHTTP($strURL, $strFileName, $blnCache) {

  // print "$strFileName <-- <a href=\"$strURL\" target=\"_blank\">URL</a>";

  $ReadXMLHTTP = null;

  $strResult = "[Site]";

  if (file_exists($strFileName) && $blnCache) {
    if (filesize($strFileName) > 1800) {
      $objFile = fopen($strFileName, "r");

      $ReadXMLHTTP = fread($objFile, filesize($strFileName));;

      fclose($objFile);

     $strResult = "[Cache]";

    }
  }

  if (empty($ReadXMLHTTP) || !$blnCache) {
    if (($objFile = fopen($strURL, "rb"))) {
      $ReadXMLHTTP = '';

      while (!feof($objFile)) {
        $ReadXMLHTTP .= fread($objFile, 8192);

      }

      fclose($objFile);

      // print " [Site]";

      $objFile = fopen($strFileName, "w");

      fputs($objFile, $ReadXMLHTTP);

      fclose($objFile);
    } else {
      // print "<-- Could not open the address!<br />\r\n";
    }
  }

  return($ReadXMLHTTP);

}

// =========================================================================
// Lê os arquivos locais, testa se aconteceu algum erro.
// Faz cache durante o processamento
// -------------------------------------------------------------------------
function ZReadFile($strFileName) {

  $strResult = "";

  if (file_exists($strFileName)) {
    $objFile = fopen($strFileName, "r");

    $strResult = fread($objFile, filesize($strFileName));

    fclose($objFile);
  }

  return $strResult;

}

// =========================================================================
// Formata o CPF
// -------------------------------------------------------------------------
function FormataCPF($strCPF) {
  if ($strCPF) {
    $strCPF = trim(ltrim($strCPF, "-"));
    $strCPF = trim(ltrim($strCPF, "0"));

    $strCPF = str_pad($strCPF, 11, "0", STR_PAD_LEFT);

    return substr($strCPF, 0, 3).".".substr($strCPF, 3, 3).".".substr($strCPF, 6, 3)."-".substr($strCPF, 9, 2);
  }
}

// =========================================================================
// Formata o CPF
// -------------------------------------------------------------------------
function FormataCNPJ($strCNPJ) {
  if ($strCNPJ) {
    $strCNPJ = ltrim($strCNPJ, "-");
    $strCNPJ = ltrim($strCNPJ, "0");

    $strCNPJ = str_pad($strCNPJ, 14, "0", STR_PAD_LEFT);

    return substr($strCNPJ, 0, 2).".".substr($strCNPJ, 2, 3).".".substr($strCNPJ, 5, 3)."/".substr($strCNPJ, 8, 4)."-".substr($strCNPJ, 12, 2);
  }
}

// =========================================================================
// Formata o CPF
// -------------------------------------------------------------------------
function FormataCPFCNPJ($strCPFCNPJ) {

  if ($strCPFCNPJ) {
    if (strlen($strCPFCNPJ) == 11) {
      return substr($strCPFCNPJ, 0, 3).".".substr($strCPFCNPJ, 3, 3).".".substr($strCPFCNPJ, 6, 3)."-".substr($strCPFCNPJ, 9, 2);
    } else {
      return substr($strCPFCNPJ, 0, 2).".".substr($strCPFCNPJ, 2, 3).".".substr($strCPFCNPJ, 5, 3)."/".substr($strCPFCNPJ, 8, 4)."-".substr($strCPFCNPJ, 12, 2);
    }
  }
}

// =========================================================================
// Formata a Data de 31122008 para 31/12/2008
//                de 311208 para 31/12/08
//                de 3112 para 31/12
// -------------------------------------------------------------------------
function FormataDataBR($strDate) {

  if ($strDate) {
    $strDate = ltrim($strDate, "-");

    if (strlen($strDate) == 8) return substr($strDate, 0, 2)."/".substr($strDate, 2, 2)."/".substr($strDate, 4, 4);
    if (strlen($strDate) == 6) return substr($strDate, 0, 2)."/".substr($strDate, 2, 2)."/".substr($strDate, 4, 2);
    if (strlen($strDate) == 4) return substr($strDate, 0, 2)."/".substr($strDate, 2, 2);

    return $strDate;
  }
}

// =========================================================================
// Formata a Data invertida de 20081231 para 31/12/2008
//                             de 081231 para 31/12/08
// -------------------------------------------------------------------------
function FormataDataBRI($strDate) {

  if ($strDate) {
    $strDate = ltrim(trim($strDate), "-");

    if (strlen($strDate) == 8) {
      $strDate = substr($strDate, 6, 2)."/".substr($strDate, 4, 2)."/".substr($strDate, 0, 4);
    } else {
      if (strlen($strDate) == 6) $strDate = substr($strDate, 4, 2)."/".substr($strDate, 2, 2)."/".substr($strDate, 0, 2);
    }

    return $strDate;
  }
}

// =========================================================================
// Formata a Hora de 1200 para 12:00
//                de 121032 para 12:10:32
// -------------------------------------------------------------------------
function FormataHoraBR($strHour) {

  if ($strHour) {
    $strHour = ltrim($strHour, "-");

    if (strlen($strHour) == 6) return substr($strHour, 0, 2).":".substr($strHour, 2, 2).":".substr($strHour, 4, 2);
    if (strlen($strHour) == 4) return substr($strHour, 0, 2).":".substr($strHour, 2, 2);

    return $strHour;
  }
}

// =========================================================================
// Formata Telefones de:
//  7 - 332-1293
//  8 - 3332-1293
//  9 - 61 332-1293
// 10 - 98 3332-1293
// -------------------------------------------------------------------------
function FormataTelefoneBR($strTelefone) {

  if ($strTelefone) {
    $strTelefone  = ltrim($strTelefone, "-");
    $strTelefone  = trim(ltrim($strTelefone, "0"));

    if (strlen($strTelefone) < 7) $strTelefone = str_pad($strTelefone, 7, "0", STR_PAD_LEFT);

    if (strlen($strTelefone) == 7) return substr($strTelefone, 0, 3)."-".substr($strTelefone, 3, 4);
    if (strlen($strTelefone) == 8) return substr($strTelefone, 0, 4)."-".substr($strTelefone, 4, 4);
    if (strlen($strTelefone) == 9) return substr($strTelefone, 0, 2)." ".substr($strTelefone, 2, 3)."-".substr($strTelefone, 5, 4);
    if (strlen($strTelefone) == 10) return substr($strTelefone, 0, 2)." ".substr($strTelefone, 2, 4)."-".substr($strTelefone, 6, 4);

    return $strTelefone;
  }
}

// =========================================================================
// Formata Telefones de:
//  7 - 332-1293
//  8 - 3332-1293
//  9 - 61 332-1293
// 10 - 98 3332-1293
// -------------------------------------------------------------------------
function FormataCEP($strCEP) {

  if ($strCEP) {
    $strCEP = trim(ltrim($strCEP, "0"));

    if (strlen($strCEP) == 4) $strCEP = str_pad($strCEP, 5, "0", STR_PAD_LEFT);
    if (strlen($strCEP) == 7) $strCEP = str_pad($strCEP, 8, "0", STR_PAD_LEFT);

    if (strlen($strCEP) == 5) return substr($strCEP, 0, 5);
    if (strlen($strCEP) == 8) return substr($strCEP, 0, 5)."-".substr($strCEP, 5, 3);

    return $strCEP;

  }
}

// =========================================================================
// Calcula o digito padrao modulo 11 dos bancos
// -------------------------------------------------------------------------
function DVModulo11($strValor) {

  $intSoma = 0;
  $bytMultiplicador = 2;

  for ($i = strlen($strValor) -1; $i >= 0; $i--) {
    $intSoma += substr($strValor, $i, 1) * $bytMultiplicador;

    if ($bytMultiplicador++ == 9) $bytMultiplicador = 2;

  }

  $intDigito = $intSoma % 11;

  if ($intDigito == 10) {
    $intDigito = 1;

  } else if ($intDigito == 1 || $intDigito == 0) {
    $intDigito = 0;

  } else {
    $intDigito = 11 - $intDigito;
  }

  return $intDigito;

}


// =========================================================================
// Calculo do Digito Verificador Modulo 10 padrao dos Bancos
// -------------------------------------------------------------------------
function DVModulo10($strValor) {

  $lngSoma = 0;
  $bytMultiplicador = 2;

  for ($i = strlen($strValor) -1; $i >= 0; $i--) {
    $intProduto = substr($strValor, $i, 1) * $bytMultiplicador;

    if (strlen($intProduto) > 1) $intProduto = substr($intProduto, 0, 1) + substr($intProduto, 1, 1);

    $lngSoma += $intProduto;

    if ($bytMultiplicador-- == 1) $bytMultiplicador = 2;

  }

  $intDigito = 10 - ($lngSoma % 10);

  if ($intDigito == 10) $intDigito = 0;

  return $intDigito;

}

// ================================================================
// Converte uma string para o formato de sentença: Oi Paipai Noel Da Silva
// ----------------------------------------------------------------
function Sentence($strPhrase) {
  $strNotWords = " do da de ou sem dos das para que pro por um uma uns umas e a ao à é no na nos nas as às sobre em outras aos com s/a p/ s/ ";

	$strUpperWords = " i ii iii iv v vi vii viii ix x xi xii xii xx xxi cpf cnpj cpf/cnpj qs qe qn qnj qd cj sqn sqs smpw shcgn qnp qng qnh qnl shin shis scrln qnn qnm cnb aos ssp sep pj pf ";

  $Sentence = "";

  $strPhrase = trim($strPhrase);

  if (strlen($strPhrase)) {
    $arrWork = explode(" ", strtolower($strPhrase));

    foreach ($arrWork as $strKey => $strValue) {
      if (strlen($strValue)) {
      	if (strpos($strUpperWords, " ".$strValue." ")) {
      		$strValue = strtoupper($strValue);

      	} elseif (!strpos($strNotWords, " ".$strValue." ")) {
      		$strValue = strtoupper(substr($strValue, 0, 1)).substr($strValue, 1);
      	}

    	}

      $Sentence .= " ".$strValue;
    }
  }

  return ltrim($Sentence, " ");

}
// ----------------------------------------------------------------
// Final da Function Sentence
// ================================================================

// ================================================================
//
// ----------------------------------------------------------------

// istCrypt( istSerial(), istIMEI(), Sequencial);

// @param Text Texto a ser criptografado.
// @param Key1 Chave textual.
// @param Key2 Chave numérica.

function istCrypt($strText, $strKey1, $intKey2) {
  $intLen = strlen($strText);
  $intLenk = strlen($strKey1);

  $strCrypt = "";

  for ($intIdx = 0, $intIdk = $intKey2; $intIdx < $intLen; $intIdx++, $intIdk++, $strPStr += 2 ) {
    $strCrypt .= sprintf("%02x", (ord(substr($strText, $intIdx, 1)) ^ ord(substr($strKey1, ($intIdk % $intLenk), 1))));
  }

  return $strCrypt;
}

// ----------------------------------------------------------------
//
// ================================================================
function latin12Conv($intAction, $strString) {
  $arrLatin1  = explode(",", "#41,#40,amp,quot,lt,gt,#8216,#8217,#8220,#8221,#8211,#8212");
  $arrAccent  = explode(",", "),(,&,\",<,>,".chr(145).",".chr(146).",".chr(147).",".chr(148).",".chr(150).",".chr(151));
  $$arrNumber = explode(",", "41,40,38,34,60,62,145,146,147,148,150,151");

  // ALT-0145  &#8216;  ‘  Left Single Quotation Mark
  // ALT-0146  &#8217;  ’  Right Single Quotation Mark
  // ALT-0147  &#8220;  “  Left Double Quotation Mark
  // ALT-0148  &#8221;  ”  Right Double Quotation Mark
  // ALT-0150  &#8211;  –  En Dash
  // ALT-0151  &#8212;  —  Em Dash

  $latin12Conv = "";

  if (strlen($strString) > 0) {
    $latin12Conv = $strString;

    foreach ($arrAccent as $x => $valor) {
      switch ($intAction) {
        // Converte de Latin1 para norma
        case 10:
          $latin12Conv = str_replace("&".$arrLatin1[$x].";", $arrAccent[$x], $latin12Conv);
          break;

        // Converte de normal para Latin1
        case 11:
          $latin12Conv = str_replace($arrAccent[$x], "&".$arrLatin1[$x].";", $latin12Conv);
          break;

        // Converte de Latin1 textual para Latin 1 numerado
        case 20:
          $latin12Conv = str_replace("&".$arrLatin1[$x].";", "&#".$arrNumber[$x].";", $latin12Conv);
          break;

        // Converte de Latin1 numerado para Latin 1 textual
        case 21:
          $latin12Conv = str_replace("&#".$arrNumber[$x].";", "&".$arrLatin1[$x].";", $latin12Conv);
          break;

        // Converte de Latin1 numerado para normal
        case 30:
          $latin12Conv = str_replace("&#".$arrNumber[$x].";", $arrAccent[$x], $latin12Conv);
          break;

        // Converte de normal para Latin 1 numerado
        case 31:
          $latin12Conv = str_replace($arrAccent[$x], "&#".$arrNumber[$x].";", $latin12Conv);
          break;

        case 200:
          $latin12Conv = str_replace("&".$arrLatin1[$x].";", "{*#".$arrNumber[$x]."*}", $latin12Conv);
          break;

        case 210:
          $latin12Conv = str_replace("{*#".$arrNumber[$x]."*}", "&".$arrLatin1[$x].";", $latin12Conv);
          break;

      }
    }
  }

  return $latin12Conv;

}
// ================================================================
// latin12Conv
// ----------------------------------------------------------------

// ----------------------------------------------------------------
//
// ================================================================
function latin12String($strString) {
  global $sarrLatin1, $sarrAccent;

  $strString = str_replace("&quot;", "\"", $strString);

  $strStringNew = str_replace($sarrLatin1, $sarrAccent, $strString);

  return $strStringNew;

}
// ================================================================
// Latin12String
// ----------------------------------------------------------------

// ----------------------------------------------------------------
//
// ================================================================
function accentString2Latin1($strString, $blnQuot = 0) {
  global $sarrLatin1, $sarrAccent;

  if ($blnQuot) $strString = str_replace("\"", "&quot;", $strString);

  $strStringNew = str_replace($sarrAccent, $sarrLatin1, $strString);

  return $strStringNew;

}
// ================================================================
// accentString2Latin1
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Converte binarios para mapa de bits do GeCel da SE - Software Express
// ================================================================
function bin2decSE($strMapa) {
  $bitMapa = "";

  $intSize = strlen($strMapa);

  for ($i = 0; $i < $intSize; $i += 4) {
    $intNibble = bindec(substr($strMapa, $i, 4));

    $bitMapa .= strval($intNibble);

  }

  return $bitMapa;

}
// ================================================================
// bin2decSE
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Inicializa as variáveis do mapa de bits
// ================================================================
function beginMapaBitSE($intMapa = 16, $strMapa = "") {
  global $sstrMapaSE, $sintMapaSE;

  $intLen = strlen($strMapa);

  $intMax = $intMapa * 8;

  if ($intMapa > $intLen) $strMapa = str_pad($strMapa, $intMax, "0", STR_PAD_RIGHT);

  $sstrMapaSE = $strMapa;
  $sintMapaSE = $intMapa;
}
// ================================================================
// beginMapaBitSE
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Inicializa as variáveis do mapa de bits
// ================================================================
function getMapaBitSE() {
  global $sstrMapaSE;

  return $sstrMapaSE;
}
// ================================================================
// getMapaBitSE
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Mapa de Bits - Set Bit
// ================================================================
function setMapaBitSE($intBit) {
  global $sstrMapaSE;

  $sstrMapaSE = substr($sstrMapaSE, 0, $intBit - 1)."1".substr($sstrMapaSE, ($intBit));

  return $sstrMapaSE;

}
// ================================================================
// setMapaBitSE
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Inicializa gerador de pacote ISO da Software Express
// ================================================================
function beginPacoteISO($intMapa) {
  global $sstrISOSE;

  beginMapaBitSE($intMapa);

  $sstrISOSE = "";
}
// ================================================================
// beginPacoteISO
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Inicializa as variáveis do mapa de bits
// ================================================================
function getPacoteISO() {
  global $sstrISOSE;

  return $sstrISOSE;
}
// ================================================================
// getMapaBitSE
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Adiciona ítem no pacote ISO
// ================================================================
function addPacoteISO($intBit = 0, $strConteudo) {
  global $sstrISOSE;

  if ($intBit) setMapaBitSE($intBit);

  $sstrISOSE .= $strConteudo;

  return $sstrISOSE;
}
// ================================================================
// addPacoteISO
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Adiciona ítem no pacote ISO e preenche com zeros a Direita
// ================================================================
function addPacoteISOLeft($intBit = 0, $intLen, $strConteudo) {
  global $sstrISOSE;

  if ($intBit) setMapaBitSE($intBit);

  $sstrISOSE .= str_pad($strConteudo, $intLen, "0", STR_PAD_LEFT);

  return $sstrISOSE;
}
// ================================================================
// addPacoteISORight
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Adiciona ítem no pacote ISO e preenche com zeros a Direita
// ================================================================
function addPacoteISORight($intBit = 0, $intLen, $strConteudo) {
  global $sstrISOSE;

  if ($intBit) setMapaBitSE($intBit);

  $sstrISOSE .= str_pad($strConteudo, $intLen, "0", STR_PAD_RIGHT);

  return $sstrISOSE;
}
// ================================================================
// addPacoteISORight
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Apresenta as Tags HTML e os &...; na tela
// ================================================================
function showHTML($strString = "") {
  echo "<br /><asdasd>zxzxz";
  echo "<br />&ccedil;&atilde;o &Ccedil;&Atilde;o ";

  if (strlen($strString)) {
    echo str_replace("<", "&lt;", str_replace(">", "&gt;", str_replace("&", "&amp;", $strString)));
  }
}
// ================================================================
// addPacoteISORight
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Apresenta as Tags HTML e os &...; na tela
// ================================================================
function toNumber($varValue) {
	$varValue = " ".$varValue;
	
	if (strpos($varValue, ",")) {
		$varValue = str_replace(",", ".", $varValue);
	}
	
	return strval($varValue);
}

// ----------------------------------------------------------------
// Retorna true se está no formato UTF-8
// ================================================================
function isUTF8($varValue) {
  return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+%xs', $varValue);
}
?>
