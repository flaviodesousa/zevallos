/* onReady */
jQuery(document).ready(function($) { // Must use jQuery $ wrapper when running in noConflict mode

	// Round corners natively
	if (!$.browser.msie) { $('#tpl_content .tabbed-content').corner('tl tr 8px keep'); };

	// Hook the tabs with mouseover event
	var tabSets = $('.tabset');
	tabSets.each(function(idx) {
		new TWIG.TabSet($(this), 'mouseover');
	});
	
});