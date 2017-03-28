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
  *Este arquivo contém as funções globais do pacrud
  */

function pParameters($params) {
	$arrParams = array();
	$theArg = explode(',',$params);
	for ($i = 0; $i < count($theArg); ++$i) {
		$theRow = explode('=',$theArg[$i]);
		if (count($theRow) == 1) {
			$arrParams[$theRow[0]] = true;
		}
		else if (count($theRow) == 2) {
			$arrParams[$theRow[0]] = $theRow[1];
		}
	}
	return $arrParams;
}

function pFormatSql($value,$type) {
  switch ($type) {
	case "string":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    return "'$value'";
	  }
	  break;

	case "integer":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    return "$value";
	  }
	  break;

	case "numeric":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    return "$value";
	  }
	  break;

	case "serial":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    return "$value";
	  }
	  break;

	case "date":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    list ($dia, $mes, $ano) = split ('[/.-]', $value);
	    return "'$ano-$mes-$dia'";
	  }
	  break;

	case "timestamp":
	  if ($value == '') {
	    return "NULL";
	  }
	  else {
	    $ano    = substr($value,6,4);
	    $mes    = substr($value,3,2);
	    $dia    = substr($value,0,2);
	    $hora   = substr($value,11,2);
	    $minuto = substr($value,14,2);
	    return "'$ano-$mes-$dia $hora:$minuto:00'";
	  }
	  break;

	case "boolean":
	  if ($value == 't') {
	    return "'t'";
	  }
	  else {
	    return "'f'";
	  }
	  break;
  }
}

function pRedirect($url) {
	echo '<script type="text/javascript">parent.location = \''.$url.'\'</script>'."\n";
}

function pError($text,$stderr) {
	if ($stderr == 'html') {
		echo $text."\n";
	}
	else if ($stderr == 'js') {
		$tratedText = str_replace('\'','\\\'',$text);
		echo '<script type="text/javascript">' . "\n";
		echo '	alert(\''.$tratedText.'\')' . "\n";
		echo '</script>' . "\n";	
	}
	else {
		echo '"stderr" unknow for pacrudError';
	}
}

function pGetSchema($schema,$sgdb) {
	global $pacrudConfig;
	
	if ($sgdb != '') {
		$lsgdb = $sgdb;
	}
	else {
		$lsgdb = $pacrudConfig['sgdb'];
	}

	if ($schema == '') {
		return '';
	}
	else {
		switch ($lsgdb) {
			case 'pgsql':
				return $schema.'.';
				break;
			case 'mysql':
				return $schema.'_';
				break;
			default:
				return $schema;
				break;
		}
	}
}

function pXmlAddParent($xml,$parent) {
	$arrXml = explode("\n",$xml);
	
	$newXml = '<'.$parent.'>'."\n";
	for ($i=0; $i < count($arrXml); $i++) {
		$newXml .= '	'.$arrXml[$i]."\n";
	}
	$newXml .= '</'.$parent.'>'."\n";
	
	return $newXml;
}

function pGetTheme($fileName,$webPath) {
	global $pacrudConfig;
	
	if ($webPath) {
		$path = $pacrudConfig['pacrudWebPath'];
	}
	else {
		$path = $pacrudConfig['pacrudPath'];
	}
	
	$file = $pacrudConfig['pacrudPath'].'/themes/'.$pacrudConfig['theme'].'/'.$fileName;
	if (file_exists($file)) {
		return $path.'/themes/'.$pacrudConfig['theme'].'/'.$fileName;
	}
	else {
		return $path.'/themes/default/'.$fileName;
	}
}
