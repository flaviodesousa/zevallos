<?php
/**
 * ZSession
 *
 * Processa as tags para leitura das variáveis $_SESSION do PHP.
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

define("zsessionVersion", 1.0, 1);
define("zsessionVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zsession_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zsession_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zsession_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zsession_version() {
  return zsessionVersion." ".zsessionVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zsession_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zsession_compare($version) {
  return zsessionVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zsession_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zsession_zexecute($tagId, $tagFunction, &$arrayTag, $arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strName  = $arrParam["name"];
  $strValue = $arrParam["value"];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
   /*+
     * Set
     *
     * <code>
     * <zsession:set name="string" value="value" />
     * </code>
     *
     * @param string name="string"
     * @param string value="value"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "set":
      $errorMessage .= ztagParamCheck($arrParam, "name,value");

    	if (strlen($strValue)) $_SESSION[$strName] = $strValue;
      break;

   /*+
     * Get
     *
     * <code>
     * <zdoc:get name="string" />
     * </code>
     *
     * @param string name="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "get":
      $errorMessage .= ztagParamCheck($arrParam, "name");

      if (isset($_SESSION[$strName])) $arrayTag[$tagId][ztagValue] = $_SESSION["tag_$strName"];
      break;

    /*+
     * Unset a session
     *
     * <code>
     * <zsession:unset name="getFiltro" />
     * </code>
     *
     * @param string name="getFiltro"
     */

    case "unset":

    /*+
     * Reset a session
     *
     * <code>
     * <zsession:reset name="getFiltro" />
     * </code>
     *
     * @param string name="getFiltro"
     */
    case "reset":
      $errorMessage = ztagParamCheck($arrParam, "name");

    	if (isset($_SESSION[$strName])) unset($_SESSION[$strName]);
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
      $errorMessage .= ztagParamCheck($arrParam, "name");

      if (isset($_SESSION[$strName])) $arrayTag[$tagId][ztagResult] = $_SESSION["tag_$strName"];
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

