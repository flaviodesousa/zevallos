/*<![CDATA[*/

/* $Id: global.js,v 1.0 2010/05/10 14:00:00 twig Exp $ */
/*
	Include global and utility functions here
*/

// Get bookmark if present: strip pound symbol, set to lowercase, then decode
var global_pageBookmark = ((window.location.hash) ? URLDecode((window.location.hash.substring(1)).toLowerCase()) : ''); 
var tallestColumn, tallestColumnHeight; // Holders for tallest column jQ object & its height

/*
	Intellectual property of Twig Interactive LLC
	Do not reuse or alter without express permission
	http://www.twiginteractive.com
	
	Use jQuery prefix not the shortcut ($) to be safe with noConflict() mode
*/

/* Twig Class */
var TWIG = {};

// Statics
TWIG.defaultTuringTestValue = 'Enter code...';
TWIG.defaultSearchValue = 'Enter Search Topic...';

// TabSet class: receives the div.tabset wrapped set
// Updated 08/31/2010: can specify event for click or rollover
TWIG.TabSet = (function() {
	function T(el, bindEvent) {
	
		// Set default event to click
		if (typeof(bindEvent) == 'undefined') { bindEvent = 'click'; };	

		// Hide elements not required when JS is active
		$('.noJS', el).hide();
			
		// Get tabs from tabset
		var tabs = $('ul.tabs li.tab', el);
		
		// Process each tab
		tabs.each(function(idx) {
			// Grab the identifier, the tab and the corresponding content
			// Identifier must be unique on the page for bookmarking to work
			var t = $(this);
			var tc = $('#' + t.attr('id').replace(/tab_/,'tab-content_')); // Simple RegEx
			var i = t.attr('id').replace(/tab_/,'');
			
			// Process anchor tags
			t.find('a').each(function(i) {
				// Replace hash in anchor with JS void
				$(this).attr('href', 'javascript:void(0);');
			});
			tc.find('a').each(function(i) {
				// Check for tab-relative bookmarks
				if ($(this).attr('rel') == 'tab') { // Click event only
					$(this).unbind('click.tabSet').bind('click.tabSet', function(evt) {
						// Replicate corresponding li event
						$('li#tab_'+$(this).attr('href').replace(/#/,'')).click(); 
					});
				};
			});
			
			// Remove unnecessary bookmarks from tab content to prevent page jumping
			// Should use <a id="xxx"> since name is deprecated
			tc.find('a[id='+i+']').remove();

			// Get vertical offset
			var tabsetOffset = el.offset({ relativeTo: document });

			// Look for tab in hash
			if (global_pageBookmark.indexOf(i.toLowerCase()) === 0) { // Tab found	
				// Turn other tabs 'off' & hide content
				tabs.filter('.on').removeClass('on');
				$('.tab-content', el).hide();
				$('.tab-content', el).removeClass('on');
				$('a.current', tabs).removeClass('current');
				
				// Turn this tab 'on'
				t.addClass('on');
				tc.addClass('on');
				tc.show();

				// Scroll to place tabset in view
				$(document).scrollTop(tabsetOffset['top']);

			}
			else { // Set initial view
				// Hide other tab contents
				$('.tab-content', el).not('.on').hide();
			};
			
			// Style selected tab's anchor as current
			if (t.attr('class').match(/\bon\b/)) {
				t.find('a').each(function(i) {
					$(this).addClass('current');
				});
			};
			
			// Bind the event
			t.unbind(bindEvent+'.tabSet').bind(bindEvent+'.tabSet', function(evt) {

				// Grab the tab
				var li = $(this);
				
				// If tab is 'on', stop processing
				if (li.hasClass('on')) return;
				
				// Turn other tabs 'off' & hide content
				tabs.filter('.on').removeClass('on');
				$('.tab-content', el).hide().removeClass('on');
				$('a.current', tabs).removeClass('current');

				// Turn this tab 'on'
				li.addClass('on');
				tc.fadeIn('fast', function() {
					if ($.browser.msie) { this.style.removeAttribute('filter'); }; // IE ClearType fix
					tc.addClass('on');
				 });
				
				// Style anchor as current
				li.find('a').each(function(i) {
					$(this).addClass('current');
				});
				
				// Scroll to place tabset in view
				//$(document).scrollTop(tabsetOffset['top']);

				// Set bookmark
				window.location.hash = i;
			});
		});
	}
	
	return T;
})();

// Toggles between default value and empty string
TWIG.toggleTextInputValue = (function() {
	function T(o,evt,defVal,len) {
		if (typeof(len) == 'undefined') {
			len = 60; // Default max length
		};
		if (evt == 'focus') {
			if (jQuery.trim(o.value) == defVal) {
				o.maxLength = len;
				o.value = '';
			};
		};
		if (evt == 'blur') {
			if (jQuery.trim(o.value) == '') {
				o.maxLength = defVal.length;
				o.value = defVal;
			};
		};
	}

	return T;
})();


// Checks email address against RFC rules
// Based on original PHP code by Cal Henderson (http://iamcal.com/publish/articles/php/parsing_email)
// Twig converted to JS regex
TWIG.isValidEmailAddress = (function() {
	function T(email) {
		var qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
		var dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
		var atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'+'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
		var quoted_pair = '\\x5c\\x00-\\x7f';
		var domain_literal = '\\x5b('+dtext+'|'+quoted_pair+')*\\x5d';
		var quoted_string = '\\x22('+qtext+'|'+quoted_pair+')*\\x22';
		var domain_ref = atom;
		var sub_domain = '('+domain_ref+'|'+domain_literal+')';
		var word = '('+atom+'|'+quoted_string+')';
		var domain = sub_domain+'(\\x2e'+sub_domain+')*';
		var local_part = word+'(\\x2e'+word+')*';
		var addr_spec = local_part+'\\x40'+domain;
		var filter = eval('/^' + addr_spec + '$/');
		
		if (filter.test(email)) { // Passes RFC RegEx
			// Sanity check for period '.' following @
			if (email.indexOf('.',email.indexOf('@')) >= 0) {
				return true;
			}
		}
		return false;
	}
	
	return T;
})();

// Checks input against RegEx for valid website URL
TWIG.isValidURL = (function() {
	function T(URL) {
		var urlMatch = /^https?:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
		if (urlMatch.test(URL)) { // Passes RegEx
			// Sanity check for period '.' following //
			if (URL.indexOf('.',URL.indexOf('//')) >= 0) {
				return true;	
			}
		}
		return false;
	}
	
	return T;
})();

// Open a pop-up window with set parameters
TWIG.openWindow = (function() {
	function T(URL,Name,W,H,L,T,Scrolls,Resize) {
		// Used to control params of pop-ups
		var defProps = 'copyhistory=no,directories=no,fullscreen=no,location=no,menubar=no,status=no,titlebar=yes,toolbar=no';
		var poppedProps = '';
	
		if (W != null) {
			if (Scrolls == true) { W += 16; } // Allow for chrome in IE
			poppedProps += ('width='+W+',');
		}
		if (H != null) { poppedProps += ('height='+H+','); }
		if (L != null) { poppedProps += ('left='+L+','); }
		if (T != null) { poppedProps += ('top='+T+','); }
		poppedProps += 'scrollbars=' + ((Scrolls != false) ? 'yes' : 'no') + ',' ; // Default 1
		poppedProps += 'resizable=' + ((Resize != false) ? 'yes' : 'no') + ',' ; // Default 1
		poppedProps += defProps;

//		alert(poppedProps);		
		poppedUp = window.open(URL,Name,poppedProps);
		if (poppedUp) { setTimeout("poppedUp.window.focus();",100); };
		return poppedUp;
	}
	return T;
})();

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

/*
	Used to match the heights of content, sidebar-left and sidebar-right for decor
	Assuming that these columns have no padding or margin per CSS layout and that contents are in *-inner divs
	*** DOM ELEMENTS ALTERED FROM DRUPAL FOR WORDPRESS! ***
*/
TWIG.matchColumnHeights = (function() {
	function T() {
		var els = jQuery('#container').add('#primary');
		var maxH = 0;
		els.each(function(n){
			jQuery(this).css({minHeight: maxH}); // Reset
			if ((h = jQuery(this).height()) > maxH) {
				maxH = h; // Update max height
				tallestColumn = jQuery(':first-child', this); // Update tallest column (global)
			};
		});
		els.each(function(n){
			jQuery(this).css({minHeight: maxH+'px'}); // Apply minimum height
		});
		tallestColumnHeight = tallestColumn.height(); // Update tallest column height (global)
	}
	
	return T;
})();
// Companion poller function to check whether the total height has changed & columns need to be re-matched
TWIG.checkColumnHeights = (function() {
	function T() {
		if (tallestColumn.height() != tallestColumnHeight) {
			TWIG.matchColumnHeights();
		};
	}
	
	return T;
})();

/* General function from public domain */

/* Cookie handling */
function getCookie(name) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	};
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ';', len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
};

function setCookie(name, value, expires, path, domain, secure) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	};
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+'='+escape( value ) +
		( ( expires ) ? ';expires='+expires_date.toGMTString() : '' ) + //expires.toGMTString()
		( ( path ) ? ';path=' + path : '' ) +
		( ( domain ) ? ';domain=' + domain : '' ) +
		( ( secure ) ? ';secure' : '' );
};

function deleteCookie(name, path, domain) {
	if ( getCookie( name ) ) document.cookie = name + '=' +
			( ( path ) ? ';path=' + path : '') +
			( ( domain ) ? ';domain=' + domain : '' ) +
			';expires=Thu, 01-Jan-1970 00:00:01 GMT';
};
/* Cookie handling */


// ====================================================================
//       URLEncode and URLDecode functions
//
// Copyright Albion Research Ltd. 2002
// http://www.albionresearch.com/
//
// ====================================================================
function URLEncode(plaintext) {
	// The Javascript escape and unescape functions do not correspond
	// with what browsers actually do...
	var SAFECHARS = "0123456789" +					// Numeric
					"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
					"abcdefghijklmnopqrstuvwxyz" +
					"-_.!~*'()";					// RFC2396 Mark characters
	var HEX = "0123456789ABCDEF";

	var encoded = "";
	for (var i = 0; i < plaintext.length; i++ ) {
		var ch = plaintext.charAt(i);
	   if (ch == " ") {
		    encoded += "+";				// x-www-urlencoded, rather than %20
		} else if (SAFECHARS.indexOf(ch) != -1) {
		    encoded += ch;
		} else {
		   var charCode = ch.charCodeAt(0);
			if (charCode > 255) {
			    alert( "Unicode Character '" 
                        + ch 
                        + "' cannot be encoded using standard URL encoding.\n" +
				          "(URL encoding only supports 8-bit characters.)\n" +
						  "A space (+) will be substituted." );
				encoded += "+";
			} else {
				encoded += "%";
				encoded += HEX.charAt((charCode >> 4) & 0xF);
				encoded += HEX.charAt(charCode & 0xF);
			};
		};
	}; // for

	return (encoded == '') ? false : encoded;
};

function URLDecode(encoded) {
   // Replace + with ' '
   // Replace %xx with equivalent character
   // Put [ERROR] in output if %xx is invalid.
   var HEXCHARS = "0123456789ABCDEFabcdef"; 
   var plaintext = "";
   var i = 0;
   while (i < encoded.length) {
      var ch = encoded.charAt(i);
	   if (ch == "+") {
	       plaintext += " ";
		   i++;
	   } else if (ch == "%") {
			if (i < (encoded.length-2) 
					&& HEXCHARS.indexOf(encoded.charAt(i+1)) != -1 
					&& HEXCHARS.indexOf(encoded.charAt(i+2)) != -1 ) {
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			} else {
				alert( 'Bad escape combination near ...' + encoded.substr(i) );
				plaintext += "%[ERROR]";
				i++;
			};
		} else {
		   plaintext += ch;
		   i++;
		};
	}; // while

	return (plaintext == '') ? false : plaintext;
};

/* General function from public domain */

/* onReady */
jQuery(document).ready(function($) { // Must use jQuery $ wrapper when running in noConflict mode

	// PNG fix for IE6
	$(document).pngFix();
	
	// Set external links
	TWIG.setExternalLinks();
	
});

/*]]>*/
