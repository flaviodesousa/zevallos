<?php
header('Content-Type: '.$siteContentType.'; charset='.$siteCharSet, true);

echo '<?xml version="1.0" encoding="'.$siteEncoding.'"?'.'>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom"
xmlns:thr="http://purl.org/syndication/thread/1.0"
xml:lang="en"
xml:base="http://ruben.zevallos.com.br/wp-atom.php">
<title type="text"><?php echo $siteTitle; ?></title> 
<subtitle type="text"><?php echo $siteDescription; ?></subtitle> 
<updated>2011-05-17T19:39:00Z</updated>
<link rel="alternate" type="text/html" href="<?php echo $siteURL; ?>" /> 
<id><?php echo $siteURL; ?>/atom/</id>
<link rel="self" type="application/atom+xml" href="http://ruben.zevallos.com.br/feed/atom/" /> 
<generator uri="http://www.direito2.com.br/" version="1.0.0">Direito 2</generator>
 
<?php while ($jnRow = mysql_fetch_object($justicaNews)) { ?>
	<entry> 
	  <author><name><?php echo $siteCreator; ?></name></author>
		<title type="html"><![CDATA[<?php echo $jnRow->nm_noticia ?>]]></title>
	  <link rel="alternate" type="text/html" href="<?php echo $jnRow->ds_url ?>" /> 
	  <id><?php echo $jnRow->ds_url ?></id> 
	  <updated><?php echo date('Y-m-d\TH:i:s\z', strtotime($jnRow->dt_referenciah)); ?></updated> 
	  <published><?php echo date('Y-m-d\TH:i:s\z', strtotime($jnRow->dt_referenciah)); ?></published> 
	  <?php $siteCategoryAtom ?> 
	  <summary type="html"><![CDATA[<?php echo $jnRow->ds_noticia ?>]]></summary>
	  <link rel="replies" type="text/html" href="<?php echo $jnRow->ds_url ?>/#comments" thr:count="0"/>
	  <link rel="replies" type="application/atom+xml" href="<?php echo $jnRow->ds_url ?>/atom/" thr:count="0"/> 
	  <thr:total>0</thr:total> 
	</entry>
<?php } ?>
	
	</channel>
</rss>
