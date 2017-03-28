<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="partner">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_system('email'); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Configurações de EMAIL</h2></div>
                <div class="sect">
                    <form method="post">
						<div class="wholetip clear"><h3>1. Enviando</h3></div>
						<div class="field">
							<label>Opções</label>
							<div style="margin-top:5px;" id="mail-zone-div"><input type="radio" name="mail[mail]" value="smtp" <?php echo $INI['mail']['mail']!='mail'?'checked':''; ?> />&nbsp;SMTP Option&nbsp;<input type="radio" name="mail[mail]" value='mail' <?php echo $INI['mail']['mail']=='mail'?'checked':''; ?> />&nbsp;PHP Mail&nbsp;</div>
						</div>
						<div id="mail-zone-smtp" style="display:<?php echo $INI['mail']['mail']!='mail'?'block':'none'; ?>;">
                        <div class="field">
                            <label>SMTP Host</label>
                            <input type="text" size="30" name="mail[host]" class="f-input" value="<?php echo $INI['mail']['host']; ?>"/>
                        </div>
                        <div class="field">
                            <label>SMTP Porta</label>
                            <input type="text" size="30" name="mail[port]" class="number" value="<?php echo $INI['mail']['port']; ?>"/>
                        </div>
                        <div class="field">
                            <label>SSL</label>
                            <input type="text" size="30" name="mail[ssl]" class="number" value="<?php echo $INI['mail']['ssl']; ?>"/>
                            <span class="inputtip">false, ssl, tls</span>
                        </div>
                        <div class="field">
                            <label>Usuario</label>
                            <input type="text" size="30" name="mail[user]" class="f-input" value="<?php echo $INI['mail']['user']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Senha</label>
                            <input type="text" size="30" name="mail[pass]" class="f-input" value="<?php echo $INI['mail']['pass']; ?>"/>
                        </div>
						</div>
                        <div class="field">
                            <label>Email que envia</label>
                            <input type="text" size="30" name="mail[from]" class="f-input" value="<?php echo $INI['mail']['from']; ?>"/>
                            <span class="inputtip">Email que aparecera nos emails enviados pelo sistema</span>
                        </div>
                        <div class="field">
                            <label>Email para responder</label>
                            <input type="text" size="30" name="mail[reply]" class="f-input" value="<?php echo $INI['mail']['reply']; ?>"/>
                            <span class="inputtip">Respostas vão para esse email</span>
                        </div>

						<div class="wholetip clear"><h3>2. Configuração de assinantes</h3></div>
                        <div class="field">
                            <label>Telefone de ajuda.</label>
                            <input type="text" size="30" name="subscribe[helpphone]" class="f-input" value="<?php echo $INI['subscribe']['helpphone']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Email de ajuda</label>
                            <input type="text" size="30" name="subscribe[helpemail]" class="f-input" value="<?php echo $INI['subscribe']['helpemail']; ?>"/>
                        </div>

                        <div class="act">
                            <input type="submit" value="Salvar" name="commit" class="formbutton"/>
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

