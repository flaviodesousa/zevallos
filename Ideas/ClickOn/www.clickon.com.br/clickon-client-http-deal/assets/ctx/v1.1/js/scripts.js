jQuery.noConflict();
jQuery(document).ready(function() {

	// funcao para testar se elemento existe.
	jQuery.fn.exists = function () {
		return jQuery(this).length > 0 ? true : false;
	};
	
	// funcao para manter dois elem com height igual
	// o terceiro parametro e opcional e server para
	// fazer uma subtracao se necessario.
	var getPadding = function(elem, parent, minusBorder) {
		if(jQuery(elem).exists() && jQuery(parent).exists()) {
			var size = parseFloat(jQuery(elem).height());
			var padding = 0;
			var padd;
			var paddPosition = ["Top","Bottom"];
			for (var i = 0; i < paddPosition.length; i++) {
				padd = parseFloat(jQuery(elem).css("padding"+paddPosition[i]).replace("px", ""));
				jQuery(parent).css("padding"+paddPosition[i], padd);
			}
			if(minusBorder === undefined) {
				jQuery(parent).height(size);
			} else {
				jQuery(parent).height(size - minusBorder);
			}
		}
		return false;
	}
	getPadding("div.offerToday", "div.buyArea", 2);
	getPadding("div.post", "div.address");

	// Retira o valor do campo input com o evento click
	// e quando ativa o evento blur o mesmo testa se
	// existe algum valor novo no campo e o mantem,
	// caso contrario retorna o valor anterior.
	var submitEmail = jQuery("#newsletterEmailField");
	var saveValue = submitEmail.val();
	submitEmail.click(function() {
		jQuery(this).attr('value','');
	});
	submitEmail.blur(function(){
		if (jQuery(this).attr('value') === '') {
			jQuery(this).attr('value',saveValue);
		}
	});

	// Add atributo para link externo
	var linkExterno = function (link) {
		link.attr('target','_blank');
	}
	linkExterno(jQuery('a[rel|=external]'));
	
	// Menu das novas cidades
	openCities = function(link, elem) {
		jQuery(link).click(function() {
			// identifica se o elem. está invisivel
			if (jQuery(elem).is(":hidden")) {
				// add a class reverse para mudar a flecha de posição
				jQuery("#city a.cities strong").addClass("reverse");
				// efeito slideDown
				jQuery(elem).slideDown();
			} else {
				jQuery("#city a.cities strong").removeClass("reverse");
				jQuery(elem).slideUp();
			}
		});
	}
	openCities("#innerHeader a.linkCitiesOpen", "#localizedZone");

	// retira borda do 6 elemento.
	jQuery("div#localizedZone li:nth-child(5n)").addClass("noBorder");
	
	// retira borda do ultimo elem e da display none no 5 elem.
	//jQuery("#accountMenu li:last-child").addClass("noBorder");
	jQuery("#accountMenu li:nth-child(7n)").css("display","none");
});

function maskPhone(phoneField){
	var value = phoneField.value;
	var cleanRE = new RegExp("\\D", "g");
	value = value.replace(cleanRE, "");
	
	if (value[0] == '0'){
		if (value.length > 6){
			value = '('+value.substr(0,3)+') '+value.substr(3,4)+ '-' + value.substr(7,value.length);
		} else if (value.length > 3){
			value = '('+value.substr(0,3)+') '+value.substr(3,value.length);
		} else if (value.length == 3){
			value = '('+ value +')';
		}else if (value.length > 0){
			value = '('+ value;				
		}
	} else {
		if (value.length > 5){
			value = '('+value.substr(0,2)+') '+value.substr(2,4)+ '-' + value.substr(6,value.length);
		} else if (value.length > 2){
			value = '('+value.substr(0,2)+') '+value.substr(2,value.length);
		} else if (value.length == 2){
			value = '('+ value +')';
		}else if (value.length > 0){
			value = '('+ value;				
		}
	}
	phoneField.value = value.substr(0,15);
}

function maskCep(cepField){
	var value = cepField.value;
	var cleanRE = new RegExp("\\D", "g");
	value = value.replace(cleanRE, "");
	
	//xxxxx-xxx
	if(value.length > 5)
		value = value.substr(0,5) + '-' + value.substr(5,value.length);
	
	cepField.value = value.substr(0,9);
	
}