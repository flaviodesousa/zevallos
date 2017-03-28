<?php
/**
 * dbSQLite
 *
 * Driver SQLite - SQLite
 *
 * @package db
 * @subpackage SQLite
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

define("SQLiteVersion", 1.0, 1);

/**
 * Retorna a versão do driver
 *
 * <code>
 * echo dbVersion_SQLite
 * </code>
 *
 * @return float versão do driver
 *
 * @since Versão 1.0
 */
function dbVersion_SQLite() {
  return SQLiteVersion;
}

/**
 * Abra a conexão com o banco de dados SQLite
 *
 * <code>
 * $sqliteHandle = dbOpen_SQLite("sqlite", "database", "user", "password");
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
function dbOpen_SQLite(&$dbHandle) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  if(!function_exists("sqlite_open")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Open</b>:"
        ."<br />extension=<b>php_sqlite.dll</b> is not loaded";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  $dbFilename = $dbHandle[dbHandleFilename];
  $dbMode     = $dbHandle[dbHandleMode];

  if (substr($dbFilename, 0, 1)) $dbFilename = substr($dbFilename, 1, strlen($dbFilename));

  $dbFilename = SiteRootDir.$dbFilename;

  // @TODO Incluir tratamento para ver se o driver está carregado
  if (!$SQLiteConn = @sqlite_open($dbFilename, $dbMode, $dbError)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br /><b>Filename</b>: $dbFilename"
        ."<br /><b>Mode</b>: $dbMode"
        ."<br /><b>Message</b>: [$dbError]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $SQLiteConn;
}

/**
 * Fecha uma conexão previamente aberta com o banco de dados SQLite
 *
 * <code>
 * dbClose_SQLite($SQLiteHandle);
 * </code>
 *
 * @param string $dbHandle handleId da conexão
 *
 * @see dbOpen_SQLite()
 *
 * @since Versão 1.0
 */
function dbClose_SQLite(&$dbHandle) {
  // @TODO Incluir tratamento para ver se o driver está carregado
  if ($dbHandle["dbHandleId"]) {
    sqlite_close($dbHandle["dbHandleId"]);
  }
}

/**
 * Recupera uma linha de registro da conexão em questão do SQLite
 *
 * <code>
 * dbQuery_SQLite($SQLiteSQL);
 * </code>
 *
 * @param handle $dbQuery handleId da query SQLite
 *
 * @see dbOpen_SQLite()
 *
 * @since Versão 1.0
 */
function dbQuery_SQLite(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbFilename = $dbHandle[dbHandleFilename];
  $dbMode     = $dbHandle[dbHandleMode];

  if (!$SQLiteQuery = @sqlite_query($dbHandleId, $dbSQL)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Filename</b>: $dbFilename"
        ."<br /><b>Mode</b>: $dbMode"
        ."<br /><b>Message</b>: [".sqlite_last_error($dbHandleId)."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $SQLiteQuery;
}

/**
 * Recupera uma linha de registro da conexão em questão do SQLite
 *
 * <code>
 * dbFetch_SQLite($SQLiteQuery);
 * </code>
 *
 * @param handle $dbQuery handleId da query SQLite
 *
 * @see dbOpen_SQLite()
 *
 * @since Versão 1.0
 */
function dbFetch_SQLite(&$dbHandle, $dbResultType=dbFetchBOTH) {
  $dbHandleQuery = $dbHandle[dbHandleQuery];

  $dbResultType .= "SQLITE_";

  if (defined($dbResultType)) {
    $dbResultType = constant($dbResultType);
  } else {
    $dbResultType = SQLITE_ASSOC;
  }

  $SQLiteFetch = sqlite_fetch_array($dbHandleQuery, $dbResultType);

  return $SQLiteFetch;
}

?>