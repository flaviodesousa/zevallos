<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';

$user = mtrack_get_pathinfo();
if ($user === null && isset($_GET['user'])) {
  $user = $_GET['user'];
}
if (!strlen(trim($user))) {
  throw new Exception("No user name provided");
}
$user = mtrack_canon_username($user);

$me = mtrack_canon_username(MTrackAuth::whoami());
if (!empty($_REQUEST['edit'])) {
  if (MTrackACL::hasAllRights('User', 'modify')) {
    // can edit all
  } else if ($me != 'anonymous' && $me === $user) {
    // Can edit my own bits
    MTrackACL::requireAllRights('User', 'read');
  } else {
    // already checked this above, but we want it to trigger the privilege
    // error here
    MTrackACL::requireAllRights('User', 'modify');
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $http_auth = MTrackAuth::getMech('MTrackAuth_HTTP');
    if ($http_auth && !isset($_SERVER['REMOTE_USER'])) {
      if ($_POST['passwd1'] != $_POST['passwd2']) {
        throw new Exception("passwords don't match!");
      }
    }

    $data = MTrackDB::q('select * from userinfo where userid = ?', $user)
              ->fetchAll(PDO::FETCH_ASSOC);
    if (isset($data[0])) {
      // Updating
      if (MTrackACL::hasAllRights('User', 'modify')) {
        if (isset($_POST['active'])) {
          $active = $_POST['active'] == 'on' ? '1' : '0';
        } else {
          $active = '0';
        }
        MTrackDB::q('update userinfo set fullname = ?, email = ?, timezone = ?, active = ?, sshkeys = ? where userid = ?', $_POST['fullname'], $_POST['email'], $_POST['timezone'], $active, $_POST['keys'], $user);
      } else {
        MTrackDB::q('update userinfo set fullname = ?, email = ?, timezone = ?, sshkeys = ? where userid = ?', $_POST['fullname'], $_POST['email'], $_POST['timezone'], $_POST['keys'], $user);
      }
    } else {
      MTrackDB::q('insert into userinfo (active, fullname, email, timezone, sshkeys, userid) values (1, ?, ?, ?, ?, ?)', $_POST['fullname'], $_POST['email'], $_POST['timezone'], $_POST['keys'], $user);
    }

    if (MTrackACL::hasAllRights('User', 'modify')) {
      MTrackDB::q('delete from useraliases where userid = ?', $user);
      foreach (preg_split("/\r?\n/", $_POST['aliases']) as $alias) {
        if (!strlen(trim($alias))) {
          continue;
        }
        MTrackDB::q('insert into useraliases (userid, alias) values (?, ?)',
          $user, $alias);
      }

      $user_class = MTrackAuth::getUserClass($user);
      if (isset($_POST['user_role']) && $_POST['user_role'] != $user_class) {
        MTrackConfig::set('user_classes', $user, $_POST['user_role']);
        MTrackConfig::save();
      }
    }
    $http_auth = MTrackAuth::getMech('MTrackAuth_HTTP');
    if ($http_auth && !isset($_SERVER['REMOTE_USER'])) {
      // Allow changing their password
      $http_auth->setUserPassword($user, $_POST['passwd1']);
    }
    header("Location: {$ABSWEB}user.php?user=" . urlencode($user));
    exit;
  }

} else {
  MTrackACL::requireAllRights('User', 'read');
}

mtrack_head("User $user");

$data = MTrackDB::q('select * from userinfo where userid = ?', $user)->fetchAll(PDO::FETCH_ASSOC);
if (isset($data[0])) {
  $data = $data[0];
} else {
  $data = null;
}

$display = $user;

if (strlen($data['fullname'])) {
  $display .= " - " . $data['fullname'];
}

echo "<h1>", htmlentities($display, ENT_QUOTES, 'utf-8'), "</h1>";
echo "<div class='userinfo'>";
echo mtrack_username($user, array(
  'no_name' => true,
  'size' => 128
));
echo "<a href='mailto:$data[email]'>$data[email]</a><br>\n";

if (empty($_GET['edit'])) {
  $aliases = MTrackDB::q('select alias from useraliases where userid = ? order by alias', $user)->fetchAll(PDO::FETCH_COLUMN, 0);
  if (count($aliases)) {
    echo "<h2>Aliases</h2><ul>\n";
    foreach ($aliases as $alias) {
      echo "<li>", htmlentities($alias, ENT_QUOTES, 'utf-8'), "</li>\n";
    }
    echo "</ul>\n";
  }
}

echo "</div>";

if (empty($_GET['edit'])) {
  $me = mtrack_canon_username(MTrackAuth::whoami());
  if ($me != 'anonymous' && $me === $user) {
    $label = 'Edit my details';
  } else if (MTrackACL::hasAnyRights('User', 'modify')) {
    $label = 'Edit user details';
  } else {
    $label = null;
  }
  if ($label !== null) {
    echo "<form method='get' action='{$ABSWEB}user.php'>" .
      "<input type='hidden' name='user' value='" . $user . "'>" .
      "<input type='hidden' name='edit' value='1'>" .
      "<button type='submit'>$label</button></form>";
  }

  if (MTrackACL::hasAnyRights('Timeline', 'read')) {
    echo "<h2>Recent Activity</h2>\n";
    mtrack_render_timeline($user);
  }
} else {

  echo "<form method='post' action='{$ABSWEB}user.php?user=" .
    urlencode($user) . "'>\n";

  $fullname = htmlentities(
    isset($data['fullname']) ? $data['fullname'] : '',
    ENT_QUOTES, 'utf-8');
  $email = htmlentities(
    isset($data['email']) ? $data['email'] : '',
    ENT_QUOTES, 'utf-8');
  $timezone = htmlentities(
    isset($data['timezone']) ? $data['timezone'] : '',
    ENT_QUOTES, 'utf-8');

  echo <<<HTML
<input type='hidden' name='edit' value='1'>

<fieldset id='userinfo-container'>
  <legend>User Information</legend>
  <table>
    <tr valign='top'>
      <td>
        <label for='fullname'>Full name</label>
      </td>
      <td>
        <input type='text' name='fullname' size='64' value='$fullname'>
      </td>
    </tr>
    <tr valign='top'>
      <td>
        <label for='email'>Email</label>
      </td>
      <td>
        <input type='text' name='email' size='64' value='$email'><br>
        <em>We use this with <a href='http://gravatar.com'>Gravatar</a>
          to obtain your avatar image throughout mtrack</em>
      </td>
    </tr>
    <tr valign='top'>
      <td>
        <label for='timezone'>Timezone</label>
      </td>
      <td>
        <input type='text' name='timezone' size='24' value='$timezone'><br>
        <em>We use this to show times in your preferred timezone</em>
      </td>
    </tr>
HTML;
  if (MTrackACL::hasAllRights('User', 'modify')) {
    if (isset($data['active'])) {
      $active = (int)$data['active'];
    } else {
      $active = 0;
    }
    if ($active) {
      $active = " checked='checked'";
    }
    echo <<<HTML
    <tr valign='top'>
      <td>
        <label for='active'>Active?</label>
      </td>
      <td>
        <input type='checkbox' name='active' $active><br>
        <em>Active users are shown in the Responsible users list when editing tickets</em>
      </td>
    </tr>
HTML;

    $user_class = MTrackAuth::getUserClass($user);
    $user_class_roles = array();
    foreach (MTrackConfig::getSection('user_class_roles') as $role => $rights) {
      $user_class_roles[$role] = $role;
    }
    $role_select = mtrack_select_box('user_role', $user_class_roles,
                    $user_class);
    echo <<<HTML
    <tr valign='top'>
      <td>
        <label for='active'>Role</label>
      </td>
      <td>
        $role_select<br>
        <em>The role defines which actions this user can carry out in mtrack</em>
      </td>
    </tr>
HTML;

  }

  $http_auth = MTrackAuth::getMech('MTrackAuth_HTTP');
  if ($http_auth && !isset($_SERVER['REMOTE_USER'])) {

    if ($me == $user) {
      $your = "your";
    } else {
      $your = "this users";
    }

    echo <<<HTML
    <tr>
      <td>
        <label for='passwd1'>New Password</label>
      </td>
      <td>
        <input type="password" name="passwd1"><br>
        <em>Enter $your new password</em>
      </td>
    </tr>
    <tr>
      <td>
        <label for='passwd2'>Confirm Password</label>
      </td>
      <td>
        <input type="password" name="passwd2"><br>
        <em>Confirm $your new password</em>
      </td>
    </tr>
HTML;

  }

  echo <<<HTML
  </table>
</fieldset>
HTML;

  $groups = MTrackAuth::getGroups($user);
  echo <<<HTML
<fieldset id='userinfo-groups'>
  <legend>Groups</legend>
  <em>This user is a member of the following groups</em>
  <ul>
HTML;
  foreach ($groups as $group) {
    echo "<li>" . htmlentities($group, ENT_QUOTES, 'utf-8') . "</li>\n";
  }
  echo <<<HTML
  </ul>
</fieldset>
HTML;

  if (MTrackACL::hasAllRights('User', 'modify')) {

    $aliases = MTrackDB::q('select alias from useraliases where userid = ? order by alias', $user)->fetchAll(PDO::FETCH_COLUMN, 0);
    $atext = '';
    foreach ($aliases as $alias) {
      $atext .= htmlentities($alias, ENT_QUOTES, 'utf-8') . "\n";
    }

    echo <<<HTML
<fieldset id='userinfo-container'>
  <legend>Aliases</legend>
  <em>This user is also known by the following identities (one per line) when
  assessing changes in the various repositories</em><br>
  <textarea name='aliases' cols='64' rows='10'>$atext</textarea>
</fieldset>

HTML;

  }

  echo <<<HTML
  </table>
</fieldset>
HTML;

  $keytext = htmlentities($data['sshkeys'], ENT_QUOTES, 'utf-8');
  echo <<<HTML
<fieldset id='sshkey-container'>
  <legend>SSH Keys</legend>
  <em>The repositories created and managed by mtrack are served over SSH.
    Access is enabled only based on public SSH keys, not passwords.
    In order to check code in or out, you must provide one or more
    keys.  Paste in the public key(s) you want to use below, one per line.
  </em><br>
  <textarea name='keys' cols='64' rows='10'>$keytext</textarea>
</fieldset>

HTML;

  echo <<<HTML

  <button>Save Changes</button>
</form>
HTML;
}

mtrack_foot();
