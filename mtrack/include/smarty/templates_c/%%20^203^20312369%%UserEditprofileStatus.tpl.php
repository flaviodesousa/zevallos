<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:04
         compiled from UserEditprofileStatus.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="content">
    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application479']; ?>
</h2></div>

  <div class="layers">
        <ul class="list01">
            <?php unset($this->_sections['tab_loop']);
$this->_sections['tab_loop']['name'] = 'tab_loop';
$this->_sections['tab_loop']['loop'] = is_array($_loop=$this->_tpl_vars['tabs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['tab_loop']['show'] = true;
$this->_sections['tab_loop']['max'] = $this->_sections['tab_loop']['loop'];
$this->_sections['tab_loop']['step'] = 1;
$this->_sections['tab_loop']['start'] = $this->_sections['tab_loop']['step'] > 0 ? 0 : $this->_sections['tab_loop']['loop']-1;
if ($this->_sections['tab_loop']['show']) {
    $this->_sections['tab_loop']['total'] = $this->_sections['tab_loop']['loop'];
    if ($this->_sections['tab_loop']['total'] == 0)
        $this->_sections['tab_loop']['show'] = false;
} else
    $this->_sections['tab_loop']['total'] = 0;
if ($this->_sections['tab_loop']['show']):

            for ($this->_sections['tab_loop']['index'] = $this->_sections['tab_loop']['start'], $this->_sections['tab_loop']['iteration'] = 1;
                 $this->_sections['tab_loop']['iteration'] <= $this->_sections['tab_loop']['total'];
                 $this->_sections['tab_loop']['index'] += $this->_sections['tab_loop']['step'], $this->_sections['tab_loop']['iteration']++):
$this->_sections['tab_loop']['rownum'] = $this->_sections['tab_loop']['iteration'];
$this->_sections['tab_loop']['index_prev'] = $this->_sections['tab_loop']['index'] - $this->_sections['tab_loop']['step'];
$this->_sections['tab_loop']['index_next'] = $this->_sections['tab_loop']['index'] + $this->_sections['tab_loop']['step'];
$this->_sections['tab_loop']['first']      = ($this->_sections['tab_loop']['iteration'] == 1);
$this->_sections['tab_loop']['last']       = ($this->_sections['tab_loop']['iteration'] == $this->_sections['tab_loop']['total']);
?>
            <li <?php if ($this->_tpl_vars['uri_page'] == $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_id']): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='UserEditprofile.php?tab_id=<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_id']; ?>
'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_name']; ?>
</a>
            </li>
            <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_id'] == $this->_tpl_vars['tab_id']):  $this->assign('pagename', $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_name']);  endif; ?>
            <?php endfor; endif; ?>
            <?php if ($this->_tpl_vars['user']->level_info['level_profile_status'] != 0): ?> <li <?php if ($this->_tpl_vars['uri_page'] == 'UserEditprofileStatus.php'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>><a href='UserEditprofileStatus.php'><?php echo $this->_tpl_vars['Application419']; ?>
</a></li><?php endif; ?>
            <?php if ($this->_tpl_vars['user']->level_info['level_photo_allow'] != 0): ?> <li <?php if ($this->_tpl_vars['uri_page'] == 'UserEditprofilePhoto.php'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>><a href='UserEditprofilePhoto.php'><?php echo $this->_tpl_vars['Application420']; ?>
</a></li><?php endif; ?>
            <li <?php if ($this->_tpl_vars['uri_page'] == 'UserEditprofileSettings.php'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>><a href='UserEditprofileSettings.php'><?php echo $this->_tpl_vars['Application422']; ?>
</a></li>
        </ul>

        <div id="primary" class="info-cnt tuneddivs">

            <p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application480']; ?>
</p>

                         <?php if ($this->_tpl_vars['result'] != 0): ?>
            <p style="color:green; padding-left:25px;"> <?php echo $this->_tpl_vars['Application474']; ?>
</p>
            <?php endif; ?>

            <form action='UserEditprofileStatus.php' method='POST' name='profile' class="settings">


                <p>
                    <label><b><?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
 <?php echo $this->_tpl_vars['Application482']; ?>
</b></label>
                    <input type='text' class='text' name='status_new' size='50' maxlength='100' value='<?php echo $this->_tpl_vars['user']->user_info['user_status']; ?>
'/>
                </p>
                <p class="line">&nbsp;</p>

                <?php $this->assign('redirect_page', $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username'])); ?>
                
                <div class="submits">
                    <label><input type="submit" value="<?php echo $this->_tpl_vars['Application481']; ?>
"/></label>
                    <label><input type="button" onclick="location.href='<?php echo $this->_tpl_vars['redirect_page']; ?>
'" value="Cancel"/></label>
                </div>

                <input type='hidden' name='task' value='dosave'>
                <input type='hidden' name='return_url' value='<?php echo $this->_tpl_vars['return_url']; ?>
'>
            </form>
        </div>
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