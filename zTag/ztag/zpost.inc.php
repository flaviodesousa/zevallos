<?php
/**
 * zpost
 *
 * Processa as tags para leitura das variáveis $_GET do PHP.
 *
 * @package ztag
 * @subpackage post
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

define("zpostVersion", 1.0, 1);
define("zpostVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zpost_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zpost_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zpost_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zpost_version() {
  return zpostVersion." ".zpostVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zpost_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zpost_compare($version) {
  return zpostVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zpost_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zpost_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strName       = $arrParam["name"];
  $strVar        = $arrParam["var"];
  $strTransform  = $arrParam["transform"];

  if ($strName) $strPostValue = $_POST[$strName];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
   /*+
     * Get
     *
     * <code>
     * <zdoc:get name="string" var="string" />
     * </code>
     *
     * @param name="string"
     * @param var="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "get":
      $errorMessage .= ztagParamCheck($arrParam, "name,var");

      if ($strTransform) $strPostValue = ztagTransform($strPostValue, $strTransform);

      if ($strVar) {
        $arrayTagId["$".$strVar][ztagIdValue] = $strPostValue;
        $arrayTagId["$".$strVar][ztagIdType] = idTypePost;
      }

      if (isset($strPostValue)) $arrayTag[$tagId][ztagValue] = $strPostValue;
      break;

   /*+
     * Show
     *
     * <code>
     * <zdoc:show name="string" />
     * </code>
     *
     * @param name="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "show":
      $errorMessage = ztagParamCheck($arrParam, "name");

      if ($strTransform) $strPostValue = ztagTransform($strPostValue, $strTransform);

      if (isset($strPostValue)) $arrayTag[$tagId][ztagResult] = $strPostValue;
      break;

    default:
      $strMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

