<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager(true);
$pages = array(
	'help_tour' => 'Como Funciona?' . $INI['system']['abbreviation'],
	'help_faqs' => '常见问题',
	'help_zuitu' => '什么是' . $INI['system']['abbreviation'],
	'help_api' => '开发API',
	'about_contact' => 'Contato',
	'about_us' => 'SObre Nós' . $INI['system']['abbreviation'],
	'about_job' => 'Empregos',
	'about_terms' => 'Termos de Serviço',
	'about_privacy' => 'Politica de Privacidade',
);

$id = strval($_GET['id']);
if ( $id && !in_array($id, array_keys($pages))) { 
	Utility::Redirect( WEB_ROOT . "/manage/system/page.php");
}
$n = Table::Fetch('page', $id);

if ( $_POST ) {
	$table = new Table('page', $_POST);
	$table->SetStrip('value');
	if ( $n ) {
		$table->SetPk('id', $id);
		$table->update( array('id', 'value') );
	} else {
		$table->insert( array('id', 'value') );
	}
	Session::Set('notice', "页面：{$pages[$id]}编辑成功");
	Utility::Redirect( WEB_ROOT . "/manage/system/page.php?id={$id}");
}

$value = $n['value'];
include template('manage_system_page');
