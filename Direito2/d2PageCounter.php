<?php
require_once('config.inc.php');
require_once('d2lib.inc.php');

myConnect();

$sql = 'UPDATE tb_noticia
				SET qt_visualizacao = qt_visualizacao + 1
				, dt_visualizacao = NOW()
				WHERE cd_noticia = '.gId;

myQuery($sql);

if (mysql_affected_rows()) {
	$sql = 'UPDATE tb_noticia_visualizacoes
					SET nm_quantidade = nm_quantidade + 1
					WHERE cd_noticia = '.gId.' AND nm_day_of_year = DAYOFYEAR(NOW()) AND nm_hora = HOUR(NOW())';
	
	myQuery($sql);
	
	if (!mysql_affected_rows()) {
		// cd_noticia, nm_ano, nm_mes, nm_dia, nm_hora, nm_quarter, nm_day_of_year, nm_week, nm_week_day, nm_quantidade		
		$sql = 'INSERT INTO tb_noticia_visualizacoes
						(cd_noticia, nm_ano, nm_mes, nm_dia, nm_hora, nm_quarter, nm_day_of_year, nm_week, nm_week_day, nm_quantidade) VALUES
						('.gId.', YEAR(NOW()), MONTH(NOW()), DAY(NOW()), HOUR(NOW()), QUARTER(NOW()), DAYOFYEAR(NOW()), WEEKOFYEAR(NOW()), DAYOFWEEK(NOW()), 1)';
		
		myQuery($sql);
	
	}
}

$sql = 'SELECT qt_visualizacao
				FROM tb_noticia
				WHERE cd_noticia = '.gId;

$pageCounter = myQuery($sql);

if ($pcRow = mysql_fetch_object($pageCounter)) {
	echo "pcVisualizacoes = $pcRow->qt_visualizacao;";

}

mysql_free_result($pageCounter);

myDisconnet();
