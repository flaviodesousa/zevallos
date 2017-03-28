					<h1><?php echo $titleH1 ?></h1>
					
					<?php
					
					$weekContent = array(0 => array('caption' => 'Dom', 'class' => 'day', 'title' => 'Domingo')					
														 , 1 => array('caption' => 'Seg', 'class' => 'day', 'title' => 'Segunda-feira')					
														 , 2 => array('caption' => 'Ter', 'class' => 'day', 'title' => 'Terça-feira')					
														 , 3 => array('caption' => 'Qua', 'class' => 'day', 'title' => 'Quarta-feira')					
														 , 4 => array('caption' => 'Qui', 'class' => 'day', 'title' => 'Quinta-feira')					
														 , 5 => array('caption' => 'Sex', 'class' => 'day', 'title' => 'Sexta-fera')					
														 , 6 => array('caption' => 'Sab', 'class' => 'day', 'title' => 'Sábado'));
					
			    $sql = 'SELECT SQL_CACHE 
										n.dt_referencia_year dt_year
									, n.dt_referencia_month dt_month
									, n.dt_referencia_day dt_day
									, cd_mes
									, LOWER(sg_mes3) sg_mes3
									, m.nm_mes nm_mes 	
									, COUNT(*) qt_month
									FROM tb_noticia n
									LEFT JOIN tb_mes m ON cd_mes = n.dt_referencia_month
									WHERE n.fl_ativo = 1 AND n.dt_referencia_year = '.gYear.'
									GROUP BY cd_mes
									ORDER BY cd_mes';
			
			    $listMonth = myQuery($sql);

			    echo '<ul>';
			    
			    while ($lmRow = mysql_fetch_object($listMonth)) {
						$monthContent = array('href' => "/noticias/$lmRow->dt_year/$lmRow->sg_mes3"
							                  , 'caption' => $lmRow->nm_mes
							                  , 'class' => 'title'							                  
							                  , 'title' => "Notícias ($lmRow->qt_month)");
							                  
						$ul = array('class' => "calendar $lmRow->sg_mes3 $lmRow->dt_year"
							        , 'href' => "/noticias/$lmRow->dt_year/$lmRow->sg_mes3"
							        , 'caption' => $lmRow->nm_mes
							        , 'title' => "Notícias ($lmRow->qt_month)");							                  
							                    
			    	$sql = "SELECT SQL_CACHE  
			    	          dt_referencia_year dt_year
										, dt_referencia_month dt_month
										, dt_referencia_day dt_day
										, nm_mes
										, LOWER(sg_mes3) sg_mes3
										, COUNT(cd_noticia) qt_day
										FROM tb_noticia n
										LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
										WHERE n.fl_ativo = 1 AND dt_referencia_year = $lmRow->dt_year AND dt_referencia_month = '$lmRow->dt_month'
										GROUP BY dt_referencia_day DESC
										ORDER BY dt_referencia_day DESC";
				
				    $listDay = myQuery($sql);
				    
				    $dayContent = array();
				
				    while ($ldRow = mysql_fetch_object($listDay)) {
						  $dayContent[$ldRow->dt_day] = array('href' => "/noticias/$ldRow->dt_year/$ldRow->sg_mes3/$ldRow->dt_day"
																								, 'title' => "Notícias ($ldRow->qt_day)");				    	
				    }
			
						echo returnCalendar($lmRow->dt_year, $lmRow->dt_month, $lmRow->dt_day, $weekContent, $dayContent, $monthContent, $ul); 
						
				    mysql_free_result($listDay);
			      
			    }
			    
			    echo '</ul>';
			    
					mysql_free_result($listMonth);
