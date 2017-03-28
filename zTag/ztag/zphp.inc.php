<?php
/**
 * zPHP
 *
 * zTag to manipulate raw PHP codes
 *
 * @package ztag
 * @subpackage template
 * @category Database
 * @version $Revision$
 * @author me <me@mydomain.tld>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://mydomain.tld
 * @copyright 2010 by me
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

define("zphpVersion", 1.0, 1);
define("zphpVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zphp_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zphp_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zphp_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zphp_version() {
  return zphpVersion." ".zphpVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zphp_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zphp_compare($version) {
  return zphpVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zphp_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zphp_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId        = $arrParam["id"];
  $strUse       = $arrParam["use"];
  $strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strTransform = $arrParam["transform"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * <zphp:connect id="idunico" var="ldap" url="http://x.y/aulam" username="asdad" password="asdasd" port="80" />
     *
     * @param string id="idunico"
     * @param string var="ldap"
     * @param string url="http://x.y/aulam"
     * @param string username="asdad"
     * @param string password="asdasd"
     * @param string port="80"
     */
    case "execute":
      // echo "<pre>".print_r($arrayTag[$tagId], 1)."</pre>";

      $strUrl      = $arrParam["url"];
      $strUsername = $arrParam["username"];
      $strPassword = $arrParam["password"];
      $strPort     = $arrParam["port"];

      $errorMessage .= ztagParamCheck($arrParam, "id,url,username,password");

      if (strlen($strId)) {
        $arrayTagId[$strId][ztagIdValue] = "idTypeMFConnect ok";
        $arrayTagId[$strId][ztagIdLength] = count("idTypeMFConnect ok");

        $arrayTagId[$strId][ztagIdType] = idTypeMFConnect;

      }

      if (strlen($strVar)) {
        $arrayTagId["$".$strVar][ztagIdValue] = "Erro ok";
        $arrayTagId["$".$strVar][ztagIdLength] = count("Erro ok");

        $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;

      }

      break;

    /*+
     * <zphp:bind use="idunico" id="ldapHadle" />
     *
     * <zphp:browse id="ldapHadle" var="browseLDAP" cn="sadasd" search="text" />
     *
     * <zphp:save use="ldapHadle" var="browseLDAP" cn="sadasd" value="text" />
     *
     * <zphp:save use="ldapHadle" var="browseLDAP" cn="sadasd">
     * </zphp:save>
     *
     * <zphp:unbind use="ldapHadle" />
     *
     *  value="My hi" Message to show to me
     */
    case "bind":
      // echo "<pre>".print_r($arrayTag[$tagId], 1)."</pre>";

      $errorMessage .= ztagParamCheck($arrParam, "use,id");


      if (strlen($strUse)) {
        if ($arrayTagId[$strUse][ztagIdType] == idTypeMFConnect) {
          $strId = $arrayTagId[$strUse][ztagIdType];
        } else {
          $errorMessage .= "O use $strUse não é um idTypeMFConnect";
        }
      }

      if (strlen($strId)) {
        $arrayTagId[$strId][ztagIdValue] = "idTypeMFBind ok";
        $arrayTagId[$strId][ztagIdLength] = count("idTypeMFBind ok");

        $arrayTagId[$strId][ztagIdType] = idTypeMFBind;

      }

      break;

    case "teste":

      if ($strContent) {
        if ($strTransform) $strContent = ztagTransform($strContent, $strTransform);

        $arrayTag[$tagId][ztagResult] = "<b>$strContent</b>";

      }

      if ($strValue) {
        if ($strTransform) $strValue = ztagTransform($strValue, $strTransform);

        $arrayTag[$tagId][ztagResult] = "<u>$strValue</u>";

      }

      if ($strVar) {
        if ($strTransform) $strVar = ztagTransform($strVar, $strTransform);

        $arrayTag[$tagId][ztagResult] = "<i>$strVar</i>";

      } else {
        if (!$strContent && !$strValue) $errorMessage .= ztagParamCheck($arrParam, "value");
      }

      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

/*+
 * <zphp:test value="My Test" />
 *
 * or
 *
 * <zphp:test var="$varTest" />
 *
 * or
 *
 * <zphp:test>
 *   My inner Test
 * </zphp:test>
 *
 * A zTag function Test
 *
 *  value="My Test" Message to show to me
 */
function zphp_test($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strValue = $arrParam["value"];
  $strVar   = $arrParam["var"];

  $templateContent = $arrayTag[$tagId][ztagContent];

	if ($strContent) {
	  if ($strTransform) $strContent = ztagTransform($strContent, $strTransform);

	  $arrayTag[$tagId][ztagResult] = "<b>$strContent</b>";

	}

	if ($strValue) {
	  if ($strTransform) $strValue = ztagTransform($strValue, $strTransform);

	  $arrayTag[$tagId][ztagResult] = "<u>$strValue</u>";

	}

	if ($strVar) {
	  if ($strTransform) $strVar = ztagTransform($strVar, $strTransform);

	  $arrayTag[$tagId][ztagResult] = "<i>$strVar</i>";

	} else {
	  if (!$strContent && !$strValue) $errorMessage .= ztagParamCheck($arrParam, "value");
	}
}
