<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

$source = strval($_GET['source']);
$backend = "http://notice.zuitu.com/verify.php?secret={$source}";
if (Utility::HttpRequest($backend)!=='+OK') die('-ERR');

$daytime = strtotime(date('Y-m-d'));
$condition = array( 'begin_time' =>  $daytime, );
$teams = DB::LimitQuery('team', array(
	'condition' => $condition,
));

$oa = array();
$si = array(
		'sitename' => $INI['system']['sitename'],
		'wwwprefix' => $INI['system']['wwwprefix'],
		'imgprefix' => $INI['system']['imgprefix'],
		);

foreach($teams AS $one) {
	$city = Table::Fetch('category', $one['city_id']);
	$group = Table::Fetch('category', $one['group_id']);
	$o = array();
	$o['id'] = $one['id'];
	$o['image'] = $one['image'];
	$o['image1'] = $one['image1'];
	$o['image2'] = $one['image2'];
	$o['title'] = $one['title'];
	$o['product'] = $one['product'];
	$o['team_price'] = $one['team_price'];
	$o['market_price'] = $one['market_price'];
	$o['city'] = $city['name'];
	$o['group'] = $group['name'];
	$oa[$one['id']] = $o;
}
$o = array( 'site' => $si, 'teams' => $oa );
Output::Json($o);
