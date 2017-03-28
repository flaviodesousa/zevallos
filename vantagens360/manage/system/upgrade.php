<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager(true);

$version = strval(SYS_VERSION);
$action = strval($_GET['action']);

if ( 'db' == $action ) {
	$r = zuitu_upgrade($action, $version);
	Session::Set('notice', '数据库结构升级成功，数据库已经是最新版本');
	Utility::Redirect( WEB_ROOT . '/manage/system/upgrade.php' );
}
$version_meta = zuitu_version();
$newversion = $version_meta['version'];
$software = $version_meta['software'];

$install = $version_meta['install'];
$patch = $version_meta['patch'];
$patchdesc = $version_meta['patchdesc'];
$upgrade = $version_meta['upgrade'];
$upgradedesc = $version_meta['upgradedesc'];

$isnew = ( $newversion == $version );

include template('manage_system_upgrade');
