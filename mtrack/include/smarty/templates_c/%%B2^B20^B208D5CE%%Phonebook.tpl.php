<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:49
         compiled from Phonebook.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'Phonebook.tpl', 111, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '
<style type="text/css">
    .submit_button{
        background:transparent url(../images/link-btn.gif) no-repeat scroll 0 0;
        color:#FFFFFF;
        display:block;
        font-weight:bold;
        height:23px;
        line-height:23px;
        margin-top:10px;
        text-align:center;
        width:129px;
        text-decoration:none;
        margin-left:25px;
    }
</style>
'; ?>


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
    function windowonload() { document.search_form.search_text.focus(); }
    // -->
    '; ?>

</script>

<div id="content">
        <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application740']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application741']; ?>
</p>
    </div>


    <div id="primary" class="info-cnt tuneddivs">
        <form action='Phonebook.php' method='POST' name='searchform' class="settings">

            <p>
                <label style="width:105px; clear:none; float:left;"><?php echo $this->_tpl_vars['Application488']; ?>
</label>
                <input style="float:left; margin-left:15px !important; margin-right:15px !important;" type='text' maxlength='100' size='30' class='text' id='search' name='search' value='<?php echo $this->_tpl_vars['search']; ?>
'/>
            </p>
            
            <p id='suggest' class='suggest' style="padding-left:125px;"></p>

            <p class="line">&nbsp;</p>
            <div class="submits" style="margin-left:20px !important;">
                <label><input type="submit" value="<?php echo $this->_tpl_vars['Application489']; ?>
"/></label>
                <label><input type="button" class="submit_button" value="Cancel" onclick="location.href='UserFriends.php'"/></label>
            </div>


            <input type='hidden' name='s' value='<?php echo $this->_tpl_vars['s']; ?>
'>
            <input type='hidden' name='p' value='<?php echo $this->_tpl_vars['p']; ?>
'>
        </form>

    </div>

    <br/>
    <p style="padding-left:25px;"><b>Search results</b></p>
    <div id="primary" class="info-cnt tuneddivs" style="color:#666666;">
        <form action="" method="post" class="settings" style="margin-top:0px !important;">
            <p class="line">&nbsp;</p>


                        <?php if ($this->_tpl_vars['total_friends'] == 0 && $this->_tpl_vars['search'] == ""): ?>
            <br/>
            <p align="center" style="color:red;">
                <?php echo $this->_tpl_vars['Application487']; ?>

            </p>
            <br/>
            <?php endif; ?>

                        <?php if ($this->_tpl_vars['total_friends'] == 0): ?>

                        <?php if ($this->_tpl_vars['search'] != ""): ?>
            <br/>
            <p align="center" style="color:red;">
                <?php echo $this->_tpl_vars['Application494']; ?>

            </p>
            <br/>
            <?php endif; ?>

                        <?php else: ?>

            
                        <?php if ($this->_tpl_vars['maxpage'] > 1): ?>
            <p>
                <?php if ($this->_tpl_vars['p'] != 1): ?><a href='Phonebook.php?s=<?php echo $this->_tpl_vars['s']; ?>
&search=<?php echo $this->_tpl_vars['search']; ?>
&p=<?php echo smarty_function_math(array('equation' => 'p-1','p' => $this->_tpl_vars['p']), $this);?>
'>&#171; <?php echo $this->_tpl_vars['Application495']; ?>
</a><?php else: ?><font class='disabled'>&#171; <?php echo $this->_tpl_vars['Application496']; ?>
</font><?php endif; ?>
                <?php if ($this->_tpl_vars['p_start'] == $this->_tpl_vars['p_end']): ?>
                &nbsp;|&nbsp; <?php echo $this->_tpl_vars['Application496']; ?>
 <?php echo $this->_tpl_vars['p_start']; ?>
 <?php echo $this->_tpl_vars['Application498']; ?>
 <?php echo $this->_tpl_vars['total_friends']; ?>
 &nbsp;|&nbsp;
                <?php else: ?>
                &nbsp;|&nbsp; <?php echo $this->_tpl_vars['Application497']; ?>
 <?php echo $this->_tpl_vars['p_start']; ?>
-<?php echo $this->_tpl_vars['p_end']; ?>
 <?php echo $this->_tpl_vars['Application498']; ?>
 <?php echo $this->_tpl_vars['total_friends']; ?>
 &nbsp;|&nbsp;
                <?php endif; ?>
                <?php if ($this->_tpl_vars['p'] != $this->_tpl_vars['maxpage']): ?><a href='Phonebook.php?s=<?php echo $this->_tpl_vars['s']; ?>
&search=<?php echo $this->_tpl_vars['search']; ?>
&p=<?php echo smarty_function_math(array('equation' => 'p+1','p' => $this->_tpl_vars['p']), $this);?>
'><?php echo $this->_tpl_vars['Application499']; ?>
 &#187;</a><?php else: ?><font class='disabled'><?php echo $this->_tpl_vars['Application499']; ?>
 &#187;</font><?php endif; ?>
            </p>
            <?php endif; ?>

            <?php unset($this->_sections['friend_loop']);
$this->_sections['friend_loop']['name'] = 'friend_loop';
$this->_sections['friend_loop']['loop'] = is_array($_loop=$this->_tpl_vars['friends']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['friend_loop']['show'] = true;
$this->_sections['friend_loop']['max'] = $this->_sections['friend_loop']['loop'];
$this->_sections['friend_loop']['step'] = 1;
$this->_sections['friend_loop']['start'] = $this->_sections['friend_loop']['step'] > 0 ? 0 : $this->_sections['friend_loop']['loop']-1;
if ($this->_sections['friend_loop']['show']) {
    $this->_sections['friend_loop']['total'] = $this->_sections['friend_loop']['loop'];
    if ($this->_sections['friend_loop']['total'] == 0)
        $this->_sections['friend_loop']['show'] = false;
} else
    $this->_sections['friend_loop']['total'] = 0;
if ($this->_sections['friend_loop']['show']):

            for ($this->_sections['friend_loop']['index'] = $this->_sections['friend_loop']['start'], $this->_sections['friend_loop']['iteration'] = 1;
                 $this->_sections['friend_loop']['iteration'] <= $this->_sections['friend_loop']['total'];
                 $this->_sections['friend_loop']['index'] += $this->_sections['friend_loop']['step'], $this->_sections['friend_loop']['iteration']++):
$this->_sections['friend_loop']['rownum'] = $this->_sections['friend_loop']['iteration'];
$this->_sections['friend_loop']['index_prev'] = $this->_sections['friend_loop']['index'] - $this->_sections['friend_loop']['step'];
$this->_sections['friend_loop']['index_next'] = $this->_sections['friend_loop']['index'] + $this->_sections['friend_loop']['step'];
$this->_sections['friend_loop']['first']      = ($this->_sections['friend_loop']['iteration'] == 1);
$this->_sections['friend_loop']['last']       = ($this->_sections['friend_loop']['iteration'] == $this->_sections['friend_loop']['total']);
?>
                        <div class="row" <?php if ($this->_sections['friend_loop']['last']): ?>style="border:none;"<?php endif; ?>>
                 <div class="f-right">

                </div>

                <a class="f-left" href="<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']); ?>
"><img src="<?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_photo('./images/nophoto.gif'); ?>
" class='img' width="92px" alt="<?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']; ?>
"/></a>
                <dl style="width:380px! important;">
                    <dt style="width:50px !important;">Name:</dt>
                    <dd><a href="<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']); ?>
"><b><?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']; ?>
</b></a></dd>

                    <?php if ($this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_phone']): ?>
                    <dt style="width:50px !important;">Phone:</dt>
                    <dd><?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_phone']; ?>
</dd>
                    <?php endif; ?>



                </dl>
            </div>
            <?php endfor; endif; ?>
            <?php endif; ?>

        </form>
    </div>


    <div class="block-bot"><span></span></div>
</div>
<div id="sidebar">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'MenuSidebar.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>