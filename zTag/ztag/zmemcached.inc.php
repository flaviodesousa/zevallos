<?php
/**
 * zMemCached
 *
 * Process zTags for MemCached service
 *
 * Windows:
 * http://abhinavsingh.googlecode.com/files/svn-trunk-Memcached.rar
 *
 * Linux
 * sudo apt-get install php5-memcache
 * Go to /etc/php5/conf.d/memcache.ini and uncomment the line ;extension=memcache.so to enable this module
 * sudo pecl install memcache
 * Go to php.ini file and add this line: extension=memcache.so
 * sudo apt-get install memcached
 * sudo /etc/init.d/memcached start
 * Restart Apache
 *
 * @package ztag
 * @subpackage memcached
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

define("zmemcachedVersion", 1.0, 1);
define("zmemcachedVersionSufix", "ALFA 0.1", 1);

define("memcachedHandleId",      1, 1);
define("memcachedHandleHost",    2, 1);
define("memcachedHandlePort",    3, 1);
define("memcachedHandleTimeout", 4, 1);

/**#@+
 * Define nosqlHandle state
 */
define("memcachedHandleStateOpen",   1, 1);
define("memcachedHandleStateClosed", 0, 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zmemcached_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zmemcached_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zmemcached_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zmemcached_version() {
  return zmemcachedVersion." ".zmemcachedVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zmemcached_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zmemcached_compare($version) {
  return zmemcachedVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zmemcached_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zmemcached_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId       = $arrParam["id"];

  $strHost     = $arrParam["host"];
  $strPort     = $arrParam["port"];

  $strUse      = $arrParam["use"];
  $strVar      = $arrParam["var"];

  $strKey      = $arrParam["key"];
  $strCompress = $arrParam["compress"];
  $strExpire   = $arrParam["expire"];
  $strTimeout  = $arrParam["timeout"];
  $strValue    = $arrParam["value"];

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = $arrayTag[$tagId][ztagContent];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
    /*+
     * Open memcached server connection
     *
     * <code>
     * <zmemcached:open id="memCached" host="localhost" port="11211" timeout="1" />
     * </code>
     *
     * @param string id="menCached" Id of an open connection
     * @param string host="localhost" Point to the host where memcached is listening for connections
     * @param int port="11211" Point to the port where memcached is listening for connections
     * @param int timeout="1" Value in seconds which will be used for connecting to the daemon
     */
    case "open":
      $errorMessage .= ztagParamCheck($arrParam, "id,host,port");

      if (extension_loaded('memcache')) {
        $mcObject = new Memcache;

        if (strlen($strTimeout)) {
          $mcObject->connect($strHost, $strPort, $strTimeout);
        } else {
          $mcObject->connect($strHost, $strPort);
        }

        if ($mcObject) {
          $mcHandle[memcachedHandleHost]    = $strHost;
          $mcHandle[memcachedHandlePort]    = $strPort;
          $mcHandle[memcachedHandleTimeout] = $strTimeout;

          $mcHandle[memcachedHandleId]      = $mcObject;

          $mcHandle[memcachedHandleState]   = dbHandleStateOpen;

          $arrayTagId[$strId][ztagIdHandle] = $mcHandle;
          $arrayTagId[$strId][ztagIdType]   = idTypeMemCached;
          $arrayTagId[$strId][ztagIdState]  = idStateOpened;
        } else {
          $errorMessage .= "<br />Could not connect to memcached server $strHost:$strPort!";
        }
      } else {
        $errorMessage .= "<br />The MemCache extension is not loaded!";
      }
      break;

    /*+
     * Close memcached server connection
     *
     * <code>
     * <zmemcached:close use="memCached" />
     * </code>
     *
     * @param string use="memCached" Id of a open connection
     */
    case "close":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $mcObject = $mcHandle[memcachedHandleId];

          $mcObject->close();

          $mcHandle[memcachedHandleId]       = $mcObject;

          $arrayTagId[$strUse][ztagIdHandle] = $mcHandle;
          $arrayTagId[$strUse][ztagIdState]  = idStateClosed;

        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Add a memcached server to connection pool
     *
     * <code>
     * <zmemcached:addserver use="memCached"host="localhost" port="11211" timeout="1" persistent="true|false|0|1" weight="10" retryinterval="10" status="true|false|0|1" />
     * </code>
     *
     * @param string id="menCached" Id of an open connection
     * @param string host="localhost" Point to the host where memcached is listening for connections
     * @param int port="11211" Point to the port where memcached is listening for connections
     * @param int timeout="1" Value in seconds which will be used for connecting to the daemon. Think twice before changing the default value of 1 second - you can lose all the advantages of caching if your connection is too slow.
     * @param boolean persistent="true|false|0|1" Controls the use of a persistent connection. Default to TRUE.
     * @param int weight="10" Number of buckets to create for this server which in turn control its probability of it being selected. The probability is relative to the total weight of all servers.
     * @param int retryinterval="10" Controls how often a failed server will be retried, the default value is 15 seconds. Setting this parameter to -1 disables automatic retry.
     * @param boolean status="true|false|0|1" Controls if the server should be flagged as online.
     */
    case "addserver":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $mcObject = $mcHandle[memcachedHandleId];

          $mcObject->addserver($strHost, $strPort);

          $mcHandle[memcachedHandleId]       = $mcObject;

          $arrayTagId[$strUse][ztagIdHandle] = $mcHandle;

        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Get Server status
     *
     * <code>
     * <zmemcached:getserverstatus use="memCached" host="localhost" port="11211" var="memcachedStatus" />
     * </code>
     *
     * @param string host="localhost" Point to the host where memcached is listening for connections
     * @param int port="11211" Point to the port where memcached is listening for connections
     */
    case "getserverstatus":
      $errorMessage .= ztagParamCheck($arrParam, "use,host,port,var");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $statusValue = $mcObject->getServerStatus($strHost, $strPort);

          if ($statusValue) {
            $arrayTagId["$".$strVar][ztagIdValue] = $statusValue;
            $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Get Server version
     *
     * <code>
     * <zmemcached:getversion use="memCached" var="versionVar" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string var="versionVar" Variable where data will be set
     */
    case "getversion":
      $errorMessage .= ztagParamCheck($arrParam, "use,var");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $mcObject = $mcHandle[memcachedHandleId];

          $versionValue = $mcObject->getVersion();

          if ($versionValue) {
            $arrayTagId["$".$strVar][ztagIdValue] = $versionValue;
            $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;

          } else {
            $errorMessage .= "<br />I cannot get the server version from Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Close memcached server connection
     *
     * Stats array
     * pid - Process id of this server process
     * uptime - Number of seconds this server has been running
     * time - Current UNIX time according to the server
     * version - Version string of this server
     * rusage_user - Accumulated user time for this process
     * rusage_system - Accumulated system time for this process
     * curr_items - Current number of items stored by the server
     * total_items - Total number of items stored by this server ever since it started
     * bytes - Current number of bytes used by this server to store items
     * curr_connections - Number of open connections
     * total_connections - Total number of connections opened since the server started running
     * connection_structures - Number of connection structures allocated by the server
     * cmd_get - Cumulative number of retrieval requests
     * cmd_set - Cumulative number of storage requests
     * get_hits - Number of keys that have been requested and found present
     * get_misses - Number of items that have been requested and not found
     * bytes_read - Total number of bytes read by this server from network
     * bytes_written - Total number of bytes sent by this server to network
     * limit_maxbytes - Number of bytes this server is allowed to use for storage.
     *
     * <code>
     * <zmemcached:getstats use="memCached" var="statsVar" type="reset" slabid="" limit="" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string type="reset" The type of statistics to fetch. Valid values are {reset, malloc, maps, cachedump, slabs, items, sizes}
     * @param string slabid="" Used in conjunction with type set to cachedump to identify the slab to dump from
     * @param int limit="" Used in conjunction with type set to cachedump to limit the number of entries to dump.
     */
    case "getstats":
      $strType   = $arrParam["type"];
      $strSlabId = $arrParam["slabid"];
      $strLimit  = $arrParam["limit"];

      $errorMessage .= ztagParamCheck($arrParam, "use,var");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          $mcObject = $mcHandle[memcachedHandleId];

          if (strlen($strType) && !strlen($strSlabId)) {
            $statsValue = $mcObject->getStats($strType);

          } elseif (strlen($strType) && strlen($strSlabId) && !strlen($strLimit)) {
            $statsValue = $mcObject->getStats($strType, $strSlabId);

          } elseif (strlen($strType) && strlen($strSlabId) && strlen($strLimit)) {
            $statsValue = $mcObject->getStats($strType, $strSlabId, $strLimit);

          } else {
            $statsValue = $mcObject->getStats();
          }

          if ($statsValue) {
            $arrayTagId["$".$strVar][ztagIdValue] = $statsValue;
            $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;

          } else {
            $errorMessage .= "<br />I cannot get stats from Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Get Server extended status
     *
     * <code>
     * <zmemcached:getextendedstats host="localhost" port="11211" var="memcachedStatus" />
     * </code>
     *
     * @param string host="localhost" Point to the host where memcached is listening for connections
     * @param int port="11211" Point to the port where memcached is listening for connections
     * @param string var="memcachedStatus"
     */
    case "getextendedstats":
      $memcache_obj->getExtendedStats();
      /*
        pid: Process id of this server process
        uptime: Number of seconds this server has been running
        time: Current UNIX time according to the server
        version: Version string of this server
        rusage_user: Accumulated user time for this process
        rusage_system: Accumulated system time for this process
        curr_items: Current number of items stored by the server
        total_items: Total number of items stored by this server ever since it started
        bytes: Current number of bytes used by this server to store items
        curr_connections: Number of open connections
        total_connections: Total number of connections opened since the server started running
        connection_structures: Number of connection structures allocated by the server
        cmd_get: Cumulative number of retrieval requests
        cmd_set: Cumulative number of storage requests
        get_hits: Number of keys that have been requested and found present
        get_misses: Number of items that have been requested and not found
        bytes_read: Total number of bytes read by this server from network
        bytes_written: Total number of bytes sent by this server to network
        limit_maxbytes: Number of bytes this server is allowed to use for storage.
       */
      break;

    /*+
     * Return a hash of your key
     *
     * <code>
     * <zmemcached:key key="My key value" var="versionVar" type="md5|base64" />
     * </code>
     *
     * @param string key="My key value"
     * @param string var="versionVar" Variable where data will be set
     * @param string type="md5|base64" Type of your key
     */
    case "key":
      $strType = $arrParam["type"];

      $errorMessage .= ztagParamCheck($arrParam, "key,var");

      switch ($strType) {
        case "md5":
          $keyValue = md5($strKey);
          break;

        default:
          $keyValue = base64_encode($strKey);
      }

      $arrayTagId["$".$strVar][ztagIdValue] = $keyValue;
      $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;
      break;

    /*+
     * Store data at the server
     *
     * <code>
     * <zmemcached:set use="memCached" key="unique Id for this" value="statsVar" compress="1" expire="0" />
     *
     * <zmemcached:set use="memCached" key="" compress="" expire="">
     *   Value to be Cached
     * </zmemcached:set>
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string key="unique Id for this" The key that will be associated with the item.
     * @param string value="$valueVar" The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param string compress="true|false|1|0" Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     * @param int expire="0" Expiration time of the item. If it's equal to zero, the item will never expire.
     */
    case "set":
      if (strlen($strContent)) {
        $strValue = $strContent;

        $arrParam["value"] = $strValue;
      }

      $errorMessage .= ztagParamCheck($arrParam, "use,key,value");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          // $resultset = serialize($resultset);

          if (strlen($strKey) && strlen($strValue) && !strlen($strCompress)) {
            $setValue = $mcObject->set($strKey, $strValue);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strCompress) && !strlen($strExpire)) {
            if ($strCompress === "true" || $strCompress === 1) $strCompress = MEMCACHE_COMPRESSED;

            $setValue = $mcObject->set($strKey, $strValue, $strCompress);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strCompress) && strlen($strExpire)) {
            $setValue = $mcObject->set($strKey, $strValue, $strCompress, $strExpire);

          }

          if (!$setValue) {
            $errorMessage .= "<br />I cannot set data lenght (".strlen($strValue).") with Key \"$strKey\" from to $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strUse of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strUse no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Retrieve item from the server
     *
     * <code>
     * <zmemcached:get use="memCached" key="unique Id for this" var="getVar" flags="" />
     *
     * <zmemcached:get use="memCached" var="getVar" flags="">
     *  keys{"Key one"
     *  , "Key two"
     *  , 'Another key'
     *  , 99}
     * </zmemcached:get>
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string key="unique Id for this" The key that will be associated with the item.
     * @param string var="$getVar" Variable where the data will be return
     * @param string flags="" Where the flag values that was written at Set
     */
    case "get":
      if (strlen($strContent)) $strKey = $strContent;

      if (strlen($strKey)) $strKey = ztagVars($strKey, $arrayTagId);

      $arrParam["key"] = $strKey;

      $errorMessage .= ztagParamCheck($arrParam, "use,key,var");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          if (strlen($strKey) && !strlen($strFlag)) {
            $getValue = $mcObject->get($strKey);

          } elseif (strlen($strKey) && strlen($strFlag)) {
            $getValue = $mcObject->get($strKey, $strFlag);

          }

          if (strlen($strVar) && strlen($getValue)) {
            $arrayTagId["$".$strVar][ztagIdValue] = $getValue;
            $arrayTagId["$".$strVar][ztagIdType]  = idTypeFVar;

          } else {
            $arrayTagId["$".$strVar] = array();
          }
        } else {
          $errorMessage .= "<br />The id $strUse of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strUse no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Retrieve item from the server
     *
     * <code>
     * <zmemcached:add use="memCached" key="unique Id for this" var="getVar" flags="" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string key="unique Id for this" The key that will be associated with the item.
     * @param string var="$getVar" Variable where the data will be return
     * @param string flags="" Where the flag values that was written at Set
     */
    case "add":
      if (strlen($strContent)) $strValue = $strContent;

      if (strlen($strValue)) $strValue = ztagVars($strValue, $arrayTagId);

      $arrParam["value"] = $strValue;

      $errorMessage .= ztagParamCheck($arrParam, "use,key,value");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          // $resultset = serialize($resultset);

          if (strlen($strKey) && strlen($strValue) && !strlen($strFlag)) {
            $addValue = $mcObject->add($strKey, $strValue);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strFlag) && !strlen($strExpire)) {
            if ($strFlag === "true" || $strFlag === 1) $strFlag = MEMCACHE_COMPRESSED;

            $addValue = $mcObject->add($strKey, $strValue, $strFlag);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strFlag) && strlen($strExpire)) {
            $addValue = $mcObject->add($strKey, $strValue, $strFlag, $strExpire);

          }

          if (!$addValue) {
            $errorMessage .= "<br />I cannot add data to Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Replace value of the existing item
     *
     * <code>
     * <zmemcached:replace use="memCached" key="unique Id for this" value="statsVar" compress="1" expire="0" />
     *
     * <zmemcached:replace use="memCached" key="" compress="1" expire="">
     *   Value to be replaced
     * </zmemcached:replace>
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param string key="unique Id for this" The key that will be associated with the item.
     * @param string value="$valueVar" The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param string compress="true|false|1|0" Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     * @param int expire="0" Expiration time of the item. If it's equal to zero, the item will never expire.
     */
    case "replace":
      if (strlen($strContent)) $strValue = $strContent;

      if (strlen($strValue)) $strValue = ztagVars($strValue, $arrayTagId);

      $arrParam["value"] = $strValue;

      $errorMessage .= ztagParamCheck($arrParam, "use,key,value");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          // $resultset = serialize($resultset);

          if (strlen($strKey) && strlen($strValue) && !strlen($strFlag)) {
            $setValue = $mcObject->replace($strKey, $strValue);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strFlag) && !strlen($strExpire)) {
            if ($strFlag === "true" || $strFlag === 1) $strFlag = MEMCACHE_COMPRESSED;

            $setValue = $mcObject->replace($strKey, $strValue, $strFlag);

          } elseif (strlen($strKey) && strlen($strValue) && strlen($strFlag) && strlen($strExpire)) {
            $setValue = $mcObject->replace($strKey, $strValue, $strFlag, $strExpire);

          }

          if (!$setValue) {
            $errorMessage .= "<br />I cannot replace data to Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Delete item from the server
     *
     * <code>
     * <zmemcached:delete use="memCached" key="unique Id for this" timeout="10" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param int timeout="0" Execution time of the item. If it's equal to zero, the item will be deleted right away whereas if you set it to 30, the item will be deleted in 30 seconds.
     */
    case "delete":
      $errorMessage .= ztagParamCheck($arrParam, "use,key");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          // $resultset = serialize($resultset);

          if (strlen($strKey) && !strlen($strTimeout)) {
            $setValue = $mcObject->delete($strKey);

          } elseif (strlen($strKey) && strlen($strTimeout)) {
            $setValue = $mcObject->delete($strKey, $strTimeout);

          }

          if (!$setValue) {
            $errorMessage .= "<br />I cannot delete data to Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Increment item's value
     *
     * <code>
     * <zmemcached:increment use="memCached" key="unique Id for this" value="1" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param int value="1" Value to be incremented
     */
    case "increment":
      $errorMessage .= ztagParamCheck($arrParam, "use,key");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          if (strlen($strKey) && !strlen($strValue)) {
            $setValue = $mcObject->increment($strKey);

          } elseif (strlen($strKey) && strlen($strValue)) {
            $setValue = $mcObject->increment($strKey, $strValue);

          }

          if (!$setValue) {
            $errorMessage .= "<br />I cannot increment data to Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Decrement item's value
     *
     * <code>
     * <zmemcached:decrement use="memCached" key="unique Id for this" value="1" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     * @param int value="1" Value to be incremented
     */
    case "decrement":
      $errorMessage .= ztagParamCheck($arrParam, "use,key");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          if (strlen($strKey) && !strlen($strValue)) {
            $setValue = $mcObject->decrement($strKey);

          } elseif (strlen($strKey) && strlen($strValue)) {
            $setValue = $mcObject->decrement($strKey, $strValue);

          }

          if (!$setValue) {
            $errorMessage .= "<br />I cannot decrement data to Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    /*+
     * Debug
     *
     * <code>
     * <zmemcached:debug use="memCached" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     */
    case "debug":
      for ($i = 0; $i < count($this->connections); $i++) {
        if ($this->connections[$i]->debug($on_off)) $result = true;
      }
      break;

    /*+
     * Flush all existing items at the server
     *
     * <code>
     * <zmemcached:flush use="memCached" />
     * </code>
     *
     * @param string use="memCached" Id of an open connection
     */
    case "flush":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTagId[$strUse][ztagIdType] === idTypeMemCached) {
        $mcHandle = $arrayTagId[$strUse][ztagIdHandle];

        $mcObject = $mcHandle[memcachedHandleId];

        $strHost = $mcHandle[memcachedHandleHost];
        $strPort = $mcHandle[memcachedHandlePort];

        if ($arrayTagId[$strUse][ztagIdState] === idStateOpened) {
          // $resultset = serialize($resultset);

          $setValue = $mcObject->flush();

          if (!$setValue) {
            $errorMessage .= "<br />I cannot flush the Id $strId of host $strHost:$strPort!";
          }
        } else {
          $errorMessage .= "<br />The id $strId of host $strHost:$strPort is not open!";
        }
      }  else {
        $errorMessage .= "<br />The id $strId no not refer to a Memcached handler!";
      }
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
