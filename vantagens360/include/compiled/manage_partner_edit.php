<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="partner">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_partner(null); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Editar parceiro</h2><b style="margin-left:20px;font-size:16px;">（<?php echo $partner['title']; ?>）</b></div>
                <div class="sect">
                    <form id="login-user-form" method="post" action="/manage/partner/edit.php?id=<?php echo $partner['id']; ?>" class="validator">
					<input type="hidden" name="id" value="<?php echo $partner['id']; ?>" />
						<div class="wholetip clear"><h3>1. Informações de login</h3></div>
                        <div class="field">
                            <label>Usuário</label>
                            <input type="text" size="30" name="username" id="partner-create-username" class="f-input" value="<?php echo $partner['username']; ?>" require="true" datatype="require" />
                        </div>
                        <div class="field password">
                            <label>Senha</label>
                            <input type="text" size="30" name="password" id="settings-password" class="f-input" />
                            <span class="hint">Deixe em branco para não alterar</span>
                        </div>
						<div class="wholetip clear"><h3>2. Informações basicas</h3></div>
                        <div class="field">
                            <label>Nome</label>
                            <input type="text" size="30" name="title" id="partner-create-title" class="f-input" value="<?php echo $partner['title']; ?>" datatype="require" require="true" />
                        </div>
                        <div class="field">
                            <label>Site</label>
                            <input type="text" size="30" name="homepage" id="partner-create-homepage" class="f-input" value="<?php echo $partner['homepage']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="text" size="30" name="contact" id="partner-create-contact" class="f-input" value="<?php echo $partner['contact']; ?>"/>
						</div>
                        <div class="field">
                            <label>Endereço</label>
                            <input type="text" size="30" name="address" id="partner-create-address" class="f-input" value="<?php echo $partner['address']; ?>" datatype="require" require="true" />
						</div>
                        <div class="field">
                            <label>Tel.</label>
                            <input type="text" size="30" name="phone" id="partner-create-phone" class="f-input" value="<?php echo $partner['phone']; ?>" maxLength="12" require="true" datatype="require" />
						</div>
                        <div class="field">
                            <label>Celular</label>
                            <input type="text" size="30" name="mobile" id="partner-create-mobile" class="f-input" value="<?php echo $partner['mobile']; ?>" maxLength="11" />
						</div>
                        <div class="field">
                            <label>Localização</label>
                            <div style="float:left;"><textarea cols="45" rows="5" name="location" id="partner-create-location" class="xheditor-simple"><?php echo htmlspecialchars($partner['location']); ?></textarea></div>
						</div>
                        <div class="field">
                            <label>Outras informações</label>
                            <div style="float:left;"><textarea cols="45" rows="5" name="other" id="partner-create-other" class="xheditor-simple"><?php echo htmlspecialchars($partner['other']); ?></textarea></div>
						</div>
						<div class="wholetip clear"><h3>3. Informações bancárias</h3></div>
                        <div class="field">
                            <label>Nome do banco</label>
                            <input type="text" size="30" name="bank_name" id="partner-create-bankname" class="f-input" value="<?php echo $partner['bank_name']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Nome do cliente</label>
                            <input type="text" size="30" name="bank_user" id="partner-create-bankuser" class="f-input" value="<?php echo $partner['bank_user']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Agencia e conta</label>
                            <input type="text" size="30" name="bank_no" id="partner-create-bankno" class="f-input" value="<?php echo $partner['bank_no']; ?>"/>
                        </div>
                        <div class="act">
                            <input type="submit" value="Editar" name="commit" id="partner-submit" class="formbutton"/>
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

