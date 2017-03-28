			<div id="bloco" class="rodape">
			  <strong>&copy; 2007 - <?php echo date('Y')?> Direito2.com.br<sup>&reg;</sup> - Todo o conteúdo pode ser copiado desde que devidamente identificada a origem.</strong>
			  <a title="Você está link do Menu" accesskey="2" href="#menu" name="menu"></a>
			  <a title="Você está link do Mapa do Site" accesskey=";" href="#mapaSite" name="mapaSite"></a>
				<ul>
			    <li class="rmenu"><a href="/apresentacao">Apresentação</a>
			    	<ul>
			    		<li><a href="/projeto">O projeto</a></li>
			    		<li><a href="/tecnologia">Tecnologia</a></li>
			    		<li><a href="/Blog">Blog</a></li>
			        <li><a href="/clima">Clima</a></li>
			    		<li><a href="/noSeuSite">No seu site</a></li>			    		
			        <li><a href="/faleConosco">Contato</a></li>
			        <li><a href="/termosUso">Termos de Uso</a></li>
			        <li><a href="/privacidade">Privacidade</a></li>
			        <li><a href="/alerta">Alerta</a></li>
			        <li><a href="/informarBug">Informar Bug</a></li>
			        <li><a href="/acessibilidade">Acessibilidade</a></li>
						</ul>
			    </li>
			    <li class="rmenu"><a href="/noticias">Arquivo</a>
			    	<ul>
			    	<?php 
				    $sql = 'SELECT SQL_CACHE dt_referencia_year dt_year
										FROM tb_noticia n
										WHERE fl_ativo = 1 AND dt_referencia_year <= YEAR(NOW())
										GROUP BY dt_referencia_year 
										ORDER BY dt_referencia_year DESC';
				
				    $menuArquivo= myQuery($sql);
				
				    while ($maRow = mysql_fetch_object($menuArquivo)) {
				    	echo "<li><a href=\"/noticias/$maRow->dt_year\" title=\"Notícias de $maRow->dt_year\">$maRow->dt_year</a></li>";
				    }
	
						mysql_free_result($menuArquivo);
			    	?>
						</ul>
			    </li>			    
			    <li class="rmenu"><a href="/noticias" title="Tribunais Superiores">Superiores</a>
			    	<ul>
			    	<?php 
				    $sql = 'SELECT SQL_CACHE sg_fonte
										, nm_fonte
										, LOWER(REPLACE(sg_fonte, \' \', \'\')) sg_fonte_cl
										FROM tb_noticia n
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte 
										WHERE n.fl_ativo = 1 AND dt_referencia_year <= YEAR(NOW()) AND sg_fonte IS NOT NULL
										AND tp_fonte = 1
										GROUP BY sg_fonte 
										ORDER BY sg_fonte';
				
				    $menuFontes= myQuery($sql);
				
				    while ($mfRow = mysql_fetch_object($menuFontes)) {
				    	echo "<li><a href=\"/$mfRow->sg_fonte_cl\" title=\"Notícias do $mfRow->nm_fonte\">$mfRow->sg_fonte</a></li>";
				    }
	
						mysql_free_result($menuFontes);
			    	?>
						</ul>
			    </li>
			    <li class="rmenu"><a href="/noticias" title="Tribunais Estaduais">Estaduais</a>
			    	<ul>
			    	<?php 
				    $sql = 'SELECT SQL_CACHE sg_fonte
										, nm_fonte
										, LOWER(REPLACE(sg_fonte, \' \', \'\')) sg_fonte_cl
										FROM tb_noticia n
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte 
										WHERE n.fl_ativo = 1 AND dt_referencia_year <= YEAR(NOW()) AND sg_fonte IS NOT NULL
										AND tp_fonte = 2
										GROUP BY sg_fonte 
										ORDER BY sg_fonte';
				
				    $menuFontes= myQuery($sql);
				
				    while ($mfRow = mysql_fetch_object($menuFontes)) {
				    	echo "<li><a href=\"/$mfRow->sg_fonte_cl\" title=\"Notícias do $mfRow->nm_fonte\">$mfRow->sg_fonte</a></li>";
				    }
	
						mysql_free_result($menuFontes);
			    	?>
						</ul>
			    </li>
			    <li class="rmenu"><a href="/noticias" title="Outras Fontes">Outras</a>
			    	<ul>
			    	<?php 
				    $sql = 'SELECT SQL_CACHE sg_fonte
										, nm_fonte
										, LOWER(REPLACE(sg_fonte, \' \', \'\')) sg_fonte_cl
										FROM tb_noticia n
										LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte 
										WHERE n.fl_ativo = 1 AND dt_referencia_year <= YEAR(NOW()) AND sg_fonte IS NOT NULL
										AND tp_fonte = 3
										GROUP BY sg_fonte 
										ORDER BY sg_fonte';
				
				    $menuFontes= myQuery($sql);
				
				    while ($mfRow = mysql_fetch_object($menuFontes)) {
				    	echo "<li><a href=\"/$mfRow->sg_fonte_cl\" title=\"Notícias do $mfRow->nm_fonte\">$mfRow->sg_fonte</a></li>";
				    }
	
						mysql_free_result($menuFontes);
			    	?>
						</ul>
			    </li>
			    <li class="rmenu"><a href="/livros">Livros</a>
			    	<ul>
							<li><a href="/livros/categoria/administrativo/" title="Direito Administrativo">Administrativo</a></li>
							<li><a href="/livros/categoria/Ambiental/" title="Direito Ambiental">Ambiental</a></li>
							<li><a href="/livros/categoria/civil/" title="Direito Civil">Civil</a></li>
							<li><a href="/livros/categoria/comercial/" title="Direito Comercial">Comercial</a></li>
							<li><a href="/livros/categoria/constitucional/" title="Direito Constitucional e Teoria do Estado">Constitucional</a></li>
							<li><a href="/livros/categoria/filosofia/" title="Filosofia">Filosofia</a></li>
							<li><a href="/livros/categoria/geral/" title="Geral">Geral</a></li>
							<li><a href="/livros/categoria/internacional/" title="Direito Internacional">Internacional</a></li>
							<li><a href="/livros/categoria/penal/" title="Direito Penal">Penal</a></li>
							<li><a href="/livros/categoria/judiciario/" title="Poder Judici&aacute;rio">Poder Judici&aacute;rio</a></li>
							<li><a href="/livros/categoria/processualcivil/" title="Direito Processual Civil">Processual Civil</a></li>
							<li><a href="/livros/categoria/processualpenal/" title="Direito Processual Penal">Processual Penal</a></li>
							<li><a href="/livros/categoria/trabalho/" title="Direito do Trabalho e Previdenci&aacute;rio">Trabalho e Previdenci&aacute;rio</a></li>
							<li><a href="/livros/categoria/tributario/" title="Direito Tribut&aacute;rio e Financeiro">Tribut&aacute;rio e Financeiro</a></li>
							<li><a href="/livros/categoria/dicionarios/" title="Dicion&aacute;rios">Dicion&aacute;rios</a></li>
						</ul>
			    </li>
				</ul>
			</div>
			</div>
		</div>
