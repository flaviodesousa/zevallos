<?php include template("header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="feedback">
    <div id="content" class="help">
        <div class="box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Sugestão</h2></div>
                <div class="sect">
                          <p class="notice">Sua sugestão é muito importante para nós, por favor não deixe de nós contá-la.</p>
                    <form id="feedback-user-form" method="post" action="/feedback/suggest.php" class="validator">
                        <div class="field fullname">
                            <label for="feedback-fullname">Seu nome</label>
                            <input type="text" size="30" name="title" id="feedback-fullname" class="f-input" value="<?php echo $login_user['username']; ?>" require="true" datatype="require" />
                        </div>
                        <div class="field email">
                            <label for="feedback-email-address">Email</label>
                            <input type="text" size="30" name="contact" id="feedback-email-address" class="f-input" value="<?php echo $login_user['email']; ?>" require="true" datatype="require" />
                            <span class="hint">Seu email é muito importante para que possamos entrar em contato.</span>
                        </div>
                        <div class="field suggest">
                            <label for="feedback-suggest">Mensagem</label>
                            <textarea cols="30" rows="5" name="content" id="feedback-suggest" class="xheditor-simple" require="true" datatype="require"></textarea>
                        </div>
                        <div class="clear"></div>
                        <div class="act">
                            <input type="submit" value="Enviar" name="commit" id="feedback-submit" class="formbutton"/>
                        </div>
                    </form>
                                </div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>

