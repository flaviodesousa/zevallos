<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="partner">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_system('pay'); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Forma de Pagamento</h2></div>
                <div class="sect">
                    <form method="post">
						<div class="wholetip clear"><h3>1. PayPal (deixe em branco caso não aceite paypal)</h3></div>
                        <div class="field">
                            <label>Conta</label>
                            <input type="text" size="30" name="paypal[mid]" class="f-input" value="<?php echo $INI['paypal']['mid']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="text" size="30" name="paypal[acc]" class="f-input" value="<?php echo $INI['paypal']['acc']; ?>"/>
                        </div>
					
						<div class="wholetip clear"><h3>2. alipay (deixe em branco caso não aceite alipay)</h3></div>
                        <div class="field">
                            <label>Conta Alipay</label>
                            <input type="text" size="30" name="alipay[mid]" class="f-input" value="<?php echo $INI['alipay']['mid']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Senha</label>
                            <input type="text" size="30" name="alipay[sec]" class="f-input" value="<?php echo $INI['alipay']['sec']; ?>"/>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input type="text" size="30" name="alipay[acc]" class="f-input" value="<?php echo $INI['alipay']['acc']; ?>"/>
                        </div>

						<div class="wholetip clear"><h3>3. Internet Banking(deixe em branco caso não aceite internet Banking)</h3></div>
                        <div class="field">
                            <label>Conta</label>
                            <input type="text" size="30" name="chinabank[mid]" class="f-input" value="<?php echo $INI['chinabank']['mid']; ?>"/>
                        </div>
                        <div class="field">
                            <label>password</label>
                            <input type="text" size="30" name="chinabank[sec]" class="f-input" value="<?php echo $INI['chinabank']['sec']; ?>"/>
                        </div>
						<div class="wholetip clear"><h3>4. Pagseguro(deixe em branco caso não aceite pagseguro)</h3></div>
                        <div class="field">
                            <label>Email</label>
                            <input type="text" size="30" name="pagseguro[mid]" class="f-input" value="<?php echo $INI['pagseguro']['mid']; ?>"/>
                        </div>

						<div class="wholetip clear"><h3>5. Outras formas de pagamento</h3></div>
                        <div class="field">
                            <label>informações de pagamento</label>
                            <div style="float:left;"><textarea cols="45" rows="5" name="other[pay]" class="f-textarea editor"><?php echo htmlspecialchars($INI['other']['pay']); ?></textarea></div>
                        </div>

                        <div class="act">
                            <input type="submit" value="Save" name="commit" class="formbutton"/>
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

<?php include template("footer");?>
