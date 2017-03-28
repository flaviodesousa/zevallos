<div id="ftw">
	<div id="ft">
		<p class="contact"><a href="/feedback/suggest.php">Dê sua opnião</a></p>
		<ul class="cf">
			<li class="col">
				<h3>Ajuda</h3>
				<ul class="sub-list">
					<li><a href="/help/tour.php">Fazer um Tour <?php echo $INI['system']['abbreviation']; ?></a></li>
					<li><a href="/help/faqs.php">FAQ</a></li>
					<li><a href="/help/about.php">O que é <?php echo $INI['system']['abbreviation']; ?></a></li>
									</ul>
			</li>
			<li class="col">
				<h3>Siga-nos</h3>
				<ul class="sub-list">
					<li><a href="/subscribe.php?ename=<?php echo $city['ename']; ?>">Cadastre seu email</a></li>
					<li><a href="/feed.php?ename=<?php echo $city['ename']; ?>">Assinar Feed</a></li>
				</ul>
			</li>
			<li class="col">
				<h3>Contato e Informações</h3>
				<ul class="sub-list">
					<li><a href="/feedback/seller.php">Negócios</a></li>
					<li><a href="/feedback/suggest.php">Sugestões</a></li>
					<li><a href="/about/contact.php">Contato</a></li>
					<?php if(is_manager()){?>
					<li><a href="/manage/index.php">Administrar <?php echo $INI['system']['abbreviation']; ?></a></li>
					<?php }?>
				</ul>
			</li>
			<li class="col">
				<h3>Empresa</h3>
				<ul class="sub-list">
					<li><a href="/about/us.php">Sobre Nós</a></li>
					<li><a href="/about/job.php">Empregos</a></li>
					<li><a href="/about/terms.php">Termos & Condições </a></li>
					<li><a href="/about/privacy.php">Privacidade</a></li>
				</ul>
			</li>
			<li class="col end">
					<div class="logo-footer">
						<a href="/"><img src="/static/css/i/logo-footer.png" /></a>
					</div>
			</li>
		</ul>
		<div class="copyright">
		<p>&copy;<span>2010</span>&nbsp;<?php echo $INI['system']['sitename']; ?> Todos os direitos reservados&nbsp;<a href="/about/terms.php"> Leia os Termos <?php echo $INI['system']['abbreviation']; ?> antes de usar </a>&nbsp;</p>
		</div>
	</div>
</div>
<?php include template("manage_html_footer");?>

