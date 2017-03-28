<?php
/**
 * ZCookie
 *
 * Processa as tags para leitura e gravação de Cookies usando a superglobal $_COOKIE do PHP.
 *
 * @package ztag
 * @subpackage cookie
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

define("zcookieVersion", 1.0, 1);
define("zcookieVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zcookie_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zcookie_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zcookie_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zcookie_version() {
  return zcookieVersion." ".zcookieVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zcookie_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zcookie_compare($version) {
  return zcookieVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zcookie_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zcookie_zexecute($tagId, $tagFunction, &$arrayTag, $arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strName  = $arrParam["name"];

  $errorMessage = "";

  if (!$strName) $errorMessage = "<br />Missing \"name\" parameter";

  switch (strtolower($tagFunction)) {
   /*+
     * Set
     *
     * <code>
     * <zdoc:set />
     * </code>
     *
     * @param string value="Value"
     * @param string expire="Value"
     * @param string path="Value"
     * @param string domain="Value"
     * @param string secure="Value"
     * @param string httponly="Value"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "set":
      $strValue    = $arrParam["value"];
      $strExpire   = $arrParam["expire"];
      $strPath     = $arrParam["path"];
      $strDomain   = $arrParam["domain"];
      $strSecure   = $arrParam["secure"];
      $strHTTPOnly = $arrParam["httponly"];

      $intTest = 0;

      if ($strExpire)   $intTest = 1;
      if ($strPath)     $intTest = 2;
      if ($strDomain)   $intTest = 3;
      if (strlen($strSecure))   $intTest = 4;
      if (strlen($strHTTPOnly)) $intTest = 5;

      if (!$strValue) $errorMessage .= "<br />Missing \"value\" parameter";

      // @TODO Utilizar algum outro meio mais eficiente para verificar se a varíavel foi definida com 0 ou false

      if (!$strExpire && $intTest >= 1) $errorMessage .= "<br />Missing \"expire\" parameter";
      if (!$strPath && $intTest >= 2) $errorMessage .= "<br />Missing \"path\" parameter";
      if (!$strDomain && $intTest >= 3) $errorMessage .= "<br />Missing \"domain\" parameter";
      if (!strlen($strSecure) && $intTest >= 4) $errorMessage .= "<br />Missing \"secure\" parameter";
      if (!strlen($strHTTPOnly) && $intTest >= 5) $errorMessage .= "<br />Missing \"httponly\" parameter";

      switch($intTest) {
      	case 1:
          setcookie($strName, $strValue,  time() + strval($strExpire));
          break;

        case 2:
          setcookie($strName, $strValue,  time() + strval($strExpire), $strPath);
          break;

        case 3:
          setcookie($strName, $strValue,  time() + strval($strExpire), $strPath, $strDomain);
          break;

        case 4:
          setcookie($strName, $strValue,  time() + strval($strExpire), $strPath, $strDomain, $strSecure);
          break;

        case 5:
          setcookie($strName, $strValue,  time() + strval($strExpire), $strPath, $strDomain, $strSecure, $strHTTPOnly);
          break;

      	default:
          setcookie($strName, $strValue);
      }

      break;

   /*+
     * Get
     *
     * <code>
     * <zdoc:get name="varName" />
     * </code>
     *
     * @param string name="varName"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "get":
      if (isset($_COOKIE[$strName])) $arrayTag[$tagId][ztagValue] = $_COOKIE[$strName];
      break;

   /*+
     * Show
     *
     * <code>
     * <zdoc:show name="varName" />
     * </code>
     *
     * @param string name="varName"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "show":
      if (isset($_COOKIE[$strName])) $arrayTag[$tagId][ztagResult] = $_COOKIE[$strName];
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

