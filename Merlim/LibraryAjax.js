// ============================================================================
// /LibraryAjax.js
// ----------------------------------------------------------------------------
// Nome     : Biblioteca b·sica para AJAX
// Home     : http://home.zevallos.com/
// Criacao  : 11/11/2008 10:27:04 PM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com>
// Versao   : 1.1f
// Local    : BrasÌlia - DF
// Copyright: 97-2008 by Ruben Zevallos(r) Jr.
// License  : Licensed under the terms of the GNU Lesser General Public License
//            http://www.opensource.org/licenses/lgpl-license.php
// ----------------------------------------------------------------------------

sobjXMLHTTP = null;
sblnXMLHTTP = false;

var testcookie = 'ft=valid';
document.cookie = testcookie;

if (document.cookie.indexOf(testcookie) == -1) {
  alert("Os Cookies n„o est„o habilitados neste browser");
}

var sintkeyCode = 0;

var sarrLatin1 = new Array();
var sarrAccent = new Array();

var sparOption = new String();
var sparAction = new String();
var sparTarget = new String();
var sparRefresh = new String();
var sparWhere = new String();
var sparForm = null;
var sstrScriptName = null;
var sparBack = 0;               // Se for diferente de zero, apÛs o Salvar ou Cancelar, volta ‡ p·gina que chamou o formul·rio

var sparPageSize = 10;
var sparPage     = 1;

prepareLatin1();
prepareQueryString();

//=======================================================================
//
//-----------------------------------------------------------------------
function prepareLatin1() {
  arrLatin1 = "#41,#40,quot,lt,gt,amp,aacute,eacute,iacute,oacute,uacute,acirc,ecirc,icirc,ocirc,ucirc,agrave,egrave,igrave,ograve,ugrave,auml,euml,iuml,ouml,uuml,atilde,otilde,ccedil,Aacute,Eacute,Iacute,Oacute,Uacute,Acirc,Ecirc,Icirc,Ocirc,Ucirc,Agrave,Egrave,Igrave,Ograve,Ugrave,Auml,Euml,Iuml,Ouml,Uuml,Atilde,Otilde,Ccedil,copy,reg,#8216,#8217,#8220,#8221,#8211,#8212".split(",");
  arrAccent = ")(\"<>&·ÈÌÛ˙‚ÍÓÙ˚‡ËÏÚ˘‰ÎÔˆ¸„ıÁ¡…Õ”⁄¬ Œ‘€¿»Ã“ŸƒÀœ÷‹√’«©Æ"+String.fromCharCode(145)+String.fromCharCode(146)+String.fromCharCode(147)+String.fromCharCode(148)+String.fromCharCode(150)+String.fromCharCode(151);

  for (x = 0; x < arrLatin1.length; x++) {
    sarrLatin1[arrLatin1[x]] = arrAccent[x];
    sarrAccent[arrAccent[x]] = arrLatin1[x];
  }
}

//=======================================================================
// Prepara o uso da QueryString
//-----------------------------------------------------------------------
function prepareQueryString() {
  // http://sip.istcartoes.com.br:9083
  //                                  /Result/sipBilhete.htm?a=1&t=1&f=1

  strURL = window.location.toString();

  intURL = strURL.indexOf("/", 7);

  // Retira do dominio
  if (intURL) {
    strURL = strURL.substring(intURL);
  }

  sstrScriptName = strURL

  intURL = strURL.indexOf("?");

  // Retira o nome do arquivo corrente + o / inicial
  if (intURL) {
    sstrScriptName = strURL.substring(0, intURL);

    strURL = strURL.substring(intURL + 1) + "&";

    var RegEx = /(\w+)=([^&]+)&/g;

    Matches = RegEx.exec(strURL);

    while (Matches !== null) {
      switch (Matches[1].toLowerCase()) {
        case "o":
          sparOption = Matches[2];
          break;

        case "a":
          sparAction = Matches[2];
          break;

        case "t":
          sparTarget = Matches[2];
          break;

        case "f":
          sparForm = Matches[2];
          break;

        case "ps":
          sparPageSize = parseInt(Matches[2]);
          break;

        case "pg":
          sparPage = parseInt(Matches[2]);
          break;

        case "rf":
          sparRefresh = parseInt(Matches[2]);
          break;

        case "b":
          sparBack = parseInt(Matches[2]);
          break;

        case "w":
          sparWhere = Matches[2];
          break;

      }

      Matches = RegEx.exec(strURL);
    }
  }
}

//=======================================================================
// Ativa o AJAX independente do Browser
//-----------------------------------------------------------------------
function startXMLHTTP() {
  blnResult = true;

  if (!sobjXMLHTTP) {
    try {
      sobjXMLHTTP = new ActiveXObject("Msxml2.XMLHTTP");

    } catch (e) {
      try {
        sobjXMLHTTP = new ActiveXObject("Microsoft.XMLHTTP");

      } catch (e) {
        try {
          sobjXMLHTTP = new XMLHttpRequest();

          if (sobjXMLHTTP.overrideMimeType) {
            sobjXMLHTTP.overrideMimeType('text/html');
          }
        } catch (e) {
          sobjXMLHTTP = false;
        }
      }
    }

    if (!sobjXMLHTTP) {
      alert('N„o foi possÌvel criar uma inst‚ncia XMLHTTP');

      blnResult = false;
    }
  }

  return blnResult;
}

//=======================================================================
//
//-----------------------------------------------------------------------
function ReadHTTP(strURL) {
  ReadHTTPR = null;

  if (startXMLHTTP()) {
    var datOMNow = new Date ();

    var strOMURL = "&x=" + datOMNow.getTime();

    sobjXMLHTTP.open("GET", strURL + strOMURL, false);
    sobjXMLHTTP.send(null);

    // alert(sobjXMLHTTP.readyState);
    // alert(sobjXMLHTTP.status);

    if (sobjXMLHTTP.responseText) {
      ReadHTTPR = sobjXMLHTTP.responseText;
    }
  }

  return ReadHTTPR;
}

//=======================================================================
//
//-----------------------------------------------------------------------
function formHTTP(strURL) {
  formHTTPR = "";

  if (startXMLHTTP()) {
    var datOMNow = new Date ();

    var objFormFields = sobjForm.elements;

    strFields = "";

    for (i = 0; i < objFormFields.length; i++) {
      if (!objFormFields[i].disabled && (objFormFields[i].tagName == "SELECT" || objFormFields[i].tagName == "INPUT" || objFormFields[i].tagName == "TEXTAREA")) {
        if (objFormFields[i].value.length > 0) {
          if (strFields.length > 0) {
            strFields = strFields + "&";
          }

          strFields = strFields + objFormFields[i].name + "=" + encodeURIComponent(accentString2Latin1(objFormFields[i].value));
        }
      }
    }

    // sobjXMLHTTP.onreadystatechange = function() {
    //   if (sobjXMLHTTP.readyState == 4) {
    //     if (sobjXMLHTTP.status == 200) {
    //       formHTTPR = sobjXMLHTTP.responseText;
    //
    //     } else {
    //       alert('Existe algum problema com o Request.\r\n' + strURL);
    //     }
    //   }
    // }

    // document.getElementById('objTarget').innerHTML = strURL + "?" + strFields;

    var strOMURL = "?x=" + datOMNow.getTime();

    sobjXMLHTTP.open('POST', strURL + strOMURL, false);
    sobjXMLHTTP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    sobjXMLHTTP.setRequestHeader("Content-Length", strFields.length);
    sobjXMLHTTP.setRequestHeader("Accept-Charset", "ISO-8859-1");
    sobjXMLHTTP.setRequestHeader("Connection", "close");

    sobjXMLHTTP.send(strFields);

    if (sobjXMLHTTP.readyState == 4) {
      if (sobjXMLHTTP.status == 200) {
        formHTTPR = sobjXMLHTTP.responseText;

      } else {
        alert('Existe algum problema com o Request.\r\n' + strURL);
      }
    }
  }

  return formHTTPR;
}

// ----------------------------------------------------------------
//
// ================================================================
function latin12String(strString) {
  // sarrLatin1["quot"] = '"'
  // sarrAccent['"'] = "quot"

  var RegEx = /&((#\d+)|(\w+));/gi;

  var Matches = RegEx.exec(strString);

  while (Matches !== null) {
    strString = strString.replace("&" + Matches[1] + ";", sarrLatin1[Matches[1]], "g");

    Matches = RegEx.exec(strString);
  }

  return strString;

}
// ----------------------------------------------------------------
//
// ================================================================

// ================================================================
// Converte os acentos da String para Latin 1
// ----------------------------------------------------------------
function accentString2Latin1(strString) {
  arrLatin1 = "#40,#41,lt,gt,amp,aacute,eacute,iacute,oacute,uacute,acirc,ecirc,icirc,ocirc,ucirc,agrave,egrave,igrave,ograve,ugrave,auml,euml,iuml,ouml,uuml,atilde,otilde,ccedil,Aacute,Eacute,Iacute,Oacute,Uacute,Acirc,Ecirc,Icirc,Ocirc,Ucirc,Agrave,Egrave,Igrave,Ograve,Ugrave,Auml,Euml,Iuml,Ouml,Uuml,Atilde,Otilde,Ccedil,copy,reg,#8216,#8217,#8220,#8221,#8211,#8212";
  arrAccent = "()<>&·ÈÌÛ˙‚ÍÓÙ˚‡ËÏÚ˘‰ÎÔˆ¸„ıÁ¡…Õ”⁄¬ Œ‘€¿»Ã“ŸƒÀœ÷‹√’«©Æ"+String.fromCharCode(145)+String.fromCharCode(146)+String.fromCharCode(147)+String.fromCharCode(148)+String.fromCharCode(150)+String.fromCharCode(151);

  arrLatin1 = arrLatin1.split(",");

  accentString2Latin1R = "";

  if (strString && strString.length > 0) {
    accentString2Latin1R = strString;

    for (x = 0; x < strString.length; x++) {

      strChar = strString.charAt(x);

      intChar = arrAccent.indexOf(strChar);

      if (intChar != -1) {
        accentString2Latin1R = accentString2Latin1R.replace(arrAccent.charAt(intChar), "&" + arrLatin1[intChar] + ";");
      }

    }
  }

  return accentString2Latin1R;
}
// ----------------------------------------------------------------
// accentString2Latin1
// ================================================================

// ================================================================
// Guarda a ˙ltima tecla pressionada nos campos
// ----------------------------------------------------------------
function lastKeyPressed(event, strEvent) {
  if (window.event) {
    event = window.event;
  }

  if (event.keyCode) sintkeyCode = event.keyCode;
}


// ================================================================
// Guarda a ˙ltima tecla pressionada nos campos
// ----------------------------------------------------------------
function formatCurrency(num, digits, point, comma) {
  num = num.toString().replace(/\$|\,/g,'');
  if(isNaN(num))
  num = "0";
  sign = (num == (num = Math.abs(num)));
  num = Math.floor(num*100+0.50000000001);
  cents = num%100;
  num = Math.floor(num/100).toString();
  if(cents<10)
  cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
  num = num.substring(0,num.length-(4*i+3))+point+
  num.substring(num.length-(4*i+3));
  if (digits) {
    return (((sign)?'':'-') + '' + num + comma + cents);
  } else {
    return (((sign)?'':'-') + '' + num);
  }
}