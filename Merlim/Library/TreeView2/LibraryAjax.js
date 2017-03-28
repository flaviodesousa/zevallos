sobjXMLHTTP = null;

var testcookie = 'ft=valid';
document.cookie = testcookie;

if (document.cookie.indexOf(testcookie) == -1) {
  alert("Os Cookies não estão habilitados neste browser");
}

//=======================================================================
//
//-----------------------------------------------------------------------
function startXMLHTTP() {
  blnResult = true;

  if (!sobjXMLHTTP) {
    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      sobjXMLHTTP = new XMLHttpRequest();

      if (sobjXMLHTTP.overrideMimeType) {
        sobjXMLHTTP.overrideMimeType('text/html');
      }
    } else if (window.ActiveXObject) { // IE
      try {
        sobjXMLHTTP = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          sobjXMLHTTP = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }

    if (!sobjXMLHTTP) {
      alert('Não foi possível criar uma instância XMLHTTP');
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
      try {
        var objXMLSer = new XMLSerializer();
        ReadHTTPR = objXMLSer.serializeToString(sobjXMLHTTP.responseText);

      } catch (e) {
        ReadHTTPR = sobjXMLHTTP.responseText;
      }
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
function latin12String(strString, strMatch) {
  // arrLatin1 = "aacute,eacute,iacute,oacute,uacute,acirc,ecirc,icirc,ocirc,ucirc,agrave,egrave,igrave,ograve,ugrave,auml,euml,iuml,ouml,uuml,atilde,otilde,ccedil,Aacute,Eacute,Iacute,Oacute,Uacute,Acirc,Ecirc,Icirc,Ocirc,Ucirc,Agrave,Egrave,Igrave,Ograve,Ugrave,Auml,Euml,Iuml,Ouml,Uuml,Atilde,Otilde,Ccedil,copy,reg,amp,quot,lt,gt".split(",");
  // arrAccent = "á,é,í,ó,ú,â,ê,î,ô,û,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ã,õ,ç,Á,É,Í,Ó,Ú,Â,Ê,Î,Ô,Û,À,È,Ì,Ò,Ù,Ä,Ë,Ï,Ö,Ü,Ã,Õ,Ç,©,®,&,\",<,>".split(",");

  arrLatin1 = "lt,gt,amp,aacute,eacute,iacute,oacute,uacute,acirc,ecirc,icirc,ocirc,ucirc,agrave,egrave,igrave,ograve,ugrave,auml,euml,iuml,ouml,uuml,atilde,otilde,ccedil,Aacute,Eacute,Iacute,Oacute,Uacute,Acirc,Ecirc,Icirc,Ocirc,Ucirc,Agrave,Egrave,Igrave,Ograve,Ugrave,Auml,Euml,Iuml,Ouml,Uuml,Atilde,Otilde,Ccedil,copy,reg,#8216,#8217,#8220,#8221,#8211,#8212";
  arrAccent = "<>&áéíóúâêîôûàèìòùäëïöüãõçÁÉÍÓÚÂÊÎÔÛÀÈÌÒÙÄËÏÖÜÃÕÇ©®"+String.fromCharCode(145)+String.fromCharCode(146)+String.fromCharCode(147)+String.fromCharCode(148)+String.fromCharCode(150)+String.fromCharCode(151);

  arrLatin1 = arrLatin1.split(",");

  latin12StringR = "";

  if (strString && strString.length > 0) {
    latin12StringR = strString;

    for (x = 0; x < arrAccent.length; x++) {
      latin12StringR = latin12StringR.replace("&" + arrLatin1[x] + ";", arrAccent.charAt(x));

    }
  }

  return latin12StringR;

}
// ================================================================
// Latin12String
// ----------------------------------------------------------------

// ----------------------------------------------------------------
//
// ================================================================
function accentString2Latin1(strString) {
  arrLatin1 = "lt,gt,amp,aacute,eacute,iacute,oacute,uacute,acirc,ecirc,icirc,ocirc,ucirc,agrave,egrave,igrave,ograve,ugrave,auml,euml,iuml,ouml,uuml,atilde,otilde,ccedil,Aacute,Eacute,Iacute,Oacute,Uacute,Acirc,Ecirc,Icirc,Ocirc,Ucirc,Agrave,Egrave,Igrave,Ograve,Ugrave,Auml,Euml,Iuml,Ouml,Uuml,Atilde,Otilde,Ccedil,copy,reg,#8216,#8217,#8220,#8221,#8211,#8212";
  arrAccent = "<>&áéíóúâêîôûàèìòùäëïöüãõçÁÉÍÓÚÂÊÎÔÛÀÈÌÒÙÄËÏÖÜÃÕÇ©®"+String.fromCharCode(145)+String.fromCharCode(146)+String.fromCharCode(147)+String.fromCharCode(148)+String.fromCharCode(150)+String.fromCharCode(151);

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
// ================================================================
// accentString2Latin1
// ----------------------------------------------------------------