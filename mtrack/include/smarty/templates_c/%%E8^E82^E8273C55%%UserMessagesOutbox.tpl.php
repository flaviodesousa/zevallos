<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:30
         compiled from UserMessagesOutbox.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'UserMessagesOutbox.tpl', 37, false),array('modifier', 'truncate', 'UserMessagesOutbox.tpl', 146, false),array('modifier', 'choptext', 'UserMessagesOutbox.tpl', 149, false),)), $this); ?>
<?php if (! $this->_tpl_vars['ajax_call']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="content">
    <?php echo '
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
        #content .row dd {
            width:210px !important;
        }
        #content .row dl {
            float:left;
            padding:0;
            width:320px;
        }
    </style>
    '; ?>


    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application682']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application683']; ?>
 <?php echo $this->_tpl_vars['total_pms']; ?>
 <?php echo $this->_tpl_vars['Application684']; ?>
</p>
                <?php if ($this->_tpl_vars['maxpage'] > 1): ?>
        <br/>
        <p class="blue">
            <?php if ($this->_tpl_vars['p'] != 1): ?><a href='UserMessagesOutbox.php?p=<?php echo smarty_function_math(array('equation' => 'p-1','p' => $this->_tpl_vars['p']), $this);?>
'>&#171; <?php echo $this->_tpl_vars['Application686']; ?>
</a><?php else: ?><font class='disabled'>&#171; <?php echo $this->_tpl_vars['Application686']; ?>
</font><?php endif; ?>
            <?php if ($this->_tpl_vars['p_start'] == $this->_tpl_vars['p_end']): ?>
            &nbsp;|&nbsp; <?php echo $this->_tpl_vars['Application687']; ?>
 <?php echo $this->_tpl_vars['p_start']; ?>
 <?php echo $this->_tpl_vars['Application689']; ?>
 <?php echo $this->_tpl_vars['total_pms']; ?>
 &nbsp;|&nbsp;
            <?php else: ?>
            &nbsp;|&nbsp; <?php echo $this->_tpl_vars['Application688']; ?>
 <?php echo $this->_tpl_vars['p_start']; ?>
-<?php echo $this->_tpl_vars['p_end']; ?>
 <?php echo $this->_tpl_vars['Application689']; ?>
 <?php echo $this->_tpl_vars['total_pms']; ?>
 &nbsp;|&nbsp;
            <?php endif; ?>
            <?php if ($this->_tpl_vars['p'] != $this->_tpl_vars['maxpage']): ?><a href='UserMessagesOutbox.php?p=<?php echo smarty_function_math(array('equation' => 'p+1','p' => $this->_tpl_vars['p']), $this);?>
'><?php echo $this->_tpl_vars['Application690']; ?>
 &#187;</a><?php else: ?><font class='disabled'><?php echo $this->_tpl_vars['Application690']; ?>
 &#187;</font><?php endif; ?>
        </p>

        <?php endif; ?>
    </div>
    <div class="layers">
        <ul class="list01">

            <li id="li1" <?php if ($this->_tpl_vars['page'] == 'UserMessages'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='javascript:void(0)' onclick="getData('#ajaxContainer','UserMessages.php'); setActiveLi(1);"><?php echo $this->_tpl_vars['Application641']; ?>
</a>
            </li>

            <li id="li2" <?php if ($this->_tpl_vars['page'] == 'UserMessagesOutbox'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='javascript:void(0)' onclick="getData('#ajaxContainer','UserMessagesOutbox.php'); setActiveLi(2);"><?php echo $this->_tpl_vars['Application642']; ?>
</a>
            </li>

            <li id="li3" <?php if ($this->_tpl_vars['page'] == 'UserMessagesSettings'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='javascript:void(0)' onclick="getData('#ajaxContainer','UserMessagesSettings.php'); setActiveLi(3);"><?php echo $this->_tpl_vars['Application662']; ?>
</a>
            </li>
        </ul>

<?php endif; ?>

        <div id="ajaxContainer">

        <p style="padding-left:25px;"><a href='UserMessagesNew.php'><?php echo $this->_tpl_vars['Application647']; ?>
</a></p>

                <?php if ($this->_tpl_vars['justsent'] == 1): ?>
        <p style="padding-left:25px; color:green;"><?php echo $this->_tpl_vars['Application648']; ?>
</p>
        <?php endif; ?>


                <?php echo '
        <script language=\'JavaScript\'>
            <!---
            var checkboxcount = 1;
            function doCheckAll() {
                if(checkboxcount == 0) {
                    with (document.messageform) {
                        for (var i=0; i < elements.length; i++) {
                            if (elements[i].type == \'checkbox\') {
                                elements[i].checked = false;
                            }}
                        checkboxcount = checkboxcount + 1;
                    }
                } else
                    with (document.messageform) {
                        for (var i=0; i < elements.length; i++) {
                            if (elements[i].type == \'checkbox\') {
                                elements[i].checked = true;
                            }}
                    checkboxcount = checkboxcount - 1;
                }
            }
            // -->
        </script>
        '; ?>




                <?php if ($this->_tpl_vars['total_pms'] == 0): ?>
        <br/>
        <p  align="center" style="color:red;"><?php echo $this->_tpl_vars['Application691']; ?>
</p>
        <br/>
        <br/>

        <?php else: ?>

        <div id="primary" class="info-cnt tuneddivs" style="color:#666666;">
            <form action="UserMessagesOutbox.php" method="post" name="messageform" class="settings">

                                <?php unset($this->_sections['pm_loop']);
$this->_sections['pm_loop']['name'] = 'pm_loop';
$this->_sections['pm_loop']['loop'] = is_array($_loop=$this->_tpl_vars['pms']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pm_loop']['show'] = true;
$this->_sections['pm_loop']['max'] = $this->_sections['pm_loop']['loop'];
$this->_sections['pm_loop']['step'] = 1;
$this->_sections['pm_loop']['start'] = $this->_sections['pm_loop']['step'] > 0 ? 0 : $this->_sections['pm_loop']['loop']-1;
if ($this->_sections['pm_loop']['show']) {
    $this->_sections['pm_loop']['total'] = $this->_sections['pm_loop']['loop'];
    if ($this->_sections['pm_loop']['total'] == 0)
        $this->_sections['pm_loop']['show'] = false;
} else
    $this->_sections['pm_loop']['total'] = 0;
if ($this->_sections['pm_loop']['show']):

            for ($this->_sections['pm_loop']['index'] = $this->_sections['pm_loop']['start'], $this->_sections['pm_loop']['iteration'] = 1;
                 $this->_sections['pm_loop']['iteration'] <= $this->_sections['pm_loop']['total'];
                 $this->_sections['pm_loop']['index'] += $this->_sections['pm_loop']['step'], $this->_sections['pm_loop']['iteration']++):
$this->_sections['pm_loop']['rownum'] = $this->_sections['pm_loop']['iteration'];
$this->_sections['pm_loop']['index_prev'] = $this->_sections['pm_loop']['index'] - $this->_sections['pm_loop']['step'];
$this->_sections['pm_loop']['index_next'] = $this->_sections['pm_loop']['index'] + $this->_sections['pm_loop']['step'];
$this->_sections['pm_loop']['first']      = ($this->_sections['pm_loop']['iteration'] == 1);
$this->_sections['pm_loop']['last']       = ($this->_sections['pm_loop']['iteration'] == $this->_sections['pm_loop']['total']);
?>

                                <?php if ($this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_status'] == 0): ?>
                <?php $this->assign('row_class', 'messages_unread'); ?>
                <?php else: ?>
                <?php $this->assign('row_class', 'messages_read'); ?>
                <?php endif; ?>



                                <div class="row" <?php if ($this->_sections['pm_loop']['last']): ?>style="border:none;"<?php endif; ?>>
                     <div class="f-right">
                       
                        <a href='UserMessagesView.php?pm_id=<?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_id']; ?>
&task=delete'><?php echo $this->_tpl_vars['Application660']; ?>
</a><br/>
                        <input type='checkbox' name='message_<?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_id']; ?>
' value='1' style="height:15px; width:15px;"/>
                    </div>

                    <a class="f-left" href="<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_user']->user_info['user_username']); ?>
"><img src="<?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_user']->user_photo('./images/nophoto.gif'); ?>
" class='img' width="92px" alt="<?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_user']->user_info['user_username']; ?>
 <?php echo $this->_tpl_vars['Application500']; ?>
"/></a>
                    <dl>
                        <dt><?php echo $this->_tpl_vars['Application655']; ?>
</dt>
                        <dd><b><a href="<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_user']->user_info['user_username']); ?>
"><?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_user']->user_info['user_username']; ?>
</a></b></dd>

                        <dt>Date posted:</dt>
                        <dd><?php echo $this->_tpl_vars['datetime']->cdate(($this->_tpl_vars['setting']['setting_timeformat'])." ".($this->_tpl_vars['setting']['setting_dateformat']),$this->_tpl_vars['datetime']->timezone($this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_date'],$this->_tpl_vars['global_timezone'])); ?>
</dd>

                        <dt><?php echo $this->_tpl_vars['Application656']; ?>
:</dt>
                        <dd><b><a href='UserMessagesView.php?pm_id=<?php echo $this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_id']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_subject'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</a></b></dd>

                        <dt>Message:</dt>
                        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['pms'][$this->_sections['pm_loop']['index']]['pm_body'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)))) ? $this->_run_mod_handler('choptext', true, $_tmp, 75, "<br>") : smarty_modifier_choptext($_tmp, 75, "<br>")); ?>
</dd>
                    </dl>
                </div>

                <?php endfor; endif; ?>

                <p align="center"><input type="checkbox" name="select_all" onClick="javascript:doCheckAll()" style="height:15px; width:15px;"/>select all</p>

                <p class="line">&nbsp;</p>
                <?php $this->assign('redirect_page', $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username'])); ?>
                <div class="f-left">

                    <a class="button" href="UserMessagesNew.php"><span><?php echo $this->_tpl_vars['Application647']; ?>
</span></a>


                    <a class="button" href="javascript:void(0)" onclick="document.messageform.submit();"><span><?php echo $this->_tpl_vars['Application697']; ?>
</span></a>
                    <input type='hidden' name='task' value='deleteselected'/>
                    <input type='hidden' name='p' value='<?php echo $this->_tpl_vars['p']; ?>
'/>

                </div>
            </form>
        </div>
        <?php endif; ?>
        
        </div>
        
<?php if (! $this->_tpl_vars['ajax_call']): ?>
        
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

<?php endif; ?>