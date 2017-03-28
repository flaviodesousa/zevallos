<?php
// PowerDNS Express API
// c9bab468-0964-4afa-ad75-f53b4674c7d9

require_once('C:/My Dropbox/zTag/svn/ztag/lib/nusoap/lib/nusoap.php');



$client = new nusoap_client("https://www.googleapis.com/urlshortener/v1/url?shortUrl=http://goo.gl/fbsS&key=AIzaSyChm6y1gqfguEK5NVDBKN0I9QToPvQhORg", 'wsdl');

$apiKey = "c9bab468-0964-4afa-ad75-f53b4674c7d9";


$client = new nusoap_client("http://www.powerdns.net/services/express.asmx?wsdl&apikey=c9bab468-0964-4afa-ad75-f53b4674c7d9", 'wsdl');

$error = $client->getError();

if($error){
     echo "<br />Error occurred during construction!";
}
else{
     $result = $client->call('listZones');

     echo "<br /><pre>result=".print_r($result, 1)."</pre>";
}
?>

