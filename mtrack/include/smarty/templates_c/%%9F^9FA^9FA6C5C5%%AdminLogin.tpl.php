<?php /* Smarty version 2.6.14, created on 2011-04-24 04:54:22
         compiled from AdminLogin.tpl */ ?>
<html>
<head>
<title><?php echo $this->_tpl_vars['Admin543']; ?>
</title>

<?php echo '
<style type=\'text/css\'>
td.box {
	padding: 15px;
	background: #FFFFFF;
	font-family: "Trebuchet MS", tahoma, verdana, serif;
	font-size: 9pt;
}
td.login {
	font-family: "Trebuchet MS", tahoma, verdana, serif;
	font-size: 9pt;
}
input.text {
	font-family: arial, tahoma, verdana, serif;
	font-size: 9pt; 
}
div.error {
	text-align: center;
	padding-top: 3px;
	font-weight: bold;
}
input.button {
	font-family: arial, tahoma, verdana, serif;
	font-size: 9pt;
	background: #DDDDDD;
	padding: 2px;
	font-weight: bold;
}
a:link { color: #336699; }
a:visited { color: #336699; }
a:hover { color: #336699; }
</style>
'; ?>


</head>
<body>

<table height='100%' width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
	<table cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td class='box' align="center">
        <h1 style="text-align: center"><img src="../images/PHPsocialAdminWhite.png" alt="Admin Login"></h1>
        <?php if ($this->_tpl_vars['validate'] == 0): ?>
        <span style="font-size: 90%">Please visit the site <a target="_blank" href="http://phpsocial.net/clientarea/login">phpsocial.net</a>, register and get a free key to phpsocial</span>
        <?php endif; ?>
		<table cellpadding='5' cellspacing='0'>
		<form action='AdminLogin.php' name='login' method='POST'>
        <?php if ($this->_tpl_vars['validate'] == 0): ?>
            <tr>
                <td class='login'>Insert key &nbsp;</td>
                <td class='login'><input type='text' class='text' name='key' maxlength='50'> &nbsp;
                    <input type='submit' class='button' value='Continue'></td>
            </tr>
            <input type='hidden' name='task' value='dovalidate'>
        <?php else: ?>
		<tr>
		<td class='login'><?php echo $this->_tpl_vars['Admin544']; ?>
 &nbsp;</td>
		<td class='login'><input type='text' class='text' name='username' maxlength='50'> &nbsp;</td>
		</tr><tr>
		<td class='login'><?php echo $this->_tpl_vars['Admin545']; ?>
 &nbsp;</td>
		<td class='login'><input type='password' class='text' name='password' maxlength='50'> &nbsp;</td>
		</tr><tr>
		<td colspan="2" class='login' align="center"><input type='submit' class='button' value='<?php echo $this->_tpl_vars['Admin546']; ?>
'></td>
		</tr>
		<input type='hidden' name='task' value='dologin'>
		<NOSCRIPT><input type='hidden' name='javascript' value='no'></NOSCRIPT>
        <?php endif; ?>
		</form>
		</table>
	<div class='error'><?php echo $this->_tpl_vars['error_message']; ?>
</div>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>


<?php echo '


<script language="JavaScript">
<!--

function SymError() { return true; }
window.onerror = SymError;
var SymRealWinOpen = window.open;
function SymWinOpen(url, name, attributes) { return (new Object()); }
window.open = SymWinOpen;
appendEvent = function(el, evname, func) {
 if (el.attachEvent) { // IE
   el.attachEvent(\'on\' + evname, func);
 } else if (el.addEventListener) { // Gecko / W3C
   el.addEventListener(evname, func, true);
 } else {
   el[\'on\' + evname] = func;
 }
};
appendEvent(window, \'load\', windowonload);
function windowonload() { document.login.username.focus(); } 
// -->
</script>

</body>
</html>

'; ?>