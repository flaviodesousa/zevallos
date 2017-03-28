$().ready(function(){

		$('#listas').accordion({
			event: 'click',
			active: '.ativo',
			selectedClass: 'ativo',
			header: "dt",
			//animated: 'easeslide',
			navigation: false,
			autoheight: false

		})
		
	
	});