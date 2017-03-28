<?php
/**
 * dbpgsql
 *
 * Entrega funções de conexão com base de dados pgsql - Microsoft SQL
 *
 * @package db
 * @subpackage PostgreSQL
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

define("pgsqlVersion", 1.0, 1);

/**
 * Retorna a versão do driver
 *
 * <code>
 * echo dbVersion_pgsql
 * </code>
 *
 * @return float versão do driver
 *
 * @since Versão 1.0
 */
function dbVersion_pgsql() {
  return pgsqlVersion;
}

/**
 * Abra a conexão com o banco de dados pgsql
 *
 * <code>
 * $pgsqlHandle = dbOpen_pgsql("pgsql", "database", "user", "password");
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

function dbOpen_PGSQL(&$dbHandle) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  if(!function_exists("pg_connect")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br />extension=<b>php_pgsql.dll</b> is not loaded";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];
  $dbUser     = $dbHandle[dbHandleUser];
  $dbPassword = $dbHandle[dbHandlePassword];

  // @TODO Incluir tratamento para ver se o driver está carregado
  if (!$pgsqlConn = @pg_connect("host=$dbHost dbname=$dbDatabase user=$dbUser password=$dbPassword")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".pg_last_error()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $pgsqlConn;
}

/**
 * Fecha uma conexão previamente aberta com o banco de dados pgsql
 *
 * <code>
 * dbClose_PGSQL($pgsqlHandle);
 * </code>
 *
 * @param string $dbHandle handleId da conexão
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */

function dbClose_PGSQL(&$dbHandle) {
  // @TODO Incluir tratamento para ver se o driver está carregado
  if ($dbHandle["dbHandleId"]) {
    pg_close($dbHandle["dbHandleId"]);
  }
}

/**
 * Recupera uma linha de registro da conexão em questão do pgsql
 *
 * <code>
 * dbQuery_PGSQL($pgsqlSQL);
 * </code>
 *
 * @param handle $dbQuery handleId da query pgsql
 *
 * @see dbOpen_PGSQL()
 *
 * @since Versão 1.0
 */
function dbQuery_PGSQL(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];

  if (!$pgsqlQuery = @pg_query($dbHandleId, $dbSQL)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".pg_last_error()."]".$dbSQL;

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $pgsqlQuery;
}

/**
 * Recupera uma linha de registro da conexão em questão do pgsql
 *
 * <code>
 * dbFetch_PGSQL($pgsqlQuery);
 * </code>
 *
 * @param handle $dbQuery handleId da query pgsql
 *
 * @see dbOpen_PGSQL()
 *
 * @since Versão 1.0
 */
function dbFetch_PGSQL(&$dbHandle, $dbResultType=dbFetchBOTH) {
  $dbHandleQuery = $dbHandle[dbHandleQuery];

  $dbResultType .= "pgsql_";

  if (defined($dbResultType)) {
    $dbResultType = constant($dbResultType);
  } else {
    $dbResultType = pgsql_ASSOC;
  }

  $pgsqlFetch = pg_fetch_array($dbHandleQuery, $dbResultType);

  return $pgsqlFetch;
}

?>