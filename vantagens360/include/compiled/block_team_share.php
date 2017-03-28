<div id="deal-share">
	<div class="deal-share-top">
		<div class="deal-share-links">
			<h4>Compartilhe: </h4>
			<ul class="cf"><li><a class="im" href="javascript:void(0);" id="deal-share-im">IM</a></li><li><a class="facebook" href="<?php echo share_facebook($team); ?>" target="_blank">Facebook</a></li><li><a class="twitter" href="<?php echo share_twitter($team); ?>" target="_blank">Twitter</a></li><li><a class="email" href="<?php echo share_mail($team); ?>" id="deal-buy-mailto">Email</a></li></ul>
		</div>
	</div>
	<div class="deal-share-fix"></div>
	<div id="deal-share-im-c">
		<div class="deal-share-im-b">
			<h3></h3>
			<p><input id="share-copy-text" type="text" value="<?php echo $INI['system']['wwwprefix']; ?>/team.php?id=<?php echo $team['id']; ?>&r=<?php echo $login_user_id; ?>" size="30" class="f-input" /> <input id="share-copy-button" type="button" value="Copy" class="formbutton" /></p>
		</div>
	</div>
</div>

