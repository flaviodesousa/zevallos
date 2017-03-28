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



*/

class pacrudMenu {
	public $objName;
	public $connection;
	public $pacrudConfig;
	public $indentation;
	
	function __construct($objName,$params) {
		global $pConnection;
		global $pacrudConfig;
		$this->objName = $objName;

		if ($pConnection) {
			$this->connection = $pConnection;
		}
		$this->param = pParameters($params);
		$this->pacrudConfig = $pacrudConfig;
		
		if (!isset($this->param['xmlFile'])) {
			if (file_exists('menu.xml')) {
				$this->param['xmlFile'] = 'menu.xml';
			}
			else if (file_exists('menu_xml.php')) {
				$this->param['xmlFile'] = 'menu_xml.php';
			}
			else {
				$this->param['xmlFile'] = $pacrudConfig['pacrudPath'].'/view/menu_xml.php';
			}
		}
		$this->indentation = '';
	}
	
	private function extractIcon($node) {
		if (isset($node->icon)) {		
			$icon = '<img src="'.$node->icon.'" alt="icon" /> ';
		}
		else {
			$icon = '';	
		}
		return $icon;
	}

	private function extractLink($node) {
		if (isset($node->link)) {		
			$link = $node->link;
		}
		else {
			$link = '';	
		}
		return $link;
	}
	
	private function drawMenu($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);
				
		$menu  = $this->indentation.'<div class="pacrudMenu" onclick="'.$this->objName.'.onClick(\''.$arrParam['id'].
		                                          '\',\''.$link.'\')">'.$icon.$arrParam['label'].'</div>'."\n";
		$menu .= $this->indentation.'<div id="'.$this->objName.'_'.$arrParam['id'].'">'."\n";
		return $menu;
	}

	private function drawMenuL1($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);
		
		$menuL1  = $this->indentation.'	<div class="pacrudMenuL1" onclick="'.$this->objName.'.onClick(\''.
		              $arrParam['id'].'\',\''.$link.'\')">'.$icon.$arrParam['label'].'</div>'."\n";
		$menuL1 .= $this->indentation.'	<div id="'.$this->objName.'_'.$arrParam['id'].'">'."\n";
		return $menuL1;
	}
	
	private function drawMenuL2($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);

		$menuL2  = $this->indentation.'		<div class="pacrudMenuL2" onclick="'.$this->objName.'.onClick(\''.
		      $arrParam['id'].'\',\''.$link.'\')">'.$icon.$arrParam['label'].'</div>'."\n";
		$menuL2 .= $this->indentation.'		<div id="'.$this->objName.'_'.$arrParam['id'].'">'."\n";
		return $menuL2;
	}
	
	private function drawMenuL3($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);

		$menuL3  = $this->indentation.'			<div class="pacrudMenuL3" onclick="'.$this->objName.'.onClick(\''.
		            $arrParam['id'].'\',\''.$link.'\')">'.$icon.$arrParam['label'].
		                                                                                            '</div>'."\n";
		$menuL3 .= $this->indentation.'			<div id="'.$this->objName.'_'.$arrParam['id'].'">'."\n";
		return $menuL3;
	}
	
	private function drawMenuL4($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);
		
		$menuL4  = $this->indentation.'				<div class="pacrudMenuL4" onclick="'.$this->objName.'.onClick(\''.
		                       $arrParam['id'].'\',\''.$link.'\')">'.
		                                                                             $icon.$arrParam['label'].'</div>'."\n";
		$menuL4 .= $this->indentation.'				<div id="'.$this->objName.'_'.$arrParam['id'].'">'."\n";
		return $menuL4;
	}
	
	private function drawMenuL5($arrParam,$node) {
		$icon = $this->extractIcon($node);
		$link = $this->extractLink($node);
		
		$menuL5  = $this->indentation.'					<div class="pacrudMenuL5" onclick="'.$this->objName.
		                                            '.onClick(\''.$arrParam['id'].'\',\''.$link.
		                                           '\')">'.
		                                                                   $icon.$arrParam['label'].'</div>'."\n";
		return $menuL5;
	}
	
	public function draw($verbose) {
		require_once($this->pacrudConfig['pacrudPath'].'/controller/inc_js_menu.php');
		$opened = array();

		$htmlMenu  = "\n";

		$htmlMenu .= $this->indentation.'<script type="text/javascript">'."\n";
		$htmlMenu .= $this->indentation.'	'.$this->objName.' = new pacrudMenu(\''.$this->objName.'\');'."\n";
		$htmlMenu .= $this->indentation.'</script>'."\n";
				
		$xml = simplexml_load_file($this->param['xmlFile']);

		$iMenu = 0;
		//menu
		foreach($xml as $value0){
			$menu = array();
			foreach($value0->attributes() as $menuAtt => $menuAttValue) {
				$menu[$menuAtt] = $menuAttValue;
			}
			if (count($value0->attributes()) > 0) {
				$htmlMenu .= $this->drawMenu($menu,$xml->menu[$iMenu]);
				if (!isset($menu['opened'])) {
					$menu['opened'] = '';
				}
				$opened[] = array($menu['id'],$menu['opened']);
			}
			$iMenu++;
		
			//level1
			$iLevel1 = 0;
			foreach($value0 as $level1Key => $level1Value) {
				$level1 = array();
				foreach($level1Value->attributes() as $level1Att => $level1AttValue) {
					$level1[$level1Att] = $level1AttValue;
				}
				
				if (count($level1Value->attributes()) > 0) {
					$htmlMenu .= $this->drawMenuL1($level1,$value0->level1[$iLevel1]);
					if (!isset($level1['opened'])) {
						$level1['opened'] = '';
					}
					$opened[] = array($level1['id'],$level1['opened']);
				}
				$iLevel1++;
							
				//level2
				$iLevel2 = 0;
				foreach($level1Value as $level2Key => $level2Value) {
					$level2 = array();
					foreach($level2Value->attributes() as $level2Att => $level2AttValue) {
						$level2[$level2Att] = $level2AttValue;
					}
					
					if (count($level2Value->attributes()) > 0) {
						$htmlMenu .= $this->drawMenuL2($level2,$level1Value->level2[$iLevel2]);
						if (!isset($level2['opened'])) {
							$level2['opened'] = '';
						}
						$opened[] = array($level2['id'],$level2['opened']);
					}
					$iLevel2++;
				
					//level3
					$iLevel3 = 0;
					foreach($level2Value as $level3Key => $level3Value) {
						$level3 = array();
						foreach($level3Value->attributes() as $level3Att => $level3AttValue) {
							$level3[$level3Att] = $level3AttValue;
						}
						
						if (count($level3Value->attributes()) > 0) {
							$htmlMenu .= $this->drawMenuL3($level3,$level2Value->level3[$iLevel3]);
							if (!isset($level3['opened'])) {
								$level3['opened'] = '';
							}
							$opened[] = array($level3['id'],$level3['opened']);
						}
						$iLevel3++;
				
						//level4
						$iLevel4 = 0;
						foreach($level3Value as $level4Key => $level4Value) {
							$level4 = array();
							foreach($level4Value->attributes() as $level4Att => $level4AttValue) {
								$level4[$level4Att] = $level4AttValue;
							}
							
							if (count($level4Value->attributes()) > 0) {
								$htmlMenu .= $this->drawMenuL4($level4,$level3Value->level4[$iLevel4]);
								if (!isset($level4['opened'])) {
									$level4['opened'] = '';
								}
								$opened[] = array($level4['id'],$level4['opened']);
							}
							$iLevel4++;
						
							//level5
							$iLevel5 = 0;
							foreach($level4Value as $level5Key => $level5Value) {
								$level5 = array();
								foreach($level5Value->attributes() as $level5Att => $level5AttValue) {
									$level5[$level5Att] = $level5AttValue;
								}
								
								if (count($level5Value->attributes()) > 0) {
									$htmlMenu .= $this->drawMenuL5($level5,$level4Value->level5[$iLevel5]);
								}
								$iLevel5++;
							}
							if (count($level4Value->attributes()) > 0) {
								$htmlMenu .= $this->indentation.'				</div>'."\n"; //menuL4
							}
						}
						if (count($level3Value->attributes()) > 0) {
							$htmlMenu .= $this->indentation.'			</div>'."\n"; //menuL3
						}
					}
					if (count($level2Value->attributes()) > 0) {
						$htmlMenu .= $this->indentation.'		</div>'."\n";  //menuL2
					}
				}
				if (count($level1Value->attributes()) > 0) {
					$htmlMenu .= $this->indentation.'	</div>'."\n"; //menuL1
				}
//				if ($iLevel1 == count($value0->level1)) {
//					$htmlMenu .= '	<div><br /></div>'."\n";
//				}
			}
			$htmlMenu .= $this->indentation.'</div><br />'."\n"; //menu
		}

		$htmlMenu .= $this->indentation.'<script type="text/javascript">'."\n";
		for ($i = 0; $i < count($opened); $i++) {
			if ($opened[$i][1] == false) {
				$htmlMenu .= $this->indentation.'	'.$this->objName.'.close(\''.$opened[$i][0].'\');'."\n";
			}
		}
		$htmlMenu .= $this->indentation.'</script>'."\n";

		if ($verbose) {
			echo $htmlMenu;
		}
		return $htmlMenu;
	}
	
	public function makeXml($verbose) {
		return '';
	}
}
