<?php
/**
 * zOut
 *
 * Process zTags for Output
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

define("zoutVersion", 1.0, 1);
define("zoutVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zout_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zout_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zout_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zout_version() {
  return zoutVersion." ".zoutVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zout_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zout_compare($version) {
  return zoutVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zout_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zout_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strURL   = $arrParam["url"];
  $strVar   = $arrParam["var"];

  $strUse   = $arrParam["use"];
  $strValue = $arrParam["value"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Turn on output buffering
     *
     * <code>
     * <zout:start />
     * </code>
     */
    case "start":
      ob_start();
      break;

    /*+
     * Flush (send) the output buffer
     *
     * <code>
     * <zout:flush end="true" var="outVar" />
     * </code>
     *
     * @param boolean end="1|0|true|false" Define if will end the buffer
     * @param string var="outVar"
     */
    case "flush":
      $strEnd = $arrParam["end"];


      if (strlen($strVar)) {
        $getValue = ob_get_flush();

        $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
        $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
      } else {
        if ($strEnd == "true" || $strEnd == "1") {
          ob_end_flush();
        } else {
          ob_flush();
        }
      }
      break;

    /*+
     * Clean (erase) the output buffer
     *
     * <code>
     * <zout:clean end="true" />
     * </code>
     *
     * @param string end="1|0|true|false" Define if will end the buffer
     */
    case "clean":
      $strEnd = $arrParam["end"];

      if ($strEnd == "true" || $strEnd == "1") {
        ob_end_clean();
      } else {
        ob_clean();
      }
      break;

    /*+
     * Return the contents of the output buffer
     *
     * <code>
     * <zout:get var="outVar" clean="true" />
     * </code>
     *
     * @param boolean clean="1|0|true|false" Clear buffer after get it's content
     * @param string var="ourVar" Variable where the buffer's content will be saved
     */
    case "get":
      $errorMessage .= ztagParamCheck($arrParam, "var");

      $strClean = $arrParam["clean"];

      if ($strClean == "true" || $strClean == "1") {
        $getValue = ob_get_clean();

      } else {
        $getValue = ob_get_contents();
      }

      echo "<br />getValue=".strlen($getValue);
      echo "<br />getValue=".strlen($getValue);

      if (strlen($strVar) && $getValue) {
        $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
        $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
      }
      break;

    /*+
     * Return the length of the output buffer
     *
     * <code>
     * <zout:len var="outVar" />
     * </code>
     *
     * @param string var="ourVar" Variable where the buffer's content will be saved
     */
    case "len":

    /*+
     * Return the length of the output buffer
     *
     * <code>
     * <zout:lenght var="outVar" />
     * </code>
     *
     * @param string var="ourVar" Variable where the buffer's content will be saved
     */
    case "lenght":
      $errorMessage .= ztagParamCheck($arrParam, "var");

      $getValue = ob_get_length();

      if (strlen($strVar) && $getValue) {
        $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
        $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
      }
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
