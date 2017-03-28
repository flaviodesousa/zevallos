<?php
function get_city($ip=null) {
	global $INI;
	$cities = option_category('city');
	$ip = ($ip) ? $ip : Utility::GetRemoteIP();
	$url = "http://open.baidu.com/ipsearch/s?wd={$ip}&tn=baiduip";
	$res = mb_convert_encoding(Utility::HttpRequest($url), 'UTF-8', 'GBK');
	if ( preg_match('#来自：<b>(.+)</b>#Ui', $res, $m) ) {
		foreach( $cities AS $cid=>$cname) {
			if ( FALSE !== strpos($m[1], $cname) ) {
				return Table::Fetch('category', $cid);
			}
		}
	}
	return array();
}

function mail_zd($email) {
	global $option_mail;
	if ( ! Utility::ValidEmail($email) ) return false;
	preg_match('#@(.+)$#', $email, $m);
	$suffix = strtolower($m[1]);
	return $option_mail[$suffix];
}

global $option_gender;
$option_gender = array(
		'M' => 'M',
		'F' => 'F',
		);
global $option_pay;
$option_pay = array(
		'pay' => '已支付',
		'unpay' => '未支付',
		);
global $option_service;
$option_service = array(
		'alipay' => '支付宝',
		'tenpay' => '财付通',
		'chinabank' => '网银在线',
		'cash' => '现金支付',
		'credit' => '余额付款',
		'other' => '其他',
		);
global $option_delivery;
$option_delivery = array(
		'express' => '快递',
		'coupon' => '券',
		'pickup' => '自取',
		);
global $option_flow;
$option_flow = array(
		'buy' => '购买',
		'invite' => '邀请',
		'store' => '充值',
		'withdraw' => '提现',
		'coupon' => '返利',
		'refund' => '退款',
		'register' => '注册',
		'charge' => '充值',
		);
global $option_mail;
$option_mail = array(
		'gmail.com' => 'https://mail.google.com/',
		'163.com' => 'http://mail.163.com/',
		'126.com' => 'http://mail.126.com/',
		'qq.com' => 'http://mail.qq.com/',
		'sina.com' => 'http://mail.sina.com/',
		'sohu.com' => 'http://mail.sohu.com/',
		'yahoo.com.cn' => 'http://mail.yahoo.com.cn/',
		'yahoo.com' => 'http://mail.yahoo.com/',
		);
global $option_cond;
$option_cond= array(
		'Y' => 'O número de empresas bem-sucedidas para comprar',
		'N' => 'Compra para o número de grupos de produtos',
		);
