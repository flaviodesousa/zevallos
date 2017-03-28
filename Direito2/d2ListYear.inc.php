					<h1><?php echo $titleH1 ?></h1>
					
					<?php 
			    $sql = 'SELECT SQL_CACHE dt_referencia_year dt_year
									, COUNT(cd_noticia) qt_year
									FROM tb_noticia n
									WHERE fl_ativo = 1 AND dt_referencia_year <= YEAR(NOW())
									GROUP BY dt_referencia_year 
									ORDER BY dt_referencia_year DESC';
			
			    $listYear = myQuery($sql);
			
					echo '<ul>';
					
			    while ($lyRow = mysql_fetch_object($listYear)) {
						echo "<ul class=\"calendar ano a$lyRow->dt_year\">
									<li class=\"title\"><a href=\"/noticias/$lyRow->dt_year\" title=\"Notícias ($lyRow->qt_year)\">$lyRow->dt_year</a></li>"; 
			    	
						$sql = "SELECT SQL_CACHE cd_mes
										, LOWER(sg_mes3) sg_mes3
										, sg_mes3 sg_mes3n
										, COUNT(*) qt_month
										FROM tb_mes
										LEFT JOIN tb_noticia n ON cd_mes = dt_referencia_month AND n.fl_ativo = 1 AND dt_referencia_year = $lyRow->dt_year
										GROUP BY cd_mes
										ORDER BY cd_mes";
				
				    $listMonth = myQuery($sql);
				
				    while ($lmRow = mysql_fetch_object($listMonth)) {
				    	if ($lmRow->qt_month > 1) {
				    		echo "<li><a href=\"/noticias/$lyRow->dt_year/$lmRow->sg_mes3\" title=\"Notícias ($lmRow->qt_month)\">$lmRow->sg_mes3n</a></li>";
				    	} else {
				    		echo "<li class=\"empty\">$lmRow->sg_mes3n</li>";
				    	}
				    }
				    
						echo '</ul>';
						
				    mysql_free_result($listMonth);
			      
			    }

					echo '</ul>';
			    
					mysql_free_result($listYear);
