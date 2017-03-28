<?php
/**
 * zCTRL
 *
 * Processa as tags para gestão das variáveis do sistema.
 *
 * @package ztag
 * @subpackage var
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

define("zctrlVersion", 1.0, 1);
define("zctrlVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zctrl_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zctrl_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zctrl_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zctrl_version() {
  return zctrlVersion." ".zctrlVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zctrl_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zctrl_compare($version) {
  return zctrlVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zctrl_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zctrl_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  global $ztagExit;

  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId    = $arrParam["id"];

  $strUse   = $arrParam["use"];
  $strValue = $arrParam["value"];
  $strKey   = $arrParam["key"];
  $strVar   = $arrParam["var"];
  
  $strTransform = $arrParam["transform"];
  $strMessage = $arrParam["message"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Iterate over arrays executing it's content many time as it's content.
     *
     * <code>
     * <zctrl:foreach use="getAll" value="value">
     *   <zhtml:b value="$key" />: <zvar:show use="$value" /><br />
     * </zctrl:foreach>
     *
     * <zctrl:foreach use="getAll" key="key" value="value">
     *   <zhtml:b value="$key" />: <zvar:show use="$value" /><br />
     * </zctrl:foreach>
     * </code>
     *
     * @param string use="getAll" Variable array
     * @param string key="key" Variable where the array Key with saved
     * @param string value="value" Variable where the array Value with saved
     * @param string filename="/Alianca8/File.txt"
     * @param string filetype="txt"
     * @param string show="0"
     */
    case "foreach":
      $strFileName = $arrParam["filename"];
      $strFileType = $arrParam["filetype"];

      $strShow       = strtolower($arrParam["show"]);

      $errorMessage .= ztagParamCheck($arrParam, "use,value");

      if ($arrayTagId["$".$strUse][ztagIdType] != idTypeFVar) $errorMessage .= "<br />The handle \"$strUse\" is not a var one!";

      // $strArray = $arrayTagId["$".$strUse][ztagIdValue];

      preg_match_all('%^(?P<var>\w+)\\[(?P<index>[^\\]]+)\\]$%', $strUse, $Matches, PREG_OFFSET_CAPTURE);

      if (count($Matches[0][0])) {
        $var   = $Matches["var"][0][0];
        $index = $Matches["index"][0][0];

        $strArray = $arrayTagId["$".$var][ztagIdValue];

        $strArray = $strArray[$index];

      } else {
        if ($arrayTagId["$".$strUse][ztagIdType] != idTypeFVar) $errorMessage .= "<br />The handle \"$strUse\" is not a var one!";

        $strArray = $arrayTagId["$".$strUse][ztagIdValue];

      }
      
      if (is_array($strArray)) {
        if (strlen($strValue) && !strlen($strKey)) {
          foreach ($strArray as $value) {
          	zTagSetVar($arrayTagId, $strValue, $value);

            // $arrayTagId["$".$strValue][ztagIdValue] = $value;
            // $arrayTagId["$".$strValue][ztagIdType]  = idTypeFVar;

  				  $arrayTag[$tagId][ztagResult] .= ztagRun($strContent, 0, $arrayTagId);

  				  if ($ztagExit) break;
  				}
        } else {
          foreach ($strArray as $key => $value) {
            $arrayTagId["$".$strKey][ztagIdValue] = $key;
            $arrayTagId["$".$strKey][ztagIdType]  = idTypeFVar;

            $arrayTagId["$".$strValue][ztagIdValue] = $value;
            $arrayTagId["$".$strValue][ztagIdType]  = idTypeFVar;

            $arrayTag[$tagId][ztagResult] .= ztagRun($strContent, 0, $arrayTagId);

            if ($ztagExit) break;
          }
        }

        if ($strFileName) {
          $strFileName = str_replace("\\", "/", $strFileName);

          if (substr($strFileName , 0, 1) === "/") $strFileName = substr($strFileName, 1);

          $strFileName = SiteRootDir.$strFileName;

          if (!$handleFile = fopen($strFileName, "w")) {
            $errorMessage .= "\r\nCannot open file ($strFileName)";

          } else {
            if (fwrite($handleFile, $arrayTag[$tagId][ztagResult]) === FALSE) {
              $errorMessage .= "\r\nCannot write to file ($strFileName)";

            }
          }

          fclose($handleFile);
        }

        if ($strShow === "false" || $strShow === "0") $arrayTag[$tagId][ztagResult] = "";
      }
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:if value="$urlAssunto0" operator="e|eq|ne|gt|gte|lt|lte" with="0" var="ifVar" >
     * </zctrl:if>
     *
     * <zctrl:if value="$urlAssunto0" function="empty()|true()|false()|len()">
     * </zctrl:if>
     *
     * <zctrl:if value="$urlAssunto0" transform="substr(1, 3)" operator="=|<>|>|>=|<|=" with="test">
     * </zctrl:if>
     * </code>
     *
     * @param string value="value" Variable or value to be used.
     * @param string operator="e|eq|ne|gt|gte|lt|lte" operator to be used
     * @param string with="0" second part of an boolean expression
     * @param string transform="substr(1, 3)" Tranform the value's content
     * @param string function="empty()|true()|false()|len()" Apply a function to value's content and check if result is true
     * @param string var="ifVar" Variable to save all if result
     * @param string show="1|0|true|false" False to not show the If result content
     */
    case "if":
      if ($arrayTag[$tagId][ztagChildLast]) {
        // echo "<hr />tagId=$tagId";
        // echo "<br />ztagChildLast=".$arrayTag[$tagId][ztagChildLast];
        // echo "<br /><pre>arrayTag=".htmlentities(print_r($arrayTag[$tagId], 1))."</pre>";
        // echo "<br /><pre>ztagChild=".htmlentities(print_r($arrayTag[$tagId][ztagChild], 1))."</pre>";
        // echo "<br /><pre>ztagChildStack=".htmlentities(print_r($arrayTag[$tagId][ztagChildStack], 1))."</pre>";

        foreach ($arrayTag[$tagId][ztagChildStack] as $key => $value) {
          $intChildLast = $value;

          // echo "<hr />intChildLast=$intChildLast";
          // echo "<br /><pre>ztagContentWidth=[".htmlentities(print_r($arrayTag[$intChildLast][ztagContentWidth], 1))."]";
          // echo "<br />ztagContent=[".htmlentities(print_r($arrayTag[$intChildLast][ztagContent], 1))."]</pre>";
        }

        die();
      } else {
        // echo "<br /><pre>ztagContentWidth=[".htmlentities(print_r($arrayTag[$tagId][ztagContentWidth], 1))."]";
        // echo "<br />ztagContent=[".htmlentities(print_r($arrayTag[$tagId][ztagContent], 1))."]</pre>";

      }

      $strOperator  = $arrParam["operator"];
      $strWith      = $arrParam["with"];
      $strFunction  = $arrParam["function"];
      $strVar       = $arrParam["var"];
      $strShow      = $arrParam["show"];

      if (strlen($strOperator)) {
        $errorMessage .= ztagParamCheck($arrParam, "value,operator,with");

        if (strlen($strTransform)) $strValue = ztagTransform($strValue, $strTransform);

        switch (strtolower($strOperator)) {
          case "e":
          case "eq":
            $strOperator = "==";
            break;

          case "ne":
            $strOperator = "<>";
            break;

          case "gt":
            $strOperator = ">";
            break;

          case "gte":
            $strOperator = ">=";
            break;

          case "lt":
            $strOperator = "<";
            break;

          case "lte":
            $strOperator = "<=";
            break;

          default:
            $errorMessage .= "<br />Operator \"$strOperator\" does not exist!";

        }

        $strEVal = "if(\$strValue $strOperator \$strWith) {return 1;} else {return 0;}";

        $strValue = eval($strEVal);

      } else if (strlen($strFunction)) {
        $errorMessage .= ztagParamCheck($arrParam, "value,function");

        if (strlen($strTransform)) $strValue = ztagTransform($strValue, $strTransform);

        if (strlen($strFunction)) $strValue = ztagTransform($strValue, $strFunction);

      } else {
        $errorMessage .= ztagParamCheck($arrParam, "value,function");
      }

      if ($strValue) {
        $getValue .= ztagRun($strContent, 0, $arrayTagId);

        if (($strShow != "false" || $strShow != "0") && strlen($getValue)) $arrayTag[$tagId][ztagResult] = $getValue;

        if (strlen($strVar) && strlen($getValue)) {
          $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
          $arrayTagId["$".$strVar][ztagIdLength] = strlen($getValue);

          $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;
        }
      }
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:if value="$urlAssunto0" operator="e|ne|gt|gte|lt|lte" with="0" var="ifVar" >
     *  <zctrl:elseif value="$urlAssunto0" operator="e|ne|gt|gte|lt|lte" with="0" var="ifVar" />
     * </zctrl:if>
     * </code>
     *
     * @param string value="value" Variable or value to be used.
     * @param string operator="e|ne|gt|gte|lt|lte" operator to be used
     * @param string with="0" second part of an boolean expression
     * @param string transform="substr(1, 3)" Tranform the value's content
     * @param string function="empty()|true()|false()|len()" Apply a function to value's content and check if result is true
     * @param string var="ifVar" Variable to save all if result
     * @param string show="1|0|true|false" False to not show the If result content
     */
    case "elseif":
      $strOperator  = $arrParam["operator"];
      $strWith      = $arrParam["with"];
      $strFunction  = $arrParam["function"];
      $strVar       = $arrParam["var"];
      $strShow      = $arrParam["show"];

      if (strlen($strOperator)) {
        $errorMessage .= ztagParamCheck($arrParam, "value,operator,with");

        if (strlen($strTransform)) $strValue = ztagTransform($strValue, $strTransform);

        switch (strtolower($strOperator)) {
          case "e":
            $strOperator = "==";
            break;

          case "ne":
            $strOperator = "<>";
            break;

          case "gt":
            $strOperator = ">";
            break;

          case "gte":
            $strOperator = ">=";
            break;

          case "lt":
            $strOperator = "<";
            break;

          case "lte":
            $strOperator = "<=";
            break;

          default:
            $errorMessage .= "<br />Operator \"$strOperator\" does not exist!";

        }

        $strEVal = "if(\$strValue $strOperator \$strWith) {return 1;} else {return 0;}";

        $strValue = eval($strEVal);

      } else if (strlen($strFunction)) {
        $errorMessage .= ztagParamCheck($arrParam, "value,function");

        if (strlen($strTransform)) $strValue = ztagTransform($strValue, $strTransform);

        if (strlen($strFunction)) $strValue = ztagTransform($strValue, $strFunction);

      } else {
        $errorMessage .= ztagParamCheck($arrParam, "value,function");
      }

      if ($strValue) {
        $getValue .= ztagRun($strContent, 0, $arrayTagId);

        if (($strShow != "false" || $strShow != "0") && strlen($getValue)) $arrayTag[$tagId][ztagResult] = $getValue;

        if (strlen($strVar) && strlen($getValue)) {
          $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
          $arrayTagId["$".$strVar][ztagIdLength] = strlen($getValue);

          $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;
        }
      }
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:if value="$urlAssunto0" operator="e|ne|gt|gte|lt|lte" with="0" var="ifVar" >
     *  <zctrl:else />
     * </zctrl:if>
     * </code>
     */
    case "else":
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:switch value="$urlAssunto0">
     *  <zctrl:case value="10" />
     *  <zctrl:default />
     * </zctrl:switch>
     * </code>
     */
    case "switch":
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:switch value="$urlAssunto0">
     *  <zctrl:case value="10" />
     *  <zctrl:default />
     * </zctrl:switch>
     * </code>
     */
    case "case":
      break;

    /*+
     * Define a conditional execution of code fragments.
     *
     * <code>
     * <zctrl:switch value="$urlAssunto0">
     *  <zctrl:case value="10" />
     *  <zctrl:default />
     * </zctrl:switch>
     * </code>
     */
    case "default":
      break;

    /*+
     * Output a message and terminate the current script.
     *
     * <code>
     * <zctrl:die message="The script die here!" />
     *
     * <zctrl:die>
     *   The script die here!
     * </zctrl:die>
     * </code>
     *
     * @param string message="value" Variable or value to be printed
     */

    case "die":
    /*+
     * Output a message and terminate the current script.
     *
     * <code>
     * <zctrl:exit message="The script exited here!" />
     *
     * <zctrl:exit>
     *   The script exit here!
     * </zctrl:exit>
     * </code>
     *
     * @param string message="value" Variable or value to be printed
     */
    case "exit":
      $ztagExit = 1;

      if (strlen($strContent)) $strMessage = ztagVars($strContent, $arrayTagId);

      if (strlen($strMessage)) $arrayTag[$tagId][ztagResult] = $strMessage;
      break;

    /*+
     * Output a string
     *
     * <code>
     * <zctrl:echo message="This an echo message" />
     *
     * <zctrl:echo>
     * This an echo message
     * </zctrl:echo>
     * </code>
     *
     * @param string message="value" Variable or value to be used
     */
    case "echo":

    /*+
     * Output a string
     *
     * <code>
     * <zctrl:print message="This a print message" />
     *
     * <zctrl:print>
     * This a print message
     * </zctrl:print>
     * </code>
     *
     * @param string message="value" Variable or value to be used
     */
    case "print":
      if (strlen($strContent)) {
        $strMessage = ztagVars($strContent, $arrayTagId);
        $arrParam["message"] = $strMessage;
      }

      $errorMessage .= ztagParamCheck($arrParam, "message");

      if (strlen($strTransform) && strlen($strMessage)) $strMessage = ztagTransform($strMessage, $strTransform);

      if (strlen($strMessage)) ztagError($strMessage, $arrayTag, $tagId);
      break;

    /*+
     * Dumps information about a variable
     *
     * <code>
     * <zctrl:dump message="<b>arrayDump</b>=" use="arrayDump" />
     * </code>
     *
     * @param string use="arrayDump" Variable to be dumped
     * @param string message="Message" Message to complement the Dump
     */
    case "dump":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      // echo "<hr><pre>".print_r($strUse,1)."</pre>";
      // echo "<hr><pre>".print_r($arrayTagId["$".$strUse][ztagIdValue],1)."</pre>";

      ztagError($strMessage.var_dump($arrayTagId["$".$strUse][ztagIdValue]), $arrayTag, $tagId);
      break;

    /*+
     * Prints human-readable information about a variable
     *
     * <code>
     * <zctrl:printr message="<b>arrayDump</b>=" use="arrayDump" />
     * </code>
     *
     * @param string use="arrayDump" Variable to be printed
     * @param string message="Message" Message to complement the Dump
     */
    case "printr":
      ztagError(print_r($arrayTagId, 1), $arrayTag, $tagId);

      $errorMessage .= ztagParamCheck($arrParam, "use");

      ztagError($strMessage.print_r($arrayTagId["$".$strUse][ztagIdValue], 1), $arrayTag, $tagId);
      break;

    /*+
     * Iterate over arrays executing it's content many time as it's content.
     *
     * <code>
     * <zctrl:while value="$urlAssunto0" operator="e|ne|gt|gte|lt|lte" with="0" var="ifVar" >
     * </zctrl:while>
     *
     * <zctrl:while value="$urlAssunto0" function="empty()|true()|false()|len()">
     * </zctrl:while>
     *
     * <zctrl:while value="$urlAssunto0" transform="substr(1, 3)" operator="=|<>|>|>=|<|=" with="test">
     * </code>
     *
     * @param string use="getAll" Variable array
     * @param string key="key" Variable where the array Key with saved
     * @param string value="value" Variable where the array Value with saved
     */
    case "while":
      $errorMessage .= ztagParamCheck($arrParam, "use,value");

      if ($arrayTagId["$".$strUse][ztagIdType] != idTypeFVar) $errorMessage .= "<br />The handle \"$strUse\" is not a var one!";

      $strArray = $arrayTagId["$".$strUse][ztagIdValue];

      if (is_array($strArray)) {
        if (strlen($strValue) && !strlen($strKey)) {
          foreach ($strArray as $value) {
            $arrayTagId["$".$strValue][ztagIdValue] = $value;
            $arrayTagId["$".$strValue][ztagIdType]  = idTypeFVar;

            $arrayTag[$tagId][ztagResult] .= ztagRun($strContent, 0, $arrayTagId);

            if ($ztagExit) break;
          }
        } else {
          foreach ($strArray as $key => $value) {
            $arrayTagId["$".$strKey][ztagIdValue] = $key;
            $arrayTagId["$".$strKey][ztagIdType]  = idTypeFVar;

            $arrayTagId["$".$strValue][ztagIdValue] = $value;
            $arrayTagId["$".$strValue][ztagIdType]  = idTypeFVar;

            $arrayTag[$tagId][ztagResult] .= ztagRun($strContent, 0, $arrayTagId);

            if ($ztagExit) break;
          }
        }
      }
      break;

    /*+
     * Output a message and terminate the current script.
     *
     * <code>
		 * <zctrl:start var="resultTime" />
		 * </code>
     *
     * @param string var="variable" a local variable to save data
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "start":
      $errorMessage = ztagParamCheck($arrParam, "var");
    	
      $mtime = microtime();
      $mtime = explode(' ', $mtime);
      
      zTagSetVar($arrayTagId, $strVar, $mtime[1] + $mtime[0]);
      break;

    /*+
     * Output a message and terminate the current script.
     *
     * <code>
     * <zctrl:stop use="runTime" var="resultTime" />
     * </code>
     *
     * @param string use="variable" a local variable used save data
     * @param string var="variable" a local variable to save the current value
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "stop":
      $errorMessage = ztagParamCheck($arrParam, "use");
      
      $starttime = $arrayTagId["$".$strUse][ztagIdValue];
    	
      $mtime = microtime();
      $mtime = explode(" ", $mtime);
      $mtime = $mtime[1] + $mtime[0];
      
      $result = $mtime - $arrayTagId["$".$strUse][ztagIdValue];
      
      if (strlen($strVar)) {
      	zTagSetVar($arrayTagId, $strVar, $result);
      } else {
      	if (strlen($strTransform)) $result = ztagTransform($result, $strTransform);
      	
      	$arrayTag[$tagId][ztagResult] = $result;
      }
      break;

    /*+
     * Output a message and terminate the current script.
     *
     * <code>
     * <zctrl:eval>
     *  echo '<br />Test do eval";
     * </zctrl:eval>
     *
     * <zctrl:eval value="'Timestamp:'.date('d/m/Y');" />
     * </code>
     *
     * @param string value="code" PHP valid code to execute
     *
     * @author Misael Ferreira <lmisael@gmail.com>
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "eval":
      if (strlen($strContent)) {
        $phpCode = ztagVars($strContent, $arrayTagId);
        $arrParam["value"] = $phpCode;
      }

      $errorMessage .= ztagParamCheck($arrParam, "value");

      $phpCode = $arrParam["value"];

      // if (strlen($phpCode)) $phpCode = ztagVars($phpCode, $arrayTagId);

      echo "<br />$phpCode";

      if (strlen($phpCode)) $arrayTag[$tagId][ztagResult] = eval($phpCode); //"return $phpCode;"
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
