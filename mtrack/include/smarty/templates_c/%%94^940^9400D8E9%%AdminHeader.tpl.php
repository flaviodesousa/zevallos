<?php /* Smarty version 2.6.14, created on 2011-04-24 04:56:30
         compiled from AdminHeader.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/DTD/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->_tpl_vars['Admin1']; ?>
</title>
    <base href='<?php echo $this->_tpl_vars['baseurl']; ?>
admin/' />
    <link href="../templates/reset.css" rel="stylesheet" type="text/css" />
    <link href="../templates/main.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../include/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../include/js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="../include/js/main.js"></script>
  
	<?php echo '
		<script language=\'JavaScript\'>
			<!--
			function showdiv(id1) {
				if(document.getElementById(id1))
				document.getElementById(id1).style.display=\'block\';
			}
			
			function hidediv(id1) {
				if(document.getElementById(id1))
				document.getElementById(id1).style.display=\'none\';
			}
			Rollimage1 = new Array()
			Rollimage1[\'1\'] = new Image(216,23);
			Rollimage1[\'1\'].src = "../images/admin_menu_bg1.gif";
			Rollimage1[\'2\'] = new Image(216,23);
			Rollimage1[\'2\'].src = "../images/admin_menu_bg2.gif";
			//-->
		</script>
	'; ?>

</head>
<body>


<div id="container">
    	<div id="header">
            <a href="./AdminHome.php" id="logo"></a>
            <div class="admin-area">&nbsp;</div>
        </div>
        <div id="wrapper" >
            <div id="content" >
                <div class="layers">
                    <div class="tunedtabs">
                        <ul class="list01">
                            <li <?php if ($this->_tpl_vars['category_main'] == 'network'): ?>class="active"<?php endif; ?>><a href="AdminHome.php"><?php echo $this->_tpl_vars['Admin5']; ?>
</a></li>
                            <li <?php if ($this->_tpl_vars['category_main'] == 'global'): ?>class="active"<?php endif; ?>><a href="AdminGeneral.php"><?php echo $this->_tpl_vars['Admin9']; ?>
</a></li>
                            <li <?php if ($this->_tpl_vars['category_main'] == 'layout'): ?>class="active"<?php endif; ?>><a href="AdminTemplates.php">Layout Settings</a></li>
                            <li <?php if ($this->_tpl_vars['category_main'] == 'other'): ?>class="active"<?php endif; ?>><a href="AdminInvite.php"><?php echo $this->_tpl_vars['Admin21']; ?>
</a></li>
                            <li><a href='AdminLogout.php'><?php echo $this->_tpl_vars['Admin23']; ?>
</a></li>
                        </ul>
                    </div>
                    <div class="info-cnt tuneddivs layer2">
                        <?php if ($this->_tpl_vars['category_main'] == 'network'): ?>
                    	<div class="tunedtabs" id="network">
                            <ul class="list01-top">
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminHome.php'): ?>class="active"<?php endif; ?>><a href='AdminHome.php'><?php echo $this->_tpl_vars['Admin6']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminViewusers.php' || $this->_tpl_vars['uri_page'] == 'AdminViewusersEdit.php'): ?>class="active"<?php endif; ?>><a href='AdminViewusers.php'><?php echo $this->_tpl_vars['Admin7']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminViewadmins.php' || $this->_tpl_vars['uri_page'] == 'AdminEditViewadmins.php' || $this->_tpl_vars['uri_page'] == 'AdminAddViewadmins.php'): ?>class="active"<?php endif; ?>><a href='AdminViewadmins.php'><?php echo $this->_tpl_vars['Admin8']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminViewreports.php' || $this->_tpl_vars['uri_page'] == 'AdminViewreportsEdit.php'): ?>class="active"<?php endif; ?>><a href='AdminViewreports.php'><?php echo $this->_tpl_vars['Admin27']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminViewplugins.php' || $this->_tpl_vars['uri_page'] == 'AdminViewevents.php' || $this->_tpl_vars['uri_page'] == 'AdminEvent.php' || $this->_tpl_vars['uri_page'] == 'AdminViewalbums.php' || $this->_tpl_vars['uri_page'] == 'AdminAlbum.php' || $this->_tpl_vars['uri_page'] == 'AdminViewarticles.php' || $this->_tpl_vars['uri_page'] == 'AdminArticle.php' || $this->_tpl_vars['uri_page'] == 'AdminGroupAddfield.php' || $this->_tpl_vars['uri_page'] == 'AdminGroupEditfield.php' || $this->_tpl_vars['uri_page'] == 'AdminViewgroups.php' || $this->_tpl_vars['uri_page'] == 'AdminGroup.php' || $this->_tpl_vars['uri_page'] == 'AdminClassified.php' || $this->_tpl_vars['uri_page'] == 'AdminViewclassifieds.php' || $this->_tpl_vars['uri_page'] == 'AdminBlog.php' || $this->_tpl_vars['uri_page'] == 'AdminViewblogs.php' || $this->_tpl_vars['uri_page'] == 'AdminChat.php' || $this->_tpl_vars['uri_page'] == 'AdminVideo.php' || $this->_tpl_vars['uri_page'] == 'AdminViewmusic.php' || $this->_tpl_vars['uri_page'] == 'AdminPhototagger.php' || $this->_tpl_vars['uri_page'] == 'AdminViewvideos.php'): ?>class="active"<?php endif; ?>><a href='AdminViewplugins.php'><?php echo $this->_tpl_vars['Admin17']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminLevels.php' || $this->_tpl_vars['uri_page'] == 'AdminAddLevels.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsAlbumsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsAlbumsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsArticlesettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsGroupsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsClassifiedsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsBlogsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsVideosettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsBlogsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsMusicsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminLevelsBlogsettings.php' || $this->_tpl_vars['uri_page'] == 'AdminEditLevels.php'): ?>class="active"<?php endif; ?>><a href='AdminLevels.php'><?php echo $this->_tpl_vars['Admin20']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminSubnetworks.php' || $this->_tpl_vars['uri_page'] == 'AdminAddSubnetworks.php' || $this->_tpl_vars['uri_page'] == 'AdminSubnetworksEdit.php'): ?>class="active"<?php endif; ?>><a href='AdminSubnetworks.php'><?php echo $this->_tpl_vars['Admin13']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminAds.php' || $this->_tpl_vars['uri_page'] == 'AdminAdsEdit.php' || $this->_tpl_vars['uri_page'] == 'AdminAdsAdd.php'): ?>class="active"<?php endif; ?>><a href='AdminAds.php'><?php echo $this->_tpl_vars['Admin32']; ?>
</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['category_main'] == 'global'): ?>
                        <div class="tunedtabs" id="global">
                            <ul class="list01-top">
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminGeneral.php'): ?>class="active"<?php endif; ?>><a href='AdminGeneral.php'><?php echo $this->_tpl_vars['Admin18']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminSignup.php'): ?>class="active"<?php endif; ?>><a href='AdminSignup.php'><?php echo $this->_tpl_vars['Admin10']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminActivity.php'): ?>class="active"<?php endif; ?>><a href='AdminActivity.php'><?php echo $this->_tpl_vars['Admin33']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminProfile.php' || $this->_tpl_vars['uri_page'] == 'AdminProfileEditfield.php' || $this->_tpl_vars['uri_page'] == 'AdminProfileAddfield.php' || $this->_tpl_vars['uri_page'] == 'AdminProfileAddtab.php' || $this->_tpl_vars['uri_page'] == 'AdminProfileEdittab.php'): ?>class="active"<?php endif; ?>><a href='AdminProfile.php'><?php echo $this->_tpl_vars['Admin12']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminBanning.php'): ?>class="active"<?php endif; ?>><a href='AdminBanning.php'><?php echo $this->_tpl_vars['Admin14']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminConnections.php'): ?>class="active"<?php endif; ?>><a href='AdminConnections.php'><?php echo $this->_tpl_vars['Admin15']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminEmails.php'): ?>class="active"<?php endif; ?>><a href='AdminEmails.php'><?php echo $this->_tpl_vars['Admin26']; ?>
</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['category_main'] == 'layout'): ?>
                        <div class="tunedtabs" id="layout">
                            <ul class="list01-top">
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminTemplates.php'): ?>class="active"<?php endif; ?>><a href='AdminTemplates.php'><?php echo $this->_tpl_vars['Admin11']; ?>
</a></li>
                                <!-- li <?php if ($this->_tpl_vars['uri_page'] == 'AdminUrl.php'): ?>class="active"<?php endif; ?>><a href='AdminUrl.php'><?php echo $this->_tpl_vars['Admin16']; ?>
</a></li -->
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminHelpUrl.php'): ?>class="active"<?php endif; ?>><a href='AdminHelpUrl.php'>Help URL</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['category_main'] == 'other'): ?>
                        <div class="tunedtabs" id="other">
                            <ul class="list01-top">
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminInvite.php'): ?>class="active"<?php endif; ?>><a href='AdminInvite.php'><?php echo $this->_tpl_vars['Admin28']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminAnnouncements.php'): ?>class="active"<?php endif; ?>><a href='AdminAnnouncements.php'><?php echo $this->_tpl_vars['Admin25']; ?>
</a></li>
                                <li <?php if ($this->_tpl_vars['uri_page'] == 'AdminLog.php'): ?>class="active"<?php endif; ?>><a href='AdminLog.php'><?php echo $this->_tpl_vars['Admin22']; ?>
</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>

<div class="info-cnt layer-in tuneddivs">
