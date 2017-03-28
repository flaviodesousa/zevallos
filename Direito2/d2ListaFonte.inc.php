					<h1><?php echo $titleH1 ?></h1>
					
					<?php
					if ($lfRow) {
							echo '<ul id="list-news">';					 
							$humandDate = dateHumanized($lfRow->dt_referenciah);
							echo "<li><a href=\"$lfRow->ds_url\" title=\"$lfRow->dt_referenciap - ".accentString2Latin1($lfRow->nm_noticia)."\">".accentString2Latin1($humandDate)." - ".accentString2Latin1($lfRow->nm_noticia)."</a></li>";
							
					    while ($lfRow = mysql_fetch_object($listaFonte)) {
					    	$humandDate = dateHumanized($lfRow->dt_referenciah);
								echo "<li><a href=\"$lfRow->ds_url\" title=\"$lfRow->dt_referenciap - ".accentString2Latin1($lfRow->nm_noticia)."\">".accentString2Latin1($humandDate)." - ".accentString2Latin1($lfRow->nm_noticia)."</a></li>";
							}
							
							echo "</ul>";
					}
						
					mysql_free_result($listaFonte);
						
			      			    