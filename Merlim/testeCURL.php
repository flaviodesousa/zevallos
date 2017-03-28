<?php
    
	$ch = curl_init();
	
	// informar URL e outras funções ao CURL
	curl_setopt($ch, CURLOPT_URL, "http://www.google.com/");

	// Acessar a URL e enviá-la ao browser
	curl_exec($ch);
?>
