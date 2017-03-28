<?php
/**
 * zheader
 *
 * Processa as tags para gestão do Header
 *
 * @package ztag
 * @subpackage header
 * @category Environment
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

define("zheaderVersion", 1.0, 1);
define("zheaderVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zheader_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zheader_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zheader_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zheader_version() {
  return zheaderVersion." ".zheaderVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zheader_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zheader_compare($version) {
  return zheaderVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zheader_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zheader_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strUse   = $arrParam["use"];
  $strValue = $arrParam["value"];

  $errorMessage = "";

  $tagValue = $arrayTagId[$strParam][ztagIdValue];

  switch (strtolower($tagFunction)) {
    /*+
     * Location IF
     *
     * <code>
     * <zheader:locationif use="!userLogged" notcondition="nome" value="/logOn.php" />
     * </code>
     *
     * @param string use="!userLogged"
     * @param string notcondition="nome"
     * @param string value="/logOn.php"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "locationif":
      // @TODO Rever esta Função, criando algo mais genérico
      $strEqual        = $arrParam["equal"];
      $strNotEqual     = $arrParam["notequal"];
      $strCondition    = $arrParam["condition"];
      $strNotCondition = $arrParam["notcondition"];

      // header("Location: /logOn.php");

      $errorMessage .= ztagParamCheck($arrParam, "use,value");

      if ($strEqual) {
        if ($strUse == $strEqual) header("Location: $strValue");
      }

      if ($strNotEqual) {
        if ($strUse != $strNotEqual) header("Location: $strValue");
      }

      if ($strCondition) {
        $strCondition = ztagTransform($strUse, $strCondition);

        if ($strCondition) header("Location: $strValue");
      }

      if ($strNotCondition) {
      	$strNotCondition = ztagTransform($strUse, $strNotCondition);

        if (!$strNotCondition)  header("Location: $strValue");
      }
      break;

    /*+
     * Location
     *
     * <code>
     * <zheader:location value="/logOn.php" />
     * </code>
     *
     * @param string value="/logOn.php"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "location":
      // header("Location: /logOn.php");

      $errorMessage .= ztagParamCheck($arrParam, "value");

      if (strlen($strValue))  header("Location: $strValue");
      break;

    /*+
     * Location
     *
     * <code>
     * <zheader:contenttype value="application/json" charset="utf-8" />
     * </code>
     *
     * @param string value="/logOn.php"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "contenttype":
      $errorMessage .= ztagParamCheck($arrParam, "value");

      if ($charset) $charset = "; charset=".$charset;
      if (strlen($strValue))  header("Content-Type: $strValue$charset");
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

