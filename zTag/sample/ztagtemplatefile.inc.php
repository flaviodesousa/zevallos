<?php
/**
 * zTagTemplateFile
 *
 * zTag template file... this functions do not have any purpose, but some could work.
 *
 * @package ztag
 * @subpackage template
 * @category Database
 * @version $Revision$
 * @author me <me@mydomain.tld>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://mydomain.tld
 * @copyright 2010 by me
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

define("ztagtemplatefileVersion", 1.0, 1);
define("ztagtemplatefileVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * ztagtemplatefile_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function ztagtemplatefile_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * ztagtemplatefile_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function ztagtemplatefile_version() {
  return ztagtemplatefileVersion." ".ztagtemplatefileVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * ztagtemplatefile_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function ztagtemplatefile_compare($version) {
  return ztagtemplatefileVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * ztagtemplatefile_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function ztagtemplatefile_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strTransform = $arrParam["transform"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * <ztagtemplatefile:hi value="My hi" />
     * 
     * or
     * 
     * <ztagtemplatefile:hi var="$varHi" />
     * 
     * or
     * 
     * <ztagtemplatefile:hi>
     *   My inner hi
     * </ztagtemplatefile:hi>
     * 
     * A zTag function Hi
     * 
     *  value="My hi" Message to show to me
     */
    case "hi":
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

/*+
 * <ztagtemplatefile:test value="My Test" />
 * 
 * or
 * 
 * <ztagtemplatefile:test var="$varTest" />
 * 
 * or
 * 
 * <ztagtemplatefile:test>
 *   My inner Test
 * </ztagtemplatefile:test>
 * 
 * A zTag function Test
 * 
 *  value="My Test" Message to show to me
 */
function ztagtemplatefile_test($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strValue = $arrParam["value"];
  $strVar   = $arrParam["var"];
  
  $templateContent = $arrayTag[$tagId][ztagContent];

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
}
