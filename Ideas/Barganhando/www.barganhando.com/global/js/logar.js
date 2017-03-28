$(document).ready(function() {
	
	 $('#contador').hide();
	
  $('#contador').css("opacity", "0.8");

 $('#logar').hide();
 
 
  $('#vLogar').click(
   function() {
		$('#logar').slideToggle('normal');
 });
	
	
	  $('.lkn-fechar').click(
   function() {
		$('#logar').slideUp('normal');
 });
 
 
 
 //limitador de caracteres
 //$('#msg').limit('200','#contadorPalavras');
	
});