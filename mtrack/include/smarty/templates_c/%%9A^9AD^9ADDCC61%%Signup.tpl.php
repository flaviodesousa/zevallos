<?php /* Smarty version 2.6.14, created on 2011-04-24 04:57:29
         compiled from Signup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'Signup.tpl', 230, false),array('modifier', 'count', 'Signup.tpl', 285, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">


    <?php if ($this->_tpl_vars['step'] == 4): ?>
        <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application326']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application327']; ?>
</p>
    </div>

    <div id="primary" class="info-cnt tuneddivs">
        <br/>
        <?php if ($this->_tpl_vars['setting']['setting_signup_enable'] == 0): ?><p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application328']; ?>
</p><?php endif; ?>
        <?php if ($this->_tpl_vars['setting']['setting_signup_randpass'] == 1): ?><p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application329']; ?>
</p><?php endif; ?>
        <?php if ($this->_tpl_vars['setting']['setting_signup_verify'] == 0): ?><p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application330']; ?>
</p><?php else: ?><p style="padding-left:25px;"><?php echo $this->_tpl_vars['Application331']; ?>
</p><?php endif; ?>

        <?php if ($this->_tpl_vars['setting']['setting_signup_verify'] == 0 && $this->_tpl_vars['setting']['setting_signup_enable'] != 0 && $this->_tpl_vars['setting']['setting_signup_randpass'] == 0): ?>
        <form action='Login.php' method='get' class="settings" style="color:#666666; margin-top:0px !important;">

           <p class="line">&nbsp;</p>
            <div class="submits" style="margin-left:0px !important;">
                <label><input style="width:100px !important;" type="submit" value="<?php echo $this->_tpl_vars['Application332']; ?>
"/></label>
              </div>
           <input type='hidden' name='email' value='<?php echo $this->_tpl_vars['new_user']->user_info['user_email']; ?>
'/>
        </form>
        <?php else: ?>
        <form action='Home.php' method='get' class="settings" style="color:#666666; margin-top:0px !important;">
            <p class="line">&nbsp;</p>
            <div class="submits" style="margin-left:0px !important;">
                <label><input style="width:100px !important;" type="submit" value="<?php echo $this->_tpl_vars['Application333']; ?>
"/></label>
    
            </div>
        </form>
        <?php endif; ?>
    </div>


        <?php elseif ($this->_tpl_vars['step'] == 3): ?>

    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application334']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application335']; ?>
</p>
    </div>

    <div id="primary" class="info-cnt tuneddivs">
        <form action='Signup.php' method='post' class="settings" style="color:#666666;" name="invite">

            <div><b><?php echo $this->_tpl_vars['Application336']; ?>
</b></div>
            <div class='form_desc'><?php echo $this->_tpl_vars['Application337']; ?>
</div>
            <textarea style="border:1px solid #CBD0D2; width:600px;"  name='invite_emails' rows='2' cols='60'></textarea>
            <br/><br/>

            <div><b><?php echo $this->_tpl_vars['Application338']; ?>
</b></div>
            <div class='form_desc'><?php echo $this->_tpl_vars['Application339']; ?>
</div>
            <textarea style="border:1px solid #CBD0D2; width:600px;"  name='invite_message' rows='5' cols='60'></textarea>
            <br/><br/>

            <p class="line">&nbsp;</p>


            <div class="submits">
                <a class="button" href="javascript:void(0);" onclick="document.invite.submit();" style="margin-top:0px !important;"><span><?php echo $this->_tpl_vars['Application340']; ?>
</span></a>
                <a class="button" href="Signup.php?task=<?php echo $this->_tpl_vars['next_task']; ?>
&amp;step=<?php echo $this->_tpl_vars['step']; ?>
" style="margin-top:0px !important;"><span><?php echo $this->_tpl_vars['Application341']; ?>
</span></a>
            </div>

            <input type='hidden' name='task' value='<?php echo $this->_tpl_vars['next_task']; ?>
'/>
        </form>
    </div>
    <div class="block-bot"><span></span></div>


        <?php elseif ($this->_tpl_vars['step'] == 2): ?>

    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application342']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application343']; ?>
</p>
    </div>

    <div id="primary" class="">
                <?php if ($this->_tpl_vars['error_message'] != ""): ?>
        <p style="color:red; padding-left:25px;"><?php echo $this->_tpl_vars['error_message']; ?>
</p>
        <?php endif; ?>

        <form action='Signup.php' class="settings" method='post' enctype='multipart/form-data'>
            <div class="row" style="border:none;">
                                <?php if ($this->_tpl_vars['new_user']->user_photo() != ""): ?>
                <a href="#" class="f-left"><img class="img" alt="" width="92px" src="<?php echo $this->_tpl_vars['new_user']->user_photo(); ?>
"/></a>
                <?php endif; ?>
                <dl style="width:375px !important;">
                    <dt><b><?php echo $this->_tpl_vars['Application452']; ?>
</b></dt>
                    <dd><input type='file' style="height:25px !important; border:1px solid #CBD0D2; height:20px; margin:0 10px 0 0;" name='photo' size='35'/></dd>
                    <br/>
                    <p style="color:#666666; padding-top:10px;"><?php echo $this->_tpl_vars['Application345']; ?>
 <?php echo $this->_tpl_vars['new_user']->level_info['level_photo_exts']; ?>
.</p>
                </dl>
            </div>

            <p class="line">&nbsp;</p>
            <div class="submits">
                <label><input style="width:92px !important;" type="submit" value="<?php echo $this->_tpl_vars['Application344']; ?>
"/></label>
                <?php if ($this->_tpl_vars['new_user']->user_photo() != ""): ?>
                <a class="button" href="Signup.php?task=<?php echo $this->_tpl_vars['last_task']; ?>
" style="margin-top:0px !important;"><span><?php echo $this->_tpl_vars['Application346']; ?>
</span></a>
                <?php else: ?>
                <a class="button" href="Signup.php?task=<?php echo $this->_tpl_vars['last_task']; ?>
" style="margin-top:0px !important;"><span><?php echo $this->_tpl_vars['Application340']; ?>
</span></a>
                <?php endif; ?>

            </div>

            <input type='hidden' name='step' value='<?php echo $this->_tpl_vars['step']; ?>
'/>
            <input type='hidden' name='task' value='<?php echo $this->_tpl_vars['next_task']; ?>
'/>
            <input type='hidden' name='MAX_FILE_SIZE' value='5000000'/>
        </form>
    </div>

    <div class="block-bot"><span></span></div>



        <?php else: ?>
    <div class="grey-head"><h2><?php echo $this->_tpl_vars['Application349']; ?>
</h2></div>
    <div class="row-blue">
        <p class="blue"><?php echo $this->_tpl_vars['Application350']; ?>
</p>
    </div>
    <div class="layers">
        <div id="primary" class="info-cnt tuneddivs">
                        <?php if ($this->_tpl_vars['error_message'] != ""): ?>
            <p style="padding-left:25px; color:red;"> <?php echo $this->_tpl_vars['error_message']; ?>
</p>
            <br/>
            <?php endif; ?>

            <form action='Signup.php' method='post' class="settings" style="color:#666666; margin-top:0px !important;">
                <div class='signup_header'><b><?php echo $this->_tpl_vars['Application351']; ?>
</b></div>
                <table cellpadding='0' cellspacing='4' style="color:#666666;">
                    <tr>
                        <td class='form1' width='100' valign="top"><?php echo $this->_tpl_vars['Application352']; ?>
</td>
                        <td class='form2'>
                            <input name='signup_email' type='text' class='text' maxlength='70' size='40' value='<?php echo $this->_tpl_vars['signup_email']; ?>
' />
                            <div class='form_desc'><?php echo $this->_tpl_vars['Application353']; ?>
</div>
                        </td>
                    </tr>
                    <?php if ($this->_tpl_vars['setting']['setting_signup_randpass'] == 0): ?>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application354']; ?>
</td>
                        <td class='form2'>
                            <input name='signup_password' type='password' class='text' maxlength='50' size='40' value='<?php echo $this->_tpl_vars['signup_password']; ?>
' />
                            <div class='form_desc'><?php echo $this->_tpl_vars['Application355']; ?>
</div>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application356']; ?>
</td>
                        <td class='form2'>
                            <input name='signup_password2' type='password' class='text' maxlength='50' size='40' value='<?php echo $this->_tpl_vars['signup_password2']; ?>
' />
                            <div class='form_desc'><?php echo $this->_tpl_vars['Application357']; ?>
</div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <input type='hidden' name='signup_password' value='' />
                    <input type='hidden' name='signup_password2' value='' />
                    <?php endif; ?>
                </table>
                <br />
                <div class='signup_header'><b><?php echo $this->_tpl_vars['Application358']; ?>
</b></div>
                <table cellpadding='0' cellspacing='4' style="color:#666666;">
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application359']; ?>
</td>
                        <td class='form2'>
                            <input name='signup_username' type='text' class='text' maxlength='50' size='40' value='<?php echo $this->_tpl_vars['signup_username']; ?>
' />
                           <!-- <img src='./images/icons/tip.gif' border='0' class='icon' onMouseover="tip('<?php echo $this->_tpl_vars['Application360']; ?>
')"; onMouseout="hidetip()">-->
                                 <div class='form_desc'><?php echo $this->_tpl_vars['Application361']; ?>
</div>
                        </td>
                    </tr>
                    <tr>
                        <td class='form1' width='100' valign="top"><?php echo $this->_tpl_vars['Application362']; ?>
</td>
                        <td class='form2'>
                            <select name='signup_timezone'>
                                <option value='-8'<?php if ($this->_tpl_vars['signup_timezone'] == "-8"): ?> SELECTED<?php endif; ?>>Pacific Time (US &amp; Canada)</option>
                                <option value='-7'<?php if ($this->_tpl_vars['signup_timezone'] == "-7"): ?> SELECTED<?php endif; ?>>Mountain Time (US &amp; Canada)</option>
                                <option value='-6'<?php if ($this->_tpl_vars['signup_timezone'] == "-6"): ?> SELECTED<?php endif; ?>>Central Time (US &amp; Canada)</option>
                                <option value='-5'<?php if ($this->_tpl_vars['signup_timezone'] == "-5"): ?> SELECTED<?php endif; ?>>Eastern Time (US &amp; Canada)</option>
                                <option value='-4'<?php if ($this->_tpl_vars['signup_timezone'] == "-4"): ?> SELECTED<?php endif; ?>>Atlantic Time (Canada)</option>
                                <option value='-9'<?php if ($this->_tpl_vars['signup_timezone'] == "-9"): ?> SELECTED<?php endif; ?>>Alaska (US &amp; Canada)</option>
                                <option value='-10'<?php if ($this->_tpl_vars['signup_timezone'] == "-10"): ?> SELECTED<?php endif; ?>>Hawaii (US)</option>
                                <option value='-11'<?php if ($this->_tpl_vars['signup_timezone'] == "-11"): ?> SELECTED<?php endif; ?>>Midway Island, Samoa</option>
                                <option value='-12'<?php if ($this->_tpl_vars['signup_timezone'] == "-12"): ?> SELECTED<?php endif; ?>>Eniwetok, Kwajalein</option>
                                <option value='-3.3'<?php if ($this->_tpl_vars['signup_timezone'] == "-3.3"): ?> SELECTED<?php endif; ?>>Newfoundland</option>
                                <option value='-3'<?php if ($this->_tpl_vars['signup_timezone'] == "-3"): ?> SELECTED<?php endif; ?>>Brasilia, Buenos Aires, Georgetown</option>
                                <option value='-2'<?php if ($this->_tpl_vars['signup_timezone'] == "-2"): ?> SELECTED<?php endif; ?>>Mid-Atlantic</option>
                                <option value='-1'<?php if ($this->_tpl_vars['signup_timezone'] == "-1"): ?> SELECTED<?php endif; ?>>Azores, Cape Verde Is.</option>
                                <option value='0'<?php if ($this->_tpl_vars['signup_timezone'] == '0'): ?> SELECTED<?php endif; ?>>Greenwich Mean Time (Lisbon, London)</option>
                                <option value='1'<?php if ($this->_tpl_vars['signup_timezone'] == '1'): ?> SELECTED<?php endif; ?>>Amsterdam, Berlin, Paris, Rome, Madrid</option>
                                <option value='2'<?php if ($this->_tpl_vars['signup_timezone'] == '2'): ?> SELECTED<?php endif; ?>>Athens, Helsinki, Istanbul, Cairo, E. Europe</option>
                                <option value='3'<?php if ($this->_tpl_vars['signup_timezone'] == '3'): ?> SELECTED<?php endif; ?>>Baghdad, Kuwait, Nairobi, Moscow</option>
                                <option value='3.3'<?php if ($this->_tpl_vars['signup_timezone'] == "3.3"): ?> SELECTED<?php endif; ?>>Tehran</option>
                                <option value='4'<?php if ($this->_tpl_vars['signup_timezone'] == '4'): ?> SELECTED<?php endif; ?>>Abu Dhabi, Kazan, Muscat</option>
                                <option value='4.3'<?php if ($this->_tpl_vars['signup_timezone'] == "4.3"): ?> SELECTED<?php endif; ?>>Kabul</option>
                                <option value='5'<?php if ($this->_tpl_vars['signup_timezone'] == '5'): ?> SELECTED<?php endif; ?>>Islamabad, Karachi, Tashkent</option>
                                <option value='5.5'<?php if ($this->_tpl_vars['signup_timezone'] == "5.5"): ?> SELECTED<?php endif; ?>>Bombay, Calcutta, New Delhi</option>
                                <option value='6'<?php if ($this->_tpl_vars['signup_timezone'] == '6'): ?> SELECTED<?php endif; ?>>Almaty, Dhaka</option>
                                <option value='7'<?php if ($this->_tpl_vars['signup_timezone'] == '7'): ?> SELECTED<?php endif; ?>>Bangkok, Jakarta, Hanoi</option>
                                <option value='8'<?php if ($this->_tpl_vars['signup_timezone'] == '8'): ?> SELECTED<?php endif; ?>>Beijing, Hong Kong, Singapore, Taipei</option>
                                <option value='9'<?php if ($this->_tpl_vars['signup_timezone'] == '9'): ?> SELECTED<?php endif; ?>>Tokyo, Osaka, Sapporto, Seoul, Yakutsk</option>
                                <option value='9.3'<?php if ($this->_tpl_vars['signup_timezone'] == "9.3"): ?> SELECTED<?php endif; ?>>Adelaide, Darwin</option>
                                <option value='10'<?php if ($this->_tpl_vars['signup_timezone'] == '10'): ?> SELECTED<?php endif; ?>>Brisbane, Melbourne, Sydney, Guam</option>
                                <option value='11'<?php if ($this->_tpl_vars['signup_timezone'] == '11'): ?> SELECTED<?php endif; ?>>Magadan, Soloman Is., New Caledonia</option>
                                <option value='12'<?php if ($this->_tpl_vars['signup_timezone'] == '12'): ?> SELECTED<?php endif; ?>>Fiji, Kamchatka, Marshall Is., Wellington</option>
                            </select>
                        </td>
                    </tr>
                    <?php if ($this->_tpl_vars['setting']['setting_signup_phone'] == 1): ?>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application739']; ?>
:</td>
                        <td class='form2'>
                            <input name='signup_phone' type='text' class='text' maxlength='50' size='40' value='<?php echo $this->_tpl_vars['signup_phone']; ?>
' />
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['setting']['setting_lang_allow'] == 1): ?>
                    <tr>
                        <td class='form1' valign="top"><?php echo $this->_tpl_vars['Application322']; ?>
</td>
                        <td class='form2'>
                            <select name='signup_lang'>
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
'<?php if (((is_array($_tmp=$this->_tpl_vars['setting']['setting_lang_default'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == ((is_array($_tmp=$this->_tpl_vars['lang_options'][$this->_sections['lang_loop']['index']])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp))): ?> selected='selected'<?php endif; ?>><?php echo $this->_tpl_vars['lang_options'][$this->_sections['lang_loop']['index']]; ?>
</option>
                                <?php endfor; endif; ?>
                            </select>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table><br/>
                <?php if (! $this->_tpl_vars['setting']['setting_signup_phone'] == 1): ?>
                <input name='signup_phone' type='hidden' value='' />
                <?php endif; ?>
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
                <div class='signup_header'><b><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_name']; ?>
</b></div>
                <table cellpadding='0' cellspacing='4' style="color:#666666;">
                                        <?php unset($this->_sections['field_loop']);
$this->_sections['field_loop']['name'] = 'field_loop';
$this->_sections['field_loop']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <tr>
                        <td class='form1' width='100' valign="top"><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_title'];  if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_required'] != 0): ?>*<?php endif; ?></td>
                        <td class='form2'>
                            <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 1): ?>
                                                        <div><input type='text' class='text' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_maxlength']; ?>
' /></div>
                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                            <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 2): ?>
                                                        <div><textarea rows='6' cols='50' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
</textarea></div>
                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                                                        <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 3): ?>
                            <div>
                                <select name="field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
" id="field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
" onchange="ShowHideSelectDeps(<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
)" style="width:75px !important;">
                                    <option value='-1'>&nbsp;</option>
                                                                        <?php unset($this->_sections['option_loop']);
$this->_sections['option_loop']['name'] = 'option_loop';
$this->_sections['option_loop']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                    <option id='op<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</option>
                                    <?php endfor; endif; ?>
                                </select>
                            </div>
                                                        <?php unset($this->_sections['option_loop']);
$this->_sections['option_loop']['name'] = 'option_loop';
$this->_sections['option_loop']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_dependency'] == 1): ?>
                            <div id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_option<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' style='margin: 5px 5px 10px 5px;<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] != $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> display: none;<?php endif; ?>'>
                                <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_title'];  if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_required'] != 0): ?>*<?php endif; ?>
                                <input type='text' class='text' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_value']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_maxlength']; ?>
' />
                            </div>
                            <?php else: ?>
                            <div id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_option<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' style='display: none;'></div>
                            <?php endif; ?>
                            <?php endfor; endif; ?>
                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                                                        <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 4): ?>
                                                        <?php unset($this->_sections['option_loop']);
$this->_sections['option_loop']['name'] = 'option_loop';
$this->_sections['option_loop']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                            <div style="clear:both; margin-left: 100px">
                                <input type='radio' class='radio' onclick="ShowHideRadioDeps(<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
, <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
, 'field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
', <?php echo count($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options']); ?>
)" style='width: 15px !important; <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' id='label_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> CHECKED<?php endif; ?> />
                                       <label style="clear: none" for='label_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</label>
                                                                <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_dependency'] == 1): ?>
                                <div id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_radio<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' style='margin: 0px 5px 10px 23px;<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] != $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> display: none;<?php endif; ?>'>
                                    <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_title']; ?>

                                    <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_required'] != 0): ?>*<?php endif; ?>
                                    <input type='text' class='text' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
' id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_value']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_maxlength']; ?>
' />
                                </div>
                                <?php else: ?>
                                <div id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_radio<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' style='display: none;'></div>
                                <?php endif; ?>

                            </div>
                            <?php endfor; endif; ?>
                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                            <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 5): ?>
                                                        <div>
                                <select name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_1' style="width:75px !important; margin-right:2px !important;">
                                    <?php unset($this->_sections['date1']);
$this->_sections['date1']['name'] = 'date1';
$this->_sections['date1']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array1']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                    <option value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array1'][$this->_sections['date1']['index']]['name']; ?>
</option>
                                    <?php endfor; endif; ?>
                                </select>
                                <select name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_2' style="width:75px !important; margin-right:2px !important;">
                                    <?php unset($this->_sections['date2']);
$this->_sections['date2']['name'] = 'date2';
$this->_sections['date2']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array2']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                    <option value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array2'][$this->_sections['date2']['index']]['name']; ?>
</option>
                                    <?php endfor; endif; ?>
                                </select>
                                <select name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_3' style="width:75px !important; margin-right:2px !important;">
                                    <?php unset($this->_sections['date3']);
$this->_sections['date3']['name'] = 'date3';
$this->_sections['date3']['loop'] = is_array($_loop=$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array3']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                    <option value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['value']; ?>
'<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['selected']; ?>
><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['date_array3'][$this->_sections['date3']['index']]['name']; ?>
</option>
                                    <?php endfor; endif; ?>
                                </select>
                            </div>
                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_error'] != ""): ?><div class='form_error'> <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_error']; ?>
</div><?php endif; ?>
                        </td>
                    </tr>
                    <?php endfor; endif; ?>
                </table>
                <br />
                <?php endfor; endif; ?>


                <?php if ($this->_tpl_vars['setting']['setting_signup_code'] == 1 || $this->_tpl_vars['setting']['setting_signup_tos'] == 1 || $this->_tpl_vars['setting']['setting_signup_invite'] != 0): ?>
                <br />
                <div class='signup_header'><b><?php echo $this->_tpl_vars['Application363']; ?>
</b></div>
                <table cellpadding='0' cellspacing='4'  style="color:#666666;">
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['setting']['setting_signup_invite'] != 0): ?>
                    <tr>
                        <td class='form1' width='100' valign="top"><?php echo $this->_tpl_vars['Application364']; ?>
</td>
                        <td class='form2'><input type='text' name='signup_invite' value='<?php echo $this->_tpl_vars['signup_invite']; ?>
' class='text' size='10' maxlength='10' style="width:95px !important;"/></td>
                    </tr>
                    <?php endif; ?>


                    <?php if ($this->_tpl_vars['setting']['setting_signup_code'] == 1): ?>
                    <tr>
                        <td class='form1' width='100' valign="top"><?php echo $this->_tpl_vars['Application365']; ?>
</td>
                        <td class='form2'>
                            <table cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td><input type='text' name='signup_secure' class='text' size='6' maxlength='10' style="width:95px !important;"/>&nbsp;</td>
                                    <td>
                                        <table cellpadding='0' cellspacing='0'>
                                            <tr>
                                                <td><img src='./images/secure.php' border='0' height='20' width='67' class='signup_code'/>&nbsp;&nbsp;</td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['setting']['setting_signup_tos'] == 1): ?>
                    <tr>
                        <td class='form1' width='100' valign="top" align="center"><input style="width:15px !important; height:15px; border:none;" type='checkbox' name='signup_agree' id='tos' value='1'<?php if ($this->_tpl_vars['signup_agree'] == 1): ?> CHECKED<?php endif; ?>/></td>
                        <td class='form2'><label for='tos' style="width:250px !important;"> <?php echo $this->_tpl_vars['Application367']; ?>
</label></td>
                    </tr>
                    <?php endif; ?>
                </table>
                <p class="line">&nbsp;</p>
                <div class="submits" style="margin-left:20px !important;">
                    <label><input style="width:100px !important;"type="submit" value="<?php echo $this->_tpl_vars['Application332']; ?>
"/></label>
                </div>


                <input type='hidden' name='task' value='<?php echo $this->_tpl_vars['next_task']; ?>
' />
                <input type='hidden' name='step' value='<?php echo $this->_tpl_vars['step']; ?>
' />
            </form>
        </div>
    </div>
    <div class="block-bot"><span>&nbsp;</span></div>
    <?php endif; ?>

</div>
<div id="sidebar">

</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>