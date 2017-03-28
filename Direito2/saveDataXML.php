<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('config.inc.php');
require_once('d2lib.inc.php');

define('gType',   $_REQUEST['y'], 1);
define('gCidade', $_REQUEST['c'], 1);
define('gEstado', $_REQUEST['e'], 1);

session_cache_limiter('saveXML');
$sintCacheLimiter = session_cache_limiter();

session_cache_expire(20);
$sintCacheExpire = session_cache_expire();

session_start();

ob_start("ob_gzhandler");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ob_end_flush();

set_time_limit(300);

$curl = new curl;

$curl->agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0';

switch (parOption) {
	case 1: // INMET
		inmetProcess($curl);
		break;

	case 101: // INMET
		$cidadeNome = "Brasília";
		
		$ufSigla = "DF";
		$ufNome = "Distrito Federal";
		
		$cidadeNomeU = "BRASILIA";
		$microRegiao = "BRASILIA";
		

		$curl->baseDir    = DocumentRoot;
		$curl->baseURL    = 'http://www.inmet.gov.br';
		$curl->cookieFile = 'inmet.gov.br';

		$fileName = "/PrevisaoTempo$ufSigla.$cidadeNomeU";
		
		$fileFull = DocumentRoot.$fileName;
				
		if (!file_exists($fileFull) || DateDiff('h', filemtime($fileFull), now(), 1) > 12) {
			inmetSave($curl, $ufNome, $ufSigla, $cidadeNome, "/prev_clima_tempo/previsao/dbHTMLCapreCid_B_X.php?cidade=$cidadeNome&uf=$ufSigla&microreg=$microRegiao", $fileName, DocumentRoot, gType);
			
		} else {
			$file = fopen($fileFull, 'r');
			echo fread($file, filesize($fileFull));
			fclose($file);

		}
		break;		
		
	case 2: // Cotacoes
		cotacaoProcess($curl);
		break;
		
	default:
		inmetProcess($curl);
		cotacaoProcess($curl);		
}

function inmetProcess($curl) {
	global $saveUF;
	
	// http://veja.abril.com.br/previsao-tempo
	// http://veja.abril.com.br/retrieve_forecast?city_code=61&state_code=DF
	
	foreach ($saveUF as $key => $value) {
		$ufNome  = $value[0];
		$ufSigla = $key;
		
		$curl->baseDir    = DocumentRoot;
		$curl->baseURL    = 'http://www.inmet.gov.br';
		$curl->cookieFile = 'inmet.gov.br';
				
		// PrevisaoTempoCE.xml
		$param = inmetSave($curl, $ufNome, $ufSigla, '', "/prev_clima_tempo/previsao/html/prevcap_$ufSigla.html", "/PrevisaoTempo$ufSigla", DocumentRoot);
  	
  	echo " &lt-- <b>$ufSigla</b> <a href=\"$curl->referer\" target=\"_blank\">URL</a><br />";
		
	}
}

function inmetSave($curl, $ufNome, $ufSigla, $cidadeNome, $url, $fileName, $fileRoot, $returnType = 0) {
	global $saveWeek, $saveMonth;
	
	$curl->open();
	
	$content = $curl->get($url);
			
	$config = array('drop-proprietary-attributes' => 1
				        , 'drop-font-tags' => 1
				        , 'drop-empty-paras' => 1
				        , 'fix-backslash' => 1
				        , 'fix-uri' => 1
				        , 'fix-bad-comments' => 1
				        , 'hide-comments' => 1
				        , 'join-classes' => 1
				        , 'join-styles' => 1
				        , 'word-2000' => 1
				        , 'output-xhtml' => 1
				        , 'clean' => 1
				        , 'indent' => false
				        , 'indent-spaces' => 0
				        , 'break-before-br' => 0
				        , 'logical-emphasis' => 1
				        , 'lower-literals' => 1
				        , 'quote-ampersand' => 1
				        , 'quote-marks' => 1
				        , 'quote-nbsp' => 1
				        , 'wrap' => 0
				        , 'show-body-only' => 1
				        , 'merge-divs' => 'auto'
				        , 'merge-spans' => 'auto'
				        , 'escape-cdata' => 1
				        , 'sort-attributes' => 'alpha'
				        , 'ascii-chars' => 1);
	
  $tidy = tidy_parse_string($content, $config);

  $tidy->cleanRepair();
  
  $content = str_replace("\n", "", $tidy);
  $content = str_replace("\r", "", $content);
    
  $content = preg_replace('%<(no)?script.*?</(no)?script>%i', "", $content);
	$content = preg_replace('%<(/?(table|td|th|tr))[^>]*>%i', "<$1>", $content);
	$content = preg_replace('%&nbsp;%i', "", $content);
	
	$content = html_entity_decode($content);
		
	preg_match_all('%prev_icon/(?P<img>\w+\.gif)\' /></td><td><span class=\'subtitulo\'><span class=\'combo\'><strong>(?P<week>[^,]+), (?P<day>\d+) de (?P<month>\w+) de (?P<year>\d+)</strong></span><br /><br /></span> <span class=\'texto\'>(?P<previsao>[^<]+)<br /><br />TEMPERATURA:(?P<temperatura>.*?) MAX.:<span class=\'fonteVermelha\'>(?P<max>\d+)°C</span>  MIN.:<span class=\'fonteAzul\'>(?P<min>\d+)°C</span><br />VENTO DIREÇÃO:(?P<direcao>[^<]+)<br />INTENSIDADE:(?P<intensidade>[^<]+)</span><br /><span class=\'texto\'>NASCER DO SOL: (?P<nascerhora>\d+):(?P<nascerminuto>\d+)h-OCASO DO SOL: (?P<acasohora>\d+):(?P<acasominuto>\d+)h%i', $content, $Matches, PREG_OFFSET_CAPTURE);

	// if (!$Matches || $sigla == 'RS') echo "<pre>", htmlentities(html_entity_decode($content)), "\r\n";
	
	// var_dump($Matches);
	
	$param = array();
	$json  = array();
			
	foreach ($Matches[0] as $key => $value) {
		foreach ($saveWeek as $week => $weekA) {
			if ($Matches[week][$key][0] === $weekA[0]) break; 
		}

		foreach ($saveMonth as $month => $monthA) {
			if ($Matches[month][$key][0] === $monthA[0]) break; 
		}
		
		$weekString = ucfirst($saveWeek[$week][0]);
		$weekCurto  = ucfirst($saveWeek[$week][1]);
		$weekLetra  = ucfirst($saveWeek[$week][2]);
		$weekTotal  = ucfirst($saveWeek[$week][3]);
		
		$monthString = ucfirst($saveMonth[$month][0]);
		$monthCurto = ucfirst($saveMonth[$month][1]);
		
		$year2 = substr($Matches[year][$key][0], 2);
		
  	$param[] = array('uf' => $ufSigla
									 , 'nome' => $ufNome
									 , 'cidade' => $cidadeNome
									 , 'imagem' => $Matches[img][$key][0]
									 , 'semana' => $weekString
									 , 'semanacurta' => $weekCurto
									 , 'semanaletra' => $weekLetra
									 , 'semanatotal' => $weekTotal
									 , 'mes' => $monthString
									 , 'mescurto' => $monthCurto
									 , 'mesnumero' => $month
									 , 'dia' => $Matches[day][$key][0]
									 , 'diacurto' => (int)$Matches[day][$key][0]
									 , 'ano' => $Matches[year][$key][0]
			             , 'hora' => date('H')
			             , 'horacurta' => date('G')
			             , 'minuto' => date('i')
			             , 'minutocurto' => (int)date('i')
									 , 'anocurto' => $year2
									 , 'temperatura' => strtolower(accentString2Latin1($Matches[temperatura][$key][0]))
									 , 'nascersolhora' => $Matches[nascerhora][$key][0]
									 , 'nascersolminuto' => $Matches[nascerminuto][$key][0]
									 , 'ocasosolhora' => $Matches[acasohora][$key][0]
									 , 'ocasosolminuto' => $Matches[acasominuto][$key][0]
									 , 'minimo' => $Matches[min][$key][0]
									 , 'maxima' => $Matches[max][$key][0]
									 , 'direcaoventos' => $Matches[direcao][$key][0]
									 , 'intensidade' => strtolower(accentString2Latin1($Matches[intensidade][$key][0]))
									 , 'texto' => strtolower(accentString2Latin1($Matches[previsao][$key][0])));
  	
	}
	
  $xml = '<?xml version="1.0" encoding="ISO-8859-1" ?><data>'.array2Param('previsaotempo', $param).'</data>';
  $json = json_encode($param);

  /*
  echo "<br />json_last_error=", json_last_error();

	switch(json_last_error()) {
		case JSON_ERROR_DEPTH:
			echo ' - The maximum stack depth has been exceeded';
			break;
		case JSON_ERROR_CTRL_CHAR:
			echo ' - Control character error, possibly incorrectly encoded';
			break;
		case JSON_ERROR_SYNTAX:
			echo ' - Syntax error';
			break;
		case JSON_ERROR_NONE:
			echo ' - No error has occurred';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			echo ' - Invalid or malformed JSON';
			break;
		case JSON_ERROR_UTF8:
			echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
			
	}
	*/
  
	$fileFull = $fileRoot.$fileName;

	if (!$returnType) echo $fileName;
	
  $file = fopen($fileFull.'.xml', "w");
	fputs($file, $xml);
	fclose($file);
	
	if (!$returnType) echo ' .xml ('.strlen($xml).')';
		
  $file = fopen($fileFull.'.json', "w");
	fputs($file, $json);
	fclose($file);

	if (!$returnType) echo ', .json ('.strlen($json).')';
	
	switch ($returnType) {
		case 'xml':
			// header('Content-Type: text/xml; charset=iso-8859-1', true);
			
			echo $xml;
			break;
			
		case 'json':
			// header('Content-Type: application/json; charset=iso-8859-1', true);
			
			echo $json;
			break;
	}
	
	return $param;
}	

// http://www4.bcb.gov.br/pec/taxas/batch/taxas.asp?id=txdolar&id=txdolar
// http://www4.bcb.gov.br/pec/taxas/port/ptaxnpesq.asp?id=txcotacao
// http://www21.bb.com.br/appbb/portal/iec/index.jsp
// http://oglobo.globo.com/economia/indicadores/
// http://br.advfn.com/commodities
// http://br.advfn.com/p.php?pid=qkquote&symbol=FX^USDBRL
// http://br.advfn.com/cambio/graficos/brl
// http://veja.abril.com.br/economia/cotacoes

function cotacaoProcess($curl) {
	
	$cotacaoMoedas = array('Dólar Médio Venda - BACEN' => array('DolarMedio', 'Dolar', 1)
											 , 'Dólar Comercial' => array('DolarComercial', 'Dolar', 1)
											 , 'Dólar Paralelo SP' => array('DolarParalelo', 'Dolar', 1)
											 , 'Dólar Turismo SP' => array('DolarTurismo', 'Dolar', 1)
											 , 'Euro x USD - BACEN' => array('Euro', 'Euro', 0)
											 , 'USD / Japanese Yen - BACEN' => array('YenJapones', 'Yen', 0)
											 , 'Real/ Peso Argentino - BACEN' => array('PesoArgentino', 'Peso', 0)
											 , 'USD/Chilean Peso - BACEN' => array('PesoChileno', 'Peso', 0)
											 , 'USD/Bolivar Forte Venezuelano - BACEN' => array('BolivarVenezuelano', 'Bolivar', 0)
											 , 'Peso Mexicano' => array('PesoMexicano', 'Peso', 0)
											 , 'USD/Canadian Dollar - BACEN' => array('DolarCanadence', 'Dolar', 0)
											 , 'Franco Suíço' => array('FrancoSuico', 'Franco', 0)
											 , 'Dólar Hong Kong' => array('DolarHongKong', 'Dolar', 0)
											 , 'Rupia Índia' => array('RupiaIndia', 'Rupia', 0)
											 , 'Won Coréia do Sul' => array('WonCoreiaSul', 'Won', 0));
												 
	$curl->baseDir    = DocumentRoot;
	$curl->baseURL    = 'http://veja.abril.com.br';
	$curl->cookieFile = 'veja.abril.com.br';

	$curl->open();
	
	$content = $curl->get('/economia/cotacoes');
			
	$config = array('drop-proprietary-attributes' => 1
				        , 'drop-font-tags' => 1
				        , 'drop-empty-paras' => 1
				        , 'fix-backslash' => 1
				        , 'fix-uri' => 1
				        , 'fix-bad-comments' => 1
				        , 'hide-comments' => 1
				        , 'join-classes' => 1
				        , 'join-styles' => 1
				        , 'word-2000' => 1
				        , 'output-xhtml' => 1
				        , 'clean' => 1
				        , 'indent' => false
				        , 'indent-spaces' => 0
				        , 'break-before-br' => 0
				        , 'logical-emphasis' => 1
				        , 'lower-literals' => 1
				        , 'quote-ampersand' => 1
				        , 'quote-marks' => 1
				        , 'quote-nbsp' => 1
				        , 'wrap' => 0
				        , 'show-body-only' => 1
				        , 'merge-divs' => 'auto'
				        , 'merge-spans' => 'auto'
				        , 'escape-cdata' => 1
				        , 'sort-attributes' => 'alpha'
				        , 'ascii-chars' => 1
				        , 'char-encoding' => 'latin1');
				        // utf8 - UTF-8
				        // latin1 - ISO-8859-1
	
  $tidy = tidy_parse_string(utf8_decode($content), $config);

  $tidy->cleanRepair();
  
  $content = str_replace("\n", "", $tidy);
  $content = str_replace("\r", "", $content);
    
  $content = preg_replace('%<(no)?script.*?</(no)?script>%i', "", $content);
	$content = preg_replace('%<(/?(table|td|th|tr))[^>]*>%i', "<$1>", $content);
	$content = preg_replace('%&nbsp;%i', "", $content);
	
	$content = html_entity_decode($content);
		
	preg_match_all('%<tr><td>(?P<moeda>[^<]+)</td><td>(?P<ultimo>[\d,.]+)</td><td>(?P<compra>[\d,.]+)</td><td>(?P<venda>[\d,.]+)</td><td>(<img alt="[^"]+" src="/images/seta-(?P<direcao>[^.]+)\.jpg\?[^"]+" />)?\s*(?P<variacao>[-\d,.]+)</td><td>(?P<day>\d+)/(?P<month>\d+)/(?P<year>\d+)</td><td>(?P<hour>\d+):(?P<minute>\d+)</td></tr>%i', $content, $Matches, PREG_OFFSET_CAPTURE);

	// echo "<pre>", htmlentities(html_entity_decode($content)), "\r\n";
	
	$jsonA = array();
	
	foreach ($Matches[0] as $key => $value) {
		$moedaName = $Matches[moeda][$key][0];
		$fileName  = $cotacaoMoedas[$moedaName][0];
		$tag       = $cotacaoMoedas[$moedaName][1];
		$grupo     = $cotacaoMoedas[$moedaName][2];

		/*
		echo "<hr />", $moedaName;
		echo "<br />", $fileName;
		echo "<br />", $tag;
		echo "<br />", $grupo, "<br />";
		*/
		
		$aPack = array('moeda' => accentString2Latin1($Matches[moeda][$key][0])
		             , 'compra' => $Matches[compra][$key][0]
		             , 'venda' => $Matches[venda][$key][0]
		             , 'variacao' => $Matches[variacao][$key][0]
		             , 'fechamentoanterior' => $Matches[ultimo][$key][0]
		             , 'horario' => $Matches[hour][$key][0].':'.$Matches[minute][$key][0]
		             , 'hora' => $Matches[hour][$key][0]
		             , 'minuto' => $Matches[minute][$key][0]
		             , 'data' => $Matches[day][$key][0].'/'.$Matches[month][$key][0].'/'.$Matches[year][$key][0]
		             , 'dia' => $Matches[day][$key][0]
		             , 'mes' => $Matches[month][$key][0]
		             , 'ano' => $Matches[year][$key][0]
		             , 'direcao' => $Matches[direcao][$key][0]);		

		$param[0] = $aPack;
				
		if ($grupo) {
			$grupoA = 1;
			
			$contentA .= array2Param($fileName, $param);
			
 			$jsonA[] = $aPack;
 			
		} else if ($grupoA) {
			$grupoA = 0;
			
 			$jsonA = json_encode($jsonA);
 			
			cotacaoSave($contentA, $jsonA, 'Dolar');
				
			echo " &lt-- <b>Dolar Pack</b> <a href=\"$curl->referer\" target=\"_blank\">URL</a><br />";
				
			$contentA = '';			
 			$jsonA = array(); 
				
		}
		
		$content = array2Param($tag, $param);

  	$json = json_encode($param);
		
		cotacaoSave($content, $json, $fileName);

  	echo " &lt-- <b>".$Matches[moeda][$key][0]."</b> <a href=\"$curl->referer\" target=\"_blank\">URL</a><br />";
	}	
}

function cotacaoSave($content, $json, $fileName) {
	$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?><data>'.$content.'</data>';

	$fileFull = $fileRoot.$fileName;

	if (!$returnType) echo $fileName;
	
  $file = fopen($fileFull.'.xml', "w");
	fputs($file, $xml);
	fclose($file);
	
	if (!$returnType) echo ' .xml ('.strlen($xml).')';
		
  $file = fopen($fileFull.'.json', "w");
	fputs($file, $json);
	fclose($file);

	if (!$returnType) echo ', .json ('.strlen($json).')';
	
	switch ($returnType) {
		case 'xml':
			// header('Content-Type: text/xml; charset=iso-8859-1', true);
			
			echo $xml;
			break;
			
		case 'json':
			// header('Content-Type: application/json; charset=iso-8859-1', true);
			
			echo $json;
			break;
	}
	
	return $param;  
}
