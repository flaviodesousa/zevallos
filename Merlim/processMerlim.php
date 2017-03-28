<?php
// ================================================================
// /processMerlim.php
// ----------------------------------------------------------------
// Nome     : Processador do Merlim 2.0
// Criacao  : 23/08/2010 14:32:0 PM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com.br>
// Versao   : 2.0.10
// Local    : Fortaleza - CE
// Copyright: 2007-2010 by Ruben Zevallos Jr.
// ----------------------------------------------------------------

require_once("Config.inc.php");
require_once("Library.inc.php");
require_once("LibraryWeb.inc.php");

$sstrCurrentScript = "processMerlim.php";

ConnectMSSQL();

main();

DisconnectMSSQL();

// ================================================================
//
// ----------------------------------------------------------------
function main() {
	switch(parOption) {
		case 10:
			processURL();
			break;
			
		default:
			processMerlim();
	}
	
}

// ================================================================
//
// ----------------------------------------------------------------
function processURL() {
	?>
	<form method="post">
		<input type="hidden" name="o" value="10" />
		<b>URL:</b><input name="t" size="50" value="<? echo parTarget ?>" />
		<br /><input type="submit" value="Processa" />
	</form>
	<?
	
	if (parTarget) {
	  $strResult = readHTTP(parTarget);
	  
	  $intResult = strlen($strResult);
	  
	  $strResult = tidyApply($strResult);

		$strResult = preg_replace('%<(no)?script.*?</(no)?script>%i', "", $strResult);
		
		$strResult = preg_replace('%<(p|span)[^>]+?>%i', '<$1>', $strResult);

		// Troca vários espaços por um único
		$strResult = preg_replace('%\s+%i', " ", $strResult);

		// Apaga os espaço entre as tags
		$strResult = preg_replace('%(>)\s+?(<)%', "$1$2", $strResult);

		// Apaga os espaço entre as tags
		$strResult = preg_replace('%</p><p>%', "<br /><br />", $strResult);
		
		// Tira os <br /> após um <p>
		$regexPattern = '%<p><br />%';
		$regexReplace = "<p>";
	
		while (preg_match($regexPattern, $strResult)) {
			$strResult = preg_replace($regexPattern, $regexReplace, $strResult);
		}
		
		// Apagar as tags vazias
		$regexPattern = '%<(\w+)[^>]*?>\s*?</\1>%';
		$regexReplace = "";
	
		while (preg_match($regexPattern, $strResult)) {
			$strResult = preg_replace($regexPattern, $regexReplace, $strResult);
		}

		// $strResult = tidyApply($strResult);
		
		$strResult = latin12String($strResult);
		
		// Troca os &nbsp; por espaço
		$strResult = preg_replace("%&nbsp;%i", " ", $strResult);
		
	  echo parTarget." (".$intResult." | ".strlen($strResult).")<hr />";
	
		echo "<textarea cols=\"100\" rows=\"20\">".str_replace(">", "&gt;", str_replace("<", "&lt;", $strResult))."</textarea><hr />";
		
	}	
}

// ================================================================
//
// ----------------------------------------------------------------
function processMerlim() {

	// ob_end_flush();
	
	$sql= "SELECT sitCodigo
	, sitSigla
	, sitNome
	, sitURL
	, sitIdAlternativo
	FROM merSite
	WHERE sitAtivo = 1
	".((parTarget) ? " AND sitIdAlternativo = ".parTarget : "")."
	ORDER BY sitNome";

  if (parDebug) echo "<br /><pre>$sql</pre>";
  
	$objSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>".ScriptName." - processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
    
  while($objRSRow = mssql_fetch_array($objSQL, MSSQL_ASSOC) ) {
  	$sitCodigo = $objRSRow["sitCodigo"];
  	
		echo "<hr /><b>sitCodigo</b>=".$objRSRow["sitCodigo"];
		echo "<br /><b>sitSigla</b>=".$objRSRow["sitSigla"];
		echo "<br /><b>sitNome</b>=".$objRSRow["sitNome"];
		echo "<br /><b>sitURL</b>=".$objRSRow["sitURL"];
		echo "<br /><b>sitIdAlternativo</b>=".$objRSRow["sitIdAlternativo"];
		
		$sql= "SELECT sifCodigo
		, sifSigla
		, sifNome
		, sifURL
		, sifIdAlternativo
		, sifcURL
		, sifTidyHTML
		, sifRegEx
		FROM merSiteFonte
		WHERE sifAtivo = 1 AND sifSite = $sitCodigo
		ORDER BY sifInicio, sifIntervalo";
	
	  if (parDebug) echo "<br /><pre>$sql</pre>";
	  
		$objFonteSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
	    
	  while($objRSFonteRow = mssql_fetch_array($objFonteSQL, MSSQL_ASSOC) ) {
	  	$sifCodigo = $objRSFonteRow["sifCodigo"];
	  	$sifURL    = $objRSFonteRow["sifURL"];
	  	$sifRegEx  = $objRSFonteRow["sifRegEx"];

			$sifcURL      = $objRSFonteRow["sifcURL"];
			$sifTidyHTML  = $objRSFonteRow["sifTidyHTML"];
			
			echo "<hr /><b>sifCodigo</b>=".$objRSFonteRow["sifCodigo"];
			echo "<br /><b>sifSigla</b>=".$objRSFonteRow["sifSigla"];
			echo "<br /><b>sifNome</b>=".$objRSFonteRow["sifNome"];
			echo "<br /><b>sifURL</b>=".$objRSFonteRow["sifURL"];
			echo "<br /><b>sifIdAlternativo</b>=".$objRSFonteRow["sifIdAlternativo"];
			echo "<br /><b>sifcURL</b>=".$objRSFonteRow["sifcURL"];
			echo "<br /><b>sifTidyHTML</b>=".$objRSFonteRow["sifTidyHTML"];
			echo "<br /><b>sifRegEx</b>=".$objRSFonteRow["sifRegEx"];

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
			WHERE sfpAtivo = 1 AND sfpFonte = $sifCodigo
			ORDER BY sfpURL";
		
		  if (parDebug) echo "<br /><pre>$sql</pre>";
		  
			$objPaginaSQL = @mssql_query($sql, objMSSQLConn) or die("<br /><b>processMerlim()</b>: A query do MSSQL ".SQLHost." não pode ser executada.<br />[".mssql_get_last_message()."]<br />$sql");
		    
		  while($objRSPaginaRow = mssql_fetch_array($objPaginaSQL, MSSQL_ASSOC) ) {
		  	$sfpURL = $objRSPaginaRow["sfpURL"];
		  	
	  		$sfpTituloRegEx    = $objRSPaginaRow["sfpTituloRegEx"];
	  		$sfpTituloSentence = $objRSPaginaRow["sfpTituloSentence"];
	  		
	  		$sfpTextoRegEx = $objRSPaginaRow["sfpTextoRegEx"];
	  		
	  		$sfpTidyHTML = $objRSPaginaRow["sfpTidyHTML"];
	  		$sfpcURL     = $objRSPaginaRow["sfpcURL"];
	  		
				echo "<hr /><b>sfpCodigo</b>=".$objRSPaginaRow["sfpCodigo"];
				echo "<br /><b>sfpTidyHTML</b>=".$objRSPaginaRow["sfpTidyHTML"];
				echo "<br /><b>sfpcURL</b>=".$objRSPaginaRow["sfpcURL"];
				echo "<br /><b>sfpURL</b>=".$objRSPaginaRow["sfpURL"];
				
				echo "<br /><b>sfpTituloRegEx</b>=".$objRSPaginaRow["sfpTituloRegEx"];
				echo "<br /><b>sfpTituloNormalizeString</b>=".$objRSPaginaRow["sfpTituloNormalizeString"];
				echo "<br /><b>sfpTituloSentence</b>=".$objRSPaginaRow["sfpTituloSentence"];
				
				echo "<br /><b>sfpDataRegEx</b>=".$objRSPaginaRow["sfpDataRegEx"];
				echo "<br /><b>sfpDataMesFromText</b>=".$objRSPaginaRow["sfpDataMesFromText"];
				echo "<br /><b>sfpDataAnoReplace</b>=".$objRSPaginaRow["sfpDataAnoReplace"];
				
				echo "<br /><b>sfpHoraRegEx</b>=".$objRSPaginaRow["sfpHoraRegEx"];
				
				echo "<br /><b>sfpTextoRegEx</b>=".$objRSPaginaRow["sfpTextoRegEx"];
				
				echo "<br /><b>sfpResumoRegEx</b>=".$objRSPaginaRow["sfpResumoRegEx"];
				
				echo "<br /><b>sfpAutorRegEx</b>=".$objRSPaginaRow["sfpAutorRegEx"];
				
				echo "<br /><b>sfpFonteRegEx</b>=".$objRSPaginaRow["sfpFonteRegEx"];

			  $sstrSiteRootDir = dirname(__FILE__).DIRECTORY_SEPARATOR;
			
			  $urlLista = $sifURL;

			 	// $sifcURL
			  
			  $strResult = readHTTP($urlLista);
			  
			  echo "<hr />";
			  
			  $strLista = $strResult;
						
			 	if ($sifTidyHTML) $strLista = tidyApply($strLista);
						
			  echo "<pre>".str_replace(">", "&gt;", str_replace("<", "&lt;", $strLista))."</pre><hr />";
			  
				$urlDomain = $sfpURL;
				
				preg_match_all($sifRegEx, $strLista, $Matches, PREG_OFFSET_CAPTURE);
				
				if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();
				
			  echo "<br /><pre>";
			
			  foreach ($Matches[0] as $key1 => $value1) {
			    // echo "<hr /><b>Matches[0][$key1][0]</b>=".str_replace("<", "&lt;", str_replace(">", "&gt;", $Matches[0][$key1][0]))." <-- Resultado do Match";
			    // echo "<br /><b>Matches[0][$key1][1]</b>=".$Matches[0][$key1][1]." <-- Caracter de início do Match";
			    // echo "<br /><b>Matches[1][$key1][0]</b>=".$Matches[1][$key1][0]." <-- Match 1";
			    
			    $dateDay   = $Matches["day"][$key1][0];
			  	$dateMonth = $Matches["month"][$key1][0];
			  	$dateYear  = $Matches["year"][$key1][0];
			
			  	$dateHour   = $Matches["hour"][$key1][0];
			  	$dateMinute = $Matches["minute"][$key1][0];
			  	$dateSecond = 0;
			  	  	
			    $urlNoticia = $urlDomain.$Matches["url"][$key1][0];
			    
			    echo "<hr />$urlNoticia - $dateDay/$dateMonth/$dateYear - $dateHour:$dateMinute:$dateSecond";
			    
			    // $url_data = parse_url ( $urlNoticia );
			
			  	// print_r($url_data);

	  			// $sfpcURL			    
			    $strResult = readHTTP($urlNoticia);
			    
			    $strNoticia = $strResult;
			
			    if ($sfpTidyHTML) $strNoticia = tidyApply($strNoticia);
			    
			    $strNoticia = preg_replace("%<(no)?script.*?</(no)?script>%i", "", $strNoticia);
			    $strNoticia = preg_replace("%<(p|span).*?>%i", "<$1>", $strNoticia);
					$strNoticia = latin12String($strNoticia);
					
			    $strNoticia = preg_replace("%&nbsp;%i", " ", $strNoticia);
					
			    echo "<pre>".str_replace(">", "&gt;", str_replace("<", "&lt;", $strNoticia))."</pre><hr />";
			    
			    // Título
			    preg_match($sfpTituloRegEx, $strNoticia, $Match, PREG_OFFSET_CAPTURE);
				
					if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();
			
					$noticiaTitulo = $Match["title"][0];
					
					if ($sfpTituloSentence) $noticiaTitulo = Sentence($noticiaTitulo);
			
					// Corpo
			    preg_match($sfpTextoRegEx, $strNoticia, $Match, PREG_OFFSET_CAPTURE);
				
					if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();
			
					$noticiaCorpo = $Match["body"][0];

					// Troca vários espaços por um único
					$noticiaCorpo = preg_replace("%\s+%i", " ", $noticiaCorpo);
					
					// Apagar as tags vazias
					$regexPattern = "%<(\w+)>\s*</\1>%i";
					$regexReplace = "";		
				
					while (preg_match($regexPattern, $noticiaCorpo)) {
						$noticiaCorpo = preg_replace($regexPattern, $regexReplace, $noticiaCorpo);
					}
										
					// Troca todos os <p> iniciais e finais por um <br /> final
					$noticiaCorpo = preg_replace("%<p>(.*?)</p>%i", "$1<br />", $noticiaCorpo);
					
					// Retira todos os <span> ou </span>
			    $noticiaCorpo = preg_replace("%</?span>%i", "", $noticiaCorpo);
			    
			    // Tira os espaços do início do bloco
			    $noticiaCorpo = preg_replace("%<br />\s+%i", "<br />", $noticiaCorpo);
			    
			    // Troca os <p> que sobraram por <br />
			    $noticiaCorpo = preg_replace("%<p>%i", "<br />", $noticiaCorpo);
			    
			    // Retira <br /> do início do bloco
			    $noticiaCorpo = preg_replace("%^(<br />)+(.*)$%i", "$2", $noticiaCorpo);
			    
			    // Tira os espaços do final do bloco
			    $noticiaCorpo = preg_replace("%(<br />)+$%i", "", $noticiaCorpo);
					
					echo "noticiaTitulo=".str_replace(">", "&gt;", str_replace("<", "&lt;", $noticiaTitulo));
					echo "<br />noticiaCorpo=".str_replace(">", "&gt;", str_replace("<", "&lt;", $noticiaCorpo));
					
			  }
				
			  echo "</pre>";
							
				
		  }
  		mssql_free_result($objPaginaSQL); 
	  }
  	mssql_free_result($objFonteSQL); 
  }  
  mssql_free_result($objSQL); 
}

// ----------------------------------------------------------------
// Aplica o TidyHTML no conteúdo
// ================================================================
function tidyApply($strContent) {
	
 	$arrOptions = array(
           "drop-proprietary-attributes" => true,
           "drop-font-tags" => true,
           "drop-empty-paras" => true,
 					 "fix-backslash" => true,
           "hide-comments" => true,
           "join-classes" => true,
           "join-styles" => true,
           "word-2000" => true,
           "output-xhtml" => true,
           "clean" => true,
           "indent" => false,
           "indent-spaces" => 0,
           "logical-emphasis" => true,
           "lower-literals" => true,
  		   	 "quote-ampersand" => true,
           "quote-marks" => true,
  		     "quote-nbsp" => true,
           "wrap" => 0,
           "show-body-only" => true,
 	         "merge-divs" => "auto",
 	         "merge-spans" => "auto");
 	
 	// ascii-chars
  // utf8

  $objTidy = tidy_parse_string($strContent, $arrOptions);

  $objTidy->cleanRepair();
  
  $strResult = str_replace("\r\n", "", $objTidy);
  
  return $strResult;
	
}
	
	// ----------------------------------------------------------------
// Retorna com a URL usando o cURL
// ================================================================
function readHTTP($strURL) {
  $objCURL = curl_init();

  curl_setopt($objCURL, CURLOPT_URL, $strURL);
  curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);
  // curl_setopt($objCURL, CURLOPT_HEADER, 1);
  // curl_setopt($objCURL, CURLINFO_HEADER_OUT, 1);
  curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

  // curl_setopt($objCURL, CURLOPT_COOKIE, "FGTServer=34B68E04678B6417793617BE38F54F47B3BBFF47; __utma=60534895.122979554.1255118786.1266121550.1266123845.13; __utmz=60534895.1266020117.10.3.utmcsr=stf.gov.br|utmccn=(referral)|utmcmd=referral|utmcct=/portal_stj/; ASPSESSIONIDSQBRSDCQ=BLCIHAPCFDAHJLEACAFCNBNJ; __utmc=60534895; ASPSESSIONIDSSARQACQ=DDCNCAPCNJNBJHDPPFPJCCEK; ASPSESSIONIDSQAQTCDQ=NFDCHLIDEIMEAAJNPFIJFHDB; ASPSESSIONIDSSCRTCBQ=PJIFCLIDNJLIHCOJGLHHLBJE; ASPSESSIONIDSQBSRBCQ=HLJGGFLDINBPGOECFNLGPMJA; __utmb=60534895.4.10.1266123845" );

  curl_setopt($objCURL, CURLOPT_COOKIE, "ASPSESSIONIDCSCARQCD=GHEFGIDCHDCLEEMGAKPEKPPO; __utma=15381288.711762829.1282791344.1282791344.1282791344.1; __utmb=15381288.8.10.1282791344; __utmc=15381288; __utmz=15381288.1282791344.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)" );
  
  $strResult = curl_exec($objCURL);

  if (!$strResult)
    echo "<br /><b>Curl error</b>: ".curl_error($objCURL);

  $arrcURL = curl_getinfo($objCURL);

  // echo "<br />http_code=".$arrcURL["http_code"];
  // echo "<br />content_type=".$arrcURL["content_type"];
  
  if (isUTF8($strResult)) $strResult = utf8_decode($strResult);
  
  return $strResult;
  	
}

?>