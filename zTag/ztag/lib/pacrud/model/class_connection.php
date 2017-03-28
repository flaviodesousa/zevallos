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

class pacrudConnection {
	private $connection;
	private $param;

	function __construct($params) {
		$this->param = pParameters($params);
		$this->err = '';

		if ($this->param['sgdb'] == 'pgsql') {
			$this->connection = new pacrudPgConnection($params);
		} 
		else if($this->param['sgdb'] == 'mysql') {
			$this->connection = new pacrudMysqlConnection($params);								
		}
	}
	
	function getErr() {
		return $this->connection->getErr();
	}

	function getConnection() {
		return $this->connection->getConnection();
	}

	function connected() {
		return $this->connection->connected;
	}
	
	function sqlQuery($sql) {
	    return $this->connection->sqlQuery($sql);
	}
	
	function sqlXml($sql,$tableName) {
		return $this->connection->sqlXml($sql,$tableName);
	}
	
	function getSqlOperator($operator) {
		return $this->connection->sqlOperator[$operator];
	}

	function disconnect() {
		$this->connection->disconnect();
	}
}
