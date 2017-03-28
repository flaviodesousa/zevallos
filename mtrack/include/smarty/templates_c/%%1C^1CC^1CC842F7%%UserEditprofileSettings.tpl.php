<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:07
         compiled from UserEditprofileSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'UserEditprofileSettings.tpl', 53, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<style type="text/css">
    .settings input {
        width:auto !important;
        height:auto !important;
    }
    .submits input{
        width:92px !important;
        height:23px !important;
    }
    input{
        border:none !important;
    }
</style>
'; ?>

<div id="content">
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
    <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_id'] == $this->_tpl_vars['tab_id']):  $this->assign('pagename', $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_name']);  endif; ?>
    <?php endfor; endif; ?>
    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application458']; ?>
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
            <p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application459']; ?>
</p>
                        <?php if ($this->_tpl_vars['result'] != 0): ?>
            <br/>
            <p style="color:green; padding-left:25px;"><?php echo $this->_tpl_vars['Application453']; ?>
</p>
            <?php endif; ?>

            <form action='UserEditprofileSettings.php' method='POST' class="settings" style="color:#666666;">

                <?php if ($this->_tpl_vars['user']->level_info['level_profile_style'] == 1): ?>
                <div><b><?php echo $this->_tpl_vars['Application461']; ?>
</b></div>
                <div class='form_desc'><?php echo $this->_tpl_vars['Application462']; ?>
</div>
                <textarea style="border:1px solid #CBD0D2; width:600px;" name='style_profile' rows='17' cols='50' style='width: 100%; font-family: courier, serif;'><?php echo $this->_tpl_vars['style_profile']; ?>
</textarea>
                <br><br>
                <?php endif; ?>

                <?php if (count($this->_tpl_vars['privacy_profile_options']) > 1): ?>
                <div><b><?php echo $this->_tpl_vars['Application463']; ?>
</b></div>
                <div class='form_desc'><?php echo $this->_tpl_vars['Application464']; ?>
</div>
                <table border="0" cellpadding='0' cellspacing='0' class='editprofile_options'>
                                        <?php unset($this->_sections['privacy_profile_loop']);
$this->_sections['privacy_profile_loop']['name'] = 'privacy_profile_loop';
$this->_sections['privacy_profile_loop']['loop'] = is_array($_loop=$this->_tpl_vars['privacy_profile_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['privacy_profile_loop']['show'] = true;
$this->_sections['privacy_profile_loop']['max'] = $this->_sections['privacy_profile_loop']['loop'];
$this->_sections['privacy_profile_loop']['step'] = 1;
$this->_sections['privacy_profile_loop']['start'] = $this->_sections['privacy_profile_loop']['step'] > 0 ? 0 : $this->_sections['privacy_profile_loop']['loop']-1;
if ($this->_sections['privacy_profile_loop']['show']) {
    $this->_sections['privacy_profile_loop']['total'] = $this->_sections['privacy_profile_loop']['loop'];
    if ($this->_sections['privacy_profile_loop']['total'] == 0)
        $this->_sections['privacy_profile_loop']['show'] = false;
} else
    $this->_sections['privacy_profile_loop']['total'] = 0;
if ($this->_sections['privacy_profile_loop']['show']):

            for ($this->_sections['privacy_profile_loop']['index'] = $this->_sections['privacy_profile_loop']['start'], $this->_sections['privacy_profile_loop']['iteration'] = 1;
                 $this->_sections['privacy_profile_loop']['iteration'] <= $this->_sections['privacy_profile_loop']['total'];
                 $this->_sections['privacy_profile_loop']['index'] += $this->_sections['privacy_profile_loop']['step'], $this->_sections['privacy_profile_loop']['iteration']++):
$this->_sections['privacy_profile_loop']['rownum'] = $this->_sections['privacy_profile_loop']['iteration'];
$this->_sections['privacy_profile_loop']['index_prev'] = $this->_sections['privacy_profile_loop']['index'] - $this->_sections['privacy_profile_loop']['step'];
$this->_sections['privacy_profile_loop']['index_next'] = $this->_sections['privacy_profile_loop']['index'] + $this->_sections['privacy_profile_loop']['step'];
$this->_sections['privacy_profile_loop']['first']      = ($this->_sections['privacy_profile_loop']['iteration'] == 1);
$this->_sections['privacy_profile_loop']['last']       = ($this->_sections['privacy_profile_loop']['iteration'] == $this->_sections['privacy_profile_loop']['total']);
?>
                    <tr><td valign="top"><input type='radio' name='privacy_profile' id='<?php echo $this->_tpl_vars['privacy_profile_options'][$this->_sections['privacy_profile_loop']['index']]['privacy_id']; ?>
' value='<?php echo $this->_tpl_vars['privacy_profile_options'][$this->_sections['privacy_profile_loop']['index']]['privacy_value']; ?>
'<?php if ($this->_tpl_vars['privacy_profile'] == $this->_tpl_vars['privacy_profile_options'][$this->_sections['privacy_profile_loop']['index']]['privacy_value']): ?> CHECKED<?php endif; ?>></td><td><label for='<?php echo $this->_tpl_vars['privacy_profile_options'][$this->_sections['privacy_profile_loop']['index']]['privacy_id']; ?>
'><?php echo $this->_tpl_vars['privacy_profile_options'][$this->_sections['privacy_profile_loop']['index']]['privacy_option']; ?>
</label></td></tr>
                    <?php endfor; endif; ?>
                </table>
                <br/>
                <?php endif; ?>

                <?php if (count($this->_tpl_vars['comments_profile_options']) > 1): ?>
                <div><b><?php echo $this->_tpl_vars['Application465']; ?>
</b></div>
                <div class='form_desc'><?php echo $this->_tpl_vars['Application466']; ?>
</div>
                <table border="0" cellpadding='0' cellspacing='0' >
                                        <?php unset($this->_sections['comments_profile_loop']);
$this->_sections['comments_profile_loop']['name'] = 'comments_profile_loop';
$this->_sections['comments_profile_loop']['loop'] = is_array($_loop=$this->_tpl_vars['comments_profile_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['comments_profile_loop']['show'] = true;
$this->_sections['comments_profile_loop']['max'] = $this->_sections['comments_profile_loop']['loop'];
$this->_sections['comments_profile_loop']['step'] = 1;
$this->_sections['comments_profile_loop']['start'] = $this->_sections['comments_profile_loop']['step'] > 0 ? 0 : $this->_sections['comments_profile_loop']['loop']-1;
if ($this->_sections['comments_profile_loop']['show']) {
    $this->_sections['comments_profile_loop']['total'] = $this->_sections['comments_profile_loop']['loop'];
    if ($this->_sections['comments_profile_loop']['total'] == 0)
        $this->_sections['comments_profile_loop']['show'] = false;
} else
    $this->_sections['comments_profile_loop']['total'] = 0;
if ($this->_sections['comments_profile_loop']['show']):

            for ($this->_sections['comments_profile_loop']['index'] = $this->_sections['comments_profile_loop']['start'], $this->_sections['comments_profile_loop']['iteration'] = 1;
                 $this->_sections['comments_profile_loop']['iteration'] <= $this->_sections['comments_profile_loop']['total'];
                 $this->_sections['comments_profile_loop']['index'] += $this->_sections['comments_profile_loop']['step'], $this->_sections['comments_profile_loop']['iteration']++):
$this->_sections['comments_profile_loop']['rownum'] = $this->_sections['comments_profile_loop']['iteration'];
$this->_sections['comments_profile_loop']['index_prev'] = $this->_sections['comments_profile_loop']['index'] - $this->_sections['comments_profile_loop']['step'];
$this->_sections['comments_profile_loop']['index_next'] = $this->_sections['comments_profile_loop']['index'] + $this->_sections['comments_profile_loop']['step'];
$this->_sections['comments_profile_loop']['first']      = ($this->_sections['comments_profile_loop']['iteration'] == 1);
$this->_sections['comments_profile_loop']['last']       = ($this->_sections['comments_profile_loop']['iteration'] == $this->_sections['comments_profile_loop']['total']);
?>
                    <tr><td valign="top"><input type='radio' name='comments_profile' id='<?php echo $this->_tpl_vars['comments_profile_options'][$this->_sections['comments_profile_loop']['index']]['privacy_id']; ?>
' value='<?php echo $this->_tpl_vars['comments_profile_options'][$this->_sections['comments_profile_loop']['index']]['privacy_value']; ?>
'<?php if ($this->_tpl_vars['comments_profile'] == $this->_tpl_vars['comments_profile_options'][$this->_sections['comments_profile_loop']['index']]['privacy_value']): ?> CHECKED<?php endif; ?>></td><td><label for='<?php echo $this->_tpl_vars['comments_profile_options'][$this->_sections['comments_profile_loop']['index']]['privacy_id']; ?>
'><?php echo $this->_tpl_vars['comments_profile_options'][$this->_sections['comments_profile_loop']['index']]['privacy_option']; ?>
</label></td></tr>
                    <?php endfor; endif; ?>
                </table>
                <br/>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['user']->level_info['level_profile_search'] == 1): ?>
                <div><b><?php echo $this->_tpl_vars['Application467']; ?>
</b></div>
                <div class='form_desc'><?php echo $this->_tpl_vars['Application468']; ?>
</div>
                <table border="0" cellpadding='0' cellspacing='0' class='editprofile_options'>
                    <tr><td valign="top"><input type='radio' name='search_profile' id='search_profile1' value='1'<?php if ($this->_tpl_vars['user']->user_info['user_privacy_search'] == 1): ?> CHECKED<?php endif; ?>></td><td><label for='search_profile1'><?php echo $this->_tpl_vars['Application469']; ?>
</label></td></tr>
                    <tr><td valign="top"><input type='radio' name='search_profile' id='search_profile0' value='0'<?php if ($this->_tpl_vars['user']->user_info['user_privacy_search'] == 0): ?> CHECKED<?php endif; ?>></td><td><label for='search_profile0'><?php echo $this->_tpl_vars['Application470']; ?>
</label></td></tr>
                </table>
                <br/>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['user']->level_info['level_profile_comments'] !== '6'): ?>
                <div><b><?php echo $this->_tpl_vars['Application471']; ?>
</b></div>
                <div class='form_desc'><?php echo $this->_tpl_vars['Application472']; ?>
</div>
                <table border="0" cellpadding='0' cellspacing='0' class='editprofile_options'>
                    <tr><td valign="top"><input type='checkbox' value='1' id='profilecomment' name='usersetting_notify_profilecomment'<?php if ($this->_tpl_vars['user']->usersetting_info['usersetting_notify_profilecomment'] == 1): ?> CHECKED<?php endif; ?>></td><td><label for='profilecomment'><?php echo $this->_tpl_vars['Application473']; ?>
</label></td></tr>
                </table>
                <br/>
                <?php endif; ?>

                <p class="line">&nbsp;</p>
                <?php $this->assign('redirect_page', $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username'])); ?>

                <div class="submits">
                    <label><input type="submit" value="<?php echo $this->_tpl_vars['Application460']; ?>
"/></label>
                    <label><input type="button" onclick="location.href='<?php echo $this->_tpl_vars['redirect_page']; ?>
'" value="Cancel"/></label>
                </div>
                
                <input type='hidden' name='task' value='dosave'>
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