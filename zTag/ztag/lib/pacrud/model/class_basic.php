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

/* **** exemplo

$pBasic1 = new pacrudBasic('pBasic1','');
$pBasic1->addField('myfield1');
$pBasic1->addField('myfield2','integer');
$pBasic1->draw(true);

*/

class pacrudBasic {
	public $name;
	public $field;
	public $error;
	public $param;
	private $connection;
	
	function __construct($name,$params) {
		global $pConnection;
		$this->error = '';
		$this->field = array();
		$this->name = $name;
		if ($pConnection) {
			$this->connection = $pConnection;
		}
		$this->param = pParameters($params);
	}
	
	public function fieldCount() {
		return count($this->field);
	}

	public function addField($params) {
		$param = pParameters($params);
		$name = $param['name'];
		if (isset($param['label']))
			$label = $param['label'];
		else
			$label = $param['name'];
			
		if (isset($param['size']))
			$size = $param['size'];
		else
			$size = null;
			
		if (isset($param['type']))
			$type = $param['type'];
		else
			$type = 'string';

		if (isset($param['notNull'])) {
			if ($param['notNull'] == 'false') {
				$notNull = false;
			}
			else {
				$notNull = true;
			}
		}
		else {
			$notNull = true;
		}
		
		$field = array(
		            'name' => $name,
		            'label' => $label,
		            'size' => $size,
		            'type' => $type,
		            'notNull' => $notNull
		         );
		$this->field[] = $field;
		return $field;
	}
	
	function fieldByName($name) {
		for ($i=0; $i < $this->fieldCount(); $i++) {
			if ($this->field[$i]['name'] == $name) {
				return $this->field[$i];
			}
		}
		return null;
	}
}
