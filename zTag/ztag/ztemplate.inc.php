<?php
/**
 * ZTemplate
 *
 * Processa as tags para leitura das variáveis $_GET do PHP.
 *
 * @package ztag
 * @subpackage template
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

define("ztemplateVersion", 1.0, 1);
define("ztemplateVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * ztemplate_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function ztemplate_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * ztemplate_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function ztemplate_version() {
  return ztemplateVersion." ".ztemplateVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * ztemplate_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function ztemplate_compare($version) {
  return ztemplateVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * ztemplate_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function ztemplate_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
	$arrParam = $arrayTag[$tagId][ztagParam];

	$strId       = $arrParam["id"];
	$strName     = $arrParam["name"];
	$strFileName = $arrParam["filename"];
  $strUse      = $arrParam["use"];

	$errorMessage = "";

	switch (strtolower($tagFunction)) {
   /*+
     * Load
     *
     * <code>
     * <ztag:load />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
	  case "load":
      if (!$strFileName) $errorMessage .= "<br />Missing \"filename\" parameter";
			if (!$strId) $errorMessage .= "<br />Missing \"id\" parameter";

			$strFileName = str_replace("\\", "/", $strFileName);

			if (substr($strFileName , 0, 1) == "/") $strFileName = substr($strFileName, 1);

			$strFileName = SiteRootDir.$strFileName;

			// @TODO incluir validações mais fortes para a abertura dos arquivos
			if (!file_exists($strFileName)) {
				$errorMessage .= "<br />File \"$strFileName\" not found!";
			}

			$objFile = fopen($strFileName, "r");

			$strContent = fread($objFile, filesize($strFileName));

			fclose($objFile);

      $arrayTagId[$strId][ztagIdValue]    = $strContent;
      $arrayTagId[$strId][ztagIdFileName] = $strFileName;
      $arrayTagId[$strId][ztagIdLength]   = strlen($strContent);

			break;

   /*+
     * Create
     *
     * <code>
     * <ztag:create />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "create":
      if (!$strId) $errorMessage .= "<br />Missing \"id\" parameter";

      if ($arrayTag[$tagId][ztagContentWidth]) {
	      $strContent = $arrayTag[$tagId][ztagContent];

	      $arrayTagId[$strId][ztagIdValue]  = $strContent;
	      $arrayTagId[$strId][ztagIdLength] = strlen($strContent);

      }
			break;

   /*+
     * Show
     *
     * <code>
     * <ztag:show />
     * </code>
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "show":
      if (!$strUse) $errorMessage .= "<br />Missing \"use\" parameter";

      $arrayTag[$tagId][ztagResult] = $arrayTagId[$strUse][ztagIdValue];

      break;

		default:
			$errorMessage .= "<br />Undefined function \"$tagFunction\"";

	}

  ztagError($errorMessage, $arrayTag, $tagId);
}

