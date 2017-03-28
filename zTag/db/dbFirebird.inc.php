<?php
/**
 * dbFirebird
 *
 * Deliver all function from connect and manage data with Firebird database
 *
 * @package db
 * @subpackage firebird
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

define("firebirdVersion", 1.0, 1);

/**
 * Retorna a versão do driver
 *
 * <code>
 * echo dbVersion_firebird
 * </code>
 *
 * @return float versão do driver
 *
 * @since Versão 1.0
 */
function dbVersion_firebird() {
  return firebirdVersion;
}

/**
 * Abra a conexão com o banco de dados firebird
 *
 * <code>
 * $firebirdHandle = dbOpen_firebird("firebird", "database", "user", "password");
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

function dbOpen_firebird(&$dbHandle) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  if(!function_exists("ibase_connect")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br />extension=<b>php_interbase_firebird.dll</b> is not loaded";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  $dbDatabase = $dbHandle[dbHandleDatabase];
  $dbUser     = $dbHandle[dbHandleUser];
  $dbPassword = $dbHandle[dbHandlePassword];

  // @TODO Incluir tratamento para ver se o driver está carregado
  if (!$firebirdConn = @ibase_connect($dbDatabase, $dbUser, $dbPassword)) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".ibase_errmsg()."]";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $firebirdConn;
}

/**
 * Fecha uma conexão previamente aberta com o banco de dados firebird
 *
 * <code>
 * dbClose_firebird($firebirdHandle);
 * </code>
 *
 * @param string $dbHandle handleId da conexão
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */

function dbClose_firebird(&$dbHandle) {
  // @TODO Incluir tratamento para ver se o driver está carregado
  if ($dbHandle["dbHandleId"]) {
    ibase_close($dbHandle["dbHandleId"]);
  }
}

/**
 * Recupera uma linha de registro da conexão em questão do firebird
 *
 * <code>
 * dbQuery_firebird($firebirdSQL);
 * </code>
 *
 * @param handle $dbQuery handleId da query firebird
 *
 * @see dbOpen_firebird()
 *
 * @since Versão 1.0
 */
function dbQuery_firebird(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbDatabase = $dbHandle[dbHandleDatabase];

  preg_match_all("%^\s*(?P<sql>.*?)(?P<r>\r)?(?P<n>\n)%sm", $dbSQL."\r\n", $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  $dbSQL = "";

  foreach ($Matches[0] as $key => $value) {
    $dbSQL .= $Matches["sql"][$key][0]."\r\n";
  }

  if (!$firebirdQuery = @ibase_query($dbHandleId, $dbSQL)) {
    $e = ibase_errmsg();

    preg_match_all("%^(?P<origem>[^=]+)=\s*(?P<error>-?\d+)\s*(?P<message>.*?)\s*(-|at)\s*line\s*(?P<line>\d+),\s*column\s*(?P<column>\d+)%i", $e, $Matches, PREG_OFFSET_CAPTURE);

    $errorOrigem  = $Matches["origem"][0][0];
    $errorError   = $Matches["error"][0][0];
    $errorMessage = $Matches["message"][0][0];
    $errorLine    = $Matches["line"][0][0];
    $errorColumn  = $Matches["column"][0][0];

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".$e."]";

    preg_match_all("%^(?P<sql>.*?)(?P<r>\r)?(?P<n>\n)%sm", $dbSQL, $Matches, PREG_OFFSET_CAPTURE);

    if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

    $i = 2;

    $errorColumn -= 8;

    foreach ($Matches[0] as $key => $value) {
      $i++;

      $sql = $Matches["sql"][$key][0];

      echo "<br />";

      if ($i != $errorLine) {
        echo htmlentities($sql);
      } else {
        echo htmlentities(substr($sql, 0, $errorColumn))."<font color=\"red\"\><b>Erro ($errorError - $errorMessage)</b> --&gt; </font>".htmlentities(substr($sql, $errorColumn, 99999));
      }
    }

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  // echo "<hr />".debugBackTrace();
  return $firebirdQuery;
}

/**
 * Recupera uma linha de registro da conexão em questão do firebird
 *
 * <code>
 * dbFetch_firebird($firebirdQuery);
 * </code>
 *
 * @param handle $dbQuery handleId da query firebird
 *
 * @see dbOpen_firebird()
 *
 * @since Versão 1.0
 */
function dbFetch_firebird(&$dbHandle, $dbResultType=dbFetchBOTH) {
  $dbHandleQuery = $dbHandle[dbHandleQuery];

  $dbResultType .= "firebird_";

  if (defined($dbResultType)) {
    $dbResultType = constant($dbResultType);
  } else {
    $dbResultType = firebird_ASSOC;
  }

  if ($dbResultType === dbFetchNUM) {
    $firebirdFetch = ibase_fetch_row($dbHandleQuery);
  } else {
    $firebirdFetch = ibase_fetch_assoc($dbHandleQuery);
  }

  // echo "<hr />".debugBackTrace();
  // echo "<br />ibase_fetch_assoc(A)";
  // echo "<br /><pre>[".htmlentities(print_r($dbHandleQuery, 1))."]</pre>";
  // echo "<br /><pre>[".htmlentities(print_r($firebirdFetch, 1))."]</pre>";
  // ob_flush(); flush();

  return $firebirdFetch;
}

/**
 * Execute a query on an InterBase database
 *
 * <code>
 * dbExecute_Firebird($firebirdSQL);
 * </code>
 *
 * @param handle $dbExecute handleId da Execute Firebird
 *
 * @see dbOpen_Firebird()
 *
 * @since 1.0
 */
function dbExecute_firebird(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  $dbHost     = $dbHandle[dbHandleHost];
  $dbDatabase = $dbHandle[dbHandleDatabase];

  preg_match_all("%^\s*(?P<sql>.*?)(?P<r>\r)?(?P<n>\n)%sm", $dbSQL."\r\n", $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  $dbSQL = "";

  foreach ($Matches[0] as $key => $value) {
    $dbSQL .= $Matches["sql"][$key][0]."\r\n";
  }

  if (!$firebirdExecute = @ibase_query($dbSQL, $dbHandleId)) {
    $e = ibase_errmsg();

    preg_match_all("%^(?P<origem>[^=]+)=\s*(?P<error>-?\d+)\s*(?P<message>.*?)\s*(-|at)\s*line\s*(?P<line>\d+),\s*column\s*(?P<column>\d+)%i", $e, $Matches, PREG_OFFSET_CAPTURE);

    $errorOrigem  = $Matches["origem"][0][0];
    $errorError   = $Matches["error"][0][0];
    $errorMessage = $Matches["message"][0][0];
    $errorLine    = $Matches["line"][0][0];
    $errorColumn  = $Matches["column"][0][0];

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction()</b>:"
        ."<br /><b>Database</b>: ".$dbDatabase
        ."<br /><b>Message</b>: [".$e."]";

    preg_match_all("%^(?P<sql>.*?)(?P<r>\r)?(?P<n>\n)%sm", $dbSQL, $Matches, PREG_OFFSET_CAPTURE);

    if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

    $i = 0;

    $errorColumn -= 1;

    foreach ($Matches[0] as $key => $value) {
      $i++;

      $sql = $Matches["sql"][$key][0];

      echo "<br />";

      if ($i != $errorLine) {
        echo htmlentities($sql);
      } else {
        echo htmlentities(substr($sql, 0, $errorColumn))."<font color=\"red\"\><b>Erro ($errorError - $errorMessage)</b> --&gt; </font>".htmlentities(substr($sql, $errorColumn, 99999));
      }
    }

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $firebirdExecute;
}

?>