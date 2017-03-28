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
este arquivo contém a classe de instalação
*/

class pacrudInstall {
	public $action;
	public $connection;
	
	public $appIdent;
	public $appName;
	public $pacrudPath;
	public $pacrudWebPath;
	public $appPath;
	public $appWebPath;
	public $afterLogin;
	public $language;
	public $showPacrudLogo;
	public $sgdb;
	public $createDatabase;
	public $createPacrudTables;
	public $dbAdminUserName;
	public $dbAdminPassword;
	public $dbHost;
	public $dbPort;
	public $dbName;
	public $appSchema;
	public $loginSchema;
	public $dbUserName;
	public $createUsername;
	public $dbPassword;
	
	function __construct() {
		$this->pacrudPath = substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-12);
		$this->pacrudWebPath = substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-12);
				
		if (isset($_POST['action'])) {
			$this->action = $_POST['action'];
		}
		else {
			$this->action = '';
		}
	}
	
	function checkWriteAccess($verbose) {
		global $pacrudText;
		$pTest = @fopen($this->pacrudPath . '/example/index.php','w');	
		if ($pTest) {
			fclose($pTest);
			return true;
		}
		else {
			if ($verbose) {
				printf($pacrudText[4].'<br />'."\n",'example');
				echo '<p>chmod o+w '.$this->pacrudPath.'/example</p>';
				echo '<p>'.$pacrudText[5]."</p>\n";
			}
			return false;
		}
	}
	
	function writeIndexFile($verbose) {
		global $pacrudText;
		$index  ='<?php'."\n";
		$index .='include(\'pacrud.php\');'."\n";
		$index .='include($pacrudConfig[\'pacrudPath\'].\'/controller/pacrud_index.php\');';
		
		$pIndexFile = @fopen($this->pacrudPath . '/example/index.php','w');		
		fwrite($pIndexFile, $index);
		if ($verbose) {
			echo '<p>'.$pacrudText[22].' '.$this->pacrudPath.'/example/index.php</p>'."\n";
		}
		
		if ($pIndexFile) {
			fclose($pIndexFile);
			return true;
		}
		else {
			fclose($pIndexFile);
			return false;
		}
	}
	
	function writePacrudFile($verbose) {
		global $pacrudText;
		global $pacrudConfig;
		
		$pacrud  = '<?php'."\n";
		$pacrud .= "\n";
		$pacrud .= '$pacrudConfig[\'appIdent\']        = \''.$this->appIdent.'\';'."\n";
		$pacrud .= '$pacrudConfig[\'appName\']         = \''.$this->appName.'\';'."\n";
		$pacrud .= '$pacrudConfig[\'pacrudPath\']      = \''.$this->pacrudPath.'\';'."\n";
		$pacrud .= '$pacrudConfig[\'pacrudWebPath\']   = \''.$this->pacrudWebPath.'\';'."\n";
		$pacrud .= '$pacrudConfig[\'appPath\']         = \''.$this->appPath.'\';'."\n";
		$pacrud .= '$pacrudConfig[\'appWebPath\']      = \''.$this->appWebPath.'\';'."\n";
		$pacrud .= "\n";
		$pacrud .= '//'.$pacrudText[25]."\n";
		$pacrud .= 'require_once($pacrudConfig[\'pacrudPath\'] . \'/config_default.php\');'."\n";
		$pacrud .= "\n";

		require_once($this->pacrudPath.'/config_default.php');
		
		if ($pacrudConfig['afterLogin'] != $this->afterLogin)
			$pacrud .= '$pacrudConfig[\'afterLogin\'] = \''.$this->afterLogin."';\n";
		if ($pacrudConfig['language'] != $this->language)
			$pacrud .= '$pacrudConfig[\'language\'] = \''.$this->language."';\n";
		if (!$this->showPacrudLogo)
			$pacrud .= '$pacrudConfig[\'showPacrudLogo\'] = false;'."\n";
		if ($pacrudConfig['sgdb'] != $this->sgdb)
			$pacrud .= '$pacrudConfig[\'sgdb\'] = \''.$this->sgdb."';\n";
		if ($pacrudConfig['dbHost'] != $this->dbHost)
			$pacrud .= '$pacrudConfig[\'dbHost\'] = \''.$this->dbHost."';\n";
		if ($pacrudConfig['dbPort'] != $this->dbPort)
			$pacrud .= '$pacrudConfig[\'dbPort\'] = '.$this->dbPort.";\n";
		if ($pacrudConfig['dbName'] != $this->dbName)
			$pacrud .= '$pacrudConfig[\'dbName\'] = \''.$this->dbName."';\n";
		if ($pacrudConfig['appSchema'] != $this->appSchema)
			$pacrud .= '$pacrudConfig[\'appSchema\'] = \''.$this->appSchema."';\n";
		if ($pacrudConfig['loginSchema'] != $this->loginSchema)
			$pacrud .= '$pacrudConfig[\'loginSchema\'] = \''.$this->loginSchema."';\n";
		if ($pacrudConfig['dbUserName'] != $this->dbUserName)
			$pacrud .= '$pacrudConfig[\'dbUserName\'] = \''.$this->dbUserName."';\n";
		if ($pacrudConfig['dbPassword'] != $this->dbPassword)
			$pacrud .= '$pacrudConfig[\'dbPassword\'] = \''.$this->dbPassword."';\n";

		$pacrud .= "\n";
		$pacrud .= 'require_once($pacrudConfig[\'pacrudPath\'] . \'/controller/all_models.php\');'."\n";
	
		$pPacrudFile = @fopen($this->pacrudPath . '/example/pacrud.php','w');
		fwrite($pPacrudFile, $pacrud);
		if ($verbose) {
			echo '<p>'.$pacrudText[22].' '.$this->pacrudPath.'/example/pacrud.php</p>'."\n";
		}
		
		if ($pPacrudFile) {
			fclose($pPacrudFile);
			return true;
		}
		else {
			fclose($pPacrudFile);
			return false;
		}
	}
	
	function getQuery() {
		$this->appIdent           = $_POST['appIdent'];
		$this->appName            = $_POST['appName'];
		$this->appPath            = $_POST['appPath'];
		$this->appWebPath         = $_POST['appWebPath'];
		$this->afterLogin         = $_POST['afterLogin'];
		$this->language           = $_POST['language'];
		if (isset($_POST['showPacrudLogo'])) {
			$this->showPacrudLogo     = $_POST['showPacrudLogo'];
		}
		else {
			$this->showPacrudLogo     = true;
		}
		$this->sgdb               = $_POST['sgdb'];
		if (isset($_POST['createDatabase'])) {
			$this->createDatabase     = $_POST['createDatabase'];
		}
		if (isset($_POST['createPacrudTables'])) {
			$this->createPacrudTables = $_POST['createPacrudTables'];
		}
		$this->dbAdminUserName    = $_POST['dbAdminUserName'];
		$this->dbAdminPassword    = $_POST['dbAdminPassword'];
		$this->dbHost             = $_POST['dbHost'];
		$this->dbPort             = $_POST['dbPort'];
		$this->dbName             = $_POST['dbName'];
		$this->appSchema          = $_POST['appSchema'];
		$this->loginSchema        = $_POST['loginSchema'];
		$this->dbUserName         = $_POST['dbUserName'];
		$this->dbPassword         = $_POST['dbPassword'];
		$this->dbAdminUserName    = $_POST['dbAdminUserName'];
		$this->dbAdminPassword    = $_POST['dbAdminPassword'];
		if (isset($_POST['createDatabase'])) {
			$this->createUsername     = $_POST['createUsername'];
		}
	}
	
	function getConfigDefault() {
		global $pacrudConfig;
		require_once($this->pacrudPath.'/config_default.php');
		$this->appIdent        = 'paCRUD';
		$this->appName         = 'PHP Ajax CRUD Framework';
		$this->appPath         = $this->pacrudPath.'/example';
		$this->appWebPath      = $this->pacrudWebPath.'/example';		
		$this->afterLogin      = $pacrudConfig['afterLogin'];
		$this->language        = $pacrudConfig['language'];
		$this->showPacrudLogo  = $pacrudConfig['showPacrudLogo'];
		$this->sgdb            = $pacrudConfig['sgdb'];
		$this->dbHost          = $pacrudConfig['dbHost'];
		$this->dbPort          = $pacrudConfig['dbPort'];
		$this->dbName          = $pacrudConfig['dbName'];
		$this->appSchema       = $pacrudConfig['appSchema'];
		$this->loginSchema     = $pacrudConfig['loginSchema'];
		$this->dbUserName      = $pacrudConfig['dbUserName'];
		$this->dbPassword      = $pacrudConfig['dbPassword'];
	}

	function validate($verbose) {
		if (!$this->checkWriteAccess($verbose)) {
			return false;
		};
		return true;
	}

	function createUsername($verbose) {

		global $pacrudText;
		if ($this->createUsername) {
			if ($verbose) {
				echo '<p>'.$pacrudText[6].'.';
			}
			switch ($this->sgdb) {
				case 'pgsql':
					$pgString = 'host='.$this->dbHost.' port='.$this->dbPort.' dbname=postgres user='.$this->dbAdminUserName.' password='.$this->dbAdminPassword;
					$admConnection = pg_connect($pgString) or die ('<br />'.$pacrudText[23].' '.$this->dbAdminUserName.', '.$pacrudText[26].'<br />');
					$sql = 'CREATE ROLE '.$this->dbUserName.' LOGIN PASSWORD \''.$this->dbPassword.'\';';
					$res = pg_query($admConnection,$sql);
					if (!$res) {
						echo '<br />'.$pacrudText[7].".\n";
					}
					break;
				case 'mysql':
					$admConnection = mysql_connect($this->dbHost.':'.$this->dbPort,$this->dbAdminUserName,$this->dbAdminPassword);
					$admSelectDb = mysql_select_db('mysql',$admConnection);
					$sql = 'GRANT USAGE ON *.* TO '.$this->dbUserName.'@'.$this->dbHost.' IDENTIFIED BY \''.$this->dbPassword.'\'';
					$res = mysql_query($sql,$admConnection) or die(mysql_error());
					if(!$res) {
						echo '<br />'.$pacrudText[7].".\n";
					}
					break;
				default:
				   echo '<br />'.$pacrudText[27]."\n";
				   break;
			}
			if ($verbose) {
				echo '</p>'."\n";
			}
			return true;
		}
		else {
			return false;
		}
	}
	
	function createDatabase($verbose) {
		global $pacrudText;
		if ($this->createDatabase) {
			if ($verbose) {
				echo '<p>'.$pacrudText[8].'.';
			}
			switch ($this->sgdb) {
				case 'pgsql':
					$pgString = 'host='.$this->dbHost.' port='.$this->dbPort.' dbname=postgres user='.$this->dbAdminUserName.' password='.$this->dbAdminPassword;
					$admConnection = pg_connect($pgString) or die ('<br />'.$pacrudText[29].' '.$this->dbAdminUserName.', '.$pacrudText[26].'<br />');
					$sql = 'CREATE DATABASE '.$this->dbName.' WITH OWNER '.$this->dbUserName.';';
					$res = pg_query($admConnection,$sql);
					if (!$res) {
						echo '<br />'.$pacrudText[9].".\n";
					}
					break;
				case 'mysql':
					$admConnection = mysql_connect($this->dbHost.':'.$this->dbPort,$this->dbAdminUserName,$this->dbAdminPassword);
					$sql_db  = 'CREATE DATABASE '.$this->dbName.'; ';
					$res_db  = mysql_query($sql_db, $admConnection); 
					if(!$res_db) {
						echo '<br />'.$pacrudText[9].".\n";  
					}
					$sql = 'GRANT ALL PRIVILEGES ON '.$this->dbName.' .  * TO '.$this->dbUserName.'@'.$this->dbHost.' WITH GRANT OPTION; ';
					$res  = mysql_query($sql,$admConnection);
					if(!$res) {
						echo '<br />'.$pacrudText[9].".\n";	
					}
					break;
				default:
				   echo '<br />'.$pacrudText[27]."\n";
				   break;
			}
			if ($verbose) {
				echo '</p>'."\n";
			}
			return true;
		}
		else {
			return false;
		}
	}

	function createPacrudTables($verbose) {
		global $pacrudText;
		if ($this->createPacrudTables) {
			if ($verbose) {
				echo '<p>'.$pacrudText[10].'.';
			}
			switch ($this->sgdb) {
				case 'pgsql':
					// Cria o schema pacrud, onde serão alocadas as tabelas do framework
					$sql  = 'CREATE SCHEMA '.$this->loginSchema.";\n";
					
					// Tabela que armazena informações de login
					$sql .= 'CREATE TABLE '.pGetSchema($this->loginSchema,'').'syslogin'."\n";
					$sql .= '('."\n";
					$sql .= '  username character varying(30) NOT NULL,'."\n";
					$sql .= '  "password" character varying(40),'."\n";
					$sql .= '  enabled boolean NOT NULL DEFAULT true,'."\n";
					$sql .= '  CONSTRAINT syslogin_pkey PRIMARY KEY (username)'."\n";
					$sql .= ')'."\n";
					$sql .= 'WITH (OIDS=FALSE);'."\n";

					// Insere usuário admin
					$sql .= 'INSERT INTO '.pGetSchema($this->loginSchema,'').'syslogin(username, "password") VALUES (\'admin\', md5(\'admin\'));'."\n";

					$res = pg_query($this->connection->getConnection(),$sql);
					if (!$res) {
						echo '<br />'.$pacrudText[11].".\n";
					}
					break;
				case 'mysql':
					$sql  = 'CREATE TABLE '.$this->dbName.'.'.pGetSchema($this->loginSchema,'mysql').'syslogin ('."\n";
					$sql .= '  username VARCHAR(30) NOT NULL,'."\n";
					$sql .= "  password VARCHAR(40) NOT NULL,"."\n";
					$sql .= "  enabled BOOLEAN NOT NULL DEFAULT 1,"."\n"; 	
					$sql .= '  PRIMARY KEY(username)'."\n";
					$sql .= ')'."\n";
					$sql .= 'ENGINE = InnoDB;'."\n";  

					$sql_insert = "INSERT INTO ".$this->dbName.".".pGetSchema($this->loginSchema,'mysql')."syslogin(username, password) VALUES('admin', md5('admin'));"."\n";
					
					$res = mysql_query($sql, $this->connection->getConnection()) or die (mysql_error());
					if(!$res) {
						echo '<br />'.$pacrudText[11].".\n";	
					}

					$res_insert = mysql_query($sql_insert, $this->connection->getConnection()) or die (mysql_error());
					if(!$res_insert) {
						echo '<br />'.$pacrudText[11].".\n";	
					}
					break;
				default:
				   echo '<br />'.$pacrudText[27]."\n";
				   break;
			}
			if ($verbose) {
				echo '</p>'."\n";
			}
			return true;
		}
		else {
			return false;
		}
	}
	
	function languages() {
		// le o directory
		$pointer  = opendir($this->pacrudPath.'/language');
		// monta os vetores com os itens encontrados na pasta
		while ($thisFile = readdir($pointer)) {
			if ($thisFile != '.' and $thisFile != '..' and $thisFile != '.svn') {
				$files[] = substr($thisFile,0,strlen($thisFile)-4);
			}
		}
		sort($files);
		return $files;
	}
}
