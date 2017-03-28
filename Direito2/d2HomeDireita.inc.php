<div id="direita">
            	<div id="news">
                <h3>Newsletter <a href="#" class="rss">RSS</a></h3>
                E-mail: <input type="text" name="email" class="inputStyle2"><br>
                <input type="submit" value="Assinar" name="btnG" class="buttonStyle">
                <input type="submit" value="Cancelar" name="btnG" class="buttonStyle">
                <input type="submit" value="Modificar Opções" name="btnG" class="buttonStyle">
                <small>543 Newsletter enviadas / Atualmente 5.563 Assinantes</small>
               	</div>
                <div id="publicidade"><!-- D2 234x60 Lateral -->
								<script type="text/javascript">google_ad_client = "ca-pub-3230208523731980";google_ad_slot = "8589784304";google_ad_width = 234;google_ad_height = 60;</script>
								<script type="text/javascript"  src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>                
								</div>
                <div id="publicidade"></div>
                <div id="publicidade">
                </div>
                <div id="tags">
	                <h3>Nuvem de Palavras-Chave</h3>
	                <?php
	                $sql = 'SELECT 
													p.cd_palavra
													, nm_palavra
													, nm_palavra_original
													, qt_noticias
													FROM (SELECT pd.cd_palavra, SUM(qt_palavra_dia) qt_noticias, nm_palavra, nm_palavra_original FROM tb_palavra_dia pd
													LEFT JOIN tb_palavra p ON p.cd_palavra = pd.cd_palavra GROUP BY cd_palavra ORDER BY SUM(qt_palavra_dia) DESC LIMIT 0, 40) p
													ORDER BY nm_palavra';
	                 
									$tagClound = myQuery($sql);
									
									$i = 0;
									$cloud = 10;
									
									while ($tcRow = mysql_fetch_object($tagClound)) {
										if ($i++ % 2 == 0) $cloud--;
										
										echo "<a rel=\"tag\" class=\"cloud_$cloud\" href=\"/p/$tcRow->nm_palavra_original\" title=\"Notícias ($tcRow->qt_noticias)\">$tcRow->nm_palavra_original</a> ";
										
									}
	                
	                ?>
                </div>
            </div>