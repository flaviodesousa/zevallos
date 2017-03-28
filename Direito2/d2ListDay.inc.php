					<h1><?php echo $titleH1 ?></h1>
					
					<?php
			    $sql = 'SELECT SQL_CACHE dt_referencia_year dt_year
									, dt_referencia_month dt_month
									, dt_referencia_day dt_day
									, nm_mes
									, LOWER(sg_mes3) sg_mes3
									, COUNT(cd_noticia) qt_day
									FROM tb_noticia n
									LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
									WHERE n.fl_ativo = 1 AND dt_referencia_year = '.gYear.' AND sg_mes3 = \''.gMonth.'\'
									GROUP BY dt_referencia_day
									ORDER BY dt_referencia_day';
			
			    $listDay = myQuery($sql);

			    echo '<ul>';

				  $i = 0;
			    
			    while ($ldRow = mysql_fetch_object($listDay)) {
			    	$i++;
			    	
						if ($i === 1) echo '<div class="boxul">';
			    	
				    echo "<ul class=\"calendar fonte\">
				    				<li class=\"title\"><a href=\"/noticias/$ldRow->dt_year/$ldRow->sg_mes3/$ldRow->dt_day\" title=\"Notícias ($ldRow->qt_day)\">$ldRow->dt_day</a></li>";

			    	$sql = "SELECT SQL_CACHE dt_referencia_year dt_year
										, dt_referencia_month dt_month
										, dt_referencia_day dt_day
										, nm_fonte
										, sg_fonte
										, REPLACE(sg_fonte, ' ', '') sg_fonte_c
										, LOWER(REPLACE(sg_fonte, ' ', '')) sg_fonte_cl
										, REPLACE(sg_fonte, ' ', '') sg_fonte_clx
										, nm_mes
										, LOWER(sg_mes3) sg_mes3
										, COUNT(cd_noticia) qt_source
										FROM tb_noticia n
										LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
										WHERE n.fl_ativo = 1 AND LENGTH(nm_fonte) > 0 AND dt_referencia_year = $ldRow->dt_year AND dt_referencia_month = $ldRow->dt_month AND dt_referencia_day = $ldRow->dt_day
										GROUP BY sg_fonte
										ORDER BY sg_fonte";
				
				    $listSource = myQuery($sql);

				    while ($lsRow = mysql_fetch_object($listSource)) {
							echo "<li><a href=\"/$lsRow->sg_fonte_cl/$lsRow->dt_year/$lsRow->sg_mes3/$lsRow->dt_day\"  title=\"$lsRow->nm_fonte - Notícias ($lsRow->qt_source)\">$lsRow->sg_fonte_clx</a></li>";
						}

			    	echo '</ul>';

						if ($i === 3) {$i = 0; echo '</div>';}
			    	
				    mysql_free_result($listSource);
			      
			    }
			    
			    echo '</ul>';
			    
					mysql_free_result($listDay);
