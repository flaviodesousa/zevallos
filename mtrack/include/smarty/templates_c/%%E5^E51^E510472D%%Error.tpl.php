<?php /* Smarty version 2.6.14, created on 2011-04-24 04:54:23
         compiled from Error.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">
    <div class="grey-head"><h2><?php echo $this->_tpl_vars['error_header']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['error_message']; ?>
</p>
    </div>

    
    <br/><br/>
    <a style="padding-left: 20px;" href="javascript:void(0)" onClick='history.go(-1)'><?php echo $this->_tpl_vars['error_submit']; ?>
</a>
    <br/><br/>

    <div class="block-bot"><span></span></div>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>