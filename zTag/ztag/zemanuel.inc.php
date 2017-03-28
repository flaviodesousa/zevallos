<?php
/**
 * zEmanuel
 *
 * Processa as tags do Emanuel
 *
 * @package ztag
 * @subpackage template
 * @category Database
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

define("zemanuelVersion", 1.0, 1);
define("zemanuelVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zemanuel_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zemanuel_exist() {
	return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zemanuel_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zemanuel_version() {
  return zemanuelVersion." ".zemanuelVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zemanuel_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zemanuel_compare($version) {
  return zemanuelVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zemanuel_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zemanuel_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
	$arrParam = $arrayTag[$tagId][ztagParam];

	$strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strTransform = $arrParam["transform"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

	$errorMessage = "";

	switch (strtolower($tagFunction)) {
		// <zemanuel:open id="mysqlConn" driver="mysql" host="@@mysqlHost" database="@@mysqlDatabase" user="@@mysqlUser", password="@@mysqlPassword" />
		// <zemanuel:open id="sqliteConn" driver="sqlite" filename="/ZTag/SQLite.db" />
		case "oi":
			// echo "<pre>".print_r($arrayTag[$tagId], 1)."</pre>";

			if ($strContent) {
				if ($strTransform) $strContent = ztagTransform($strContent, $strTransform);

				$arrayTag[$tagId][ztagResult] = "<b>$strContent</b>";

			}

      if ($strValue) {
        if ($strTransform) $strValue = ztagTransform($strValue, $strTransform);

        $arrayTag[$tagId][ztagResult] = "<u>$strValue</u>";

      }

      if ($strVar) {
        if ($strTransform) $strVar = ztagTransform($strVar, $strTransform);

      	$arrayTag[$tagId][ztagResult] = "<i>$strVar</i>";

      } else {
	      if (!$strContent && !$strValue) $errorMessage .= ztagParamCheck($arrParam, "value");
      }

	    break;

		default:
			$errorMessage .= "<br />Undefined function \"$tagFunction\"";

	}

  ztagError($errorMessage, $arrayTag, $tagId);
}
