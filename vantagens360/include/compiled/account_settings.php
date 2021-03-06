<?php include template("header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="settings">
	<div class="dashboard" id="dashboard">
		<ul><?php echo current_account('/account/settings.php'); ?></ul>
	</div>
    <div id="content" class="clear settings-box">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Configurações</h2></div>
                <div class="sect">
                    <form id="settings-form" method="post" action="/account/settings.php" enctype="multipart/form-data" class="validator">
						<div class="wholetip clear"><h3>1. Informações</h3></div>
                        <div class="field email">
                            <label>Email</label>
                            <input type="text" size="30" name="email" id="settings-email-address" class="f-input readonly" readonly="readonly" value="<?php echo $login_user['email']; ?>" />
                        </div>
                        <div class="field">
                            <label>Avatar</label>
							<?php if($login_user['avatar']){?>
							<img src="<?php echo user_image($login_user['avatar']); ?>" style="float:left;margin-top:-10px;margin-right:10px;"/>
							<?php }?>
                            <input type="file" size="30" name="upload_image" id="settings-avatar" class="f-input" />
                            <span class="hint">Tamanho máximo 512K.</span>
                        </div>
                        <div class="field username">
                            <label>Usu&aacute;rio</label>
                            <input type="text" size="30" name="username" id="settings-username" class="f-input" value="<?php echo $login_user['username']; ?>" require="true" datatype="limit" min="2" max="16" maxLength="16"/>
                        </div>
                        <div class="field password">
                            <label>Nova senha</label>
                            <input type="password" size="30" name="password" id="settings-password" class="f-input" />
                            <span class="hint">Deixe em branco para não alterar a senha</span>
                        </div>
                        <div class="field password">
                            <label>Confirmar</label>
                            <input type="password" size="30" name="password2" id="settings-password-confirm" class="f-input" />
                        </div>
                        <div class="field password">
                            <label>Sexo</label>
							<select name="gender" class="f-city"><?php echo Utility::Option($option_gender, $login_user['gender']); ?></select>
                        </div>
						<div class="wholetip clear"><h3>3. Outros </h3></div>
                        <div class="field mobile">
                            <label>Celular</label>
                            <input type="text" size="30" name="mobile" id="settings-mobile" class="number" value="<?php echo $login_user['mobile']; ?>" /><span class="inputtip">Celular é muito importante.</span>
                        </div>
                        <div class="field password">
                            <label>MSN</label>
                            <input type="text" size="30" name="qq" id="settings-qq" class="number" value="<?php echo $login_user['qq']; ?>" />
                        </div>
						<div class="field city">
                            <label>Cidade</label>
							<select name="city_id" class="f-city"><?php echo Utility::Option(Utility::OptionArray($hotcities, 'id', 'name'), $login_user['city_id']); ?><option value='0'>Outra</option></select>
                        </div>
						<div class="wholetip clear"><h3>2. Informações de entrega</h3></div>
                        <div class="field username">
                            <label>Nome real</label>
                            <input type="text" size="30" name="realname" id="settings-realname" class="f-input" value="<?php echo $login_user['realname']; ?>" />
                        </div>
                        <div class="field">
                            <label>CEP</label>
                            <input type="text" maxLength=6 size="10" name="zipcode" id="settings-zipcode" class="f-input number" value="<?php echo $login_user['zipcode']; ?>" />
                        </div>
                        <div class="field username">
                            <label>Endereço</label>
                            <input type="text" size="30" name="address" id="settings-address" class="f-input" value="<?php echo $login_user['address']; ?>" />
                            <span class="hint">Informe RUA BAIRRO E N&Uacute;MERO"</span>
                        </div>
                        <div class="clear"></div>
                        <div class="act">
                            <input type="submit" value="Alterar" name="commit" id="settings-submit" class="formbutton"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
    <div id="sidebar" class="rail">
		<?php include template("block_side_credit");?>
		<?php include template("block_side_credittip");?>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>

