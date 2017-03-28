<?php
/**
 * zCode
 *
 * Processa as tags zCode
 *
 * @package ztag
 * @subpackage template
 * @category help
 * @version $Revision$
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://ztag.zyc.com.br
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

define("zcodeVersion", 1.0, 1);
define("zcodeVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zcode_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zcode_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zcode_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zcode_version() {
  return zcodeVersion." ".zcodeVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zcode_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zcode_compare($version) {
  return zcodeVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zcode_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zcode_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strTransform = $arrParam["transform"];
  $strTitle     = $arrParam["title"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Present in a code way of values content
     *
     *
     * Return the variable content in a formated code
     * Teste line
     *
     *
     * <code>
     * <zcode:show value="$templateValue" title="$templateFile" />
     * </code>
     *
     * @param string value="$templateValue"
     * @param string title="$templateFile"
     *
     * @param string dir="string" Full directory where the zTag .inc.php files are
     *
     * @return array Return an array with all docs and functions
     *
     * @since 0.0.2 ALFA
     *
     * @see zctrl:if
     *
     * @uses zvar:set
     *
     * @copyright 2010 by Ruben Zevallos(r) Jr.
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "show":
      // echo "<pre>".print_r($arrayTag[$tagId], 1)."</pre>";

      if ($strContent) $contentHTML = $strContent;

      if ($strValue) $contentHTML = $strValue;

      if ($strVar) {
        $contentHTML = $strVar;

      } else {
        if (!$strContent && !$strValue) $errorMessage .= ztagParamCheck($arrParam, "value");
      }

      if ($strTransform) $contentHTML = ztagTransform($contentHTML, $strTransform);

      $contentHTML  = preg_replace("/\t/", "  ", $contentHTML);
      $contentHTML  = ltrim($contentHTML, "\r\n");
      $contentHTML  = rtrim($contentHTML, "\r\n");

      preg_match_all("%^(?P<spaces>[ ]+)?(?P<line>.*?)(\r)?(\n)%sm", $contentHTML."\r\n", $Matches, PREG_OFFSET_CAPTURE);

      $contentHTML = "";

      $lineMax = 0;
      $lineMin = 9999;

      foreach ($Matches[0] as $key => $value) {
        if (strlen($Matches["line"][$key][0]) > 0 && $lineMax < ($intSize = strlen($Matches["spaces"][$key][0]))) $lineMax = $intSize;
        if (strlen($Matches["line"][$key][0]) > 0 && $lineMin > ($intSize = strlen($Matches["spaces"][$key][0]))) $lineMin = $intSize;

      }

      foreach ($Matches[0] as $key => $value) {
      	$lineSpaces  = $Matches["spaces"][$key][0];

        $lineSpaces  = substr($lineSpaces, $lineMin, strlen($lineSpaces));
        $lineContent = $Matches["line"][$key][0];

        if (!$lineAlt) {
        	$lineAlt = " class=\"alt\"";
        } else {
        	$lineAlt = "";
        }

        $contentHTML .= "<li$lineAlt>".preg_replace("/\s/", "&nbsp;", $lineSpaces).htmlentities($lineContent)."</li>";
        $contentPRE  .= $lineSpaces.$lineContent."\n";

      }

/*
              ."<div class=\"bar\">"
              ."<div class=\"tools\"><a onclick=\"zcodeViewPlain('ViewSource',this);return false;\" href=\"#\">view plain</a>"
              ."<a onclick=\"zcodeViewSource(this);return false;\" href=\"#\">copy to clipboard</a>"
              ."<a onclick=\"zcodePrintSource('PrintSource',this);return false;\" href=\"#\">print</a>"
              ."<a onclick=\"zcodeAbout('About',this);return false;\" href=\"#\">?</a>"
              ."</div>"
              ."</div>"
  */
			$html = "<div style=\"border-width: 1px;\">"
			        ."<div style=\"border-bottom-width: 1px;\" class=\"codeHeader panelHeader\"><b>\"$strTitle\"</b></div>"
			        ."<div>"
			        ."<div class=\"zcode\">"
			        ."<ol start=\"1\" class=\"dp-c\">"
			        .$contentHTML
			        ."</ol>"
			        ."</div>"
			        ."<pre style=\"display: none;\">"
			        .$contentPRE
			        ."</pre>"
			        ."</div>"
			        ."</div>";

        $arrayTag[$tagId][ztagResult] = $html;

      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
