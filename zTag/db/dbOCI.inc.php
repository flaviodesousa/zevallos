<?php
/**
 * dbOCI
 *
 * Entrega funções de conexão com base de dados OCI - Oracle
 *
 * @package db
 * @subpackage OCI
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

define("ociVersion", 1.0, 1);

/**
 * Retorna a versão do driver
 *
 * <code>
 * echo dbVersion_OCI
 * </code>
 *
 * @return float versão do driver
 *
 * @since Versão 1.0
 */
function dbVersion_OCI() {
	return ociVersion;
}

/**
 * Abra a conexão com o banco de dados Oracle
 *
 * <code>
 * $ociHandle = dbOpen_OCI("(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.0.84)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=xe)))", "", "user", "password");
 * </code>
 *
 * @param string $dbHost string de conexão com o banco de dados
 * @param string $dbDatabase[optional] database (não usado no Oracle)
 * @param string $dbUser[optional] nome do usuário
 * @param string $dbPassword[optional] senha do usuário
 *
 * @return array com o handleId e o nome do driver
 *
 * @since Versão 1.0
 */
function dbOpen_OCI(&$dbHandle) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

	$dbDriver   = $dbHandle[dbHandleDriver];

  if(!function_exists("oci_connect")) {
    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br />extension=<b>php_oci8.dll</b> is not loaded";

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  $dbHost     = $dbHandle[dbHandleHost];
	$dbDatabase = $dbHandle[dbHandleDatabase];
	$dbUser     = $dbHandle[dbHandleUser];
	$dbPassword = $dbHandle[dbHandlePassword];

  // @TODO Incluir tratamento para ver se o driver está carregado
  if (!@$oracleConn = oci_connect($dbUser, $dbPassword, $dbHost)) {
    $e = oci_error();

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Connect</b>:"
        ."<br /><b>Conexão</b>: ".$dbHost
        ."<br /><b>Código</b>: ".$e["code"]
        ."<br /><b>Mensagem</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Offset</b>: ".($e["offset"] + 1);

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $oracleConn;
}

/**
 * Fecha uma conexão previamente aberta com o banco de dados Oracle
 *
 * <code>
 * dbClose_OCI($ociHandle);
 * </code>
 *
 * @param string $dbHandle handleId da conexão
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */
function dbClose_OCI(&$dbHandle) {
  // @TODO Incluir tratamento para ver se o driver está carregado
  if ($dbHandle["dbHandleId"]) {
    oci_close($dbHandle["dbHandleId"]);
  }
}

/**
 * Recupera uma linha de registro da conexão em questão do OCI
 *
 * <code>
 * dbQuery_OCI($OCISQL);
 * </code>
 *
 * @param handle $dbQuery handleId da query OCI
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */
function dbQuery_OCI(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  if (!$ociQuery = @oci_parse($dbHandleId, $dbSQL)) {
    $e = oci_error($dbHandleId);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Parse</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Code</b>: ".$e["code"]
        ."<br /><b>Message</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Command</b>: ".oci_statement_type($dbHandleId);

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  if (!@oci_execute($ociQuery)) {
    $e = oci_error($ociQuery);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Execute</b>:"
        ."<br /><b>Conexão</b>: ".$dbHost
        ."<br /><b>Código</b>: ".$e["code"]
        ."<br /><b>Mensagem</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Offset</b>: ".($e["offset"] + 1)
        ."<hr />".htmlentities(substr($e["sqltext"], 0, $e["offset"]))."<font color=\"red\"\><b>Erro (".$e["code"].")</b> --&gt; </font>".htmlentities(substr($e["sqltext"], $e["offset"], 99999));

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  // @TODO Try to put the execute to other final functions... so that we can use the Query... may be one time...
  if (!@oci_execute($ociQuery)) {
    $e = oci_error($ociQuery);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Execute</b>:"
        ."<br /><b>Conexão</b>: ".$dbHost
        ."<br /><b>Código</b>: ".$e["code"]
        ."<br /><b>Mensagem</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Offset</b>: ".($e["offset"] + 1)
        ."<hr />".htmlentities(substr($e["sqltext"], 0, $e["offset"]))."<font color=\"red\"\><b>Erro (".$e["code"].")</b> --&gt; </font>".htmlentities(substr($e["sqltext"], $e["offset"], 99999));

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $ociQuery;
}

/**
 * Recupera uma linha de registro da conexão em questão do OCI
 *
 * <code>
 * dbFetch_OCI($OCIQuery);
 * </code>
 *
 * @param handle $dbQuery handleId da query OCI
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */
function dbFetch_OCI(&$dbHandle, $dbResultType=dbFetchBOTH) {
  $dbHandleQuery = $dbHandle[dbHandleQuery];

  $dbResultType .= "OCI_";

  if (defined($dbResultType)) {
    $dbResultType = constant($dbResultType);
  } else {
    $dbResultType = OCI_ASSOC + OCI_RETURN_NULLS;
  }

  $ociFetch = oci_fetch_array($dbHandleQuery, $dbResultType);

  return $ociFetch;
}

/**
 * Processa a query no Oracle
 *
 * <code>
 dbParse_OCI(&$dbHandle, $dbSQL)
 * </code>
 *
 * @param handle $dbHandle HandleId da conexão
 *
 * @see dbOpen_OCI()
 *
 * @since Versão 1.0
 */
function dbParse_OCI(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  if (!$ociParse = @oci_parse($dbHandleId, $dbSQL)) {
    $e = oci_error($dbHandleId);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Parse</b>:"
        ."<br /><b>Connection</b>: ".$dbHost
        ."<br /><b>Code</b>: ".$e["code"]
        ."<br /><b>Message</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Command</b>: ".oci_statement_type($dbHandleId);

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }
  return $ociParse;

}
/**
 * Recupera uma linha de registro da conexão em questão do OCI
 *
 * <code>
 * dbExecute_OCI($dbHandle);
 * </code>
 *
 * @param handle $dbQuery handleId da query OCI
 *
 * @see dbOpen_OCI()
 * @see dbParse_OCI()
 *
 * @since Versão 1.0
 */
function dbExecute_OCI(&$dbHandle, $dbSQL) {
  $debugBackTrace = debug_backtrace();

  $debugFile     = basename($debugBackTrace[1]["file"]);
  $debugFunction = $debugBackTrace[1]["function"];

  $dbDriver   = $dbHandle[dbHandleDriver];

  $dbHandleId = $dbHandle[dbHandleId];

  if (!$ociParse = @oci_parse($dbHandleId, $dbSQL)) {
    $e = oci_error($dbHandleId);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Parse</b>:"
        ."<br /><b>Host</b>: ".$dbHost
        ."<br /><b>Code</b>: ".$e["code"]
        ."<br /><b>Message</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Offset</b>: ".($e["offset"] + 1)
        ."<hr />".htmlentities(substr($e["sqltext"], 0, $e["offset"]))."<font color=\"red\"\><b>Error (".$e["code"].")</b> --&gt; </font>".htmlentities(substr($e["sqltext"], $e["offset"], 99999));

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  if (!$ociExecute = @oci_execute($ociParse)) {
    $e = oci_error($ociParse);

    echo "<span style=\"text-align: left;\"><pre><b>$dbDriver - $debugFile - $debugFunction() - Execute</b>:"
        ."<br /><b>Conexão</b>: ".$dbHost
        ."<br /><b>Código</b>: ".$e["code"]
        ."<br /><b>Mensagem</b>: [".htmlentities($e["message"])."]"
        ."<br /><b>Offset</b>: ".($e["offset"] + 1)
        ."<hr />".htmlentities(substr($e["sqltext"], 0, $e["offset"]))."<font color=\"red\"\><b>Erro (".$e["code"].")</b> --&gt; </font>".htmlentities(substr($e["sqltext"], $e["offset"], 99999));

    echo "<hr />".debugBackTrace();

    echo "</pre></span>";
    die();
  }

  return $ociExecute;

}
?>