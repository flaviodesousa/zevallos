<?php
/**
 * zlmView
 *
 * Lugar M�dico - zTags para o processamento do Perfil do usu�rio
 *
 * @package ztag
 * @subpackage header
 * @category Environment
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

define("zlmviewVersion", 1.0, 1);
define("zlmviewVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zlmview_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zlmview_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zlmview_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zlmview_version() {
  return zlmviewVersion." ".zlmviewVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zlmview_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zlmview_compare($version) {
  return zlmviewVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zlmview_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
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
function zlmview_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strVar   = $arrParam["var"];
  $strUser  = $arrParam["user"];
  $strUse   = $arrParam["use"];
  $strRoom = $arrParam["view"];
  
  $errorMessage = "";

  $tagValue = $arrayTagId[$strParam][ztagIdValue];

  switch (strtolower($tagFunction)) {
    /*+
     * Return an array with all user friends
     *
     * <code>
     * <zlmview:grouplist use="myConn" user="!userLogged" var="groupList" />
     * </code>
     *
     * @param string use="myConn" Database connection 
     * @param string user="!userLogged" User that you want to get his friend list 
     * @param string var="friendList" Variable to save the array
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "colegas":
        $errorMessage .= ztagParamCheck($arrParam, "use,var,user");

        if ($strRoom) {
                $strRoomSQL = "SP.FL_ATIVO = 1 AND P.FL_ATIVO = 1 AND SP.CD_SALA = $strRoom";
        } else if ($strUser) {
                $strUserSQL = "Sa.FL_ATIVO = 1 AND (Sa.CD_PESSOA = $strUser OR SP.CD_PESSOA = $strUser)";
        }
  		
    	$sql = "SELECT DISTINCT Sa.CD_SALA id
    	, Sa.NM_SALA nome
    	, Sa.DS_RESUMO resumo     	
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%d/%m/%Y %H:%i:%S') inclusao
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%d') inclusao_day
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%m') inclusao_month
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%Y') inclusao_year
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%H') inclusao_hour
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%i') inclusao_minute
    	, DATE_FORMAT(Sa.DT_INCLUSAO , '%S') inclusao_second 
			, P.CD_PESSOA pessoa_id
			, P.NM_PESSOA pessoa_nome
			, CASE WHEN P.DS_AVATAR IS NULL THEN '/avatar/avatar.jpg' ELSE P.DS_AVATAR END pessoa_avatar
			, CASE WHEN Sa.CD_PESSOA = P.CD_PESSOA THEN 1 ELSE 0 END dono   	
    	FROM TB_SALA Sa 
    	LEFT JOIN TB_PESSOA P ON Sa.CD_PESSOA = P.CD_PESSOA
    	LEFT JOIN TB_SALA_PESSOAS SP ON SP.CD_PESSOA = P.CD_PESSOA
    	WHERE $strUserSQL$strRoomSQL";

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];
    	
			dbQuery($dbHandle, $sql);
			
			$i=0;
			
  		while (dbFetch($dbHandle, $strResultType)) {
  			$arrayVar[$i++] = $dbHandle[dbHandleFetch];
  			
  		}

  		zTagSetVar($arrayTagId, $strVar, $arrayVar);
    	break;

    /*+
     * Return an array with user profile
     *
     * <code>
     * <zlmview:view use="myConn" user="!userLogged" var="profile" />
     * </code>
     *
     * @param string use="myConn" Database connection 
     * @param string user="!userLogged" User that you want to get his friend list 
     * @param string var="friendList" Variable to save the array
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "roomview":
  		$errorMessage .= ztagParamCheck($arrParam, "use,var,user");
  		
    	$sql = "SELECT     	
    	  DATE_FORMAT(SP.DT_INCLUSAO , '%d/%m/%Y %H:%i:%S') inclusao
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%d') inclusao_day
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%m') inclusao_month
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%Y') inclusao_year
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%H') inclusao_hour
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%i') inclusao_minute
    	, DATE_FORMAT(SP.DT_INCLUSAO , '%S') inclusao_second 
		, P.CD_PESSOA id_p
		, P.NM_PESSOA nome_p
		, P.DS_AVATAR avatar_p   	
    	FROM TB_SALA_PESSOAS SP
    	LEFT JOIN TB_PESSOA P ON SP.CD_PESSOA = P.CD_PESSOA
    	WHERE ";
    	
      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];
    	
			dbQuery($dbHandle, $sql);
						
  		if (dbFetch($dbHandle, $strResultType)) {
  			zTagSetVar($arrayTagId, $strVar, $dbHandle[dbHandleFetch]);
  			
  		}	
    	break;

    /*+
     * Return an array with all circle data
     *
     * <code>
     * <zlmview:cirle use="myConn" circle="!userLogged" var="circle" />
     * </code>
     *
     * @param string use="myConn" Database connection 
     * @param string user="!userLogged" User that you want to get his friend list 
     * @param string var="friendList" Variable to save the array
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "cirle":
  		$errorMessage .= ztagParamCheck($arrParam, "use,var,circle");
  		
    	$sql = "SELECT Ci.CD_CIRCULO id
    	, Ci.NM_CIRCULO nome
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%d/%m/%Y %H:%i:%S') inclusao
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%d') inclusao_day
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%m') inclusao_month
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%Y') inclusao_year
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%H') inclusao_hour
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%i') inclusao_minute
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%S') inclusao_second    	
    	FROM TB_CIRCULO Ci
    	WHERE Ci.FL_ATIVO = 1 AND Ci.CD_CIRCULO = ".$strCircle;

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];
    	
			dbQuery($dbHandle, $sql);
			
  		if (dbFetch($dbHandle, $strResultType)) {
  			zTagSetVar($arrayTagId, $strVar, $dbHandle[dbHandleFetch]);
  			
  		}	
			break;
    	
		/*+
     * Return an array with all user friends
     *
     * <code>
     * <zlmview:cirles use="myConn" user="!userLogged" var="friendList" />
     * </code>
     *
     * @param string use="myConn" Database connection 
     * @param string user="!userLogged" User that you want to get his friend list 
     * @param string var="friendList" Variable to save the array
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "cirles":
  		$errorMessage .= ztagParamCheck($arrParam, "use,var,user");
  		
    	$sql = "SELECT Ci.CD_CIRCULO id
    	, Ci.NM_CIRCULO nome
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%d/%m/%Y %H:%i:%S') inclusao
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%d') inclusao_day
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%m') inclusao_month
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%Y') inclusao_year
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%H') inclusao_hour
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%i') inclusao_minute
    	, DATE_FORMAT(Ci.DT_INCLUSAO , '%S') inclusao_second
    	, (SELECT COUNT(*) FROM TB_CIRCULO_PESSOA CP WHERE CP.CD_CIRCULO = Ci.CD_CIRCULO AND CP.FL_ATIVO = 1) qtd_pessoa    	
    	FROM TB_CIRCULO Ci
    	WHERE Ci.FL_ATIVO = 1 AND Ci.CD_PESSOA = ".$strUser;

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];
    	
			dbQuery($dbHandle, $sql);
			
			$i=0;
			
  		while (dbFetch($dbHandle, $strResultType)) {
  			$arrayVar[$i++] = $dbHandle[dbHandleFetch];
  			
  		}

  		zTagSetVar($arrayTagId, $strVar, $arrayVar);
    	break;

    /*+
     * Return an array with all user friends
     *
     * <code>
     * <zlmview:friendlist use="myConn" user="!userLogged" var="friendList" />
     * </code>
     *
     * @param string use="myConn" Database connection 
     * @param string user="!userLogged" User that you want to get his friend list 
     * @param string var="friendList" Variable to save the array
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "circlelist":
  		$errorMessage .= ztagParamCheck($arrParam, "use,var,circle");
  		
  		if ($strCircle) {
  			$strCircleSQL = "AND CD_CIRCULO = $strCircle";
  		} else if ($strUser) {
  			$strUserSQL = "AND CD_CIRCULO IN (SELECT CD_CIRCULO FROM TB_CIRCULO WHERE FL_ATIVO = 1 AND CD_PESSOA = $strUser)";
  		}
  		
    	$sql = "SELECT P.CD_PESSOA id
    	, P.NM_PESSOA nome
	    , CASE WHEN P.DS_AVATAR IS NULL THEN '/avatar/avatar.jpg' ELSE P.DS_AVATAR END avatar
	    , CASE WHEN P.DS_URL_PERFIL IS NULL THEN CONCAT('/perfil.ztag?p=', P.CD_PESSOA) ELSE P.DS_URL_PERFIL END profileurl
    	FROM TB_PESSOA P
    	WHERE P.FL_ATIVO = 1 AND P.CD_PESSOA IN (
    	SELECT DISTINCT CD_PESSOA 
    	  FROM TB_CIRCULO_PESSOA 
    	  WHERE FL_ATIVO $strCircleSQL$strUserSQL
    	)";
    	
    	// echo "<br /><pre>".$sql;

      $dbHandle = $arrayTagId[$strUse][ztagIdHandle];
    	
			dbQuery($dbHandle, $sql);
			
			$i=0;
			
  		while (dbFetch($dbHandle, $strResultType)) {
  			$arrayVar[$i++] = $dbHandle[dbHandleFetch];
  			
  		}

  		zTagSetVar($arrayTagId, $strVar, $arrayVar);
    	break;
    	
    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  /*
  debugError("tagFunction=$tagFunction"
            ."<br />tagId=$tagId"
            ."<br />strId=$strId"
            ."<br />strUse=$strUse"
            ."<br />strValue=$strValue"
            ."<br />arrayTagId[$strId][ztagIdValue]=".$arrayTagId[$strId][ztagIdValue]
            ."<br />arrayTagId[$strUse][ztagIdValue]=".$arrayTagId[$strUse][ztagIdValue]
            ."<br />arrayTag[$tagId][ztagResult]=".$arrayTag[$tagId][ztagResult]);
            */

  ztagError($errorMessage, $arrayTag, $tagId);
}

