$(document).ready(function(){
$('#sugiraInicio').hide();
 
 
 
 $('.sugira').click(function() {
		$('#sugiraInicio').slideDown(); 
		$(this).parent().html('&nbsp;');
	
  });
  
  //btn-fechar da tela de inicio
     $('.btnFechar').click(function() {
		$('#overlayt, #conteudoInicio').fadeOut(200); 
		$(this).fadeOut(200); 
  });
  
      $("#overlayt").css({'visibility': 'visible', 'opacity': '0', 'height': $(document).height()+"px"}).animate({'opacity': '0.8'}, 500);  
	  
	  $("#conteudoInicio").css({'visibility': 'visible', 'opacity': '0'}).animate({'opacity': '1'}, 500);  


   
   var top;
	var $div = $('#conteudoInicio');
	$(window).bind('scroll resize',function(){
		//$div.html($(this).scrollTop());
		top = $(this).scrollTop() + ($(this).height()-$div.outerHeight())/2;	
		$div.css('top', top+'px');
	});
	$(window).trigger('scroll');
  

 });