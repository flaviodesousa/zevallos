<?php
/**
 * zHTTP
 *
 * Process all HTTP zTags
 *
 * @package ztag
 * @subpackage var
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

define("zhttpVersion", 1.0, 1);
define("zhttpVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zhttp_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zhttp_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zhttp_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zhttp_version() {
  return zhttpVersion." ".zhttpVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zhttp_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zhttp_compare($version) {
  return zhttpVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zhttp_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zhttp_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId    = $arrParam["id"];
  $strUse   = $arrParam["use"];
  $strVar   = $arrParam["var"];
  $strValue = $arrParam["value"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Creates a $var with it´s value
     *
     * <code>
     * <zhttp:set id="someVar" value="someValue">
     *
     * <zhttp:set id="otherVar">
     *   It's value
     * </zhttp:set>
     * </code>
     *
     * @param string id="someVar"
     * @param string value="someValue"
     */
    case "read":
      $objCURL = curl_init();

      curl_setopt($objCURL, CURLOPT_URL, $strURL);
      curl_setopt($objCURL, CURLOPT_RETURNTRANSFER, 1);
      // curl_setopt($objCURL, CURLOPT_HEADER, 1);
      // curl_setopt($objCURL, CURLINFO_HEADER_OUT, 1);
      curl_setopt($objCURL, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)" );

      // curl_setopt($objCURL, CURLOPT_COOKIE, "FGTServer=34B68E04678B6417793617BE38F54F47B3BBFF47; __utma=60534895.122979554.1255118786.1266121550.1266123845.13; __utmz=60534895.1266020117.10.3.utmcsr=stf.gov.br|utmccn=(referral)|utmcmd=referral|utmcct=/portal_stj/; ASPSESSIONIDSQBRSDCQ=BLCIHAPCFDAHJLEACAFCNBNJ; __utmc=60534895; ASPSESSIONIDSSARQACQ=DDCNCAPCNJNBJHDPPFPJCCEK; ASPSESSIONIDSQAQTCDQ=NFDCHLIDEIMEAAJNPFIJFHDB; ASPSESSIONIDSSCRTCBQ=PJIFCLIDNJLIHCOJGLHHLBJE; ASPSESSIONIDSQBSRBCQ=HLJGGFLDINBPGOECFNLGPMJA; __utmb=60534895.4.10.1266123845" );

      curl_setopt($objCURL, CURLOPT_COOKIE, "ASPSESSIONIDCSCARQCD=GHEFGIDCHDCLEEMGAKPEKPPO; __utma=15381288.711762829.1282791344.1282791344.1282791344.1; __utmb=15381288.8.10.1282791344; __utmc=15381288; __utmz=15381288.1282791344.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)" );

      $strResult = curl_exec($objCURL);

      if (!$strResult)
        echo "<br /><b>Curl error</b>: ".curl_error($objCURL);

      $arrcURL = curl_getinfo($objCURL);

      // echo "<br />http_code=".$arrcURL["http_code"];
      // echo "<br />content_type=".$arrcURL["content_type"];

      if (isUTF8($strResult)) $strResult = utf8_decode($strResult);

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

