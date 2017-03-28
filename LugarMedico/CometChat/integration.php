<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('SET_SESSION_NAME','');			// Session name
define('DO_NOT_START_SESSION','0');		// Set to 1 if you have already started the session
define('DO_NOT_DESTROY_SESSION','0');	// Set to 1 if you do not want to destroy session on logout
define('SWITCH_ENABLED','0');		
define('INCLUDE_JQUERY','1');	
define('FORCE_MAGIC_QUOTES','0');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */

switch (php_uname('n')) {
  case 'ZevallosNote3': // Ubuntu
		define('DB_SERVER',		'localhost');
		define('DB_PORT',			'3306');
		define('DB_USERNAME',	'root');
		define('DB_PASSWORD',	'');
		define('DB_NAME',			'LugarMedico');
    break;

  case 'hm4204': // Locaweb
		define('DB_SERVER',		'mysql03.zyc.com.br');
		define('DB_PORT',			'3306');
		define('DB_USERNAME',	'zyc3');
		define('DB_PASSWORD',	'lm432*');
		define('DB_NAME',			'zyc3');
    break;

  default:
		define('DB_SERVER',		'localhost');
		define('DB_PORT',			'3306');
		define('DB_USERNAME',	'root');
		define('DB_PASSWORD',	'');
		define('DB_NAME',			'LugarMedico');
  	
}

define('TABLE_PREFIX',				''										);
define('DB_USERTABLE',				'TB_PESSOA'									);
define('DB_USERTABLE_NAME',			'NM_DISPLAY'								);
define('DB_USERTABLE_USERID',		'CD_PESSOA'								);
define('DB_USERTABLE_LASTACTIVITY',	'DT_ULTIMA_ATIVIDADE'							);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */

function getUserID() {
	$userid = 0;
	
	if (!empty($_SESSION['userid'])) {
		$userid = $_SESSION['userid'];
	}
	
	return $userid;
}


function getFriendsList($userid,$time) {
//	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX."friends join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."friends.toid = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX."friends.fromid = '".mysql_real_escape_string($userid)."' order by username asc");

	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from TB_COLEGA join ".TABLE_PREFIX.DB_USERTABLE." on  TB_COLEGA.CD_PESSOA_COLEGA = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where TB_COLEGA.CD_PESSOA = '".mysql_real_escape_string($userid)."' order by username asc");
	return $sql;
}

function getUserDetails($userid) {
	$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." avatar, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	echo "<br />getUserDetails sql=$sql";
	return $sql;
}

function updateLastActivity($userid) {
	$sql = ("update `".TABLE_PREFIX.DB_USERTABLE."` set ".DB_USERTABLE_LASTACTIVITY." = '".getTimeStamp()."' where ".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	echo "<br />updateLastActivity sql=$sql";
	return $sql;
}

function getUserStatus($userid) {
	 $sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".mysql_real_escape_string($userid)."'");
	 return $sql;
}

function getLink($link) {
    return 'users.php?id='.$link;
}

function getAvatar($image) {
    if (is_file(dirname(dirname(__FILE__)).'/images/'.$image.'.gif')) {
        return 'images/'.$image.'.gif';
    } else {
        return '/avatar/noavatar.jpg';
    }
}


function getTimeStamp() {
	return time();
}

function processTime($time) {
	return $time;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid,$statusmessage) {
	
}

function hooks_forcefriends() {
	
}

function hooks_activityupdate($userid,$status) {

}

function hooks_message($userid,$unsanitizedmessage) {
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */

include_once(dirname(__FILE__).'/license.php');
$x="\x62a\x73\x656\x34\x5fd\x65c\157\144\x65";
eval($x('JHI9ZXhwbG9kZSgnLScsJGxpY2Vuc2VrZXkpOyRwXz0wO2lmKCFlbXB0eSgkclsyXSkpJHBfPWludHZhbChwcmVnX3JlcGxhY2UoIi9bXjAtOV0vIiwnJywkclsyXSkpOw'));

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 