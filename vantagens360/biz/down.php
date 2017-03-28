<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_partner();
$id = abs(intval($_GET['id']));

$partner_id = abs(intval($_SESSION['partner_id']));
$login_partner = Table::Fetch('partner', $partner_id);

$team = Table::Fetch('team', $id);
if($team['partner_id'] != $partner_id) {
	Session::Set('error', '无权访问数据');
	Utility::Redirect( WEB_ROOT  . '/biz/index.php');
}

if ( $team['delivery']=='express' ) {
	$oc = array('state' => 'pay');
	$orders = DB::LimitQuery('order', array('condition'=>$oc));
	$xls[] = "姓名\t电话\t地址";
	foreach($orders As $o) {
		$xls[] = "{$o['realname']}\t'{$o['mobile']}\t{$o['address']}";
	}
	$xls = join("\n", $xls);
	header('Content-Disposition: attachment; filename="team'.$id.'.xls"');
	die(mb_convert_encoding($xls,'GBK','UTF-8'));
}
else {
	$cc = array(
		'team_id' => $id,
		);
	$coupons = DB::LimitQuery('coupon', array('condition'=>$cc));
	$users = Table::Fetch('user', Utility::GetColumn($coupons, 'user_id'));

	$kn = array(
		'email' => '用户Email',
		'mobile' => '手机号码',
		'id' => $INI['system']['couponname']."编号",
	);
	if ( abs(intval($INI['system']['partnerdown']))) {
		$kn['secret'] = '消费密码';
	}
	foreach($coupons As $kid=>$o) {
		$u = $users[$o['user_id']];
		$o['email'] = $u['email'];
		$o['mobile'] = $u['mobile'];
		$coupons[$kid] = $o;
	}

	$name = "teamcoupon_{$id}";
	down_xls($coupons, $kn, $name);
}
