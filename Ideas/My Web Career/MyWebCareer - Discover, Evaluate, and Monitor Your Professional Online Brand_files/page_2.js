/*<![CDATA[*/

/* $Id: global.js,v 1.0 2010/05/10 14:00:00 twig Exp $ */
/*
	Include global and utility functions here
*/

/* Twig Class */
var TWIG = {};

// Used to set anchor targets for new window / pop-ups
// External plugin should take care of most but use this for PDFs etc.
// MODIFIED to use RegEx to allow multiple rel values
TWIG.setExternalLinks = (function() {
	function T() {
		var anchors = jQuery('a'); // Get <a> tags
		anchors.each(function(idx){
			var a = jQuery(this);
			var rel = a.attr('rel');
			if (rel.match(/\bnew\b/)) {
				a.attr('target','_blank'); // Set target
			}
			else if (rel.match(/\bpopup\b/)) {
				// Prevent multiple application
				if (a.attr('href').match(/^javascript:/)) {
						return void(0);
				};

				var popupParams = "'"+a.attr('href')+"','POPUP'"; // Build pop-up params list
				if (jQuery.trim(a.attr('rev')) != '') { popupParams += ","+a.attr('rev'); };
				a.attr('href','javascript:void(0);'); // Clear href entry to prevent parent location changing
				a.unbind('click.setExternalLinks').bind('click.setExternalLinks', function(evt) { // Bind the onclick event
					eval('TWIG.openWindow('+popupParams+');'); // Pass eval'd params to openWindow fn
					return void(0);
				});
			};
		});
	}
	
	return T;
})();

/* onReady */
jQuery(document).ready(function($) { // Must use jQuery $ wrapper when running in noConflict mode

	// PNG fix for IE6
	$(document).pngFix();
	
	// Set external links
	TWIG.setExternalLinks();
	
	// Table-data toggler
	$('table.toggled thead th a.toggler').bind('click',function(){
		var el = $(this);
		var tbl = el.parents('table.toggled').slice(0,1);
		if (tbl.hasClass('open')) { // Is open, so close
			$('tbody', tbl).hide();
			tbl.removeClass('open').addClass('closed');
		}
		else { // Is closed, so open
			$('tbody', tbl).show();
			tbl.removeClass('closed').addClass('open');
		};
	});
	
		/* onReady code from Boxie Admin */
		// Search input text handling on focus
		var $searchq = $("#search-q").attr("value");
		 $('#search-q.text').css('color', '#999');
		$('#search-q').focus(function(){
			if ( $(this).attr('value') == $searchq) {
				$(this).css('color', '#555');
				$(this).attr('value', '');
			}
		});
		$('#search-q').blur(function(){
			if ( $(this).attr('value') == '' ) {
				$(this).attr('value', $searchq);
				$(this).css('color', '#999');
			}
		});
	// Switch categories
		$('#h-wrap').hover(function(){
				$(this).toggleClass('active');
				$("#h-wrap ul").css('display', 'block');
			}, function(){
				$(this).toggleClass('active');
				$("#h-wrap ul").css('display', 'none');
		});
	// Handling with tables (adding first and last classes for borders and adding alternate bgs)
		$('tbody tr:even').addClass('even');
		$('table.grid tbody tr:last-child').addClass('last');
		$('tr th:first-child, tr td:first-child').addClass('first');
		$('tr th:last-child, tr td:last-child').addClass('last');
		$('form.fields fieldset:last-child').addClass('last');
	// Handling with lists (alternate bgs)
		$('ul.simple li:even').addClass('even');
	// Handling with grid views (adding first and last classes for borders and adding alternate bgs)
		$('.grid .line:even').addClass('even');
		$('.grid .line:first-child').addClass('firstline');
		$('.grid .line:last-child').addClass('lastline');
		
	// Tabs switching for .tabbedbox.activetabs
		$('.tabbedbox.activetabs').each(function(){
			$('.content[id^=tab_]:not([class*=active]').hide();
			$('.header ul a', this).click(function(){
				var el = $(this).parents('.tabbedbox').slice(0,1); // Get parent box
				$('.header ul a', el).removeClass('active');
				$(this).addClass('active'); // make clicked tab active
				$('.content', el).hide(); // hide all content
				$('#' + $(this).attr('rel'), el).show(); // and show content related to clicked tab
				return false;
			});
		});
		
		/* Twig additions */
		$('thead tr:first-child').add('tbody tr:first-child').addClass('first');
		$('thead tr:last-child').add('tbody tr:last-child').addClass('last');
		
		// Match <dt>s and <dd>s for <dl>s
		$('dl.match-row-heights').each(function(){
			$('dt', this).each(function(){
				var dt = $(this);
				var h1 = dt.height();
				var dd = dt.next('dd');
				var h2 = dd.height();
				var h = Math.max(h1, h2);
				dt.add(dd).height(h);
			});
		});
	
	
});

/*]]>*/
