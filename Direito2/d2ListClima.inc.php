					<h1><?php echo $titleH1 ?></h1>
					
					<?php
		    	$sql = "SELECT SQL_CACHE cd_regiao 
		    					, sg_regiao
		    					, nm_regiao
									FROM tb_regiao n
									ORDER BY nm_regiao";

				  $listRegiao = myQuery($sql);
		    	
			    echo '<ul>';
			    
			    $i = 0;
			    
			    while ($lgRow = mysql_fetch_object($listRegiao)) {
			    	$i++;
			    	
						if ($i === 1) echo '<div class="boxul">';
						
			    	echo "<ul class=\"calendar source $lgRow->sg_regiao\">
								    <li class=\"titlex\">$lgRow->sg_regiao - $lgRow->nm_regiao</li>";
							                    
			    	$sql = "SELECT SQL_CACHE e.sg_estado
										, nm_estado
										, nm_cidade 					
										FROM tb_estado e
										LEFT JOIN tb_cidade c ON e.cd_estado = c.cd_estado AND c.fl_capital = 1 
										WHERE cd_regiao = $lgRow->cd_regiao  
										ORDER BY nm_estado";
				
				    $listEstado = myQuery($sql);
				    				
				    while ($leRow = mysql_fetch_object($listEstado)) {
							$fileName = DocumentRoot."/PrevisaoTempo$leRow->sg_estado.json";
							$file = fopen($fileName, "r");
							$previsaoTempo = fread($file, filesize($fileName));
							fclose($file);
							
							$json = json_decode($previsaoTempo);
							
							$json = $json[0];
							
							$imagem = substr($json->imagem, 0, 1);
				    	
							echo "<li>$leRow->sg_estado - $leRow->nm_cidade <span id=\"climaRight\"><span class=\"climaMin\">min</span> $json->minimo&ordm;&nbsp;&nbsp;&nbsp;<span class=\"climaMax\">max</span> $json->maxima&ordm;</li>";
						}

			    	echo '</ul>';
			    	
			    if ($i === 2) {$i = 0; echo '</div>';}
									    			    	
				    mysql_free_result($listEstado);
			      
			    }
			    
			    echo '</ul>';
			    
					mysql_free_result($listRegiao);
