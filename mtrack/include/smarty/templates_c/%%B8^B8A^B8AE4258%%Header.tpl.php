<?php /* Smarty version 2.6.14, created on 2011-04-24 04:54:24
         compiled from Header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'array', 'Header.tpl', 43, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/DTD/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $this->_tpl_vars['global_page_title']; ?>
</title>
    <base href='<?php echo $this->_tpl_vars['baseurl']; ?>
' />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link href="templates/reset_client.css" rel="stylesheet" type="text/css" />
    <link href="templates/main_client.css" rel="stylesheet" type="text/css" />
	
    <script type="text/javascript" src="include/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="include/js/jquery-ui-1.7.2.custom.min.js"></script>
	<script src="include/js/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="include/js/jquery.kwicks-1.5.1.pack.js" type="text/javascript"></script>
    <script type="text/javascript" src="include/js/jquery.accordion.js"></script>
    <script type="text/javascript" src="include/js/main.js"></script>

	<!--[if lt IE 7]><script type="text/javascript" src="include/js/unitpngfix.js"></script><![endif]-->

    <!--[if gte IE 5.5]>
        <![if lte IE 6]>
        <iframe id="shim" src="" style="position:absolute;display:none;filter:progid:DXImageTransform.Microsoft.Chroma(Color='#ffffff');" scrolling="no" frameborder="0"></iframe>
        <iframe id="shim2" src="" style="position:absolute;display:none;filter:progid:DXImageTransform.Microsoft.Chroma(Color='#ffffff');" scrolling="no" frameborder="0"></iframe>
        <![endif]>
    <![endif]-->

        <script type="text/javascript" src="include/js/tips.js"></script>
    <script type="text/javascript" src="include/js/showhide.js"></script>
    <script type="text/javascript" src="include/js/suggest.js"></script>
    <script type="text/javascript" src="include/js/status.js"></script>

        <?php unset($this->_sections['header_loop']);
$this->_sections['header_loop']['name'] = 'header_loop';
$this->_sections['header_loop']['loop'] = is_array($_loop=$this->_tpl_vars['global_plugins']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['header_loop']['show'] = true;
$this->_sections['header_loop']['max'] = $this->_sections['header_loop']['loop'];
$this->_sections['header_loop']['step'] = 1;
$this->_sections['header_loop']['start'] = $this->_sections['header_loop']['step'] > 0 ? 0 : $this->_sections['header_loop']['loop']-1;
if ($this->_sections['header_loop']['show']) {
    $this->_sections['header_loop']['total'] = $this->_sections['header_loop']['loop'];
    if ($this->_sections['header_loop']['total'] == 0)
        $this->_sections['header_loop']['show'] = false;
} else
    $this->_sections['header_loop']['total'] = 0;
if ($this->_sections['header_loop']['show']):

            for ($this->_sections['header_loop']['index'] = $this->_sections['header_loop']['start'], $this->_sections['header_loop']['iteration'] = 1;
                 $this->_sections['header_loop']['iteration'] <= $this->_sections['header_loop']['total'];
                 $this->_sections['header_loop']['index'] += $this->_sections['header_loop']['step'], $this->_sections['header_loop']['iteration']++):
$this->_sections['header_loop']['rownum'] = $this->_sections['header_loop']['iteration'];
$this->_sections['header_loop']['index_prev'] = $this->_sections['header_loop']['index'] - $this->_sections['header_loop']['step'];
$this->_sections['header_loop']['index_next'] = $this->_sections['header_loop']['index'] + $this->_sections['header_loop']['step'];
$this->_sections['header_loop']['first']      = ($this->_sections['header_loop']['iteration'] == 1);
$this->_sections['header_loop']['last']       = ($this->_sections['header_loop']['iteration'] == $this->_sections['header_loop']['total']);
?>
        <?php ob_start(); ?>../plugins/<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['header_loop']['index']]; ?>
/templates/Header<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['header_loop']['index']]; ?>
.tpl<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('pltpl1', ob_get_contents());ob_end_clean(); ?>
        <?php ob_start(); ?>../../<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['header_loop']['index']]; ?>
/templates/Header<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['header_loop']['index']]; ?>
.tpl<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('pltpl2', ob_get_contents());ob_end_clean(); ?>
        <?php if ($this->_tpl_vars['location'] == 'plugins' || $this->_tpl_vars['location'] == 'pluginsfront'): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['pltpl2'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else: ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['pltpl1'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    <?php endfor; endif; ?>
    <?php echo smarty_function_array(array('var' => 'global_plugin_menu','value' => ''), $this);?>

    
</head>
<body>
<div id="container">
<div id="header">
    <div id="quickSearch">
        <form action='Search.php' method='get'>
            <div id="qquery">
                <input value="<?php echo $this->_tpl_vars['Application4']; ?>
" onBlur="<?php echo 'if(document.getElementById(\'qinput\').value ==\'\'){document.getElementById(\'qinput\').value=\'';  echo $this->_tpl_vars['Application4'];  echo '\'} document.getElementById(\'list\').style.dispay=\'none\';  document.getElementById(\'qchoose\').style.dispay=\'none\';'; ?>
" onFocus="document.getElementById('qinput').value=''" id="qinput"/>
                <span id="qchoose">&nbsp;</span>
            </div>
            <div id="qfriends">
                <div class="selected-cat">
                    <a href="#"><?php echo $this->_tpl_vars['Application69']; ?>
</a>
                </div>
                <input type="text" name="search_text" value="Search"/>
            </div>
            <ul class="src-cats" id="list">
                <li><a href="#" class="active"><?php echo $this->_tpl_vars['Application69']; ?>
</a></li>
                <?php unset($this->_sections['menu_loop']);
$this->_sections['menu_loop']['name'] = 'menu_loop';
$this->_sections['menu_loop']['loop'] = is_array($_loop=$this->_tpl_vars['global_plugins_search']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['menu_loop']['show'] = true;
$this->_sections['menu_loop']['max'] = $this->_sections['menu_loop']['loop'];
$this->_sections['menu_loop']['step'] = 1;
$this->_sections['menu_loop']['start'] = $this->_sections['menu_loop']['step'] > 0 ? 0 : $this->_sections['menu_loop']['loop']-1;
if ($this->_sections['menu_loop']['show']) {
    $this->_sections['menu_loop']['total'] = $this->_sections['menu_loop']['loop'];
    if ($this->_sections['menu_loop']['total'] == 0)
        $this->_sections['menu_loop']['show'] = false;
} else
    $this->_sections['menu_loop']['total'] = 0;
if ($this->_sections['menu_loop']['show']):

            for ($this->_sections['menu_loop']['index'] = $this->_sections['menu_loop']['start'], $this->_sections['menu_loop']['iteration'] = 1;
                 $this->_sections['menu_loop']['iteration'] <= $this->_sections['menu_loop']['total'];
                 $this->_sections['menu_loop']['index'] += $this->_sections['menu_loop']['step'], $this->_sections['menu_loop']['iteration']++):
$this->_sections['menu_loop']['rownum'] = $this->_sections['menu_loop']['iteration'];
$this->_sections['menu_loop']['index_prev'] = $this->_sections['menu_loop']['index'] - $this->_sections['menu_loop']['step'];
$this->_sections['menu_loop']['index_next'] = $this->_sections['menu_loop']['index'] + $this->_sections['menu_loop']['step'];
$this->_sections['menu_loop']['first']      = ($this->_sections['menu_loop']['iteration'] == 1);
$this->_sections['menu_loop']['last']       = ($this->_sections['menu_loop']['iteration'] == $this->_sections['menu_loop']['total']);
?>
                <?php if ($this->_tpl_vars['global_plugins_search'][$this->_sections['menu_loop']['index']] != ''): ?>
                <li><a href='#' alt="<?php echo $this->_tpl_vars['global_plugins_search'][$this->_sections['menu_loop']['index']]; ?>
"><?php echo $this->_tpl_vars['global_plugins_search'][$this->_sections['menu_loop']['index']]; ?>
s</a></li>
                <?php endif; ?>
                <?php endfor; endif; ?>
            </ul>
            <input type='hidden' name='task' value='dosearch' />
            <input type='hidden' id="t" name='t' value='0' />
            <input type="submit" style="position: absolute; top: -100px"/>
        </form>
    </div>

 

    <a href="/" id="logo"></a>
    <ul id="nav">
                        <?php if ($this->_tpl_vars['user']->user_exists != 0): ?>
         <li class="cat_item"><a href="UserLogout.php"><?php echo $this->_tpl_vars['Application8']; ?>
</a></li>

                <?php else: ?>
        <li class="cat_item"><a href="Login.php"><?php echo $this->_tpl_vars['Application10']; ?>
</a></li>
        <li class="cat_item"><a href="Signup.php"><?php echo $this->_tpl_vars['Application9']; ?>
</a><span>|</span></li>
        <?php endif; ?>

        <li class="cat_item"><a href="Help.php"><?php echo $this->_tpl_vars['Application6']; ?>
</a><span>|</span></li>
        <?php if ($this->_tpl_vars['setting']['setting_permission_invite']): ?>
            <li class="cat_item"><a href="Invite.php"><?php echo $this->_tpl_vars['Application5']; ?>
</a><span>|</span></li>
        <?php endif; ?>
        <?php if (( ! ( $this->_tpl_vars['setting']['setting_news_page'] == 0 && ! $this->_tpl_vars['user']->user_exists ) )): ?>
        <li class="cat_item"><a href="News.php"><?php echo $this->_tpl_vars['Application743']; ?>
</a><span>|</span></li>
        <?php endif; ?>
        <li class="cat_item"><a href="Search.php"><?php echo $this->_tpl_vars['Application4']; ?>
</a><span>|</span></li>
        <li class="cat_item"><a href="Home.php"><?php echo $this->_tpl_vars['Application3']; ?>
</a><span>|</span></li>
    </ul>
</div>

<div id="wrapper">
<?php if ($this->_tpl_vars['ads']->ad_top != ""): ?><div class='ad_top' style='display: block; visibility: visible;text-align: center;float:right;padding:5px;'><?php echo $this->_tpl_vars['ads']->ad_top; ?>
</div><?php endif; ?>