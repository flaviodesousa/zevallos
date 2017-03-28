<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="leader">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_team($selector); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Nova Oferta</h2></b></div>
                <div class="sect">
				<form id="login-user-form" method="post" action="/manage/team/create.php" enctype="multipart/form-data" class="validator">
					<div class="wholetip clear"><h3>1. Basico</h3></div>
					<div class="field">
						<label>Cidade&Categoria</label>
						<select name="city_id" class="f-input" style="width:160px;"><?php echo Utility::Option(Utility::OptionArray($hotcities, 'id','name'), $team['city_id'], 'Todas'); ?></select><select name="group_id" class="f-input" style="width:160px;"><?php echo Utility::Option($groups, $team['group_id']); ?></select><select name="conduser" class="f-input" style="width:160px;"><?php echo Utility::Option($option_cond, $team['conduser']); ?></select>
					</div>
					<div class="field">
						<label>Titulo</label>
						<input type="text" size="30" name="title" id="team-create-title" class="f-input" value="<?php echo $team['title']; ?>" require="true" datatype="require"/>
					</div>
					<div class="field">
						<label>Preço normal</label>
						<input type="text" size="10" name="market_price" id="team-create-market-price" class="number" value="<?php echo moneyit($team['market_price']); ?>" datatype="money" require="true" />
						<label>Preço com desconto</label>
						<input type="text" size="10" name="team_price" id="team-create-team-price" class="number" value="<?php echo moneyit($team['team_price']); ?>" datatype="double" require="true" />
						<span class="inputtip">Tem que ser menor que preço normal</span>
					</div>
					<div class="field">
						<label>Minimo</label>
						<input type="text" size="10" name="min_number" id="team-create-min-number" class="number" value="<?php echo intval($team['min_number']); ?>" maxLength="6" datatype="number" require="true" />
						<label>Maximo</label>
						<input type="text" size="10" name="max_number" id="team-create-max-number" class="number" value="<?php echo intval($team['max_number']); ?>" maxLength="6" datatype="number" require="true" />
						<label>Maximo por pessoa</label>
						<input type="text" size="10" name="per_number" id="team-create-per-number" class="number" value="<?php echo intval($team['per_number']); ?>" maxLength="6" datatype="number" require="true" />
						<span class="hint">Minimo tem que ser maior que 0, e para o maximo. coloque 0 para ilimitado.</span>
					</div>
					<div class="field">
						<label>Data inicio</label>
						<input type="text" size="10" name="begin_time" id="team-create-begin-time" class="date" value="<?php echo date('d-m-Y', $team['begin_time']); ?>" maxLength="10" />
						<label>Data fim</label>
						<input type="text" size="10" name="end_time" id="team-create-end-time" class="date" value="<?php echo date('d-m-Y', $team['end_time']); ?>" maxLength="10" />
						<label>Validade</label>
						<input type="text" size="10" name="expire_time" id="team-create-expire-time" class="date" value="<?php echo date('d-m-Y', $team['expire_time']); ?>" maxLength="10" />
						<span class="hint">inicia 00:00:00, e termina 00:00:00 da data fim</span>
					</div>
					<div class="field">
						<label>Introdução sobre a oferta</label>
						<div style="float:left;"><textarea cols="45" rows="5" name="summary" id="team-create-summary" class="xheditor-simple" datatype="require" require="true"></textarea></div>
					</div>
					<div class="field">
						<label>Sugestão especial</label>
						<div style="float:left;"><textarea cols="45" rows="5" name="notice" id="team-create-notice" class="xheditor-simple"><?php echo $team['notice']; ?></textarea></div>
						<span class="hint">Detalhe bem</span>
					</div>
					<input type="hidden" name="guarantee" value="Y" />
					<input type="hidden" name="system" value="Y" />
					<div class="wholetip clear"><h3>2. Informações sobre a oferta</h3></div>
					<div class="field">
						<label>Empresa parceira</label>
						<select name="partner_id" datatype="require" require="true"><?php echo Utility::Option($partners, $team['partner_id'], '------ Escolha o parceiro ------'); ?></select>
					</div>
					<div class="field">
						<label>Ticket</label>
						<input type="text" size="10" name="card" id="team-create-card" class="number" value="<?php echo moneyit($team['card']); ?>" require="true" datatype="money" />
						<span class="inputtip">Qual o valor maximo?</span>
					</div>
					<div class="field">
						<label>Nome do item</label>
						<input type="text" size="30" name="product" id="team-create-product" class="f-input" value="<?php echo $team['product']; ?>" datatype="require" require="true" />
					</div>
					<div class="field">
						<label>Imagens</label>
						<input type="file" size="30" name="upload_image" id="team-create-image" class="f-input" />
						<span class="hint">No minimo uma imagem.</span>
					</div>
					<div class="field">
						<label>Imagem 1</label>
						<input type="file" size="30" name="upload_image1" id="team-create-image1" class="f-input" />
					</div>
					<div class="field">
						<label>Imagem 2</label>
						<input type="file" size="30" name="upload_image2" id="team-create-image2" class="f-input" />
					</div>
					<div class="field">
						<label>FLV Video</label>
						<input type="text" size="30" name="flv" id="team-create-flv" class="f-input" />
						<span class="hint">Formato: http://.../video.flv</span>
					</div>
					<div class="field">
						<label>Detalhes do pedido</label>
						<div style="float:left;"><textarea cols="45" rows="5" name="detail" id="team-create-detail" class="xheditor-simple"></textarea></div>
					</div>
					<div class="field">
						<label>Comentários</label>
						<div style="float:left;"><textarea cols="45" rows="5" name="userreview" id="team-create-userreview" class="xheditor-simple"><?php echo htmlspecialchars($team['userreview']); ?></textarea></div>
						<span class="hint">Formato: "Muito BOM! |Rose|http://ww....|XXX Website" Um por linha.</span>
					</div>
					<div class="field">
						<label><?php echo $INI['system']['abbreviation']; ?> abreviação</label>
						<div style="float:left;"><textarea cols="45" rows="5" name="systemreview" id="team-create-systemreview" class="xheditor-simple"></textarea></div>
					</div>
					<div class="wholetip clear"><h3>3. Informações de entrega</h3></div>
					<div class="field">
						<label>Entrega</label>
						<div style="margin-top:5px;" id="express-zone-div"><input type="radio" name="delivery" value="coupon" checked>&nbsp;<?php echo $INI['system']['couponname']; ?>&nbsp;<input type="radio" name="delivery" value='express' />&nbsp;Entrega expressa&nbsp;<input type="radio" name="delivery" value='pickup' />&nbsp;Retirar em mãos</div>
					</div>
					<div id="express-zone-coupon" style="display:<?php echo $team['delivery']=='coupon'?'block':'none'; ?>;">
						<div class="field">
							<label>Desconto</label>
							<input type="text" size="10" name="credit" id="team-create-credit" class="number" value="<?php echo moneyit($team['credit']); ?>" datatype="money" require="true" />
							<span class="inputtip">Consumido <?php echo $INI['system']['couponname']; ?>, ganhe desconto</span>
						</div>
					</div>
					<div id="express-zone-pickup" style="display:<?php echo $team['delivery']=='pickup'?'block':'none'; ?>;">
						<div class="field">
							<label>Tel.</label>
							<input type="text" size="10" name="mobile" id="team-create-mobile" class="f-input" value="<?php echo $login_manager['mobile']; ?>" />
						</div>
						<div class="field">
							<label>Retirar em</label>
							<input type="text" size="10" name="address" id="team-create-address" class="f-input" value="<?php echo $login_manager['address']; ?>" />
						</div>
					</div>
					<div id="express-zone-express" style="display:<?php echo $team['delivery']=='express'?'block':'none'; ?>;">
						<div class="field">
							<label>Delivery preço</label>
							<input type="text" size="10" name="fare" id="team-create-fare" class="number" value="<?php echo intval($team['fare']); ?>" maxLength="6" datatype="money" require="true" />
							<span class="inputtip">Entrega gratis 0</span>
						</div>
						<div class="field">
							<label>Endereço</label>
							<div style="float:left;"><textarea cols="45" rows="5" name="express" id="team-create-express" class="xheditor-simple"><?php echo $team['express']; ?></textarea></div>
						</div>
					</div>
					<div class="act">
						<input type="submit" value="OK, Cadastrar" name="commit" id="leader-submit" class="formbutton"/>
					</div>
				</form>
                </div>
            </div>
            <div class="box-bottom"></div>
        </div>
	</div>

<div id="sidebar">
</div>

</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("manage_footer");?>

