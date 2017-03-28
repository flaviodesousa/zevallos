<?php /* Smarty version 2.6.14, created on 2011-04-24 05:01:35
         compiled from Profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'Profile.tpl', 129, false),array('modifier', 'choptext', 'Profile.tpl', 169, false),array('modifier', 'count', 'Profile.tpl', 239, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script type=\'text/javascript\'>
<!--
var comment_changed = 0;
var first_comment = 1;
var last_comment = ';  if ($this->_tpl_vars['comments'][0]['comment_id']):  echo $this->_tpl_vars['comments'][0]['comment_id'];  else: ?>0<?php endif;  echo ';
var next_comment = last_comment+1;
var total_comments = ';  echo $this->_tpl_vars['total_comments'];  echo ';

function removeText(commentBody) {
  if(comment_changed == 0) {
    commentBody.value=\'\';
    commentBody.style.color=\'#000000\';
    comment_changed = 1;
  }
}

function addText(commentBody) {
  if(commentBody.value == \'\') {
    commentBody.value = \'';  echo $this->_tpl_vars['Application230'];  echo '\';
    commentBody.style.color = \'#888888\';
    comment_changed = 0;
  }
}

function checkText() {
  if(comment_changed == 0) { 
    var commentBody = document.getElementById(\'comment_body\');
    commentBody.value=\'\'; 
  }
  var commentSubmit = document.getElementById(\'comment_submit\');
  commentSubmit.value = \'';  echo $this->_tpl_vars['Application231'];  echo '\';
  commentSubmit.disabled = true;
  
}

function deleteComment(id) {
  document.getElementById("comment_" + id).style.display = "none";
  $.ajax({
    type: "GET",
    url: "UserEditprofileComments.php?comment_id=" + id,
    dataType: "script"
  });  
}

function addComment(is_error, comment_body, comment_date, comment_id) {
  if(is_error == 1) {
    var commentError = document.getElementById(\'comment_error\');
    commentError.style.display = \'block\';
    if(comment_body == \'\') {
      commentError.innerHTML = \'';  echo $this->_tpl_vars['Application232'];  echo '\';
    } else {
      commentError.innerHTML = \'';  echo $this->_tpl_vars['Application233'];  echo '\';
    }
    var commentSubmit = document.getElementById(\'comment_submit\');
    commentSubmit.value = \'';  echo $this->_tpl_vars['Application234'];  echo '\';
    commentSubmit.disabled = false;
  } else {
    var commentError = document.getElementById(\'comment_error\');
    commentError.style.display = \'none\';
    commentError.innerHTML = \'\';

    var commentBody = document.getElementById(\'comment_body\');
    commentBody.value = \'\';
    addText(commentBody);

    var commentSubmit = document.getElementById(\'comment_submit\');
    commentSubmit.value = \'';  echo $this->_tpl_vars['Application234'];  echo '\';
    commentSubmit.disabled = false;

    if(document.getElementById(\'comment_secure\')) {
      var commentSecure = document.getElementById(\'comment_secure\');
      commentSecure.value=\'\'
      var secureImage = document.getElementById(\'secure_image\');
      secureImage.src = secureImage.src + \'?\' + (new Date()).getTime();
    }

    total_comments++;
    var totalComments = document.getElementById(\'total_comments\');
    totalComments.innerHTML = total_comments;

    if(total_comments > 10) {
      var oldComment = document.getElementById(\'comment_\'+first_comment);
      if(oldComment) { oldComment.style.display = \'none\'; first_comment++; }
    }

    var newComment = document.createElement(\'div\');
    var divIdName = \'comment_\'+comment_id;
    newComment.setAttribute(\'id\',divIdName);

    var newTable = "<div class=\'row post\'><div class=\'w80 fleft\'>";
    '; ?>

      <?php if ($this->_tpl_vars['user']->user_info['user_id'] != 0): ?>
        newTable += "<a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username']); ?>
'><img src='<?php echo $this->_tpl_vars['user']->user_photo('./images/nophoto.gif'); ?>
' class='photo' border='0' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['user']->user_photo('./images/nophoto.gif'),'75','75','w'); ?>
'></a>";
      <?php else: ?>
        newTable += "<img src='./images/nophoto.gif' class='photo' border='0' width='75'>";
      <?php endif; ?>
      newTable += "</div><div class='fright w490'><div class='grey w490 mb10'><div class='f-right'>2 <?php echo $this->_tpl_vars['Application22']; ?>
</div><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['user']->user_info['user_username']); ?>
'><?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
</a><?php if ($this->_tpl_vars['user']->user_info['user_id'] == $this->_tpl_vars['owner']->user_info['user_id']): ?>&nbsp;<a style='margin-left: 250px' href='javascript:deleteComment(" + comment_id + ")'><?php echo $this->_tpl_vars['Application660']; ?>
</a><?php endif;  echo '</div><div class=\'wall-img w490\'>" + comment_body + "</div></div></div>";
    newComment.innerHTML = newTable;
    var profileComments = document.getElementById(\'profile_comments\');
    var prevComment = document.getElementById(\'comment_\'+last_comment);
    profileComments.insertBefore(newComment, prevComment);
    last_comment = comment_id;
    document.getElementById(\'comment_form\').reset();
  }
}


//-->
</script>
'; ?>



            <div id="content">
		<div class="block-top"><span></span></div>
				
				
                <div class="pfl">				
                    <?php if ($this->_tpl_vars['owner']->user_info['user_username'] != $this->_tpl_vars['user']->user_info['user_username']): ?>
                    <br/><img width="174px" src='<?php echo $this->_tpl_vars['owner']->user_photo("./images/nophoto.gif"); ?>
' class="img"/>
                    <?php if (( $this->_tpl_vars['total_friends'] != 0 ) || ( $this->_tpl_vars['friendship_allowed'] != 0 && $this->_tpl_vars['user']->user_exists != 0 ) || ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 2 || ( ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) && ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) ) || ( $this->_tpl_vars['user']->level_info['level_profile_block'] != 0 )): ?>
                    <br/><br/>
                    <div class="block">
                        <div class="block-top"><span></span></div>
                        <ul>
                            <?php if ($this->_tpl_vars['total_friends'] != 0): ?><li<?php if (( ! ( ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) && ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) ) && $this->_tpl_vars['user']->level_info['level_profile_block'] == 0 && ( ! ( $this->_tpl_vars['friendship_allowed'] != 0 && $this->_tpl_vars['user']->user_exists != 0 ) ) )): ?> class="last-li"<?php endif; ?>><a href='ProfileFriends.php?user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application199']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['owner']->user_info['user_username'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 10, "...", true) : smarty_modifier_truncate($_tmp, 10, "...", true));  echo $this->_tpl_vars['Application200']; ?>
</a></li><?php endif; ?>
                            <?php if ($this->_tpl_vars['friendship_allowed'] != 0 && $this->_tpl_vars['user']->user_exists != 0): ?>
                              <?php if ($this->_tpl_vars['is_friend'] == TRUE): ?>
                                <li<?php if (( ! ( ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) && ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) ) && $this->_tpl_vars['user']->level_info['level_profile_block'] == 0 )): ?> class="last-li"<?php endif; ?>><a href='UserFriendsConfirm.php?task=remove&user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
&return_url=<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['owner']->user_info['user_username']); ?>
'><?php echo $this->_tpl_vars['Application227']; ?>
</a></li>
                              <?php else: ?>
                                <li<?php if (( ! ( ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) && ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) ) && $this->_tpl_vars['user']->level_info['level_profile_block'] == 0 )): ?> class="last-li"<?php endif; ?>><a href='UserFriendsAdd.php?user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application201']; ?>
</a></li>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (( $this->_tpl_vars['user']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['user']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) ) && ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 2 || ( $this->_tpl_vars['owner']->level_info['level_message_allow'] == 1 && $this->_tpl_vars['is_friend'] ) )): ?>
                              <li<?php if ($this->_tpl_vars['user']->level_info['level_profile_block'] == 0): ?> class="last-li"<?php endif; ?>><a href='UserMessagesNew.php?to=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application202']; ?>
</a></li>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['user']->level_info['level_profile_block'] != 0): ?>
                              <?php if ($this->_tpl_vars['user']->user_blocked($this->_tpl_vars['owner']->user_info['user_id']) == TRUE): ?>
                                <li class="last-li"><a href='UserFriendsBlock.php?task=unblock&user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application228']; ?>
</a></li>
                              <?php else: ?>
                                <li class="last-li"><a href='UserFriendsBlock.php?task=block&user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application204']; ?>
</a></li>
                              <?php endif; ?>
                            <?php endif; ?>
                        </ul>  
                        <div class="block-bot"><span></span></div>
                    </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <br/>
                    <div style="position: relative; overflow: visible">
                        <img width="174px" src='<?php echo $this->_tpl_vars['user']->user_photo("./images/nophoto.gif"); ?>
' class="img"/>
                        <?php if ($this->_tpl_vars['user']->user_photo("") == ""): ?>
                        <a style="position: absolute; top: 120px; left: 65px;" href="./UserEditprofilePhoto.php">Set Avatar</a>
                        <?php endif; ?>
                    </div>
                    <br/><br/>
                    <?php endif; ?>
                </div>
				
				<h3 class="stat w385">
				    <?php if ($this->_tpl_vars['user']->user_info['user_id'] == $this->_tpl_vars['owner']->user_info['user_id']): ?><a href="#" class="f-right set-status">change status</a><?php endif; ?>
				    <b><?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>

				    
				    <?php if ($this->_tpl_vars['is_online'] == 1):  echo $this->_tpl_vars['Application213'];  endif; ?></b>&nbsp;
				    <?php if ($this->_tpl_vars['owner']->level_info['level_profile_status'] != 0 && $this->_tpl_vars['owner']->user_info['user_status'] != "" && $this->_tpl_vars['owner']->user_info['user_id'] == $this->_tpl_vars['user']->user_info['user_id']): ?>
                    <span class="status-text"><?php echo ((is_array($_tmp=$this->_tpl_vars['owner']->user_info['user_status'])) ? $this->_run_mod_handler('choptext', true, $_tmp, 12, "<br>") : smarty_modifier_choptext($_tmp, 12, "<br>")); ?>
</span>
                    <?php endif; ?>
				    <?php if ($this->_tpl_vars['owner']->level_info['level_profile_status'] != 0 && $this->_tpl_vars['owner']->user_info['user_status'] != "" && $this->_tpl_vars['owner']->user_info['user_id'] != $this->_tpl_vars['user']->user_info['user_id']): ?>
                    <span class="status-text2"><?php echo ((is_array($_tmp=$this->_tpl_vars['owner']->user_info['user_status'])) ? $this->_run_mod_handler('choptext', true, $_tmp, 12, "<br>") : smarty_modifier_choptext($_tmp, 12, "<br>")); ?>
</span>
                    <?php endif; ?>
				    <span class="status"><input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['owner']->user_info['user_status'])) ? $this->_run_mod_handler('choptext', true, $_tmp, 12, "<br>") : smarty_modifier_choptext($_tmp, 12, "<br>")); ?>
"/></span>
				    <input type="hidden" style="display:none" id="status_username" value="<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
" />
			    </h3>
			    <?php if ($this->_tpl_vars['is_private_profile']): ?>
			          <h2><?php echo $this->_tpl_vars['Application195']; ?>
</h2>
                      <?php echo $this->_tpl_vars['Application196']; ?>

                <?php else: ?>

                	<dl class="w385">
                        <dt><?php echo $this->_tpl_vars['Application208']; ?>
</dt><dd style="width: 250px !important"><?php echo $this->_tpl_vars['total_views']; ?>
 <?php echo $this->_tpl_vars['Application209']; ?>
</dd>
                        <?php if ($this->_tpl_vars['setting']['setting_connection_allow'] != 0): ?><dt><?php echo $this->_tpl_vars['Application210']; ?>
</dt><dd style="width: 250px !important"><?php echo $this->_tpl_vars['total_friends']; ?>
 <?php echo $this->_tpl_vars['Application211']; ?>
</dd><?php endif; ?>
                        <?php if ($this->_tpl_vars['owner']->user_info['user_dateupdated'] != ""): ?><dt><?php echo $this->_tpl_vars['Application214']; ?>
</dt><dd style="width: 250px !important"><?php echo $this->_tpl_vars['datetime']->time_since($this->_tpl_vars['owner']->user_info['user_dateupdated']); ?>
</dd><?php endif; ?>
                        <?php if ($this->_tpl_vars['owner']->user_info['user_signupdate'] != ""): ?><dt><?php echo $this->_tpl_vars['Application215']; ?>
</dt><dd style="width: 250px !important"><?php echo $this->_tpl_vars['datetime']->cdate(($this->_tpl_vars['setting']['setting_dateformat']),$this->_tpl_vars['datetime']->timezone(($this->_tpl_vars['owner']->user_info['user_signupdate']),$this->_tpl_vars['global_timezone'])); ?>
</dd><?php endif; ?>
                    </dl>
                    
                    <ul class="accordion">
                        <li class="active"><a href="#" class="opener active"><h2>Information</h2></a>
                         	<div class="slide">
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
                               	<h3><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['tab_name']; ?>
 <?php if ($this->_tpl_vars['owner']->user_info['user_id'] == $this->_tpl_vars['user']->user_info['user_id']): ?><span style="width:30px; display:block;float:right; position: relative"><a href="#" class="f-right edit-info save-btn" style="position: absolute;display:none;top:0;">save</a>&nbsp;<a href="#" class="f-right edit-info edit-btn" style="position: absolute; top:0;">edit</a><?php endif; ?></span></h3>
                                <dl>
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
                                    <dt><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_title']; ?>
:</dt>
                                    <dd>
                                        <div class="field-case">
                                                                                        <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 1): ?>
                                            <input type='text' class='text' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_maxlength']; ?>
'/>
                        
                                                                                        <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 2): ?>
                                            <textarea rows='6' cols='50' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
</textarea>
                                            <div class='form_desc'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_desc']; ?>
</div>
                        
                                                                                        <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 3): ?>
                                            <select name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' id='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' onchange="ShowHideSelectDeps(<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
)" style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
'>
                                                <option value='-1'></option>
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
                                                <option id='op' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</option>
                                                <?php endfor; endif; ?>
                                            </select>
                        
                                                                    
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
                        
                                            <div>
                                                <input type='radio' class='radio' onclick="ShowHideRadioDeps(<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
, <?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
, 'field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['dep_field_id']; ?>
', <?php echo count($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options']); ?>
)" style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' id='label_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
' value='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'<?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id'] == $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']): ?> CHECKED<?php endif; ?>>
                                                       <label for='label_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_id']; ?>
'><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_options'][$this->_sections['option_loop']['index']]['option_label']; ?>
</label>
                        
                                                                        
                                            </div>
                                            <?php endfor; endif; ?>
                        
                        
                                                                                        <?php elseif ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_type'] == 5): ?>
                                            <input type='text' class='text' name='field_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
' value='<?php echo $this->_tpl_vars['datetime']->cdate(($this->_tpl_vars['setting']['setting_dateformat']),$this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']); ?>
' style='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_style']; ?>
' maxlength='<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_maxlength']; ?>
'/>

                                            <?php endif; ?>    
                                                                                    
                                            <!-- input type="text" name="profilevalue_<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
" value="<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']; ?>
" -->
                                        </div>
                                        <div id="<?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_id']; ?>
" class="field-val"><?php echo $this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value_profile']; ?>

                                        <?php if ($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_birthday'] == 1): ?> (<?php echo $this->_tpl_vars['datetime']->age($this->_tpl_vars['tabs'][$this->_sections['tab_loop']['index']]['fields'][$this->_sections['field_loop']['index']]['field_value']); ?>
 <?php echo $this->_tpl_vars['Application224']; ?>
)<?php endif; ?></div>
                                    </dd>
                                    <?php endfor; endif; ?>
                                </dl>
                                <?php endfor; endif; ?>
                                                    
                            </div>
                        </li>
                                                <li class="active"><a href="#" class="opener active"><h2> <?php echo $this->_tpl_vars['Application225']; ?>
 (<span id='total_comments'><?php echo $this->_tpl_vars['total_comments']; ?>
</span>)</h2></a>
                            <div class="slide" style="padding:0; width: 640px">
                                <div class="row-blue">
                                    <div class="f-right" style="padding-right:5px;">
                                        <?php if ($this->_tpl_vars['allowed_to_comment'] != 0): ?><a style="padding-left: 10px" href="#" class="oth-func-link">other functions ></a><?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($this->_tpl_vars['allowed_to_comment'] != 0): ?>
                                <form action='ProfileComments.php' id="comment_form" method='post' target='AddCommentWindow' enctype="multipart/form-data" onSubmit='checkText()'>
                                <div class="oth-func-box">
                                    <div class="sl">
                                        <a href="#audio"><?php echo $this->_tpl_vars['Application745']; ?>
</a>
                                        <a href="#photo"><?php echo $this->_tpl_vars['Application744']; ?>
</a>
                                        <a href="#video"><?php echo $this->_tpl_vars['Application746']; ?>
</a>
                                    </div>
                                </div>
                                <div class="func-box audio">
                                    <div class="sl">
                                      <table cellpadding='0' cellspacing='0' width='100%'>
                                      <tr>
                                      <td width="100" align='left'>
                                          <?php echo $this->_tpl_vars['Application745']; ?>

                                      </td>
                                      <td colspan="3" align='left' style='padding-top: 5px;'>
                                          <input type="file" name="comment_mp3" width="200" />
                                      </td>
                                      </tr>
                                      <tr>
                                      </table>
                                    </div>
                                </div>
                                <div class="func-box photo">
                                    <div class="sl">
                                      <table cellpadding='0' cellspacing='0' width='100%'>
                                      <tr>
                                      <td width="100" align='left'>
                                          <?php echo $this->_tpl_vars['Application744']; ?>

                                      </td>
                                      <td colspan="3" align='left'>
                                          <input type="file" name="comment_image" width="200"/>
                                      </td>
                                      </tr>
                                      </table>
                                    </div>
                                </div>
                                <div class="func-box video">
                                    <div class="sl">
                                      <table cellpadding='0' cellspacing='0' width='100%'>
                                      <tr>
                                      <td width="100" align='left'>
                                          <?php echo $this->_tpl_vars['Application746']; ?>

                                      </td>
                                      <td colspan="3" align='left'>
                                          <input type="text" name="comment_video" width="200"/>
                                      </td>
                                      </tr>
                                      </table>
                                    </div>
                                </div>
                                <div class="leave-msg-box" style="display:block">
                                    <div class="sl">
                                        <div id='comment_error' style='color: #FF0000; display: none;'></div>

                                          <textarea name='comment_body' id='comment_body' rows='2' cols='65' style="width:600px" onfocus='removeText(this)' onblur='addText(this)' class='comment_area'><?php echo $this->_tpl_vars['Application230']; ?>
</textarea>
                                          
                                          <table cellpadding='0' cellspacing='0' width='100%'>
                                          <tr>
                                          <?php if ($this->_tpl_vars['setting']['setting_comment_code'] == 1): ?>
                                            <td width='75' valign='top'><img src='./images/secure.php' id='secure_image' border='0' height='20' width='67' class='signup_code'></td>
                                            <td width='68' style='padding-top: 4px;'><input type='text' name='comment_secure' id='comment_secure' class='text' size='6' maxlength='10'></td>
                                            <td width='10'><img src='./images/icons/tip.gif' border='0' class='icon' onMouseover="tip('<?php echo $this->_tpl_vars['Application235']; ?>
')"; onMouseout="hidetip()"></td>
                                          <?php endif; ?>
                                          <td align='right' valign="top" style='padding-top: 5px; padding-left:10px'>
                                          <input type='submit' id='comment_submit' value='<?php echo $this->_tpl_vars['Application234']; ?>
'>
                                          <input type='hidden' name='user' value='<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
'>
                                          <input type='hidden' name='task' value='dopost'>
                                          </form>
                                          </td>
                                          </tr>
                                          </table>
                                    </div>
                                </div>
                                <iframe name='AddCommentWindow' style='width: 100px; height: 100px; display: none' src=''></iframe>
                                </form>
                                <?php endif; ?>
                             <div id="profile_comments">
                                <div style="float: right; padding:20px; padding-bottom: 0;">
                                    <?php if ($this->_tpl_vars['prev']): ?><a href="Profile.php?user=<?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
&amp;p=<?php echo $this->_tpl_vars['prev']; ?>
"><?php echo $this->_tpl_vars['Application241']; ?>
</a><?php endif; ?>
                                    <?php if ($this->_tpl_vars['next']): ?><a href="Profile.php?user=<?php echo $this->_tpl_vars['user']->user_info['user_username']; ?>
&amp;p=<?php echo $this->_tpl_vars['next']; ?>
"><?php echo $this->_tpl_vars['Application245']; ?>
</a><?php endif; ?>
                                </div>
                                <?php unset($this->_sections['comment_loop']);
$this->_sections['comment_loop']['name'] = 'comment_loop';
$this->_sections['comment_loop']['loop'] = is_array($_loop=$this->_tpl_vars['comments']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['comment_loop']['show'] = true;
$this->_sections['comment_loop']['max'] = $this->_sections['comment_loop']['loop'];
$this->_sections['comment_loop']['step'] = 1;
$this->_sections['comment_loop']['start'] = $this->_sections['comment_loop']['step'] > 0 ? 0 : $this->_sections['comment_loop']['loop']-1;
if ($this->_sections['comment_loop']['show']) {
    $this->_sections['comment_loop']['total'] = $this->_sections['comment_loop']['loop'];
    if ($this->_sections['comment_loop']['total'] == 0)
        $this->_sections['comment_loop']['show'] = false;
} else
    $this->_sections['comment_loop']['total'] = 0;
if ($this->_sections['comment_loop']['show']):

            for ($this->_sections['comment_loop']['index'] = $this->_sections['comment_loop']['start'], $this->_sections['comment_loop']['iteration'] = 1;
                 $this->_sections['comment_loop']['iteration'] <= $this->_sections['comment_loop']['total'];
                 $this->_sections['comment_loop']['index'] += $this->_sections['comment_loop']['step'], $this->_sections['comment_loop']['iteration']++):
$this->_sections['comment_loop']['rownum'] = $this->_sections['comment_loop']['iteration'];
$this->_sections['comment_loop']['index_prev'] = $this->_sections['comment_loop']['index'] - $this->_sections['comment_loop']['step'];
$this->_sections['comment_loop']['index_next'] = $this->_sections['comment_loop']['index'] + $this->_sections['comment_loop']['step'];
$this->_sections['comment_loop']['first']      = ($this->_sections['comment_loop']['iteration'] == 1);
$this->_sections['comment_loop']['last']       = ($this->_sections['comment_loop']['iteration'] == $this->_sections['comment_loop']['total']);
?>
                                <div class="row post" id='comment_<?php echo $this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_id']; ?>
'>
                                    <div class="w120 fleft">
                                    <?php if ($this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_id'] != 0): ?>
                                       <a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_username']); ?>
'><img src='<?php echo $this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_photo('./images/nophoto.gif'); ?>
' class='photo' border='0' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_photo('./images/nophoto.gif'),'75','75','w'); ?>
'></a>
                                    <?php else: ?>
                                       <img src='./images/nophoto.gif' class='photo' border='0' width='75'>
                                    <?php endif; ?>
                                    </div>
                                    <div class="fleft w490">
                                    <div class="grey w490 mb10"><div class="f-right"><?php echo $this->_tpl_vars['datetime']->cdate(($this->_tpl_vars['setting']['setting_timeformat'])." ".($this->_tpl_vars['Application212'])." ".($this->_tpl_vars['setting']['setting_dateformat']),$this->_tpl_vars['datetime']->timezone($this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_date'],$this->_tpl_vars['global_timezone'])); ?>
</div><?php if ($this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_id'] != 0): ?><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_username']); ?>
'><?php echo $this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_username']; ?>
</a><?php else:  echo $this->_tpl_vars['Application33'];  endif;  if ($this->_tpl_vars['user']->user_info['user_id'] == $this->_tpl_vars['owner']->user_info['user_id']): ?>&nbsp;<a style="margin-left: 250px" href="javascript:deleteComment(<?php echo $this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_id']; ?>
)"><?php echo $this->_tpl_vars['Application660']; ?>
</a><?php endif; ?></div>
                                    <div class="wall-img w490">
                                        <?php echo ((is_array($_tmp=$this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_body'])) ? $this->_run_mod_handler('choptext', true, $_tmp, 50, "<br>") : smarty_modifier_choptext($_tmp, 50, "<br>")); ?>

                                    </div>
                                    <?php if ($this->_tpl_vars['user']->user_info['user_exists']): ?>
                                    <div class="navi blue w490">
                                        <?php if ($this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_id'] != 0): ?>
                                               <a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_username']); ?>
#comments'><?php echo $this->_tpl_vars['Application218']; ?>
</a>&nbsp;|&nbsp;
                                               <a href='UserMessagesNew.php?to=<?php echo $this->_tpl_vars['comments'][$this->_sections['comment_loop']['index']]['comment_author']->user_info['user_username']; ?>
'><?php echo $this->_tpl_vars['Application221']; ?>
</a><?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php endfor; endif; ?>
                             </div>
                                                <?php endif; ?>
                        </div>
                    </li>
                </ul>
			<div class="block-bot"><span></span></div>
            </div>  
            <div id="sidebar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'MenuSidebar.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                                <?php if ($this->_tpl_vars['total_friends'] != 0): ?>
                <div class="block">
                        <ul class="accordion">
                            <li class="form-top active"><a href="#" class="opener active"><?php echo $this->_tpl_vars['Application222']; ?>
 (<?php echo $this->_tpl_vars['total_friends']; ?>
)</a>
                            	<div class="slide">
                                	<div class="row-blue blue"><a href="ProfileFriends.php?user=<?php echo $this->_tpl_vars['owner']->user_info['user_username']; ?>
" class="f-right"><?php echo $this->_tpl_vars['Application217']; ?>
 <?php echo $this->_tpl_vars['Application211']; ?>
</a></div>
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
                                	<div class="user"><a href='<?php echo $this->_tpl_vars['url']->url_create('profile',$this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']); ?>
'><img src='<?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_photo('./images/nophoto.gif'); ?>
' class='photo' border='0' width='<?php echo $this->_tpl_vars['misc']->photo_size($this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_photo('./images/nophoto.gif'),'75','75','w'); ?>
'><br><?php echo $this->_tpl_vars['friends'][$this->_sections['friend_loop']['index']]->user_info['user_username']; ?>
</a></div>
                                    <?php endfor; endif; ?>
                                <div class="block-bot"><span></span></div>
                                </div>
                            </li>
                        </ul>
				</div>
                <?php endif; ?>
                
                                <?php unset($this->_sections['profile_loop']);
$this->_sections['profile_loop']['name'] = 'profile_loop';
$this->_sections['profile_loop']['loop'] = is_array($_loop=$this->_tpl_vars['global_plugins']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['profile_loop']['show'] = true;
$this->_sections['profile_loop']['max'] = $this->_sections['profile_loop']['loop'];
$this->_sections['profile_loop']['step'] = 1;
$this->_sections['profile_loop']['start'] = $this->_sections['profile_loop']['step'] > 0 ? 0 : $this->_sections['profile_loop']['loop']-1;
if ($this->_sections['profile_loop']['show']) {
    $this->_sections['profile_loop']['total'] = $this->_sections['profile_loop']['loop'];
    if ($this->_sections['profile_loop']['total'] == 0)
        $this->_sections['profile_loop']['show'] = false;
} else
    $this->_sections['profile_loop']['total'] = 0;
if ($this->_sections['profile_loop']['show']):

            for ($this->_sections['profile_loop']['index'] = $this->_sections['profile_loop']['start'], $this->_sections['profile_loop']['iteration'] = 1;
                 $this->_sections['profile_loop']['iteration'] <= $this->_sections['profile_loop']['total'];
                 $this->_sections['profile_loop']['index'] += $this->_sections['profile_loop']['step'], $this->_sections['profile_loop']['iteration']++):
$this->_sections['profile_loop']['rownum'] = $this->_sections['profile_loop']['iteration'];
$this->_sections['profile_loop']['index_prev'] = $this->_sections['profile_loop']['index'] - $this->_sections['profile_loop']['step'];
$this->_sections['profile_loop']['index_next'] = $this->_sections['profile_loop']['index'] + $this->_sections['profile_loop']['step'];
$this->_sections['profile_loop']['first']      = ($this->_sections['profile_loop']['iteration'] == 1);
$this->_sections['profile_loop']['last']       = ($this->_sections['profile_loop']['iteration'] == $this->_sections['profile_loop']['total']);
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../plugins/".($this->_tpl_vars['global_plugins'][$this->_sections['profile_loop']['index']])."/templates/Profile".($this->_tpl_vars['global_plugins'][$this->_sections['profile_loop']['index']]).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endfor; endif; ?>

			</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>