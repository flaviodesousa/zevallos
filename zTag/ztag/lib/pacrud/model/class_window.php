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

/*
Basic Example:

$pWindow1 = new pacrudWindow('pWindow1');
$pWindow1->title = 'Window Test';
$pWindow1->draw(true,'my html where');
*/

class pacrudWindow {
	private $objName;
	public $indentation;
		
	// client property
	public $width;
	public $title;
	public $align;
	public $vAlign;
	
	function __construct($objName) {
		global $pacrudConfig;
		require_once($pacrudConfig['pacrudPath'].'/controller/inc_js_window.php');		
		$this->objName     = $objName;
		$this->width       = 730;
		$this->title       = 'pacrudWindow';
		$this->align       = 'center';
		$this->vAlign      = 'middle';
		$this->indentation = '';
	}
	
	public function draw($verbose,$htmlContent) {
		global $pacrudConfig;
		
		$pWindow  = $this->indentation . '<div class="pacrudVeil" id="'.$this->objName.'_veil"></div>'."\n";
		$pWindow .= $this->indentation . '<div class="pacrudWindow" id="'.$this->objName.'">'."\n";
		$pWindow .= $this->indentation . '	<div class="pacrudWindowTitle" id="'.$this->objName.'_titleBar">'."\n";
		$pWindow .= $this->indentation . '		<div class="pacrudWindowClose">'."\n";
		$pWindow .= $this->indentation . '			<a href="javascript:'.$this->objName.'.hide()">'."\n";
		$pWindow .= $this->indentation . '				<img src="'.$pacrudConfig['pacrudWebPath'].'/view/images/close.png" alt="" />'."\n";
		$pWindow .= $this->indentation . '			</a>&nbsp;&nbsp;'."\n";
		$pWindow .= $this->indentation . '		</div>'."\n";
		$pWindow .= $this->indentation . '		<div id="'.$this->objName.'_title" class="pacrudWindowTitle" onmousedown="'.$this->objName.'.move()" onmouseup="'.$this->objName.'.dropMove()">titulo</div>'."\n";
		$pWindow .= $this->indentation . '		<div id="'.$this->objName.'_loading" class="pacrudWindowLoading"><img src="'.$pacrudConfig['pacrudWebPath'].'/view/images/loading.gif" alt="" /></div>'."\n";
		$pWindow .= $this->indentation . '	</div>'."\n";
		$pWindow .= $this->indentation . '	<div class="pacrudWindowBody">'."\n";
//		$pWindow .= $this->indentation . '		<p>&nbsp;</p>'."\n";
//		$pWindow .= $this->indentation . '		<p>'."\n";
		$pWindow .= $htmlContent."\n";
//		$pWindow .= $this->indentation . '		<p>&nbsp;</p>'."\n";
//		$pWindow .= $this->indentation . '		</p>'."\n";
		$pWindow .= $this->indentation . '	</div>'."\n";
		$pWindow .= $this->indentation . '</div>'."\n";
	
		$pWindow .= $this->indentation . '<script type="text/javascript">'."\n";
		$pWindow .= $this->indentation . '	'.$this->objName.' = new pacrudWindow(\''.$this->objName.'\');'."\n";
		$pWindow .= $this->indentation . '	'.$this->objName.'.width = '.$this->width.';'."\n";
		$pWindow .= $this->indentation . '	'.$this->objName.'.title = \''.$this->title.'\';'."\n";
		$pWindow .= $this->indentation . '	'.$this->objName.'.align = \''.$this->align.'\';'."\n";
		$pWindow .= $this->indentation . '	'.$this->objName.'.vAlign = \''.$this->vAlign.'\';'."\n";		
		$pWindow .= $this->indentation . '</script>'."\n";

		if ($verbose) {
			echo $pWindow;
		}
		
		return $pWindow;
	}
}
?>
