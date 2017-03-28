<?php
/**
 * zLayout
 *
 * Processa as tags para gestão de layout
 *
 * @package ztag
 * @subpackage layout
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


$layoutLastCreateId = "";
$layoutColumnOrder = array();

// Tipos de nós disponíveis no layout
define("layoutTypeHeader",  0);
define("layoutTypeBody",    1);
define("layoutTypeFooter",  2);
define("layoutTypeOrder",   3);
define("layoutTypeDefault", 4);

define("layoutOrder",    0);
define("layouCaption",   1);
define("layoutStyle",    2);
define("layoutBefore",   3);
define("layoutAfter",    4);
define("layouHeadTag",   5);
define("layouCellTag",   6);
define("layouTransform", 7);


function zlayout_exist() {
	return 1;
}

function zlayout_execute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
	global $layoutLastCreateId;
  global $layoutColumnOrder;

	$arrParam = $arrayTag[$tagId][ztagParam];

	$strId        = $arrParam["id"];
	$strUse       = $arrParam["use"];
	$strName      = $arrParam["name"];

	$strTransform = $arrParam["transform"];

	$errorMessage = "";

	switch (strtolower($tagFunction)) {
    /*+
     * Create
     *
     * <code>
     * <zlayout:create id="ociPrestador" headtag="th" celltag="td">
     * </code>
     *
     * @param string id="ociPrestador"
     * @param string headtag="th"
     * @param string celltag="td"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
		case "create":
			$errorMessage .= ztagParamCheck($arrParam, "id");

      $layoutLastCreateId = $strId;

			$layoutColumnOrder[$strId] = 0;

      if ($arrayTag[$tagId][ztagContentWidth]) {
      	$strContent = $arrayTag[$tagId][ztagContent];

				$arrayTagId[$strId][ztagIdValue]  = $strContent;
				$arrayTagId[$strId][ztagIdLength] = strlen($strValue);

				$arrayTagId[$strId][ztagIdType] = idTypeLayout;

				if ($arrParam["headtag"]) $arrayTagId[$strId][layoutTypeDefault][layouHeadTag] = $arrParam["headtag"];
        if ($arrParam["celltag"]) $arrayTagId[$strId][layoutTypeDefault][layouCellTag] = $arrParam["celltag"];

        ztagRun($strContent, 0, $arrayTagId);

        echo "<pre>";
        print_r($arrayTagId);

      }
			break;

    /*+
     * Column
     *
     * <code>
     * <zlayout:column use="ociPrestador" name="NM_PESSOA" caption="Nome" headstyle="C,U" bodystyle="R" transform="sentence()" />
     * </code>
     *
     * @param string use="ociPrestador"
     * @param string name="NM_PESSOA"
     * @param string caption="Nome"
     * @param string headstyle="C,U"
     * @param string bodystyle="R"
     * @param string transform="sentence()"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
		case "column":
			if (!$strUse) $arrParam["use"] = $layoutLastCreateId;

			$strUse       = $arrParam["use"];
      $strCaption   = $arrParam["caption"];
      $strHeadStyle = $arrParam["headstyle"];
      $strBodyStyle = $arrParam["bodystyle"];

      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      if ($arrayTagId[$strUse][ztagIdType] == idTypeLayout) {
      	$arrLayout = $arrayTagId[$strUse][ztagIdLayout];

      	$intOrder = $layoutColumnOrder[$strUse]++;

      	$arrLayout[$strName][layoutTypeOrder][$intOrder] = $strName;

      	if (!$strCaption) $strCaption = $strName;

        $arrLayout[$strName][layoutTypeHeader][layouCaption] = $strCaption;
        $arrLayout[$strName][layoutTypeHeader][layoutStyle]  = $strHeadStyle;

        $arrLayout[$strName][layoutTypeBody][layoutStyle]    = $strBodyStyle;
        $arrLayout[$strName][layoutTypeBody][layouTransform] = $strTransform;

        // unset($arrayTagId[$strId][ztagIdLayout]);

        $arrayTagId[$strUse][ztagIdLayout] = $arrLayout;

      } else {
      	$errorMessage .= "<br />The id \"$strUse\" is not a layout type";
      }

			break;

		default:
			$errorMessage .= "<br />Undefined function \"$tagFunction\"";

	}

	ztagError($errorMessage, $arrayTag, $tagId);
}
