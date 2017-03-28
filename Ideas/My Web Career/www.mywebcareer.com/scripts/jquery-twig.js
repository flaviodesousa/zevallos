/*<![CDATA[*/

/* $Id: jquery-twig.js,v 1.0 2010/05/10 14:00:00 twig Exp $ */

/* jQuery extension functions */

// Empties a select box
(function($){
	$.fn.emptySelect = function() {
		return this.each(function() {
			if (this.tagName == 'SELECT') { this.options.length = 0; };
		});
	}
})(jQuery);

// Fills a select box from associative JS array
(function($){
	$.fn.loadSelect = function(optionsDataArray) {
		return this.emptySelect().each(function() {
			if (this.tagName == 'SELECT') {
				var selectElement = this;
				$.each(optionsDataArray, function(index,optionData) {
					var option = new Option(optionData.txt, optionData.value);
					if ($.browser.msie) { selectElement.add(option); }
					else { selectElement.add(option,null); };
				});
			};
		});
	}
})(jQuery);

// Disables a form element
(function($){
	$.fn.disableMe = function() {
		return this.each(function() {
			if (typeof(this.disabled) != 'undefined') { $(this).attr('disabled','disabled'); };
		});							
	}
})(jQuery);

// Enables a form element
(function($){
	$.fn.enableMe = function() {
		return this.each(function() {
			if (typeof(this.disabled) != 'undefined') { $(this).removeAttr('disabled'); };
		});							
	}
})(jQuery);

// Selects a form element
(function($){
	$.fn.selectMe = function() {
		return this.each(function() {
			if (typeof(this.selected) != 'undefined') { $(this).attr('selected','selected'); };
		});							
	}
})(jQuery);

// Stripe a table
(function($){
	$.fn.stripeMe = function() {
		return this.each(function() {
			$('tbody tr.zebra', this).removeClass('zebra');
			$('tbody tr:odd', this).addClass('zebra');
			$('tr', this).each(function(n){
				$('th:first,td:first', this).addClass('first');
				$('th:last,td:last', this).addClass('last');
			});
		});
	}
})(jQuery);

/*]]>*/
