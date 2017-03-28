    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
    function displayPosition(pos) {
    	var mylat = pos.coords.latitude;
    	var mylon = pos.coords.longitude;
	    
	    $(barraClima).load('/d2GetClima.php', {'lat':mylat,'lon':mylon});
    }
    
		function errorFunction(error) {
			switch(error.code) {
			  case error.TIMEOUT:
			  alert ('Timeout');
			  break;
			
			  case error.POSITION_UNAVAILABLE:
			  alert ('Posição indiponível');
			  break;
			
			  case error.PERMISSION_DENIED:
			  alert ('Permissão negada');
			  break;
			
			  case error.UNKNOWN_ERROR:
			  alert ('Erro desconhecido');
			  break;
			
			  default:
			  alert('Error!');
			}
	  }    
		</script>		

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">
		var barraClima   = '#climaPlace';
		var barraTempo   = '#tempoPlace';
		var boxRightNews = '#boxRightNews';

		<?php if ($homeJS) { ?>
		var containerId = '#noticias';
		var tabsId = '#abaDir';

		$(document).ready(function(){
			// Preload tab on page load
			if ($(tabsId + ' LI.sel A').length > 0){
				loadTab($(tabsId + ' LI.sel A'));
			}

	    $(tabsId + ' A').click(function(){
	    	if ($(this).parent().hasClass('sel')){ return false; }

	    	$(tabsId + ' LI.sel').removeClass('sel');
	    	$(this).parent().addClass('sel');

	    	loadTab($(this));
				return false;
	    });

	    $(barraTempo).load('/d2GetTempo.php');

	    if (navigator.geolocation) {
	    	navigator.geolocation.getCurrentPosition(displayPosition, errorFunction);
	    } else {
	    	$(barraClima).load('/d2GetClima.php');
	    	// alert('Parece que o sistema de localição geográfica que é requerido para esta página não está habilitado no seu navegador. Por favor, utilize um que suporte.');
	    }
		});

		function loadTab(tabObj){
	    if(!tabObj || !tabObj.length){ return; }
	    $(containerId).html('<img src="http://img.ecr.me/d2/loader.gif" class="loading" />');
	
	    $(containerId).load(tabObj.attr('href'), {'js':1});
		}
		<?php } else {
		if (gSource) $sourceJS = ", {'s':'".gSource."'}"; 
		?>
		$(document).ready(function(){
	    $(barraTempo).load('/d2GetTempo.php');
	    $(boxRightNews).load('/d2BoxRightNews.php'<?php echo $sourceJS ?>);
	    
	    if (navigator.geolocation) {
	    	navigator.geolocation.getCurrentPosition(displayPosition, errorFunction);
	    } else {
	    	$(barraClima).load('/d2GetClima.php');
	    	// alert('Parece que o sistema de localição geográfica que é requerido para esta página não está habilitado no seu navegador. Por favor, utilize um que suporte.');
	    }
		});
		<?php } ?>
		</script>
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script><script type="text/javascript">_uacct = "UA-414744-10";urchinTracker();</script>
		<script type="text/javascript">_qoptions={qacct:"p-dcqKWSeOuMsXw"};</script><script type="text/javascript" src="http://edge.quantserve.com/quant.js"></script><noscript><img src="http://pixel.quantserve.com/pixel/p-dcqKWSeOuMsXw.gif" style="display: none;" height="1" width="1" alt="Quantcast" /></noscript>
		<script id="aptureScript">(function (){var a=document.createElement("script");a.defer="true";a.src="http://www.apture.com/js/apture.js?siteToken=l6WNh2P";document.getElementsByTagName("head")[0].appendChild(a);})();</script>
		<script type="text/javascript" src="http://ads4333.hotwords.com.br/show.jsp?id=4333&cor=0e38fb"></script>
		<?php if ($snRow) {?>
			<script language="javascript" src="/d2PageCounter.php?id=<?php echo $snRow->cd_noticia ?>" type="text/javascript"></script>
		<?php } ?>
	</body>
