					<h1><?php echo $titleH1 ?></h1>
					
					<?php
			    	$sql = 	'SELECT nm_noticia 
										, ds_resumo 
										, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
										, nm_fonte
										, sg_fonte
										, nm_mes
										, DATE_FORMAT(dt_referencia, \'%d/%m/%Y\') dt_referencia
										FROM tb_noticia_fullsearch nf 
										INNER JOIN tb_noticia n ON nf.cd_noticia = n.cd_noticia  
										LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
										LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
										WHERE n.fl_ativo = 1 AND MATCH nf.ds_noticia  AGAINST(\''.fullSearch.'\')
										ORDER BY dt_referencia_year DESC, dt_referencia_month DESC,  dt_referencia_day DESC, nm_noticia
										LIMIT 50';
			    					
				    $fullSearch= myQuery($sql);

						echo '<ul id="list-news">';					 
				    if (mysql_num_rows($fullSearch)) {
					    while ($fsRow = mysql_fetch_object($fullSearch)) {
					    	echo "<li><a href=\"$fsRow->ds_url?fs=".fullSearch."\" title=\"$fsRow->nm_noticia\">$fsRow->nm_noticia</a><span>";
								if ($fsRow->dt_referencia) echo "<strong>$fsRow->ds_resumo</strong>";
					    	if ($fsRow->ds_resumo) echo $fsRow->ds_resumo;
								echo "</span></li>";
							}
				    } else {
					  	echo "<li>0 notícias encontradas</li>";
				    	
				    }
						echo "</ul>";
				    mysql_free_result($fullSearch);
			      			    