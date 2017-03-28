<?php /* Smarty version 2.6.14, created on 2011-04-24 04:57:08
         compiled from MenuSidebar.tpl */ ?>
<?php if ($this->_tpl_vars['user']->user_exists != 0): ?>
<div class="block">
    <div class="block-top"><span>&nbsp;</span></div>
    <ul>
        <li><a href="Profile.php?user=<?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
"><?php echo $this->_tpl_vars['Application11']; ?>
</a></li>
        		<li><a href='UserEditprofile.php'>Edit Profile</a></li>
        <?php if ($this->_tpl_vars['setting']['setting_connection_allow'] != 0): ?>
        <li><a href="UserFriends<?php if ($this->_tpl_vars['total_friends_requests'] > 0): ?>Requests<?php endif; ?>.php"><?php echo $this->_tpl_vars['Application14'];  if ($this->_tpl_vars['total_friends_requests'] > 0): ?> (<?php echo $this->_tpl_vars['total_friends_requests']; ?>
)<?php endif; ?></a></li>
        <?php endif; ?>

                <?php unset($this->_sections['menu_loop']);
$this->_sections['menu_loop']['name'] = 'menu_loop';
$this->_sections['menu_loop']['loop'] = is_array($_loop=$this->_tpl_vars['global_plugins']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <?php if ($this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']] != '' && $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']] != 'Phototagger'): ?> 
        	<?php if ($this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']] == 'Chat'): ?>
        		<li><a href='<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
.php'><?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
</a></li>
        	<?php elseif ($this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']] == 'Music'): ?>
        		<li><a href='UserMusicEdit.php'><?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
</a></li>
        	<?php elseif ($this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']] == 'Vidfeeder'): ?>
        		<li><a href='Vidfeeder.php'><?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
</a></li>
        	<?php else: ?>
        		<li><a href='User<?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
.php'><?php echo $this->_tpl_vars['global_plugins'][$this->_sections['menu_loop']['index']]; ?>
s</a></li>
        	<?php endif; ?>
        <?php endif; ?>
        <?php endfor; endif; ?>

                <?php if ($this->_tpl_vars['user']->level_info['level_message_allow'] != 0): ?>
        <li><a href='UserMessages.php'><?php echo $this->_tpl_vars['Application15'];  if ($this->_tpl_vars['user_unread_pms'] != 0): ?> (<?php echo $this->_tpl_vars['user_unread_pms']; ?>
)<?php endif; ?></a></li>
        <?php endif; ?>

                <li<?php if (! $this->_tpl_vars['setting']['setting_signup_phone'] == 1): ?> class="last-li"<?php endif; ?>><a href='UserAccount.php'><?php echo $this->_tpl_vars['Application16']; ?>
</a></li>
        <?php if ($this->_tpl_vars['setting']['setting_signup_phone'] == 1): ?><li class="last-li"><a href='Phonebook.php'><?php echo $this->_tpl_vars['Application740']; ?>
</a></li><?php endif; ?>
        
    </ul>
    <div class="block-bot"><span>&nbsp;</span></div>
</div>
<?php endif; ?>

<?php echo '
<style type="text/css">
	.ad_left div{
		text-align: center;
		padding:5px;
	}
</style>
'; ?>


<?php if ($this->_tpl_vars['ads']->ad_left != ""): ?>
<div class="block">
	<div class='ad_left' style=' visibility: visible;text-align: center;'><?php echo $this->_tpl_vars['ads']->ad_left; ?>
</div>
</div>
<?php endif; ?>