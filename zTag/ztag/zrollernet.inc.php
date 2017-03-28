<?php

error_reporting(E_ALL & ~E_NOTICE);

set_time_limit(10000);

session_start();

# File where the cookie is stored
define('COOKIE_FILE', '/tmp/isavail-cookie.txt');

# Default Server Address and port
define('SERVER_ADDR', '200.160.2.3');
define('SERVER_PORT', 43);

define('MAX_UDP_SIZE', 512);
define('DEFAULT_COOKIE', '00000000000000000000');

# Maximum retries and interval
define('MAX_RETRIES', 3);
define('RETRY_TIMEOUT', 5);

$masterServer = "187.103.46.160";
$userName = "zevallos";
$apiKey = "ZevallosUS";

$strDir = "c:/temp/MSDNS/D2";

if (is_dir ($strDir)) {
  if ($dirHandle = opendir($strDir)) {
    $i = 0;

    $objAC = new AvailClient();

    ?>
    <table border="1" cellspacing="0">
      <tr bgcolor="lightgray">
        <th>#</th>
        <th>Domain</th>
        <th>Registro.br</th>
        <th>SOA<br />dns, e-mail, serial</th>
        <th>NS</th>
        <th>MX<br />priority, target</th>
        <th>TXT</th>
        <?php

    $strRegistro= $objAC->send_query('registro.br');

    while (false !== ($strName = readdir($dirHandle))) {
      // abolaesua.com.br.dns

      if (substr($strName, strlen($strName) - 4, 4) === ".dns") {
        $domainName = substr($strName, 0, strlen($strName) - 4);

        echo "<tr>";
        echo "<td>".$i++."</td><td><b>$domainName</b></td>";

        if (substr($strName, strlen($strName) - 7, 7) === ".br.dns") {
          // $ac->setParam($atrib);
          $strRegistro = $objAC->send_query($domainName);

          $arrayRegistro = explode("\n", $strRegistro);

        	preg_match_all('/(ST|CK)\s+(?P<status>\d+)\s+(?P<id>\d+)/', $arrayRegistro[1], $Matches, PREG_OFFSET_CAPTURE);

      		$intStatus = intval($Matches['status'][0][0]);
      		$intId     = $Matches['id'][0][0];

      		$intDomain  = $arrayRegistro[2];

          switch ($intStatus) {
            case '0': // disponível
              $strMessagem = "disponível";
              break;
            case '1': // disponível com tickets concorrentes
              $strMessagem = "disponível com tickets concorrentes";
              break;
            case '2': // registrado
              $strMessagem = "registrado";
              break;
            case '3': // indisponível
              $strMessagem = "indisponível";
              break;
            case '4': // query inválido
              $strMessagem = "query inválido";
              break;
            case '5': // aguardando processo de liberação
              $strMessagem = "aguardando processo de liberação";
              break;
            case '6': // disponível no processo de liberação em andamento
              $strMessagem = "disponível no processo de liberação em andamento";
              break;
            case '7': // disponível no processo de liberação em andamento com tickets concorrentes
              $strMessagem = "disponível no processo de liberação em andamento com tickets concorrentes";
              break;
            case '8': // erro
              $strMessagem = "erro";
              break;
          }



          // 2012-09-19|published|dns1.zevallos.com.br|dns2.zevallos.com.br

      		$regExpiration = null;
      		$regStatusDesc = null;
      		$regMaster     = null;
      		$regSlave      = null;
      		$regSlave2     = null;
      		$regSlave3     = null;

          if ($intStatus == 2) {
          	preg_match_all('/(?P<expiration>.*?)\|(?P<statusdesc>\w+)\|(?P<master>.*?)\|(?P<slave>.*?)(\|(?P<slave2>.*?))?(\|(?P<slave3>.*?))?$/m', $arrayRegistro[3], $Matches, PREG_OFFSET_CAPTURE);

        		$regExpiration = $Matches['expiration'][0][0];
        		$regStatusDesc = $Matches['statusdesc'][0][0];
        		$regMaster     = $Matches['master'][0][0];
        		$regSlave      = $Matches['slave'][0][0];
        		$regSlave2     = $Matches['slave2'][0][0];
        		$regSlave3     = $Matches['slave3'][0][0];
          }

          echo '<td nowrap="nowrap" valign="top">';

          echo $intStatus.' - '.$strMessagem;
          if ($intStatus == 2) {
            echo "<br />".$regExpiration.' - '.$regStatusDesc;
            echo "<br />".$regMaster.' - '.$regSlave;
            if ($regSlave2) echo "<br />".$regSlave2;
            if ($regSlave3) echo ' - '.$regSlave3;
          }
          // echo "<pre>".print_r($strRegistro, 1)."</pre>";
          echo "</td>";

          /*
          echo "<pre>".print_r($strRegistro, 1)."</pre>";
          echo "<pre>".print_r($arrayRegistro[1], 1)."</pre>";
          echo "<pre>".print_r($arrayRegistro, 1)."</pre>";
          echo "<pre>".print_r($Matches, 1)."</pre>";
          die();
          */

          /*
    	    0 - disponível
    	    1 - disponível com tickets concorrentes
    	    2 - registrado
    	    3 - indisponível
    	    4 - query inválido
    	    5 - aguardando processo de liberação
    	    6 - disponível no processo de liberação em andamento
    	    7 - disponível no processo de liberação em andamento com tickets concorrentes
    	    8 - erro
    	    */
        } else {
          echo "<td>&nbsp;</td>";
        }

        if ($intStatus == 2 && $strMessagem != 'on_hold') {
          // DNS_A, DNS_CNAME, DNS_HINFO, DNS_MX, DNS_NS, DNS_PTR, DNS_SOA, DNS_TXT, DNS_AAAA, DNS_SRV, DNS_NAPTR, DNS_A6, DNS_ALL or DNS_ANY.
          $arrayDNS = dns_get_record($domainName, DNS_SOA + DNS_NS + DNS_MX + DNS_A + DNS_TXT);

          $dnsInfo = array();
          foreach ($arrayDNS as $valueDNS) {
            $dnsAux = array();

            switch ($valueDNS['type']) {
              case 'SOA':
                $dnsAux['dns'] = $valueDNS['mname'];
                $dnsAux['email'] = $valueDNS['rname'];
                $dnsAux['serial'] = $valueDNS['rname'];

                $dnsInfo['SOA'][] = $dnsAux;
                break;

              case 'NS':
                $dnsInfo['NS'][] = $valueDNS['target'];
                break;

              case 'MX':
                $dnsAux['pri'] = $valueDNS['pri'];
                $dnsAux['target'] = $valueDNS['target'];

                $dnsInfo['MX'][] = $dnsAux;
                break;

              case 'TXT':
                $dnsInfo['TXT'][] = $valueDNS['txt'];
                break;
            }
          }

          echo '<td nowrap="nowrap" valign="top">';
          foreach ($dnsInfo['SOA'] as $valueInfo) {
            echo $valueInfo['dns'].', '.$valueInfo['email'].', '.$valueInfo['serial']."<br />";
          }
          echo "</td>";

          echo '<td nowrap="nowrap" valign="top">';
          if (count($dnsInfo['NS'])) {
            foreach ($dnsInfo['NS'] as $valueInfo) {
              echo $valueInfo."<br />";
            }
          } else {
            echo '<td align="center">-</td>';
          }
          echo "</td>";

          echo '<td nowrap="nowrap" valign="top">';
          if (count($dnsInfo['MX'])) {
            foreach ($dnsInfo['MX'] as $valueInfo) {
              echo $valueInfo['pri'].', '.$valueInfo['target']."<br />";
            }
          } else {
            echo '<td align="center">-</td>';
          }
          echo "</td>";

          echo '<td nowrap="nowrap" valign="top">';
          if (count($dnsInfo['TXT'])) {
            foreach ($dnsInfo['TXT'] as $valueInfo) {
              echo $valueInfo."<br />";
            }
          } else {
            echo '<td align="center">-</td>';
          }
          echo "</td>";
        }

        echo "</tr>";

        // $strResult = rollerneAdd($domainName, $masterServer, $userName, $apiKey);

        if ($debug == true) {
            echo "<br />*****Response received*****";
            echo "<br />";
            echo $arp->{response};

        }

        ob_flush(); flush();

        // if ($i > 20) die();
      }
    }
    echo '</table>';
  }
}

function rollerneAdd($domainName, $masterServer, $userName, $apiKey) {
  $strURL = "https://acc.rollernet.us/api/api.php?u=$userName&k=$apiKey&m=secdns&a=add&d=$domainName|$masterServer";

  echo " [<a href=\"$strURL\" />URL</a>] ";

  $objCURL = curl_init();

  curl_setopt($objCURL, CURLOPT_URL, $strURL);

  curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($objCURL, CURLOPT_CONNECTTIMEOUT, 10);

  curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

  // curl_setopt($objCURL, CURLOPT_POST, true);

  // curl_setopt($objCURL, CURLOPT_POSTFIELDS, $parameters);
  // curl_setopt($objCURL, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

  curl_setopt($objCURL, CURLOPT_SSLVERSION, 3);
  curl_setopt($objCURL, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($objCURL, CURLOPT_SSL_VERIFYHOST, 2);

  curl_setopt($objCURL, CURLOPT_HEADER, false);
  curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, true);

  $strResult = curl_exec($objCURL);

  if (curl_errno($objCURL)) {
    echo "<br /><pre>curl_errno=".curl_errno($objCURL)." - curl_error=".curl_error($objCURL)."</pre>";

  }

  curl_close($objCURL);

  return $strResult;

}


#############################################################
##                                                         ##
##  Class responsible for parsing a Domain Check response  ##
##                                                         ##
#############################################################
class AvailResponseParser {

    var $status = -1;
    var $query_id = '';
    var $fqdn = '';
    var $fqdn_ace = '';
    var $expiration_date = '';
    var $publication_status = '';
    var $nameservers = '';
    var $tickets = '';
    var $release_process_dates = array();
    var $msg = '';
    var $cookie = '';
    var $response = '';
    var $suggestions = array();

    function str() {
        $message = '';
        $message .= "Query ID: $this->query_id\n";
        $message .= "Domain name: $this->fqdn\n";
        $message .= "Response Status: $this->status (";

        if ($this->status == 0) {
            $message .= "Available)\n";

        } else if ($this->status == 1) {
            $message .= "Available with active tickets)\n";
            $message .= "Tickets: \n";
            $message .= "  " . $this->tickets . "\n";

        } else if ($this->status == 2) {
            $message .= "Registered)\n";
            $message .= 'Expiration Date: ';
            if ($this->expiration_date == '0') {
                $message .= "Exempt from payment\n";
            } else {
                $message .= $this->expiration_date . "\n";
            }

            $message .= "Publication Status: " . $this->publication_status . "\n";
            $message .= "Nameservers: \n";
            $message .= $this->nameservers;

            if (sizeof($this->suggestions) > 0) {
                $message .= "Suggestions:";
                foreach ($this->suggestions as $suggestion) {
                    $message .= " " . $suggestion;
                }
                $message .= "\n";
            }

        } else if ($this->status == 3) {
            $message .= "Unavailable)\n";
            $message .= "Additional Message: " . $this->msg . "\n";

            if (sizeof($this->suggestions) > 0) {
                $message .= "Suggestions:";
                foreach ($this->suggestions as $suggestion) {
                    $message .= " " . $suggestion;
                }
                $message .= "\n";
            }

        } else if ($this->status == 4) {
            $message .= "Invalid query)\n";
            $message .= "Additional Message: " . $this->msg . "\n";

        } else if ($this->status == 5) {
            $message .= "Release process waiting)\n";

        } else if ($this->status == 6) {
            $message .= "Release process in progress)\n";
            $message .= "Release Process:\n";
            $message .= "  Start date: " . $this->release_process_dates[0] . "\n";
            $message .= "  End date:   " . $this->release_process_dates[1] . "\n";

        } else if ($this->status == 7) {
            $message .= "Release process in progress with active tickets)\n";
            $message .= "Release Process:\n";
            $message .= "  Start date: " . $this->release_process_dates[0] . "\n";
            $message .= "  End date:   " . $this->release_process_dates[1] . "\n";
            $message .= "Tickets: \n";
            $message .= $this->tickets;

        } else if ($this->status == 8) {
            $message .= "Error)\n";
            $message .= "Additional Message: " . $this->msg . "\n";

        } else if ($this->response != '') {
            $message = $this->response;

        } else {
            $message = 'No response';
        }

        return $message;
    }

    # Parse a string response
    function parse_response($response) {
        $this->response = $response;
        $buffer = explode("\n", $this->response);

        while (42) {
            if (count($buffer) == 0) { break; }
            $line = trim(array_shift($buffer));

            # Ignore blank lines at the beginning
            if (strlen($line) == 0) { continue; }

            # Ignore comments
            if (substr($line, 0, 1) == "%") { continue; }

            # Get the status of the response, or cookie
            if ((substr($line, 0, 3) == "CK ") ||
                (substr($line, 0, 3) == "ST ")) {
                $items = explode(" ", $line);

                # New cookie
                if ($items[0] == "CK") {
                    $this->cookie = substr($items[1], 0, 20);
                    $this->query_id = $items[2];
                    return 0;
                }

                if (count('items') == 0) { return -1; }

                # Get the response status
                $this->status = $items[1];

                # Status 8: Error
                if ($this->status == 8) {
                    $this->msg = trim(array_shift($buffer));
                    return 0;
                }

                $this->query_id = $items[2];
            }

            # Get the fqdn and fqdn_ace
            $line = trim(array_shift($buffer));
            $words = explode('\|', $line);
            $this->fqdn = $words[0];
            if (count($words) > 1) {
                $this->fqdn_ace = $words[1];
            }

            # Domain available or waiting release process
            if (($this->status == 0) || ($this->status == 5)) {
                return 0;
            }

            # Read a new line from the buffer
            $line = trim(array_shift($buffer));

            # Domain available with ticket: Get the list of active tickets
            if ($this->status == 1) {
                $tickets = explode('\|', $line);
                foreach ($tickets as $t) {
                    $this->tickets .= " $t\n";
                }
                return 0;

            # Domain already registered
            } else if ($this->status == 2) {
                $words = explode('\|', $line);
                if (count($words) < 2) { return -1; }

                $this->expiration_date = $words[0];
                $this->publication_status = $words[1];
                for ($i = 2; $i < count($words); $i++) {
                    $this->nameservers .= "  " . $words[$i] . "\n";
                }

                # Check if there's any suggestion
                $line = trim(array_shift($buffer));
                if ($line == "") {
                    return 0;
                }

                $this->suggestions = explode('\|', $line);
                for ($i = 0; $i < sizeof($this->suggestions); $i++) {
                    $this->suggestions[$i] = $this->suggestions[$i] . ".br";
                }

                return 0;

            # Domain unavailable or invalid or release process
            } else if ($this->status == 3 || $this->status == 4) {
                # Just get the message
                $this->msg = $line;

                if ($this->status == 3) {
                    # Check if there's any suggestion
                    $line = trim(array_shift($buffer));
                    if ($line == "") {
                        return 0;
                    }

                    $this->suggestions = explode('\|', $line);
                    for ($i = 0; $i < sizeof($this->suggestions); $i++) {
                      $this->suggestions[$i] = $this->suggestions[$i] . ".br";
                    }
                }

                return 0;

            # Release process
            } else if ($this->status == 6 || $this->status == 7) {
                # Get the release process dates
                $this->release_process_dates = explode('\|', $line);
                if (count($this->release_process_dates) < 2) {
                    return -1;
                }

                # Get the tickets (status 7)
                if ($this->status == 7) {
                    $line = trim(array_shift($buffer));
                    $tickets = explode('\|', $line);
                    foreach ($tickets as $t) {
                        $this->tickets .= "  " . $t . "\n";
                    }
                }
                return 0;
            }

            # Error
            return -1;
        }
    }

}

############################################################
##                                                        ##
## Class responsible for sending a query thru the network ##
##                                                        ##
############################################################
class AvailClient {

    var $lang = 0;
    var $ip = '';
    var $cookie = DEFAULT_COOKIE;
    var $cookie_file = COOKIE_FILE;
    var $version = 1;
    var $server = SERVER_ADDR;
    var $port = SERVER_PORT;
    var $suggest = 1;

    function setParam($arg) {
        $this->lang        = $arg["lang"];
        $this->ip          = $arg["ip"];
        $this->cookie_file = $arg["cookie_file"];
        $this->server      = $arg["server"];
        $this->port        = $arg["port"];
        $this->suggest     = $arg["suggest"];

        if (!file_exists($this->cookie_file) || !is_readable($this->cookie_file)) {
            # Send a query with an invalid cookie
            $this->send_query('registro.br');
        } else {
            $COOKIE = fopen($this->cookie_file, "r");
            $this->cookie = fread($COOKIE, filesize($this->cookie_file));
            fclose($COOKIE);
        }
    }

    function send_query($fqdn) {
        $query = '';
        if ($this->ip != '') {
            $query .= "[" . $this->ip . "] ";
        }

        # Create a random 10 digit query ID (2^32)
        $query_id = rand(1000000000, 4294967296);

        # Form the query
        $query .= $this->version . " " . $this->cookie . " " .
                  $this->lang . " " . $query_id . " " . trim($fqdn);

        if ($this->version > 0) {
            $query .= " " . $this->suggest;
        }

        # Create a new socket
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!(@socket_connect($sock, $this->server, $this->port))) {
            print "\nConnection Failed!\n";
            exit(1);
        }

        # Send the query and wait for a response
        $timeout = 0;
        $retries = 0;
        $resend = true;

        # Response parser
        $parser = new AvailResponseParser();
        while (42) {
            # Check the need to (re)send the query
            if ($resend == true) {
                $resend = false;
                $retries++;
                if ($retries > MAX_RETRIES) {
                    break;
                }

                # Send the query
                socket_write($sock, $query, strlen($query));
            }

            # Set the timeout
            $timeout += RETRY_TIMEOUT;
            socket_set_option($sock,
                          SOL_SOCKET,  // socket level
                          SO_RCVTIMEO, // timeout option
                          array(
                                "sec"  => $timeout, // Timeout in seconds
                                "usec" => 0  // I assume timeout in microseconds
                               ) );

            $response = @socket_read($sock, MAX_UDP_SIZE);

            if (empty($response)) {
                $resend = true;
                continue;
            }

            # Response received. Call the parser
            $parser->parse_response($response);

            # Check the query ID
            if (($parser->query_id != $query_id) &&
                ($parser->status != 8)) {
                # Wrong query ID. Just wait for another response
                $resend = false;
                continue;
            }

            # Check if the cookie was invalid
            if ($parser->cookie != "") {
                # Save the new cookie
                $cookie = $this->cookie;
                $this->cookie = $parser->cookie;

                if ($COOKIE = fopen($this->cookie_file, "w")) {
                    fwrite($COOKIE, $this->cookie);
                    fclose($COOKIE);
                }

                if ($cookie == DEFAULT_COOKIE) {
                    # Nothing else to do
                    break;
                } else {
                    # Resend query. Now we should have the right cookie
                    $parser = $this->send_query($fqdn);
                    break;
                }

            }
            break;
        }

        # Return the filled ResponseParser object
        return $response;
        return $parser;
    }
}

?>