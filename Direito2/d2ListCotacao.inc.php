<h1><?php echo $titleH1 ?></h1>

<?php
$cotacaoMoedas = array('DolarMedio'
                     , 'DolarComercial'
										 , 'DolarParalelo'
										 , 'DolarTurismo'
										 , 'Euro'
										 , 'YenJapones'
										 , 'PesoArgentino'
										 , 'PesoChileno'
										 , 'BolivarVenezuelano'
										 , 'PesoMexicano'
										 , 'DolarCanadence'
										 , 'FrancoSuico'
										 , 'DolarHongKong'
										 , 'RupiaIndia'
										 , 'WonCoreiaSul');

echo "<ul class=\"calendar source\">
				<li class=\"titlex\">Cotações</li>";
										 
foreach ($cotacaoMoedas as $key => $value) {
	$fileName = DocumentRoot."/$value.json";
	$file = fopen($fileName, "r");
	$cotacao = fread($file, filesize($fileName));
	fclose($file);
	
	$json = json_decode($cotacao);
	
	$json = $json[0];
	
	echo "<li>$json->moeda <span id=\"climaRight\"></li>";
	
}

echo '</ul>';
