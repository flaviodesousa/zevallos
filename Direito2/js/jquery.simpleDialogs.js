/*!
 * jQuery SimpleDialogs Plugin by Gant Laborde of Gantix LLC
 * version: 1.0 (06-APR-2011)
 * @requires jQuery v1.5.1 or later
 * @requires jQueryUI 
 *
 * Examples and documentation at: 	http://www.verveserver.com/SimpleDialogs
 * 									http://plugins.jquery.com/project/SimpleDialogs
 */

(function($) {

	$.fn.simpleDialogs = function(options) {
		
		// Plugin Defaults
		var myDefaults = {
			submitForms: true,
			dialogID: "mySimpleDialogPopUp",
			dynamicDivID: "mySimpleDialogDynamicArea",
			loadingHTML: "Loading...",
		};
	
		// recursively modify depending on options
		options = $.extend(myDefaults, options);
		
		// Dialog Defaults
		var myDialogSettings = {
			autoOpen: false,
			show: "fold",
			width: 600,
			modal: true,
			position: "top",
			hide: "clip",
			buttons: {
				"YES": function() {
					// if submitForms then YES will submit all forms on the dialog
					if(options.submitForms)
					{
						$("#" + options.dialogID + " form").submit();
					} // end if
				},
				"NO": function() {
					$( this ).dialog( "close" );
				}
			}
		};	
	
		// attach div for dynamic dialogs
		var addSection = "<div id='" + options.dialogID + "'><div id='" + options.dynamicDivID + "'>&nbsp;</div></div>";
		$("body").append(addSection);
		var myDialog = $("#" + options.dialogID);
	
		// merge all the dialog settings
		options.dialogSettings = $.extend(myDialogSettings, options.dialogSettings);
		
		// create a popup dialog
		myDialog.dialog(options.dialogSettings);
	
		
		// NOW go to each popup link selected
		return this.each(function(e) {  
								  
			// set the click event
			$(this).click(function(evt) {
	
				var url = $(this).attr('href');
				var myTitle = $(this).attr('title');
				
				// show loading HTML
				$("#" + options.dynamicDivID).html(options.loadingHTML);
				
				// load the dynamic content into the popup
				$("#" + options.dynamicDivID).load(url, options.loadedDialog);
				
				// Configure and open the dialog
				myDialog.dialog('option', 'title', myTitle);
				myDialog.dialog("open");
				
				evt.stopPropagation(); // to prevent event from bubbling up
				evt.preventDefault(); // then cancel the event (if it's cancelable)
			});	// end this.click
		});  // end this.each
	
	}; // end plugin code
		  
})(jQuery);
