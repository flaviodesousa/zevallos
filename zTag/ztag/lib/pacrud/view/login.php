<?php

/* *********************************************************************
 *
 *  paCRUD - PHP Ajax CRUD Framework é um framework para
 *  desenvolvimento rápido de sistemas de informação web.
 *  Copyright (C) 2010 Emerson Casas Salvador <salvaemerson@gmail.com>
 *  e Odair Rubleski <orubleski@gmail.com>
 *
 *  This file is part of paCRUD.
 *
 *  paCRUD is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3, or (at your option)
 *  any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 * ******************************************************************* */

require_once($pacrudConfig['pacrudPath'].'/controller/inc_js_md5.php');
require_once($pacrudConfig['pacrudPath'].'/controller/inc_js_ajax.php');
require_once($pacrudConfig['pacrudPath'].'/view/loading.php');
?>

<script type="text/javascript">
	ajaxLogin = new pacrudAjax('<?php echo $pacrudConfig['pacrudWebPath']; ?>/controller/login.php');
	ajaxLogin.ajaxFormat = 'text';
	ajaxLogin.process = function() {
		document.write(this.responseText);
	}
</script>
<script type="text/javascript" src="<?php echo $pacrudConfig['pacrudWebPath'].'/view/login.js'; ?>"></script>

<?php
/*
<div style="height:50px">&nbsp;</div>
<h1><?php echo $pacrudConfig['appIdent'].' - '.$pacrudConfig['appName']; ?></h1>

<div style="height:20px">&nbsp;</div>

<table class="pacrudGridTable">
	<tr>
		<th class="pacrudGridTh" colspan="2"><?php echo $pacrudText[16]; ?></th>
	</tr>
	<tr class="pacrudGridTrOdd">
		<td class="pacrudGridTd" align="right"><?php echo $pacrudText[17]; ?>:</td>
		<td class="pacrudGridTd"></td>
	</tr>
	<tr class="pacrudGridTrEven">
		<td class="pacrudGridTd" align="right"><?php echo $pacrudText[18]; ?>:</td>
		<td class="pacrudGridTd"></td>
	</tr>
	<tr class="pacrudGridTrOdd">
		<td class="pacrudGridTd" colspan="2"></td>
	</tr>
</table>

<div style="height:50px">&nbsp;</div>

<?php
require_once($pacrudConfig['pacrudPath'].'/view/pacrud_logo.php');
?>

<p>
	<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" 
	alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
</p>
*/

$pHtmlPreLayout = file_get_contents(pGetTheme('login.php',false));

// titulo
$pHtmlPreLayout = str_replace(':appIdent:',$pacrudConfig['appIdent'],$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':appName:',$pacrudConfig['appName'],$pHtmlPreLayout);

// tabela
$inputUsername = '<input id="txtUsername" name="txtUsername" type="text" size="18" />';
$inputPassword = '<input id="txtPassword" name="txtPassword" type="password" size="18" />';
$inputSubmit = '<input type="submit" value="'.$pacrudText[19].'" onclick="goLogin(\'appPath='.$pacrudConfig['appPath'].'\')" />';
$pHtmlPreLayout = str_replace(':autentication:',$pacrudText[16],$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':username:',$pacrudText[17],$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':inputUsername:',$inputUsername,$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':password:',$pacrudText[18],$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':inputPassword:',$inputPassword,$pHtmlPreLayout);
$pHtmlPreLayout = str_replace(':submit:',$inputSubmit,$pHtmlPreLayout);

// pacrud logo
$pacrudLogo .= '<a href="http://www.pacrud.com.br">';
$pacrudLogo .= '<img src="'.$pacrudConfig['pacrudWebPath'].'/view/images/logo_small.png" alt="paCRUD" />';
$pacrudLogo .= '</a>'."\n";
if (!$pacrudConfig['showPacrudLogo']) {
	$pacrudLogo = '';
}
$pHtmlPreLayout = str_replace(':pacrudLogo:',$pacrudLogo,$pHtmlPreLayout);

// logo w3c
$w3cLogo = '<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>';
$pHtmlPreLayout = str_replace(':w3c:',$w3cLogo,$pHtmlPreLayout);

$htmlLogin = $pHtmlPreLayout;

echo $htmlLogin;
?>

<script type="text/javascript">
	focoLogin = function() {
		document.getElementById('txtUsername').focus();
	}
	window.onload = focoLogin();
</script>

<?php
include($pacrudConfig['pacrudPath'].'/view/footer.php');
?>
