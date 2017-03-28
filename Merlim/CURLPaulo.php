<?php
  $sstrSiteRootDir = dirname(__FILE__).DIRECTORY_SEPARATOR;

  $urlLista = "http://www.oabdf.org.br/noticias/457/relacaoNoticias/";

  $strResult = readHTTP($urlLista);
  
  echo "<hr />";

  /* for ($x = 1; $x <= 250 ; $x++) {
    $a = substr($strResultY, $x, 1);

    echo "<br />$x - ".str_replace(">", "&gt;", str_replace("<", "&lt;", $a))."=".ord($a)."(".chr(ord($a)).")";

  }

  echo "<pre>".str_replace(">", "&gt;", str_replace("<", "&lt;", $strResultY))."</pre>";

  */


  // $strResult = str_replace("\r\n", "<br />", $strResult);

  // $strResult = str_replace("\r", "<br />", $strResult);

  // $strResult = str_replace("\n", "<br />", $strResult);

 	$strLista = tidyApply($strResult);

  /* <table border="1" width="100%">
    <tr>
      <th valign="top">Result (<?php  echo strlen($strResult)?>)</th>
      <th valign="top">Tidy (<?php  echo strlen($strTidy)?>)</th>
    </tr>
    <tr>
      <td valign="top"><pre><? echo str_replace(">", "&gt;", str_replace("<", "&lt;", wordwrap($strResult, 100))); ?></pre></td>
      <td valign="top"><pre><? echo str_replace(">", "&gt;", str_replace("<", "&lt;", wordwrap($strTidy, 100))); ?></pre></td>
    </tr>
   </table>
  */

  echo "<pre>".str_replace(">", "&gt;", str_replace("<", "&lt;", $strLista))."</pre><hr />";
  
  // http://www.oabdf.org.br/noticias/457/relacaoNoticias/
	// "/noticias/\d+/\d+/[^"]+"

	// http://www.oabdf.org.br/noticias/457/122582/JusticaDoTrabalhoAlteraSistemaDePeticionamentoEletronico/
	$urlDomain = "http://www.oabdf.org.br/";
	
	preg_match_all('%data">(?P<day>\d+)/(?P<month>\d+)/(?P<year>\d+)\s+-\s+(?P<hour>\d+):(?P<minute>\d+)</div>.*?descricao"><a href="/(?P<url>noticias/[^"]+)">(?P<title>.*?)</a>%i', $strLista, $Matches, PREG_OFFSET_CAPTURE);
	
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
  	 
    $strResult = readHTTP($urlNoticia);

    $strNoticia = tidyApply($strResult);
    
    echo "<pre>".str_replace(">", "&gt;", str_replace("<", "&lt;", $strNoticia))."</pre><hr />";

    // Título
    preg_match('%<div class="titNot">(?P<title>.*?)</div>%i', $strNoticia, $Match, PREG_OFFSET_CAPTURE);
	
		if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();

		$noticiaTitulo = $Match["title"][0];

		// Corpo
    preg_match('%texto"><p>(?P<body>.*?)</p></div>%i', $strNoticia, $Match, PREG_OFFSET_CAPTURE);
	
		if (preg_last_error()) echo "<br /><b>preg_last_error</b>:".preg_last_error();

		$noticiaCorpo = $Match["body"][0];
		
		echo "noticiaTitulo=".str_replace(">", "&gt;", str_replace("<", "&lt;", $noticiaTitulo));
		echo "<br />noticiaCorpo=".str_replace(">", "&gt;", str_replace("<", "&lt;", $noticiaCorpo));
		
  }
	
  echo "</pre>";
	
	//$strTidy
  
// ----------------------------------------------------------------
// Aplica o TidyHTML no conteúdo
// ================================================================
function tidyApply($strContent) {
	
 	$arrOptions = array(
           "drop-proprietary-attributes" => true,
           "drop-font-tags" => true,
           "drop-empty-paras" => true,
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
           "show-body-only" => true);
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

  $strResult = curl_exec($objCURL);

  if (!$strResult)
    echo "<br /><b>Curl error</b>: ".curl_error($objCURL);

  $arrcURL = curl_getinfo($objCURL);

  // echo "<br />http_code=".$arrcURL["http_code"];
  // echo "<br />content_type=".$arrcURL["content_type"];
  
  if (isUTF8($strResult)) $strResult = utf8_decode($strResult);
  
  return $strResult;
  	
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