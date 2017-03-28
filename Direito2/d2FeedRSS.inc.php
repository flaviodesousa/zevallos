<?php
header('Content-Type: '.$siteContentType.'; charset='.$siteCharSet, true);

echo '<?xml version="1.0" encoding="'.$siteEncoding.'"?'.'>'; ?>
<rss version="0.92">
	<channel>
		<title><?php echo $siteTitle; ?></title>
		<link><?php echo $siteURL; ?></link>
		<description><?php echo $siteDescription; ?></description>
		<lastBuildDate><?php echo date('D, d M Y H:i:s +0000'); ?></lastBuildDate>
		<docs>http://backend.userland.com/rss092</docs>
		<language><?php echo $siteLanguage; ?></language>
		<sy:updatePeriod>hourly</sy:updatePeriod> 
		<sy:updateFrequency>1</sy:updateFrequency> 
		<generator>Direito 2</generator>
		
		<?php while ($jnRow = mysql_fetch_object($justicaNews)) { ?>
		<item>
			<title><![CDATA[<?php echo $jnRow->nm_noticia ?>]]></title>
			<description><![CDATA[<?php echo $jnRow->ds_noticia ?>]]></description>
			<link><?php echo $jnRow->ds_url ?></link>
		  <pubDate><?php echo date('D, d M Y H:i:s +0000', strtotime($jnRow->dt_referenciah)); ?></pubDate> 
		  <dc:creator><?php echo $siteCreator; ?></dc:creator> 
		  <?php $siteCategory ?> 
			<comments><?php echo $jnRow->ds_url ?>/#comments</comments> 
		  <wfw:commentRss><?php echo $jnRow->ds_url ?>/feed/</wfw:commentRss>
		  <slash:comments>0</slash:comments> 
		  <guid isPermaLink="false"><?php echo $jnRow->ds_url ?></guid> 
		</item>
	<?php } ?>
	</channel>
</rss>
