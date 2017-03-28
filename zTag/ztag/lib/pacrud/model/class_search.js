

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

function pacrudSearch(objName,ajaxFile) {
	this.objName = objName;
	this.modal;
	this.identification = objName;
	this.page;

	this.pAjax;
	this.pWindow;
	this.pFilter;
	this.pGrid;
	this.pGridNavigation;
	
	this.fieldReturn = Array();

	this.modal = true;
	this.pAjax = new pacrudAjax(ajaxFile);
	this.pAjax.parent = this;
	this.pAjax.identification = this.identification;
	this.pAjax.divLoading = 'pWindow_'+this.objName+'_loading';
	this.pAjax.ajaxXmlOk = function() {
		this.parent.pGrid.assignResponseXML(this.responseXML);
		this.parent.pGridNavigation.assignResponseXML(this.responseXML);
		this.parent.pFilter.assignResponseXML(this.responseXML);
	}
	
	this.parametersFilters = function() {
		param = '';
		for (i=0; i < this.pFilter.filter.length; i++) {
			param += '&fField[]='+this.pFilter.filter[i].fieldName;
			param += '&fOperator[]='+this.pFilter.filter[i].operator;
			param += '&fValue[]='+this.pFilter.filter[i].value;
			param += '&fValue2[]='+this.pFilter.filter[i].value2;
			param += '&fVisible[]='+this.pFilter.filter[i].visible;
		}
		return param;
	}
	
	this.parameters = function() {
		if (this.page == undefined) {
			this.page = 1;
		}
		param = this.objName+'_action=makeXml';
		param += '&page='+this.page;
		param += this.parametersFilters();
		return param;
	}
	
	this.lineClick = function(lineIndex) {
		for (i=0; i<this.fieldReturn.length; i++) {
			var value = this.pGrid.getValue(this.fieldReturn[i][0],lineIndex);
			document.getElementById(this.fieldReturn[i][1]).value = value;
		}
		this.hide();
	}
	
	this.goSearch = function(page) {
		this.page = page;
		if (page == undefined) {
			this.pFilter.clearValues();
			this.pGrid.clear();
			this.pGridNavigation.clear();
		}
		this.show();
		this.pAjax.goAjax(this.parameters());
	}
	
	this.cmdSearch = function() {
		this.goSearch(1);
	}
	
	this.cmdSearchAll = function() {
		this.pFilter.clearValues();
		this.cmdSearch();
		this.pFilter.draw();
	}
	
	this.addFieldReturn = function(fieldName,idReturn) {
		this.fieldReturn[this.fieldReturn.length] = Array(fieldName,idReturn);
	}
	
	this.show = function() {
		this.pWindow.show(this.modal);
	}
  
	this.hide = function() {
		this.pWindow.hide();
	}
}
