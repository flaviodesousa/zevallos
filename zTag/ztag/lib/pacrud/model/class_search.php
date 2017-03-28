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

$pSearch1 = new pacrudSearch('pSearch1','title=MINHA PESQUISA,xmlFile=psearch1.php');
$pSearch1->addField('name=campo1');
$pSearch1->addField('name=campo2');
$pSearch1->autoInit();

$pSearch1->addFieldReturn('campo1','txtCampo1');
$pSearch1->makeButton(true);

*/

class pacrudSearch extends pacrudBasic {
	private $pGrid;
	private $fieldReturn;
	private $indentation;
	public $pacrudConfig;
	public $pConnection;
	public $pFilter;
	
	public $page;
	
	function __construct($name,$params) {
		global $pacrudText;
		global $pacrudConfig;
		$this->pacrudConfig = $pacrudConfig;

		parent::__construct($name,$params);
		$this->name = $name;
		$this->constructGrid();

		if (!isset($this->param['xmlFile'])) {
			pError($pacrudText[30],'js');
		}
		if (!isset($this->param['tableName'])) {
			$this->param['tableName'] = $name;
		}
		
		$this->page = 1;
		$this->fieldReturn = array();
	}
	
	public function setIndentation($indentation) {
		$this->indentation = $indentation;
		$this->pGrid->indentation = $indentation . '		';
	}

	private function constructGrid() {
		$lines = $this->pageLines();
		$this->pGrid = new pacrudGrid($this->name,$lines);
		$this->pGrid->indentation = $this->indentation . '		';
		$this->pGrid->width = '95%';
		$this->pGrid->lineEventOnData = $this->name.'.lineClick(%)';
		$this->pGrid->pointerCursorOnData = true;
	}
	
	private function pageLines() {
		if (isset($this->param['pageLines'])) {
			$lines = $this->param['pageLines'];
		}
		else {
			$lines = $this->pacrudConfig['searchLines'];
		}
		return $lines;
	}

	public function addField($params) {
		parent::addField($params);
		$this->pGrid->addColumn($params);
		$this->pFilter = new pacrudFilter($this->name,$this->field,'');
		$this->pFilter->setIndentation($this->indentation.'		');
	}

	public function addFieldReturn($fieldName,$idReturn) {
		$this->fieldReturn[] = array($fieldName,$idReturn);
		echo $this->indentation.'<script type="text/javascript">'."\n";
		echo $this->indentation. '	'.$this->name.'.addFieldReturn(\''.$fieldName.'\',\''.$idReturn."');\n";
		echo $this->indentation.'</script>'."\n";
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
		$clientObject .= $this->indentation. '	'.$this->name.' = new pacrudSearch(\''.$this->name.'\',\''.$ajaxFile.'\');'."\n";
		// repassa condicionalmente o pog debug para o objeto ajax
		if (isset($this->param['debug']) && $this->param['debug']) {
			$clientObject .= $this->indentation. '	'.$this->name.'.pAjax.debug = true;'."\n";
		}
		$clientObject .= $this->indentation. '</script>'."\n";
		return $clientObject;
	}
	
	private function makeFilters() {
		$htmlFilters = $this->pFilter->draw(false);
		
		// vinculo do pacrudFilter com o pacrudSearch
		$htmlFilters .= $this->indentation. '		<script type="text/javascript">'."\n";
		$htmlFilters .= $this->indentation. '			'.$this->name.'.pFilter = pFilter_'.$this->name.";\n";
		$htmlFilters .= $this->indentation. '		</script>'."\n";

		return $htmlFilters;
	}
	
	private function makeGrid() {
		$htmlGrid = $this->pGrid->draw(false);
		
		// passa informação dos fields do servidor para o grid
		$htmlGrid .= $this->indentation.'		<script type="text/javascript">'."\n";
		
		$htmlGrid .= $this->indentation. '			pGrid_'.$this->name.'.field = new Array(';
		for ($i=0; $i < $this->fieldCount(); $i++) {
			$htmlGrid .= '"'.$this->field[$i]['name'].'"';
			if ($i < $this->fieldCount() -1) {
				$htmlGrid .= ',';
			}
		}		
		$htmlGrid .= ');'."\n";
		$htmlGrid .= $this->indentation. '			pGrid_'.$this->name.'.xmlIdentification = \''.$this->name.'\';'."\n";
		$htmlGrid .= $this->indentation. '			pGrid_'.$this->name.'.lineEventOnData = \''.$this->pGrid->lineEventOnData.'\';'."\n";
		if ($this->pGrid->pointerCursorOnData) {
		$htmlGrid .= $this->indentation. '			pGrid_'.$this->name.'.pointerCursorOnData = true;'."\n";
		}

		// vinculo do pacrudGrid com o pacrudSearch
		$htmlGrid .= $this->indentation. '			'.$this->name.'.pGrid = pGrid_'.$this->name.";\n";
		
		$htmlGrid .= $this->indentation.'		</script>'."\n";
		return $htmlGrid;
	}
	
	private function makeGridNavigation() {
		$htmlGridNavigation = $this->indentation.'		<div id="pGridNavigation_'.$this->name.'" class="pacrudGridNavigation"></div>'."\n";
		$htmlGridNavigation .= $this->indentation.'		<br />'."\n";
		$htmlGridNavigation .= $this->indentation.'		<script type="text/javascript">'."\n";
		$htmlGridNavigation .= $this->indentation.'			pGridNavigation_'.$this->name.' = new pacrudGridNavigation(\''.$this->name.'\');'."\n";
		
		// vinculo do pacrudGridNavigation com o pacrudSearch
		$htmlGridNavigation .= $this->indentation.'			'.$this->name.'.pGridNavigation = pGridNavigation_'.$this->name.';'."\n";

		$htmlGridNavigation .= $this->indentation.'		</script>'."\n";
		return $htmlGridNavigation;
	}
	
	private function addWindow($pSearch) {
		if (isset($this->param['title'])) {
			$title = $this->param['title'];
		}
		else {
			$title = $this->name;
		}
		$pWindow = new pacrudWindow('pWindow_'.$this->name);
		$pWindow->title = $title;
		$pWindow->indentation = $this->indentation;
		$pSearchReturn = $pWindow->draw(false,$pSearch);
		
		// vinculo do pacrudWindow com o pacrudSearch
		$pSearchReturn .= $this->indentation.'<script type="text/javascript">'."\n";
		$pSearchReturn .= $this->indentation.'	'.$this->name.'.pWindow = pWindow_'.$this->name.";\n";
		// repassa parametro modal do pacrudSerch (que só faz sentido se tiver pWindow, por isso o codigo esta aqui)
		if (isset($this->param['modal'])) {
			$pSearchReturn .= $this->indentation. '			'.$this->name.'.modal = '.$this->param['modal'].";\n";
		}
		$pSearchReturn .= $this->indentation.'</script>'."\n";
		
		return $pSearchReturn;
	}
	
	public function draw($verbose) {
		global $pacrudConfig;
		require_once($this->pacrudConfig['pacrudPath'].'/controller/inc_js_search.php');

		// junta os objetos
		$pSearchInit = $this->initClientObject();
		$pSearchChids = $this->makeFilters();
		$pSearchChids .= $this->makeGrid();
		$pSearchChids .= $this->makeGridNavigation();		
		$pSearchChids = $this->addWindow($pSearchChids);

		$pSearch = $pSearchInit . $pSearchChids;
		if ($verbose) {
			echo $pSearch;
		}
		return $pSearch;
	}
	
	public function sqlCondition() {
		$fieldName = $this->pFilter->filter['fieldName'];
		$operator  = $this->pFilter->filter['operator'];
		$value     = $this->pFilter->filter['value'];

		$arrCondition = array();
		$iValue = 0;
		for ($i = 0; $i < count($fieldName); $i++) {
			if ($value[$i] != '') {
				$this->assignConnection();
				$condition = $this->pConnection->getSqlOperator($operator[$i]);
				$condition = str_replace(':field:',$fieldName[$i],$condition);
				$condition = str_replace(':value:',$value[$i],$condition);
				$arrCondition[$iValue] = $condition;
				$iValue++;
			}
		}

		$conditionOut = '';
		for ($i = 0; $i < count($arrCondition); $i++) {
			if ($i == 0) {
				$conditionOut .= ' WHERE '.$arrCondition[$i];
			}
			else {
				$conditionOut .= ' AND '.$arrCondition[$i];
			}
		}

		return $conditionOut;
	}
	
	public function sqlOrderby() {
		$fieldName = $this->pFilter->filter['fieldName'];
		$visible   = $this->pFilter->filter['visible'];
		
		$orderbyOut = '';
		$iVisible = 0;
		for ($i = 0; $i < count($fieldName); $i++) {
			if ($visible[$i]) {
				if ($iVisible == 0) {
					$orderbyOut .= $fieldName[$i];
				}
				else {
					$orderbyOut .= ','.$fieldName[$i];
				}
				$iVisible++;
			}
		}
		$orderbyOut = ' ORDER BY ' .$orderbyOut;
		return $orderbyOut;
	}
	
	public function sqlSearch() {
		$offsetNum = ($this->page - 1) * $this->pageLines();

		$tableName = $this->param['tableName'];
		$schema = pGetSchema($this->pacrudConfig['appSchema'],$this->pacrudConfig['sgdb']);

		$fields = '';
		for ($i=0; $i < $this->fieldCount(); $i++) {
			if ($fields != '') {
				$fields .= ',';
			}
			$fields .= $this->field[$i]['name'];
		}

		$offset = ' OFFSET '.$offsetNum;
		$limit = ' LIMIT '.$this->pageLines();
		$orderby = $this->sqlOrderby();
		$condition = $this->sqlCondition();
		$sqlSearch = 'SELECT '.$fields.' FROM '.$schema.$tableName.$condition.$orderby.$limit.$offset.';';
		return $sqlSearch;
	}
	
	public function sqlCount() {
		$tableName = $this->param['tableName'];
		$schema = pGetSchema($this->pacrudConfig['appSchema'],$this->pacrudConfig['sgdb']);

		$condition = $this->sqlCondition();

		$sqlCount = 'SELECT count(*) FROM '.$schema.$tableName.$condition.';';
		return $sqlCount;
	}
	
	private function assignConnection() {
		global $pConnection;
		global $pacrudConfig;
		
		if (!isset($this->connection)) {
			require_once($this->pacrudConfig['pacrudPath'].'/controller/connection.php');
			$this->pConnection = $pConnection;
		}
		else {
			$this->pConnection = $this->pConnection;
		}
	}
	
	public function makeXml($verbose) {
		$this->assignConnection();
		
		$this->page = $_POST['page'];
		$this->pFilter->loadQuery();

		$count = $this->pConnection->sqlQuery($this->sqlCount());
		$xml = $this->pConnection->sqlXml($this->sqlSearch(),$this->name);
		$xmlStatus  = '<count>'.$count.'</count>'."\n";
		$xmlStatus .= '<pageLines>'.$this->pageLines().'</pageLines>'."\n";
		$xmlStatus .= '<page>'.$this->page.'</page>';
		$xmlStatus = pXmlAddParent($xmlStatus,'pGridStatus');

		$xml .= $xmlStatus;

		$xml .= $this->pFilter->makeXmlFilter();
		
		$debugSql  = '<sql>'.$this->sqlSearch().'</sql>'."\n";
		$debugSql .= '<sqlCount>'.$this->sqlCount().'</sqlCount>';
		$debugSql = pXmlAddParent($debugSql,'debugSql');
		if (isset($this->param['debug']) && $this->param['debug']) {
			$xml .= $debugSql;
		}

		$xml = pXmlAddParent($xml,$this->pacrudConfig['appIdent']);
		if ($verbose) {
			Header('Content-type: application/xml; charset=UTF-8');
			echo $xml;
		}
		return $xml;
	}
	
	public function makeButton($verbose) {
		$iconSearch = pGetTheme('icons/pacrudSearch.png',true);
		$button = '<a href="javascript:'.$this->name.'.goSearch();"><img src="'.$iconSearch.'" alt="pacrudSearch" /></a>';
		if ($verbose) {
			echo $button;
		}
		return $button;
	}

	public function autoInit() {
		if (isset($_POST[$this->name.'_action']) && $_POST[$this->name.'_action'] == 'makeXml') {
			$this->makeXml(true);
		}
		else {
			$this->draw(true);
		}
	}
}
