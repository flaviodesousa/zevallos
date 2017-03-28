<?php
/**
 * zNoSQL
 *
 * Processa as tags zNoSQL
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

define("znosqlVersion", 1.0, 1);
define("znosqlVersionSufix", "ALFA 0.1", 1);
/**
 * Constants for future NoSQL driver manager
 */
define("nosqlMongo", "Mongo", 1);

/**#@+
 * Define the nosqlHandle structure
 */
define("nosqlHandleDriver",          0, 1);
define("nosqlHandleId",              1, 1);
define("nosqlHandleHost",            2, 1);
define("nosqlHandleDatabaseName",    3, 1);
define("nosqlHandleUser",            4, 1);
define("nosqlHandlePassword",        5, 1);
define("nosqlHandlePort",            6, 1);
define("nosqlHandleCollectionName",  7, 1);
define("nosqlHandleState",           8, 1);
define("nosqlHandleDatabase",        9, 1);
define("nosqlHandleCollection",     10, 1);

/**#@+
 * Define nosqlHandle state
 */
define("nosqlHandleStateOpen",   1, 1);
define("nosqlHandleStateClosed", 0, 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * znosql_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function znosql_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * znosql_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function znosql_version() {
  return znosqlVersion." ".znosqlVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * znosql_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function znosql_compare($version) {
  return znosqlVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * znosql_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function znosql_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId        = $arrParam["id"];

  $strUse        = $arrParam["use"];
  $strDatabase   = $arrParam["database"];
  $strCollection = $arrParam["collection"];
  $strName       = $arrParam["name"];

  $strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strTransform = $arrParam["transform"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Open connection to a NoSQL Server
     *
     * <code>
     * <znosql:open id="mongoDB" driver="Mongo" host="username:password@mongodb.zyc.com.br:27017" database="Direito2" collection="pubPaginas" />
     * <znosql:open id="mongoDB" driver="Mongo" host="mongodb.zyc.com.br" username="username" password="password" port="27017" database="Direito2" collection="pubPaginas" />
     * </code>
     *
     * @param string id="mongoDB"
     * @param string driver="Mongo"
     * @param string host="username:password@mongodb.zyc.com.br:27017" <-- mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
     * @param string username="username"
     * @param string password="password"
     * @param int port="27017"
     * @param string options="option=value;option2=value2"
     * @param string database="Direito2"
     * @param string collection="pubPaginas"
     * @param boolean persist="0|1|true|false|x"
     */
    case "open":
      $strDriver     = $arrParam["driver"];
    	$strHost       = $arrParam["host"];

      $strUsername   = $arrParam["username"];
      $strPassword   = $arrParam["password"];
      $strPort       = $arrParam["port"];
      $strPersist    = $arrParam["persist"];
      $strOptions    = $arrParam["options"];

      ztagReturnConstant($strHost);
      ztagReturnConstant($strDatabase);
      ztagReturnConstant($strUsername);
      ztagReturnConstant($strPassword);
      ztagReturnConstant($strPort);

    	$errorMessage .= ztagParamCheck($arrParam, "id,driver,host");

      $strDriver = constant("nosql$strDriver");

      if (strlen($strUsername) && strlen($strPassword)) $strHost = "$strUsername:$strPassword@$strHost";
      if (strlen($strPort)) $strHost = "$strHost:$strPort";
      if (strlen($strOptions)) $strHost = "$strHost?$strOptions";

    	if (extension_loaded('mongo')) {
        try {
        	$nosqlHandle = Array();

        	if ($strPersist === "true" || $strPersist === "1" || $strPersist ="x") {
            $dbHandle = new  Mongo($strHost, array("persist" => "x"));
        	} else {
            $dbHandle = new  Mongo($strHost);
        	}

          if ($strDatabase) $nosqlDB = $nosqlHandle->selectDB($strDatabase);

        } catch(MongoConnectionException $e) {
          $errorMessage .= "<br />Cannot connect to $strDriver (".$e->getMessage().")";
        }

        if (strlen($strCollection)) $nosqlColection = $nosqlDB->selectCollection($strCollection);

    	} else {
    		$errorMessage .= "<br />$strDriver extention is not installed!";
    	}

			// Set all NoSQL handler structure
			$nosqlHandle[nosqlHandleDriver]       = $strDriver;

			$nosqlHandle[nosqlHandleHost]         = $strHost;
			$nosqlHandle[nosqlHandleDatabaseName] = $dbDatabase;
			$nosqlHandle[nosqlHandleUser]         = $strUsername;
			$nosqlHandle[nosqlHandlePassword]     = $strPassword;
			$nosqlHandle[nosqlHandlePort]         = $strPort;

			$nosqlHandle[nosqlHandleId]           = $dbHandle;

			$nosqlHandle[nosqlHandleState]        = dbHandleStateOpen;

      $arrayTagId[$strId][ztagIdHandle]     = $nosqlHandle;
      $arrayTagId[$strId][ztagIdType]       = idTypeNoSQL;
      $arrayTagId[$strId][ztagIdState]      = idStateOpened;
    	break;

    /*+
     * Close a Opened NoSQL Handle connection
     *
     * <code>
     * <znosql:close use="mongoDB">
     * </code>
     *
     * @param string use="mongoDB"
     *
     */
    case "close":
      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $dbHandle = $nosqlHandle[nosqlHandleId];

          $dbHandle->close();

          $arrayTagId[$strUse][ztagIdHandle] = $dbHandle;

          $arrayTagId[$strUse][ztagIdState] = idStateClosed;

        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Do an Insert in currente Collection
     *
     * </code>
     * <znosql:insert use="mongoDB">
     *  pagCodigo="1"
     *  , pagNome="Page \"title\""
     *  , pagResumo='Page \'resume\''
     *  , pagDescricao:"Page body"
     *  , pagPalavrasChave:{"example","NoSQL"}
		 *  , pagInclusao='2010/10/10'
		 *  , pagAtivo=1
     * </znosql:insert>
     *
     * <znosql:insert use="mongoDB" value="$varInsert" />
     * </code>
     *
     *  @param string use="mongoDB"
     *  @param boolean safe="true|false|1|0"
     *  @param boolean fsync="true|false|1|0"
     */
    case "insert":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
          	$dbHandle = $nosqlHandle[nosqlHandleId];

            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            // Prepare the tag content
						if (strlen($strContent)) $contentArray = $strContent;

						if (strlen($strValue)) $contentArray = $strValue;

						if (strlen($contentArray)) $contentArray = ztagVars($contentArray, $arrayTagId);

						if (strlen($contentArray)) $contentArray = ztagRun($contentArray, 0, $arrayTagId);

						if (!strlen($strContent) && !strlen($strValue)) $errorMessage .= ztagParamCheck($arrParam, "value");

						if (strlen($strTransform) && strlen($contentArray)) $contentArray = ztagTransform($contentArray, $strTransform);

						$contentArray = znosql_lib_Content2Array($contentArray);

						// @TODO Think how to manage the safe option and it's MongoCursorException
						try {
            	$dbResult = $dbHandleCollection->insert($contentArray);

            	// echo "<br /><pre>".print_r($contentArray, 1)."</pre>";

              if (!strlen($dbResult)) {
                $errorMessage .= "<br />Cannot insert into collection $strCollection at database $strDatabase  <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(MongoException $e) {
              $errorMessage .= "<br />Cannot insert into collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Execute a NoSQL commands
     *
     * <code>
     * <znosql:get use="mongoDB">
     *   Commands
     * </znosql:get>
     * </code>
     *
     * @param string use="mongoDB"
     *
     */
    case "getone":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            // Prepare the tag content
            if (strlen($strContent)) $contentArray = $strContent;

            if (strlen($strValue)) $contentArray = $strValue;

            if (strlen($contentArray)) $contentArray = ztagVars($contentArray, $arrayTagId);

            if (!strlen($strContent) && !strlen($strValue)) $errorMessage .= ztagParamCheck($arrParam, "value");

            if (strlen($strTransform) && strlen($contentArray)) $contentArray = ztagTransform($contentArray, $strTransform);

            $contentArray = ltrim($contentArray, "\r\n");
            $contentArray = rtrim($contentArray, "\r\n");

            preg_match_all("%criteria\s*{(?P<criteria>(?:\\}|[^}])*?)}(\s*,\s*fields\s*{(?P<fields>(?:\\}|[^}])*?)})?%", $contentArray, $Matches, PREG_OFFSET_CAPTURE);

            $arrayCriteria = znosql_lib_Content2Array($Matches["criteria"][0][0]);

            if ($Matches["fields"][0][0]) $arrayFields = znosql_lib_Content2Array($Matches["fields"][0][0], 1);

            // @TODO Think how to manage the safe option and it's MongoCursorException
            try {
              if (count($arrayFields)) {
                $dbResult = $dbHandleCollection->findOne($arrayCriteria, $arrayFields);
              } else {
              	$dbResult = $dbHandleCollection->findOne($arrayCriteria);
              }

	            if (strlen($strVar) && count($dbResult)) {
	            	$arrayTagId["$".$strVar][ztagIdValue] = $value;
	              $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
	            }
            } catch(MongoConnectionException $e) {
              $errorMessage .= "<br />Cannot find the criteria into collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
    	break;

    /*+
     * Execute a NoSQL commands
     *
     * <code>
     * <znosql:get use="mongoDB">
     *   Commands
     * </znosql:execute>
     * </code>
     *
     * $query - the evaluation or "where" expression
     * $orderby - sort order desired
     * $hint - hint to query optimizer
     * $explain - if true, return explain plan results instead of query results
     * $snapshot - if true, "snapshot mode"
     */
    case "getall":
      /*$errorMessage .= ztagParamCheck($arrParam, "use");
	    // $cursor = $this->collection->find($f);

	    $k = array();
	    $i = 0;

	    while( $cursor->hasNext())
	    {
	        $k[$i] = $cursor->getNext();
	      $i++;
	    }

	    // return $k;
	     *
	     */
    break;

    /*+
     * Do an Update in currente Collection
     *
     * <code>
     * <znosql:update use="mongoDB">
     *  criteria{pagCodigo="1"}
     *  , set{pagNome="Page \"title\" Updated"
     *  , pagResumo='Page \'resume\''
     *  , pagDescricao:"Page body"
     *  , pagPalavrasChave:{"example","NoSQL"}
     *  , pagInclusao='2010/10/10'
     *  , pagAtivo=1
     *  }
     * </znosql:update>
     *
     * <znosql:update use="mongoDB" criteria="$varCriteria" value="$varInsert" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param boolean upsert="true|false|1|0"
     * @param boolean multiple="true|false|1|0"
     * @param boolean safe="true|false|1|0"
     * @param boolean fsync="true|false|1|0"
     */
    case "update":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            // Prepare the tag content
            if (strlen($strContent)) $contentArray = $strContent;

            if (strlen($strValue)) $contentArray = $strValue;

            if (strlen($contentArray)) $contentArray = ztagVars($contentArray, $arrayTagId);

            if (!strlen($strContent) && !strlen($strValue)) $errorMessage .= ztagParamCheck($arrParam, "value");

            if (strlen($strTransform) && strlen($contentArray)) $contentArray = ztagTransform($contentArray, $strTransform);

            $contentArray = ltrim($contentArray, "\r\n");
            $contentArray = rtrim($contentArray, "\r\n");

            preg_match_all("%criteria\s*{(?P<criteria>(?:\\}|[^}])*?)}\s*,\s*set\s*{(?P<set>(?:\\}|[^}])*?)}%", $contentArray, $Matches, PREG_OFFSET_CAPTURE);

            // echo "<br /><pre>".print_r($Matches, 1)."</pre>";

            $arrayCriteria = znosql_lib_Content2Array($Matches["criteria"][0][0]);
            $arraySet      = array('$set' => znosql_lib_Content2Array($Matches["set"][0][0]));

            // @TODO Think how to manage the safe option and it's MongoCursorException
            try {
              $dbResult = $dbHandleCollection->update($arrayCriteria, $arraySet);

              // echo "<br /><pre>".print_r($contentArray, 1)."</pre>";

              if (!$dbResult) {
                $errorMessage .= "<br />Cannot update the criteria into collection $strCollection at database $strDatabase  <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot update the criteria into collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Delete a criteria at current collection
     *
     * <code>
     * <znosql:delete use="mongoDB">
     *  pagCodigo="1"
     * </znosql:delete>
     *
     * <znosql:delete use="mongoDB" criteria="$varCriteria" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param boolean justone="true|false|1|0"
     * @param boolean safe="true|false|1|0"
     * @param boolean fsync="true|false|1|0"
     */
    case "delete":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            // Prepare the tag content
            if (strlen($strContent)) $contentArray = $strContent;

            if (strlen($strValue)) $contentArray = $strValue;

            if (strlen($contentArray)) $contentArray = ztagVars($contentArray, $arrayTagId);

            if (!strlen($strContent) && !strlen($strValue)) $errorMessage .= ztagParamCheck($arrParam, "value");

            if (strlen($strTransform) && strlen($contentArray)) $contentArray = ztagTransform($contentArray, $strTransform);

            $contentArray = znosql_lib_Content2Array($contentArray);

            // echo "<br /><pre>contentArray=".print_r($contentArray, 1)."</pre>";

            // @TODO Think how to manage the safe option and it's MongoCursorException
            try {
              $dbResult = $dbHandleCollection->remove($contentArray);

              // echo "<br /><pre>dbResult=".print_r($dbResult, 1)."</pre>";

              if (!$dbResult) {
                $errorMessage .= "<br />Cannot delete the criteria into collection $strCollection at database $strDatabase  <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot delete the criteria into collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Execute a NoSQL commands
     *
     * <code>
     * <znosql:execute use="mongoDB">
     *   Commands
     * </znosql:execute>
     * </code>
     *
     * @param string use="mongoDB"
     *
     */
    case "execute":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      // Execute Close, but I don't know yet!
      $arrayTagId[$strUse][ztagIdState] = idStateClosed;

    	// mongo->execute($cmd);
      break;

    /*+
     * Set a database for current Opened NoSQL connection
     *
     * <code>
     * <znosql:setdatabase use="mongoDB" name="Direito2" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param string name="Direito2" The database name.
     */
    case "setdatabase":
      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
	      if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $dbHandle = $nosqlHandle[nosqlHandleId];

          try {
            $dbHandleDatabase = $dbHandle->selectDB($strName);

            $nosqlHandle[nosqlHandleDatabaseName] = $strName;
            $nosqlHandle[nosqlHandleDatabase]     = $dbHandleDatabase;

	        } catch(InvalidArgumentException $e) {
	          $errorMessage .= "<br />Invalid database name $strName (".$e->getMessage().")";
	        }

          $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

	      } else {
	        $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
	      }
      } else {
      	$errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * List all databases
     *
     * <code>
     * <znosql:listdatabases use="mongoDB" id="mongoDBlist" var="databasesList" total="databasesTotalSize">
     *   <br /><znosql:field use="mongoDBlist" name="name" /> - <znosql:field use="mongoDBlist" name="sizeOnDisk" /> - <znosql:field use="mongoDBlist" name="empty" />
     * </znosql:listdatabases>
     * </code>
     *
     * @param string use="mongoDB" A NoSQL handle
     * @param string id="mongoDBlist" Id with array of each line
     * @param string var="databasesList" Variable with array of each line
     * @param string total="databasesTotalSize" Variable with total size
     */
    case "listdatabases":
    	$strTotal = $arrParam["total"];

      $errorMessage .= ztagParamCheck($arrParam, "use,id");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $dbHandle = $nosqlHandle[nosqlHandleId];

          $arrayDatabases = $dbHandle->listDBs();

          $totalDbSize = $arrayDatabases['totalSize'];

          foreach ($arrayDatabases["databases"] as $key => $value) {
	          if (strlen($strId)) {
		          $arrayTagId[$strId][ztagIdValue] = $arrayDatabases["databases"][$key];
		          $arrayTagId[$strId][ztagIdType]  = idTypeValue;
		        }

            if (strlen($strVar)) {
              $arrayTagId["$".$strVar][ztagIdValue] = $arrayDatabases["databases"][$key];
              $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
            }

		        $strResult = ztagRun($strContent, 0, $arrayTagId);

		        $arrayTag[$tagId][ztagResult] .= $strResult;
			    }

          if (strlen($strTotal)) {
            $arrayTagId["$".$strTotal][ztagIdValue] = $arrayDatabases[totalSize];
            $arrayTagId["$".$strTotal][ztagIdType]  = idTypeFVar;
          }

        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Return the server Stats
     *
     * <code>
     * <znosql:getstats use="mongoDB" var="mongoStats" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param string var="mongoStats"
     */
    case "getstats":
      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $dbHandle = $nosqlHandle[nosqlHandleId];

          $arrayDatabases = $dbHandle->listDBs();

		      $dbHandleAdmin = $dbHandle->selectDB('admin');

		      $arrayReturn = array_merge($dbHandleAdmin->command(array('buildinfo' => 1)),
		                                 $dbHandleAdmin->command(array('serverStatus' => 1)));

		      $profile = $dbHandleAdmin->command(array('profile' => -1));

		      $arrayReturn['profilingLevel'] = $profile['was'];

		      $arrayReturn['mongoDbTotalSize'] = round($totalDbSize / 1000000) . 'mb';

		      $prevError = $dbHandleAdmin->command(array('getpreverror' => 1));

		      if (!$prevError['n']) {
		          $arrayReturn['previousDbErrors'] = 'None';
		      } else {
		          $arrayReturn['previousDbErrors']['error'] = $prevError['err'];
		          $arrayReturn['previousDbErrors']['numberOfOperationsAgo'] = $prevError['nPrev'];
		      }

		      $arrayReturn['globalLock']['totalTime'] .= ' &#0181;Sec';

		      $arrayReturn['uptime'] = round($arrayReturn['uptime'] / 60) . ':' . str_pad($arrayReturn['uptime'] % 60, 2, '0', STR_PAD_LEFT). ' minutes';

		      $arrayUnshift['mongo'] = $arrayReturn['version'] . ' (' . $arrayReturn['bits'] . '-bit)';
		      $arrayUnshift['mongoPhpDriver'] = Mongo::VERSION;
		      $arrayUnshift['phpMoAdmin'] = '1.0.8';
		      $arrayUnshift['php'] = PHP_VERSION . ' (' . (PHP_INT_MAX > 2200000000 ? 64 : 32) . '-bit)';
		      $arrayUnshift['gitVersion'] = $arrayReturn['gitVersion'];

		      unset($arrayReturn['ok'], $arrayReturn['version'], $arrayReturn['gitVersion'], $arrayReturn['bits']);

		      $arrayReturn = array_merge(array('version' => $arrayUnshift), $arrayReturn);

		      $iniIndex = array(-1 => 'Unlimited', 'Off', 'On');

		      $phpIni = array('allow_persistent', 'auto_reconnect', 'chunk_size', 'cmd', 'default_host', 'default_port',
		                      'max_connections', 'max_persistent');

		      foreach ($phpIni as $ini) {
	          $key = 'php_' . $ini;

	          $arrayReturn[$key] = ini_get('mongo.' . $ini);

	          if (isset($iniIndex[$arrayReturn[$key]])) {
	              $arrayReturn[$key] = $iniIndex[$arrayReturn[$key]];
	          }
		      }
          if (strlen($strVar)) {
            $arrayTagId["$".$strVar][ztagIdValue] = $arrayReturn;
            $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
          }

        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }

    	break;

    /*+
     * Repair the current Database
     *
     * <code>
     * <znosql:repairdatabase use="mongoDB" preserveclonedfiles="true" backuporiginalfiles="true" />
     * </code>
     *
     * @param boolean preserveclonedfiles="true|false|1|0"
     * @param boolean backuporiginalfiles="true|false|1|0"
     */
    case "repairdatabase":
    	$strPreserveClonedFiles = $arrParam["preserveclonedfiles"];
      $strBackupOriginalFiles = $arrParam["backuporiginalfiles"];

      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleDatabase]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $dbHandleDatabase = $nosqlHandle[nosqlHandleDatabase];

            try {
            	if ($strPreserveClonedFiles === "true" || $strPreserveClonedFiles === "1") $strPreserveClonedFiles = true;
              if ($strBackupOriginalFiles === "true" || $strBackupOriginalFiles === "1") $strBackupOriginalFiles = true;

            	if (strlen($strPreserveClonedFiles) && !strlen($strBackupOriginalFiles)) {
                $dbResult = $dbHandleDatabase->repair($strPreserveClonedFiles);
            	} elseif(strlen($strBackupOriginalFiles)) {
            		$dbResult = $dbHandleDatabase->repair($strPreserveClonedFiles, $strBackupOriginalFiles);
            	} else {
            		$dbResult = $dbHandleDatabase->repair();
            	}

              if ($dbResult["ok"] != 1) {
                $errorMessage .= "<br />Cannot repair the $strDatabase <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Invalid database name $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Drop current database
     *
     * <code>
     * <znosql:dropdatabase use="mongoDB" />
     * </code>
     *
     * @param string use="mongoDB"
     */
    case "dropdatabase":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleDatabase]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $dbHandleDatabase = $nosqlHandle[nosqlHandleDatabase];

            try {
              $dbResult = $dbHandleDatabase->drop();

              if ($dbResult["ok"] == 1) {
	              $nosqlHandle[nosqlHandleDatabaseName] = null;
	              $nosqlHandle[nosqlHandleDatabase]     = null;
              } else {
              	$errorMessage .= "<br />Cannot drop the database $strDatabase <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot drop the database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Set a collection for current database
     *
     * <code>
     * <znosql:setcollection use="mongoDB" name="pubPaginas" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param string name="pubPaginas"
     */
    case "setcollection":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
        	$nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

        	if ($nosqlHandle[nosqlHandleDatabase]) {
	          $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
	          $dbHandleDatabase = $nosqlHandle[nosqlHandleDatabase];

	          try {
	            $dbHandleCollection = $dbHandleDatabase->selectCollection($strName);

	            $nosqlHandle[nosqlHandleCollectionName] = $strName;
	            $nosqlHandle[nosqlHandleCollection]     = $dbHandleCollection;

	          } catch(InvalidArgumentException $e) {
	            $errorMessage .= "<br />Invalid collection name $strName at database $strDatabase (".$e->getMessage().")";
	          }

	          $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

	        } else {
	          $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
	        }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
    	break;

    /*+
     * List all databases
     *
     * <code>
     * <znosql:listcollections use="mongoDB" id="mongoDBlist" var="databasesList" total="databasesTotalSize">
     *   <br /><znosql:field use="mongoDBlist" name="name" /> - <znosql:field use="mongoDBlist" name="sizeOnDisk" /> - <znosql:field use="mongoDBlist" name="empty" />
     * </znosql:listcollections>
     * </code>
     *
     * @param string use="mongoDB" A NoSQL handle
     * @param string id="mongoDBlist" Id with array of each line
     * @param string var="databasesList" Variable with array of each line
     * @param string total="databasesTotalSize" Variable with total size
     */
    case "listcollections":
      $strTotal = $arrParam["total"];

      $errorMessage .= ztagParamCheck($arrParam, "use,id");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleDatabase]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $dbHandleDatabase = $nosqlHandle[nosqlHandleDatabase];

	          $arrayCollections = $dbHandleDatabase->listCollections();

	          foreach ($arrayCollections as $dbHandleCollecion) {
	            if ($strId) {
	              $arrayTagId[$strId][ztagIdValue] = array("name" => $dbHandleCollecion->getName());
	              $arrayTagId[$strId][ztagIdType]  = idTypeValue;
	            }

	            if ($strVar) {
	              $arrayTagId["$".$strVar][ztagIdValue] = array("name" => $dbHandleCollecion->getName());
	              $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
	            }

	            $strResult = ztagRun($strContent, 0, $arrayTagId);

	            $arrayTag[$tagId][ztagResult] .= $strResult;
	          }

	        } else {
	          $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
	        }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
    	break;

    /*+
     * Drop a collection for current database
     *
     * <code>
     * <znosql:dropcollection use="mongoDB" />
     * </code>
     *
     * @param string use="mongoDB"
     */
    case "dropcollection":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
          	$strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            try {
              $dbResult = $dbHandleCollection->drop();

              if ($dbResult["ok"] == 1) {
	              $nosqlHandle[nosqlHandleCollectionName] = null;
	              $nosqlHandle[nosqlHandleCollection]     = null;
              } else {
                $errorMessage .= "<br />Cannot drop the collection $strCollection at database $strDatabase  <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot drop the collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * Create a collection for current database
     *
     * </code>
     * <znosql:createcollection use="mongoDB" name="pubPaginas" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param string name="pubPaginas" The name of the collection.
     * @param boolean capped="true|false|1|0" If the collection should be a fixed size.
     * @param int  size="10000000" If the collection is fixed size, its size in bytes.
     * @param int max="1000" If the collection is fixed size, the maximum number of elements to store in the collection.
     */
    case "createcollection":
      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleDatabase]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $dbHandleDatabase = $nosqlHandle[nosqlHandleDatabase];

            try {
              $dbHandleCollection = $dbHandleDatabase->createCollection($strName);

              $nosqlHandle[nosqlHandleCollectionName] = $strName;
              $nosqlHandle[nosqlHandleCollection]     = $dbHandleCollection;

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot create the collection $strName at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
    	break;

    /*+
     * Create a collection for current database
     *
     * <code>
     * <znosql:createcollection use="mongoDB" from="pubPaginas" to="pubPaginasOld"/>
     * </code>
     *
     * @param string use="mongoDB"
     * @param string from="pubPaginas"
     * @param string to="pubPaginasOld"
     */
    case "renamecollection":
    	$strFrom = $arrParam["from"];
    	$strTo   = $arrParam["to"];

      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          $dbHandle = $nosqlHandle[nosqlHandleId];

          if ($nosqlHandle[nosqlHandleDatabase]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];

			    	$dbResult = $dbHandle->selectDB('admin')->command(array('renameCollection' => "$strDatabase.$strFrom", 'to' => "$strDatabase.$strTo"));

			    	echo "<pre>".print_r($dbResult, 1)."</pre>";

	          } else {
	            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
	          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
      break;

    /*+
     * List all indexes for a Collection
     *
     * <code>
     * <znosql:listindexes use="mongoDB" collection="pubPaginas" />
     * </code>
     *
     * @param string use="mongoDB"
     * @param string collection="pubPaginas"
     */
   case "listindexes":
    	return $this->mongo->selectCollection($collection)->getIndexInfo();
      break;

    /*+
     * Ensure a index of current collection
     *
     * <code>
     * <znosql:ensureindex use="mongoDB" />
     * </code>
     *
     * @param string use="mongoDB"
     */
    case "ensureindex":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeNoSQL) {
        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $nosqlHandle = $arrayTagId[$strUse][ztagIdHandle];

          if ($nosqlHandle[nosqlHandleCollection]) {
            $strDatabase = $nosqlHandle[nosqlHandleDatabaseName];
            $strCollection = $nosqlHandle[nosqlHandleCollectionName];
            $dbHandleCollection= $nosqlHandle[nosqlHandleCollection];

            try {
              $dbResult = $dbHandleCollection->ensureIndex();

				      $unique = ($unique ? true : false); //signature requires a bool in both Mongo v. 1.0.1 and 1.2.0
				      // mongo->selectCollection($collection)->ensureIndex($indexes, $unique);

              if ($dbResult["ok"] == 1) {
                $nosqlHandle[nosqlHandleCollectionName] = null;
                $nosqlHandle[nosqlHandleCollection]     = null;
              } else {
                $errorMessage .= "<br />Cannot drop the collection $strCollection at database $strDatabase  <pre>".print_r($dbResult, 1)."</pre>";
              }

            } catch(InvalidArgumentException $e) {
              $errorMessage .= "<br />Cannot drop the collection $strCollection at database $strDatabase (".$e->getMessage().")";
            }

            $arrayTagId[$strUse][ztagIdHandle] = $nosqlHandle;

          } else {
            $errorMessage .= "<br />The $strUse NoSQL handler do not have a database defined!";
          }
        } else {
          $errorMessage .= "<br />The $strUse NoSQL handler is not Open!";
        }
      } else {
        $errorMessage .= "<br />The $strUse type is not a NoSQL!";
      }
    	break;

    /*+
     * Delete a index for a Collection
     *
     * <code>
     * <znosql:deleteindex use="mongoDB" collection="pubPaginas" index="pagNome"/>
     * </code>
     *
     * @param string use="mongoDB"
     * @param string collection="pubPaginas"
     * @param string index="pagNome"
     *
     */
   case "deleteindex":
      // mongo->selectCollection($collection)->deleteIndex($index);
    	break;

    /*+
     * Return the content of name field of current record
     *
     * </code>
     * <zdb:field use="mongoDBlist" name="name" />
     * </code>
     *
     * @param string use="mongoDBlist" - The Id Handle from current array
     * @param string name="name"
     */
    case "field":
      $strVar = $arrParam["var"];

      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      $fieldArray = $arrayTagId[$strUse][ztagIdValue];

      $fieldValue = $fieldArray[$strName];

      if (strlen($strTransform)) $fieldValue = ztagTransform($fieldValue, $strTransform);

      if (strlen($strVar)) {
        $arrayTagId["$".$strVar][ztagIdValue] = $fieldValue;
        $arrayTagId["$".$strVar][ztagIdType] = idTypeField;
      }

      $arrayTag[$tagId][ztagResult] = $fieldValue;
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

/**
 * Transform a formated zTag content to an Array
 *
 * <code>
 * $contentArray = znosql_lib_Content2Array($contentArray);
 * </code>
 *
 * @param array $contentArray
 * @param integer $noValue
 *
 * @return array of result
 *
 * @since 1.0
 */
function znosql_lib_Content2Array($contentArray, $noValue = 0) {
	$contentArray = ltrim($contentArray, "\r\n");
	$contentArray = rtrim($contentArray, "\r\n");

	$patternValue = "(\s*|\s*,\s*)(?P<param>\s*?\w+)";

	if (!$noValue) $patternValue = "(\s*|\s*,\s*)(?P<param>\s*?\w+\s*[=:]\s*)((?P<value>\"(?:\\\\\"|[^\"])*?\"|\w+|\'(?:\\\\'|[^'])*?')|(?P<array>{(?:\\\\\"|[^}])*?}))";

	preg_match_all("%$patternValue%", $contentArray, $Matches, PREG_OFFSET_CAPTURE);

	$contentArray = array();

	foreach ($Matches[0] as $key => $value) {
	  $paramKey   = $Matches["param"][$key][0];
	  $paramValue = $Matches["value"][$key][0];
	  $paramArray = $Matches["array"][$key][0];

	  if (substr($paramKey, 0, 1) === " ") $paramKey = substr($paramKey, 1, strlen($paramKey));
	  if (substr($paramKey, strlen($paramKey)-1, 1) === "=" || substr($paramKey, strlen($paramKey)-1, 1) === ":") $paramKey = substr($paramKey, 0, strlen($paramKey) - 1);

	  if (substr($paramValue, 0, 1) === "\"" || substr($paramValue, 0, 1) === "'") $paramValue = substr($paramValue, 1, strlen($paramValue) - 2);

	  if (strlen($paramValue)) {
	  	$paramValue = preg_replace("%\\\\(\"|')%", "$1", $paramValue);

      $arrayContent[$paramKey] = $paramValue;

	  } else {
	  	$arrayContent[] = $paramKey;
	  }

	  if (strlen($paramArray)) {
	    // @TODO Generate the array
	  }
	}

  return $arrayContent;
}