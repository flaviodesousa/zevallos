<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_partner('index'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>Parceiros</h2>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="40">ID</th><th width="320">Nome</th><th width="120">Email</th><th width="130">Tel.</th><th width="100">Data</th><th width="80">Operação</th></tr>
					<?php if(is_array($partners)){foreach($partners AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="team-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['id']; ?></td>
						<td style="text-align:left;"><a class="deal-title" href="/manage/partner/edit.php?id=<?php echo $one['id']; ?>"><?php echo $one['title']; ?></a></td>
						<td nowrap><?php echo $one['contact']; ?></td>
						<td nowrap><?php echo $one['phone']; ?><br/><?php echo $one['mobile']; ?></td>
						<td nowrap><?php echo date('d-m-Y',$one['create_time']); ?></td>
						<td class="op" nowrap><a href="/manage/partner/edit.php?id=<?php echo $one['id']; ?>">Editar</a>｜<a href="/ajax/manage.php?action=partnerremove&id=<?php echo $one['id']; ?>" class="ajaxlink" ask="Delete this partner?">Deletar</a></td>
					</tr>
					<?php }}?>
					<tr><td colspan="6"><?php echo $pagestring; ?></td></tr>
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

