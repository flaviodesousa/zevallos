<?php include template("header");?>
<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="referrals">
    <div id="content" class="refers">
        <div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>Convide seus amigos e ganhe bônus!</h2></div>
                <div class="sect islogin">
					<?php if($money){?>
					<p class="notice-total">Você convidou <strong><?php echo $count; ?></strong> pessoas, ganhe <strong><span class="money"><?php echo $currency; ?></span><?php echo $count*$INI['system']['invitecredit']; ?></strong> em cr&eacute;ditos. <a href="/account/refer.php">Confira sua lista</a></p>
					<?php }?>
					<div class="share-links">
                    <ul class="share-list cf">
                        <li class="site">
                            <a class="logo" href="javascript:void 0" id="referrals-share-others-link"><img src="<?php echo $INI['system']['wwwprefix']; ?>/static/css/i/logo_msn.png" /></a>
                            <p class="im">Veja o link para informar aos seus amigos:
                                <input id="share-copy-text" type="text" value="<?php echo $INI['system']['wwwprefix']; ?>/r.php?r=<?php echo $login_user_id; ?>" size="35" class="f-input" onfocus="this.select()" />
                            </p>
                        </li>
                    </ul>
					<div class="nodeal cf">
						<ul class="share-list">
							<li><a class="logo" href="<?php echo share_facebook($team); ?>" target="_blank" title="Share this deal with your friends and you can get $<?php echo $INI['system']['invitecredit']; ?> rebate"><img src="/static/css/i/logo_facebook.png" /></a><p class="link"><a href="<?php echo share_facebook($team); ?>" target="_blank" title="Invite your friends here and you will get $<?php echo $INI['system']['invitecredit']; ?> rebate ">Facebook</a></p></li>
							<li><a class="logo" href="<?php echo share_twitter($team); ?>" target="_blank" title="Share this deal with your friends and you can get $<?php echo $INI['system']['invitecredit']; ?> rebate"><img src="/static/css/i/logo_twitter.jpg" /></a><p class="link"><a href="<?php echo share_twitter($team); ?>" target="_blank" title="Invite your friends here and you will get $<?php echo $INI['system']['invitecredit']; ?> rebate ">Twitter</a></p></li>
						</ul>
						</div>
					</div>
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

