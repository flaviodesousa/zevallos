
function sendMail() {
  $strAddress = "ruben@zevallos.com";

  $arrMXHost = array();
  $arrMXWeight = array();

  list($strUser, $strDomain) = split("@", $strAddress, 2);

  if(checkdnsrr($strDomain, "MX")) {
    if(!getmxrr($strDomain, $strMXHhost, $strMXWeight)) {
      $strError = "Could not retrieve mail exchanges !<br>\n";
      return false;
    }
  } else {
    $arrMXHost[] = $strDomain;
    $arrMXWeight[] = 1;
  }

  $arrWeightedHost = array();

  for($i=0; $i < count($arrMXHost); $i++) {
    $arrWeightedHost[($strMXWeight[$i])] = $strMXHhost[$i];
  }

  ksort($arrWeightedHost);

  foreach($arrWeightedHost as $strHost) {
    if(!($objFS = fsockopen($strHost, 25))) {
      continue;
    }

    stream_set_blocking($objFS, FALSE);

    $intStopTime = time() + 10;
    $blnGotREsponse = FALSE;

    while(TRUE) {
      $strLine = fgets($objFS, 1024);

      if(substr($strLine, 0, 3 )== "220") {
        $intStopTime = time() + 10;
        $blnGotREsponse = TRUE;

      } elseif(($strLine == "") AND ($blnGotREsponse)) {
        break;
      } elseif(time() > $stopTime) {
        break;
      }
    }

    if(!$gotResponse) {
      continue;
    }

    stream_set_blocking($objFS, TRUE);

    fputs($objFS, "HELO {$_SERVER['SERVER_NAME']}\r\n");
    fgets($objFS, 1024);

    fputs($objFS, "MAIL FROM: ". "<httpd@{$_SERVER['SERVER_NAME']}>\r\n");
    fgets($objFS, 1024);

    fputs($objFS,"RCPT TO: <$address>\r\n");
    $strLine = fgets($objFS,1024);

    fputs($objFS, "QUIT\r\n");
    fclose($objFS);


  }
}
