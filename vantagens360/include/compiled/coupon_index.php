<?php include template("header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo current_account('/coupon/index.php'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>Minha Conta</h2>
                    <ul class="filter">
						<li class="label">Categoria:  </li>
						<?php echo current_coupon_sub('index'); ?>
					</ul>
				</div>
                <div class="sect">
					<?php if($selector=='index'&&!$coupons){?>
					<div class="notice">Não existem <?php echo $INI['system']['couponname']; ?> utilizaveis</div>
					<?php }?>
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
						<tr><th width="240">Item</th><th width="100" nowrap>Numero do CUPOM</th><th width="60" nowrap>Senha</th><th width="100" nowrap>Data de validade</th><th width="80">Operação</th></tr>
					<?php if(is_array($coupons)){foreach($coupons AS $index=>$one) { ?>
						<tr <?php echo $index%2?'':'class="alt"'; ?>>
							<td><a class="deal-title" href="/team.php?id=<?php echo $one['team_id']; ?>" target="_blank"><?php echo $teams[$one['team_id']]['title']; ?></a></td>
							<td><?php echo $one['id']; ?></td>
							<td><?php echo $one['secret']; ?></td>
							<td><?php echo date('d-m-Y', $one['expire_time']); ?></td>
							<td><a href="/coupon/print.php?id=<?php echo $one['id']; ?>">Imprimir</a></td>
						</tr>
					<?php }}?>
						<tr><td colspan="5"><?php echo $pagestring; ?></td></tr>
                    </table>
				</div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
    <div id="sidebar">
		<?php include template("block_side_aboutcoupon");?>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>

