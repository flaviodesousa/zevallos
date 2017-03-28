<?php
/**
 * ZTag
 *
 * Processa as novas ZTags
 *
 * @package ztag
 * @subpackage template
 * @version 1.0
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2007-2010 by Ruben Zevallos(r) Jr.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://ztag.zyc.com.br> *
   */

define("ztagVersion", 1.0, 1);
define("ztagVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * ztag_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function ztag_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * ztag_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function ztag_version() {
  return ztagVersion." ".ztagVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * ztag_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function ztag_compare($version) {
  return ztagVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * ztag_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
 * </code>
 *
 * @param integer $tagId array id of current zTag of $arrayTag array
 * @param string $tagFunction name of zTag function
 * @param array $arrayTag array with all compiled zTags
 * @param array $arrayTagId array with all Ids values
 * @param array $arrayOrder array with zTag executing order
 *
 * @since 1.0
 */
function ztag_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrMes = explode(",", ",janeiro,fevereiro,março,abril,maio,junho,julho,agosto,setembro,outubro,novembro,dezembro");
  $arrSemana = explode(",", ",domingo,segunda-feira,terça-feira,quarta-feira,quinta-feira,sexta-feira,sábado");

  $arrToday = getdate();

  // echo "<pre>";
  // print_r($arrMes);
  // echo "</pre>";

  $blnLegacy = $arrayTag[$tagId][ztagLegacyTag];

  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId       = $arrParam["id"];
  $strName     = $arrParam["name"];
  $strURL      = $arrParam["url"];
  $strFile     = $arrParam["file"];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
   /*+
     * Model
     *
     * <code>
     * <ztag:model id="CaminhoAna">
     * </ztag:model>
     * </code>
     *
     * @param string id="value"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "model":
      if (!$strId) $errorMessage .= "<br />Missing \"id\" parameter";

      if ($arrayTag[$tagId][ztagContentWidth]) {
        $strContent = $arrayTag[$tagId][ztagContent];

        $arrayTagId[$strId][ztagIdValue]  = $strContent;
        $arrayTagId[$strId][ztagIdLength] = strlen($strContent);
        $arrayTagId[$strId][ztagIdType]   = idTypeModel;

      }
      break;

   /*+
     * Field
     *
     * <code>
     * <ztag:field sql="" name="Pr.Nome" />
     * </code>
     *
     * @param string sql=""
     * @param string name="Pr.Nome"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "field":
      $arrayTag[$tagId][ztagResult] = "";
      break;

   /*+
     * FileName
     *
     * <code>
     * <ztag:HTMLFileName />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "htmlfilename":
      $arrayTag[$tagId][ztagResult] = $_SERVER["SCRIPT_NAME"];
      break;

   /*+
     * FileName
     *
     * <code>
     * <ztag:TemplateName />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "templatename":
      $arrayTag[$tagId][ztagResult] = templateFile;
      break;

   /*+
     * RealURL
     *
     * <code>
     * <ztag:RealURL />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "realurl":
      $arrayTag[$tagId][ztagResult] = $_SERVER["SCRIPT_NAME"];
      break;

   /*+
     * UserDomain
     *
     * <code>
     * <ztag:UserDomain />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "userdomain":
      $arrayTag[$tagId][ztagResult] = $_SERVER["SERVER_NAME"];
      break;

   /*+
     * UserScriptName
     *
     * <code>
     * <ztag:UserScriptName />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "userscriptname":
      $arrayTag[$tagId][ztagResult] = $_SERVER["SCRIPT_NAME"];
      break;

   /*+
     * UserRemoteAddress
     *
     * <code>
     * <ztag:UserRemoteAddress />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "userremoteaddress":
      $arrayTag[$tagId][ztagResult] = $_SERVER["REMOTE_ADDR"];
       break;

   /*+
     * UserDomainReferer
     *
     * <code>
     * <ztag:UserDomainReferer />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "userdomainreferer":
      $arrayTag[$tagId][ztagResult] = $_SERVER["HTTP_REFERER"];
      break;

   /*+
     * ParameterOption
     *
     * <code>
     * <ztag:ParameterOption />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parameteroption":
      $arrayTag[$tagId][ztagResult] = parOption;
      break;

   /*+
     * ParameterTarget
     *
     * <code>
     * <ztag:ParameterTarget />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parametertarget":
      $arrayTag[$tagId][ztagResult] = parTarget;
      break;

   /*+
     * ParameterAction
     *
     * <code>
     * <ztag:ParameterAction />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parameteraction":
      $arrayTag[$tagId][ztagResult] = parAction;
      break;

   /*+
     * ParameterTemplate
     *
     * <code>
     * <ztag:ParameterTemplate />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parametertemplate":
      $arrayTag[$tagId][ztagResult] = templateFile;
      break;

   /*+
     * ParameterFind
     *
     * <code>
     * <ztag:ParameterFind />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parameterfind":
      $arrayTag[$tagId][ztagResult] = $_GET["txtfind"];
      break;

   /*+
     * Parameter
     *
     * <code>
     * <ztag:Parameter name="o" />
     * </code>
     *
     * @param string name="o"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "parameter":
      if ($blnLegacy) $strName = $arrParam[1];
      if (!$strName) $errorMessage .= "<br />Missing \"name\" parameter";

      $arrayTag[$tagId][ztagResult] = $_GET["$strName"];
      break;


    // OM - Onyx Manager:
    // <ztag:WebPublisher />
    case "webpublisher":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:WebCompressor />
    case "webcompressor":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:WebXHTML />
    case "webxhtml":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:LastReferenceID />
    case "lastreferenceid":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:LastArquivo />
    case "lastarquivo":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;


    // Cookie:
    // <ztag:UserCookieSet />
    case "usercookieset":
      break;

    // <ztag:UserCookieGet />
    case "usercookieget":
      break;


    // Data de Hoje:
    // <ztag:TodayDate />
    case "todaydate":
      $arrayTag[$tagId][ztagResult] = date("m/d/Y");
      break;

    // <ztag:TodayDateBR />
    case "todaydatebr":
      $arrayTag[$tagId][ztagResult] = date("d/m/Y");
      break;

    // <ztag:TodayDateDay />
    case "todaydateday":
      $arrayTag[$tagId][ztagResult] = date("j");
      break;

    // <ztag:TodayDateDayFull />
    case "todaydatedayfull":
      $arrayTag[$tagId][ztagResult] = date("d");
      break;

      // <ztag:TodayDateDayFullOrdinalSuffix />
    case "todaydatedayordinalsuffix":
      $arrayTag[$tagId][ztagResult] = date("S");
      break;

    // <ztag:TodayDateDay />
    case "todaydateday":
      $arrayTag[$tagId][ztagResult] = date("j");
      break;

    // <ztag:TodayDateMonth />
    case "todaydatemonth":
      $arrayTag[$tagId][ztagResult] = date("n");
      break;

    // <ztag:TodayDateMonthFull />
    case "todaydatemonthfull":
      $arrayTag[$tagId][ztagResult] = date("m");
      break;

    // <ztag:TodayDateMonthNameBR />
    case "todaydatemonthnamebr":
      $arrayTag[$tagId][ztagResult] = $arrMes[date("n")];
      break;

    // <ztag:TodayDateMonthNameBR1 />
    case "todaydatemonthnamebr1":
      $arrayTag[$tagId][ztagResult] = substr($arrMes[date("n")], 0, 1);
      break;

    // <ztag:TodayDateMonthNameBR2 />
    case "todaydatemonthnamebr2":
      $arrayTag[$tagId][ztagResult] = substr($arrMes[date("n")], 0, 2);
      break;

    // <ztag:TodayDateMonthNameBR3 />
    case "todaydatemonthnamebr3":
      $arrayTag[$tagId][ztagResult] = substr($arrMes[date("n")], 0, 3);
      break;

    // <ztag:TodayDateMonthName />
    case "todaydatemonthname":
      $arrayTag[$tagId][ztagResult] = strtolower(date("F"));
      break;

    // <ztag:TodayDateMonthName1 />
    case "todaydatemonthname1":
      $arrayTag[$tagId][ztagResult] = substr(strtolower(date("F")), 0, 1);
      break;

    // <ztag:TodayDateMonthName2 />
    case "todaydatemonthname2":
      $arrayTag[$tagId][ztagResult] = substr(strtolower(date("F")), 0, 2);
      break;

    // <ztag:TodayDateMonthName3 />
    case "todaydatemonthname3":
      $arrayTag[$tagId][ztagResult] = substr(strtolower(date("F")), 0, 3);
      break;

    // <ztag:TodayDateYear />
    case "todaydateyear":
      $arrayTag[$tagId][ztagResult] = date("y");
      break;

    // <ztag:TodayDateYearFull />
    case "todaydateyearfull":
      $arrayTag[$tagId][ztagResult] = date("Y");
      break;

    // <ztag:TodayDateHour />
    case "todaydatehour":
      $arrayTag[$tagId][ztagResult] = date("G");
      break;

    // <ztag:TodayDateHourFull />
    case "todaydatehourfull":
      $arrayTag[$tagId][ztagResult] = date("H");
      break;

    // <ztag:TodayDateMinute />
    case "todaydateminute":
      $arrayTag[$tagId][ztagResult] = intval(date("i"));
      break;

    // <ztag:TodayDateMinuteFull />
    case "todaydateminutefull":
      $arrayTag[$tagId][ztagResult] = date("i");
      break;

    // <ztag:TodayDateSecond />
    case "todaydatesecond":
      $arrayTag[$tagId][ztagResult] = intval(date("s"));
      break;

    // <ztag:TodayDateSecondFull />
    case "todaydatesecondfull":
      $arrayTag[$tagId][ztagResult] = date("s");
      break;

    // <ztag:TodayDateWeekday />
    case "todaydateweekday":
      $arrayTag[$tagId][ztagResult] = date("w");
      break;

    // <ztag:TodayDateWeekdayFull />
    case "todaydateweekdayfull":
      $arrayTag[$tagId][ztagResult] = "0".date("w");
      break;

    // <ztag:TodayDateWeekdayNameBR />
    case "todaydateweekdaynamebr":
      $arrayTag[$tagId][ztagResult] = $arrSemana[date("w")];
      break;

    // <ztag:TodayDateWeekdayNameBR1 />
    case "todaydateweekdaynamebr1":
      $arrayTag[$tagId][ztagResult] = substr($arrSemana[date("w")], 0, 1);
      break;

    // <ztag:TodayDateWeekdayNameBR2 />
    case "todaydateweekdaynamebr2":
      $arrayTag[$tagId][ztagResult] = substr($arrSemana[date("w")], 0, 2);
      break;

    // <ztag:TodayDateWeekdayNameBR3 />
    case "todaydateweekdaynamebr3":
      $arrayTag[$tagId][ztagResult] = substr($arrSemana[date("w")], 0, 3);
      break;

    // <ztag:TodayDateWeekdayName />
    case "todaydateweekdayname":
      $arrayTag[$tagId][ztagResult] = strtolower($arrToday["weekday"]);
      break;

    // <ztag:TodayDateWeekdayName1 />
    case "todaydateweekdayname1":
      $arrayTag[$tagId][ztagResult] = substr(strtolower($arrToday["weekday"]), 0, 1);
      break;

    // <ztag:TodayDateWeekdayName2 />
    case "todaydateweekdayname2":
      $arrayTag[$tagId][ztagResult] = substr(strtolower($arrToday["weekday"]), 0, 2);
      break;

    // <ztag:TodayDateWeekdayName3 />
    case "todaydateweekdayname3":
      $arrayTag[$tagId][ztagResult] = substr(strtolower($arrToday["weekday"]), 0, 3);
      break;

    // <ztag:TodayDateWeekofYear />
    case "todaydateweekofyear":
      $arrayTag[$tagId][ztagResult] = date("W");
      break;

    // <ztag:TodayDateWeekofYearFull />
    case "todaydateweekofyearfull":
      $arrayTag[$tagId][ztagResult] = date("W");
      break;

    // <ztag:TodayDateQuarter />
    case "todaydatequarter":
      $arrayTag[$tagId][ztagResult] = floor(date('m', strtotime("12/01/2010")) / 3.1) + 1;
      break;

    // <ztag:TodayDateQuarterFull />
    case "todaydatequarterfull":
      $arrayTag[$tagId][ztagResult] = "0".(floor(date('m', strtotime("12/01/2010")) / 3.1) + 1);
      break;

    case "bodyrightpicture":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "box":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "box2":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "boxcalendario":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "boxfather":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "conf":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "currentdate":
      $arrayTag[$tagId][ztagResult] = "<script type=\"text/javascript\">"
                         ."hoje = new Date();"
                         ."dia = hoje.getDate();"
                         ."dias = hoje.getDay();"
                         ."ano = hoje.getFullYear();"
                         ."if (dia < 10) dia = '0' + dia;"
                         ."nd = 'domingo,segunda-feira,terça-feira,quarta-feira,quinta-feira,sexta-feira,sábado'.split(',');"
                         ."nm = 'janeiro,fevereiro,março,abril,maio,junho,julho,agosto,setembro,outubro,novembro,dezembro'.split(',');"
                         ."document.write (nd[dias] + ', ' + dia + ' de ' + nm[hoje.getMonth()] + ' de ' + ano);"
                         ."</script>";
      break;
    // <ztag:CurrentUser />
    case "currentuser":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:CurrentUserPages />
    case "currentuserpages":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:CurrentUserStart />
    case "currentuserstart":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:CurrentUserStartTime />
    case "currentuserstarttime":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:DateTime />
    case "datetime":
      $arrayTag[$tagId][ztagResult] = date("d/m/Y H:i:s");
      break;

    // <ztag:DefaultDomain />
    case "defaultdomain":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:DefaultPaginaASP />
    case "defaultpaginaasp":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:DefaultPaginaHTM />
    case "defaultpaginahtm":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:Form name="temp" />
    case "form":
      if ($blnLegacy) $strName = $arrParam[1];
      if (!$strName) $errorMessage .= "<br />Missing \"name\" parameter";

      $arrayTag[$tagId][ztagResult] = $_REQUEST[$strName]."as";
      break;

    case "freetextsearch":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "iframe":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "lastimage":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "lastnews":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "loadfile":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "loadfilejs":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menufooterprint":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menufooterrecomendation":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menufooterscript":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menuleft":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menuleftjs":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menuright":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "menurightjs":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    // <ztag:PageBodySubTitle />
    case "pagebodysubtitle":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:PageBodyTitle />
    case "pagebodytitle":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:PageTemplateTitle />
    case "pagetemplatetitle":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:PageTitle />
    case "pagetitle":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:PrintingDateTime />
    case "printingdatetime":
      $arrayTag[$tagId][ztagResult] = date("d/m/Y H:i:s");
      break;

    case "processxml":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "processxmljs":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    // <ztag:ReadHTTP url="http://www.google.com" />
    case "readhttp":
      if ($blnLegacy) $strURL = $arrParam[1];
      if (!$strURL) $errorMessage .= "<br />Missing \"url\" parameter";

      $arrayTag[$tagId][ztagResult] = ztag_lib_readHTTP($strURL);
      break;

    // <ztag:ReadHTTPJS url="http://sg.d2.net.br:2023/ZTag/ZTagLegadoComplexas.txt" file="/ZTag/ZTagLegadoComplexas.js" />
    case "readhttpjs":
      if ($blnLegacy) $strURL = $arrParam[1];
      if (!$strURL) $errorMessage .= "<br />Missing \"url\" parameter";

      if ($blnLegacy) $strFile = $arrParam[2];
      if (!$strFile) $errorMessage .= "<br />Missing \"file\" parameter";

      $strFile = str_replace("\\", "/", $strFile);

      if (substr($strFile, 0, 1) == "/") $strFile = substr($strFile, 1, strlen($strFile));

      $strFileName = SiteRootDir.$strFile;

      $readHTTP = ztag_lib_readHTTP($strURL);

      // @TODO fazer ajustes e executar as funções equivalentes ao ConvertPaginas e FixPage do OM3

      $readHTTP = str_replace("\"", "\\\"", $readHTTP);
      $readHTTP = str_replace("\r\n", "\"\r\n\+\"", $readHTTP);

      if (substr($readHTTP, strlen($readHTTP)-1, 1) == "\"") $readHTTP = substr($readHTTP, 0, strlen($readHTTP) - 1);
      if (substr($readHTTP, strlen($readHTTP)-1, 1) == " ") $readHTTP = substr($readHTTP, 0, strlen($readHTTP) - 1);
      if (substr($readHTTP, strlen($readHTTP)-1, 1) == "+") $readHTTP = substr($readHTTP, 0, strlen($readHTTP) - 1);

      $readHTTP = "document.write(\"$readHTTP\");\r\n";

      $handleFile = fopen($strFileName, "w");
      fwrite($handleFile, $readHTTP, strlen($readHTTP));
      fclose($handleFile);

      $arrayTag[$tagId][ztagResult] = "<script language=\"javascript\" src=\"/$strFile\" type=\"text/JavaScript\"></script>";
      break;

    case "reference":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "savefile":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    // <ztag:ScriptName />
    case "scriptname":
      $arrayTag[$tagId][ztagResult] = ScriptName;
      break;

    case "sendhttp":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "sendmail":
      /*
      Name: Teste de uso de formulário para o envio de e-mail
      Version: 1.0
      Author: Ruben Zevallos Jr.
      Created: 1/22/2007 4:38AM
      Update:
      From: Ruben Zevallos Jr. <ruben@zevallos.com.br>
      ReplyTo: Ruben Zevallos Jr. <ruben@zevallos.com.br>
      To: Ruben Zevallos Jr. <ruben@wez.com.br>, Ivo Correia <rubenzevallos@hotmail.com>
      Subject: Assunto da mensagem
      Priority: 1 to 5
      ContentType: text/html
      SMTPServer: mail.wez.com.br
      STMPPort: 25
      SMTPUsername: ruben@wez.com.br
      STMPPassword:
      BodyBegin:
      */

      // @TODO Trabalhar para finalizar esta
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "siteactive":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "sitelast":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "sitelasttime":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "sitelogged":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    // <ztag:SiteOnline />
    case "siteonline":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    // <ztag:SiteOnlineTime />
    case "siteonlinetime":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    // <ztag:SitePages />
    case "sitepages":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    // <ztag:SiteUsers />
    case "siteusers":
      $arrayTag[$tagId][ztagResult] = "<!-- Sorry! Not implemented yet -->";
      break;

    case "sql":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "sqlsave":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "sqlsavejs":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    case "trackingline":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    // <ztag:WebPublisherVersion />
    case "webpublisherversion":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    // <ztag:WebWebPublisherVersionDate />
    case "webwebpublisherversiondate":
      $arrayTag[$tagId][ztagResult] = "<!-- Deprecate at OM4 -->";
      break;

    case "xml":
      $arrayTag[$tagId][ztagResult] = "";
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

// <ztag:sql times="6" model="CaminhoAna" where="Pe.Inclusao between 1/marco to 31/marco " orderby="C.Nome, Pr.Nome">
// </ztag:sql>

/*
<ZTagSQL(15).Menu."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND LEFT(Pa.pagOrdem, 4) = 'Menu'"."Pa.pagOrdem, Pa.pagNome">

<li>
<a href="<ZTagField.PapagURLAlternativo>" class="<ZTagField.P1pagArquivo4> cufon"><ZTagField.PapagNome></a>
<ul><ZTagSQL(15).MenuItem."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND Pa.pagPai = <ZTagField.PapagCodigo>"."Pa.pagOrdem, Pa.pagNome"></ul>
</li>

<ZTagSQL(15).Menu."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND LEFT(Pa.pagOrdem, 4) = 'Menu'"."Pa.pagOrdem, Pa.pagNome">

<li>
<a href="<ZTagField.PapagURLAlternativo>" class="<ZTagField.P1pagArquivo4> cufon"><ZTagField.PapagNome></a>
<ul><ZTagSQL(15).MenuItem."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND Pa.pagPai = <ZTagField.PapagCodigo>"."Pa.pagOrdem, Pa.pagNome"></ul>
</li>

<ztag:sql var="fieldMenu" times="15" where="P1.pagReferencia IN (0, 10) AND P1.pagAtivo = 1 AND LEFT(P1.pagOrdem, 4) = 'Menu'" orderby="P1.pagOrdem, P1.pagNome">
  <li><zhtml:a href="$fieldMenu[P1pagURLAlternativo]" class="$fieldMenu[P1pagArquivo4] cufon" value="$fieldMenu[P1pagNome]" />
	  <ul><ztag:sql var="fieldMenuItem" times="15" where="Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND Pa.pagPai = $field[PapagCodigo]>" orderby="Pa.pagOrdem, Pa.pagNome">
	    <li><zhtml:a href="$fieldMenuItem[PapagURLAlternativo]" value="&raquo; $fieldMenuItem[PapagNome]" /></li>
      </ztag:sql>
	  </ul>
  </li>
</ztag:sql>

-- Proposta do tradicional
<ZTagSQL(15).Menu."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND LEFT(Pa.pagOrdem, 4) = 'Menu'"."Pa.pagOrdem, Pa.pagNome" />
	<li>
	<a href="<ZTagField.PapagURLAlternativo />" class="<ZTagField.P1pagArquivo4 /> cufon"><ZTagField.PapagNome /></a>
	<ul><ZTagSQL(15).MenuItem."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND Pa.pagPai = <ZTagField.PapagCodigo>"."Pa.pagOrdem, Pa.pagNome" />
	 <li><a href="<ZTagField.PapagURLAlternativo />">&raquo; <ZTagField.PapagNome /></a></li>
	</ZTagSQL></ul>
	</li>
</ZTagSQL>

-- Método tradicional - Updated
<ZTagSQL(15).Menu."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND LEFT(Pa.pagOrdem, 4) = 'Menu'"."Pa.pagOrdem, Pa.pagNome" />

<li>
<a href="<ZTagField.PapagURLAlternativo />" class="<ZTagField.P1pagArquivo4 /> cufon"><ZTagField.PapagNome /></a>
<ul><ZTagSQL(15).MenuItem."Pa.pagReferencia IN (0, 10) AND Pa.pagAtivo = 1 AND Pa.pagPai = <ZTagField.PapagCodigo>"."Pa.pagOrdem, Pa.pagNome" /></ul>
</li>


<li><a href="<ZTagField.PapagURLAlternativo />">&raquo; <ZTagField.PapagNome /></a></li>

*/
function ztag_SQL2($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
	global $dbHandleDefault;

  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId       = $arrParam["id"];

  $errorMessage = "";

  if ($arrayTag[$tagId][ztagLegacyTag]) {
  	$arrParam["template"] = $arrParam[1];
    $arrParam["where"]    = $arrParam[2];
    $arrParam["orderby"]  = $arrParam[3];

  }

  $strTemplate = $arrParam["template"];
  $strTimes    = $arrParam["times"];
  $strModel    = $arrParam["model"];
  $strWhere    = $arrParam["where"];
  $strOrderBy  = $arrParam["orderby"];

  if ($arrayTag[$tagId][ztagContentWidth]) {
  	$strContent = $arrayTag[$tagId][ztagContent];

  } else if ($strTemplate) {
    $strFileName = str_replace("\\", "/", $strTemplate);

  	if (strpos($strFileName, "/") === false) $strFileName = "/Templates/SQL".$strFileName.".htm";

		if ($strFileName) {
		  if (substr($strFileName , 0, 1) == "/") $strFileName = substr($strFileName, 1);

		  $strFileName = SiteRootDir.$strFileName;

		  // @TODO incluir validações mais fortes para a abertura dos arquivos
		  if (!file_exists($strFileName)) {
		    $errorMessage .= "<br />File \"$strFileName\" not found!";
		  } else {
		  	$handleFile = fopen($strFileName, "r");

		  	$strContent = fread($handleFile, filesize($strFileName));

		  	fclose($handleFile);
		  }
		}
  }

  $errorMessage .= ztagParamCheck($arrParam, "where,orderby");

  echo "<pre>".htmlentities($strContent)."</pre>";
  /*
    FROM pubPaginas P1
    LEFT JOIN pubPaginas P2 ON P1.pagPai = P2.pagCodigo
    LEFT JOIN pubPaginas P3 ON P2.pagPai = P3.pagCodigo
    LEFT JOIN pubPaginas P4 ON P3.pagPai = P4.pagCodigo
    LEFT JOIN pubPaginas P5 ON P4.pagPai = P5.pagCodigo
    LEFT JOIN pubPaginas P6 ON P5.pagPai = P6.pagCodigo
  */

  if ($arrayTag[$tagId][ztagContentWidth]) {
    $strContent = $arrayTag[$tagId][ztagContent];

    $arrayTagId[$strId][ztagIdValue]  = $strContent;
    $arrayTagId[$strId][ztagIdLength] = strlen($strContent);
    $arrayTagId[$strId][ztagIdType]   = idTypeSQL;

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

// ----------------------------------------------------------------
// Retorna com a URL usando o cURL
// ================================================================
function ztag_lib_readHTTP($strURL) {
  $objCURL = curl_init();

  curl_setopt($objCURL, CURLOPT_URL, $strURL);
  curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);
  // curl_setopt($objCURL, CURLOPT_HEADER, 1);
  // curl_setopt($objCURL, CURLINFO_HEADER_OUT, 1);
  curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

  // curl_setopt($objCURL, CURLOPT_COOKIE, "FGTServer=34B68E04678B6417793617BE38F54F47B3BBFF47; __utma=60534895.122979554.1255118786.1266121550.1266123845.13; __utmz=60534895.1266020117.10.3.utmcsr=stf.gov.br|utmccn=(referral)|utmcmd=referral|utmcct=/portal_stj/; ASPSESSIONIDSQBRSDCQ=BLCIHAPCFDAHJLEACAFCNBNJ; __utmc=60534895; ASPSESSIONIDSSARQACQ=DDCNCAPCNJNBJHDPPFPJCCEK; ASPSESSIONIDSQAQTCDQ=NFDCHLIDEIMEAAJNPFIJFHDB; ASPSESSIONIDSSCRTCBQ=PJIFCLIDNJLIHCOJGLHHLBJE; ASPSESSIONIDSQBSRBCQ=HLJGGFLDINBPGOECFNLGPMJA; __utmb=60534895.4.10.1266123845" );

  $strResult = curl_exec($objCURL);

  if (!$strResult)
    $strResult = "<br /><b>Curl error</b>: ".curl_error($objCURL);

  // $arrURL = curl_getinfo($objCURL);
  // echo "<br />http_code=".$arrURL["http_code"];
  // echo "<br />content_type=".v["content_type"];

  if (isUTF8($strResult)) $strResult = utf8_decode($strResult);

  return $strResult;

}
