<?php

	/**
	 * B2DB Base class
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package B2DB
	 * @subpackage core
	 */

	/**
	 * B2DB Base class
	 *
	 * @package B2DB
	 * @subpackage core
	 */
	class BaseB2DB
	{
		static protected $_db_connection;
		static protected $_db_host, $_db_uname, $_db_pwd, $_db_name, $_db_type, $_db_port = null;
		static protected $_dsn = null;
		static protected $_tableprefix = '';
		static protected $_sqlhits;
		static protected $_sqltiming;
		static protected $_throwhtmlexception = false;
		static protected $_aliascnt = 0;
		static protected $_transaction_active = false;
		static protected $_tables = array();
		static protected $_debug_mode = true;
		static protected $_engine_classpath = null;
		static protected $_cache_dir = null;
		static protected $_cached_column_class_properties = array();
		static protected $_cached_foreign_classes = array();

		/**
		 * Loads a table and adds it to the B2DBObject stack
		 * 
		 * @param BaseB2DBtable $tbl_name
		 * 
		 * @return B2DBtable
		 */
		public static function loadNewTable(BaseB2DBtable $table)
		{
			self::$_tables[get_class($table)] = $table;
			return $table;
		}

		/**
		 * Enable or disable debug mode
		 *
		 * @param boolean $debug_mode
		 */
		public static function setDebugMode($debug_mode)
		{
			self::$_debug_mode = $debug_mode;
		}

		/**
		 * Return whether or not debug mode is enabled
		 *
		 * @return boolean
		 */
		public static function isDebugMode()
		{
			return self::$_debug_mode;
		}

		/**
		 * Add a table alias to alias counter
		 *
		 * @return integer
		 */
		public static function addAlias()
		{
			return self::$_aliascnt++;
		}

		/**
		 * Initialize B2DB and load related B2DB classes
		 *
		 * @param boolean $load_parameters[optional] whether to load connection parameters
		 */
		public static function initialize($bootstrap_location = null)
		{
			if (!defined('B2DB_BASEPATH'))
			{
				throw new B2DBException('The constant B2DB_BASEPATH must be defined. B2DB_BASEPATH should be the full system path to B2DB');
			}
			
			try
			{
				if ($bootstrap_location !== null)
				{
					if (file_exists($bootstrap_location))
					{
						require $bootstrap_location;
					}
				}
				
				if (self::getDBtype() != '')
				{
					$b2db_engine_path = '';
					if (file_exists(B2DB_BASEPATH . self::getDBtype() . '/classes/B2DB.class.php'))
					{
						$b2db_engine_path = B2DB_BASEPATH . self::getDBtype();
					}
					else
					{
						$b2db_engine_path = B2DB_BASEPATH . 'PDO';
					}
					
					self::$_engine_classpath = $b2db_engine_path . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
				}
			}
			catch (Exception $e)
			{
				throw $e;
			}
			
		}

		/**
		 * Return the classpath to the B2DB engine files for the currently selected
		 * db engine
		 *
		 * @return string
		 */
		public static function getEngineClassPath()
		{
			return self::$_engine_classpath;
		}

		/**
		 * Store connection parameters
		 *
		 * @param string $bootstrap_location Where to save the connection parameters
		 */
		public static function saveConnectionParameters($bootstrap_location)
		{
			$string = "<?php\n";
			$string .= "\t/**\n";
			$string .= "\t * B2DB sql parameters\n";
			$string .= "\t *\n";
			$string .= "\t * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>\n";
			$string .= "\t * @version 2.0\n";
			$string .= "\t * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)\n";
			$string .= "\t * @package B2DB\n";
			$string .= "\t * @subpackage core\n";
			$string .= "\t */\n";
			$string .= "\n";
			$string .= "\tself::setUname('".self::getUname()."');\n";
			$string .= "\tself::setPasswd('".self::getPasswd()."');\n";
			$string .= "\tself::setTablePrefix('".self::getTablePrefix()."');\n";
			$string .= "\n";
			$string .= "\tself::setDSN('".self::getDSN()."');\n";
			$string .= "\n";
			try
			{
				if (file_put_contents($bootstrap_location, $string) === false)
				{
					throw new B2DBException('Could not save the database connection details');
				}
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		
		/**
		 * Returns the B2DBTable
		 *
		 * @param BaseB2DBTable $tbl_name
		 * 
		 * @return BaseB2DBTable
		 */
		public static function getTable($tbl_name)
		{
			if (!isset(self::$_tables[$tbl_name]))
			{
				try
				{
					if (!class_exists($tbl_name))
					{
						throw new B2DBException("Class $tbl_name does not exist, cannot load it");
					}
					self::loadNewTable(new $tbl_name());
				}
				catch (Exception $e)
				{
					throw $e;
				}
			}
			if (!isset(self::$_tables[$tbl_name]))
			{
				throw new B2DBException('Table ' . $tbl_name . ' is not loaded');
			}
			return self::$_tables[$tbl_name];
		}

		/**
		 * Return all tables registered
		 *
		 * @return array
		 */
		public static function getTables()
		{
			return self::$_tables;
		}

		/**
		 * Set tables
		 *
		 * @param array $tables
		 */
		public static function setTables($tables)
		{
			self::$_tables = $tables;
		}

		/**
		 * Register a new SQL call
		 */
		public static function sqlHit($sql, $values, $time)
		{
			self::$_sqlhits[] = array('sql' => $sql, 'values' => $values, 'time' => $time);
			self::$_sqltiming += $time;
		}

		/**
		 * Get number of SQL calls
		 *
		 * @return integer
		 */
		public static function getSQLHits()
		{
			return self::$_sqlhits;
		}
		
		public static function getSQLCount()
		{
			return count(self::$_sqlhits) +1;
		}
		
		public static function getSQLTiming()
		{
			return self::$_sqltiming;
		}

		/**
		 * Return the database connection object
		 * 
		 * @return PDO
		 */
		public static function getDBlink()
		{
			return self::$_db_connection;
		}

		/**
		 * Set the DSN
		 *
		 * @param string $dsn
		 */
		public static function setDSN($dsn)
		{
			$dsn_details = parse_url($dsn);
			if (!array_key_exists('scheme', $dsn_details))
			{
				throw new B2DBException('This does not look like a valid DSN - cannot read the database type');
			}
			try
			{
				self::setDBtype($dsn_details['scheme']);
				$dsn_details = explode(';', $dsn_details['path']);
				foreach ($dsn_details as $dsn_detail)
				{
					$detail_info = explode('=', $dsn_detail);
					if (count($detail_info) != 2)
					{
						throw new B2DBException('This does not look like a valid DSN - cannot read the connection details');
					}
					switch ($detail_info[0])
					{
						case 'host':
							self::setHost($detail_info[1]);
							break;
						case 'port':
							self::setPort($detail_info[1]);
							break;
						case 'dbname':
							self::setDBname($detail_info[1]);
							break;
					}
				}
			}
			catch (Exception $e)
			{
				throw $e;
			}
			self::$_dsn = $dsn;
		}

		/**
		 * Generate the DSN when needed
		 */
		protected static function _generateDSN()
		{
			$dsn = self::getDBtype() . ":host=" . self::getHost();
			if (self::getPort())
			{
				$dsn .= ';port=' . self::getPort();
			}
			$dsn .= ';dbname='.self::getDBname();
			self::$_dsn = $dsn;
		}

		/**
		 * Return current DSN
		 *
		 * @return string
		 */
		public static function getDSN()
		{
			if (self::$_dsn === null)
			{
				self::_generateDSN();
			}
			return self::$_dsn;
		}

		/**
		 * Set the database host
		 *
		 * @param string $host
		 */
		public static function setHost($host)
		{
			self::$_db_host = $host;
		}

		/**
		 * Return the database host
		 *
		 * @return string
		 */
		public static function getHost()
		{
			return self::$_db_host;
		}

		/**
		 * Return the database port
		 *
		 * @return integer
		 */
		public static function getPort()
		{
			return self::$_db_port;
		}

		/**
		 * Set the database port
		 * 
		 * @param integer $port 
		 */
		public static function setPort($port)
		{
			self::$_db_port = $port;
		}

		/**
		 * Set database username
		 *
		 * @param string $uname
		 */
		public static function setUname($uname)
		{
			self::$_db_uname = $uname;
		}

		/**
		 * Get database username
		 *
		 * @return string
		 */
		public static function getUname()
		{
			return self::$_db_uname;
		}

		/**
		 * Set the database table prefix
		 *
		 * @param string $prefix
		 */
		public static function setTablePrefix($prefix)
		{
			self::$_tableprefix = $prefix;
		}

		/**
		 * Get the database table prefix
		 *
		 * @return string
		 */
		public static function getTablePrefix()
		{
			return self::$_tableprefix;
		}

		/**
		 * Set the database password
		 *
		 * @param string $upwd
		 */
		public static function setPasswd($upwd)
		{
			self::$_db_pwd = $upwd;
		}

		/**
		 * Return the database password
		 *
		 * @return string
		 */
		public static function getPasswd()
		{
			return self::$_db_pwd;
		}

		/**
		 * Set the database name
		 *
		 * @param string $dbname
		 */
		public static function setDBname($dbname)
		{
			self::$_db_name = $dbname;
			self::$_dsn = null;
		}

		/**
		 * Get the database name
		 *
		 * @return string
		 */
		public static function getDBname()
		{
			return self::$_db_name;
		}

		/**
		 * Set the database type
		 *
		 * @param string $dbtype
		 */
		public static function setDBtype($dbtype)
		{
			if (self::hasDBEngine($dbtype) == false)
			{
				throw new B2DBException('The selected database is not supported: "' . $dbtype . '".');
			}
			self::$_db_type = $dbtype;
		}

		/**
		 * Get the database type
		 *
		 * @return string
		 */
		public static function getDBtype()
		{
			if (!self::$_db_type && defined('B2DB_SQLTYPE'))
			{
				self::setDBtype(B2DB_SQLTYPE);
			}
			return self::$_db_type;
		}

		/**
		 * Connect to the database
		 */
		public static function doConnect()
		{
			self::$_db_connection = b2db_sql_connect(self::$_db_host, self::$_db_uname, self::$_db_pwd) or die(b2db_sql_fatal_error(1));
		}

		/**
		 * Select/enable the selected database
		 *
		 * @param string $db_name[optional]
		 */
		public static function doSelectDB($db_name = null)
		{
			if ($db_name == null)
			{
				$db_name = self::$_db_name;
			}
			b2db_sql_select_db($db_name, self::$_db_connection) or die(b2db_sql_fatal_error(2));
		}

		/**
		 * Manually close the database connection
		 */
		public static function closeDBLink()
		{
			self::saveCache();
		}
		
		public static function getCacheDir()
		{
			if (self::$_cache_dir === null)
			{
				$cache_dir = (defined('B2DB_CACHEPATH')) ? realpath(B2DB_CACHEPATH) : realpath(B2DB_BASEPATH . 'cache/');
				self::$_cache_dir = $cache_dir;
			}
			return self::$_cache_dir;
		}
		
		public static function saveCache()
		{
			$cache_dir = self::getCacheDir();
			
			if ($cache_dir !== false)
			{
				foreach (self::$_cached_column_class_properties as $class => $properties)
				{
					if ((!file_exists($cache_dir . "/{$class}.column_class_properties.cache.php") && is_writable($cache_dir)) || is_writable($cache_dir . "/{$class}.column_class_properties.cache.php"))
					{
						$content = '<?php '."\n\n";
						$content .= "\tself::\$_cached_column_class_properties['{$class}'] = array();\n";
						foreach ($properties as $property => $value)
						{
							$content .= "\tself::\$_cached_column_class_properties['{$class}']['{$property}'] = \"{$value}\";\n";
						}
						$content .= "\n\n";
						file_put_contents($cache_dir . "/{$class}.column_class_properties.cache.php", $content);
					}
				}
				
				foreach (self::$_cached_foreign_classes as $class => $properties)
				{
					if ((!file_exists($cache_dir . "/{$class}.foreign_classes.cache.php") && is_writable($cache_dir)) || is_writable($cache_dir . "/{$class}.foreign_classes.cache.php"))
					{
						$content = '<?php '."\n\n";
						$content .= "\tself::\$_cached_foreign_classes['{$class}'] = array();\n";
						foreach ($properties as $property => $value)
						{
							$content .= "\tself::\$_cached_foreign_classes['{$class}']['{$property}'] = \"{$value}\";\n";
						}
						$content .= "\n\n";
						file_put_contents($cache_dir . "/{$class}.foreign_classes.cache.php", $content);
					}
				}				
			}
		}

		/**
		 * Toggle the transaction state
		 *
		 * @param boolean $state
		 */
		public static function setTransaction($state)
		{
			self::$_transaction_active = $state;
		}
		
		/**
		 * Starts a new transaction
		 */
		public static function startTransaction()
		{
			return new B2DBTransaction();
		}
		
		public static function isTransactionActive()
		{
			return (bool) self::$_transaction_active == BaseB2DBTransaction::DB_TRANSACTION_STARTED;
		}
		
		/**
		 * Displays a nicely formatted exception message
		 *  
		 * @param B2DBException $exception
		 */
		public static function fatalError(B2DBException $exception)
		{
			$ob_status = ob_get_status();
			if (!empty($ob_status) && $ob_status['status'] != PHP_OUTPUT_HANDLER_END)
			{
				ob_end_clean();
			}
			if (self::$_throwhtmlexception)
			{
				echo "
				<style>
				body { background-color: #DFDFDF; font-family: \"Droid Sans\", \"Trebuchet MS\", \"Liberation Sans\", \"Nimbus Sans L\", \"Luxi Sans\", Verdana, sans-serif; font-size: 13px; }
				h1 { margin: 5px 0 15px 0; font-size: 18px; }
				h2 { margin: 15px 0 0 0; font-size: 15px; }
				.rounded_box {background: transparent; margin:0px;}
				.rounded_box h4 { margin-bottom: 0px; margin-top: 7px; font-size: 14px; }
				.xtop, .xbottom {display:block; background:transparent; font-size:1px;}
				.xb1, .xb2, .xb3, .xb4 {display:block; overflow:hidden;}
				.xb1, .xb2, .xb3 {height:1px;}
				.xb2, .xb3, .xb4 {background:#F9F9F9; border-left:1px solid #CCC; border-right:1px solid #CCC;}
				.xb1 {margin:0 5px; background:#CCC;}
				.xb2 {margin:0 3px; border-width:0 2px;}
				.xb3 {margin:0 2px;}
				.xb4 {height:2px; margin:0 1px;}
				.xboxcontent {display:block; background:#F9F9F9; border:0 solid #CCC; border-width:0 1px; padding: 0 5px 0 5px;}
				.xboxcontent table td.description { padding: 3px 3px 3px 0;}
				.white .xb2, .white .xb3, .white .xb4 { background: #FFF; border-color: #CCC; }
				.white .xb1 { background: #CCC; }
				.white .xboxcontent { background: #FFF; border-color: #CCC; }
				</style>
				<div class=\"rounded_box white\" style=\"margin: 30px auto 0 auto; width: 600px;\">
					<b class=\"xtop\"><b class=\"xb1\"></b><b class=\"xb2\"></b><b class=\"xb3\"></b><b class=\"xb4\"></b></b>
					<div class=\"xboxcontent\" style=\"vertical-align: middle; padding: 10px 10px 10px 15px;\">
					<h1>An error occured in the B2DB database framework</h1>
					<h2>The following error occured:</h2>
					<i>".$exception->getMessage()."</i><br>
					";
					if ($exception->getSQL())
					{
						echo "<h2>SQL was:</h2>";
						echo $exception->getSQL();
						echo '<br>';
					}
					echo "<h2>Stack trace:</h2>
					<ul>";
					foreach ($exception->getTrace() as $trace_element)
					{
						echo '<li>';
						if (array_key_exists('class', $trace_element))
						{
							echo '<strong>'.$trace_element['class'].$trace_element['type'].$trace_element['function'].'()</strong><br>';
						}
						elseif (array_key_exists('function', $trace_element))
						{
							echo '<strong>'.$trace_element['function'].'()</strong><br>';
						}
						else
						{
							echo '<strong>unknown function</strong><br>';
						}
						if (array_key_exists('file', $trace_element))
						{
							echo '<span style="color: #55F;">'.$trace_element['file'].'</span>, line '.$trace_element['line'];
						}
						else
						{
							echo '<span style="color: #C95;">unknown file</span>';
						}	
						echo '</li>';
					}
					echo "
					</ul></div>
					<b class=\"xbottom\"><b class=\"xb4\"></b><b class=\"xb3\"></b><b class=\"xb2\"></b><b class=\"xb1\"></b></b>
				</div>
				";
			}
			else
			{
				echo "B2DB error\n";
				echo 'The following error occurred in ' . $e->getFile() . ' at line ' . $e->getLine() . ":\n";
				echo $e->getMessage() . "\n\n";
				echo "Trace:\n";
				echo $e->getTraceAsString() . "\n\n";
				echo self::$_db_connection->error . "\n\n";
				echo "For more information, refer to the B2DB manual.\n";
			}
		}

		/**
		 * Toggle HTML exception messages
		 *
		 * @param boolean $active
		 */
		public static function setHTMLException($active)
		{
			self::$_throwhtmlexception = $active;
		}

		/**
		 * Return whether exceptions are thrown and displayed as HTML
		 *
		 * @return boolean
		 */
		public static function throwExceptionAsHTML()
		{
			return self::$_throwhtmlexception;
		}

		/**
		 * Get available DB drivers
		 *
		 * @return array
		 */
		public static function getDBtypes()
		{
			$retarr = array();
			
			if (class_exists('PDO'))
			{
				$retarr['mysql'] = 'MySQL';
				$retarr['pgsql'] = 'PostgreSQL';
				/*$retarr['mssql'] = 'MsSQL (PDO)';
				$retarr['sybase'] = 'Sybase (PDO)';
				$retarr['dblib'] = 'DBLib (PDO)';
				$retarr['firebird'] = 'Firebird (PDO)';
				$retarr['ibm'] = 'IBM (PDO)';
				$retarr['oci'] = 'Oracle (PDO)';
				$retarr['sqlite'] = 'SQLite (PDO)';*/
			}
			else
			{
				throw new B2DBException('You need to have PHP PDO installed to be able to use this software');
			}
			
			return $retarr;
		}

		/**
		 * Whether a specific DB driver is supported
		 *
		 * @param string $driver
		 *
		 * @return boolean
		 */
		public static function hasDBEngine($driver)
		{
			return array_key_exists($driver, self::getDBtypes());
		}

		public static function loadCachedClassFiles($class)
		{
			$filename = self::getCacheDir() . "/{$class}.column_class_properties.cache.php";
			if (file_exists($filename))
			{
				require $filename;
			}
			$filename = self::getCacheDir() . "/{$class}.foreign_classes.cache.php";
			if (file_exists($filename))
			{
				require $filename;
			}
		}
		
		public static function addCachedColumnClassProperty($column, $class, $property)
		{
			if (!array_key_exists($class, self::$_cached_column_class_properties))
			{
				self::$_cached_column_class_properties[$class] = array();
			}
			self::$_cached_column_class_properties[$class][$column] = $property;
		}
		
		public static function getCachedColumnClassProperty($column, $class)
		{
			self::loadCachedClassFiles($class);
			if (array_key_exists($class, self::$_cached_column_class_properties))
			{
				if (array_key_exists($column, self::$_cached_column_class_properties[$class]))
				{
					return self::$_cached_column_class_properties[$class][$column];
				}
			}
			return null;
		}
		
		public static function addCachedClassPropertyForeignClass($class, $property, $foreign_class)
		{
			if (!array_key_exists($class, self::$_cached_foreign_classes))
			{
				self::$_cached_foreign_classes[$class] = array();
			}
			self::$_cached_foreign_classes[$class][$property] = $foreign_class;
		}
		
		public static function getCachedClassPropertyForeignClass($class, $property)
		{
			self::loadCachedClassFiles($class);
			if (array_key_exists($class, self::$_cached_foreign_classes))
			{
				if (array_key_exists($property, self::$_cached_foreign_classes[$class]))
				{
					return self::$_cached_foreign_classes[$class][$property];
				}
			}
			return null;
		}
		
	}
	