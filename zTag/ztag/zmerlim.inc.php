<?php
/**
 * zMerlim
 *
 * Process zTags for Merlim news and pages scrapper
 *
 * @package ztag
 * @subpackage d2
 * @category Environment
 * @version 1.0
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://www.gnu.org/licenses/gpl.html - GNU Public License
 * @copyright 2010 by Ruben Zevallos(r) Jr.
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
 * and is licensed under the GNU GPL. For more information, see
 * <http://http://code.google.com/p/ztag/>
 */

define("zmerlimVersion", 1.0, 1);
define("zmerlimVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zmerlim_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zmerlim_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zmerlim_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zmerlim_version() {
  return zmerlimVersion." ".zmerlimVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zmerlim_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zmerlim_compare($version) {
  return zmerlimVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zmerlim_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zmerlim_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strURL   = $arrParam["url"];
  $strVar   = $arrParam["var"];

  $strUse   = $arrParam["use"];
  $strValue = $arrParam["value"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Read a HTML from a URL with CURL
     *
     * <code>
     * <zmerlim:read url="http://www.direito2.com.br" var="urlVar" errorno="errornoVar" error="errorVar" />
     * </code>
     *
     * @param string url="http://www.direito2.com.br" URL to be retrieve
     * @param string var="urlVar" var where the result will be saved
     */
    case "read":
      $errorMessage .= ztagParamCheck($arrParam, "url,var");

      $objCURL = curl_init();

      curl_setopt($objCURL, CURLOPT_URL, $strURL);
      curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);

      // curl_setopt($objCURL, CURLOPT_NOBODY, 1);

      curl_setopt($objCURL, CURLOPT_HEADER, 0);

      curl_setopt($objCURL, CURLOPT_CONNECTTIMEOUT, 10);

      curl_setopt($objCURL, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($objCURL, CURLOPT_MAXREDIRS, 10);

      curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

      // curl_setopt($objCURL, CURLOPT_COOKIE, "ASPSESSIONIDCSCARQCD=GHEFGIDCHDCLEEMGAKPEKPPO; __utma=15381288.711762829.1282791344.1282791344.1282791344.1; __utmb=15381288.8.10.1282791344; __utmc=15381288; __utmz=15381288.1282791344.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)" );

      $strResult = curl_exec($objCURL);

      if (curl_errno($objCURL)) {
        $strErrorNo = $arrParam["errorno"];
        $strError   = $arrParam["error"];

        $errorMessage .= "<br /><b>Curl error</b>: ".curl_errno($objCURL)." - ".curl_error($objCURL);

        if (strlen($strErrorNo)) {
          $arrayTagId["$".$strErrorNo][ztagIdValue] = curl_errno($objCURL);
          $arrayTagId["$".$strErrorNo][ztagIdLength] = 0;

          $arrayTagId["$".$strErrorNo][ztagIdType] = idTypeFVar;
        }

        if (strlen($strError)) {
          $arrayTagId["$".$strError][ztagIdValue] = curl_error($objCURL);
          $arrayTagId["$".$strError][ztagIdLength] = strlen($strResult);

          $arrayTagId["$".$strError][ztagIdType] = idTypeFVar;
        }
      }

      if (strlen($strVar) && strlen($strResult)) {
        $arrayTagId["$".$strVar][ztagIdValue] = $strResult;
        $arrayTagId["$".$strVar][ztagIdLength] = strlen($strResult);

        $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;
      }

      // $arrcURL = curl_getinfo($objCURL);

      // echo "<br />http_code=".$arrcURL["http_code"];
      // echo "<br />content_type=".$arrcURL["content_type"];

      // if (isUTF8($strResult)) $strResult = utf8_decode($strResult);
      curl_close($objCURL);

      break;

    /*+
     * Read a HTML from a URL with CURL
     *
     * <code>
     * <zmerlim:tidy use="htmlVar" />
     * </code>
     *
     * @param string url="http://www.direito2.com.br" URL to be retrieve
     * @param string var="urlVar" var where the result will be saved
     */
    case "tidy":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      $arrOptions = array(
           "drop-proprietary-attributes" => true,
           "drop-font-tags" => true,
           "drop-empty-paras" => true,
           "fix-backslash" => true,
           "hide-comments" => true,
           "join-classes" => true,
           "join-styles" => true,
           "word-2000" => true,
           "output-xhtml" => true,
           "clean" => true,
           "indent" => false,
           "indent-spaces" => 0,
           "logical-emphasis" => true,
           "lower-literals" => true,
           "quote-ampersand" => true,
           "quote-marks" => true,
           "quote-nbsp" => true,
           "wrap" => 0,
           "show-body-only" => true,
           "merge-divs" => "auto",
           "merge-spans" => "auto");

      // ascii-chars
      // utf8

      if ($strUse) $strContent = $arrayTagId["$".$strUse][ztagIdValue];

      $objTidy = tidy_parse_string($strContent, $arrOptions);

      $objTidy->cleanRepair();

      $strResult = str_replace("\r\n", "", $objTidy);

      if (strlen($strUse) && strlen($strResult)) {
        $arrayTagId["$".$strUse][ztagIdValue] = $strResult;
        $arrayTagId["$".$strUse][ztagIdLength] = strlen($strResult);

        $arrayTagId["$".$strUse][ztagIdType] = idTypeFVar;
      }
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  /*
  debugError("tagFunction=$tagFunction"
            ."<br />tagId=$tagId"
            ."<br />strId=$strId"
            ."<br />strUse=$strUse"
            ."<br />strValue=$strValue"
            ."<br />arrayTagId[$strId][ztagIdValue]=".$arrayTagId[$strId][ztagIdValue]
            ."<br />arrayTagId[$strUse][ztagIdValue]=".$arrayTagId[$strUse][ztagIdValue]
            ."<br />arrayTag[$tagId][ztagResult]=".$arrayTag[$tagId][ztagResult]);
            */

  ztagError($errorMessage, $arrayTag, $tagId);
}
