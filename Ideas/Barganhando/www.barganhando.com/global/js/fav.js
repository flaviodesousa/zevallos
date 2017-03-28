$(document).ready(function(){
$("a.favoritos").click(function(e){
	e.preventDefault(); 
	var bookmarkUrl = this.href;
	var bookmarkTitle = this.title;
 
	if (window.sidebar) { //para o firefox
		window.sidebar.addPanel(bookmarkTitle, bookmarkUrl,"");
	} else if( window.external || document.all) { //fara o IE
		window.external.AddFavorite( bookmarkUrl, bookmarkTitle);
	} else if(window.opera) { // para o Opera
		$("a.favoritos").attr("href",bookmarkUrl);
		$("a.favoritos").attr("title",bookmarkTitle);
		$("a.favoritos").attr("rel","sidebar");
	} else { 
		 alert('Seu browser não suporta essa função');
		 return false;
	}
});
});