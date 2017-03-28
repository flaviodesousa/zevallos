<?PHP

echo "<br />php_uname('n')=".php_uname('n');

echo "<br />strtolower(PHP_OS)=".strtolower(PHP_OS);

$arrPathInfo = pathinfo(__FILE__);

$sstrSiteRootDir = $arrPathInfo["dirname"].DIRECTORY_SEPARATOR;
$sstrSiteRootDir = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR;
$sstrSiteRootDir = preg_replace('%[/\\\\]+%', '/', $sstrSiteRootDir);

echo "<br />sstrSiteRootDir=$sstrSiteRootDir";

?>