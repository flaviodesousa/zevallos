<?php
/**
 * zOM
 *
 * Processa as tags específicas do Onyx Manager
 *
 * @package ztag
 * @subpackage template
 * @category help
 * @version $Revision$
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://ztag.zyc.com.br
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

define("zomVersion", 1.0, 1);
define("zomVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zom_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zom_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zom_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zom_version() {
  return zomVersion." ".zomVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zom_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zom_compare($version) {
  return zomVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zom_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zom_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    // <zdb:open id="mssqlD2" driver="mssql" host="#mssqlD2" user="#mssqlD2User", password="#mssqlD2Pass" />
    // <zdb:open id="mssqlALFA" driver="mssql" host="#mssqlALFA" user="#mssqlALFAUser", password="#mssqlALFAPass" />
  	// <zom:copy source="Pass" destination="mssqlALFA" idsource="" iddestination="" />
    // <zdb:close id="mssqlD2" />
    // <zdb:close id="mssqlALFA" />
  	case "copy":
      $strSource      = $arrParam["source"];
      $strDestination = $arrParam["destination"];

      $strIdSource      = $arrParam["idsource"];
      $strIdDestination = $arrParam["iddestination"];

      $errorMessage .= ztagParamCheck($arrParam, "source,destination,idsource,iddestination");

      $sql = "SELECT * FROM pubPaginas WHERE pagPai = $strIdSource";

      dbQuery($arrayTagId[$strSource][ztagIdHandle], $sql);

      while (dbFetch($arrayTagId[$strSource][ztagIdHandle])) {

      }

      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
