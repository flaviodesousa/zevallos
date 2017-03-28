<?php
/**
 * ZXLS
 *
 * Return a file at XLS way
 *
 * @package ztag
 * @subpackage template
 * @category Database
 * @version $Revision$
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

define("zxlsVersion", 1.0, 1);
define("zxlsVersionSufix", "ALFA 0.1", 1);

$dbHandleDefault = "";

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zxls_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zxls_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zxls_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zxls_version() {
  return zxlsVersion." ".zxlsVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zxls_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zxls_compare($version) {
  return zxlsVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zxls_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zxls_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  global $dbHandleDefault;

  $arrParam = $arrayTag[$tagId][ztagParam];

  $strUse      = $arrParam["use"];

  $strRow   = $arrParam["row"];
  $strCol   = $arrParam["col"];
  $strValue = $arrParam["value"];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
	  /*+
     * Return the BOF - Begin of File info
     *
     * <code>
	   * <zxls:bof />
     * </code>
	   */
    case "bof":
      $arrayTag[$tagId][ztagResult] = pack("ssssss", 0x809, 0x08, 0x00, 0x10, 0x00, 0x00);
      break;

    /*+
     * Return the EOF - End of File info
     *
     * <code>
     * <zxls:eof />
     * </code>
     */
    case "eof":
      $arrayTag[$tagId][ztagResult] = pack("ss", 0x0A, 0x00);
      break;

	  /*+
     * Return a excel cell label
     *
     * <code>
	   * <zxls:label row="10" col="10" value="Label" />
     * </code>
	   */
    case "label":
      $strLen = strlen($strValue);
      $arrayTag[$tagId][ztagResult] = pack("ssssss", 0x0204, 8 + $strLen, $strRow, $strCol, 0x00, $strLen).$strValue;
      break;

	  /*+
     * Return a excel cell
     *
     * <code>
	   * <zxls:number row="10" col="10" value="14" />
     * </code>
	   */
    case "number":
      settype($strValue,'float');
      settype($strRow, 'integer');
      settype($strCol, 'integer');

      $arrayTag[$tagId][ztagResult] = pack('sssss', 0x0203, 14, $strRow, $strCol, 0x00).pack('d', $strValue);
      break;

	  /*+
     * Return the BOF - Begin of File info
     *
     * <code>
	   * <zxls:header filename="Filename" />
     * </code>
	   */
    case "header":
      $strFilename = $arrParam["filename"];

      header("Pragma: no-cache");
      header("Expires: Mon, 23 Aug 1966 03:00:00 GMT");
      header("Last-Modified: ".gmdate("D,d M YH:i:s")." GMT");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: application/x-msexcel");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");;
      header("Content-Disposition: attachment;filename=$strFilename.xls");
      header("Content-Transfer-Encoding: binary ");
      header("Content-Description: zTag:XLS Generated data");
      break;

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
