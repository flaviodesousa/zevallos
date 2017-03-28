	function goLogin(params) {
		params += '&txtUsername='+document.getElementById('txtUsername').value;
		params += '&txtPassword='+md5(document.getElementById('txtPassword').value);
		ajaxLogin.goAjax(params);
	}

