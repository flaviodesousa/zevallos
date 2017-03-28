<?php
header('Content-Type: '.$siteContentType.'; charset='.$siteCharSet, true);

while ($jnRow = mysql_fetch_object($justicaNews)) {
	$rdfLI .= "<rdf:li rdf:resource=\"$jnRow->ds_url\"/>";

	$rdfItem .= "<item rdf:about=\"$jnRow->ds_url\">
							  <title>$jnRow->nm_noticia</title>
							  <link>$jnRow->ds_url</link> 
							  <dc:date>".date('Y-m-d\TH:i:s\Z', strtotime($jnRow->dt_referenciah))."</dc:date> 
							  <dc:creator>$siteCreator</dc:creator>
							  $siteCategoryRDF 
							  <description>$jnRow->ds_noticia</description> 
							</item>";	
}

echo '<?xml version="1.0" encoding="'.$siteEncoding.'"?'.'>'; ?>
<rdf:RDF
	xmlns="http://purl.org/rss/1.0/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel rdf:about="<?php echo $siteURL; ?>">
	<title><?php echo $siteTitle; ?></title>
	<link><?php echo $siteURL; ?></link>
	<description><?php echo $siteDescription; ?></description>
	<dc:date><?php echo date('Y-m-d\TH:i:s\Z', strtotime($jnRow->dt_referenciah)); ?></dc:date>
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
	<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>
	<admin:generatorAgent rdf:resource="http://www.direito2.com.br/" />
	<items>
		<rdf:Seq>
		<?php echo $rdfLI ?>
		</rdf:Seq>
	</items>
</channel>
<?php echo $rdfItem ?>

