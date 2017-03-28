<?php
function sms_send($phone, $content) {
	global $INI;
	if (mb_strlen($content, 'UTF-8') < 20) {
		return '短信长度低于20汉字？长点吧～';
	}
	$user = $INI['sms']['user']; 
	$pass = strtolower(md5($INI['sms']['pass']));
	$content = urlEncode($content);
	$api = "http://notice.zuitu.com/sms?user={$user}&pass={$pass}&phones={$phone}&content={$content}";
	$res = Utility::HttpRequest($api);
	return trim(strval($res))=='+OK' ? true : strval($res);
}

function sms_coupon($coupon) {
	global $INI;
	$coupon_user = Table::Fetch('user', $coupon['user_id']);
	if ( $coupon['consume'] == 'Y' 
			|| $coupon['expire_time'] < strtotime(date('Y-m-d'))) {
		return $INI['system']['couponname'] . '已失效';
	}
	else if ( !Utility::IsMobile($coupon_user['mobile']) ) {
		return '请设置合法的手机号码，以便接受短信';
	}

	$team = Table::Fetch('team', $coupon['team_id']);
	$user = Table::Fetch('user', $coupon['user_id']);
	$coupon['end'] = date('Y-n-j', $coupon['expire_time']);
	$coupon['name'] = $team['product'];
	$content = render('manage_tpl_smscoupon', array(
		'coupon' => $coupon,
		'user' => $user,
	));
	$content = trim(preg_replace("/[\s]+/",'',$content));
	if (true===($code=sms_send($coupon_user['mobile'], $content))) {
		Table::UpdateCache('coupon', $coupon['id'], array(
			'sms' => array('`sms` + 1'),
		));
		return true;
	}
	return $code;
}
