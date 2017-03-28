// ============================================================================
// OMPageCounter.js
// ----------------------------------------------------------------------------
// Nome     : Contador de acesso ao site
// Home     : http://ruben.zevallos.com/
// Criacao  : 12/16/2006 9:57PM
// Autor    : Ruben Zevallos Jr. <ruben@zevallos.com>
// Versao   : 1.0
// Local    : São Luís - MA
// Copyright: 97-2006 by Ruben Zevallos(r) Jr.
// License  : Licensed under the terms of the GNU Lesser General Public License
//            http://www.opensource.org/licenses/lgpl-license.php
// ----------------------------------------------------------------------------

//var regEx = new RegExp("(ApresentaSite\.asp\?o=100\&t=(\d+))|Pagina(\d+).htm)", "ig");
//var regEx = new RegExp("Pagina(\d+).htm", "ig");

intOMVezes = 0;
strOMS = "";

// var regOMEx = /^((\w+)\/(\d+)/(\w+)\/(\d+))\/([^/]+)\/*$/i;

var strOMURL = document.URL;

// http://www.direito2.com.br/stf/2007/jun/15/stf-recebe-pedido-de-habeas-para-soldados-da

// Tira o http://
if (strOMURL.substring(0, 7) == 'http://')
  strOMURL = strOMURL.substring(7);

// Tira ou https://
if (strOMURL.substring(0, 8) == 'https://')
  strOMURL = strOMURL.substring(8);

var datOMNow = new Date ();

if (regOMEx.test(strOMURL)) {
  // var regOMEx = /ApresentaSite\.asp\?o=100&t=(\d+)/i;

  var arrOMURL = regOMEx.exec(strOMURL);

  var strOMImg = "d2PageCounter.php?t=" + arrOMURL[1]  + "&x=" + datOMNow.getTime();

  // [0]=Pagina999.htm
  // [1]=999

} else {
  strOMURL = strOMURL.substring(strOMURL.indexOf("/"));

  // /stf-recebe-pedido-de-habeas-para-soldados-da
  strFile = strOMURL.substring(strOMURL.lastIndexOf("/"));

  // /stf/2007/jun/15
  strDir = strOMURL.substring(0, strOMURL.length - strFile.length);

  // OMPageCounter.asp?p=10&d=/stf/2007/jun/15&f=/stf-recebe-pedido-de-habeas-para-soldados-da&x=1182084376062
  var strOMImg = "d2PageCounter.php?p=10&d=" + strDir + "&f=" + strFile + "&x=" + datOMNow.getTime();

}

if (strOMImg != null) {
  document.write('<script src="/' + strOMImg + '" type="text/JavaScript"></script>');
}
