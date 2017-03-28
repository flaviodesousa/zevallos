<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="<?php echo $INI['sn']['sn']; ?>">
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8">
<?php if(!$pagetitle||$request_uri=='index'){?>
	<title><?php echo $INI['system']['sitename']; ?> - Descontos em|<?php echo $city['name']; ?>|Cupons|</title>
<?php } else { ?>
	<title><?php echo $pagetitle; ?> | <?php echo $INI['system']['sitename']; ?> - Compre ofertas diáriamente |<?php echo $city['name']; ?> Descontos |<?php echo $city['name']; ?> Cupons |<?php echo $city['name']; ?>Ofertas<?php echo $INI['system']['subtitle']; ?></title>
<?php }?>
	<meta name="description" content="Buy Deals Everyday|<?php echo $city['name']; ?> Shopping |<?php echo $city['name']; ?> Voucher |<?php echo $city['name']; ?>Discount" />
	<meta name="keywords" content="<?php echo $INI['system']['sitename']; ?>, <?php echo $city['name']; ?>, <?php echo $city['name']; ?><?php echo $INI['system']['sitename']; ?>, <?php echo $city['name']; ?> Shopping, <?php echo $city['name']; ?> Voucher, <?php echo $city['name']; ?>Discount, Voucher, Discount, Shopping, groupon, Collective Buying, Team buy, Group buy" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<link href="<?php echo $INI['system']['wwwprefix']; ?>/feed.php?ename=<?php echo $city['ename']; ?>" rel="alternate" title="Subscribe" type="application/rss+xml" />
	<link rel="shortcut icon" href="/static/icon/favicon.ico" />
	<link rel="stylesheet" href="/static/css/index.css" type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">var WEB_ROOT = '<?php echo WEB_ROOT; ?>';</script>
	<script type="text/javascript">var LOGINUID= <?php echo abs(intval($login_user_id)); ?>;</script>
	<script src="/static/js/index.js" type="text/javascript"></script>
</head>
<body class="<?php echo $request_uri=='index'?'bg-alt':'newbie'; ?>">
<div id="pagemasker"></div><div id="dialog"></div>
<div id="doc">

