<?php

phpinfo();

error_reporting(E_ALL & ~ E_NOTICE);

$sstrScriptName  = strtolower($_SERVER["SCRIPT_NAME"]);
$sstrSiteRootDir = str_replace("\\\\", "\\", $_SERVER["PATH_TRANSLATED"]);
$sstrDomain      = strtolower($_SERVER["SERVER_NAME"]);

print "<br />".$sstrScriptName;
print "<br />".$sstrSiteRootDir;
print "<br />".$sstrDomain;
print "<br />realpath=".realpath("/Config.inc.php");

print "<br /><b>Constants</b>";
print "<br />DIRECTORY_SEPARATOR=".DIRECTORY_SEPARATOR;
print "<br />PHP_SHLIB_SUFFIX=".PHP_SHLIB_SUFFIX;
print "<br />PATH_SEPARATOR=".PATH_SEPARATOR;

print "<br /><br /><b>PHP_</b>";
print "<br />PHP_VERSION=".PHP_VERSION;
print "<br />DEFAULT_INCLUDE_PATH=".DEFAULT_INCLUDE_PATH;
print "<br />PHP_BINDIR=".PHP_BINDIR;
print "<br />PHP_LIBDIR=".PHP_LIBDIR;
print "<br />PHP_SYSCONFDIR=".PHP_SYSCONFDIR;
print "<br />PHP_EXTENSION_DIR=".PHP_EXTENSION_DIR;
print "<br />PHP_CONFIG_FILE_PATH=".PHP_CONFIG_FILE_PATH;

print "<br />_SERVER";
print "<br />SystemDrive=".$_SERVER["SystemDrive"];
print "<br />SERVER_NAME=".$_SERVER["SERVER_NAME"];
print "<br />DOCUMENT_ROOT=".$_SERVER["DOCUMENT_ROOT"];
print "<br />SERVER_ADMIN=".$_SERVER["SERVER_ADMIN"];
print "<br />SERVER_PORT=".$_SERVER["SERVER_PORT"];
print "<br />PATH_TRANSLATED=".$_SERVER["PATH_TRANSLATED"];
print "<br />PHP_SELF=".$_SERVER["PHP_SELF"];
print "<br />HTTP_HOST=".$_SERVER["HTTP_HOST"];
print "<br />REQUEST_URI=".$_SERVER["REQUEST_URI"];

print "<br /><br /><b>_ENV</b>";
print "<br />SCRIPT_FILENAME=".$_ENV["SCRIPT_FILENAME"];
print "<br />ORIG_PATH_INFO=".$_ENV["ORIG_PATH_INFO"];
print "<br />ORIG_PATH_TRANSLATED=".$_ENV["ORIG_PATH_TRANSLATED"];
print "<br />DOCUMENT_ROOT=".$_ENV["DOCUMENT_ROOT"];

print "<br />__";
print "<br />FILE=".dirname(__FILE__)
?>