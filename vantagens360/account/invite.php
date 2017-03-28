<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

$pagetitle = '邀请有奖';

if (! is_login() ) {
	die(include template('account_invite_signup'));
}

$condition = array( 
		'user_id' => $login_user_id, 
		'credit > 0',
		'pay' => 'Y',
		);
$money = Table::Count('invite', $condition, 'credit');
$count = Table::Count('invite', $condition);

$team = current_team($city['id']);
if ($team) {
	die(include template('account_invite'));
}
include template('account_invite_no');
