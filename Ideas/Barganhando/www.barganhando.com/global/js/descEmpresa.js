$(function() {

	$('.desc').hide(0); 
	$('.desc:first').show(0);
	
	$('.col-um ul li a:first').addClass("ativo");
	
	$('.col-um ul li').click(function() {
		$('.col-um ul li a').removeClass("ativo");
		$('a', this).addClass("ativo");
		
		n = $('.col-um ul li').index(this);
		
		$('.desc').stop(true, true).fadeOut(0);
		$('.desc').eq(n).stop(true, true).fadeIn('fast');
		
	}, function() {
	
	});
});