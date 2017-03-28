<?php

/* *********************************************************************
 *
 *	paCRUD - PHP Ajax CRUD Framework é um framework para
 *	desenvolvimento rápido de sistemas de informação web.
 *	Copyright (C) 2010 Emerson Casas Salvador <salvaemerson@gmail.com>
 *	e Odair Rubleski <orubleski@gmail.com>
 *
 *	This file is part of paCRUD.
 *
 *	paCRUD is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation; either version 3, or (at your option)
 *	any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 * ******************************************************************* */

/* Exemplo

*/

class paCRUD extends pacrudBasic {
	private $indentation;
	public $pacrudConfig;
	public $pConnection;
	
	function __construct($name,$params) {
		global $pacrudText;
		global $pacrudConfig;
		$this->pacrudConfig = $pacrudConfig;

		parent::__construct($name,$params);

		if (!isset($this->param['xmlFile'])) {
			pError($pacrudText[30],'js');
		}
		if (!isset($this->param['tableName'])) {
			$this->param['tableName'] = $name;
		}
	}
	
	private function initClientObject() {
		// trata o caminho do xmlFile (relativo e absoluto)
		if (substr($this->param['xmlFile'],0,1) == '/') {
			$ajaxFile = $this->param['xmlFile'];
		}
		else {
			$ajaxFile = $this->pacrudConfig['appWebPath'].'/'.$this->param['xmlFile'];
		}
		
		// instancia o objeto pacrudSearch no cliente
		$clientObject = $this->indentation. '<script type="text/javascript">'."\n";
		$clientObject .= $this->indentation. '	'.$this->name.' = new paCRUD(\''.$this->name.'\',\''.$ajaxFile.'\');'."\n";
		// repassa condicionalmente o pog debug para o objeto ajax
		if (isset($this->param['debug']) && $this->param['debug']) {
			$clientObject .= $this->indentation. '	'.$this->name.'.pAjax.debug = true;'."\n";
		}
		$clientObject .= $this->indentation. '</script>'."\n";
		return $clientObject;
	}
	
	public function draw($verbose) {
		$pSearch = '';
		if ($verbose) {
			echo $pSearch;
		}
		return $pSearch;
	}	

	private function doCreate($verbose) {
		if ($verbose) {
			Header('Content-type: application/xml; charset=UTF-8');
			echo $xml;
		}
		return $xml;
	}
	
	private function doRetrieve($verbose) {
		if ($verbose) {
			Header('Content-type: application/xml; charset=UTF-8');
			echo $xml;
		}
		return $xml;
	}
	
	private function doUpdate($verbose) {
		if ($verbose) {
			Header('Content-type: application/xml; charset=UTF-8');
			echo $xml;
		}
		return $xml;
	}

	private function doDelete($verbose) {
		if ($verbose) {
			Header('Content-type: application/xml; charset=UTF-8');
			echo $xml;
		}
		return $xml;
	}
	
	private function sqlCreate() {
		$sql = '';
		return $sql;
	}
	
	private function sqlRetrieve() {
		$sql = '';
		return $sql;
	}
	
	private function sqlUpdate() {
		$sql = '';
		return $sql;
	}

	private function sqlDelete() {
		$sql = '';
		return $sql;
	}

	public function autoInit() {
		if (isset($_POST[$this->name.'_action']) && $_POST[$this->name.'_action'] != '') {
			switch ($_POST[$this->name.'_action']) {
				case 'c':
					$this->doCreate();
					break;
				case 'r':
					$this->doRetrieve();
					break;
				case 'u':
					$this->doUpdate();
					break;
				case 'd':
					$this->doDelete();
					break;
				default:
					echo 'Error';
					break;
			}
		}
		else {
			$this->initClientObject();
		}
	}
}
