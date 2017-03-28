<?php
/**
 * zDoc
 *
 * Read all zTag files, update a database and create HTML doc file.
 *
 * @package ztag
 * @subpackage doc
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

define("zdocVersion", 1.0, 1);
define("zdocVersionSufix", "ALFA 0.1", 1);

/*
 * @author    author name <author@email> Documents the author of the current element.
 * @copyright name date                  Documents copyright information.
 * @example   /path/to/example           Documents the location of an external saved example file.
 * @link      URL
 * @param     type [param="sample"] description
 * @return    type description
 * @see                                  Documents an association to another zTag.
 * @uses                                 Documents an association to another method or class.
 * @since                                version Documents when a zTag was added to the package.
 * @version                              Provides the version number of a zTag
 */

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zdoc_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zdoc_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zdoc_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zdoc_version() {
  return zdocVersion." ".zdocVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zdoc_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zdoc_compare($version) {
  return zdocVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zdoc_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zdoc_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId    = $arrParam["id"];

  $strDir   = $arrParam["dir"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
   /*+
     * Read all z*.inc.php files and get all comments
     *
     * <code>
     * <zdoc:read dir="C:\My Dropbox\zTag\svn\ztag" />
     * </code>
     *
     * @param string dir="string" Full directory where the zTag .inc.php files are
     *
     * @return array Return an array with all docs and functions
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "read":
      $strVar = $arrParam["var"];

      if (is_dir ($strDir)) {
        if ($dirHandle = opendir($strDir)) {

          $arrayDoc = array();

          while (false !== ($strName = readdir($dirHandle))) {
            $strFilename = preg_replace('%[/\\\\]+%', '/', $strDir."/".$strName);

            if (is_file($strFilename) && substr($strName, strlen($strName) - 8, 8) === ".inc.php" && substr($strName, 0, 1) === "z") {
              $fileHandle = fopen($strFilename, "r");

              $strZTagName = substr($strName, 0, strlen($strName) - 8);

              $fileContent = fread($fileHandle, filesize($strFilename));

              preg_match_all('%\*\+(\n|\r\n)(?P<content>\s*\*\s*.*?)\*/.*?(case\s+[\'"](?P<case>\w+)["\']\s*:|function\s+\w+_(?P<function>\w+)\s*\()%si', $fileContent, $Matches, PREG_OFFSET_CAPTURE);

              foreach ($Matches[0] as $key => $value) {
                $strContent  = $Matches["content"][$key][0];
                $strCase     = $Matches["case"][$key][0];
                $strFunction = $Matches["function"][$key][0];

                $strKey = $strCase.$strFunction;

                // Clear all space *
                $strContent = preg_replace('/^(\s*\*)[ \t]*/m', '', $strContent);

                // Change all more than one line break to one
                $strContent = preg_replace('/(\r\n|\n){2,99}/m', '$1$1', $strContent);

                // echo "<hr /><pre>".htmlentities($strContent)."</pre>";

                preg_match_all('/^(?P<long>(?P<short>[^.\r\n]+)[^@]+)(?P<tags>@.*?)?$/s', $strContent, $MatchesContent, PREG_OFFSET_CAPTURE);

                $strShort = $MatchesContent["short"][0][0];
                $strLong  = $MatchesContent["long"][0][0];
                $strTags  = $MatchesContent["tags"][0][0];

                $arrayDoc[$strZTagName]["doc"][$strKey]["function"] = $strKey;
                $arrayDoc[$strZTagName]["doc"][$strKey]["short"] = $strShort;
                $arrayDoc[$strZTagName]["doc"][$strKey]["long"] = $strLong;
                // $arrayDoc[$strZTagName]["doc"][$strKey]["tags"] = $strTags;

                $arrayDoc[$strZTagName]["filename"] = $strName;
                $arrayDoc[$strZTagName]["filenamefull"] = $strFilename;

                $arrayDoc[$strZTagName]["name"] = $strZTagName;

                preg_match_all('/^@(?P<tag>(param|author|since|see|return|uses|copyright))(\s+)?((?P<type>\w+)\s+)?(?P<paramall>(?P<param>\w+)=["\'](?P<paramvalue>.*?)[\'"])?(?P<description>.*?)$/m', $strTags, $MatchesTag, PREG_OFFSET_CAPTURE);

                $arrayResult = array();

                foreach ($MatchesTag[0] as $keyTag => $valueTag) {
                  $strTag   = $MatchesTag["tag"][$keyTag][0];
                  $strParam = $MatchesTag["param"][$keyTag][0];

                  if (strlen($strParam)) {
                    $arrayResult[$strTag][$strParam]["tag"]         = $MatchesTag["tag"][$keyTag][0];
                    $arrayResult[$strTag][$strParam]["type"]        = $MatchesTag["type"][$keyTag][0];
                    $arrayResult[$strTag][$strParam]["paramall"]    = $MatchesTag["paramall"][$keyTag][0];
                    $arrayResult[$strTag][$strParam]["description"] = $MatchesTag["description"][$keyTag][0];
                    $arrayResult[$strTag][$strParam]["param"]       = $MatchesTag["param"][$keyTag][0];
                    $arrayResult[$strTag][$strParam]["paramvalue"]  = $MatchesTag["paramvalue"][$keyTag][0];

                  } else {
                    $arrayTagAux["tag"]         = $MatchesTag["tag"][$keyTag][0];
                    $arrayTagAux["type"]        = $MatchesTag["type"][$keyTag][0];
                    $arrayTagAux["paramall"]    = $MatchesTag["paramall"][$keyTag][0];
                    $arrayTagAux["description"] = $MatchesTag["description"][$keyTag][0];
                    $arrayTagAux["param"]       = $MatchesTag["param"][$keyTag][0];
                    $arrayTagAux["paramvalue"]  = $MatchesTag["paramvalue"][$keyTag][0];

                    $arrayResult[$strTag][] = $arrayTagAux;

                  }

                }

                $arrayDoc[$strZTagName]["doc"][$strKey]["params"] = $arrayResult;


                // if ($i++ > 10) die();

                // ^\s*\*\s+(?P<title>.*?)-\+-\+-(\r\n|\n)\s*\*\s+(?P<description>.*?)-\+-\+-
                // foreach ($MatchesSplit[0] as $keySplit => $valueSplit) {
                // }

              }

              fclose($fileHandle);

              // <code>(?P<code>.*?)</code>
              // @param\s+(?P<type>\w+)\s+(?P<param>\w+=["'].*?['"])\s+(?P<description>.*?)$


              // \s*\*\s+(?P<param>\w+)\s*=\s*["'](?P<value>(\d+|.*?))['"]\s+(?P<description>.*?)(\r\n|\n)
              // \s*\*\s*(\r\n|\n)\s*\*\s+(?P<description>.*?)(\r\n|\n)
            }
          }
          closedir($dirHandle);

          // echo "<hr><pre>".print_r($arrayDoc,1)."</pre>";

          if (strlen($strVar) && count($arrayDoc)) {
            $arrayTagId["$".$strVar][ztagIdValue] = $arrayDoc;
            $arrayTagId["$".$strVar][ztagIdLength] = count($arrayDoc);

            $arrayTagId["$".$strVar][ztagIdType] = idTypeFVar;

          }
        }
      } else {
        $errorMessage = "<br />The $strDir is not a directory";
      }
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
