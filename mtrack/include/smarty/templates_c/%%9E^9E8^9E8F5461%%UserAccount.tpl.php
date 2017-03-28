<?php /* Smarty version 2.6.14, created on 2011-04-24 05:02:47
         compiled from UserAccount.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'UserAccount.tpl', 150, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
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
           .form_check input{
        border:none !important;
    }
        .block_div{
            padding-top:4px;
        }
    </style>
    '; ?>

        <script type="text/javascript">
        <!--
        var blocked_id = <?php echo $this->_tpl_vars['num_blocked']; ?>
;
        <?php echo '
        function addInput(fieldname) {
            var ni = document.getElementById(fieldname);
            var newdiv = document.createElement(\'div\');
            var divIdName = \'my\'+blocked_id+\'Div\';
            newdiv.setAttribute(\'id\',divIdName);
            newdiv._appendClass(\'block_div\');
            newdiv.innerHTML = "<input type=\'text\' name=\'blocked" + blocked_id +"\' size=\'25\' class=\'text\' maxlength=\'50\'>&nbsp;<br>";
            ni.appendChild(newdiv);
            blocked_id++;
            window.document.info.num_blocked.value=blocked_id;
        }

        // -->
    </script>
    '; ?>


    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application388']; ?>
</h2></div>
    <div class="row-blue" align="center">
        <p class="blue"><?php echo $this->_tpl_vars['Application389']; ?>
</p>
    </div>


    <div class="layers" >
        <ul class="list01" style="margin:1px; width:600px;">

            <li <?php if ($this->_tpl_vars['page'] == 'UserAccount'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='UserAccount.php'><?php echo $this->_tpl_vars['Application385']; ?>
</a>
            </li>

            <li <?php if ($this->_tpl_vars['page'] == 'UserAccountPass'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='UserAccountPass.php'><?php echo $this->_tpl_vars['Application386']; ?>
</a>
            </li>

            <li <?php if ($this->_tpl_vars['page'] == 'UserAccountDelete'): ?>class="ui-state-active ui-tabs-selected"<?php endif; ?>>
                <a href='UserAccountDelete.php'><?php echo $this->_tpl_vars['Application387']; ?>
</a>
            </li>
        </ul>



                <?php if ($this->_tpl_vars['result'] != ""): ?>
        <br/>
        <p align="center" style="color:green;"><?php echo $this->_tpl_vars['result']; ?>
</p>
        <br/>

                <?php elseif ($this->_tpl_vars['error_message'] != ""): ?>
        <br/>
        <p align="center" style="color:red;"><?php echo $this->_tpl_vars['error_message']; ?>
</p>
        <br/>
        <?php endif; ?>


        <div id="primary" class="info-cnt tuneddivs" style="margin:1px;">

            <form action="UserAccount.php" method="post" class="settings">
                <table cellpadding='0' cellspacing='0' style="color:#666666;">
                    <tr>
                        <td class='form1'><?php echo $this->_tpl_vars['Application390']; ?>
</td>
                        <td class='form2'>
                            <input name='user_email' type='text' class='text' size='40' maxlength='70' value='<?php echo $this->_tpl_vars['user']->user_info['user_email']; ?>
'>
                            <?php if ($this->_tpl_vars['user']->user_info['user_email'] != $this->_tpl_vars['user']->user_info['user_newemail'] & $this->_tpl_vars['user']->user_info['user_newemail'] != "" & $this->_tpl_vars['setting']['setting_signup_verify'] != 0): ?><div class='form_desc'><?php echo $this->_tpl_vars['Application391']; ?>
 <?php echo $this->_tpl_vars['user']->user_info['user_newemail']; ?>
</div><?php endif; ?>
                            <?php if ($this->_tpl_vars['setting']['setting_subnet_field1_id'] == 0 | $this->_tpl_vars['setting']['setting_subnet_field2_id'] == 0): ?><div class='form_desc'><?php echo $this->_tpl_vars['Application392']; ?>
 <?php echo $this->_tpl_vars['user']->subnet_info['subnet_name']; ?>
</div><?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'><?php echo $this->_tpl_vars['Application393']; ?>
</td>
                        <td class='form2' style="padding-top:4px !important;">
                            <input name='user_username' type='text' class='text' size='40' maxlength='50' value='<?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
'>
                            
                                 <div class='form_desc'><?php echo $this->_tpl_vars['Application382']; ?>
</div>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1'><?php echo $this->_tpl_vars['Application395']; ?>
</td>
                        <td class='form2' style="padding-top:4px !important;">
                            <select name='user_timezone'>
                                <option value='-8'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-8"): ?> SELECTED<?php endif; ?>>Pacific Time (US & Canada)</option>
                                <option value='-7'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-7"): ?> SELECTED<?php endif; ?>>Mountain Time (US & Canada)</option>
                                <option value='-6'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-6"): ?> SELECTED<?php endif; ?>>Central Time (US & Canada)</option>
                                <option value='-5'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-5"): ?> SELECTED<?php endif; ?>>Eastern Time (US & Canada)</option>
                                <option value='-4'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-4"): ?> SELECTED<?php endif; ?>>Atlantic Time (Canada)</option>
                                <option value='-9'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-9"): ?> SELECTED<?php endif; ?>>Alaska (US & Canada)</option>
                                <option value='-10'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-10"): ?> SELECTED<?php endif; ?>>Hawaii (US)</option>
                                <option value='-11'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-11"): ?> SELECTED<?php endif; ?>>Midway Island, Samoa</option>
                                <option value='-12'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-12"): ?> SELECTED<?php endif; ?>>Eniwetok, Kwajalein</option>
                                <option value='-3.3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-3.3"): ?> SELECTED<?php endif; ?>>Newfoundland</option>
                                <option value='-3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-3"): ?> SELECTED<?php endif; ?>>Brasilia, Buenos Aires, Georgetown</option>
                                <option value='-2'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-2"): ?> SELECTED<?php endif; ?>>Mid-Atlantic</option>
                                <option value='-1'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "-1"): ?> SELECTED<?php endif; ?>>Azores, Cape Verde Is.</option>
                                <option value='0'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '0'): ?> SELECTED<?php endif; ?>>Greenwich Mean Time (Lisbon, London)</option>
                                <option value='1'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '1'): ?> SELECTED<?php endif; ?>>Amsterdam, Berlin, Paris, Rome, Madrid</option>
                                <option value='2'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '2'): ?> SELECTED<?php endif; ?>>Athens, Helsinki, Istanbul, Cairo, E. Europe</option>
                                <option value='3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '3'): ?> SELECTED<?php endif; ?>>Baghdad, Kuwait, Nairobi, Moscow</option>
                                <option value='3.3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "3.3"): ?> SELECTED<?php endif; ?>>Tehran</option>
                                <option value='4'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '4'): ?> SELECTED<?php endif; ?>>Abu Dhabi, Kazan, Muscat</option>
                                <option value='4.3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "4.3"): ?> SELECTED<?php endif; ?>>Kabul</option>
                                <option value='5'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '5'): ?> SELECTED<?php endif; ?>>Islamabad, Karachi, Tashkent</option>
                                <option value='5.5'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "5.5"): ?> SELECTED<?php endif; ?>>Bombay, Calcutta, New Delhi</option>
                                <option value='6'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '6'): ?> SELECTED<?php endif; ?>>Almaty, Dhaka</option>
                                <option value='7'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '7'): ?> SELECTED<?php endif; ?>>Bangkok, Jakarta, Hanoi</option>
                                <option value='8'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '8'): ?> SELECTED<?php endif; ?>>Beijing, Hong Kong, Singapore, Taipei</option>
                                <option value='9'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '9'): ?> SELECTED<?php endif; ?>>Tokyo, Osaka, Sapporto, Seoul, Yakutsk</option>
                                <option value='9.3'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == "9.3"): ?> SELECTED<?php endif; ?>>Adelaide, Darwin</option>
                                <option value='10'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '10'): ?> SELECTED<?php endif; ?>>Brisbane, Melbourne, Sydney, Guam</option>
                                <option value='11'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '11'): ?> SELECTED<?php endif; ?>>Magadan, Soloman Is., New Caledonia</option>
                                <option value='12'<?php if ($this->_tpl_vars['user']->user_info['user_timezone'] == '12'): ?> SELECTED<?php endif; ?>>Fiji, Kamchatka, Marshall Is., Wellington</option>
                            </select>
                        </td>
                    </tr>

                                        <?php if ($this->_tpl_vars['setting']['setting_lang_allow'] == 1): ?>
                    <tr>
                        <td class='form1'><?php echo $this->_tpl_vars['Application379']; ?>
</td>
                        <td class='form2'>
                            <select name='user_lang'>
                                <?php unset($this->_sections['lang_loop']);
$this->_sections['lang_loop']['name'] = 'lang_loop';
$this->_sections['lang_loop']['loop'] = is_array($_loop=$this->_tpl_vars['lang_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['lang_loop']['show'] = true;
$this->_sections['lang_loop']['max'] = $this->_sections['lang_loop']['loop'];
$this->_sections['lang_loop']['step'] = 1;
$this->_sections['lang_loop']['start'] = $this->_sections['lang_loop']['step'] > 0 ? 0 : $this->_sections['lang_loop']['loop']-1;
if ($this->_sections['lang_loop']['show']) {
    $this->_sections['lang_loop']['total'] = $this->_sections['lang_loop']['loop'];
    if ($this->_sections['lang_loop']['total'] == 0)
        $this->_sections['lang_loop']['show'] = false;
} else
    $this->_sections['lang_loop']['total'] = 0;
if ($this->_sections['lang_loop']['show']):

            for ($this->_sections['lang_loop']['index'] = $this->_sections['lang_loop']['start'], $this->_sections['lang_loop']['iteration'] = 1;
                 $this->_sections['lang_loop']['iteration'] <= $this->_sections['lang_loop']['total'];
                 $this->_sections['lang_loop']['index'] += $this->_sections['lang_loop']['step'], $this->_sections['lang_loop']['iteration']++):
$this->_sections['lang_loop']['rownum'] = $this->_sections['lang_loop']['iteration'];
$this->_sections['lang_loop']['index_prev'] = $this->_sections['lang_loop']['index'] - $this->_sections['lang_loop']['step'];
$this->_sections['lang_loop']['index_next'] = $this->_sections['lang_loop']['index'] + $this->_sections['lang_loop']['step'];
$this->_sections['lang_loop']['first']      = ($this->_sections['lang_loop']['iteration'] == 1);
$this->_sections['lang_loop']['last']       = ($this->_sections['lang_loop']['iteration'] == $this->_sections['lang_loop']['total']);
?>
                                <option value='<?php echo $this->_tpl_vars['lang_options'][$this->_sections['lang_loop']['index']]; ?>
'<?php if ($this->_tpl_vars['user']->user_info['user_lang'] == ((is_array($_tmp=$this->_tpl_vars['lang_options'][$this->_sections['lang_loop']['index']])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp))): ?> selected='selected'<?php endif; ?>><?php echo $this->_tpl_vars['lang_options'][$this->_sections['lang_loop']['index']]; ?>
</option>
                                <?php endfor; endif; ?>
                            </select>
                        </td>
                    </tr>
                    <?php endif; ?>

                                        <?php if ($this->_tpl_vars['setting']['setting_actions_privacy'] == 1): ?>
                    <tr>
                        <td class='form1' colspan="2" style="padding-top:12px; padding-bottom:10px;"><?php echo $this->_tpl_vars['Application381']; ?>
</td>
                    </tr>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application380']; ?>
</td>
                        <td class='form_check' valign="top">
                            
                            <table cellpadding='0' cellspacing='0' style="width:300px;"  style="color:#666666;">
                                <?php unset($this->_sections['actiontypes_loop']);
$this->_sections['actiontypes_loop']['name'] = 'actiontypes_loop';
$this->_sections['actiontypes_loop']['loop'] = is_array($_loop=$this->_tpl_vars['actiontypes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['actiontypes_loop']['show'] = true;
$this->_sections['actiontypes_loop']['max'] = $this->_sections['actiontypes_loop']['loop'];
$this->_sections['actiontypes_loop']['step'] = 1;
$this->_sections['actiontypes_loop']['start'] = $this->_sections['actiontypes_loop']['step'] > 0 ? 0 : $this->_sections['actiontypes_loop']['loop']-1;
if ($this->_sections['actiontypes_loop']['show']) {
    $this->_sections['actiontypes_loop']['total'] = $this->_sections['actiontypes_loop']['loop'];
    if ($this->_sections['actiontypes_loop']['total'] == 0)
        $this->_sections['actiontypes_loop']['show'] = false;
} else
    $this->_sections['actiontypes_loop']['total'] = 0;
if ($this->_sections['actiontypes_loop']['show']):

            for ($this->_sections['actiontypes_loop']['index'] = $this->_sections['actiontypes_loop']['start'], $this->_sections['actiontypes_loop']['iteration'] = 1;
                 $this->_sections['actiontypes_loop']['iteration'] <= $this->_sections['actiontypes_loop']['total'];
                 $this->_sections['actiontypes_loop']['index'] += $this->_sections['actiontypes_loop']['step'], $this->_sections['actiontypes_loop']['iteration']++):
$this->_sections['actiontypes_loop']['rownum'] = $this->_sections['actiontypes_loop']['iteration'];
$this->_sections['actiontypes_loop']['index_prev'] = $this->_sections['actiontypes_loop']['index'] - $this->_sections['actiontypes_loop']['step'];
$this->_sections['actiontypes_loop']['index_next'] = $this->_sections['actiontypes_loop']['index'] + $this->_sections['actiontypes_loop']['step'];
$this->_sections['actiontypes_loop']['first']      = ($this->_sections['actiontypes_loop']['iteration'] == 1);
$this->_sections['actiontypes_loop']['last']       = ($this->_sections['actiontypes_loop']['iteration'] == $this->_sections['actiontypes_loop']['total']);
?>
                                <?php if ($this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_desc'] != ""): ?>
                                <tr  style="color:#666666;">
                                    <td colspan="2" style="width:300px;"  style="color:#666666;">
                                        <input style="width:15px !important; height:15px !important;" type='checkbox' name='actiontype_id_<?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_id']; ?>
' id='actiontype_id_<?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_id']; ?>
' value='<?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_id']; ?>
'<?php if ($this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_selected'] == 1): ?> checked='checked'<?php endif; ?>>
                                        <?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_desc']; ?>

                                    </td>
                                    
                                </tr>
                                <?php else: ?>
                                <input type='hidden' name='actiontype_id_<?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_id']; ?>
' value='<?php echo $this->_tpl_vars['actiontypes'][$this->_sections['actiontypes_loop']['index']]['actiontype_id']; ?>
'>
                                <?php endif; ?>
                                <?php endfor; endif; ?>
                            </table>
                            <input type='hidden' name='actiontypes_max_id' value='<?php echo $this->_tpl_vars['actiontypes_max_id']; ?>
'>
                        </td>
                    </tr>
                    <?php endif; ?>

                                        <?php if ($this->_tpl_vars['user']->level_info['level_profile_block'] != 0): ?>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application396']; ?>
</td>
                        <td>
                            <table cellpadding='0' cellspacing='0'>
                                                                <?php unset($this->_sections['blocked_loop']);
$this->_sections['blocked_loop']['name'] = 'blocked_loop';
$this->_sections['blocked_loop']['loop'] = is_array($_loop=$this->_tpl_vars['blocked_users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['blocked_loop']['show'] = true;
$this->_sections['blocked_loop']['max'] = $this->_sections['blocked_loop']['loop'];
$this->_sections['blocked_loop']['step'] = 1;
$this->_sections['blocked_loop']['start'] = $this->_sections['blocked_loop']['step'] > 0 ? 0 : $this->_sections['blocked_loop']['loop']-1;
if ($this->_sections['blocked_loop']['show']) {
    $this->_sections['blocked_loop']['total'] = $this->_sections['blocked_loop']['loop'];
    if ($this->_sections['blocked_loop']['total'] == 0)
        $this->_sections['blocked_loop']['show'] = false;
} else
    $this->_sections['blocked_loop']['total'] = 0;
if ($this->_sections['blocked_loop']['show']):

            for ($this->_sections['blocked_loop']['index'] = $this->_sections['blocked_loop']['start'], $this->_sections['blocked_loop']['iteration'] = 1;
                 $this->_sections['blocked_loop']['iteration'] <= $this->_sections['blocked_loop']['total'];
                 $this->_sections['blocked_loop']['index'] += $this->_sections['blocked_loop']['step'], $this->_sections['blocked_loop']['iteration']++):
$this->_sections['blocked_loop']['rownum'] = $this->_sections['blocked_loop']['iteration'];
$this->_sections['blocked_loop']['index_prev'] = $this->_sections['blocked_loop']['index'] - $this->_sections['blocked_loop']['step'];
$this->_sections['blocked_loop']['index_next'] = $this->_sections['blocked_loop']['index'] + $this->_sections['blocked_loop']['step'];
$this->_sections['blocked_loop']['first']      = ($this->_sections['blocked_loop']['iteration'] == 1);
$this->_sections['blocked_loop']['last']       = ($this->_sections['blocked_loop']['iteration'] == $this->_sections['blocked_loop']['total']);
?>
                                <tr>
                                    <td style="padding-top:4px !important;">
                                        <input type='text' class='text' name='blocked<?php echo $this->_sections['blocked_loop']['index']; ?>
' size='25' maxlength='50' value='<?php echo $this->_tpl_vars['blocked_users'][$this->_sections['blocked_loop']['index']]; ?>
'>
                                        <?php if ($this->_sections['blocked_loop']['first']): ?><img src='./images/icons/tip.gif' border='0' class='icon' onMouseover="tip('<?php echo $this->_tpl_vars['Application397']; ?>
')"; onMouseout="hidetip()"><?php endif; ?>
                                     </td>
                                </tr>
                                <?php endfor; endif; ?>
                                <?php if ($this->_sections['blocked_loop']['total'] == 0): ?>
                                <tr>
                                    <td>
                                        <input type='text' class='text' name='blocked0' size='25' maxlength='50' value=''>
                                        <img src='./images/icons/tip.gif' border='0' class='icon' onMouseover="tip('<?php echo $this->_tpl_vars['Application397']; ?>
')"; onMouseout="hidetip()">
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td style="padding-top:4px !important;"><p id='newblock'></p></td>
                                </tr>
                                <tr>
                                    <td style="padding-top:4px !important;"><a href="javascript:addInput('newblock')"><?php echo $this->_tpl_vars['Application398']; ?>
</a></td>
                                </tr>
                            </table>
                            <input type='hidden' name='num_blocked' value='<?php echo $this->_tpl_vars['num_blocked']; ?>
'>
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                </table>

                <p class="line">&nbsp;</p>
                <?php $this->assign('redirect_page', $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username'])); ?>

                <div class="submits">
                    <label><input type="submit" value="<?php echo $this->_tpl_vars['Application399']; ?>
"/></label>
                    <label><input type="button" onclick="location.href='<?php echo $this->_tpl_vars['redirect_page']; ?>
'" value="Cancel"/></label>
                </div>

                <input type='hidden' name='task' value='dosave'/>
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