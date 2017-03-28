<?php
require_once('config.inc.php');
require_once('d2lib.inc.php');
// require_once('d2GetIP.inc.php');

$auxIP = $_COOKIE["ipGeo"];

$ipGeo = array();

if ($auxIP) {
	$ipGeo = unserialize(base64_decode($auxIP));

	if ($ipGeo[ipAddress] != $_SERVER['REMOTE_ADDR']) $auxIP = null;

} else {
  $ipGeo[ipAddress] = $_SERVER['REMOTE_ADDR'];
}

$gLatitude  = $_REQUEST['lat'];
$gLongitude = $_REQUEST['lon'];

$okCURL = 0;

if (!$auxIP && $gLatitude && $gLongitude) {
  $ipGeo[ipAddress] = $_SERVER['REMOTE_ADDR'];
  $ipGeo[latitude]  = $gLatitude;
  $ipGeo[longitude] = $gLongitude;

  $curl = new curl();

  $pathInfo = pathinfo(__FILE__);
  $siteRootDir = str_replace('\\', '/', $pathInfo["dirname"]).'/';

  $curl->agent      = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0';
  $curl->baseDir    = $siteRootDir;

  $curl->baseURL    = 'http://maps.googleapis.com';
  $curl->cookieFile = 'maps.googleapis.com';

  // http://code.google.com/apis/maps/documentation/geocoding/#ReverseGeocoding
  // http://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&sensor=

  $curl->open();

  // $curl->step = '<br />Iniciando a sessão com '.$curl->baseURL;

  $curl->post('/maps/api/geocode/json?latlng='.$gLatitude.','.$gLongitude.'&sensor=false');

  $json = json_decode($curl->result);

  // echo "<br />", $json->results[address_components];
  // echo "<br />", $json->results[0]->address_components[3]->short_name;

	// define('ipAddress',   $ipCity->ipAddress, 1);
	// define('timeZone',    $ipCity->timeZone, 1);
	define('latitude',    $gLatitude, 1);
	define('longitude',   $gLongitude, 1);

	$okCURL = ($json->status == 'OK');

  foreach($json->results[0]->address_components as $key => $value) {
    switch($value->types[0]) {
      case 'sublocality':
      case 'locality':
      	$ipGeo[cityName] = $value->long_name;
      	break;

      case 'administrative_area_level_1':
      	$ipGeo[regionName] = $value->long_name;
      	break;

      case 'country':
      	$ipGeo[countryName] = $value->long_name;
      	$ipGeo[countryCode] = $value->short_name;
      	break;

      case 'postal_code':
      	$ipGeo[zipCode] = $value->long_name;
      	break;
    }
  }

  if ($okCURL) setcookie("ipGeo", base64_encode(serialize($ipGeo)), time()+3600*24*7); // Cookie para 1 semana

}

if (!$okCURL) {
	$ipGeo[countryCode] = 'BR';
	$ipGeo[countryName] = 'BRAZIL';
	$ipGeo[timeZone] = '-03:00';
	$ipGeo[regionName] = 'DISTRITO FEDERAL';
	$ipGeo[cityName] = 'BRASILIA';
	$ipGeo[latitude] = '-11';
	$ipGeo[longitude] = '-68.7333';

}

if (gDebug && $okCURL) {
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>d2GetClima</title>
      <script type="text/javascript" src="http://js.ecr.me/jquery-1.3.2.min.js"></script>
    </head>
    <body>
  <?php
}

$sql = 'SELECT SQL_CACHE
				nm_estado
				, e.sg_estado
				, nm_cidade
				FROM tb_estado e
				LEFT JOIN tb_cidade c ON e.cd_estado = c.cd_estado AND c.fl_capital = 1
				WHERE LOWER(nm_estado) = LOWER(\''.$ipGeo[regionName].'\') COLLATE '.mysqlCollate.'
				LIMIT 1';

if (!$myConn) {
	myConnect();
	$blnAux = 1;
}

$climaHead = myQuery($sql);

if ($chRow = mysql_fetch_object($climaHead)) {
	$fileName = DocumentRoot."/PrevisaoTempo$chRow->sg_estado.json";
	$file = fopen($fileName, "r");
	$previsaoTempo = fread($file, filesize($fileName));
	fclose($file);

	$json = json_decode($previsaoTempo);

	$json = $json[0];

	$imagem = substr($json->imagem, 0, 1);

	echo "<div class=\"tempo\" id=\"barraClima\"><a href=\"/clima\"><img src=\"http://img.ecr.me/d2/Clima/$imagem.png\" /><strong>".accentString2Latin1($chRow->nm_cidade)."</strong></a><br /><strong class=\"tempo-qnt\">Min: $json->minimo&ordm;, Max: $json->maxima&ordm;</strong><br /><small class=\"small-clima-texto\">".$json->texto."<br />$json->diacurto/$json->mescurto/$json->ano $json->hora:$json->minuto</small></div>";
}

if ($blnAux) myDisconnet();

if (gDebug && $okCURL) {
  ?>
      <div id="map_canvas2" style="height:300px;width:300px;background-color:#99B3CC;float:right;border-style:solid;border-width:1px;padding:5px;">Please be patient while the browser attempts to determine your location and the map loads. Most browsers will prompt you for your approval before the map will load -- <strong>You must give your approval for this geolocation method</strong></div>
      <?php
    	echo '<pre>ipAddress=', $ipGeo[ipAddress];
    	echo '<br />countryName=', $ipGeo[countryName];
    	echo '<br />countryCode=', $ipGeo[countryCode];
    	echo '<br />latitude=', $ipGeo[latitude];
    	echo '<br />longitude=', $ipGeo[longitude];
    	echo '<br />timeZone=', $ipGeo[timeZone];
    	echo '<br />regionName=', $ipGeo[regionName];
    	echo '<br />cityName=', $ipGeo[cityName];
    	echo '<br />zipCode=', $ipGeo[zipCode];

      var_dump($ipCity);

      ?>
    	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    	<script type="text/javascript">
    	// Determine support for Geolocation
    	if (navigator.geolocation) {
    		// Locate position
    		navigator.geolocation.getCurrentPosition(displayPosition, errorFunction);
    	} else {
    		alert('Parece que o sistema de localição geográfica que é requerido para esta página não está habilitado no seu navegador. Por favor, utilize um que suporte.');
    	}

    	// Success callback function
    	function displayPosition(pos) {
    		var mylat = pos.coords.latitude;
    		var mylong = pos.coords.longitude;

    		// Load Google Map
    		var latlng = new google.maps.LatLng(mylat, mylong);
    		var myOptions = {
    			zoom: 12,
    			navigationControl: true,
    			scaleControl: true,
    			mapTypeControl: true,
    			center: latlng,
    			mapTypeId: google.maps.MapTypeId.ROADMAP
    		};

    		var map = new google.maps.Map(document.getElementById("map_canvas2"), myOptions);

    		//Add marker
    		var marker = new google.maps.Marker({
    		position: latlng,
    		map: map,
    		title:"Voce está aqui (" + pos.coords.latitude + ", " + pos.coords.longitude + ")"
    		});

    	}

    	// Error callback function
    	function errorFunction(error) {
        switch(error.code) {
          case error.TIMEOUT:
          	alert ('Timeout');
          	break;

          case error.POSITION_UNAVAILABLE:
          	alert ('Posição indiponível');
          	break;

          case error.PERMISSION_DENIED:
          	alert ('Permissão negada');
          	break;

          case error.UNKNOWN_ERROR:
          	alert ('Erro desconhecido');
          	break;

          default:
          	alert('Error!');
        }
    	}
    	</script>

      <div id="jsonMap"><?php echo 'url='.$url.'<br />urlParam='.$urlParam.'<br />result='.$curl->result.'<br />gLatitude='.$gLatitude.'<br />gLongitude='.$gLongitude ?></div>
    	<script type="text/javascript">
    		$(document).ready(function() {
      		var jsonMap    = '#jsonMap';
      		var jsonMapURL = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + pos.coords.latitude + ',' + pos.coords.longitude + '&sensor=true_or_false';

          $(jsonMap).load('/d2GetClima.php');
          alert(jsonMapURL);
    		});
    	</script>
    </body>
  <html>
  <?php
}
