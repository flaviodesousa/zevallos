<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="partner">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_system('index'); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Configurações basicas</h2></div>
                <div class="sect">
                    <form method="post">
						<div class="wholetip clear"><h3>1. Basico</h3></div>
                        <div class="field">
                            <label>Nome do site</label>
                            <input type="text" size="30" name="system[sitename]" class="f-input" value="<?php echo $INI['system']['sitename']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Titulo do site</label>
                            <input type="text" size="30" name="system[sitetitle]" class="f-input" value="<?php echo $INI['system']['sitetitle']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Abreviação</label>
                            <input type="text" size="30" name="system[abbreviation]" class="f-input" value="<?php echo $INI['system']['abbreviation']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Nome do CUPOM</label>
                            <input type="text" size="30" name="system[couponname]" class="f-input" value="<?php echo $INI['system']['couponname']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Simbolo da moeda</label>
                            <input type="text" size="30" name="system[currency]" class="number" value="<?php echo $INI['system']['currency']; ?>"/>
						</div>
                        <div class="field">
                            <label>Valor pago ao convites</label>
                            <input type="text" size="30" name="system[invitecredit]" class="number" value="<?php echo abs(intval($INI['system']['invitecredit'])); ?>"/>
							<span class="inputtip">em real</span>
						</div>
                        <div class="field">
                            <label>Confirmação por email?</label>
                            <input type="text" size="30" name="system[emailverify]" class="number" value="<?php echo abs(intval($INI['system']['emailverify'])); ?>"/>
							<span class="inputtip">1 para sim, 0 para não confirmar</span>
						</div>
                        <div class="field">
                            <label>Barra de informações de ofertas?</label>
                            <input type="text" size="30" name="system[sideteam]" class="number" value="<?php echo abs(intval($INI['system']['sideteam'])); ?>"/>
							<span class="inputtip">total, 0 para nao</span>
							<span class="hint">Mostrar informações na barra?</span>
						</div>
						<div class="wholetip clear"><h3>2. Outras</h3></div>
                        <div class="field">
                            <label>Restrição</label>
                            <input type="text" size="30" name="system[conduser]" class="number" value="<?php echo abs(intval($INI['system']['conduser'])); ?>"/><span class="inputtip">1 Decidir pelo numeros de pessoas. 0 Decidir pelo numero de pedidos pagos.</span>
						</div>
                        <div class="field">
                            <label>Download do cupom</label>
                            <input type="text" size="30" name="system[partnerdown]" class="number" value="<?php echo abs(intval($INI['system']['partnerdown'])); ?>"/><span class="inputtip">1 Parceiro pode fazer donwload de todas as informações. 0 Somente do codigo do cupom</span>
						</div>
                        <div class="field">
                            <label>Habilitar forum</label>
                            <input type="text" size="30" name="system[forum]" class="number" value="<?php echo abs(intval($INI['system']['forum'])); ?>"/><span class="inputtip">1 Abilitar. 0 Desabilitar</span>
						</div>
                        <div class="field">
                            <label>Comprimir saida</label>
                            <input type="text" size="30" name="system[gzip]" class="number" value="<?php echo abs(intval($INI['system']['gzip'])); ?>"/><span class="inputtip">1 Comprimir. 0 Normal</span>
							<span class="hint">Diminui a banda consumida no servidor.</span>
						</div>
						<div class="wholetip clear"><h3>3. Serviços ao cliete</h3></div>
                        <div class="field">
                            <label>MSN</label>
                            <input type="text" size="30" name="system[kefuqq]" class="f-input" value="<?php echo $INI['system']['kefuqq']; ?>"/>
						</div>
                        <div class="field">
                            <label>MSN</label>
                            <input type="text" size="30" name="system[kefumsn]" class="f-input" value="<?php echo $INI['system']['kefumsn']; ?>"/>
						</div>
						<div class="wholetip clear"><h3>4. Outras</h3></div>
                        <div class="field">
                            <label>Numero da EMPRESA(CNPJ).</label>
                            <input type="text" size="30" name="system[icp]" class="f-input" value="<?php echo $INI['system']['icp']; ?>"/>
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

