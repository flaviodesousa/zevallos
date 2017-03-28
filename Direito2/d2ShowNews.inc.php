<?php
if ($snRow) {
	$sql = "SELECT n.cd_noticia
					, sg_fonte
					, nm_noticia
					, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
					FROM tb_noticia n
					LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
					LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
					WHERE dt_referencia_day = $snRow->dt_referencia_day
					AND dt_referencia_month = $snRow->dt_referencia_month
					AND dt_referencia_year = $snRow->dt_referencia_year
					ORDER BY sg_fonte, cd_noticia";

	$navNews = myQuery($sql);

	while ($nnRow = mysql_fetch_object($navNews)) {
		if ($idB) {
			$navA = $navB;
			$navIA = $navIB;
			$idA  = $idB;
		}

		if ($idC) {
			$navB = $navC;
			$navIB = $navIC;
			$idB  = $idC;
		}

		$navC = "<a href=\"$nnRow->ds_url\" title=\"$nnRow->sg_fonte - $nnRow->nm_noticia\"";
		$navIC = "<dd><strong>$nnRow->sg_fonte</strong> <a href=\"$nnRow->ds_url\">$nnRow->nm_noticia</a></dd>";
		$idC = $nnRow->cd_noticia;

		// echo "<br />$idA, $idB, $idC - $nnRow->nm_noticia";

		if ($idB == $snRow->cd_noticia) break;

	}

	if ($navA) {
		$navPrev = $navA." class=\"prev\">Anterior</a>";
		$navPrevI = "<dt>Próxima:</dt>".$navIA;
	}
	if ($navC && $idB == $snRow->cd_noticia) {
		$navNext = $navC." class=\"next\">Próxima</a>";
		$navNextI = "<dt>Anterior:</dt>".$navIC;
	}

	mysql_free_result($navNews);
}
?>
					<h1><?php echo $snRow->nm_noticia ?></h1>

					<div id="info">
						<span class="org"><strong>De:</strong> <a href="<?php echo $fonteURL ?>" rel="nofollow" title="<?php echo $snRow->nm_fonte ?>" target="_blank"><?php echo $snRow->sg_fonte ?></a> - <?php echo $snRow->dt_referenciaf ?> (<a href="<?php echo $fonteURL.$snRow->ds_url_old ?>" rel="nofollow" target="_blank">original</a>)</span>

						<div id="face"><script src="http://connect.facebook.net/pt_BR/all.js#xfbml=1"></script><fb:like href="http://<?php echo Domain.$snRow->ds_url ?>" layout="button_count" show_faces="true" width="70"></fb:like></div>
						<div id="tw"><script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script><a
href="http://twitter.com/share" class="twitter-share-button" rel="nofollow" style="display:none;" title="Enviar para o seu Twitter" data-url="http://<?php echo Domain.$snRow->ds_url ?>" data-text="<?php echo $snRow->nm_noticia ?> via @Direito2" data-count="horizontal">Twittar</a></div>
						<div id="lk"><script type="text/javascript" src="http://platform.linkedin.com/in.js"></script><script type="in/share" data-url="http://<?php echo Domain.$snRow->ds_url ?>" data-counter="right"></script></div>

						<!-- <a href="#" class="print" title="Imprimir">&nbsp;</a>  -->
						<!-- <a href="#" class="rec" title="Recomendar">&nbsp;</a>  -->
						<a href="#" class="erro" title="Informar Erro">&nbsp;</a>
						<a href="#" class="edit" title="Propor ajuste no conteúdo">&nbsp;</a>

						<div id="prevnext">
							<a href="/noticias/<?php echo gYear.'/'.gMonth.'/'.gDay ?>" title="Índice do dia <?php echo gDay ?>" class="ind">Índice</a>
							<?php echo $navPrev ?>
							<?php echo $navNext ?>
						</div>

				 	</div>


					<div id="HOTWordsTxt" name="HOTWordsTxt"><span id="TextAdBox"><script type="text/javascript"> google_ad_client = "pub-3230208523731980"; google_ad_width = 250; google_ad_height = 250; google_ad_format = "250x250_as"; google_ad_type = "text_image"; google_ad_channel = "7383918978"; google_color_border = "CCCCCC"; google_color_bg = "FFFFFF"; google_color_link = "333333"; google_color_text = "333333"; google_color_url = "333333";</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script></span>

					<?php if (fullSearch) $snRow->ds_noticia = preg_replace('%('.fullSearch.')%mi', '<strong>$1</strong>', $snRow->ds_noticia);
					echo preg_replace('%^(.*)$%m', '<p>$1</p>', $snRow->ds_noticia); ?>
					</div>

					<dl id="otherposts">
						<?php echo $navPrevI ?>
						<?php echo $navNextI ?>
						<dt><a href="/noticias/<?php echo gYear.'/'.gMonth.'/'.gDay ?>" title="Índice do dia <?php echo gDay ?>">Índice do dia <?php echo gDay ?></a></dt>
					</dl>
<?php 

$cNome       = $_REQUEST['nome'];
$cEMail      = $_REQUEST['email'];
$cURL        = $_REQUEST['url'];
$cAtividade  = $_REQUEST['atividade'];
$cLocalidade = $_REQUEST['localidade'];
$cAntiSpam   = $_REQUEST['antispam'];
$cMensagem   = $_REQUEST['mensagem'];
$cNotificar  = $_REQUEST['notificar'];
$cIdNew      = $_REQUEST['idnew'];

$sql = 'SELECT cd_comentario
			, cd_comentario_pai
			, ds_url
			, ds_cidade
			, ds_estado
			, ds_atividade
			, ds_comentario
			, c.dt_inclusao
			, u.nm_usuario
			, u.hs_usuario
			FROM tb_comentario c
			LEFT JOIN tb_usuario u ON u.cd_usuario = c.cd_usuario 
			WHERE c.fl_ativo = 1 AND c.cd_noticia = '.$snRow->cd_noticia.'
			AND c.dt_validado IS NOT NULL'; 

// die("<pre>$sql");

$listComentario = myQuery($sql);

$comentarios = mysql_num_rows($listComentario);

$i = 1;

?>
<div id="omcomentarios">
	<h6><?php echo $comentarios ?> pessoas comentaram a notícia "<?php echo $snRow->nm_noticia ?>"</h6>
	<ol class="commentlist">
	<?php while ($lcRow = mysql_fetch_object($listComentario)) {
		$url = $lcRow->nm_usuario;
		
		if ($lcRow->ds_url) $url = "<a href=\"http://$lcRow->ds_url\" rel=\"external nofollow\">$url</a>";  
		if ($lcRow->ds_atividade) $atividade = ", $lcRow->ds_atividade";
		
		$comentario = str_replace('\r\n', '<br />', $lcRow->ds_comentario);
				
		if ($lcRow->ds_comentario) {
			$comentario = preg_replace('%<a href="[^>]+>(.*?)</a>%i', '$1', $comentario);
			
			$comentario = preg_replace('%^[ \t]*$[\r?\n?]+%', '', $comentario);
			
			$comentario = preg_replace('%$[\r?\n?]+%', '\r\n', $comentario);
		
			$comentario = preg_replace('%<(/?b|u|i|strong|em)>%i', '[$1]', $comentario);
			
			$comentario = preg_replace('%<[^.]+>%', '', $comentario);
			
			$comentario = preg_replace('%\[(/?b|u|i)\]%i', '<$1>', $comentario);
			
			$comentario = preg_replace('%((http://(www\.[-a-z\.0-9]+|[-a-z\.0-9]+))|www\.[-a-z\.0-9]+)%i', '<a href="http://$1" rel="nofollow" target="_blank">$1</a> ', $comentario);
		
			$comentario = preg_replace('%(http://)+%i', 'http://', $comentario);
			
			$comentario = preg_replace('%[ ]+%', ' ', $comentario);
		
			$comentario = preg_replace('%^\s+%', '', $comentario);
		
			$comentario = preg_replace('%[\r\n\s]+$%', '', $comentario);
			
		  $comentario = '<p>'.str_replace($comentario.'</p>', '\r\n', '</p><p>');
		
			$comentario = preg_replace('%<([^>])></\1>%', '', $comentario);
			
			$comentario = preg_replace('%\s+</p>%i', '</p>', $comentario);
		  
		}
		
		?>
		<li class="alt" id="comentario-<?php echo $lcRow->cd_comentario ?>">
			<div class="commentcount"><?php echo $i++ ?></div>
			<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo $lcRow->hs_usuario ?>&default=http%3A%2F%2Fwww%2Edireito2%2Ecom%2Ebr%2FGravatar%2Ebmp" width="32px" height="32px" style="margin-right:5px; float:left;" /> 
			<cite><?php echo $url ?></cite><?php echo $atividade ?>
			<br /> 
			<small class="commentmetadata"><a href="#comentario-<?php echo $lcRow->cd_comentario ?>" rel="nofollow" title="30 de maio de 2011 as 03:47">30 de maio de 2011 as 03:47</a></small>
			<?php echo $comentario ?><?php echo strlen($lcRow->ds_comentario)?>
		</li>
	<?php } ?>
	</ol>
<?php 
$int1 = rand(1, 10);
$int2 = rand(1, 10);

$idNew = date('s').chr(64 + $int1).chr(74 + $int1).chr(116 + $int2).date('i').Chr(96 + int2);

?>	
	<form action="<?php echo gPath ?>">
		<fieldset>
			<label for="nome"><span>Nome <small class="ob">(obrigatório)</small>:</span><input type="text" name="nome" id="nome" class="text" /></label>
			<label for="email"><span>Email <small class="ob">(não será publicado) (obrigatório)</small>:</span><input type="text" name="email" id="email" class="text" /></label>
			<label for="url"><span>URL Endereço do seu site:</span><input type="text" name="url" id="url" class="text" /></label>
			<label for="atividade"><span>Sua atividade:</span><input type="text" name="atividade" id="atividade" class="text" /></label>
			<label for="localidade"><span>Sua cidade:</span><input type="text" name="localidade" id="localidade" class="text" /></label>
			<label for="antispam"><span>Proteção contra SPAM: Qual a soma para <strong><?php echo $int1 ?> + <?php echo $int2 ?> </strong><small class="ob">(obrigatório)</small>:</span><input type="text" name="antispam" id="antispam" class="text" /></label>
			<label for="" class="mensagem"><span>Mensagem</span><textarea name="mensagem" id="mensagem"></textarea></label>
			<label for="notificar"><input name="notificar" id="notificar" value="1" type="checkbox"><span>&nbsp;Notifique-me dos próximos comentários por e-mail...</span></label>
			<p>Utilize se necessário &lt;b&gt;&lt;em&gt;&lt;i&gt;&lt;u&gt;&lt;strong&gt; em seu comentário.</p>
			<input name="submit" id="submit" tabindex="7" value="Envia comentário" type="submit" class="buttonStyle"/>
			<input name="id" value="<?php echo $snRow->cd_noticia ?>" type="hidden" />
			<input name="idNew" id="idNew" value="<?php echo $idNew ?>" type="hidden" />
			<p>Ao comentar, você está automaticamente concordando com os <a href="#">critérios de uso</a> dos comentários deste site.</p>
			<p>Você deseja ver o seu avatar no seu próximo comentário? Você precisa do <a href="http://www.gravatar.com" target="_blank">Gravatar</a></p>
			<p>* Os textos publicados neste espaço são de responsabilidade única de seus autores e podem não expressar necessariamente a opinião do Direito 2.</p>
		</fieldset>
	</form>
</div>
