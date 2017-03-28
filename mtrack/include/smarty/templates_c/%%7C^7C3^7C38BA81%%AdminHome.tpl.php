<?php /* Smarty version 2.6.14, created on 2011-04-24 04:56:30
         compiled from AdminHome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'AdminHome.tpl', 43, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'AdminHeader.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h2><?php echo $this->_tpl_vars['Admin406']; ?>
</h2>
<p><?php echo $this->_tpl_vars['Admin407']; ?>
</p>



<?php if ($this->_tpl_vars['install_exists'] == 1): ?>
<p style="color:red;"><?php echo $this->_tpl_vars['Admin414']; ?>
</p>
<?php endif; ?>

<div class="row-blue">
    <?php if ($this->_tpl_vars['setting']['setting_key']): ?><div class="f-left"><p><?php echo $this->_tpl_vars['Admin408']; ?>
<b><?php echo $this->_tpl_vars['setting']['setting_key']; ?>
</b></p></div><?php endif; ?>
    <div class="f-left"><p><?php echo $this->_tpl_vars['Admin409']; ?>
 <b><?php echo $this->_tpl_vars['version_num']; ?>
</b></p></div>
</div>


<div class="row">
    <dl class="f-left">
        <dt><?php echo $this->_tpl_vars['Admin410']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_users_num']; ?>
</dd>
        <dt>Subnetworks:</dt><dd><?php echo $this->_tpl_vars['total_subnetworks']; ?>
</dd>
        <dt>Views Today:</dt><dd><?php echo $this->_tpl_vars['views_today']; ?>
</dd>
    </dl>
    <dl class="f-left">
        <dt><?php echo $this->_tpl_vars['Admin411']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_comments_num']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin412']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_messages_num']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin416']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_user_levels']; ?>
</dd>
    </dl>
    <dl class="f-left">
        <dt><?php echo $this->_tpl_vars['Admin417']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_reports']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin418']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_friendships']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin419']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_announcements']; ?>
</dd>
    </dl>
    <dl class="f-left">
        <dt><?php echo $this->_tpl_vars['Admin420']; ?>
</dt><dd><?php echo $this->_tpl_vars['signups_today']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin421']; ?>
</dt><dd><?php echo $this->_tpl_vars['logins_today']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['Admin422']; ?>
</dt><dd><?php echo $this->_tpl_vars['total_admins']; ?>
</dd>
    </dl>

        <?php if (count($this->_tpl_vars['online_users']) > 0): ?>
    <div class="clear"><p><b><?php echo count($this->_tpl_vars['online_users']); ?>
</b> <?php echo $this->_tpl_vars['Admin413']; ?>
&nbsp;
            <?php unset($this->_sections['online_users_loop']);
$this->_sections['online_users_loop']['name'] = 'online_users_loop';
$this->_sections['online_users_loop']['loop'] = is_array($_loop=$this->_tpl_vars['online_users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['online_users_loop']['show'] = true;
$this->_sections['online_users_loop']['max'] = $this->_sections['online_users_loop']['loop'];
$this->_sections['online_users_loop']['step'] = 1;
$this->_sections['online_users_loop']['start'] = $this->_sections['online_users_loop']['step'] > 0 ? 0 : $this->_sections['online_users_loop']['loop']-1;
if ($this->_sections['online_users_loop']['show']) {
    $this->_sections['online_users_loop']['total'] = $this->_sections['online_users_loop']['loop'];
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
?>
            <?php if ($this->_sections['online_users_loop']['rownum'] != 1): ?>, <?php endif; ?><b><?php echo $this->_tpl_vars['online_users'][$this->_sections['online_users_loop']['index']]; ?>
</b><?php endfor; endif; ?>
    </p></div>
    <?php endif; ?>

</div>

<?php echo $this->_tpl_vars['Admin423']; ?>


<div class="row2"><img src="images/img1.jpg" alt=""/>
    <div class="row">
        <p><a href="AdminProfile.php" class="blue"><?php echo $this->_tpl_vars['Admin429']; ?>
</a><br/>
        <?php echo $this->_tpl_vars['Admin430']; ?>
</p>
</div></div>
<div class="row2"><img src="images/img2.jpg" alt=""/>
    <div class="row">
        <a href="AdminSignup.php" class="blue"><?php echo $this->_tpl_vars['Admin431']; ?>
</a><br/>
        <p><?php echo $this->_tpl_vars['Admin432']; ?>
</p>
</div></div>
<div class="row2"><img src="images/img3.jpg" alt=""/>
    <div class="row">
        <a href="AdminLevels.php" class="blue"><?php echo $this->_tpl_vars['Admin433']; ?>
</a><br/>
        <p><?php echo $this->_tpl_vars['Admin434']; ?>
</p>
</div></div>
<div class="row2"><img src="images/img4.jpg" alt=""/>
    <div class="row">
        <a href="AdminViewplugins.php" class="blue"><?php echo $this->_tpl_vars['Admin435']; ?>
</a><br/>
        <p><?php echo $this->_tpl_vars['Admin436']; ?>
</p>
</div></div>
<div class="row2"><img src="images/img5.jpg" alt=""/>
    <div class="row" style="border:none;">
        <a href="AdminTemplates.php" class="blue"><?php echo $this->_tpl_vars['Admin437']; ?>
</a><br/>
        <p><?php echo $this->_tpl_vars['Admin438']; ?>
</p>
</div></div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'AdminFooter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>