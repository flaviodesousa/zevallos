<?php include template("html_header");?>

<div id="hdw">
	<div id="hd">
		<div id="logo"><a href="/index.php" class="link"><img src="/static/css/i/logo.png" /></a></div>
		<div class="guides">
			<div class="city">
				<h2><?php echo $city['name']; ?></h2>
			</div>
			<?php if(count($hotcities)>1){?>
			<div id="guides-city-change" class="change">Mudar cidade</div>
			<div id="guides-city-list" class="city-list">
				<ul><?php echo current_city($city['ename'], $hotcities); ?></ul>
			</div>
			<?php }?>
			<?php if(is_manager()){?>
			<li><a href="/manage/index.php">Administração</a></li>
			<?php }?>
		</div>
		<ul class="nav cf"><?php echo current_frontend(); ?></ul>
		<div class="refer">&raquo;&nbsp;<a href="/subscribe.php">Receba as ofertas</a>&nbsp;&nbsp;&nbsp;&raquo;<a href="/account/invite.php">Convide</a>&nbsp;&nbsp;&nbsp;&raquo;<a id="verify-coupon-id" href="javascript:;">Verificar <?php echo $INI['system']['couponname']; ?></a>
		</div>
		<?php if($login_user){?>
		<div class="logins">
			<ul id="account">
				<li class="username">Bem vindo, <?php echo $login_user['username']; ?>!</li>
				<li class="account"><a href="/order/index.php" id="myaccount" class="account">Minha Conta</a></li>
				<li class="logout"><a href="/account/logout.php">Sair</a></li>
			</ul>
			<div class="line islogin"></div>
		</div>
		<ul id="myaccount-menu">
			<li><a href="/order/index.php">Minhas compras</a></li>
			<li><a href="/coupon/index.php">Meus <?php echo $INI['system']['couponname']; ?></a></li>
			<li><a href="/account/settings.php">Configuração</a></li>
		</ul>
		<?php } else { ?>
		<div class="logins">
			<ul id="account">
				<li class="login"><a href="/account/login.php">Login</a></li>
				<li class="signup"><a href="/account/signup.php">Registrar </a></li>
				<li class="fconnectC" style="width: 108px; margin-top: 8px;">
					<div id="user">
					 <fb:login-button onlogin="update_user_box();go_get_session();"></fb:login-button>
					 <script type="text/javascript"> function update_user_box() {

	  var user_box = document.getElementById("user");

	  // add in some XFBML. note that we set useyou=false so it doesn't display "you"
	  user_box.innerHTML =
	  "<span>"
	  + "<fb:profile-pic uid='loggedinuser' facebook-logo='true'></fb:profile-pic>"
	  + "Welcome, <fb:name uid='loggedinuser' useyou='false'></fb:name>. You are signed in with your Facebook account."
	  + "</span>";

	  // because this is XFBML, we need to tell Facebook to re-process the document
	  FB.XFBML.Host.parseDomTree();

	  }
	  function go_get_session() {
	  	window.location="facebooklogin.php";
	  }
	  </script>
	  <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
<script type="text/javascript">FB.init("<?php echo $facebook_api_key; ?>","xd_receiver.htm", {"ifUserConnected" : update_user_box} );</script>
</div>
				</li>
			</ul>
			<div class="line "></div>
		</div>
		<?php }?>
	</div>
</div>

<?php if($session_notice=Session::Get('notice',true)){?>
<div class="sysmsgw" id="sysmsg-success"><div class="sysmsg"><p><?php echo $session_notice; ?></p><span class="close">Fechar</span></div></div>
<?php }?>
<?php if($session_notice=Session::Get('error',true)){?>
<div class="sysmsgw" id="sysmsg-error"><div class="sysmsg"><p><?php echo $session_notice; ?></p><span class="close">Fechar</span></div></div>
<?php }?>

