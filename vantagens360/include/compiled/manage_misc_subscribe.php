<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_misc('subscribe'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>Lista de assinante via email</h2>
					<ul class="filter">
						<li><form action="/manage/misc/subscribe.php" method="get">Cidade: <input type="text" name="cs" value="<?php echo htmlspecialchars($cs); ?>" class="h-input" />&nbsp;Email: <input type="text" name="like" value="<?php echo htmlspecialchars($like); ?>" class="h-input" />&nbsp;<input type="submit" value="Filtrar" class="formbutton"  style="padding:1px 6px;"/><form></li>
					</ul>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="350">Email</th><th width="80">Cidade</th><th width="350">Chave de segurança</th><th width="80">Operação</th></tr>
					<?php if(is_array($subscribes)){foreach($subscribes AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="team-list-id-<?php echo $one['id']; ?>">
						<td nowrap><?php echo $one['email']; ?></td>
						<td nowrap><?php echo $cities[$one['city_id']]['name']; ?></td>
						<td nowrap><?php echo $one['secret']; ?></td>
						<td class="op" nowrap><a ask="Delete or not?" href="/ajax/manage.php?action=subscriberemove&id=<?php echo $one['id']; ?>" class="ajaxlink">Deletar</a></td>
					</tr>
					<?php }}?>
					<tr><td colspan="6"><?php echo $pagestring; ?></tr>
                    </table>
				</div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("manage_footer");?>

