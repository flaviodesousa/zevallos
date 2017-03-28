						<div id="meio">
            	<div id="noticias">
            		<img src="http://img.ecr.me/d2/loader.gif" class="loading" />
              </div>
							<ul id="abaDir">
								<?php
								  $sql = "SELECT SQL_CACHE cd_fonte
								  , nm_fonte
								  , sg_fonte
								  , UPPER(REPLACE(sg_fonte, ' ', '')) sg_fonte_cu
								  , LOWER(REPLACE(sg_fonte, ' ', '')) sg_fonte_cl 
								  FROM tb_fonte 
								  WHERE fl_ativo = 1 
								  AND tp_fonte = 2 
								  ORDER BY sg_fonte";
								   
									$justica = myQuery($sql);
	
									$sel = ' class="sel"';
									
				    			while ($tjRow = mysql_fetch_object($justica)) {
										echo "<li$sel><a href=\"/$tjRow->sg_fonte_cl\" title=\"$tjRow->sg_fonte - $tjRow->nm_fonte\">$tjRow->sg_fonte_cu</a></li>";
										$sel = '';
										
								}
									
								mysql_free_result($justica);
								?>
							</ul>
            </div>