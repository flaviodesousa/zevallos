<?php 
								echo '<div id="destaques">';
								
								$s = array('STF', 'STJ', 'TSE', 'TST');

								$ts = '';
								
								foreach ($s as $value) {
									$superiores = myQuery("SELECT SQL_CACHE cd_noticia FROM tb_noticia n LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte WHERE n.fl_ativo = 1 AND f.sg_fonte = '$value' ORDER BY n.cd_noticia DESC LIMIT 2");
										
				    			while ($tsRow = mysql_fetch_object($superiores)) {
				    				$ts .= ','.$tsRow->cd_noticia;
				    			}
				    			
				    			mysql_free_result($superiores);
								}
								
								$ts = substr($ts, 1);
			    											
								$sql = "SELECT SQL_CACHE nm_noticia 
												, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
												, dt_referencia_year dt_year 
												, dt_referencia_day dt_day
												, LOWER(sg_mes3) sg_mes3
												, LOWER(REPLACE(sg_fonte, ' ', '')) sg_fonte_cl
												, nm_fonte
												, sg_fonte
												, nm_mes
												, DATE_FORMAT(dt_referencia, '%d/%m/%Y') dt_referencia
												FROM tb_noticia n   
												LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
												LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
												LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
												WHERE n.fl_ativo = 1 AND n.cd_noticia IN ($ts) 
												ORDER BY dt_referencia_year DESC, dt_referencia_month DESC,  dt_referencia_day DESC, nm_noticia
												LIMIT 8";

								// die("<pre>$sql");
										
								$listDestaques = myQuery($sql);
																				
			    			while ($ldRow = mysql_fetch_object($listDestaques)) {
                	echo "<div id=\"destaqueBox\">
													<h2 class=\"laranja\"><a href=\"/$ldRow->sg_fonte_cl\" title=\"$ldRow->nm_fonte\">$ldRow->sg_fonte</a> | <a href=\"/noticias/$ldRow->dt_year/$ldRow->sg_mes3/$ldRow->dt_day\">$ldRow->dt_day de $ldRow->nm_mes de $ldRow->dt_year</a></h2>
													<h1><a href=\"$ldRow->ds_url\">$ldRow->nm_noticia</a></h1>
											  </div>";
								}
									
								mysql_free_result($listDestaques);
		                
                echo '</div>';
