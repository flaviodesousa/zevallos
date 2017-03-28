<?php
require_once('config.inc.php');
require_once('d2lib.inc.php');

$arrAccent = explode(",","á,é,í,ó,ú,â,ê,î,ô,û,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ã,õ,ç,Á,É,Í,Ó,Ú,Â,Ê,Î,Ô,Û,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Ã,Õ,Ç,ª");
$arrNormal = explode(",","a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,o,c,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,E,I,O,U,A,I,Ç,a");

foreach ($arrAccent as $key => $value) {
	$arrAccent[$key] = '%'.$value.'%i';
	
}

$notWords = array('[áéíóúàèìòùâêîôû]'
								, '[áéíóúàèìòùâêîôû]+'
								, '[a-z]'
							  , 'n(a|o|e)'
							  , 'd(o|a|e)s?'
								, 'p(a)?r(a|o)'
								, 'que'
								, 'por'
								, 'u(n|m)a?s?'
								, '[oea]s?'
								, 'aos?'
								, 'n(o|a)s?'
								, 'sobre'
								, 'em'
								, '[eo]u'
								, 'ser?'
								, 'outras'
								, '[a-z]/'
								, 'como'
								, 'tá'
								, 'foi'
								, '[sb][ie]m'
								, '[ns][ãa]o'
								, 'w+'
								, 'com(\.br)?'
								, '[tq]u?em'
								, 'i{1,3}');
								
$notWordsR = array();

$serviceAll = array('google' => 1, 'bing' => 2, 'yahoo' => 3, 'live' => 4, 'ask' => 5);

foreach ($notWords as $key => $value) {
	$notWords[$key] = '%\b'.$value.'\b%i';
	$notWordsR[$key] = ''; 
	
}

myConnect();

myQuery('TRUNCATE TABLE tb_palavra_dia');
echo "<br />tb_palavra_dia TRUNCATED";

myQuery('TRUNCATE TABLE tb_palavra_noticia');
echo "<br />tb_palavra_noticia TRUNCATED";

myQuery('TRUNCATE TABLE tb_palavra');
echo "<br />tb_palavra TRUNCATED";

echo "<br />tb_palavra_dia TRUNCATED";

ob_flush(); flush();

$logDir = 'C:/Web/direito2.com.br/IISLog/';

if (is_dir($logDir)) {
	if ($dh = opendir($logDir)) {
		while ($file = readdir($dh)) {
			$fileName = $logDir.$file;

			if (filetype($fileName) == 'file' && substr($file, 0, 2) == 'ex') {
				if (filesize($fileName) > 0) { 
					echo '<hr />', $fileName;
					ob_flush(); flush();
					
					$fh = fopen($fileName, 'rb');
					
					$i = 0;
					$pl = 0;
					
				 	while ($line = fgets($fh, 4096)) {
				 		$i++;
						// #Software: Microsoft Internet Information Services 6.0
						// #Version: 1.0
						// #Date: 2008-04-06 00:00:01
						// #Fields: date time cs-method cs-uri-stem cs-uri-query cs-username c-ip cs-version cs(User-Agent) cs(Referer) sc-status sc-bytes
						// 2008-04-06 00:29:13 GET /tjpr/2008/fev/25/informativo-judiciario p=10&d=/tjpr/2008/fev/25&f=/informativo-judiciario&WebPublisher=1 - 189.72.136.22 HTTP/1.1 Mozilla/4.0+(compatible;+MSIE+7.0;+Windows+NT+5.1;+InfoPath.2;+.NET+CLR+1.1.4322;+.NET+CLR+2.0.50727;+.NET+CLR+3.0.04506.30) http://cade.search.yahoo.com/search?p=inicia%C3%A7%C3%A3o++magistrado++curso+df&ei=UTF-8&y=Buscar&rd=r1&fr=sfp&pstart=1&b=21 200 35327
						// 2008-04-06 00:37:33 GET /cjf/2007/out/1/justica-federal-do-pr-disponibiliza-edital-de-leiloes-judiciais p=10&d=/cjf/2007/out/1&f=/justica-federal-do-pr-disponibiliza-edital-de-leiloes-judiciais&WebPublisher=1 - 189.35.96.114 HTTP/1.1 Mozilla/4.0+(compatible;+MSIE+7.0;+Windows+NT+5.1;+InfoPath.1) http://www.google.com.br/search?q=leil%C3%B5es+paran%C3%A1&hl=pt-BR&start=80&sa=N 200 23766
						// 2008-04-06 00:38:57 GET /cnj/2007/out/11/tjmt-lanca-edital-para-exame-de-selecao-de-conciliadores p=10&d=/cnj/2007/out/11&f=/tjmt-lanca-edital-para-exame-de-selecao-de-conciliadores&WebPublisher=1 - 65.55.165.41 HTTP/1.0 Mozilla/4.0+(compatible;+MSIE+7.0;+Windows+NT+5.2;+.NET+CLR+1.1.4322) http://search.live.com/results.aspx?q=edital&mrt=en-us&FORM=LIVSOP 200 28501
						// 2008-03-01 00:00:28 GET /2006/11/8/PaginaNoticiaTJSENoticias.htm o=140&t=2006/11/8&Temp=TJSENoticias&WebPublisher=1 - 189.70.80.70 HTTP/1.1 Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1) http://www.google.com.br/search?q=Jackson+Borges+Advogados&hl=pt-BR&rlz=1T4ADBR_pt-BRBR214BR215&start=20&sa=N 200 0
						
				 		// http://www.google.com/search?sourceid=chrome&ie=UTF-8&q=direito2+noticias
				 		// http://www.bing.com/search?q=direito2+noticias&go=&form=QBLH&filt=all
				 		// http://br.search.yahoo.com/search;_ylt=Ar5EqWMHV3oECptX3uNfkk2U7q5_;_ylc=X1MDMjE0MjE3MDc3MgRfcgMyBGZyA3lmcC10LTcwNwRuX2dwcwMwBG9yaWdpbgNici55YWhvby5jb20EcXVlcnkDZGlyZWl0bzIgbm90aWNpYXMEc2FvAzE-?vc=&p=direito2+noticias&toggle=1&cop=mss&ei=UTF-8&fr=yfp-t-707

				 		if (substr($line, 0, 1) != '#') {
					  	preg_match_all('%(?P<date>(?P<year>\d+)-(?P<month>\d+)-(?P<day>\d+)) (?P<time>(?P<hour>\d+):(?P<minute>\d+):(?P<second>\d+)) GET (?P<url>[^ ]+) .*?(?P<service>google|bing|yahoo|live|ask)\.(?P<local>((com)(\.\w+)?|\w+))/(results|search|url|web)(\?|;|\.aspx).*?(q|p)=(?P<query>[^& ]+)%', $line, $q, PREG_OFFSET_CAPTURE);
					  	
					  	if (preg_last_error()) echo "<b>preg_last_error</b>:".preg_last_error();

					  	if ($q[query][0][0]) {
						  	$date    = $q[date][0][0];
						  	$year    = (int)$q[year][0][0];
						  	$month   = (int)$q[month][0][0];
						  	$day     = (int)$q[day][0][0];
						  	$time    = $q[time][0][0];
						  	$hour    = (int)$q[hour][0][0];
						  	$minute  = (int)$q[minute][0][0];
						  	$second  = (int)$q[second][0][0];
						  	$url     = $q[url][0][0];
					  		$service = $q[service][0][0];
						  	$local   = $q[local][0][0];
						  	$queryO  = strtolower(utf8_decode(urldecode($q[query][0][0])));
						  	
								$sql = 'SELECT cd_noticia
												FROM tb_noticia_hash
												WHERE hs_noticia = MD5(\''.$url.'\')';
					
								// die("<pre>$sql");
					
								$showNews = myQuery($sql);
					
								$cd_noticia = 0;
								
								if ($snRow = mysql_fetch_object($showNews)) {
									$cd_noticia = $snRow->cd_noticia;
								}
								
								mysql_free_result($showNews);
						  	
						  	$cd_origem = $serviceAll[$service];
						  	
						  	$dateTime = mktime($hour, $minute, $second, $month, $day, $year);
	
						  	// echo "<hr />$line";
						  	$pl++;
						  	$query = preg_replace($notWords, $notWordsR, $queryO);
						  	
						  	if (($pl % 1000) == 0 || $pl == 1) echo '<br />', number_format($pl), ' [', number_format($i), "] - $date - $time - $url - $service - $local = [".htmlentities($queryO)."] ($query)<br />";
						  	
						  	preg_match_all('%(\s+|\b)(?P<word>[^\\\\\\\\/.,@#$z\%&*+=`´~|{}[\]:;!?^"\(\)<> ]+)(\b|\s+)%i', " $query ", $words, PREG_OFFSET_CAPTURE);
						  	
						  	if (preg_last_error()) echo "<b>preg_last_error</b>:".preg_last_error();
						  	
						  	// if (($pl % 1000) == 0 || $pl == 1) var_dump($words[0]);
						  							  	
								foreach ($words[0] as $key => $value) {
									$palavraO = $words[word][$key][0];
									
									$palavra = preg_replace('%^[0-9\W]+$%', '', $palavraO);
									
									if ($palavra) {
										$palavra = preg_replace($arrAccent, $arrNormal, $palavra);
										
										$palavra = mysql_real_escape_string($palavra);
										
										if (($pl % 1000) == 0 || $pl == 1) echo ", $palavra";
										
	
								  	$sql = "SELECT cd_palavra
								  					FROM tb_palavra
														WHERE nm_palavra = '$palavra' -- $date - $time - $url - [".substr($queryO, 0, 20)."] (".substr($query, 0, 20).") - $palavra - ($palavraO)";
								  	
								  	$palavraGET = myQuery($sql);
								  	
										if ($plRow = mysql_fetch_object($palavraGET)) {
											mysql_free_result($palavraGET);
											
											$cd_palavra = $plRow->cd_palavra;
	
									  	$sql = 'UPDATE tb_palavra
															SET qt_palavra = qt_palavra + 1
															, dt_ultima = NOW()													
															WHERE cd_palavra = '.$cd_palavra;
				
											myQuery($sql);
											
										} else {
											mysql_free_result($palavraGET);
											
											$queryMY = mysql_real_escape_string($query);
											$palavraO = mysql_real_escape_string($palavraO);
											
											$sql = "INSERT INTO tb_palavra
															(cd_palavra_origem, nm_palavra, nm_palavra_original, qt_palavra, dt_ultima, ds_original) VALUES
															($cd_origem, '$palavra', '$palavraO', 1, NOW(), '$queryMY')";
											
											myQuery($sql);
											
											$cd_palavra = mysql_insert_id();								
											
										}
										
										$sql = "UPDATE  tb_palavra_dia
														SET qt_palavra_dia = qt_palavra_dia + 1
														WHERE cd_palavra = $cd_palavra 
														AND nu_ano = $year 
														AND nu_mes = $month
														AND nu_dia = $day
														AND nu_hora = $hour";
										
										myQuery($sql);
										
										if (!mysql_affected_rows()) {
											// cd_noticia, nm_ano, nm_mes, nm_dia, nm_hora, nm_quarter, nm_day_of_year, nm_week, nm_week_day, nm_quantidade
	
											$weekOfYear = date('W', $dateTime); 
											$dayOfYear = date('z', $dateTime);
											$dayOfWeek = date('N', $dateTime);
											
											switch($month) {
												case 1:
												case 2:
												case 3:
													$quarter = 1;
													break;
													
												case 4:
												case 5:
												case 6:
													$quarter = 2;
													break;
													
												case 7:
												case 8:
												case 9:
													$quarter = 3;
													break;
													
												case 10:
												case 11:
												case 12:
													$quarter = 4;
											}
											
											$sql = "INSERT INTO tb_palavra_dia
															(cd_palavra, nu_ano, nu_mes, nu_dia, nu_hora, nu_quarter, nu_day_of_year, nu_week, nu_week_day, qt_palavra_dia) VALUES
															($cd_palavra, $year, $month, $day, $hour, $quarter, $dayOfYear, $weekOfYear, $dayOfWeek, 1)";
											
											myQuery($sql);
											
											if ($cd_noticia) {
												$sql = "INSERT INTO tb_palavra_noticia
																(cd_palavra, cd_noticia) VALUES
																($cd_palavra, $cd_noticia)";
												
												myQuery($sql);
											}										
										}
									}
									if (($pl % 1000) == 0 || $pl == 1) {
										echo '<br />';
										ob_flush(); flush();
									}
								}							
							}						  	
					  }
				 	}				 		
			    
			    if (!feof($fh)) echo "Error: unexpected fgets() fail\n";
			    				
					fclose($fh);
					// die();
				}
			}			
		}
		closedir($dh);
	}
}

myDisconnet();
