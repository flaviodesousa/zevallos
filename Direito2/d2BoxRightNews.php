<?php
require_once('config.inc.php');
require_once('d2lib.inc.php');

if (gSource) $sourceSG = ' AND LOWER(REPLACE(sg_fonte, \' \', \'\')) = \''.gSource.'\'';

$sql = "SELECT SQL_CACHE nm_noticia 
				, SUBSTRING(nh.ds_url, 1,  LENGTH(nh.ds_url) - 4) ds_url
				, nm_fonte
				, sg_fonte
				, nm_mes
				, sg_mes3
				, DATE_FORMAT(dt_referencia, '%Y-%m-%d %H:%i:%s') dt_referenciah
				, DATE_FORMAT(dt_referencia, '%d/%m/%Y %H:%i:%s') dt_referenciap
				, dt_referencia_day dt_day
				, dt_referencia_month dt_month
				, dt_referencia_year dt_year
				FROM tb_noticia n
				LEFT JOIN tb_mes ON cd_mes = dt_referencia_month
				LEFT JOIN tb_fonte f ON n.cd_fonte = f.cd_fonte
				LEFT JOIN tb_noticia_hash nh ON n.cd_noticia_hash = nh.cd_noticia_hash
				WHERE n.fl_ativo = 1 AND dt_referencia <= NOW()$sourceSG
				ORDER BY dt_referencia DESC
				LIMIT 8";

if (!$myConn) {
	myConnect();
	$blnAux = 1;
}

$listNews = myQuery($sql);

if ($lnRow = mysql_fetch_object($listNews)) {
  if (gSource) {
  	$sourceName = "$lnRow->sg_fonte - ";
  	$sourceTitle = " title=\"&Uacute;ltimas not&iacute;cias do $lnRow->sg_fonte - ".accentString2Latin1($lnRow->nm_fonte)."\"";
  } 
  
	echo "<h6$sourceTitle>$sourceName&Uacute;ltimas not&iacute;cias</h6>";
	
	echo '<ul>';
	
	$humandDate = dateHumanized($lnRow->dt_referenciah);
	
	echo "<li><a href=\"$lnRow->ds_url\" title=\"Not&iacute;cia publicada em $lnRow->dt_day de $lnRow->nm_mes de $lnRow->dt_year\"><strong>A ".accentString2Latin1($humandDate)."</strong>".accentString2Latin1($lnRow->nm_noticia, 1)."</a></li>";

	while ($lnRow = mysql_fetch_object($listNews)) {
		$humandDate = dateHumanized($lnRow->dt_referenciah);
		
		echo "<li><a href=\"$lnRow->ds_url\" title=\"Not&iacute;cia publicada em $lnRow->dt_day de $lnRow->nm_mes de $lnRow->dt_year\"><strong>A ".accentString2Latin1($humandDate)."</strong>".accentString2Latin1($lnRow->nm_noticia, 1)."</a></li>";
	}
	
	echo '</ul>';
}

if ($blnAux) myDisconnet();
