<?php
/**
 * ZServer
 *
 * Processa as tags para leitura das variáveis $_SERVER do PHP.
 *
 * @package ztag
 * @subpackage server
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

define("zctrlVersion", 1.0, 1);
define("zctrlVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zserver_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zserver_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zserver_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zserver_version() {
  return zctrlVersion." ".zctrlVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zserver_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zserver_compare($version) {
  return zctrlVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zserver_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zserver_zExecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strUse = $arrParam["use"];
  $strVar = $arrParam["var"];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Get a server variable
     *
     * <code>
     * <zserver:get use="envVar" var="envVariable" />
     * </code>
     *
     * @param string use="envVar" Name of environment variable
     * @param string var="envVariable" Variable where data will be saved
     */
    case "get":
	    $errorMessage .= ztagParamCheck($arrParam, "use");

      if (strlen($strVar) && isset($_SERVER[$strUse])) {
        $arrayTagId["$".$strVar][ztagIdValue] = $_SERVER[$strUse];
        $arrayTagId["$".$strVar][ztagIdLength] = count($_SERVER[$strUse]);

        $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;
      }
      break;

    /*+
     * Show an server variable
     *
     * <code>
     * <zserver:show use="envVar" />
     * </code>
     *
     * @param string use="envVar" Name of environment variable
     */
    case "show":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if (isset($_SERVER[$strUse])) $arrayTag[$tagId][ztagResult] = $_SERVER[$strUse];
      break;

  	default:
  		$errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}