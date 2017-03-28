					<h1><?php echo $titleH1 ?></h1>
					
					<?php
			    $sql = 'SELECT SQL_CACHE DISTINCT dt_referencia_year dt_year 
									, dt_referencia_month dt_month
									, dt_referencia_day dt_day 
									, REPLACE(sg_fonte, \' \', \'\') sg_fonte_c
									, LOWER(REPLACE(sg_fonte, \' \', \'\')) sg_fonte_cl
									, LOWER(sg_mes3) sg_mes3
									, n.cd_fonte
									, nm_fonte
									, sg_fonte
									FROM tb_noticia n
									LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
									LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
									WHERE n.fl_ativo = 1 AND LENGTH(nm_fonte) > 0 AND dt_referencia_year = '.gYear.' AND sg_mes3 = \''.gMonth.'\' AND dt_referencia_day = '.gDay.'
									ORDER BY sg_fonte';
			
			    $listSource = myQuery($sql);

			    echo '<ul>';
			    
			    $i = 0;
			    
			    while ($lsRow = mysql_fetch_object($listSource)) {
			    	$i++;
			    	
						if ($i === 1) echo '<div class="boxul">';
						
			    	echo "<ul class=\"calendar source $lsRow->sg_mes3 $lsRow->dt_day $lsRow->sg_fonte_cl\">
								    <li class=\"title\"><a href=\"/$lsRow->sg_fonte_cl/$lsRow->dt_year/$lsRow->sg_mes3/$lsRow->dt_day\" title=\"$lsRow->nm_fonte\">$lsRow->sg_fonte</a></li>";
							                    
			    	$sql = "SELECT SQL_CACHE nm_noticia
										, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
										, nm_fonte
										, sg_fonte
										, nm_mes
										FROM tb_noticia n
										LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
										LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
										WHERE n.fl_ativo = 1 AND dt_referencia_year = $lsRow->dt_year AND dt_referencia_month = $lsRow->dt_month AND dt_referencia_day = $lsRow->dt_day AND n.cd_fonte = $lsRow->cd_fonte 
										ORDER BY nm_noticia
										LIMIT 5";
				
				    $listNews = myQuery($sql);
				    				
				    while ($lnRow = mysql_fetch_object($listNews)) {
							echo "<li><a href=\"$lnRow->ds_url\" title=\"$lnRow->nm_noticia\">$lnRow->nm_noticia</a></li>";
						}

			    	echo '</ul>';
			    	
			    if ($i === 2) {$i = 0; echo '</div>';}
									    			    	
				    mysql_free_result($listNews);
			      
			    }
			    
			    echo '</ul>';
			    
					mysql_free_result($listSource);
