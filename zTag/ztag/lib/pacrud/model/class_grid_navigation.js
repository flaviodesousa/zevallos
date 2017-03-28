

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
 
function pacrudGridNavigation(objName) {
	this.objName = objName;
	this.xmlIdentification = 'pGridStatus';
	this.count;
	this.pageLines;
	this.page;
	this.maxPages = 10;

	//recalc
	this.registersFrom;
	this.registersTo;
	this.pages;
	
	this.gridNavigation = document.getElementById('pGridNavigation_'+objName);
	
	this.clear = function() {
		this.gridNavigation.innerHTML = document.pacrudText[24]+'...';
	}
	
	this.recalc = function() {
		this.registersFrom = (this.page - 1) * this.pageLines + 1;
		this.registersTo = this.registersFrom*1 + this.pageLines*1-1;
		if (this.registersTo > this.count) {
			this.registersTo = this.count;
		}
		this.pages = Math.ceil(this.count / this.pageLines);
	}
	
	this.redraw = function() {
		this.recalc();
		htmlOut = '';		
		
		// calculos
		var bars = Math.ceil(this.pages / this.maxPages);
		var thisBar = Math.ceil(this.page / this.maxPages);
		var pageFrom = (thisBar -1) * this.maxPages + 1;
		var pageTo = pageFrom + this.maxPages - 1;
		
		//quando nenhum registro encontrado
		if (this.registersTo == 0) {
			this.registersFrom = 0;
			this.page = 0;
		}
		
		// quando não há paginas suficientes para completar a barra
		if (pageTo > this.pages) {
			pageTo = this.pages;
		}

		// pog debug
		/*
		debug = 'thisPage='+this.page+'\n';
		debug += 'thisPages='+this.pages+'\n';
		debug += 'bars='+bars+'\n';
		debug += 'thisBar='+thisBar+'\n';
		debug += 'pageFrom='+pageFrom+'\n';
		debug += 'pageTo='+pageTo+'\n';
		alert(debug);
		*/

		// botão primeira página
		if (thisBar > 2) {
			htmlOut += '<input type="submit" value="1..." onclick="'+this.objName+'.goSearch(1)" />';
		}

		// botão pagina anterior
		if (thisBar > 1) {
			var lastPage = pageFrom - 1;
			htmlOut += '<input type="submit" value="<" onclick="'+this.objName+'.goSearch('+lastPage+')" />';
		}
		
		// Laço que cria os botões da barra
		for(i = 0 ; i < pageTo - pageFrom +1 ; i++) {
			var iPage = pageFrom + i;
			if (iPage == this.page) {
				htmlOut += '<input type="submit" value="'+iPage+'" disabled onclick="'+this.objName+'.goSearch('+iPage+')" />';
			}
			else {
				htmlOut += '<input type="submit" value="'+iPage+'" onclick="'+this.objName+'.goSearch('+iPage+')" />';
			}
		}
		
		// botão próxima pagina
		if (iPage != this.pages && this.page != 0) {
			var nextPage = iPage + 1;
			htmlOut += '<input type="submit" value=">" onclick="'+this.objName+'.goSearch('+nextPage+')" />';
		}
		
		// botão última página
		if (bars - thisBar > 1) {
			htmlOut += '<input type="submit" value="...'+this.pages+'" onclick="'+this.objName+'.goSearch('+this.pages+')" />';
		}

		// monta a barra de status
		htmlOut += '<br />';
		htmlOut += document.pacrudText[35]+': '+this.count+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
		htmlOut += document.pacrudText[36]+' '+this.registersFrom+' '+document.pacrudText[39]+' '+this.registersTo+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
		htmlOut += document.pacrudText[37]+' '+this.page+' '+document.pacrudText[38]+' '+this.pages;
		htmlOut += '<br />';
	
		this.gridNavigation.innerHTML = htmlOut;
	}
	
	this.assignResponseXML = function(responseXML) {
		var x=responseXML.getElementsByTagName(this.xmlIdentification);
		this.clear();
		this.count     = x[0].getElementsByTagName('count')[0].childNodes[0].nodeValue;
		this.pageLines = x[0].getElementsByTagName('pageLines')[0].childNodes[0].nodeValue;
		this.page      = x[0].getElementsByTagName('page')[0].childNodes[0].nodeValue;

		this.redraw();
	}

}
