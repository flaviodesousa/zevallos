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
 
/*
este arquivo contém a o controller view de instalação
*/

$pacrudConfig['pacrudPath'] = substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-12);
$pacrudConfig['pacrudWebPath'] = substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-12);

require_once('model/class_install.php');
require_once('config_default.php');

if (isset($_POST['language'])) {
	$language = $_POST['language'];
}
else {
	$language = $pacrudConfig['language'];
}

require_once($pacrudConfig['pacrudPath'].'/language/'.$language.'.php');
$pInstall = new pacrudInstall();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">

<head>
	<title>paCRUD - PHP Ajax CRUD Framework</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" media="screen" href="<?php echo $pacrudConfig['pacrudWebPath']; ?>/themes/default/style.css" />
	<link type="text/css" rel="stylesheet" media="screen" href="<?php echo $pacrudConfig['pacrudWebPath']; ?>/themes/default/style_grid.css" />
</head>

<body>

<script type="text/javascript">
	function chkCreateDatabase_click() {
	 	chkCreateDatabase = document.getElementById('createDatabase');
	 	
	 	if (chkCreateDatabase.checked == 0) {
		 	document.getElementById('dbAdminUserName').setAttribute('readonly','readonly');
		 	document.getElementById('dbAdminPassword').setAttribute('readonly','readonly');
		 	document.getElementById('createUsername').setAttribute('readonly','readonly');
		 	changeSgdb();
		}
		else {
		 	document.getElementById('dbAdminUserName').removeAttribute('readonly');
		 	document.getElementById('dbAdminPassword').removeAttribute('readonly');
		 	document.getElementById('createUsername').removeAttribute('readonly');
		 	document.getElementById('createUsername').setAttribute('checked','checked');
		 	changeSgdb();
		}
	}
	
	function changeSgdb() {
		select = document.getElementById('sgdb');
	 	chkCreateDatabase = document.getElementById('createDatabase');
	 	
		if (select.value == 'pgsql') {
			document.getElementById('dbAdminUserName').value = 'postgres';
			document.getElementById('dbAdminPassword').value = 'postgres';
			document.getElementById('dbPort').value = '5432';
			document.getElementById('appSchema').value = 'public';			
		}
		else if (select.value == 'mysql') {
			document.getElementById('dbAdminUserName').value = 'root';
			document.getElementById('dbAdminPassword').value = '';
			document.getElementById('dbPort').value = '3306';
			document.getElementById('appSchema').value = '';
		}
		
	 	if (chkCreateDatabase.checked == 0) {
		 	document.getElementById('dbAdminUserName').value = '';
		 	document.getElementById('dbAdminPassword').value = '';
		 	document.getElementById('createUsername').removeAttribute('checked');
		 }
	}
</script>
<p><a href="http://pacrud.consoli.org.br"><img src="view/images/logo_small.png" alt="paCRUD Framework" /></a></p>

<?php

	//Executa a instalação
	if ($pInstall->action == 'install') {
		$pInstall->getQuery();
		if ($pInstall->validate(true)) {
			require_once($pInstall->pacrudPath.'/model/pacrud_functions.php');
			require_once($pInstall->pacrudPath.'/model/class_pg_connection.php');
			require_once($pInstall->pacrudPath.'/model/class_mysql_connection.php');
			require_once($pInstall->pacrudPath.'/model/class_connection.php');

			$pInstall->createUsername(true);
			$pInstall->createDatabase(true);
			$pInstall->connection = new pacrudConnection(
										'sgdb='.$pInstall->sgdb.
										',dbHost='.$pInstall->dbHost.
										',dbPort='.$pInstall->dbPort.
										',dbName='.$pInstall->dbName.
										',dbUserName='.$pInstall->dbUserName.
										',dbPassword='.$pInstall->dbPassword
									);
			$pInstall->createPacrudTables(true);
			$pInstall->writeIndexFile(true);
			$pInstall->writePacrudFile(true);

			echo '<p>'.$pacrudText[12].'<br />'."\n";
			echo $pacrudText[13].'</p>'."\n";
			echo '<p><a href="'.$pInstall->appWebPath.'/'.$pInstall->afterLogin.'">'.$pacrudText[14].'</a> '.$pacrudText[15].'</p>';
		}
	}
	else {
		$pInstall->getConfigDefault();
		// exibe o formulário
		?>
		<h1>paCRUD Framework - <?php echo $pacrudText[0]; ?></h1>
		<form method="post" action="install.php">
			<table class="pacrudGridTable">
				<tr class="pacrudGridTrEven">
					<th class="pacrudGridTh" colspan="2"><b><?php echo $pacrudText[1]; ?></b><input type="hidden" name="action" value="install" /></th>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">appIdent:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="appIdent" value="<?php echo $pInstall->appIdent; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">appName:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="appName" value="<?php echo $pInstall->appName; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">pacrudPath:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="pacrudPath" value="<?php echo $pInstall->pacrudPath; ?>" readonly="readonly" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">pacrudWebPath:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="pacrudWebPath" value="<?php echo $pInstall->pacrudWebPath; ?>" readonly="readonly" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">appPath:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="appPath" value="<?php echo $pInstall->appPath; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">appWebPath:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="appWebPath" value="<?php echo $pInstall->appWebPath; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">afterLogin:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="afterLogin" value="<?php echo $pInstall->afterLogin; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">language:</td>
					<td class="pacrudGridTd" align="left">
<!--						<input type="text" size="30" name="language" value="<?php echo $pInstall->language; ?>"> -->
						<select name="language">
							<?php
								//$pInstall->pacrudPath
								$languages = $pInstall->languages();
								for ($i=0; $i < count($languages); ++$i) {
									if ($languages[$i] == $pInstall->language) {
										echo '							<option value="'.$languages[$i].'" selected="selected">'.$languages[$i].'</option>';
									}
									else {
										echo '							<option value="'.$languages[$i].'">'.$languages[$i].'</option>';
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd"><br /></td>
					<td class="pacrudGridTd" align="left"><input type="checkbox" size="30" name="showPacrudLogo"<?php if ($pInstall->showPacrudLogo) echo ' checked="checked"';?> />showPacrudLogo</td>
				</tr>
			</table>
			<p><br /></p>
			<table class="pacrudGridTable">
				<tr class="pacrudGridTrEven">
					<th class="pacrudGridTh" colspan="2"><b><?php echo $pacrudText[2]; ?></b></th>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="center">sgdb:</td>					
					<td class="pacrudGridTd" align="left">
						<select name="sgdb" id="sgdb" onchange="changeSgdb()">
							<option <?php if ($pInstall->sgdb == 'pgsql') echo 'selected="selected"';?> value="pgsql">pgsql</option>
							<option <?php if ($pInstall->sgdb == 'mysql') echo 'selected="selected"';?> value="mysql">mysql</option>
						</select>
						<br /><input name="createDatabase" id="createDatabase" type="checkbox" checked="checked" onclick="chkCreateDatabase_click();" />createDatabase
						<br /><input name="createPacrudTables" id="createPacrudTables" type="checkbox" checked="checked" />createPacrudTables
					</td>
				</tr>

				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">dbAdminUserName:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="dbAdminUserName" id="dbAdminUserName" value="postgres" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">dbAdminPassword:</td>
					<td class="pacrudGridTd" align="left"><input type="password" size="30" name="dbAdminPassword" id="dbAdminPassword" value="postgres" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">dbUserName:</td>
					<td class="pacrudGridTd" align="left">
						<input type="text" size="10" name="dbUserName" value="<?php echo $pInstall->dbUserName; ?>" />
						<input name="createUsername" id="createUsername" type="checkbox" checked="checked" />createUsername
					</td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">dbPassword:</td>
					<td class="pacrudGridTd" align="left"><input type="password" size="30" name="dbPassword" value="<?php echo $pInstall->dbPassword; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">dbHost:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="dbHost" value="<?php echo $pInstall->dbHost; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">dbPort:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="dbPort" id="dbPort" value="<?php echo $pInstall->dbPort; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">dbName:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="dbName" value="<?php echo $pInstall->dbName; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrOdd">
					<td class="pacrudGridTd" align="right">appSchema:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="appSchema" id="appSchema" value="<?php echo $pInstall->appSchema; ?>" /></td>
				</tr>
				<tr class="pacrudGridTrEven">
					<td class="pacrudGridTd" align="right">loginSchema:</td>
					<td class="pacrudGridTd" align="left"><input type="text" size="30" name="loginSchema" value="<?php echo $pInstall->loginSchema; ?>" /></td>
				</tr>
							
			</table>
			<p><br /><input type="submit" value="gerar arquivos de configuração" /></p>
		</form>
		<?php
	}
?>

</body>

</html>
