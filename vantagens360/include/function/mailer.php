<?php
function mail_custom($emails=array(), $subject, $message) {
	global $INI;
	settype($emails, 'array');

	$options = array(
		'contentType' => 'text/html',
		'encoding' => 'GBK',
	);

	$from = $INI['mail']['from'];
	$to = array_shift($emails);
	if ($INI['mail']['mail']=='mail') {
		Mailer::SendMail($from, $to, $subject, $message, $options, $emails);
	} else {
		Mailer::SmtpMail($from, $to, $subject, $message, $options, $emails);
	}
}

function mail_sign($user) {
	global $INI;
	if ( empty($user) ) return true;
	$from = $INI['mail']['from'];
	$to = $user['email'];

	$vars = array( 'user' => $user,);
	$message = render('mail_sign_verify', $vars);
	$subject = '感谢注册'.$INI['system']['sitename'].'，请验证Email以获取更多服务';

	$options = array(
		'contentType' => 'text/html',
		'encoding' => 'GBK',
	);
	if ($INI['mail']['mail']=='mail') {
		Mailer::SendMail($from, $to, $subject, $message, $options);
	} else {
		Mailer::SmtpMail($from, $to, $subject, $message, $options);
	}
}

function mail_sign_id($id) {
	$user = Table::Fetch('user', $id);
	mail_sign($user);
}

function mail_sign_email($email) {
	$user = Table::Fetch('user', $email, 'email');
	mail_sign($user);
}

function mail_repass($user) {
	global $INI;
	if ( empty($user) ) return true;
	$from = $INI['mail']['from'];
	$to = $user['email'];

	$vars = array( 'user' => $user,);
	$message = render('mail_repass', $vars);
	$subject = $INI['system']['sitename'] . '重设密码';

	$options = array(
		'contentType' => 'text/html',
		'encoding' => 'GBK',
	);
	if ($INI['mail']['mail']=='mail') {
		Mailer::SendMail($from, $to, $subject, $message, $options);
	} else {
		Mailer::SmtpMail($from, $to, $subject, $message, $options);
	}
}

function mail_subscribe($city, $team, $partner, $subscribe) 
{
	global $INI;
	$week = array('日','一','二','三','四','五','六');
	$today = date('Y年n月j日 星期') . $week[date('w')];
	$vars = array(
		'today' => $today,
		'team' => $team,
		'city' => $city,
		'subscribe' => $subscribe,
		'partner' => $partner,
		'help_email' => $INI['subscribe']['helpemail'],
		'help_mobile' => $INI['subscribe']['helpphone'],
		'notice_email' => $INI['mail']['reply'],
	);
	$message = render('mail_subscribe_team', $vars);
	$mesasge = mb_convert_encoding($mesage, 'GBK', 'UTF-8');
	$options = array(
		'contentType' => 'text/html',
		'encoding' => 'GBK',
	);
	$from = $INI['mail']['from'];
	$to = $subscribe['email'];
	$subject = $INI['system']['sitename'] . "Ofertas de Hoje：{$team['title']}";

	if ($INI['mail']['mail']=='mail') {
		Mailer::SendMail($from, $to, $subject, $message, $options);
	} else {
		Mailer::SmtpMail($from, $to, $subject, $message, $options);
	}
}
