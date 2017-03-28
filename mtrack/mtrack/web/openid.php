<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';
require_once 'Auth/OpenID/Consumer.php';
require_once 'Auth/OpenID/FileStore.php';
require_once 'Auth/OpenID/SReg.php';
require_once 'Auth/OpenID/PAPE.php';

$store_location = MTrackConfig::get('openid', 'store_dir');
if (!$store_location) {
  $store_location = MTrackConfig::get('core', 'vardir') . '/openid';
}
if (!is_dir($store_location)) {
  mkdir($store_location);
}
$store = new Auth_OpenID_FileStore($store_location);
$consumer = new Auth_OpenID_Consumer($store);

$message = null;

$pi = mtrack_get_pathinfo();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $pi != 'register') {

  $req = null;

  if (!isset($_POST['openid_identifier']) ||
      !strlen($_POST['openid_identifier'])) {
    $message = "you must fill in your OpenID";
  } else {
    $id = $_POST['openid_identifier'];
    if (!preg_match('/^https?:\/\//', $id)) {
      $id = "http://$id";
    }
    $req = $consumer->begin($id);
    if (!$req) {
      $message = "not a valid OpenID";
    }
  }
  if ($req) {
    $sreg = Auth_OpenID_SRegRequest::build(
      array('nickname', 'fullname', 'email')
    );
    $req->addExtension($sreg);

    if ($req->shouldSendRedirect()) {
      $rurl = $req->redirectURL(
        $ABSWEB, $ABSWEB . 'openid.php/callback');
      if (Auth_OpenID::isFailure($rurl)) {
        $message = "Unable to redirect to server: " . $rurl->message;
      } else {
        header("Location: $rurl");
        exit;
      }
    } else {
      $html = $req->htmlMarkup($ABSWEB, $ABSWEB . 'openid.php/callback',
        false, array('id' => 'openid_message'));
      if (Auth_OpenID::isFailure($html)) {
        $message = "Unable to redirect to server: " . $html->message;
      } else {
        echo $html;
      }
    }
  }
} else if ($pi == 'callback') {
  $res = $consumer->complete($ABSWEB . 'openid.php/callback');

  if ($res->status == Auth_OpenID_CANCEL) {
    $message = 'Verification cancelled';
  } else if ($res->status == Auth_OpenID_FAILURE) {
    $message = 'OpenID authentication failed: ' . $res->message;
  } else if ($res->status == Auth_OpenID_SUCCESS) {
    $id = $res->getDisplayIdentifier();
    $sreg = Auth_OpenID_SRegResponse::fromSuccessResponse($res)->contents();

    if (!empty($sreg['nickname'])) {
      $name = $sreg['nickname'];
    } else if (!empty($sreg['fullname'])) {
      $name = $sreg['fullname'];
    } else {
      $name = $id;
    }
    $message = 'Authenticated as ' . $name;

    $_SESSION['openid.id'] = $id;
    unset($_SESSION['openid.userid']);
    $_SESSION['openid.name'] = $name;
    if (!empty($sreg['email'])) {
      $_SESSION['openid.email'] = $sreg['email'];
    }
    /* See if we can find a canonical identity for the user */
    foreach (MTrackDB::q('select userid from useraliases where alias = ?',
        $id)->fetchAll() as $row) {
      $_SESSION['openid.userid'] = $row[0];
      break;
    }

    if (!isset($_SESSION['openid.userid'])) {
      /* no alias; is there a direct userinfo entry? */
      foreach (MTrackDB::q('select userid from userinfo where userid = ?',
          $id)->fetchAll() as $row) {
        $_SERVER['openid.userid'] = $row[0];
        break;
      }
    }

    if (!isset($_SESSION['openid.userid'])) {
      /* prompt the user to fill out some basic details so that we can create
       * a local identity and associate their OpenID with it */
      header("Location: {$ABSWEB}openid.php/register?" .
        http_build_query($sreg));
    } else {
      header("Location: " . $ABSWEB);
    }
    exit;
  } else {
    $message = 'An error occurred while talking to your OpenID provider';
  }
} else if ($pi == 'signout') {
  session_destroy();
  header('Location: ' . $ABSWEB);
  exit;
} else if ($pi == 'register') {

  if (!isset($_SESSION['openid.id'])) {
    header("Location: " . $ABSWEB);
    exit;
  }

  $userid = isset($_REQUEST['nickname']) ? $_REQUEST['nickname'] : '';
  $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
  $message = null;

  /* See if we can find a canonical identity for the user */
  foreach (MTrackDB::q('select userid from useraliases where alias = ?',
      $_SESSION['openid.id'])->fetchAll() as $row) {
    header("Location: " . $ABSWEB);
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!strlen($userid)) {
      $message = 'You must enter a userid';
    } else {
      /* is the requested id available? */
      $avail = true;
      foreach (MTrackDB::q('select userid from userinfo where userid = ?',
            $userid)->fetchAll() as $row) {
        $avail = false;
        $message = "Your selected user ID is not available";
      }
      if ($avail) {
        MTrackDB::q('insert into userinfo (userid, email, active) values (?, ?, 1)', $userid, $email);
        /* we know the alias doesn't already exist, because we double-checked
         * for it above */
        MTrackDB::q('insert into useraliases (userid, alias) values (?,?)',
          $userid, $_SESSION['openid.id']);
        header("Location: {$ABSWEB}user.php?user=$userid&edit=1");
        exit;
      }
    }
  }

  mtrack_head('Register');

  $userid = htmlentities($userid, ENT_QUOTES, 'utf-8');
  $email = htmlentities($email, ENT_QUOTES, 'utf-8');

  if ($message) {
    $message = htmlentities($message, ENT_QUOTES, 'utf-8');
    echo <<<HTML
<div class='ui-state-error ui-corner-all'>
    <span class='ui-icon ui-icon-alert'></span>
    $message
</div>
HTML;
  }

  echo <<<HTML
<h1>Set up your local account</h1>
<form method='post'>
  User ID: <input type='text' name='nickname' value='$userid'><br>
  Email: <input type='text' name='email' value='$email'><br>
  <button type='submit'>Save</button>
</form>


HTML;
  mtrack_foot();
  exit;
}

mtrack_head('Authentication Required');
echo "<h1>Please sign in with your <a id='openidlink' href='http://openid.net'><img src='{$ABSWEB}images/logo_openid.png' alt='OpenID' border='0'></a></h1>\n";
echo "<form method='post' action='{$ABSWEB}openid.php'>";
echo "<input type='text' name='openid_identifier' id='openid_identifier'>";
echo " <button type='submit' id='openid-sign-in'>Sign In</button>";

if ($message) {
  $message = htmlentities($message, ENT_QUOTES, 'utf-8');
  echo <<<HTML
<div class='ui-state-highlight ui-corner-all'>
    <span class='ui-icon ui-icon-info'></span>
    $message
</div>
HTML;
}

echo "</form>";


mtrack_foot();

