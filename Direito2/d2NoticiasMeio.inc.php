<?php 
	$i = 0;
	
	while ($jnRow = mysql_fetch_object($justicaNews)) {
		if ($i++ < 1)$buffer = "<ul><li class=\"title\"><a name=\"salto$jnRow->sg_fonte_cu\" href=\"#salto$jnRow->sg_fonte_cu\" title=\"Você está nas notícias do $jnRow->sg_fonte - $jnRow->nm_fonte\"></a><a href=\"/$jnRow->sg_fonte_cl\" title=\"Últimas Noticias do $jnRow->sg_fonte - $jnRow->nm_fonte\">$jnRow->sg_fonte - Últimas Noticias</a> <a href=\"/rss$jnRow->sg_fonte_cu"."Noticias.xml\" class=\"rss\">RSS</a></li>";
			
		$humandDate = dateHumanized($jnRow->dt_referenciah);
		
		$buffer .= "<li><a href=\"$jnRow->ds_url\" title=\"$jnRow->dt_referenciap - $jnRow->nm_noticia\">$jnRow->nm_noticia</a> a $humandDate</li>";
		
	}
	
	echo accentString2Latin1($buffer, 1)."</ul>";
