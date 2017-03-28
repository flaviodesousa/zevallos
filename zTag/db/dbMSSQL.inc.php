<?php
/**
 * dbMSSQL
 *
 * Entrega funções de conexão com base de dados MSSQL - Microsoft SQL
 *
 * @package db
 * @subpackage MSSQL
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

define("mssqlVersion", 1.0, 1);

/**
 * Retorna a versão do driver
 *
 * <code>
 * echo dbVersion_MSSQL
 * </code>
 *
 * @return float versão do driver
 *
 * @since Versão 1.0
 */
function dbVersion_MSSQL() {
  return mssqlVersion;
}

/**
 * Abra a conexão com o banco de dados MSSQL
 *
 * <code>
 * $mssqlHandle = dbOpen_MSSQL("MSSQL", "database", "user", "password");
 * </code>
 *
 * @param string $dbHost string de conexão com o banco de dados
 * @param string $dbDatabase[optional] string database utilizado
 * @param string $dbUser[optional] nome do usuário
 * @param string $dbPassword[optional] senha do usuário
 *
 * @return array com o handleId e o nome do driver
 *
 * @since Versão 1.0
 */

function dbOpen_MSSQL(&$dbHandle) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  if(!function_exists("mssql_connect")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br />extension=<b>php_mssql.dll</b> is not loaded";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];
  $dbUser     = $dbHandle[dbHandleUser];
  $dbPassword = $dbHandle[dbHandlePassword];

  // @TODO Incluir tratamento para ver se o driver está carregado
  if (!$mssqlConn = @mssql_connect($dbHost, $dbUser, $dbPassword)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".mssql_get_last_message()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  if (!@mssql_select_db($dbDatabase, $mssqlConn)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - SelectDB</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".mssql_get_last_message()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $mssqlConn;
}

/**
 * Fecha uma conexão previamente aberta com o banco de dados MSSQL
 *
 * <code>
 * dbClose_MSSQL($mssqlHandle);
 * </code>
 *
 * @param string $dbHandle handleId da conexão
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */

function dbClose_MSSQL(&$dbHandle) {
  // @TODO Incluir tratamento para ver se o driver está carregado
  if ($dbHandle["dbHandleId"]) {
    mssql_close($dbHandle["dbHandleId"]);
  }
}

/**
 * Recupera uma linha de registro da conexão em questão do MSSQL
 *
 * <code>
 * dbQuery_MSSQL($MSSQLSQL);
 * </code>
 *
 * @param handle $dbQuery handleId da query MSSQL
 *
 * @see dbOpen_MSSQL()
 *
 * @since Versão 1.0
 */
function dbQuery_MSSQL(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];

  if (!$mssqlQuery = @mssql_query($dbSQL, $dbHandleId)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".mssql_get_last_message()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $mssqlQuery;
}

/**
 * Recupera uma linha de registro da conexão em questão do MSSQL
 *
 * <code>
 * dbFetch_MSSQL($MSSQLQuery);
 * </code>
 *
 * @param handle $dbQuery handleId da query MSSQL
 *
 * @see dbOpen_MSSQL()
 *
 * @since Versão 1.0
 */
function dbFetch_MSSQL(&$dbHandle, $dbResultType=dbFetchBOTH) {
  $dbHandleQuery = $dbHandle[dbHandleQuery];

	$dbResultType .= "MSSQL_";

	if (defined($dbResultType)) {
		$dbResultType = constant($dbResultType);
	} else {
    $dbResultType = MSSQL_ASSOC;
	}

  $mssqlFetch = mssql_fetch_array($dbHandleQuery, $dbResultType);

  return $mssqlFetch;
}

/**
 * Recupera linhas de registro da conexão em questão do MySQL
 *
 * <code>
 * dbExecute_MySQL($mysqlSQL);
 * </code>
 *
 * @param handle $dbExecute handleId da Execute MySQL
 *
 * @see dbOpen_MySQL()
 *
 * @since 1.0
 */
function dbExecute_MSSQL(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];

  if (!$mssqlExecute = @mssql_query($dbSQL, $dbHandleId)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Host</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".mssql_get_last_message()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $mssqlExecute;
}
?>