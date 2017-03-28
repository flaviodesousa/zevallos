<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();

if ( $_POST ) {
	$team_id = abs(intval($_POST['team_id']));
	$service = $_POST['service'];
	$state = $_POST['state'];
	if (!$team_id || !$service || !$state) die('-ERR ERR_NO_DATA');
	
	$condition = array(
		'service' => $service,
		'state' => $state,
		'team_id' => $team_id,
	);
	$orders = DB::LimitQuery('order', array(
		'condition' => $condition,
		'order' => 'ORDER BY id DESC',
	));

	if (!$orders) die('-ERR ERR_NO_DATA');
	$team = Table::Fetch('team', $team_id);
	$name = 'order_'.date('Ymd');
	$kn = array(
		'id' => '订单号',
		'pay_id' => '支付号',
		'service' => '支付方式',
		'price' => '单价',
		'quantity' => '数量',
		'fare' => '运费',
		'origin' => '总金额',
		'money' => '支付款',
		'credit' => '余额付款',
		'state' => '支付状态',
		);

	if ( $team['delivery'] == 'express' ) {
		$kn = array_merge($kn, array(
					'realanem' => '收件人',
					'mobile' => '手机号码',
					'zipcode' => '邮政编码',
					'address' => '送货地址',
					));
	}
	$pay = array(
			'alipay' => '支付宝',
			'tenpay' => '财付通',
			'chinabank' => '网银在线',
			'credit' => '余额付款',
			'cash' => '现金支付',
			'' => '其他',
			);
	$state = array(
			'unpay' => '未支付',
			'pay' => '已支付',
			);
	$eorders = array();
	foreach( $orders AS $one ) {
		$one['fare'] = ($one['delivery'] == 'express') ? $one['fare'] : 0;
		$one['service'] = $pay[$one['service']];
		$one['price'] = $team['market_price'];
		$one['state'] = $state[$one['state']];
		$eorders[] = $one;
	}
	down_xls($eorders, $kn, $name);
}

include template('manage_market_downorder');
