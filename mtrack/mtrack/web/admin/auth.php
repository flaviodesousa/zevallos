<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

MTrackACL::requireAnyRights('User', 'modify');
$plugins = MTrackConfig::getSection('plugins');

function get_openid_admins()
{
  $admins = array();
  $regadmins = array();
  foreach (MTrackConfig::getSection('user_classes') as $id => $role) {
    if ($role == 'admin') {
      if (preg_match('@^https?://@', $id)) {
        $admins[] = $id;
      } else {
        $regadmins[$id] = $id;
      }
    }
  }
  if (count($regadmins)) {
    /* look at aliases to see if there are any that look like OpenIDs */
    foreach (MTrackDB::q('select alias, userid from useraliases')->fetchAll()
        as $row) {
      if (!preg_match('@^https?://@', $row[0])) {
        continue;
      }
      if (isset($regadmins[$row[1]])) {
        $admins[] = $row[0];
      }
    }
  }
  return $admins;
}

function get_admins()
{
  $admins = array();
  foreach (MTrackConfig::getSection('user_classes') as $id => $role) {
    if ($role == 'admin' && !preg_match('@^https?://@', $id)) {
      $admins[] = $id;
    }
  }
  return $admins;
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['setuppublic'])) {
    $admins = get_openid_admins();
    $add_admin = isset($_POST['adminopenid']) ?
                    trim($_POST['adminopenid']) : '';
    $localid = isset($_POST['adminuserid']) ?
                    trim($_POST['adminuserid']) : '';
    if (count($admins) == 0 && (!strlen($add_admin) || !strlen($localid))) {
      $message = "You MUST add an OpenID for the administrator";
    } else {
      if (strlen($localid)) {
        MTrackConfig::set('user_classes', $localid, 'admin');
      }
      $new = true;
      foreach (MTrackDB::q('select userid from userinfo where userid = ?',
          $localid)->fetchAll() as $row) {
        $new = false;
        break;
      }
      if ($new) {
        MTrackDB::q('insert into userinfo (userid, active) values (?, 1)', $localid);
      }
      $new = true;
      foreach (MTrackDB::q('select userid from useraliases where alias = ?', $add_admin)->fetchAll() as $row) {
        if ($row[0] != $localid) {
          throw new Exception("$add_admin is already associated with $row[0]");
        }
        $new = false;
      }
      if ($new) {
        MTrackDB::q('insert into useraliases (userid, alias) values (?,?)',
          $localid, $add_admin);
      }

      MTrackConfig::set('plugins', 'MTrackAuth_OpenID', '');
      if (isset($plugins['MTrackAuth_HTTP'])) {
        MTrackConfig::remove('plugins', 'MTrackAuth_HTTP');
        // Reset anonymous for public access
        MTrackConfig::remove('user_class_roles', 'anonymous');
      }

      MTrackConfig::save();
      header("Location: {$ABSWEB}admin/auth.php");
      exit;
    }
  } elseif (isset($_POST['setupprivate'])) {
    $admins = get_admins();
    $add_admin = isset($_POST['adminuser']) ?
                    trim($_POST['adminuser']) : '';
    if (count($admins) == 0 && !strlen($add_admin)) {
      $message = "You MUST add a user with admin rights";
    } else {
      $vardir = MTrackConfig::get('core', 'vardir');
      $pfile = "$vardir/http.user";

      if (strlen($add_admin)) {
        if (!isset($_SERVER['REMOTE_USER'])) {
          // validate the password
          if ($_POST['adminpass1'] != $_POST['adminpass2']) {
            $message = "Passwords don't match";
          } else {
            $http_auth = new MTrackAuth_HTTP(null, "digest:$pfile");
            $http_auth->setUserPassword($add_admin, $_POST['adminpass1']);
          }
        }
        MTrackConfig::set('user_classes', $add_admin, 'admin');
      }
      if ($message == null) {
        if (!isset($plugins['MTrackAuth_HTTP'])) {
          MTrackConfig::set('plugins', 'MTrackAuth_HTTP',
              "$vardir/http.group, digest:$pfile");
        }
        if (isset($plugins['MTrackAuth_OpenID'])) {
          MTrackConfig::remove('plugins', 'MTrackAuth_OpenID');
          // Set up the roles for private access
          // Use default authenticated permissions
          MTrackConfig::remove('user_class_roles', 'authenticated');
          // Make anonymous have no rights
          MTrackConfig::set('user_class_roles', 'anonymous', '');
        }
        MTrackConfig::save();
        header("Location: {$ABSWEB}admin/auth.php");
        exit;
      }
    }
  }
}

mtrack_head("Administration - Authentication");

$plugins = MTrackConfig::getSection('plugins');
$http_configd = isset($plugins['MTrackAuth_HTTP']) ?  " (Active)" : '';
$openid_configd = isset($plugins['MTrackAuth_OpenID']) ?  " (Active)" : '';


?>
<h1>Authentication</h1>
<?php
if ($message) {
  $message = htmlentities($message, ENT_QUOTES, 'utf-8');
  echo <<<HTML
<div class='ui-state-error ui-corner-all'>
    <span class='ui-icon ui-icon-alert'></span>
    $message
</div>
HTML;
}


?>
<p>
Select one of the following, depending
on which one best matches your intended mtrack deployment:
</p>

<form method='post'>
<div id="authaccordion">
<h2><a href='#'>Private (HTTP authentication)<?php echo $http_configd ?></a></h2>
<div>
<p>
  I want to strictly control who has access to mtrack, and prevent
  anonymous users from having any rights.
</p>
<?php
if (isset($_SERVER['REMOTE_USER'])) {
?>
<p>
  It looks like your web server is configured to use HTTP authentication
  (you're authenticated as <?php
    echo htmlentities($_SERVER['REMOTE_USER'], ENT_QUOTES, 'utf-8') ?>)
  mtrack will defer to your web server configuration for authentication.
  Contact your system administrator to add or remove users, or to change
  their passwords.  You may still use the mtrack user management screens
  to change rights assignments for the users.
</p>
<?php
} else {
?>
<p>
  mtrack will use HTTP authentication and store the password and group
  files in the <em>vardir</em>.
</p>
<?php
}
echo "<h3>Administrators</h3>";
$admins = get_admins();
if (count($admins)) {
  echo "<p>The following users are configured with admin rights:</p>";
  echo "<p>";
  foreach ($admins as $id) {
    echo mtrack_username($id) . " ";
  }
  echo "</p>";
} else {
  echo <<<HTML
<p>You <em>MUST</em> add at least one user as an administrator,
otherwise no one will be able to administer the system without editing
the config.ini file.
</p>
HTML;

  echo <<<HTML
<table>
<tr>
<td><b>Add Admin User</b>:</td>
<td><input type="text" name="adminuser"></td>
</tr>
HTML;

  if (!isset($_SERVER['REMOTE_USER'])) {
    echo <<<HTML
<tr>
  <td><b>Set Password</b>:</td>
  <td><input type="password" name="adminpass1"></td>
</tr>
<tr>
  <td><b>Confirm Password</b>:</td>
  <td><input type="password" name="adminpass2"></td>
</tr>
</table>
HTML;
  } else {
    echo <<<HTML
</table>
<p>
<em>You can't set the password here, because mtrack has no way to automatically
find out how to do that.  Contact your system administrator to ensure that
you have a username and password configured for mtrack</em></p>
HTML;
  }
}
?>
  <input type='submit' name='setupprivate'
    value='Configure Private Authentication'>

</div>
<h2><a href='#'>Public (OpenID)<?php echo $openid_configd ?></a></h2>
<div>
<p>
  I want to allow the public access to mtrack, but only allow people that
  I trust to make certain kinds of changes.
</p>
<p>
  mtrack will use OpenID to manage authentication.
</p>
<h3>Administrators</h3>
<?php
$admins = get_openid_admins();
if (count($admins)) {
  echo "<p>The following OpenID users are configured with admin rights:</p>";
  echo "<p>";
  foreach ($admins as $id) {
    echo mtrack_username($id) . " ($id) ";
  }
  echo "</p>";
} else {
  echo <<<HTML
<p>You <em>MUST</em> add at least one OpenID as an administrator,
otherwise no one will be able to administer the system without editing
the config.ini file.
</p>
HTML;
}
?>
<b>Add Admin OpenID</b>: <input type="text" name="adminopenid"><br>
<b>Local Username</b>: <input type="text" name="adminuserid"><br>
  <input type='submit' name='setuppublic'
    value='Configure Public Authentication'>
</div>
</div>
</form>
<script>
$(document).ready(function () {
  $('#authaccordion').accordion({
    active: <?php
  if (isset($plugins['MTrackAuth_OpenID'])) {
    echo "1";
  } else {
    echo "0";
  }
?>});
});
</script>
<?php
mtrack_foot();

