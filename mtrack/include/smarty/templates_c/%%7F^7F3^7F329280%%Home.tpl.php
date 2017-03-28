<?php /* Smarty version 2.6.14, created on 2011-04-24 04:57:08
         compiled from Home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'Home.tpl', 32, false),array('modifier', 'truncate', 'Home.tpl', 50, false),array('function', 'cycle', 'Home.tpl', 49, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

            <div id="content">
				<div class="block-top"><span>&nbsp;</span></div>

                  <?php if (in_array ( 'Hello message' , $this->_tpl_vars['homepageBlocks'] )): ?>
				  <h3 class="stat"><b><?php echo $this->_tpl_vars['Application123']; ?>
</b></h3>

                  <div class="contentblock"><?php echo $this->_tpl_vars['Application124']; ?>
</div>
                  <?php endif; ?>                
                  
                  <ul class="accordion">
                    
                  <?php if ($this->_tpl_vars['news_total'] > 0): ?>
                    <?php if (in_array ( 'Recent news' , $this->_tpl_vars['homepageBlocks'] )): ?>
                        <li class="active"><a class="opener active" href="#"><?php echo $this->_tpl_vars['Application141']; ?>
</a>
                            <div class="slide">
                                  <?php unset($this->_sections['news_loop']);
$this->_sections['news_loop']['name'] = 'news_loop';
$this->_sections['news_loop']['loop'] = is_array($_loop=$this->_tpl_vars['news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['news_loop']['max'] = (int)5;
$this->_sections['news_loop']['show'] = true;
if ($this->_sections['news_loop']['max'] < 0)
    $this->_sections['news_loop']['max'] = $this->_sections['news_loop']['loop'];
$this->_sections['news_loop']['step'] = 1;
$this->_sections['news_loop']['start'] = $this->_sections['news_loop']['step'] > 0 ? 0 : $this->_sections['news_loop']['loop']-1;
if ($this->_sections['news_loop']['show']) {
    $this->_sections['news_loop']['total'] = min(ceil(($this->_sections['news_loop']['step'] > 0 ? $this->_sections['news_loop']['loop'] - $this->_sections['news_loop']['start'] : $this->_sections['news_loop']['start']+1)/abs($this->_sections['news_loop']['step'])), $this->_sections['news_loop']['max']);
    if ($this->_sections['news_loop']['total'] == 0)
        $this->_sections['news_loop']['show'] = false;
} else
    $this->_sections['news_loop']['total'] = 0;
if ($this->_sections['news_loop']['show']):

            for ($this->_sections['news_loop']['index'] = $this->_sections['news_loop']['start'], $this->_sections['news_loop']['iteration'] = 1;
                 $this->_sections['news_loop']['iteration'] <= $this->_sections['news_loop']['total'];
                 $this->_sections['news_loop']['index'] += $this->_sections['news_loop']['step'], $this->_sections['news_loop']['iteration']++):
$this->_sections['news_loop']['rownum'] = $this->_sections['news_loop']['iteration'];
$this->_sections['news_loop']['index_prev'] = $this->_sections['news_loop']['index'] - $this->_sections['news_loop']['step'];
$this->_sections['news_loop']['index_next'] = $this->_sections['news_loop']['index'] + $this->_sections['news_loop']['step'];
$this->_sections['news_loop']['first']      = ($this->_sections['news_loop']['iteration'] == 1);
$this->_sections['news_loop']['last']       = ($this->_sections['news_loop']['iteration'] == $this->_sections['news_loop']['total']);
?>
                                    <table cellpadding='0' cellspacing='5'>
                                    <tr>
                                    <td valign='top'><b><?php echo $this->_tpl_vars['news'][$this->_sections['news_loop']['index']]['item_subject']; ?>
</b><br/><?php echo $this->_tpl_vars['news'][$this->_sections['news_loop']['index']]['item_date']; ?>
<br /><?php echo $this->_tpl_vars['news'][$this->_sections['news_loop']['index']]['item_body']; ?>
</td>
                                    </tr>
                                    </table>
                                    <?php if ($this->_sections['news_loop']['last'] == false): ?><br /><?php endif; ?>
                                  <?php endfor; endif; ?>
                            </div>
                        </li>
                    <?php endif; ?>
                  <?php endif; ?>
                
                                    <?php if (count($this->_tpl_vars['online_users']) > 0): ?>
                    <?php if (in_array ( 'Members online' , $this->_tpl_vars['homepageBlocks'] )): ?>
                        <li class="active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application143']; ?>
 (<?php echo count($this->_tpl_vars['online_users']); ?>
)</a>
                            <div class="slide">
                                <?php unset($this->_sections['online_users_loop']);
$this->_sections['online_users_loop']['name'] = 'online_users_loop';
$this->_sections['online_users_loop']['loop'] = is_array($_loop=$this->_tpl_vars['online_users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['online_users_loop']['max'] = (int)20;
$this->_sections['online_users_loop']['show'] = true;
if ($this->_sections['online_users_loop']['max'] < 0)
    $this->_sections['online_users_loop']['max'] = $this->_sections['online_users_loop']['loop'];
$this->_sections['online_users_loop']['step'] = 1;
$this->_sections['online_users_loop']['start'] = $this->_sections['online_users_loop']['step'] > 0 ? 0 : $this->_sections['online_users_loop']['loop']-1;
if ($this->_sections['online_users_loop']['show']) {
    $this->_sections['online_users_loop']['total'] = min(ceil(($this->_sections['online_users_loop']['step'] > 0 ? $this->_sections['online_users_loop']['loop'] - $this->_sections['online_users_loop']['start'] : $this->_sections['online_users_loop']['start']+1)/abs($this->_sections['online_users_loop']['step'])), $this->_sections['online_users_loop']['max']);
    if ($this->_sections['online_users_loop']['total'] == 0)
        $this->_sections['online_users_loop']['show'] = false;
} else
    $this->_sections['online_users_loop']['total'] = 0;
if ($this->_sections['online_users_loop']['show']):

            for ($this->_sections['online_users_loop']['index'] = $this->_sections['online_users_loop']['start'], $this->_sections['online_users_loop']['iteration'] = 1;
                 $this->_sections['online_users_loop']['iteration'] <= $this->_sections['online_users_loop']['total'];
                 $this->_sections['online_users_loop']['index'] += $this->_sections['online_users_loop']['step'], $this->_sections['online_users_loop']['iteration']++):
$this->_sections['online_users_loop']['rownum'] = $this->_sections['online_users_loop']['iteration'];
$this->_sections['online_users_loop']['index_prev'] = $this->_sections['online_users_loop']['index'] - $this->_sections['online_users_loop']['step'];
$this->_sections['online_users_loop']['index_next'] = $this->_sections['online_users_loop']['index'] + $this->_sections['online_users_loop']['step'];
$this->_sections['online_users_loop']['first']      = ($this->_sections['online_users_loop']['iteration'] == 1);
$this->_sections['online_users_loop']['last']       = ($this->_sections['online_users_loop']['iteration'] == $this->_sections['online_users_loop']['total']);
 if ($this->_sections['online_users_loop']['rownum'] != 1): ?>, <?php endif; ?><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['online_users'][$this->_sections['online_users_loop']['index']]); ?>
'><?php echo $this->_tpl_vars['online_users'][$this->_sections['online_users_loop']['index']]; ?>
</a><?php endfor; endif; ?><br/>
                            </div>
                        </li>
                    <?php endif; ?>
                  <?php endif; ?>
                
                                    <?php if (in_array ( 'Newest members' , $this->_tpl_vars['homepageBlocks'] )): ?>
                        <li class="active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application144']; ?>
</a>
                            <div class="slide">
                                <?php if (count($this->_tpl_vars['signups']) > 0): ?>
                                  <?php unset($this->_sections['signups_loop']);
$this->_sections['signups_loop']['name'] = 'signups_loop';
$this->_sections['signups_loop']['loop'] = is_array($_loop=$this->_tpl_vars['signups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['signups_loop']['max'] = (int)5;
$this->_sections['signups_loop']['show'] = true;
if ($this->_sections['signups_loop']['max'] < 0)
    $this->_sections['signups_loop']['max'] = $this->_sections['signups_loop']['loop'];
$this->_sections['signups_loop']['step'] = 1;
$this->_sections['signups_loop']['start'] = $this->_sections['signups_loop']['step'] > 0 ? 0 : $this->_sections['signups_loop']['loop']-1;
if ($this->_sections['signups_loop']['show']) {
    $this->_sections['signups_loop']['total'] = min(ceil(($this->_sections['signups_loop']['step'] > 0 ? $this->_sections['signups_loop']['loop'] - $this->_sections['signups_loop']['start'] : $this->_sections['signups_loop']['start']+1)/abs($this->_sections['signups_loop']['step'])), $this->_sections['signups_loop']['max']);
    if ($this->_sections['signups_loop']['total'] == 0)
        $this->_sections['signups_loop']['show'] = false;
} else
    $this->_sections['signups_loop']['total'] = 0;
if ($this->_sections['signups_loop']['show']):

            for ($this->_sections['signups_loop']['index'] = $this->_sections['signups_loop']['start'], $this->_sections['signups_loop']['iteration'] = 1;
                 $this->_sections['signups_loop']['iteration'] <= $this->_sections['signups_loop']['total'];
                 $this->_sections['signups_loop']['index'] += $this->_sections['signups_loop']['step'], $this->_sections['signups_loop']['iteration']++):
$this->_sections['signups_loop']['rownum'] = $this->_sections['signups_loop']['iteration'];
$this->_sections['signups_loop']['index_prev'] = $this->_sections['signups_loop']['index'] - $this->_sections['signups_loop']['step'];
$this->_sections['signups_loop']['index_next'] = $this->_sections['signups_loop']['index'] + $this->_sections['signups_loop']['step'];
$this->_sections['signups_loop']['first']      = ($this->_sections['signups_loop']['iteration'] == 1);
$this->_sections['signups_loop']['last']       = ($this->_sections['signups_loop']['iteration'] == $this->_sections['signups_loop']['total']);
?>
                                                                        <?php echo smarty_function_cycle(array('name' => 'startrow','values' => "<table cellpadding='0' cellspacing='15' align='center'><tr>,,,,"), $this);?>

                                    <td class='portal_member' valign='top'><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['signups'][$this->_sections['signups_loop']['index']]->user_info['user_username']); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['signups'][$this->_sections['signups_loop']['index']]->user_info['user_username'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15, "...", true) : smarty_modifier_truncate($_tmp, 15, "...", true)); ?>
<br /><img src='<?php echo $this->_tpl_vars['signups'][$this->_sections['signups_loop']['index']]->user_photo('./images/nophoto.gif'); ?>
' class='photo' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['signups'][$this->_sections['signups_loop']['index']]->user_photo('./images/nophoto.gif'),'90','90','w'); ?>
' border='0' /></a></td>
                                                                        <?php if ($this->_sections['signups_loop']['last'] == true): ?>
                                      </tr></table>
                                    <?php else: ?>
                                      <?php echo smarty_function_cycle(array('name' => 'endrow','values' => ",,,,</tr></table>"), $this);?>

                                    <?php endif; ?>
                                  <?php endfor; endif; ?>
                                <?php else: ?>
                                  <?php echo $this->_tpl_vars['Application145']; ?>

                                <?php endif; ?>
                            </div>
                        </li>
                 <?php endif; ?>
                
                
                                <?php if ($this->_tpl_vars['setting']['setting_connection_allow'] != 0): ?>
                    <?php if (in_array ( 'Most popular members' , $this->_tpl_vars['homepageBlocks'] )): ?>
                        <li class="active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application146']; ?>
</a>
                            <div class="slide">
                              <?php if (count($this->_tpl_vars['friends']) > 0): ?>
                                <?php unset($this->_sections['friends_loop']);
$this->_sections['friends_loop']['name'] = 'friends_loop';
$this->_sections['friends_loop']['loop'] = is_array($_loop=$this->_tpl_vars['friends']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['friends_loop']['max'] = (int)5;
$this->_sections['friends_loop']['show'] = true;
if ($this->_sections['friends_loop']['max'] < 0)
    $this->_sections['friends_loop']['max'] = $this->_sections['friends_loop']['loop'];
$this->_sections['friends_loop']['step'] = 1;
$this->_sections['friends_loop']['start'] = $this->_sections['friends_loop']['step'] > 0 ? 0 : $this->_sections['friends_loop']['loop']-1;
if ($this->_sections['friends_loop']['show']) {
    $this->_sections['friends_loop']['total'] = min(ceil(($this->_sections['friends_loop']['step'] > 0 ? $this->_sections['friends_loop']['loop'] - $this->_sections['friends_loop']['start'] : $this->_sections['friends_loop']['start']+1)/abs($this->_sections['friends_loop']['step'])), $this->_sections['friends_loop']['max']);
    if ($this->_sections['friends_loop']['total'] == 0)
        $this->_sections['friends_loop']['show'] = false;
} else
    $this->_sections['friends_loop']['total'] = 0;
if ($this->_sections['friends_loop']['show']):

            for ($this->_sections['friends_loop']['index'] = $this->_sections['friends_loop']['start'], $this->_sections['friends_loop']['iteration'] = 1;
                 $this->_sections['friends_loop']['iteration'] <= $this->_sections['friends_loop']['total'];
                 $this->_sections['friends_loop']['index'] += $this->_sections['friends_loop']['step'], $this->_sections['friends_loop']['iteration']++):
$this->_sections['friends_loop']['rownum'] = $this->_sections['friends_loop']['iteration'];
$this->_sections['friends_loop']['index_prev'] = $this->_sections['friends_loop']['index'] - $this->_sections['friends_loop']['step'];
$this->_sections['friends_loop']['index_next'] = $this->_sections['friends_loop']['index'] + $this->_sections['friends_loop']['step'];
$this->_sections['friends_loop']['first']      = ($this->_sections['friends_loop']['iteration'] == 1);
$this->_sections['friends_loop']['last']       = ($this->_sections['friends_loop']['iteration'] == $this->_sections['friends_loop']['total']);
?>
                                                                    <?php echo smarty_function_cycle(array('name' => 'startrow2','values' => "<table cellpadding='0' cellspacing='15' align='center'><tr>,,,,"), $this);?>

                                  <td class='portal_member' valign='top'><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['friends'][$this->_sections['friends_loop']['index']]['friend']->user_info['user_username']); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['friends'][$this->_sections['friends_loop']['index']]['friend']->user_info['user_username'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
<br /><img src='<?php echo $this->_tpl_vars['friends'][$this->_sections['friends_loop']['index']]['friend']->user_photo('./images/nophoto.gif'); ?>
' class='photo' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['friends'][$this->_sections['friends_loop']['index']]['friend']->user_photo('./images/nophoto.gif'),'90','90','w'); ?>
' border='0' /></a><br /><?php echo $this->_tpl_vars['friends'][$this->_sections['friends_loop']['index']]['total_friends']; ?>
 <?php echo $this->_tpl_vars['Application147']; ?>
</td>
                                                                    <?php if ($this->_sections['friends_loop']['last'] == true): ?>
                                    </tr></table>
                                  <?php else: ?>
                                    <?php echo smarty_function_cycle(array('name' => 'endrow2','values' => ",,,,</tr></table>"), $this);?>

                                  <?php endif; ?>
                                <?php endfor; endif; ?>
                              <?php else: ?>
                                <?php echo $this->_tpl_vars['Application148']; ?>

                              <?php endif; ?>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                
                                <?php if (in_array ( 'Members last logged in' , $this->_tpl_vars['homepageBlocks'] )): ?>
                        <li class="active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application149']; ?>
</a>
                            <div class="slide">
                                <?php if (count($this->_tpl_vars['logins']) > 0): ?>
                                  <?php unset($this->_sections['login_loop']);
$this->_sections['login_loop']['name'] = 'login_loop';
$this->_sections['login_loop']['loop'] = is_array($_loop=$this->_tpl_vars['logins']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['login_loop']['max'] = (int)5;
$this->_sections['login_loop']['show'] = true;
if ($this->_sections['login_loop']['max'] < 0)
    $this->_sections['login_loop']['max'] = $this->_sections['login_loop']['loop'];
$this->_sections['login_loop']['step'] = 1;
$this->_sections['login_loop']['start'] = $this->_sections['login_loop']['step'] > 0 ? 0 : $this->_sections['login_loop']['loop']-1;
if ($this->_sections['login_loop']['show']) {
    $this->_sections['login_loop']['total'] = min(ceil(($this->_sections['login_loop']['step'] > 0 ? $this->_sections['login_loop']['loop'] - $this->_sections['login_loop']['start'] : $this->_sections['login_loop']['start']+1)/abs($this->_sections['login_loop']['step'])), $this->_sections['login_loop']['max']);
    if ($this->_sections['login_loop']['total'] == 0)
        $this->_sections['login_loop']['show'] = false;
} else
    $this->_sections['login_loop']['total'] = 0;
if ($this->_sections['login_loop']['show']):

            for ($this->_sections['login_loop']['index'] = $this->_sections['login_loop']['start'], $this->_sections['login_loop']['iteration'] = 1;
                 $this->_sections['login_loop']['iteration'] <= $this->_sections['login_loop']['total'];
                 $this->_sections['login_loop']['index'] += $this->_sections['login_loop']['step'], $this->_sections['login_loop']['iteration']++):
$this->_sections['login_loop']['rownum'] = $this->_sections['login_loop']['iteration'];
$this->_sections['login_loop']['index_prev'] = $this->_sections['login_loop']['index'] - $this->_sections['login_loop']['step'];
$this->_sections['login_loop']['index_next'] = $this->_sections['login_loop']['index'] + $this->_sections['login_loop']['step'];
$this->_sections['login_loop']['first']      = ($this->_sections['login_loop']['iteration'] == 1);
$this->_sections['login_loop']['last']       = ($this->_sections['login_loop']['iteration'] == $this->_sections['login_loop']['total']);
?>
                                                                        <?php echo smarty_function_cycle(array('name' => 'startrow3','values' => "<table cellpadding='0' cellspacing='15' align='center'><tr>,,,,"), $this);?>

                                    <td class='portal_member' valign='top'><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['logins'][$this->_sections['login_loop']['index']]->user_info['user_username']); ?>
'><?php echo $this->_tpl_vars['logins'][$this->_sections['login_loop']['index']]->user_info['user_username']; ?>
<br /><img src='<?php echo $this->_tpl_vars['logins'][$this->_sections['login_loop']['index']]->user_photo('./images/nophoto.gif'); ?>
' class='photo' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['logins'][$this->_sections['login_loop']['index']]->user_photo('./images/nophoto.gif'),'90','90','w'); ?>
' border='0' /></a></td>
                                                                        <?php if ($this->_sections['login_loop']['last'] == true): ?>
                                      </tr></table>
                                    <?php else: ?>
                                      <?php echo smarty_function_cycle(array('name' => 'endrow3','values' => ",,,,</tr></table>"), $this);?>

                                    <?php endif; ?>
                                  <?php endfor; endif; ?>
                                <?php else: ?>
                                  <?php echo $this->_tpl_vars['Application150']; ?>

                                <?php endif; ?>
                            </div>
                        </li>
                <?php endif; ?>
                </ul>
                
                <div class="block-bot"><span>&nbsp;</span></div>
            </div>  
            <div id="sidebar">

            	<?php if ($this->_tpl_vars['user']->user_exists): ?><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username']); ?>
'><img src='<?php echo $this->_tpl_vars['user']->user_photo("./images/nophoto.gif"); ?>
' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['user']->user_photo("./images/nophoto.gif"),'174','174','w'); ?>
' border='0' class="img" alt="<?php echo $this->_tpl_vars['user']->user_info['user_username'];  echo $this->_tpl_vars['Application130']; ?>
" /></a>
            	<br/><br/><?php endif; ?>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'MenuSidebar.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php if (in_array ( 'Network statistic' , $this->_tpl_vars['homepageBlocks'] )): ?>
                <div class="block">
                    <ul class="accordion">
                        <li class="form-top active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application133']; ?>
</a>
                            <div class="slide">
                                    <div>
                                        <?php echo $this->_tpl_vars['Application134']; ?>
 <?php echo $this->_tpl_vars['total_members']; ?>
 <?php echo $this->_tpl_vars['Application135']; ?>

                                        <?php if ($this->_tpl_vars['setting']['setting_connection_allow'] != 0): ?><br /><?php echo $this->_tpl_vars['Application137']; ?>
 <?php echo $this->_tpl_vars['total_friends']; ?>
 <?php echo $this->_tpl_vars['Application138'];  endif; ?>
                                        <br /><?php echo $this->_tpl_vars['Application139']; ?>
 <?php echo $this->_tpl_vars['total_comments']; ?>
 <?php echo $this->_tpl_vars['Application140']; ?>

                                    </div>
                                    <div class="block-bot"><span>&nbsp;</span></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

                
			</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>