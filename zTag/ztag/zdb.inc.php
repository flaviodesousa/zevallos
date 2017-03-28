<?php
/**
 * ZDb
 *
 * Processa as tags para processo de banco e dados
 *
 * @package ztag
 * @subpackage template
 * @category Database
 * @version $Revision$
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

define("zdbVersion", 1.0, 1);
define("zdbVersionSufix", "ALFA 0.1", 1);

$dbHandleDefault = "";

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zdb_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zdb_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zdb_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zdb_version() {
  return zdbVersion." ".zdbVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zdb_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zdb_compare($version) {
  return zdbVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zdb_zexecute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zdb_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  global $dbHandleDefault;

  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId       = $arrParam["id"];
  $strUpdate   = $arrParam["update"];

  $strDriver   = $arrParam["driver"];
  $strHost     = $arrParam["host"];
  $strDatabase = $arrParam["database"];
  $strUser     = $arrParam["user"];
  $strPassword = $arrParam["password"];

  $strFilename = $arrParam["filename"];
  $strMode     = $arrParam["mode"];

  $strUse      = $arrParam["use"];
  $strName     = $arrParam["name"];

  $strTransform = $arrParam["transform"];

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
	  /*+
     * Open a database connection to defined driver and parameters
     *
     * <code>
	   * <zdb:open id="mysqlConn" driver="mysql" host="#mysqlHost" database="#mysqlDatabase" user="#mysqlUser" password="#mysqlPassword" charset="utf8"/>
	   *
	   * <zdb:open id="sqliteConn" driver="sqlite" filename="/ZTag/SQLite.db" />
     * </code>
	   *
	   * @param string id="MySQL Handle Id"
	   * @param string driver="mssql|mysql|pgsql|sqlite|firebird"
	   * @param string host="Host"
	   * @param string database="Database name"
	   * @param string user="User name"
	   * @param string password="Password"
	   * @param string charset="utf8"
	   */
    case "open":
      $strCharset = $arrParam["charset"];

      $strDriver = constant("db$strDriver");

      switch ($strDriver) {
        case dbOCI:
          $blnDatabase = 0;
          break;

        default:
          $blnDatabase = 1;
      }

      if (strlen($strHost))     ztagReturnConstant($strHost);
      if (strlen($strDatabase)) ztagReturnConstant($strDatabase);
      if (strlen($strUser))     ztagReturnConstant($strUser);
      if (strlen($strPassword)) ztagReturnConstant($strPassword);

      switch ($strDriver) {
        case dbSQLite:
          if (strlen($strFilename)) ztagReturnConstant($strFilename);
          if (strlen($strMode))     ztagReturnConstant($strMode);

          if (!$strMode) $strMode = 0666;

          $errorMessage .= ztagParamCheck($arrParam, "id,driver,filename");

          $dbHandle = dbOpenFile($strDriver, $strFilename, $strMode);

          if ($arrParam["name"]) $dbHandleDefault = $dbHandle;
          break;

        case dbFirebird:
          // @TODO melhorar o esquema de retornar as variáveis, podemos usar o @@ para todas as variáveis, inclusive as constantes
          $errorMessage .= ztagParamCheck($arrParam, "id,driver,user,password,database");

          $dbHandle = dbOpen($strDriver, $strHost, $strDatabase, $strUser, $strPassword);
          break;

        case dbMySQL:
          // @TODO melhorar o esquema de retornar as variáveis, podemos usar o @@ para todas as variáveis, inclusive as constantes
          if (!$strDatabase && $blnDatabase) $checkParam .= ",database";

          $errorMessage .= ztagParamCheck($arrParam, "id,driver,host,user,password$checkParam");

          $dbHandle = dbOpen($strDriver, $strHost, $strDatabase, $strUser, $strPassword, $strCharset);

        default:
          // @TODO melhorar o esquema de retornar as variáveis, podemos usar o @@ para todas as variáveis, inclusive as constantes
          if (!$strDatabase && $blnDatabase) $checkParam .= ",database";

          $errorMessage .= ztagParamCheck($arrParam, "id,driver,host,user,password$checkParam");

          $dbHandle = dbOpen($strDriver, $strHost, $strDatabase, $strUser, $strPassword);
      }

      if ($errorMessage) $errorMessage .= "<br />$strDriver$errorMessage";

      $arrayTagId[$strId][ztagIdHandle] = $dbHandle;
      $arrayTagId[$strId][ztagIdType]   = idTypeDB;
      $arrayTagId[$strId][ztagIdState]  = idStateOpened;

      break;

    /*+
     * Close the Database connection openned with Id.
     *
     * <code>
     * <zdb:close use="mysqlConn" />
     * </code>
     *
     * @param string use="mysqlConn"
     */
    case "close":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      dbClose($arrayTagId[$strUse][ztagIdHandle]);
      $arrayTagId[$strUse][ztagIdState]  = idStateClosed;
      break;

    /*+
     * Set a SQL query to use with another zDB Tag.
     *
     * <code>
     * <zdb:query use="ociConn" id="ociQuery">
     *   SELECT US.NM_USUARIO userLogin
     *   , P.NM_PESSOA userName
     *   , P.CD_PESSOA userId
     *   , US.CD_SESSAO_LOGIN userLoginLast
     *   , US.CD_SESSAO_LOGIN_ERRO userLoginError
     *   , US.NU_LOGIN_ERROS userLoginErrors
     *   , to_char(US.DT_LOGIN_BLOQUEIO,'yyyy/mm/dd hh24:mi:ss') userLoginBlocked
     *   FROM TB_USUARIO_SISTEMA US
     *   LEFT JOIN TB_PESSOA P ON US.CD_PESSOA_USUARIO = P.CD_PESSOA
     *   AND FL_ATIVO = 'S'
     * </zdb:query>
     * </code>
     *
     * @param string use="ociConn" The Id Handle from a Open zDB tag
     * @param string id="ociQuery" Id where the Query will be saved
     * @param string update="ociQuery" Id to be updated
     */
    case "query":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      if ($arrayTag[$tagId][ztagContentWidth]) {
        $strContent = ztagVars($arrayTag[$tagId][ztagContent], $arrayTagId);

        if (!strlen($strId)) {
          $errorMessage .= ztagParamCheck($arrParam, "update");

          $strId = $strUpdate;

        } else {
          $errorMessage .= ztagParamCheck($arrParam, "id");
        }

        if ($strId) {
          $arrayTagId[$strId][ztagIdValue]  = $strContent;
          $arrayTagId[$strId][ztagIdLength] = strlen($strContent);

          $arrayTagId[$strId][ztagIdType] = idTypeQuery;

          $arrayTagId[$strId][ztagIdHandle] = $arrayTagId[$strUse][ztagIdHandle];

        }

        dbQuery($arrayTagId[$strId][ztagIdHandle], $strContent);

      } else {
        $errorMessage .= "<br />Tag Query cannot be empty!";
      }
      break;

    /*+
     * Return TRUE if the current record is at the first
     *
     * <code>
     * <zdb:bof use="mysqlConn" />
     * </code>
     *
     * @param string use="mysqlConn" - The Id Handle from a Open zDB tag
     */
    case "bof":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      $arrayTag[$tagId][ztagResult] = dbBOF($arrayTagId[$strUse][ztagIdHandle]);
      break;

    /*+
     * Return TRUE if the current record is at the first
     *
     * <code>
     * <zdb:eof use="mysqlConn" />
     * </code>
     *
     * use="mysqlConn" - The Id Handle from a Open zDB tag
     */
    case "eof":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      $arrayTag[$tagId][ztagResult] = dbEOF($arrayTagId[$strUse][ztagIdHandle]);
      break;

    /*+
     * Return TRUE if the current record is at the first
     *
     * <code>
     * <zdb:field use="mysqlConn" name="NM_NOME" />
     *
     * Or
     *
     * <zdb:field name="NM_NOME" />
     * </code>
     *
     * @param string use="mysqlConn" - The Id Handle from a Open zDB tag
     */
    case "field":
      if ($intFather = $arrayTag[$tagId][ztagFather]) $arrParam["use"] = "Field_$intFather";

      // echo "<br />intFather=$intFather";

      $strVar = $arrParam["var"];

      $errorMessage .= ztagParamCheck($arrParam, "use,name");

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];

      $dbHandleValue = $dbHandle[dbHandleFetch][$strName];

      if ($strTransform) $dbHandleValue = ztagTransform($dbHandleValue, $strTransform);

      zTagSetVar($arrayTagId, $strVar, $dbHandleValue, idTypeField);

      $arrayTag[$tagId][ztagResult] = $dbHandleValue;
      break;

    /*+
     * FieldVar
     *
     * <code>
     * <zdb:fieldvar use="mysqlConn" name="NM_NOME" var="$NM_NOME" />
     * </code>
     *
     * @param string use="mysqlConn" The Id Handle from a Open zDB tag
     * @param string name="NM_NOME"
     * @param string var="$NM_NOME"
     */
    case "fieldvar":
      $strVar = $arrParam["var"];

      $errorMessage .= ztagParamCheck($arrParam, "use,name,var");

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];

      $dbHandleValue = $dbHandle[dbHandleFetch][$strName];

      if ($strTransform) $dbHandleValue = ztagTransform($dbHandleValue, $strTransform);

      zTagSetVar($arrayTagId, $strVar, $dbHandleValue, idTypeField);

      break;

    /*+
     * Prepare
     *
     * <code>
     * <zdb:prepare conn="oracleConn" use="oracleQuery" />
     * </code>
     *
     * @param string conn="oracleConn"
     * @param string use="oracleQuery"
     */
    case "prepare":
      $errorMessage .= ztagParamCheck($arrParam, "use,conn");

      $dbHandle = $arrayTagId[$strConn][ztagIdHandle];
      $dbQuery  = $arrayTagId[$strUse][ztagIdValue];

      dbPrepare($dbHandle, $dbQuery);
      break;

    /*+
     * Prepare
     *
     * <code>
     * <zdb:param use="query002" param="P_CD_PESSOA_USUARIO" var="" type="PARAM_INT" />
     * </code>
     *
     * @param string use="query002"
     * @param string param="P_CD_PESSOA_USUARIO"
     * @param string var=""
     * @param string type="PARAM_INT"
     */
    case "param":
      $errorMessage .= ztagParamCheck($arrParam, "id,value");

      $arrayTagId[$strId][ztagIdValue] = $strValue;
      $arrayTagId[$strId][ztagIdLength] = strlen($strValue);

      $arrayTagId[$strId][ztagIdType] = idTypeFVar;
      break;

    /*+
     * Create
     *
     * <code>
     * <zdb:create use="query002" />
     * </code>
     *
     * @param string use="query002"
     */
    case "create":
      $errorMessage .= ztagParamCheck($arrParam, "id");

      if ($arrayTag[$tagId][ztagContentWidth]) {
        $strContent = $arrayTag[$tagId][ztagContent];

        $arrayTagId[$strId][ztagIdValue]  = $strContent;
        $arrayTagId[$strId][ztagIdLength] = strlen($strContent);

      }
      break;

    /*+
     * Show
     *
     * <code>
     * <zdb:show use="query002" />
     * </code>
     *
     * @param string use="query002"
     */
    case "show":
      $errorMessage .= ztagParamCheck($arrParam, "use");

      $arrayTag[$tagId][ztagResult] = $arrayTagId[$strUse][ztagIdValue];

      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

/*+
 * Fetch
 *
 * <code>
 * <zdb:fetch use="ociQuery" var="ociRow" resulttype="ASSOC" />
 * </code>
 *
 * @param string use="ociQuery"
 * @param string var="ociRow"
 * @param string resulttype="ASSOC"
 */
function zdb_Fetch($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId  = $arrParam["id"];

  $strUse        = $arrParam["use"];
  $strResultType = $arrParam["resulttype"];

  $strVar        = $arrParam["var"];

  /*
   echo "<br />arrParam";
   debugError($arrParam);

   echo "<br />arrayTagId";
   debugError($arrayTagId);

   echo "<br />arrayTagId($strUse)";
   debugError($arrayTagId[$strUse]);

   echo "<br />arrayTagId($strId)";
   debugError($arrayTagId[$strId]);
   */

  $errorMessage = ztagParamCheck($arrParam, "use");

  $strLocalId = $strUse;

  if ($strId) {
    $strLocalId = $strId;

    $arrayTagId[$strLocalId][ztagIdValue]  = $strContent;
    $arrayTagId[$strLocalId][ztagIdLength] = strlen($strContent);

    $arrayTagId[$strLocalId][ztagIdType] = idTypeFetch;

    $arrayTagId[$strLocalId][ztagIdHandle] = $arrayTagId[$strUse][ztagIdHandle];

  }

  if ($strResultType) {
    dbFetch($arrayTagId[$strLocalId][ztagIdHandle], $strResultType);

  } else {
    dbFetch($arrayTagId[$strLocalId][ztagIdHandle]);

  }

  zTagSetVar($arrayTagId, $strVar, $arrayTagId[$strLocalId][ztagIdHandle][dbHandleFetch], idTypeField);


  ztagError($errorMessage, $arrayTag, $tagId);
}

/*+
 * FetchUntil
 *
 * <code>
 * <zdb:fetchuntil condition="eof" use="mysqlQuery" id="mysqlFetch" var="$mysqlRow" filename="/Alianca8/File.txt" filetype="txt" show="0" field="1">
 * </zdb:fetchuntil>
 * </code>
 *
 * @param string condition="eof"
 * @param string use="mysqlQuery"
 * @param string id="mysqlFetch"
 * @param string var="$mysqlRow"
 * @param string filename="/Alianca8/File.txt"
 * @param string filetype="txt"
 * @param string show="0"
 * @param string field="1" Eneble the use of legacy like <zTag:Field name="fieldName" /> Or <zTagField.fieldName />
 */
function zdb_FetchUntil($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId         = $arrParam["id"];

  $strFileName   = $arrParam["filename"];
  $strFileType   = $arrParam["filetype"];

  $strShow       = strtolower($arrParam["show"]);
  $strTimes      = $arrParam["times"];
  $strUse        = $arrParam["use"];
  $strResultType = $arrParam["resulttype"];

  $strVar        = $arrParam["var"];
  $strField      = $arrParam["field"];

  $errorMessage = ztagParamCheck($arrParam, "use");

  $strParam = "";

  $templateContent = $arrayTag[$tagId][ztagContent];

  $strLocalId = $strUse;

  if ($strId) {
    $strLocalId = $strId;

    $arrayTagId[$strLocalId][ztagIdValue]  = $strContent;
    $arrayTagId[$strLocalId][ztagIdLength] = strlen($strContent);

    $arrayTagId[$strLocalId][ztagIdType] = idTypeFetch;

    $arrayTagId[$strLocalId][ztagIdHandle] = $arrayTagId[$strUse][ztagIdHandle];

  }

  if(!$strTimes) {
    $blnOk = 1;
  } else {
    $blnOk = $intTimes = intval($strTimes) - 1;
  }

  $blnShow = ($strShow === "true" || $strShow === "1" || !strlen($strShow));

  if ($strFileName) {
    $strFileName = str_replace("\\", "/", $strFileName);

    if (substr($strFileName , 0, 1) === "/") $strFileName = substr($strFileName, 1);

    $strFileName = SiteRootDir.$strFileName;

    if (!$handleFile = fopen($strFileName, "w")) $errorMessage .= "\r\nCannot open file ($strFileName)";
  }

  $errorMessageTemp = "";

  while (dbFetch($arrayTagId[$strLocalId][ztagIdHandle], $strResultType) && $blnOk) {
    zTagSetVar($arrayTagId, $strVar, $arrayTagId[$strLocalId][ztagIdHandle][dbHandleFetch], idTypeFetch);

    if (strlen($strField)) {
      $arrayTagId["Field_$tagId"][ztagIdValue] = $arrayTagId[$strLocalId][ztagIdHandle][dbHandleFetch];
      $arrayTagId["Field_$tagId"][ztagIdType]  = idTypeFetch;
    }

    if ($strTimes) $blnOk = $intTimes--;

    $strResult = ztagRun($templateContent, 0, $arrayTagId);

    if ($handleFile && fwrite($handleFile, $arrayTag[$tagId][ztagResult]) === FALSE) {
      if (!$errorMessageTemp) $errorMessage .= $errorMessageTemp = "\r\nCannot write to file ($strFileName)";

    }

    if ($blnShow) $arrayTag[$tagId][ztagResult] .= $strResult;
  }

  if ($handleFile) fclose($handleFile);

  ztagError($errorMessage, $arrayTag, $tagId);
}

/*+
 * FetchUntil
 *
 * <code>
 * <zdb:execute use="mssqlConn">
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Execute($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId  = $arrParam["id"];
  $strUse = $arrParam["use"];

	$errorMessage .= ztagParamCheck($arrParam, "use");

	if ($arrayTag[$tagId][ztagContentWidth]) {
	  $strContent = ztagVars($arrayTag[$tagId][ztagContent], $arrayTagId);

	  $strLocalId = $strUse;

	  if ($strId) {
	    $strLocalId = $strId;

	    $arrayTagId[$strId][ztagIdValue]  = $strContent;
	    $arrayTagId[$strId][ztagIdLength] = strlen($strContent);

	    $arrayTagId[$strId][ztagIdType] = idTypeExecute;

	    $arrayTagId[$strId][ztagIdHandle] = $arrayTagId[$strUse][ztagIdHandle];

	  }

	  dbExecute($arrayTagId[$strLocalId][ztagIdHandle], $strContent);

	} else {
	  $errorMessage .= "<br />Tag Execute cannot be empty!";
	}

  ztagError($errorMessage, $arrayTag, $tagId);

}

/*
 * <zdb:model id="OM3">
 *   <zdb:table name="pubPaginas" alias="P6" primarykey="pagCodigo">
 *     <zdb:table name="pubPaginas" alias="P5" primarykey="pagCodigo" relates="P6:pagCodigo">
 *       <zdb:table name="pubPaginas" alias="P4" primarykey="pagCodigo" relates="P5:pagCodigo">
 *         <zdb:table name="pubPaginas" alias="P3" primarykey="pagCodigo" relates="P4:pagCodigo">
 *           <zdb:table name="pubPaginas" alias="P2" primarykey="pagCodigo" relates="P3:pagCodigo">
 *             <zdb:table name="pubPaginas" alias="P1" primarykey="pagCodigo" relates="P1:pagPai=P2:pagCodigo">
 *               <zdb:field name="pagCodigo" type="int" caption="Id" align="right" />
 *               <zdb:field name="pagNome" type="text" caption="Nome" />
 *             </zdb:table>
 *           </zdb:table>
 *         </zdb:table>
 *       </zdb:table>
 *     </zdb:table>
 *   </zdb:table>
 *
 *  <zdb:table name="pubPaginas" primarykey="pagCodigo">
 *    <zdb:field name="pagCodigo" type="int" caption="Id" align="right" />
 *    <zdb:field name="pagNome" type="text" caption="Nome" />
 *  </zdb:table>
 *
 * </ztag:model>
 *
 * <zform:begin use="OM3"/>
 *
 * <zform:boxtext model="OM3" use="P1:pagCodigo" />
 * <zform:boxtext use="pagNome" />
 *
 * <zdb:modelfield use="OM3" name="pubPaginas:pagCodigo" caption="Código" align="center" />
 *
 * <zlist:run id="listOM3" use="OM3" where="P1:pagAtivo = 1 AND P6:pagAtivo = 1" orderby="P1:pagNome">
 *  <zlist:field use="P6:pagCodigo" />
 *  <zlist:field use="P1:pagCodigo" />
 *  <zlist:field use="P1:pagNome" />
 * </zlist:run>
 *
 * Sequence
 * 0 - P6
 * 1 - P5
 * 2 - P4
 * 3 - P3
 * 4 - P2
 * 5 - P1
 *
 * P6 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *
 * P5 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *    [relates]    = P6:pagCodigo
 *
 * P4 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *    [relates]    = P5:pagCodigo
 *
 * P3 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *    [relates]    = P4:pagCodigo
 *
 * P2 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *    [relates]    = P3:pagCodigo
 *
 * P1 [primarykey] = pagCodigo
 *    [name]       = pubPaginas
 *    [relates]    = P2:pagCodigo
 *
 * <zdb:model id="RAUD0001" type="yml" load="File.yml" />
 *
 * <zdb:model id="RAUD0001">
 *   <zdb:table name="TB_GUIA" alias="G" primarykey="NU_GUIA">
 *     <zdb:table name="TB_GUIA_CM" alias="GC" primarykey="NU_CONTA_MEDICA,CD_SEQUENCIA_GUIA" relates="G:NU_GUIA">
 *       <zdb:table name="TB_CONTA_MEDICA" alias="CM" primarykey="NU_CONTA_MEDICA" relates="GC:NU_CONTA_MEDICA" override="NU_CONTA_MEDICA">
 *         <zdb:table name="TB_PESSOA" alias="PP" primarykey="CD_PESSOA" relates="CM:CD_PESSOA" override="CD_PESSOA_PRESTADOR" />
 *       </zdb:table>
 *     </zdb:table>
 *     <zdb:table name="TB_USUARIO" alias="U" primarykey="CD_USUARIO" relates="G:CD_USUARIO" override="CD_USUARIO">
 *       <zdb:table name="TB_PESSOA" alias="UP" primarykey="CD_PESSOA" relates="U:CD_PESSOA_USUARIO" />
 *     </zdb:table>
 *   </zdb:table>
 *   <zdb:view name="V_GUIA_CM_TOTAIS" alias="GCT" relates="GC:CD_SEQUENCIA_GUIA,NU_GUIA,CM:NU_CONTA_MEDICA" override="CD_SEQUENCIA_GUIA,NU_GUIA,NU_CONTA_MEDICA" />
 *   <zdb:view name="V_GUIA_QUANTIDADE" alias="GQ" relates="GC:NU_GUIA,CM:NU_CONTA_MEDICA" override="NU_GUIA,NU_CONTA_MEDICA" />
 * </zdb:model>
 *
 * Sequence
 * 0 - G
 * 1 - GC
 * 2 - CM
 * 3 - PP
 *
 * 4 - Y
 * 5 - UP
 *
 * 6 - GCT
 * 7 - GQ
 *
 * G [primarykey] = NU_GUIA
 *   [name]      = TB_GUIA
 *
 * GC [primarykey] = NU_CONTA_MEDICA,CD_SEQUENCIA_GUIA
 *    [name]       = TB_GUIA_CM
 *    [relates][G][0][field] = NU_GUIA <-- O NU_GUIA se relaciona com o PK da tabela G (TB_GUIA)
 *
 * CM [primarykey] = NU_CONTA_MEDICA
 *    [name]      = TB_CONTA_MEDICA
 *    [relates][GC][0][field]    = NU_CONTA_MEDICA
 *    [relates][GC][0][override] = NU_CONTA_MEDICA
 *
 * PP [primarykey] = CD_PESSOA
 *    [name]      = TB_PESSOA
 *    [relates][CM][0][field] = CD_PESSOA
 *    [relates][CM][0][override] = CD_PESSOA_PRESTADOR
 *
 * U [primarykey] = CD_USUARIO
 *   [name]      = TB_USUARIO
 *   [relates]    = G:CD_USUARIO
 *   [override]   = CD_USUARIO
 *
 * UP [primarykey] = PP [primarykey] =
 *    [name]      = TB_USUARIO
 *    [relates][G][0][field] = CD_USUARIO <-- O CD_USUARIO se relaciona com o PK da tabela G (TB_GUIA)
 *    [relates][G][0][override] = CD_USUARIO   <-- Sobrepoe o campo de relação da tabela G para CD_USUARIO (não será mais o PK)
 *
 * <view name="V_GUIA_CM_TOTAIS" alias="GCT" relates="GC:CD_SEQUENCIA_GUIA,NU_GUIA,CM:NU_CONTA_MEDICA" override="CD_SEQUENCIA_GUIA,NU_GUIA,NU_CONTA_MEDICA" />
 * GCT [primarykey] = ""
 *     [name]      = V_GUIA_CM_TOTAIS
 *     [relates][GC][0][field]    = CD_SEQUENCIA_GUIA
 *     [relates][GC][0][override] = CD_SEQUENCIA_GUIA
 *
 *     [relates][GC][1][field]    = NU_GUIA
 *     [relates][GC][1][override] = NU_GUIA
 *
 *     [relates][CM][0][field]    = NU_CONTA_MEDICA
 *     [relates][CM][0][override] = NU_CONTA_MEDICA
 *
 * <view name="V_GUIA_QUANTIDADE" alias="GQ" relates="GC:NU_GUIA,CM:NU_CONTA_MEDICA" override="NU_GUIA,NU_CONTA_MEDICA" />
 * GQ [primarykey] = ""
 *    [name]       = V_GUIA_QUANTIDADE
 *    [relates][GC][0][field]    = NU_GUIA
 *    [relates][GC][0][override] = NU_GUIA
 *
 *    [relates][CM][0][field]    = NU_CONTA_MEDICA
 *    [relates][CM][0][override] = NU_CONTA_MEDICA
 *
 */

/*+
 * Model
 *
 * <code>
 * <zdb:model use="mssqlConn">
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Model($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strId        = $arrParam["id"];

  $errorMessage .= ztagParamCheck($arrParam, "id");

  $templateContent = $arrayTag[$tagId][ztagContent];

  $strLocalId = $strUse;

  $arrayTagTable   = array();
  $arrayModel      = array();
  $arrayModelOrder = array();

  // Compile the Model to recover all zDB:Table tags content
  ztagCompile($templateContent, $arrayTagTable, $arrayTagId, $arrayOrder, $arrayTagLevel);

  $o = 0;

  // <zdb:table name="TB_CONTA_MEDICA" alias="CM" primarykey="NU_CONTA_MEDICA" relates="GC:NU_CONTA_MEDICA" override="NU_CONTA_MEDICA">
  foreach ($arrayTagTable as $keyTag => $valueTag) {
    $arrTagParam = $arrayTagTable[$keyTag][ztagParam];
    $arrTagBegin = $arrayTagTable[$keyTag][ztagBegin];
    $arrTagStart = $arrayTagTable[$keyTag][ztagStart];
    $tagFunction = strtolower($valueTag[ztagFunction]);

    if (!$arrTagBegin) {
      switch ($tagFunction) {
        case "table":
          $errorTagMessage = ztagParamCheck($arrTagParam, "name,primarykey");
          break;

        case "view":
          $errorTagMessage = ztagParamCheck($arrTagParam, "name");
          break;
      }

      if ($errorTagMessage) debugError("zdb:$tagFunction($arrTagStart)<br />$errorTagMessage");

      $strTagName       = $arrTagParam["name"];
      $strTagAlias      = $arrTagParam["alias"];
      $strTagPrimaryKey = $arrTagParam["primarykey"];
      $strTagRelates    = $arrTagParam["relates"];
      $strTagOverride   = $arrTagParam["override"];

      if (!strlen($strTagAlias)) $strTagAlias = $strTagName;

      $arrayModelOrder[$o++] = $strTagAlias;

      if (strlen($strTagPrimaryKey)) $arrayModel[$strTagAlias]["primarykey"] = $strTagPrimaryKey;
      if (strlen($strTagName)) $arrayModel[$strTagAlias]["name"] = $strTagName;

      // Process all Relates - GC:CD_SEQUENCIA_GUIA,NU_GUIA,CM:NU_CONTA_MEDICA
      if (strlen($strTagRelates)) {
        $arrayBlock = explode(";", $strTagRelates);

        $i = 0;

        foreach ($arrayBlock as $keyBlock => $valueBlock) {
          $arrayItem = explode(":", $valueBlock);

          $blockAlias = $arrayItem[0];
          $blockField = $arrayItem[1];

          $arrayModel[$strTagAlias]["relates"][$blockAlias][$i++]["field"] = $blockField;

        }
      }

      // Process all Override - CD_SEQUENCIA_GUIA,NU_GUIA,NU_CONTA_MEDICA
      if (strlen($strTagOverride)) {
        $arrayBlock = explode(";", $strTagOverride);

        $i = 0;

        foreach ($arrayBlock as $keyBlock => $valueBlock) {
          $arrayItem = explode(":", $valueBlock);

          $blockAlias = $arrayItem[0];
          $blockField = $arrayItem[1];

          $arrayModel[$strTagAlias]["relates"][$blockAlias][$i++]["override"] = $blockField;

        }
      }
    }
  }

  $arrayTagId[$strId][ztagIdValue]  = $templateContent;
  $arrayTagId[$strId][ztagIdLength] = strlen($templateContent);

  $arrayTagId[$strId][ztagIdType] = idTypeModel;

  $arrayTagId[$strId][ztagIdModel]      = $arrayModel;
  $arrayTagId[$strId][ztagIdModelOrder] = $arrayModelOrder;

  ztagError($errorMessage, $arrayTag, $tagId);
}

// <zdb:sql id="sqlOM3" use="om3Conn" model="OM3" limit="6" where="P5.pagNome = '2010' AND P1.pagAtivo = 1 AND P1.pagReferencia = 4" orderby="P1.pagCodigo DESC">
function zdb_SQL($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $arrayTable      = array();
  $arrayTableOrder = array();

  $strId        = $arrParam["id"];

  $errorMessage .= ztagParamCheck($arrParam, "id,use,model");

  $templateContent = $arrayTag[$tagId][ztagContent];

  $arrayTagField = array();

  $arrayModel      = $arrayTagId[$strId][ztagIdModel];
  $arrayModelOrder = $arrayTagId[$strId][ztagIdModelOrder];

  $arrayField = array();

  // Compile the Model to recover all zDB:Field tags content
  ztagCompile($templateContent, $arrayTagField, $arrayTagId, $arrayOrder, $arrayTagLevel);

  $o = 0;

  // <zdb:field name="CM.CD_PESSOA_PRESTADOR" />
  foreach ($arrayTagField as $keyTag => $valueTag) {
    $arrTagParam = $arrayTagField[$keyTag][ztagParam];
    $arrTagBegin = $arrayTagField[$keyTag][ztagBegin];
    $arrTagStart = $arrayTagField[$keyTag][ztagStart];

    if (!$arrTagBegin) $errorTagMessage = ztagParamCheck($arrTagParam, "name");

    ztagError($errorTagMessage, $arrayTag, $tagId);

    $strTagName      = $arrTagParam["name"];
    $strTagTransform = $arrTagParam["transform"];

    $intPos = strpos($strTagName, ":");

    if ($intPos) {
      $arrayField[$strTagName]["alias"] = substr($strTagName, 0, $intPos - 1);
      $arrayField[$strTagName]["name"] = substr($strTagName, $intPos + 1, strlen($strTagName));

    }

    // @TODO Gerar a query e os Joins colocando o Where e o Order by
    // @TODO processar a Query e executar o join * vezes como definido no limit.

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}

/* Para inclusão no modelo
 * <zdb:table name="pubPaginas" alias="P6" primarykey="pagCodigo">
 *
 * Para definição da tabela
 * <zdb:table name="pubPaginas" alias="P6" primary="pagCodigo">
 *   <zdb:column name="pagCodigo" type="int" autoincrement="1" notnull="1" comment="PK da linha" />
 *   <zdb:column name="pagNome" type="varchar" size="50" notnull="1" comment="Nome da página" />
 *   <zdb:column name="pagTitulo" type="varchar" size="50" comment="Título da página" />
 *   <zdb:column name="pagTitulo" type="varchar" size="50" comment="Título da página" />
 * </zdb:table>
 *
 * <zdb:table id="TB_CLIENTE" name="TB_CLIENTE" alias="C" primary="CD_CLIENTE">
 *   <zdb:column name="PK_CLIENTE" type="int" autoincrement="1" notnull="1" comment="PK do cliente" />
 *   <zdb:column name="CD_CLIENTE" type="int" notnull="1" comment="Código do cliente" />
 *   <zdb:column name="NM_CLIENTE" type="varchar(50)" notnull="1" comment="Nome do cliente" />
 *   <zdb:column name="DS_INCLUSAO" type="datetime" notnull="1" default="now()" comment="Data e hora da inclusão" />
 *   <zdb:column name="FL_ATIVO" type="tinyint" notnull="1" default="1" comment="Flag se o cliente está ativo" />
 * </zdb:table>
 *
 * <zdb:table id="TB_PEDIDO" name="TB_PEDIDO" alias="Pe" primary="CD_PEDIDO">
 *   <zdb:column name="PK_PEDIDO" type="int" autoincrement="1" notnull="1" comment="PK do Pedido" />
 *   <zdb:column name="PK_CLIENTE" type="int" notnull="1" comment="PK do cliente" />
 *   <zdb:column name="CD_PEDIDO" type="int" notnull="1" comment="Código do Pedido" />
 *   <zdb:column name="DS_INCLUSAO" type="datetime" notnull="1" default="now()" comment="Data e hora da inclusão" />
 * </zdb:table>
 *
 * <zdb:table id="TB_PEDIDO_ITEM" name="TB_PEDIDO_ITEM" alias="PI" primary="CD_PEDIDO_ITEM">
 *   <zdb:column name="PK_PEDIDO_ITEM" type="int" autoincrement="1" notnull="1" comment="PK do Ítem do pedido" />
 *   <zdb:column name="PK_PEDIDO" type="int" notnull="1" comment="PK do pedido" />
 *   <zdb:column name="PK_PRODUTO" type="int" notnull="1" comment="PK do produto" />
 *   <zdb:column name="QT_QUANTIDADE" type="int" notnull="1" comment="Quantidade de produtos" />
 *   <zdb:column name="PK_UNIDADE" type="int" notnull="1" comment="PK da Unidade" />
 *   <zdb:column name="VL_VALOR_UNITARIO" type="money" notnull="1" comment="Valor unitário do produto" />
 *   <zdb:column name="VL_VALOR_TOTAL" type="money" notnull="1" comment="Valor total do produto" />
 *   <zdb:column name="CM_OBSERVACOES" type="varchar(50)" comment="Observações sobre o ítem" />
 *   <zdb:column name="FL_ATIVO" type="tinyint" default="1" comment="Se o ítem está ativo" />
 * </zdb:table>
 *
 * Cria a tabela com toda a definição da tabela
 * <zdb:tablecreate conn="ociUnidental" use="TB_CLIENTE" type="" />
 * - Ver os tipos de tabelas do MySQL
 *
 * Cria o índice da tabela
 * <zdb:tableindex conn="ociUnidental" use="TB_CLIENTE" columns="PK_PEDIDO,PK_PRODUTO" type="" />
 * - as colunas são sempre ordem normal, mas se colocar o *, irá mudar a ordem
 * - O tipo definirá o tipo de indice
 *
 * Lê todas as configurações do banco, então o usuário poderá somente atualizar o que precisar.
 * <zdb:tableload conn="ociUnidental" use="TB_CLIENTE" />
 *
 * Obs: Na descrição das colunas, poderemos colocar na seguinte sintaxe "Caption;Description", onde:
 * - Caption, será incluido como parâmetro Caption e com o & para a letra
 * - Description, será a descrição do campo.
 *
 * Poderei pensar em outras possibilidades, inclusive validação e formato
 *
 * Pensar em talvez incluir nas colunas:
 * - Caption - Para usar em algum lugar
 * - Validate - Quando for precisar de validação com a mensagem de erro
 * - Foreign Key - Relação com outra tabela (talvez como depends tabela.coluna)
 */

/*+
 * Model
 *
 * <code>
 * <zdb:begintransaction conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_BeginTransaction($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:commit conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Commit($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:rollback conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_RollBack($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:errorcode conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_ErrorCode($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:errorinfo conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_ErrorInfo($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:fetchcolumn conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_FetchColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:fetchall conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_FetchAll($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:rowcount conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_RowCount($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:columncount conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_ColumnCount($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:bindvalue conn="ociUnidental" param="" value="" type="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_BindValue($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:bindparam conn="ociUnidental" column="" variable="" type="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_BindParam($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:gettable conn="ociUnidental" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_GetTable($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:gettables conn="ociUnidental" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_GetTables($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:getcolumn conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_GetColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:getcolumns conn="ociUnidental" table="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_GetColumns($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:hastable conn="ociUnidental" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_HasTable($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:createtable conn="ociUnidental" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_CreateTable($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:renametable conn="ociUnidental" name="" to="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_RenameTable($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:droptable conn="ociUnidental" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_DropTable($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:setprimarykey conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_SetPrimaryKey($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:createindex conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_CreateIndex($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:dropindex conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_DropIndex($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:addcolumn conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_AddColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:renamecolumn conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_RenameColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:changecolumn conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_ChangeColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:dropcolumn conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_DropColumn($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:createview conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_CreateView($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:dropview conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_DropView($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:createstoredprocedure conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_CreateStoredProcedure($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:dropstoredprocedure conn="ociUnidental" table="" name="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_DropStoredProcedure($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:select conn="ociUnidental" id=">
 *   <zdb:column name="" />
 *   <zdb:from name="" />
 *   <zdb:leftjoin table="" alias="" />
 *   <zdb:innerjoin table="" alias="" />
 *   <zdb:whereand value="" />
 *   <zdb:whereor value="" />
 *   <zdb:groupby value="" />
 *   <zdb:orderby value="" />
 * </zdb:select>
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Select($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:insert conn="ociUnidental" table="" columns="" values="" />
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Insert($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:update conn="ociUnidental" table="" set="" where="" />
 * UPDATE TB_USUARIO_SISTEMA
 * SET CD_SESSAO_LOGIN_ERRO = ".$_SESSION["sessionId"]."
 * WHERE NM_USUARIO = '$txtUsuario'";
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Update($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}

/*+
 * Model
 *
 * <code>
 * <zdb:delete conn="ociUnidental" table="" where="" />
 * DELETE FROM [TableName]
 * WHERE [Condition];
 * </code>
 *
 * @param string use="mysqlQuery"
 */
function zdb_Delete($tagId, &$arrayTag, &$arrayTagId, $arrayOrder) {
}
