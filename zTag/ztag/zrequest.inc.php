<?php
/**
 * ZRequest
 *
 * Processa as tags para leitura das variáveis $_GET do PHP.
 *
 * @package ztag
 * @subpackage get
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

define("zrequestVersion", 1.0, 1);
define("zrequestVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zrequest_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zrequest_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zrequest_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zrequest_version() {
  return zrequestVersion." ".zrequestVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zrequest_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zrequest_compare($version) {
  return zrequestVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zrequest_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zrequest_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strName = $arrParam["name"];
  $strVar  = $arrParam["var"];
  $strTransform  = $arrParam["transform"];

  if ($strName) $strRequestValue = $_REQUEST[strtolower($strName)];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
   /*+
     * Get
     *
     * <code>
     * <zdoc:get name="string" var="var" />
     * </code>
     *
     * @param string name="string"
     * @param string var="var"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "get":
      $errorMessage = ztagParamCheck($arrParam, "name,var");

      if ($strTransform) $strRequestValue = ztagTransform($strRequestValue, $strTransform);

      if ($strVar) {
        $arrayTagId["$".$strVar][ztagIdValue] = $strRequestValue;
        $arrayTagId["$".$strVar][ztagIdType] = idTypeRequest;
        $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;
      }

      if (isset($strRequestValue)) $arrayTag[$tagId][ztagValue] = $strRequestValue;
      break;

   /*+
     * Show
     *
     * <code>
     * <zdoc:show name="string" />
     * </code>
     *
     * @param string name="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "show":
      $errorMessage = ztagParamCheck($arrParam, "name");

      if ($strTransform) $strRequestValue = ztagTransform($strRequestValue, $strTransform);

      if (isset($strRequestValue)) $arrayTag[$tagId][ztagResult] = $strRequestValue;
      break;

    default:
      $strMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

