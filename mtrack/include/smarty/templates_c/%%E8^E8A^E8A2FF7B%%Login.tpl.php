<?php /* Smarty version 2.6.14, created on 2011-04-24 05:01:31
         compiled from Login.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="content">
    <?php echo '
<script type="text/javascript">
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
    function windowonload() {
        if(document.login.email.value == "") {
            document.login.email.focus();
        } else {
            document.login.password.focus();
        }
    }
    // -->
</script>
'; ?>


    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application172']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application177']; ?>
</p>
    </div>

    <?php if ($this->_tpl_vars['setting']['setting_signup_verify'] == 1): ?><p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application170']; ?>
</p><?php endif; ?>

        <?php if ($this->_tpl_vars['error_message'] != ""): ?>
    <br/>
    <p style="color:red; padding-left:25px;"><?php echo $this->_tpl_vars['error_message']; ?>
</p>
    <br/>
    <?php endif; ?>

        <form action='Login.php' method='post' name='login' class="settings">

            <div><b><?php echo $this->_tpl_vars['Application173']; ?>
</b></div>
            <div class='form_desc'></div>
            <input style="width:235px !important; border:1px solid #CBD0D2;"  type='text' class='text' name='email' value='<?php echo $this->_tpl_vars['email']; ?>
'/>
            <br/><br/>

            <div><b><?php echo $this->_tpl_vars['Application174']; ?>
</b></div>
            <div class='form_desc'></div>
            <input style="width:235px !important; border:1px solid #CBD0D2;" type='password' class='text' name='password' size='30' maxlength='50'/>
            <br/><br/>

            <label style="width:90px;"><?php echo $this->_tpl_vars['Application171']; ?>
</label>
            <input style="border:none; width:15px !important; height:15px !important; float:left !important; margin-left:5px;" type='checkbox' name='persistent' id='persistent' value='1'/>
            &nbsp; <a href='Lostpass.php'><?php echo $this->_tpl_vars['Application176']; ?>
</a>

            <p class="line">&nbsp;</p>
            <div class="submits" style="margin-left:0px !important;">
                <label><input style="width:92px !important; width:100px !important;" type="submit" value="<?php echo $this->_tpl_vars['Application10']; ?>
"/></label>
            </div>
            
            <NOSCRIPT><input type='hidden' name='javascript_disabled' value='1'/></NOSCRIPT>
            <input type='hidden' name='task' value='dologin'/>
            <input type='hidden' name='return_url' value='<?php echo $this->_tpl_vars['return_url']; ?>
'/>
        </form>

    <div class="block-bot"><span>&nbsp;</span></div>
</div>






<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>