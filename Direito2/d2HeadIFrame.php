<?php

require_once('config.inc.php');
require_once('d2lib.inc.php');

require_once('d2GetIP.inc.php');

myConnect();

require_once('d2ShowClima.inc.php');

$sql = "SELECT SQL_CACHE 
				DATE_FORMAT(dt_referencia, '%Y-%m-%d %H:%i:%s') dt_referenciah
				FROM tb_noticia
				WHERE fl_ativo = 1 AND dt_referencia <= NOW() 
				ORDER BY dt_referencia DESC
				LIMIT 1";
					
$homeTempo = myQuery($sql);
	
require_once('d2HTMLEnd.inc.php');

echo "IP=", $_SERVER['REMOTE_ADDR'], "<br />";

// <iframe src="d2HeadIFrame.php" marginheight="0" marginwidth="0" frameborder="0" name="headIFrame"  scrolling="auto" width="462" height="250"></iframe>
        
	