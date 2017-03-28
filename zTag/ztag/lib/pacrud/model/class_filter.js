

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
 
function pacrudFilter(objName) {
	this.objName = objName;
	this.pAjax = new pacrudAjax();
	this.page;
	this.pacrudWebPath;
	this.xmlIdentification = 'pFilter';
	
	this.fieldName  = new Array();
	this.fieldLabel = new Array();
	this.fieldType  = new Array();
	
	this.filter = new Array();

	this.textOperators        = Array(
								        'like',
								        'not like',
								        'begins with',
								        'ends with',
								        'not begins with',
								        'not ends with',
								        'equal',
								        'not equal'
								     );
	this.textOperatorsName    = Array(
								        document.pacrudText[43],
								        document.pacrudText[44],
						                document.pacrudText[45],
						                document.pacrudText[46],
						                document.pacrudText[47],
						                document.pacrudText[48],
						                document.pacrudText[49],
						                document.pacrudText[50]
									 );
	this.numericOperators     = Array(
								        'numeric equal',
								        'numeric not equal',
								        'less than',
								        'greater than',
								        'less than or equal',
								        'greater than or equal'
								     );
	this.numericOperatorsName = Array(
								        document.pacrudText[49],
								        document.pacrudText[50],
						                document.pacrudText[51],
						                document.pacrudText[52],
						                document.pacrudText[53],
						                document.pacrudText[54]
								     );

	this.addFilter = function(filterIndex,fieldName,operator,value,value2,visible) {
		function aFilter(aFieldName,aOperator,aValue,aValue2,aVisible) {
			this.fieldName = aFieldName;
			this.operator  = aOperator;
			this.value     = aValue;
			this.value2    = aValue2;
			this.visible   = aVisible;
		}
		var newFilter = new aFilter(fieldName,operator,value,value2,visible);
		if (filterIndex == null) {
			this.filter.push(newFilter);
		}
		else {
			this.filter.splice(filterIndex,0,newFilter);
		}
	}
	
//	this.filterIndexByName = function(fieldName) {
//		for (iii = 0; iii < this.filter.length; iii++) {
//			if (this.filter[iii].fieldName == fieldName) {
//				return iii;
//			}
//		}
//		return null;
//	}
	
	this.cmdAddFilter = function(filterIndex) {
		this.addFilter(filterIndex,'','','','',true);
		this.draw();
	}
	
	this.removeFilter = function(filterIndex) {
		removed = this.filter.splice(filterIndex,1);
		return removed;
	}
	
	this.cmdRemoveFilter = function(filterIndex) {
		this.removeFilter(filterIndex);
		this.draw();
	}
	
	this.clearFilters = function() {
		this.filter = new Array();
	}
	
	this.loadCustomFilters = function() {
	
	}
	
	this.fieldTypeByName = function(fieldName) {
		for (i=0; i < this.fieldName.length; i++) {
			if (fieldName == this.fieldName[i]) {
				return this.fieldType[i];
			}
		}
		return null;
	}
	
	this.operatorTypeByName = function(fieldName) {
		fieldType = this.fieldTypeByName(fieldName);
		
	    switch (fieldType) {
		    case 'string':
		        return 'text';
		        break;
		    case 'integer':
		        return 'numeric';
		        break;
		    case 'serial':
		        return 'numeric';
		        break;
		    case 'date':
		        return 'numeric';
		        break;
		    case 'time':
		        return 'numeric';
		        break;
		    case 'timestamp':
		        return 'numeric';
		        break;
        default:
            return 'text';
	    }
	}
	
	this.selectFieldChange = function(selectFieldName,index) {
		operatorType = this.operatorTypeByName(selectFieldName.value);
		selectOperator = document.getElementById(this.objName+'_'+index+'_operator');

		if (operatorType == 'text') {
			arrOperators     = this.textOperators;
			arrOperatorsName = this.textOperatorsName;
		}
		if (operatorType == 'numeric') {
			arrOperators     = this.numericOperators;
			arrOperatorsName = this.numericOperatorsName;
		}
		
		var htmlOptions = '';
		for (j=0; j < arrOperators.length; j++) {
			htmlOptions += '<option value="'+arrOperators[j]+'">'+arrOperatorsName[j]+'</option>\n';
		}

		selectOperator.innerHTML = htmlOptions;	
		this.filter[index].fieldName = selectFieldName.value;
		
		selectOperator.value = arrOperators[0];
///////////////// a proxima linha era um bug que sobrescreve o valor do filtro que foi selecionado pelo usuário
//		this.selectOperatorChange(selectOperator,index);

		// coloca o foco no campo de pesquisa
		document.getElementById(this.objName+'_'+index+'_value').focus();
	}
	
	this.selectOperatorChange = function(selectOperator,index) {
		// passa o valor escolhido para o objeto filter
		this.filter[index].operator = selectOperator.value;
		// coloca o foco no campo de pesquisa
		document.getElementById(this.objName+'_'+index+'_value').focus();
	}


	this.inputValueChange = function(inputObject,index) {
		this.filter[index].value = inputObject.value;
	}
	
	this.inputValueKeyUp = function(event,index) {
	    switch (event.keyCode) {
		    case 13: //ENTER
		        this.parent.cmdSearch();
		        break;
		    case 27: //ESC
		        this.parent.hide();
		        break;
		    case 113: //F2
		        alert('F2');
		        break;
		    case 38:
		        alert('Seta para cima');
		        break;
		    case 40:
		        alert('Seta para baixo');
		        break;
//        default:
//            alert('outra');
	    }
//	    this.inputValueChange(this.objName+'_'+index+'_value',index);
	}

	
	this.drawFilterControls = function() {
		visibleFilters = 0;
		for (i=0; i < this.filter.length; i++) {
			if (this.filter[i].visible) {
				visibleFilters++;
			}
		}

		if (visibleFilters == 1) {
			filterControl  = '<a href="javascript:'+this.objName+'.cmdAddFilter(1)">';
			filterControl += '<img src="'+this.pacrudWebPath+'/view/images/add.png" alt="add" />';
			filterControl += '</a>\n';
			document.getElementById(this.objName+'_0_controls').innerHTML = filterControl;
		}
		else {
			for (i=0; i < this.filter.length; i++) {
				if (this.filter[i].visible) {
					filterIndex = i+1;
					filterControl  = '<a href="javascript:'+this.objName+'.cmdAddFilter('+filterIndex+')">';
					filterControl += '<img src="'+this.pacrudWebPath+'/view/images/add.png" alt="add" />';
					filterControl += '</a>\n';
					filterControl += '<a href="javascript:'+this.objName+'.cmdRemoveFilter('+i+')">';
					filterControl += '<img src="'+this.pacrudWebPath+'/view/images/remove.png" alt="del" />';
					filterControl += '</a>\n';
					document.getElementById(this.objName+'_'+i+'_controls').innerHTML = filterControl;
				}
			}
		}
	}
	
	this.clearValues = function() {
		for (i=0; i < this.filter.length; i++) {
			if (this.filter[i].visible) {
				this.filter[i].value  = '';
				this.filter[i].value2 = '';
			}
		}
	}

	this.configureFilter = function(fieldName,filterIndex) {
//		var filterIndex = this.filterIndexByName(fieldName);

		if (this.filter[filterIndex].visible) {
			selectFieldName = document.getElementById(this.objName+'_'+filterIndex+'_field');
			selectOperator  = document.getElementById(this.objName+'_'+filterIndex+'_operator');
			inputValue      = document.getElementById(this.objName+'_'+filterIndex+'_value');

			// configura selectFilter e o inputValue com o valor anteriormente passado via XML
			if (this.filter[filterIndex].fieldName == '') {
				selectFieldName.value = this.filter[0].fieldName;
			}
			else {
				selectFieldName.value = this.filter[filterIndex].fieldName;
			}
			inputValue.value = this.filter[filterIndex].value;

			// preenche o combo selectOperator
			this.selectFieldChange(selectFieldName,filterIndex);

			// configura o selectOperator
			var operatorType = this.operatorTypeByName(this.filter[filterIndex].fieldName);
			if (this.filter[filterIndex].operator == '') {
				switch (operatorType) {
					case 'text':
						arrOperators = this.textOperators;
						break;
					case 'numeric':
						arrOperators = this.numericOperators;
						break;
				default:
					arrOperators = this.textOperators;
				}
				this.filter[filterIndex].operator = arrOperators[0];
			}
			selectOperator.value = this.filter[filterIndex].operator;
		}
	}

	this.draw = function() {
		htmlFilters = '<table cellpadding="0" cellspacing="0">\n';
		for (i=0; i< this.filter.length; i++) {
			if (this.filter[i].visible) {
				htmlFilters += '	<tr>\n';
				htmlFilters += '		<td>\n';
				htmlFilters += '			<select id="'+this.objName+'_'+i+'_field" onchange="'+this.objName+'.selectFieldChange(this,'+i+')">\n';
				for (j=0; j< this.fieldName.length; j++) {
					htmlFilters += '				<option value="'+this.fieldName[j]+'">'+this.fieldLabel[j]+'</option>\n';
				}
				htmlFilters += '			</select>\n';
				htmlFilters += '		</td>\n';
				htmlFilters += '		<td>\n';
				htmlFilters += '			<select id="'+this.objName+'_'+i+'_operator" onchange="'+this.objName+'.selectOperatorChange(this,'+i+')"></select>\n';
				htmlFilters += '		</td>\n';
				htmlFilters += '		<td>\n';
				htmlFilters += '			<input id="'+this.objName+'_'+i+'_value" size="15" onchange="'+this.objName+'.inputValueChange(this,'+i+')" onkeyup="'+this.objName+'.inputValueKeyUp(event,'+i+')" />\n';
				htmlFilters += '			<span id="'+this.objName+'_'+i+'_controls"></span>\n';
				htmlFilters += '		</td>\n';
				htmlFilters += '	</tr>\n';
			}
		}
		htmlFilters += '</table>\n';
		
		document.getElementById(this.objName+'_filters').innerHTML = htmlFilters;

		for (ii = 0; ii < this.filter.length; ii++) {
			this.configureFilter(this.filter[ii].fieldName,ii);
		}
		
		this.drawFilterControls();
	}
	
	this.getValue = function(fieldName,index) {
		var value = this.xmlData[index].getElementsByTagName(fieldName)[0].childNodes[0].nodeValue;
		if (value == 'NULL') {
			value = '';
		}
		return value;
	}
	
	this.assignResponseXML = function(responseXML) {
		this.xmlData = responseXML.getElementsByTagName(this.xmlIdentification);

		//limpa os filtros
		this.filter = new Array()

		//laço que percorre o xml
		for (i=0; i < this.xmlData.length; i++) {
			fieldName    = this.getValue('fieldName',i);
			operator     = this.getValue('operator',i);
			value        = this.getValue('value',i);
			value2       = this.getValue('value2',i);
			visible      = this.getValue('visible',i);

			this.addFilter(i,fieldName,operator,value,value2,visible);
		}
		this.draw();
	}
}
