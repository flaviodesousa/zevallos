<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

if (isset($_SESSION['partner_id'])) {
	unset($_SESSION['partner_id']);
}

Utility::Redirect( WEB_ROOT . '/biz/login.php');
