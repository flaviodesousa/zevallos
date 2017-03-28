<?php
/**
 * Biblioteca genérica para gestão de banco de dados
 *
 * Conjunto de funções para trabalho com diversos tipos de banco de dados.
 *
 * @package library
 * @subpackage db
 * @category Database
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

/**#@+
 * Define the driver name
 */

/**
 * OCI - Oracle driver
 */
define("dbOCI",      "OCI",      1);

/**
 * MSSQL - Microsoft SQL Driver
 */
define("dbMSSQL",    "MSSQL",    1);
define("dbMySQL",    "MySQL",    1);
define("dbPGSQL",    "PGSQL",    1);
define("dbSQLite",   "SQLite",   1);
define("dbFirebird", "Firebird", 1);
define("dbIBM",      "IBM",      1);
define("dbInformix", "Informix", 1);
define("db4D",       "4D",       1);
define("dbODBC",     "ODBC",     1);

/**#@+
 * Define the dbHandle structure
 */
define("dbHandleDriver",    0, 1);
define("dbHandleId",        1, 1);
define("dbHandlePrepare",   3, 1);
define("dbHandleHost",      4, 1);
define("dbHandleDatabase",  5, 1);
define("dbHandleUser",      6, 1);
define("dbHandlePassword",  7, 1);
define("dbHandleState",     8, 1);
define("dbHandleQuery",     9, 1);
define("dbHandleFetch",    10, 1);
define("dbHandleBOF",      11, 1);
define("dbHandleEOF",      12, 1);
define("dbHandleFilename", 13, 1);
define("dbHandleMode",     14, 1);
define("dbHandleParse",    15, 1);
define("dbHandleExecute",  16, 1);
define("dbHandleCharset",  17, 1);

/**#@+
 * Define dbHandle state
 */
define("dbHandleStateOpen", 1, 1);
define("dbHandleStateClosed", 0, 1);

/**#@+
 * Define the Fetch result
 */
define("dbFetchBOTH",  "BOTH",  1);
define("dbFetchASSOC", "ASSOC", 1);
define("dbFetchNUM",   "NUM",   1);

/**#@+
 * Lock modes
 */
define("dbLockNone",             0,   1);
define("dbLockOptimistic",       1,   1);
define("dbLockPessimisticRead",  2,   1);
define("dbLockPessimisticWrite", 3,   1);

// Data Source Name (DSN) - Samples
// oci:unidental=@oracleHost
// mssql:host=$hostname;dbname=$dbname
// mysql:host=hostname;dbname=defaultDbName
// pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
// sqlite:/opt/databases/mydb.sq3
// sqlite::memory:
// firebird:dbname=localhost:C:\path\to\database\MyDatabase.FDB
// ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=testdb;HOSTNAME=11.22.33.444;PORT=56789;PROTOCOL=TCPIP;
// informix:host=host.domain.com; service=9800;database=common_db; server=ids_server; protocol=onsoctcp;EnableScrollableCursors=1
// 4D:host=localhost;charset=UTF-8
// odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\\db.mdb;Uid=Admin

// OCI - ^(?P<driver>\w+):(?P<database>\w+)=(?P<host>.*?)$
// MSSQL - ^(?P<driver>\w+):host=(?P<host>.*?);dbname=(?P<database>.*?)$
// MySQL - ^(?P<driver>\w+):host=(?P<host>.*?);dbname=(?P<database>.*?)$
// PGSQL - ^(?P<driver>\w+):host=(?P<host>.*?);dbname=(?P<database>.*?);user=(?P<user>.*?);password=(?P<password>.*?)$

/**
 * Executa o Open do banco de dados de acordo com o driver carregado.
 *
 * <code>
 * $ociHandle   = dbOpen("OCI", "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=sg.unidental.com.br)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=xe)))", "user", "password");
 * $mssqlHandle = dbOpen("MSSQL", ");
 * </code>
 *
 * @param string $dbDriver nome do driver do banco de dados utilizado
 * @param string $dbDSN string de conexão com o banco de dados
 * @param string $dbUser[optional] nome do usuário
 * @param string $dbPassword[optional] senha do usuário
 *
 * @return array com o handleId e o nome do driver
 *
 * @since Versão 1.0
 */
function dbOpen($dbDriver, $dbHost, $dbDatabase="", $dbUser="", $dbPassword="", $dbCharset="") {
	$dbHandle = null;

	// @TODO Melhorar a validação do Driver
	if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

	if (($myFunction = ztagDriverFunction($dbDriver, "Open"))) {
		$dbHandle[dbHandleDriver]   = $dbDriver;

    if (strlen($dbHost))     $dbHandle[dbHandleHost]     = $dbHost;
    if (strlen($dbDatabase)) $dbHandle[dbHandleDatabase] = $dbDatabase;
    if (strlen($dbUser))     $dbHandle[dbHandleUser]     = $dbUser;
    if (strlen($dbPassword)) $dbHandle[dbHandlePassword] = $dbPassword;
    if (strlen($dbCharset)) $dbHandle[dbHandleCharset]   = $dbCharset;

		$dbHandle[dbHandleId] = $myFunction($dbHandle);

    $dbHandle[dbHandleState] = dbHandleStateOpen;
    // @TODO incluir mensagem de erro no caso de não ter a função escolhida
  }

	return $dbHandle;
}

/**
 * Executa o Open do banco de dados de acordo com o driver carregado.
 *
 * <code>
 * $sqliteHandle   = dbOpenFile("SQLLite", "/ZTag/SQLite.db");
 * </code>
 *
 * @param string $dbDriver nome do driver do banco de dados utilizado
 * @param string $strFilename nome do arquivo que será utilizado
 * @param string $strMode[optional] Modo de gravação do arquivo
 *
 * @return array com o handleId e o nome do driver
 *
 * @since Versão 1.0
 */
function dbOpenFile($dbDriver, $strFilename, $strMode=0666) {
  $dbHandle = null;

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Open"))) {
    $dbHandle[dbHandleDriver]   = $dbDriver;

    $dbHandle[dbHandleFilename] = $strFilename;
    $dbHandle[dbHandleMode]     = $strMode;

    $dbHandle[dbHandleId]       = $myFunction($dbHandle);

    $dbHandle[dbHandleState]    = dbHandleStateOpen;

  }

  return $dbHandle;
}
/**
 * Executa o Close do banco de dados de acordo com o driver carregado.
 *
 * <code>
 * dbOpen($ociHandle);
 * dbOpen($mssqlHandle);
 * </code>
 *
 * @param string $dbHandle handleId do banco de dados conectado
 *
 * @since Versão 1.0
 */
function dbClose(&$dbHandle) {
	$dbDriver   = $dbHandle[dbHandleDriver];

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Close"))) {
    $myFunction($dbHandle);

    $dbHandle[dbHandleState] = dbHandleStateClosed;
  }
}

/**
 * Processa uma consulta do Oracle
 *
 * <code>
 * $sql = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbQuery($sql);
 * </code>
 *
 * @param string $dbSQL Query que será executada
 *
 * @since Versão 1.0
 */
function dbQuery(&$dbHandle, $dbSQL) {
  $dbDriver  = $dbHandle[dbHandleDriver];

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Query"))) {
  	$dbHandle[dbHandleQuery] = $myFunction($dbHandle, $dbSQL);

  }
}

/**
 * Processa uma consulta do Oracle
 *
 * <code>
 * $sql = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbQuery($sql);
 * </code>
 *
 * @param string $dbSQL Query que será executada
 *
 * @since Versão 1.0
 */
function dbParse(&$dbHandle, $dbSQL) {
  $dbDriver  = $dbHandle[dbHandleDriver];

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Parse"))) {
    $dbHandle[dbHandleParse] = $myFunction($dbHandle, $dbSQL);

  }
}

/**
 * Processa uma consulta do Oracle
 *
 * <code>
 * $sql = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbFetch($dbHandle);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 * @param string query SQL que será utilizada
 *
 * @since Versão 1.0
 */
function dbFetch(&$dbHandle, $dbResultType=MSSQL_BOTH) {
  $dbDriver  = $dbHandle[dbHandleDriver];

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Fetch"))) {
  	$dbHandle[dbHandleEOF] = 0;

  	if (!$dbHandle[dbHandleFetch] = $myFunction($dbHandle, $dbResultType)) {
  		$dbHandle[dbHandleEOF] = 1;

  	} else if (!isset($dbHandle[dbHandleBOF])) {
      $dbHandle[dbHandleBOF] = 1;

    } else {
      $dbHandle[dbHandleBOF] = 0;

    }
  }

  return $dbHandle[dbHandleFetch];
}

/**
 * Retorna a flag informando se está no início do arquivo
 *
 * <code>
 * dbBOF($dbHandle);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 *
 * @return boolean 1 - se estiver no início do arquivo
 *
 * @since Versão 1.0
 */
function dbBOF($dbHandle) {
  return $dbHandle[dbHandleBOF];
}

/**
 * Retorna a flag informando se está no final do arquivo
 *
 * <code>
 * dbBOF($dbHandle);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 *
 * @return boolean 1 - se estiver no final do arquivo
 *
 * @since Versão 1.0
 */
function dbEOF($dbHandle) {
  return $dbHandle[dbHandleEOF];
}

/**
 * Prepara a consulta
 *
 * <code>
 * $ociQuery = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbPrepare($ociHandle, $ociQuery);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 * @param string query SQL que será utilizada
 *
 * @since Versão 1.0
 */
function dbPrepare(&$dbHandle, $dbQuery) {
  $dbDriver = $dbHandle[dbHandleDriver];

  if (ztagDriverLoad($dbDriver) && ($myFunction = ztagDriverFunction($dbDriver, "Prepare"))) {
    $dbHandleId = $dbHandle[dbHandleId];

    $dbHandle[dbHandlePrepare] = $myFunction($dbHandle, $dbQuery);
  }
}

/**
 * Vincula um parâmetro a uma variável
 *
 * <code>
 * $ociQuery = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbPrepare($ociHandle, $ociQuery);
 *
 * dbBindByName($ociHandle, ":P_NM_USUARIO", $P_NM_USUARIO);
 * dbBindByName($ociHandle, ":P_CD_PESSOA_USUARIO", $P_CD_PESSOA_USUARIO);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 * @param string parâmetro com : inicial que está na query
 * @param variable variável
 *
 * @since Versão 1.0
 */
function dbBindByName(&$dbHandle, $dbParam, $dbValue, $dbType=0) {
  $dbDriver  = $dbHandle[dbHandleDriver];
  $dbPrepare = $dbHandle[dbHandleDriver];

  if (ztagDriverLoad($dbDriver) && ($myFunction = ztagDriverFunction($dbDriver, "BindByName"))) {
    $dbHandle[dbHandlePrepare] = $myFunction($dbPrepare, $dbParam, $dbValue, $dbType);
    $dbHandle[dbHandleDriver]  = $dbPrepare;
  }
}

/**
 * Processa uma consulta do MSSQL
 *
 * <code>
 * $ociQuery = "SELECT *
 *  FROM TB_USUARIO_SISTEMA
 *  WHERE CD_PESSOA_USUARIO = :P_CD_PESSOA_USUARIO
 *  OR NM_USUARIO = :P_NM_USUARIO
 *  ORDER BY CD_PESSOA_USUARIO";
 *
 * dbExecute($ociHandle, $ociQuery);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 * @param string query SQL que será utilizada
 *
 * @since Versão 1.0
 */
function dbExecute(&$dbHandle, $dbSQL) {
  $dbDriver  = $dbHandle[dbHandleDriver];

  if ($dbDriverFile = ztagDriverLoad($dbDriver)) require_once($dbDriverFile);

  if (($myFunction = ztagDriverFunction($dbDriver, "Execute"))) {
    $dbHandle[dbHandleExecute] = $myFunction($dbHandle, $dbSQL);

  }
}

/**
 * Retorna a lista das tabelas
 *
 * <code>
 * dbGetTables($ociHandle);
 * </code>
 *
 * @param array $dbHandle handleId do banco de dados conectado
 * @param string query SQL que será utilizada
 *
 * @since Versão 1.0
 */
function dbGetTables(&$dbHandle, $dbParam, $dbValue, $dbType=0) {
  $dbDriver  = $dbHandle[dbHandleDriver];

  // SELECT TABLE_NAME FROM USER_TABLES

  if (ztagDriverLoad($dbDriver) && ($myFunction = ztagDriverFunction($dbDriver, "GetTables"))) {
    $dbHandle[dbHandlePrepare] = $myFunction($dbPrepare, $dbParam, $dbValue, $dbType);
  }
}