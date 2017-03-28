<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_order('unpay'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>Compras Não pagas</h2>
					<ul class="filter">
						<li><form action="/manage/order/unpay.php" method="get">Email：<input type="text" name="uemail" value="<?php echo htmlspecialchars($uemail); ?>" >&nbsp;<input type="submit" value="Filter" class="formbutton"  style="padding:1px 6px;"/><form></li>
					</ul>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="40">ID</th>
					<th width="440">Oferta</th>
					<th width="180">Usuário</th><th width="40">Quantidade</th><th width="50">Total</th><th width="50">Não Pago</th><th width="50">Pagar</th><th width="50">Operação</th></tr>
					<?php if(is_array($orders)){foreach($orders AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="order-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['id']; ?></td>
						<td><a class="deal-title" href="/team.php?id=<?php echo $one['team_id']; ?>" target="_blank"><?php echo $teams[$one['team_id']]['title']; ?></a></td>
						<td><?php echo $users[$one['user_id']]['email']; ?><br/><?php echo $users[$one['user_id']]['username']; ?></td>
						<td><?php echo $one['quantity']; ?></td>
						<td><span class="money"><?php echo $currency; ?></span><?php echo moneyit($one['origin']); ?></td>
						<td><span class="money"><?php echo $currency; ?></span><?php echo moneyit($one['credit']); ?></td>
						<td><span class="money"><?php echo $currency; ?></span><?php echo moneyit($one['origin']-$one['credit']); ?></td>
                        <td class="op">
                          <?php if($one['state']=='pay'){?>
                          <a href="/ajax/manage.php?action=orderview&id=<?php echo $one['id']; ?>" class="ajaxlink">Detalhes</a>
                          <?php } else if($one['state']=='unpay') { ?>
                          <a href="/ajax/manage.php?action=orderpagseguro&id=<?php echo $one['id']; ?>" class="ajaxlink" ask="Confirm this order will be paid by Pagseguro? ">PagSeguro?</a>
                          <?php }?></td>

					</tr>
					<?php }}?>
					<tr><td colspan="8"><?php echo $pagestring; ?></tr>
                    </table>
				</div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>
