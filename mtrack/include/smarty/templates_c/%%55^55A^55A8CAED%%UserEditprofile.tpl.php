<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:00
         compiled from UserEditprofile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'UserEditprofile.tpl', 84, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
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
    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application423']; ?>
 <?php echo $this->_tpl_vars['pagename']; ?>
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

            <p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application424']; ?>
</p>

                        <?php if ($this->_tpl_vars['result'] != ""): ?>
            <p style="color:green; padding-left:25px;"> <?php echo $this->_tpl_vars['result']; ?>
</p>
            <?php endif; ?>

                        <?php if ($this->_tpl_vars['error_message'] != ""): ?>
            <p style="color:red; padding-left:25px;"><?php echo $this->_tpl_vars['error_message']; ?>
</p>
            <?php endif; ?>
            
            <?php if ($this->_tpl_vars['tab_num_fields'] == 0): ?><br/><br/><br/><?php endif; ?>
            <?php if ($this->_tpl_vars['tab_num_fields'] != 0): ?>
            <form action='UserEditprofile.php' method='POST' name='profile' class="settings">

                                <?php unset($this->_sections['field_loop']);
$this->_sections['field_loop']['name'] = 'field_loop';
$this->_sections['field_loop']['loop'] = is_array($_loop=$this->_tpl_vars['fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['field_loop']['show'] = true;
$this->_sections['field_loop']['max'] = $this->_sections['field_loop']['loop'];
$this->_sections['field_loop']['step'] = 1;
$this->_sections['field_loop']['start'] = $this->_sections['field_loop']['step'] > 0 ? 0 : $this->_sections['field_loop']['loop']-1;
if ($this->_sections['field_loop']['show']) {
    $this->_sections['field_loop']['total'] = $this->_sections['field_loop']['loop'];
    if ($this->_sections['field_loop']['total'] == 0)
        $this->_sections['field_loop']['show'] = false;
} else
    $this->_sections['field_loop']['total'] = 0;
if ($this->_sections['field_loop']['show']):

            for ($this->_sections['field_loop']['index'] = $this->_sections['field_loop']['start'], $this->_sections['field_loop']['iteration'] = 1;
                 $this->_sections['field_loop']['iteration'] <= $this->_sections['field_loop']['total'];
                 $this->_sections['field_loop']['index'] += $this->_sections['field_loop']['step'], $this->_sections['field_loop']['iteration']++):
$this->_sections['field_loop']['rownum'] = $this->_sections['field_loop']['iteration'];
$this->_sections['field_loop']['index_prev'] = $this->_sections['field_loop']['index'] - $this->_sections['field_loop']['step'];
$this->_sections['field_loop']['index_next'] = $this->_sections['field_loop']['index'] + $this->_sections['field_loop']['step'];
$this->_sections['field_loop']['first']      = ($this->_sections['field_loop']['iteration'] == 1);
$this->_sections['field_loop']['last']       = ($this->_sections['field_loop']['iteration'] == $this->_sections['field_loop']['total']);
?>
                <p>
                    <label><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_title'];  if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_required'] != 0): ?>*<?php endif; ?></label>&nbsp;

                                        <?php if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_type'] == 1): ?>
                    <input type='text' class='text' name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
' style='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_maxlength']; ?>
'/>

                                        <?php elseif ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_type'] == 2): ?>
                    <textarea rows='6' cols='50' name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' style='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
'><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
</textarea>
                    <div class='form_desc'><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>

                                        <?php elseif ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_type'] == 3): ?>
                    <select name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' id='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' onchange="ShowHideSelectDeps(<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
)" style='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
'>
                        <option value='-1'></option>
                                                <?php unset($this->_sections['option_loop']);
$this->_sections['option_loop']['name'] = 'option_loop';
$this->_sections['option_loop']['loop'] = is_array($_loop=$this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['option_loop']['show'] = true;
$this->_sections['option_loop']['max'] = $this->_sections['option_loop']['loop'];
$this->_sections['option_loop']['step'] = 1;
$this->_sections['option_loop']['start'] = $this->_sections['option_loop']['step'] > 0 ? 0 : $this->_sections['option_loop']['loop']-1;
if ($this->_sections['option_loop']['show']) {
    $this->_sections['option_loop']['total'] = $this->_sections['option_loop']['loop'];
    if ($this->_sections['option_loop']['total'] == 0)
        $this->_sections['option_loop']['show'] = false;
} else
    $this->_sections['option_loop']['total'] = 0;
if ($this->_sections['option_loop']['show']):

            for ($this->_sections['option_loop']['index'] = $this->_sections['option_loop']['start'], $this->_sections['option_loop']['iteration'] = 1;
                 $this->_sections['option_loop']['iteration'] <= $this->_sections['option_loop']['total'];
                 $this->_sections['option_loop']['index'] += $this->_sections['option_loop']['step'], $this->_sections['option_loop']['iteration']++):
$this->_sections['option_loop']['rownum'] = $this->_sections['option_loop']['iteration'];
$this->_sections['option_loop']['index_prev'] = $this->_sections['option_loop']['index'] - $this->_sections['option_loop']['step'];
$this->_sections['option_loop']['index_next'] = $this->_sections['option_loop']['index'] + $this->_sections['option_loop']['step'];
$this->_sections['option_loop']['first']      = ($this->_sections['option_loop']['iteration'] == 1);
$this->_sections['option_loop']['last']       = ($this->_sections['option_loop']['iteration'] == $this->_sections['option_loop']['total']);
?>
                        <option id='op' value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</option>
                        <?php endfor; endif; ?>
                    </select>

                    
                                        <?php elseif ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_type'] == 4): ?>

                                        <div style="clear:right; float: left;">
                    <?php unset($this->_sections['option_loop']);
$this->_sections['option_loop']['name'] = 'option_loop';
$this->_sections['option_loop']['loop'] = is_array($_loop=$this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['option_loop']['show'] = true;
$this->_sections['option_loop']['max'] = $this->_sections['option_loop']['loop'];
$this->_sections['option_loop']['step'] = 1;
$this->_sections['option_loop']['start'] = $this->_sections['option_loop']['step'] > 0 ? 0 : $this->_sections['option_loop']['loop']-1;
if ($this->_sections['option_loop']['show']) {
    $this->_sections['option_loop']['total'] = $this->_sections['option_loop']['loop'];
    if ($this->_sections['option_loop']['total'] == 0)
        $this->_sections['option_loop']['show'] = false;
} else
    $this->_sections['option_loop']['total'] = 0;
if ($this->_sections['option_loop']['show']):

            for ($this->_sections['option_loop']['index'] = $this->_sections['option_loop']['start'], $this->_sections['option_loop']['iteration'] = 1;
                 $this->_sections['option_loop']['iteration'] <= $this->_sections['option_loop']['total'];
                 $this->_sections['option_loop']['index'] += $this->_sections['option_loop']['step'], $this->_sections['option_loop']['iteration']++):
$this->_sections['option_loop']['rownum'] = $this->_sections['option_loop']['iteration'];
$this->_sections['option_loop']['index_prev'] = $this->_sections['option_loop']['index'] - $this->_sections['option_loop']['step'];
$this->_sections['option_loop']['index_next'] = $this->_sections['option_loop']['index'] + $this->_sections['option_loop']['step'];
$this->_sections['option_loop']['first']      = ($this->_sections['option_loop']['iteration'] == 1);
$this->_sections['option_loop']['last']       = ($this->_sections['option_loop']['iteration'] == $this->_sections['option_loop']['total']);
?>

                        <div style="clear:both;">
                            <input type='radio' class='radio' style="border: none; width:14px;" onclick="ShowHideRadioDeps(<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
, <?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
, 'field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
', <?php echo count($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options']); ?>
)" style='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' id='label_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> CHECKED<?php endif; ?>>
                            <label style="clear: none" for='label_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</label>
                        </div>
                                            <?php endfor; endif; ?>
                    </div>


                                        <?php elseif ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_type'] == 5): ?>

                    <select name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_1' style='border:1px solid #CBD0D2; height:20px; margin:0 10px 0 0; width:57px;'>
                        <?php unset($this->_sections['date1']);
$this->_sections['date1']['name'] = 'date1';
$this->_sections['date1']['loop'] = is_array($_loop=$this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array1']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['date1']['show'] = true;
$this->_sections['date1']['max'] = $this->_sections['date1']['loop'];
$this->_sections['date1']['step'] = 1;
$this->_sections['date1']['start'] = $this->_sections['date1']['step'] > 0 ? 0 : $this->_sections['date1']['loop']-1;
if ($this->_sections['date1']['show']) {
    $this->_sections['date1']['total'] = $this->_sections['date1']['loop'];
    if ($this->_sections['date1']['total'] == 0)
        $this->_sections['date1']['show'] = false;
} else
    $this->_sections['date1']['total'] = 0;
if ($this->_sections['date1']['show']):

            for ($this->_sections['date1']['index'] = $this->_sections['date1']['start'], $this->_sections['date1']['iteration'] = 1;
                 $this->_sections['date1']['iteration'] <= $this->_sections['date1']['total'];
                 $this->_sections['date1']['index'] += $this->_sections['date1']['step'], $this->_sections['date1']['iteration']++):
$this->_sections['date1']['rownum'] = $this->_sections['date1']['iteration'];
$this->_sections['date1']['index_prev'] = $this->_sections['date1']['index'] - $this->_sections['date1']['step'];
$this->_sections['date1']['index_next'] = $this->_sections['date1']['index'] + $this->_sections['date1']['step'];
$this->_sections['date1']['first']      = ($this->_sections['date1']['iteration'] == 1);
$this->_sections['date1']['last']       = ($this->_sections['date1']['iteration'] == $this->_sections['date1']['total']);
?>
                        <option value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['name']; ?>
</option>
                        <?php endfor; endif; ?>
                    </select>

                    <select name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_2' style='border:1px solid #CBD0D2; height:20px; margin:0 10px 0 0; width:57px;'>
                        <?php unset($this->_sections['date2']);
$this->_sections['date2']['name'] = 'date2';
$this->_sections['date2']['loop'] = is_array($_loop=$this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array2']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['date2']['show'] = true;
$this->_sections['date2']['max'] = $this->_sections['date2']['loop'];
$this->_sections['date2']['step'] = 1;
$this->_sections['date2']['start'] = $this->_sections['date2']['step'] > 0 ? 0 : $this->_sections['date2']['loop']-1;
if ($this->_sections['date2']['show']) {
    $this->_sections['date2']['total'] = $this->_sections['date2']['loop'];
    if ($this->_sections['date2']['total'] == 0)
        $this->_sections['date2']['show'] = false;
} else
    $this->_sections['date2']['total'] = 0;
if ($this->_sections['date2']['show']):

            for ($this->_sections['date2']['index'] = $this->_sections['date2']['start'], $this->_sections['date2']['iteration'] = 1;
                 $this->_sections['date2']['iteration'] <= $this->_sections['date2']['total'];
                 $this->_sections['date2']['index'] += $this->_sections['date2']['step'], $this->_sections['date2']['iteration']++):
$this->_sections['date2']['rownum'] = $this->_sections['date2']['iteration'];
$this->_sections['date2']['index_prev'] = $this->_sections['date2']['index'] - $this->_sections['date2']['step'];
$this->_sections['date2']['index_next'] = $this->_sections['date2']['index'] + $this->_sections['date2']['step'];
$this->_sections['date2']['first']      = ($this->_sections['date2']['iteration'] == 1);
$this->_sections['date2']['last']       = ($this->_sections['date2']['iteration'] == $this->_sections['date2']['total']);
?>
                        <option value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['name']; ?>
</option>
                        <?php endfor; endif; ?>
                    </select>

                    <select name='field_<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_3' style='border:1px solid #CBD0D2; height:20px; margin:0 10px 0 0; width:57px;'>
                        <?php unset($this->_sections['date3']);
$this->_sections['date3']['name'] = 'date3';
$this->_sections['date3']['loop'] = is_array($_loop=$this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array3']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['date3']['show'] = true;
$this->_sections['date3']['max'] = $this->_sections['date3']['loop'];
$this->_sections['date3']['step'] = 1;
$this->_sections['date3']['start'] = $this->_sections['date3']['step'] > 0 ? 0 : $this->_sections['date3']['loop']-1;
if ($this->_sections['date3']['show']) {
    $this->_sections['date3']['total'] = $this->_sections['date3']['loop'];
    if ($this->_sections['date3']['total'] == 0)
        $this->_sections['date3']['show'] = false;
} else
    $this->_sections['date3']['total'] = 0;
if ($this->_sections['date3']['show']):

            for ($this->_sections['date3']['index'] = $this->_sections['date3']['start'], $this->_sections['date3']['iteration'] = 1;
                 $this->_sections['date3']['iteration'] <= $this->_sections['date3']['total'];
                 $this->_sections['date3']['index'] += $this->_sections['date3']['step'], $this->_sections['date3']['iteration']++):
$this->_sections['date3']['rownum'] = $this->_sections['date3']['iteration'];
$this->_sections['date3']['index_prev'] = $this->_sections['date3']['index'] - $this->_sections['date3']['step'];
$this->_sections['date3']['index_next'] = $this->_sections['date3']['index'] + $this->_sections['date3']['step'];
$this->_sections['date3']['first']      = ($this->_sections['date3']['iteration'] == 1);
$this->_sections['date3']['last']       = ($this->_sections['date3']['iteration'] == $this->_sections['date3']['total']);
?>
                        <option value='<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['name']; ?>
</option>
                        <?php endfor; endif; ?>
                    </select>
                </p>
                <div class='form_desc'><?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id'] == $this->_tpl_vars['setting']['setting_subnet_field1_id'] | $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_id'] == $this->_tpl_vars['setting']['setting_subnet_field2_id']):  echo $this->_tpl_vars['UserProfile']; ?>
 <?php echo $this->_tpl_vars['user']->subnet_info['subnet_name'];  endif; ?>

                                <?php if ($this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_error'] != ""): ?><div class='form_error'><img src='./images/icons/error16.gif' border='0' class='icon'> <?php echo $this->_tpl_vars['fields'][$this->_sections['field_loop']['index']]['field_error']; ?>
</div><?php endif; ?>

                <?php endfor; endif; ?>
                <p class="line">&nbsp;</p>
                <?php $this->assign('redirect_page', $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username'])); ?>
                
                <div class="submits">
                    <label><input type="submit" value="<?php echo $this->_tpl_vars['Application425']; ?>
"/></label>
                    <label><input type="button" onclick="location.href='<?php echo $this->_tpl_vars['redirect_page']; ?>
'" value="Cancel"/></label>
                </div>
          
                <input type='hidden' name='task' value='dosave'>
                <input type='hidden' name='tab_id' value='<?php echo $this->_tpl_vars['tab_id']; ?>
'>
            </form>
            <?php endif; ?>
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