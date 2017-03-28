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

$pFilter1 = new pacrudFilter('pFilter1',$field,'subscribe=link.php');
$pSearch1->draw(true);

*/

class pacrudFilter {
	private $indentation;
	public $field;
	public $pacrudConfig;
	
	function __construct($name,$field,$params) {
		global $pacrudConfig;
		$this->pacrudConfig = $pacrudConfig;

		$this->param = pParameters($params);
		$this->name = $name;

		$this->field = $field;
	}

	public function setIndentation($indentation) {
		$this->indentation = $indentation;
	}
	
	public function fieldCount() {
		return count($this->field);
	}

	public function draw($verbose) {
		$htmlFilters = $this->makeHtml();
		$htmlFilters .= $this->makeJs();
	
		if ($verbose) {
			echo $htmlFilters;
		}
		return $htmlFilters;
	}
	
	public function makeHtml() {
		global $pacrudText;
		$htmlFilters  = $this->indentation.'<div id="pFilter_'.$this->name.'">'."\n";
		$htmlFilters .= $this->indentation.'	<table class="pacrudFilter">'."\n";
		$htmlFilters .= $this->indentation.'		<tr>'."\n";
		$htmlFilters .= $this->indentation.'			<td id="pFilter_'.$this->name.'_filters" align="left">'."\n";
		$htmlFilters .= $this->indentation.'			</td>'."\n";
		$htmlFilters .= $this->indentation.'			<td id="pFilter_'.$this->name.'_controls">'."\n";
		$htmlFilters .= $this->indentation.'				<div style="text-align:center;">'."\n";
		$htmlFilters .= $this->indentation.'					<input type="submit" value="'.$pacrudText[40].'" onclick="'.$this->name.'.cmdSearch()" />'."\n";
		$htmlFilters .= $this->indentation.'					<input type="submit" value="'.$pacrudText[41].'" onclick="'.$this->name.'.cmdSearchAll()" />'."\n";
		if (isset($this->param['subscribe']) && $this->param['subscribe'] != '') {
			$htmlFilters .= $this->indentation.'					<br /><input type="submit" value="'.$pacrudText[42].'" />'."\n";
		}
		$htmlFilters .= $this->indentation.'				</div>'."\n";
		$htmlFilters .= $this->indentation.'			</td>'."\n";
		$htmlFilters .= $this->indentation.'		</tr>'."\n";
		$htmlFilters .= $this->indentation.'	</table>'."\n";
		$htmlFilters .= $this->indentation.'</div>'."\n";
		
		return $htmlFilters;
	}
	
	private function makeJs() {
		global $pacrudConfig;
		$jsFilters  = $this->indentation.'<script type="text/javascript">'."\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.' = new pacrudFilter(\'pFilter_'.$this->name.'\');'."\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.pacrudWebPath = \''.$pacrudConfig['pacrudWebPath'].'\';'."\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.parent = '.$this->name.';'."\n";

		// passa informação dos fields do servidor para o filter
		$filterName = '';
		$filterLabel = '';
		$filterType = '';
		for ($i=0; $i < $this->fieldCount(); $i++) {
			$filterName .= '"'.$this->field[$i]['name'].'"';
			if ($i < $this->fieldCount() -1) {
				$filterName .= ',';
			}
			$filterLabel .= '"'.$this->field[$i]['label'].'"';
			if ($i < $this->fieldCount() -1) {
				$filterLabel .= ',';
			}
			$filterType .= '"'.$this->field[$i]['type'].'"';
			if ($i < $this->fieldCount() -1) {
				$filterType .= ',';
			}
		}
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.fieldName  = new Array('.$filterName.");\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.fieldLabel = new Array('.$filterLabel.");\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.fieldType  = new Array('.$filterType.");\n";
		$jsFilters .= $this->indentation.'	pFilter_'.$this->name.'.draw()'."\n";
		$jsFilters .= $this->indentation.'</script>'."\n";

		return $jsFilters;
	}
	
	public function loadQuery() {
		$this->filter = array();
		if (isset($_POST['fField'])) {
			$this->filter['fieldName'] = $_POST['fField'];
		}
		else {
			$this->filter['fieldName'] = array();
		}

		if (count($this->filter['fieldName']) == 0) {
			$this->filter['fieldName'][0] = $this->field[0]['name'];
			$this->filter['operator'][0]  = '';
			$this->filter['value'][0]     = '';
			$this->filter['value2'][0]    = '';
			$this->filter['visible'][0]   = true;
		}
		else {
			$this->filter['operator']  = $_POST['fOperator'];
			$this->filter['value']     = $_POST['fValue'];
			$this->filter['value2']    = $_POST['fValue2'];
			$this->filter['visible']   = $_POST['fVisible'];
		}
	}
	
	public function makeXmlFilter() {
		$this->loadQuery();
		$xmlFilters = '';
		for ($i = 0; $i < count($this->filter['fieldName']); $i++) {
			$xmlFilter  = '<fieldName>'.$this->formatXmlData($this->filter['fieldName'][$i]).'</fieldName>'."\n";
			$xmlFilter .= '<operator>'.$this->formatXmlData($this->filter['operator'][$i]).'</operator>'."\n";
			$xmlFilter .= '<value>'.$this->formatXmlData($this->filter['value'][$i]).'</value>'."\n";
			$xmlFilter .= '<value2>'.$this->formatXmlData($this->filter['value2'][$i]).'</value2>'."\n";
			$xmlFilter .= '<visible>'.$this->formatXmlData($this->filter['visible'][$i]).'</visible>';
			$xmlFilter = pXmlAddParent($xmlFilter,'pFilter');
			$xmlFilters .= $xmlFilter;
		}
		return $xmlFilters;
	}
	
	private function formatXmlData($text) {
		if ($text == '') {
			$textOut = 'NULL';
		}
		else {
			$textOut = $text;
		}
		return $textOut;
	}

}
