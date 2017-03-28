<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="coupons">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_user('index'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>Lista de usuários</h2>
                    <ul class="filter">
						<li><form action="/manage/user/index.php" method="get">Email: <input type="text" name="like" class="h-input" value="<?php echo htmlspecialchars($like); ?>" >&nbsp;<input type="submit" value="Filtrar" class="formbutton"  style="padding:1px 6px;"/><form></li>
					</ul>
				</div>
                <div class="sect">
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="50">ID</th><th width="200">Email/Usuário</th><th width="100" nowrap> Nome/Cidade</th><th width="40">Balanço</th><th width="40">CEP</th><th width="120">IP/Data registro</th></th><th width="100">Tel.</th><th width="120">Operação</th></tr>
					<?php if(is_array($users)){foreach($users AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="team-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['id']; ?></td>
						<td><?php echo $one['email']; ?><br/><?php echo $one['username']; ?></td>
						<td><?php echo $one['realname']?$one['realname']:'----';; ?><br/><?php if($one['city_id']){?><?php echo $hotcities[$one['city_id']]['name']; ?><?php } else { ?>Não definida<?php }?></td>
						<td><span class="currency"><?php echo $currency; ?></span><?php echo moneyit($one['money']); ?></td>
						<td><?php echo $one['zipcode']; ?></td>
						<td><?php echo $one['ip']; ?><br/><?php echo date('d-m-Y H:i', $one['create_time']); ?></td>
						<td><?php echo $one['mobile']; ?></td>
						<td class="op"><a href="/ajax/manage.php?action=userview&id=<?php echo $one['id']; ?>" class="ajaxlink">Detalhes</a>｜<a href="/manage/user/edit.php?id=<?php echo $one['id']; ?>">Editar</a></td>
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

<?php include template("manage_footer");?>

