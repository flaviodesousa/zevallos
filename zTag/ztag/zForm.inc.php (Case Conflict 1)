<?php
/**
 * ZForm
 *
 * Processa as tags para processo de formulários
 *
 * @package ztag
 * @subpackage form
 * @category UserInterface
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

define("zformVersion", 1.0, 1);
define("zformVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zform_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zform_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zform_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zform_version() {
  return zformVersion." ".zformVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zform_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zform_compare($version) {
  return zformVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zform_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zform_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId      = $arrParam["id"];

  $strTagId   = $arrParam["tagid"];
  $strName    = $arrParam["name"];
  $strCaption = $arrParam["caption"];
  $strValue   = $arrParam["value"];
  $strValues  = $arrParam["values"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  if (!$strTagId) $strTagId = $strName;

  $arrParam["id"] = $strTagId;
  $arrParam["for"] = $strTagId;

  if ($strCaption) {
  	if (strpos(" $strCaption", "&")) {
      $labelAccessKey = preg_match("%&(.)%i", $strCaption);

      if (!$labelAccessKey) $labelAccessKey = substr($strCaption, 1, 1);

      $arrParam["accesskey"] = $labelAccessKey;

      $strCaption = preg_replace("%^(.*)&(.)(.*)$%i", "$1<u>$2</u>$3", $strCaption);
    }
  }

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Input Text
     *
     * <code>
     * <zform:input type="text" name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string type="text"
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "input":
      $errorMessage = ztagParamCheck($arrParam, "type,name");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly,disabled,autocomplete,maxlength,size,tabindex,datebr");

      $arrayTag[$tagId][ztagResult] = "<input$strParam />";;
      break;

    /*+
     * Input Text
     *
     * <code>
     * <zform:inputtext name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "inputtext":
    	$arrParam["type"] = "text";

      $errorMessage = ztagParamCheck($arrParam, "type,name");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly,disabled,autocomplete,maxlength,size,tabindex,title,datebr");

      $arrayTag[$tagId][ztagResult] = "<input$strParam />";;
    	break;

    /*+
     * Input Hidden
     *
     * <code>
     * <zform:inputhidden name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "inputhidden":
      $arrParam["type"] = "hidden";

      $errorMessage = ztagParamCheck($arrParam, "type,name");

      $strParam = ztagParam($arrParam, "type,id,name,value");

      $arrayTag[$tagId][ztagResult] = "<input$strParam />";;
      break;

    /*+
     * Input Password
     *
     * <code>
     * <zform:inputpassword name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "inputpassword":
      $arrParam["type"] = "password";

      $errorMessage = ztagParamCheck($arrParam, "type,name");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly");

      $arrayTag[$tagId][ztagResult] = "<input$strParam />";;
      break;

    /*+
     * Input Checkbox
     *
     * <code>
     * <zform:inputcheckbox name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "inputcheckbox":
      $arrParam["type"] = "checkbox";

      $arrValues = explode(";", $strValues);

      if ($arrValues[0] == $strValue) $strChecked = " checked=\"checked\"";

      $errorMessage = ztagParamCheck($arrParam, "type,name,values");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly");

      $arrayTag[$tagId][ztagResult] = "<input$strParam$strChecked />";;
      break;

    /*+
     * Text Area
     *
     * <code>
     * <zform:textarea name="fieldName" tagid="fieldId" value="fieldValue" />
     *
     * <zform:textarea name="fieldName" tagid="fieldId" filename="fileSave.txt" filetype="txt">
     * Text Area value
     * </zform:textarea>
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @param string filename="/Alianca8/File.txt"
     * @param string filetype="txt"
     * @param string show="0"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "textarea":
      $strFileName   = $arrParam["filename"];
      $strFileType   = $arrParam["filetype"];

      $strShow       = strtolower($arrParam["show"]);

      if (strlen($strContent)) $arrParam["value"] = $strContent;

      $strValue = $arrParam["value"];

      if (strlen($strValue)) {
        $strValue = ztagVars($strValue, $arrayTagId);
        $strValue = ztagRun($strValue, 0, $arrayTagId);
      }

      $arrParam["type"] = "textarea";

      $errorMessage = ztagParamCheck($arrParam, "type,name");

      $strParam = ztagParam($arrParam, "type,id,name,style,required,readonly,disabled,cols,rows,tabindex,title");

      $arrayTag[$tagId][ztagResult] = "<textarea$strParam />$strValue</textarea>";

      $blnShow = ($strShow === "true" || $strShow === "1" || !strlen($strShow));

      if ($strFileName) {
        $strFileName = str_replace("\\", "/", $strFileName);

        if (substr($strFileName , 0, 1) === "/") $strFileName = substr($strFileName, 1);

        $strFileName = SiteRootDir.$strFileName;

        if (!$handleFile = fopen($strFileName, "w")) $errorMessage .= "\r\nCannot open file ($strFileName)";
      }

      if ($handleFile && fwrite($handleFile, $strValue) === FALSE) {
        if (!$errorMessageTemp) $errorMessage .= $errorMessageTemp = "\r\nCannot write to file ($strFileName)";

      }

      if ($handleFile) fclose($handleFile);

      break;

    /*+
     * Select
     *
     * <code>
     * <zform:select name="textNameSelect" tagid="textIdSelect" value="tagOptionValue1,tagOptionCaption1;tagOptionValue2,tagOptionCaption2;tagOptionValue3,tagOptionCaption3" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "select":
    	$strOptions = $arrParam["options"];

    	if ($strOptions) {
    		$arrOptions = explode(";", $strOptions);

    		foreach ($arrOptions as $keyOptions => $valueOptions) {
          $strSelected = "";

          $arrOption = explode(",", $valueOptions);

          if ($strValue == $arrOption[0]) $strSelected = " selected=\"selected\"";

          if (!$arrOption[1]) $arrOption[1] = $arrOption[0];

          $strOptions .= "<option value=\"$arrOption[0]\"$strSelected>$arrOption[1]</option>";

    		}

    	}

      $errorMessage = ztagParamCheck($arrParam, "name,options");

      $strParam = ztagParam($arrParam, "type,id,name,style,required,readonly,disabled,cols,rows,tabindex,title");

      $arrayTag[$tagId][ztagResult] = "<select$strParam />$strOptions</select>";
      break;

    /*+
     * Input Submit
     *
     * <code>
     * <zform:inputsubmit name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "inputsubmit":
      $arrParam["type"] = "submit";

      $errorMessage = ztagParamCheck($arrParam, "type,name,value");

      $strParam = ztagParam($arrParam, "type,id,name,value");

      $arrayTag[$tagId][ztagResult] = "<input$strParam />";;
      break;

    /*+
     * Box
     *
     * <code>
     * <zform:box type="text" caption="caption" name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "box":
    	$errorMessage = ztagParamCheck($arrParam, "type,caption,name");

    	$strLabel = ztagParam($arrParam, "accesskey,for");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly,disabled,autocomplete,maxlength,size,tabindex,title,datebr");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel>$strCaption<br /><input$strParam /></label>";
    	break;

    /*+
     * Box Text
     *
     * <code>
     * <zform:boxtext caption="caption" name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxtext":
      $arrParam["type"] = "text";

    	$errorMessage = ztagParamCheck($arrParam, "type,caption,name");

      $strLabel = ztagParam($arrParam, "accesskey,for");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly,disabled,autocomplete,maxlength,size,tabindex,title,datebr");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel>$strCaption<br /><input$strParam /></label>";
    	break;

    /*+
     * Box Password
     *
     * <code>
     * <zform:boxpassword name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxpassword":
      $arrParam["type"] = "password";

      $errorMessage = ztagParamCheck($arrParam, "type,caption,name");

      $strLabel = ztagParam($arrParam, "accesskey,for");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel>$strCaption<br /><input$strParam /></label>";
    	break;

    /*+
     * Box Checkbox
     *
     * <code>
     * <zform:boxcheckbox caption="&CheckBox" name="checkboxNameBox" tagid="checkboxIdBox"  value="checkboxValueOn" values="checkboxValueOn;checkboxValueOff" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxcheckbox":
      $arrParam["type"] = "checkbox";

      $arrValues = explode(";", $strValues);

      if ($arrValues[0] == $strValue) $arrParam["checked"] = "checked";

      $errorMessage = ztagParamCheck($arrParam, "type,name,value,values");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel><input$strParam /> $strCaption</label>";
      break;

    /*+
     * Box Radio
     *
     * <code>
     * <zform:boxradio caption="Processos com GTOs duplicadas" name="FL_CONTA_MEDICA_Estado" tagid="FL_DATA_Consulta" value="1" checked="$FL_GTO_DUPLICADACheck" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxradio":
      $arrParam["type"] = "radio";

      $arrValues = explode(";", $strValues);

      if ($arrValues[0] == $strValue) $arrParam["checked"] = "checked";

      $errorMessage = ztagParamCheck($arrParam, "type,name,value,values");

      $strParam = ztagParam($arrParam, "type,id,name,value,style,required,readonly");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel><input$strParam /> $strCaption</label>";
      break;

    /*+
     * Box Text Area
     *
     * <code>
     * <zform:boxtextarea name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxtextarea":
      $arrParam["type"] = "textarea";

      $errorMessage = ztagParamCheck($arrParam, "type,caption,name,value");

      $strLabel = ztagParam($arrParam, "accesskey,for");

      $strParam = ztagParam($arrParam, "type,id,name,style,required,readonly,disabled,cols,rows,tabindex,title");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel>$strCaption<br /><textarea$strParam />$strValue</textarea></label>";
      break;

    /*+
     * Box Select
     *
     * <code>
     * <zform:boxselect caption="&Text" name="textNameBox" tagid="textIdBox" value="textValueBox" options="tagOptionValue1,tagOptionCaption1;tagOptionValue2,tagOptionCaption2;tagOptionValue3,tagOptionCaption3" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "boxselect":
      $strOptions = $arrParam["options"];

      if ($strOptions) {
        $arrOptions = explode(";", $strOptions);
        $strOptions = "";

        foreach ($arrOptions as $keyOptions => $valueOptions) {
        	$strSelected = "";

          $arrOption = explode(",", $valueOptions);

        	if ($strValue == $arrOption[0]) $strSelected = " selected=\"selected\"";

          if (!$arrOption[1]) $arrOption[1] = $arrOption[0];

          $strOptions .= "<option value=\"$arrOption[0]\"$strSelected>$arrOption[1]</option>";

        }

      }

      $errorMessage = ztagParamCheck($arrParam, "name,options");

      $strLabel = ztagParam($arrParam, "accesskey,for");

      $strParam = ztagParam($arrParam, "type,id,name,style,required,readonly,disabled,cols,rows,tabindex,title");

      $arrayTag[$tagId][ztagResult] = "<label$strLabel>$strCaption<br /><select$strParam />$strOptions</select></label>";
      break;

    /*+
     * Button
     *
     * <code>
     * <zform:button name="fieldName" tagid="fieldId" value="fieldValue" />
     * </code>
     *
     * @param string name="fieldName"
     * @param string tagid="fieldId"
     * @param string value="fieldValue"
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "button":
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
?>
