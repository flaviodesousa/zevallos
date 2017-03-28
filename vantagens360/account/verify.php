<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

($secret = strval($_GET['code'])) || ($secret=strval($_GET['email']));

if (empty($secret)) {
	($email = Session::Get('unemail')) || ($email = $login_user['email']);
	$wwwlink = mail_zd($email);
	die(include template('account_verify'));	
}
else if ( strpos($secret, '@') ) {
	Session::Set('unemail', $secret);
	mail_sign_email($secret);
	Utility::Redirect( WEB_ROOT . '/account/verify.php');
}

$user = Table::Fetch('user', $secret, 'secret');
if ( $user ) {
	Table::UpdateCache('user', $user['id'], array(
		'enable' => 'Y',
	));
	Session::Set('notice', '恭喜！你的帐户已经通过Email验证');
	ZLogin::Login($user['id']);
	Utility::Redirect(get_loginpage(WEB_ROOT . '/index.php'));
}

Utility::Redirect(WEB_ROOT . '/index.php');
