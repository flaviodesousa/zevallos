<?php include template("header");?>



<div id="bdw" class="bdw">

<div id="bd" class="cf">

<div id="signup">

    <div id="content" class="signup-box">

        <div class="box">

            <div class="box-top"></div>

            <div class="box-content">

                <div class="head"><h2>Registre-se </h2><span>&nbsp;ou efetue <a href="/account/login.php">Login</a></span></div>

                <div class="sect">

                    <form id="signup-user-form" method="post" action="/account/signup.php" class="validator">

                        <div class="field email">

                            <label for="signup-email-address">Email</label>

                            <input type="text" size="30" name="email" id="signup-email-address" class="f-input" value="<?php echo $_POST['email']; ?>" require="true" datatype="email" />

                            <span class="f-input-tip">Por favor informe um email correto.</span>

                            <span class="hint">Não será publicado, mas serve para login e resetar senha.</span>

                        </div>

                        <div class="field username">

                            <label for="signup-username">Usu&aacute;rio</label>

                            <input type="text" size="30" name="username" id="signup-username" class="f-input" value="<?php echo $_POST['username']; ?>" datatype="limit" require="true" min="2" max="16" maxLength="16" />

                            <span class="hint">4-16 letras</span>

                        </div>

                        <div class="field password">

                            <label for="signup-password">Senha</label>

                            <input type="password" size="30" name="password" id="signup-password" class="f-input" require="true" datatype="require" />

                            <span class="hint">Mínimo 4 letras</span>

                        </div>

                        <div class="field password">

                            <label for="signup-password-confirm">Confirme a senha</label>

                            <input type="password" size="30" name="password2" id="signup-password-confirm" class="f-input" require="true" datatype="compare" compare="signup-password" />

                        </div>

                        <div class="field">

                            <label for="signup-password-confirm">Celular</label>

                            <input type="text" size="30" name="mobile" id="signup-mobile" class="number" /><span class="inputtip">Celular é muito importante.</span>

                        </div>

						<div class="field city">

                            <label id="enter-address-city-label" for="signup-city">Sua cidade</label>

							<select name="city_id" class="f-city"><?php echo Utility::Option(Utility::OptionArray($hotcities, 'id', 'name'), $city['id']); ?><option value='0'>Outra</option></select>

                        </div>

						 <div class="field subscribe">

                            <input tabindex="3" type="checkbox" value="1" name="subscribe" id="subscribe" class="f-check" checked="checked" />

                            <label for="subscribe">Desejo receber ofertas por email.</label>

                        </div>

                        <div class="act">

                            <br /><input type="submit" value="Registrar " name="commit" id="signup-submit" class="formbutton"/>

                        </div>

                    </form>

                </div>

            </div>

            <div class="box-bottom"></div>

        </div>

    </div>

    <div id="sidebar">

        <div class="sbox">

            <div class="sbox-top"></div>

            <div class="sbox-content">

                <div class="side-tip">

                    <h2>Já tem uma conta no <?php echo $INI['system']['abbreviation']; ?>? </h2>

                    <p>Faça o <a href="/account/login.php">Login</a> por favor. </p>

                </div>

            </div>

            <div class="sbox-bottom"></div>

        </div>

    </div>

</div>

</div> <!-- bd end -->

</div> <!-- bdw end -->



<?php include template("footer");?>