<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_misc('feedback'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>FeedBack</h2>
                    <ul class="filter">
						<li><form action="/manage/misc/feedback.php" method="get"><input type="text" name="like" value="<?php echo htmlspecialchars($like); ?>" class="h-input" />&nbsp;<select name="cate"><?php echo Utility::Option($feedcate, $cate, 'All Category'); ?></select>&nbsp;<input type="submit" value="Filter" class="formbutton"  style="padding:1px 6px;"/><form></li>
					</ul>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="200">Cliente</th><th width="80">Tipo</th><th width="360">Contexto</th><th width="80">Status</th><th width="80">Data</th><th width="100">Operação</th></tr>
					<?php if(is_array($asks)){foreach($asks AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="team-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['title']; ?><br/><?php echo $one['contact']; ?></td>
						<td nowrap><?php echo $feedcate[$one['category']]; ?></td>
						<td><?php echo htmlspecialchars($one['content']); ?></td>
						<td><?php echo $one['user_id']?$users[$one['user_id']]['username']:''; ?></td>
						<td><?php echo date('Y-n-j',$one['create_time']); ?></td>
						<td class="op" nowrap><a href="/manage/misc/feedback.php?action=r&id=<?php echo $one['id']; ?>&r=<?php echo $currefer; ?>" class="remove-record">Deletar</a><?php if(!$one['user_id']){?>｜<a href="/manage/misc/feedback.php?action=m&id=<?php echo $one['id']; ?>&r=<?php echo $currefer; ?>">Processada</a><?php }?></td>
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

