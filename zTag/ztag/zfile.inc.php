<?php
/**
 * ZFile
 *
 * Processa todas as funções de gestão de arquivo
 *
 * @package ztag
 * @subpackage file
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

define("zfileVersion", 1.0, 1);
define("zfileVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zfile_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zfile_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zfile_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zfile_version() {
  return zfileVersion." ".zfileVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zfile_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zfile_compare($version) {
  return zfileVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zfile_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zfile_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId     = $arrParam["id"];
  $strUse    = $arrParam["use"];
  $strValue  = $arrParam["value"];
  $strVar    = $arrParam["var"];
  $strLength = $arrParam["length"];

  $errorMessage = "";

  $tagValue = $arrayTagId[$strParam][ztagIdValue];

  /*
  if (file_exists($strFileName)) {
    // fopen  ( string $filename  , string $mode  [, bool $use_include_path = false  [, resource $context  ]] )
    $objFile = fopen($strFileName, "r");

    $strResult = fread($objFile, filesize($strFileName));

    fclose($objFile);
  }
  */

  switch (strtolower($tagFunction)) {
    /*+
     * Open
     *
     * <code>
     * <zdoc:open />
     * </code>
     *
     * @param string filename="string"
     * @param string mode="string"
     * @param string includepath="string"
     * @param string contexts="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "open":
      $errorMessage .= ztagParamCheck($arrParam, "id,filename,mode");

    	$strFileName    = $arrParam["filename"];
      $strMode        = $arrParam["mode"];
      $strIncludePath = $arrParam["includepath"];
      $strContexts    = $arrParam["contexts"];

      $intTest = 0;

      if (strlen($strIncludePath)) $intTest = 1;
      if ($strContexts)            $intTest = 2;

      // @TODO Utilizar algum outro meio mais eficiente para verificar se a varíavel foi definida com 0 ou false

      if ($strFileName) {
      	$strFileName = str_replace("\\", "/", $strFileName);

		    if (substr($strFileName , 0, 1) == "/") $strFileName = substr($strFileName, 1);

		    $strFileName = SiteRootDir.$strFileName;

		    // @TODO incluir validações mais fortes para a abertura dos arquivos
        if (!file_exists($strFileName)) {
        	$errorMessage .= "<br />File \"$strFileName\" not found!";
        }
      }

      if (!strlen($strIncludePath) && $intTest >= 1) $errorMessage .= "<br />Missing \"includepath\" parameter";
      if (!$strContexts && $intTest >= 2) $errorMessage .= "<br />Missing \"contexts\" parameter";

      // debugError("strFileName=$strFileName");

      switch($intTest) {
        case 1:
          $handleFile = fopen($strFileName, $strMode, $strIncludePath);
          break;

        case 1:
          $handleFile = fopen($strFileName, $strMode, $strIncludePath, $strContexts);
          break;

        default:
          $handleFile = fopen($strFileName, $strMode);
      }

      $arrayTagId[$strId][ztagIdHandle]   = $handleFile;
      $arrayTagId[$strId][ztagIdType]     = idTypeFile;
      $arrayTagId[$strId][ztagIdState]    = idStateOpened;
      $arrayTagId[$strId][ztagIdFileName] = $strFileName;

      break;

    /*+
     * Read
     *
     * <code>
     * <zdoc:read />
     * </code>
     *
     * @param string  filename="string"
     * @param string  mode="string"
     * @param string  includepath="string"
     * @param string  contexts="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "read":
      $errorMessage .= ztagParamCheck($arrParam, "use,length");

      if ($arrayTagId[$strUse][ztagIdType] != idTypeFile) $errorMessage .= "<br />The handle \"$strUse\" is not a file one!";

      $handleFile  = $arrayTagId[$strUse][ztagIdHandle];
      $strFileName = $arrayTagId[$strUse][ztagIdFileName];

      $strValue = fread($handleFile, strval($strLength));

      if ($strVar) {
        $arrayTagId[$strVar][ztagIdValue] = $strValue;
        $arrayTagId[$strVar][ztagIdLength] = strlen($strValue);

      }
      break;

    /*+
     * ReadAll
     *
     * <code>
     * <zdoc:readall />
     * </code>
     *
     * @param string  filename="string"
     * @param string  mode="string"
     * @param string  includepath="string"
     * @param string  contexts="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "readall":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] != idTypeFile) $errorMessage .= "<br />The handle \"$strUse\" is not a file one!";

      $handleFile  = $arrayTagId[$strUse][ztagIdHandle];
      $strFileName = $arrayTagId[$strUse][ztagIdFileName];

      $strValue = fread($handleFile, filesize($strFileName));

		  if ($strVar) {
		    $arrayTagId["$".$strVar][ztagIdValue] = $strValue;
		    $arrayTagId[$strVar][ztagIdLength] = strlen($strValue);
		  }

    	break;

    /*+
     * Close
     *
     * <code>
     * <zdoc:close />
     * </code>
     *
     * @param string filename="string"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "close":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] != idTypeFile) $errorMessage .= "<br />The handle \"$strUse\" is not a file one!";

      $handleFile  = $arrayTagId[$strUse][ztagIdHandle];

      fclose($handleFile);

      $arrayTagId[$strUse][ztagIdState] = idStateClosed;

      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

