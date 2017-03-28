<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager();

$action = strval($_GET['action']);
$id = abs(intval($_GET['id']));

if ( 'orderrefund' == $action) {
	$order = Table::Fetch('order', $id);
	$rid = strtolower(strval($_GET['rid']));
	if ( $rid == 'credit' ) {
		ZFlow::CreateFromRefund($order);
	} else {
		Table::UpdateCache('order', $id, array('state' => 'unpay'));
	}
	/* team -- */
	$team = Table::Fetch('team', $order['team_id']);
	team_state($team);
	if ( $team['state'] != 'failure' ) {
		$minus = $team['conduser'] == 'Y' ? 1 : $order['quantity'];
		Table::UpdateCache('team', $team['id'], array(
					'now_number' => array( "now_number - {$minus}", ),
		));
	}
	/* card refund */
	if ( $order['card_id'] ) {
		Table::UpdateCache('card', $order['card_id'], array(
			'consume' => 'N',
			'team_id' => 0,
			'order_id' => 0,
		));
	}
	/* coupons */
	if ( in_array($team['delivery'], array('coupon', 'pickup') )) {
		$coupons = Table::Fetch('coupon', array($order['id']), 'order_id');
		foreach($coupons AS $one) Table::Delete('coupon', $one['id']);
	}

	/* order update */
	Table::UpdateCache('order', $id, array(
				'card' => 0, 
				'card_id' => '',
				'express_id' => 0,
				'express_no' => '',
				));
	Session::Set('notice', '退款成功');
	json(null, 'refresh');
}
elseif ( 'orderremove' == $action) {
	$order = Table::Fetch('order', $id);
	if ( $order['state'] != 'unpay' ) {
		json('付款订单不能删除', 'alert');
	}
	/* card refund */
	if ( $order['card_id'] ) {
		Table::UpdateCache('card', $order['card_id'], array(
			'consume' => 'N',
			'team_id' => 0,
			'order_id' => 0,
		));
	}
	Table::Delete('order', $order['id']);
	Session::Set('notice', "删除订单 {$order['id']} 成功");
	json(null, 'refresh');
}
else if ( 'ordercash' == $action ) {
	$order = Table::Fetch('order', $id);
	ZOrder::CashIt($order);
	$user = Table::Fetch('user', $order['user_id']);
	Session::Set('notice', "现金付款成功，购买用户：{$user['email']}");
	json(null, 'refresh');
}
else if ( 'teamdetail' == $action) {
	$team = Table::Fetch('team', $id);
	$partner = Table::Fetch('partner', $team['partner_id']);

	$paycount = Table::Count('order', array(
		'state' => 'pay',
		'team_id' => $id,
	));
	$buycount = Table::Count('order', array(
		'state' => 'pay',
		'team_id' => $id,
	), 'quantity');
	$onlinepay = Table::Count('order', array(
		'state' => 'pay',
		'team_id' => $id,
	), 'money');
	$creditpay = Table::Count('order', array(
		'state' => 'pay',
		'team_id' => $id,
	), 'credit');
	$cardpay = Table::Count('order', array(
		'state' => 'pay',
		'team_id' => $id,
	), 'card');
	$couponcount = Table::Count('coupon', array(
		'team_id' => $id,
	));
	$team['state'] = team_state($team);
	$subcount = Table::Count('subscribe', array( 
				'city_id' => $team['city_id'],
				));

	/* send team subscribe mail */	
	$team['noticesubscribe'] = ($team['close_time']==0&&is_manager());
	/* send success coupon */
	$team['noticesms'] = ($team['delivery']!='express')&&(in_array($team['state'], array('success', 'soldout')))&&is_manager();
	/* teamcoupon */
	$team['teamcoupon'] = ($team['noticesms']&&$buycount>$couponcount);
	$team['needline'] = ($team['noticesms']||$team['noticesubscribe']||$team['teamcoupon']);

	$html = render('manage_ajax_dialog_teamdetail');
	json($html, 'dialog');
}
else if ( 'teamremove' == $action) {
	$team = Table::Fetch('team', $id);
	$order_count = Table::Count('order', array(
		'team_id' => $id,
		'state' => 'pay',
	));
	if ( $order_count > 0 ) {
		json('本团购包含付款订单，不能删除', 'alert');
	}
	ZTeam::DeleteTeam($id);

	/* remove coupon */
	$coupons = Table::Fetch('coupon', array($id), 'team_id');
	foreach($coupons AS $one) Table::Delete('coupon', $one['id']);
	/* remove order */
	$orders = Table::Fetch('order', array($id), 'team_id');
	foreach($orders AS $one) Table::Delete('order', $one['id']);
	/* end */

	Session::Set('notice', "团购 {$id} 删除成功！");
	json(null, 'refresh');
}
else if ( 'cardremove' == $action) {
	$id = strval($_GET['id']);
	$card = Table::Fetch('card', $id);
	if (!$card) json('没有相关代金券', 'alert');
	if ($card['consume']=='Y') { json('代金券已经被使用，不能删除', 'alert'); }
	Table::Delete('card', $id);
	Session::Set('notice', "代金券 {$id} 删除成功！");
	json(null, 'refresh');
}
else if ( 'userview' == $action) {
	$user = Table::Fetch('user', $id);
	$html = render('manage_ajax_dialog_user');
	json($html, 'dialog');
}
else if ( 'usermoney' == $action) {
	$user = Table::Fetch('user', $id);
	$money = intval($_GET['money']);
	if ( ZFlow::CreateFromStore($id, $money) ) {
		$action = ($money>0) ? '线下充值' : '用户提现';
		$money = abs($money);
		json(array(
					array('data' => "{$action}{$money}元成功", 'type'=>'alert'),
					array('data' => null, 'type'=>'refresh'),
				  ), 'mix');
	}
	json('充值失败', 'alert'); 
}
else if ( 'orderexpress' == $action ) {
	$express_id = abs(intval($_GET['eid']));
	$express_no = strval($_GET['nid']);
	if (!$express_id) $express_no = null;
	Table::UpdateCache('order', $id, array(
		'express_id' => $express_id,
		'express_no' => $express_no,
	));
	json(array(
				array('data' => "修改快递信息成功", 'type'=>'alert'),
				array('data' => null, 'type'=>'refresh'),
			  ), 'mix');
}
else if ( 'orderview' == $action) {
	$order = Table::Fetch('order', $id);
	$user = Table::Fetch('user', $order['user_id']);
	$team = Table::Fetch('team', $order['team_id']);
	if ($team['delivery'] == 'express') {
		$option_express = option_category('express');
	}
	$payservice = array(
		'alipay' => '支付宝',
		'tenpay' => '财付通',
		'chinabank' => '网银在线',
		'credit' => '余额付款',
		'cash' => '线下支付',
	);
	$paystate = array(
		'unpay' => '<font color="green">未付款</font>',
		'pay' => '<font color="red">已付款</font>',
	);
	$option_refund = array(
		'credit' => '退款到账户余额',
		'online' => '其他途径已退款',
	);
	$html = render('manage_ajax_dialog_orderview');
	json($html, 'dialog');
}
else if ( 'inviteok' == $action ) {
	need_auth('admin');
	$invite = Table::Fetch('invite', $id);
	if (!$invite || $invite['pay']!='N') {
		json('非法操作', 'alert');
	}
	if(!$invite['team_id']) {
		json('没有发生购买行为，不能执行返利', 'alert');
	}
	$team = Table::Fetch('team', $invite['team_id']);
	$team_state = team_state($team);
	if (!in_array($team_state, array('success', 'soldout'))) {
		json('只有成功的团购才可以执行邀请返利', 'alert');
	}
	Table::UpdateCache('invite', $id, array(
				'pay' => 'Y', 
				'admin_id'=>$login_user_id,
				));
	$invite = Table::FetchForce('invite', $id);
	ZFlow::CreateFromInvite($invite);
	Session::Set('notice', '邀请返利操作成功');
	json(null, 'refresh');
}
else if ( 'inviteremove' == $action ) {
	need_auth('admin');
	Table::Delete('invite', $id);
	Session::Set('notice', '不合法邀请记录删除成功！');
	json(null, 'refresh');
}
else if ( 'subscriberemove' == $action ) {
	$subscribe = Table::Fetch('subscribe', $id);
	if ($subscribe) {
		ZSubscribe::Unsubscribe($subscribe);
		Session::Set('notice', "邮件地址：{$subscribe['email']}退订成功");
	}
	json(null, 'refresh');
}
else if ( 'partnerremove' == $action ) {
	$partner = Table::Fetch('partner', $id);
	$count = Table::Count('team', array('partner_id' => $id) );
	if ($partner && $count==0) {
		Table::Delete('partner', $id);
		Session::Set('notice', "商户：{$id} 删除成功");
		json(null, 'refresh');
	}
	if ( $count > 0 ) {
		json('商户有团购项目，删除失败', 'alert'); 
	}
	json('商户删除失败', 'alert'); 
}
else if ( 'noticesms' == $action ) {
	$nid = abs(intval($_GET['nid']));
	$now = time();
	$team = Table::Fetch('team', $id);
	$condition = array( 'team_id' => $id, );
	$coups = DB::LimitQuery('coupon', array(
				'condition' => $condition,
				'order' => 'ORDER BY id ASC',
				'offset' => $nid,
				'size' => 1,
				));
	if ( $coups ) {
		foreach($coups AS $one) {
			$nid++;
			sms_coupon($one);
		}
		json("X.misc.noticesms({$id},{$nid});", 'eval');
	} else {
		json($INI['system']['couponname'].'发送完毕', 'alert');
	}
}
else if ( 'noticesubscribe' == $action ) {
	$nid = abs(intval($_GET['nid']));
	$now = time();
	$team = Table::Fetch('team', $id);
	$partner = Table::Fetch('partner', $team['partner_id']);
	$city = Table::Fetch('category', $team['city_id']);
	$condition = array( 'city_id' => $team['city_id'], );
	$subs = DB::LimitQuery('subscribe', array(
				'condition' => $condition,
				'order' => 'ORDER BY id ASC',
				'offset' => $nid,
				'size' => 1,
				));
	if ( $subs ) {
		foreach($subs AS $one) {
			$nid++;
			try {
				ob_start();
				mail_subscribe($city, $team, $partner, $one);
				$v = ob_get_clean();
				if ($v) throw new Exception($v);
			}catch(Exception $e) { 
				json(array(
							array('data' => $e->getMessage(), 'type'=>'alert'),
							array('data' => "X.misc.noticenext({$id},{$nid});", 'eval'),
						  ), 'mix');
			}
			$cost = time() - $now;
			if ( $cost >= 20 ) {
				json("X.misc.noticenext({$id},{$nid});", 'eval');
			}
		}
		json("X.misc.noticenext({$id},{$nid});", 'eval');
	} else {
		json('订阅邮件发送完毕', 'alert');
	}
}
elseif ( 'categoryedit' == $action ) {
	if ($id) {
		$category = Table::Fetch('category', $id);
		if (!$category) json('无数据', 'alert');
		$zone = $category['zone'];
	} else {
		$zone = strval($_GET['zone']);
	}
	if ( !$zone ) json('请确定分类', 'alert');
	$zone = get_zones($zone);

	$html = render('manage_ajax_dialog_categoryedit');
	json($html, 'dialog');
}
elseif ( 'categoryremove' == $action ) {
	$category = Table::Fetch('category', $id);
	if (!$category) json('无此分类', 'alert');
	if ($category['zone'] == 'city') {
		$tcount = Table::Count('team', array('city_id' => $id));
		if ($tcount ) json('本类下存在团购项目', 'alert');
	}
	elseif ($category['zone'] == 'group') {
		$tcount = Table::Count('team', array('group_id' => $id));
		if ($tcount ) json('本类下存在团购项目', 'alert');
	}
	elseif ($category['zone'] == 'express') {
		$tcount = Table::Count('order', array('express_id' => $id));
		if ($tcount ) json('本类下存在订单项目', 'alert');
	}
	elseif ($category['zone'] == 'public') {
		$tcount = Table::Count('topic', array('public_id' => $id));
		if ($tcount ) json('本类下存在讨论区话题', 'alert');
	}
	Table::Delete('category', $id);
	option_category($category['zone']);
	Session::Set('notice', '删除分类成功');
	json(null, 'refresh');
}
else if ( 'teamcoupon' == $action ) {
	$team = Table::Fetch('team', $id);
	team_state($team);
	if (!$team['close_time'] || $team['now_number']<$team['min_number'])
		json('团购未结束或未达到最低成团人数', 'alert');
	$orders = DB::LimitQuery('order', array(
				'condition' => array(
					'team_id' => $id,
					'state' => 'pay',
					),
				));
	foreach($orders AS $order) {
		ZCoupon::Create($order);
	}
	json('发券成功',  'alert');
}
