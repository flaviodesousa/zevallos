/* http://keith-wood.name/countdown.html
   Brazilian initialisation for the jQuery countdown extension
   Translated by Marcelo Pellicano de Oliveira (pellicano@gmail.com) Feb 2008. */
(function($) {
	$.countdown.regional['pt-BR'] = {
		labels: ['Anos', 'Meses', 'Semanas', 'Dias', 'Horas', 'Min', 'Seg'],
		labels1: ['Anos', 'Meses', 'Semanas', 'Dias', 'Horas', 'Min', 'Seg'],
		compactLabels: ['a', 'm', 's', 'd'],
		whichLabels: null,
		timeSeparator: ':', isRTL: false};
	$.countdown.setDefaults($.countdown.regional['pt-BR']);
})(jQuery);

function serverTime() { 
    var time = null; 
    $.ajax({url: 'global/incs/ServerTime.php', 
        async: false, dataType: 'text', 
        success: function(text) { 
            time = new Date(text); 
        }, error: function(http, message, exc) { 
            time = new Date(); 
    }}); 
    return time; 
	}	