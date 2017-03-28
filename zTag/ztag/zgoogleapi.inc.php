<?php
session_start();

require_once "C:/My Dropbox/zTag/svn/ztag/lib/googleapi/src/apiClient.php";
/*
if($format == 'json') {
  header('Content-type: application/json');
  echo json_encode(array('posts'=>$posts));
}
if (isset($_SESSION['oauth_access_token'])) {
  $apiClient->setAccessToken($_SESSION['oauth_access_token']);

} else {
  $token = $apiClient->authenticate();
  $_SESSION['oauth_access_token'] = $token;
}
*/

// https://www.googleapis.com/language/translate/v2?key=AIzaSyChm6y1gqfguEK5NVDBKN0I9QToPvQhORg&q=flowers&source=en&target=fr&callback=handleResponse&prettyprint=true

// Decode a string from URL-safe base64
function decodeBase64UrlSafe($value)
{
  return base64_decode(str_replace(array('-', '_'), array('+', '/'),
    $value));
}


$objCURL = curl_init();

// https://www.googleapis.com/urlshortener/v1/url?shortUrl=http://goo.gl/fbsS&key=AIzaSyChm6y1gqfguEK5NVDBKN0I9QToPvQhORg
// http://maps.googleapis.com/maps/api/directions/json?origin=Location_A&alternatives=false&units=imperial&destination=Location_B&sensor=false

curl_setopt($objCURL, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyChm6y1gqfguEK5NVDBKN0I9QToPvQhORg');

// http://maps.googleapis.com/maps/api/geocode/json



// http://maps.google.com/maps/api/geocode/json?address=R.+Marcos+Macedo,+1350+Fortaleza+CE+Brasil&sensor=false&client=clientID

$parameters = '{"address":"R. Marcos Macedo, 1350 Fortaleza CE Brasil"}';

https://acc.rollernet.us/api/api.php?u=myuser&k=mykey&m=secdns&a=add&d=mydomain.com|master.mydomain.com

curl_setopt($objCURL, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?address=R+Marcos+Macedo+1350+Fortaleza+CE&sensor=false');

curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($objCURL, CURLOPT_CONNECTTIMEOUT, 10);

curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

curl_setopt($objCURL, CURLOPT_POST, true);

curl_setopt($objCURL, CURLOPT_POSTFIELDS, $parameters);
curl_setopt($objCURL, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

curl_setopt($objCURL, CURLOPT_SSLVERSION, 3);
curl_setopt($objCURL, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($objCURL, CURLOPT_SSL_VERIFYHOST, 2);

curl_setopt($objCURL, CURLOPT_HEADER, false);
curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, true);

$strResult = curl_exec($objCURL);

if (curl_errno($objCURL)) {
  echo "<br /><pre>curl_errno=".curl_errno($objCURL)." - curl_error=".curl_error($objCURL)."</pre>";

}

echo "<br /><pre>strResult=".print_r($strResult, 1)."</pre>";

echo "<br /><pre>json_decode=".print_r(json_decode($strResult), 1)."</pre>";

$objJSon = json_decode($strResult);

echo "<br />kind=".$objJSon->kind;
echo "<br />Id=".$objJSon->Id;
echo "<br />longUrl=".$objJSon->longUrl;

curl_close($objCURL);

$objCURL = curl_init();

curl_setopt($objCURL, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?shortUrl=http://goo.gl/80Z6p&projection=FULL&key=AIzaSyChm6y1gqfguEK5NVDBKN0I9QToPvQhORg');
curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($objCURL, CURLOPT_CONNECTTIMEOUT, 10);

curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

curl_setopt($objCURL, CURLOPT_POST, true);

curl_setopt($objCURL, CURLOPT_POSTFIELDS, $parameters);
curl_setopt($objCURL, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

curl_setopt($objCURL, CURLOPT_SSLVERSION, 3);
curl_setopt($objCURL, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($objCURL, CURLOPT_SSL_VERIFYHOST, 2);

curl_setopt($objCURL, CURLOPT_HEADER, false);
curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, true);

$strResult = curl_exec($objCURL);

$objJSon = json_decode($strResult);

curl_close($objCURL);

echo "<hr /><pre>json_decode=".print_r(json_decode($strResult), 1)."</pre>";


      // $arrcURL = curl_getinfo($objCURL);

      // echo "<br />http_code=".$arrcURL["http_code"];
      // echo "<br />content_type=".$arrcURL["content_type"];

      // if (isUTF8($strResult)) $strResult = utf8_decode($strResult);
?>
