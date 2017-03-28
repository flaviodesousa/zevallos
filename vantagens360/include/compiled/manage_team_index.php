<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_team($selector); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
				<?php if($selector=='failure'){?>
                    <h2>Ofertas que falharam</h2>
				<?php } else if($selector=='success') { ?>
                    <h2>Ofertas validas</h2>
				<?php } else { ?>
                    <h2>Oferta atual</h2>
				<?php }?>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="40">ID</th><th width="400">Item</th><th width="80" nowrap>Categoria</th><th width="100">Data</th><th width="50">Completa</th><th width="60" nowrap>Preço</th><th width="140">Operação</th></tr>
					<?php if(is_array($teams)){foreach($teams AS $index=>$one) { ?>
					<?php $oldstate = $one['state']; ?>
					<?php $one['state'] = team_state($one); ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="team-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['id']; ?></a></td>
						<td><a class="deal-title" href="/team.php?id=<?php echo $one['id']; ?>" target="_blank"><?php echo $one['title']; ?></a></td>
						<td nowrap><?php echo $cities[$one['city_id']]['name']; ?><br/><?php echo $groups[$one['group_id']]['name']; ?></td>
						<td nowrap><?php echo date('d-m-Y',$one['begin_time']); ?><br/><?php echo date('d-m-Y',$one['end_time']); ?></td>
						<td nowrap><?php echo $one['now_number']; ?>/<?php echo $one['min_number']; ?></td>
						<td nowrap><span class="money"><?php echo $currency; ?></span><?php echo moneyit($one['team_price']); ?><br/><span class="money"><?php echo $currency; ?></span><?php echo moneyit($one['market_price']); ?></td>
						<td class="op" nowrap><a href="/ajax/manage.php?action=teamdetail&id=<?php echo $one['id']; ?>" class="ajaxlink">Detalhes</a>｜<a href="/manage/team/edit.php?id=<?php echo $one['id']; ?>">Editar</a>｜<a href="/ajax/manage.php?action=teamremove&id=<?php echo $one['id']; ?>" class="ajaxlink" ask="Certeza?" >Deletar</a><?php if($one['close_time']&&in_array($one['state'],array('success','soldout'))){?>｜<a href="/manage/team/down.php?id=<?php echo $one['id']; ?>" target="_blank">Download</a><?php }?></td>
					</tr>
					<?php }}?>
					<tr><td colspan="7"><?php echo $pagestring; ?></tr>
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

