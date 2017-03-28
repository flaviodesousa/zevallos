					<h1><?php echo $titleH1 ?></h1>
					
					<?php
			    	$sql = 'SELECT SQL_CACHE nm_noticia
										, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
										, nm_fonte
										, sg_fonte
										, nm_mes
										FROM tb_noticia n
										LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
										LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
										WHERE n.fl_ativo = 1 AND dt_referencia_year = '.gYear.' AND sg_mes3 = \''.gMonth.'\' AND dt_referencia_day = '.gDay.' AND LOWER(REPLACE(sg_fonte, \' \', \'\')) = \''.gSource.'\'
										ORDER BY dt_referencia_year DESC, dt_referencia_month DESC,  dt_referencia_day DESC, nm_noticia';
				
				    $listNews = myQuery($sql);

						echo '<ul id="list-news">';					 
									    
				    while ($lnRow = mysql_fetch_object($listNews)) {
							echo "<li><a href=\"$lnRow->ds_url\" title=\"$lnRow->nm_noticia\">$lnRow->nm_noticia</a></li>";
						}
						
						echo "</ul>";
						
				    mysql_free_result($listNews);
			      			    