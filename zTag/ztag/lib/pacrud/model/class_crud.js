

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

function paCRUD(objName,ajaxFile) {
	this.objName = objName;
	this.modal;
	this.identification = objName;

	this.pAjax;
	
	this.pAjax = new pacrudAjax(ajaxFile);
	this.pAjax.parent = this;
	this.pAjax.identification = this.identification;
	this.pAjax.divLoading = 'pWindow_'+this.objName+'_loading';
	this.pAjax.ajaxXmlOk = function() {
//		this.parent.pGrid.assignResponseXML(this.responseXML);
//		this.parent.pGridNavigation.assignResponseXML(this.responseXML);
//		this.parent.pFilter.assignResponseXML(this.responseXML);
	}
	
	
	this.parameters = function() {
/*
		if (this.page == undefined) {
			this.page = 1;
		}
		param = this.objName+'_action=makeXml';
		param += '&page='+this.page;
		param += this.parametersFilters();
*/
		return param;
	}
	
	this.doCreate = function() {
		//code
	}

	this.doRetrieve = function() {
		//code	
	}
	
	this.doUpdate = function() {
		//code	
	}
	
	this.doDelete = function() {
		//code
	}
}
