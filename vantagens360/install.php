<?php
require_once( dirname(__FILE__) . '/include/application.php');
header('Content-Type: text/html; charset=UTF-8;'); 
Session::Init();

if (is_get() ) {
	$db = array(
		'host' => 'localhost',
		'user' => 'root',
		'pass' => '',
		'name' => 'zuitu_db',
	);
	if (!is_writable(DIR_COMPILED)) {
		die( 'include/compiled/  - 目录必须设置为可写！');
	}
	die(include template('install_step'));
}
$db = $_POST['db'];
$m = mysql_connect($db['host'], $db['user'], $db['pass']);

if (!is_writable(dirname(__FILE__) . '/include/configure/') ) {
	Session::Set('error', 'include/configure/ 不可写');
	Utility::Redirect('install.php');
}

if (!is_writable(dirname(__FILE__) . '/include/data/') ) {
	Session::Set('error', 'include/data/ 不可写');
	Utility::Redirect('install.php');
}

if (!is_writable(dirname(__FILE__) . '/static/team/') ) {
	Session::Set('error', 'static/team/ 不可写');
	Utility::Redirect('install.php');
}

if (!is_writable(dirname(__FILE__) . '/static/user/') ) {
	Session::Set('error', 'static/user/ 不可写');
	Utility::Redirect('install.php');
}

if ( !$m ) {
	Session::Set('error', '错误的数据库配置');
	Utility::Redirect('install.php');
}

if ( !mysql_select_db($db['name'], $m) 
		&& !mysql_query("CREATE database `{$db['name']}`;", $m) ) {
	Session::Set('error', "选择数据库 {$db['name']} 错误，可能不存在？");
	Utility::Redirect('install.php');
}
mysql_select_db($db['name'], $m);

$dir = dirname(__FILE__);
$sql = '';
$f = file('./include/configure/db.sql');
foreach($f AS $l) {
	if ( strpos(trim($l), '--')===0 || strpos(trim($l), '/*') === 0 || !trim($l)) {
		continue;
	}
	$sql .= $l;
}

mysql_query("SET names UTF8;");
$sqls = explode(';', $sql);

foreach($sqls AS $sql) {
	mysql_query($sql, $m);
}

$PHP = array(
	'db' => $db,
);
if ( write_php_file($PHP, SYS_PHPFILE) ) {
	Session::Set('notice', '最土软件安装成功，请及时删除 install.php！');
}
Utility::Redirect('index.php');
