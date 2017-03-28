

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
 
function pacrudGrid(objName) {
	this.xmlIdentification;
	this.xmlData;
	this.objName = objName;
	this.lines;
	this.lineEventOnData = '';
	this.pointerCursorOnData = false;
	
	this.table = document.getElementById(objName);
	
	for (i=1; i <= this.table.rows.length -1; i++) {
		this.table.rows[i].setAttribute('onmouseover',objName+'.onmouseover('+i+')');
	}

	this.onmouseover = function(i) {
		this.table.rows[i].setAttribute('class','pacrudGridTrSelected');
		for (j=1; j <= this.table.rows.length -1; j++) {
			if (j != i) {
				if (j % 2 != 0) {
					this.table.rows[j].setAttribute('class','pacrudGridTrEven');
				}
				else {
					this.table.rows[j].setAttribute('class','pacrudGridTrOdd');
				}				
			}
		}
	}

	this.clear = function() {
		pGrid = document.getElementById(this.objName);
		//laço que percorre as linhas do grid
		for (i=1; i < pGrid.rows.length; i++) {
			pGridRow = pGrid.rows[i];
			//restaura o cursor
			pGridRow.style.cursor = 'default';
			pGridRow.removeAttribute('onClick');
			for (j=0; j < pGridRow.cells.length; j++) {
				pGridRow.cells[j].innerHTML = '<br />';
			}
		}
	}
	
	this.assignResponseXML = function(responseXML) {
		this.xmlData = responseXML.getElementsByTagName(this.xmlIdentification);

		//limpa o grid
		this.clear();

		//laço que percorre o xml
		for (i=0; i < this.xmlData.length; i++) {
			// faz referencia a linha do grid
			var pGridRow = document.getElementById(this.objName).rows[i+1];
			var pGridRowCells = pGridRow.cells;
			//coloca o cursor pointer
			if (this.pointerCursorOnData) {
				pGridRow.style.cursor = 'pointer';
			}
			//coloca o evento click na linha do grid
			if (this.lineEventOnData != '') {
				pGridRow.setAttribute('onClick',this.lineEventOnData.replace('%',i));
			}

			//laço que percorre as colunas do pacrudGrid
			for (j=0; j < this.field.length; j++) {
				pGridRowCells[j].innerHTML = this.getValue(this.field[j],i);
			}
		}
	}
	
	this.getValue = function(fieldName,index) {
		var value = this.xmlData[index].getElementsByTagName(fieldName)[0].childNodes[0].nodeValue;
		if (value == 'NULL') {
			value = '';
		}
		return value;
	}

}
