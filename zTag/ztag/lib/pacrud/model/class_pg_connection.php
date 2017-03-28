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

class pacrudPgConnection {
	private $connection;
	private $err;

	public $connected;
	public $param;
	public $sqlOperator;

	function __construct($params) {
		$this->param = pParameters($params);
		$this->connected = false;
		$this->err = '';
		
		$this->sqlOperator = array();
		$this->sqlOperator['like']                  = ':field: ilike \'%:value:%\'';
		$this->sqlOperator['not like']              = 'NOT :field: ilike \'%:value:%\'';
		$this->sqlOperator['begins with']           = ':field: ilike \':value:%\'';
		$this->sqlOperator['ends with']             = ':field: ilike \'%:value:\'';
		$this->sqlOperator['not begins with']       = 'NOT :field: ilike \':value:%\'';
		$this->sqlOperator['not ends with']         = 'NOT :field: ilike \'%:value:\'';
		$this->sqlOperator['equal']                 = ':field: = \':value:\'';
		$this->sqlOperator['not equal']             = 'NOT :field: = \':value:\'';
		$this->sqlOperator['numeric equal']         = ':field: = :value:';
		$this->sqlOperator['numeric not equal']     = 'NOT :field: = :value:';
		$this->sqlOperator['less than']             = ':field: < \':value:\'';
		$this->sqlOperator['greater than']          = ':field: > \':value:\'';
		$this->sqlOperator['less than or equal']    = ':field: <= \':value:\'';
		$this->sqlOperator['greater than or equal'] = ':field: >= \':value:\'';
	}

	private function connect() {
		global $pacrudText;
		$dbHost     = $this->param['dbHost'];
		$dbPort     = $this->param['dbPort'];
		$dbName     = $this->param['dbName'];
		$dbUserName = $this->param['dbUserName'];
		$dbPassword = $this->param['dbPassword'];

		if ($this->connected()) {
			$this->disconnect();
		}

		$pgString = "host=$dbHost port=$dbPort dbname=$dbName user=$dbUserName password=$dbPassword";
		$this->connection = @pg_connect($pgString) ;
		if ($this->connection) {
			$this->connected = true;
			$this->err = '';
			return true;
		}
		else {
			$this->connected = false;
			$this->err = $pacrudText[23];
			return false;
		}
	}
	
	function getErr() {
		return $this->err;
	}

	function connected() {
		return $this->connected;
	}

	function getConnection() {
		if ($this->connected) {
			return $this->connection;
		}
		else {
			$this->connect();
			return $this->connection;
		}
	}

    function sqlQuery($sql) {
        if ($this->getConnection()) {
            $res = pg_query($this->getConnection(),$sql);
            $row = pg_fetch_row($res);
            return $row[0];
        }
        else {
            return false;
        }
    }

	function sqlXml($sql,$tableName) {
		$res = pg_query($this->getConnection(),$sql);
	
		if ($tableName == '') {
			$xmlTableName = pg_field_table($res, 0);
		}
		else {
			$xmlTableName = $tableName;
		}

		$ncols = pg_num_fields($res);
		$xml = '';

		$i = 0;
		while ($row = pg_fetch_assoc($res)) {
			$xml .= "<$xmlTableName>\n";
			for ($j = 0; $j < $ncols; ++$j) {
				$fieldName = pg_field_name($res, $j);
				$fieldValue = pg_fetch_result($res, $i, $fieldName);
				if ($fieldValue == '') {
					$xml .= "	<$fieldName>NULL</$fieldName>\n";
				}
				else {
					$xml .= "	<$fieldName>$fieldValue</$fieldName>\n";
				}
			}
			$xml .= "</$xmlTableName>\n";
			$i++;
		}
		return $xml;
	}

	function disconnect() {
		if ($this->connection) {
			pg_close($this->connection);
		}
		$this->connected = false;
	}
}
