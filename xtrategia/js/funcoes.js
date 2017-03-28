function addFlash(_src,_w,_h){
	var novoHtml = '';
 	novoHtml += '  <OBJECT height="'+_h+'" width="'+_w+'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
	novoHtml += '  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" ';
	novoHtml += '  wmode="transparent" ALIGN="">';
	novoHtml += '    <PARAM NAME="movie"   VALUE="'+_src+'">';
	novoHtml += '    <PARAM NAME="quality" VALUE="high">';
	novoHtml += '    <PARAM NAME="wmode"   VALUE="transparent">';
	novoHtml += '    <PARAM NAME="bgcolor" VALUE="#FFFFFF">';
	novoHtml += '    <EMBED wmode="transparent" src="'+_src+'" pluginspage="http://www.macromedia.com/go/getflashplayer" height="'+_h+'" width="'+_w+'"></EMBED>';
	novoHtml += '  </OBJECT>';
  document.write(novoHtml);
}


function open_center(url, name, params, Wwidth, Wheight) {
    Swidth = screen.width;
    Sheight = screen.height;

    Wleft = Math.floor((Swidth / 2) - (Wwidth / 2) - 8);
    Wtop = Math.floor((Sheight / 2) - (Wheight / 2) - 20);

    params = params+",left="+Wleft+",top="+Wtop+",width="+Wwidth+",height="+Wheight;

    window.open(url, name, params);
}


function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}



function MM_jumpMenuGo(selName,targ,restore){ //v3.0
  var selObj = MM_findObj(selName); if (selObj) MM_jumpMenu(targ,selObj,restore);
}
function addFav(){
    var url      = "http://www.zyc.com.br";
    var title    = "ZYC";
    if (window.sidebar) window.sidebar.addPanel(title, url,"");
    else if(window.opera && window.print){
        var mbm = document.createElement('a');
        mbm.setAttribute('rel','sidebar');
        mbm.setAttribute('href',url);
        mbm.setAttribute('title',title);
        mbm.click();
    }
    else if(document.all){window.external.AddFavorite(url, title);}
}
// Setando as variáveis de aumento de fonte

var defaultFontSize = 120;
var maximumFontSize = 400;
var currentFontSize = defaultFontSize;

function resetFontSize() {
	resetSize = defaultFontSize - currentFontSize;
	changeFontSize(resetSize);
}

function changeFontSize(sizeDifference) {
	currentFontSize = currentFontSize + sizeDifference;

	if(currentFontSize > maximumFontSize) { alert('Você já chegou ao tamanho máximo!'); currentFontSize = 300;  }
	else if(currentFontSize < 60) { alert('Você já chegou ao tamanho mínimo!'); currentFontSize = 60;  }

	setFontSize(currentFontSize);
}

function setFontSize(fontSize){ document.getElementById('areatexto').style.fontSize = fontSize + '%'; }


function areaTexto(x) {
	if (x == "A") { changeFontSize(10); }
	else if (x == "D") { changeFontSize(-10); }
	else { resetFontSize(); }
}


