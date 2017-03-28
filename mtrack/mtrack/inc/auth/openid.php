<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

/* all the clever stuff happens in openid.php */
class MTrackAuth_OpenID implements IMTrackAuth, IMTrackNavigationHelper {
  function __construct() {
    MTrackAuth::registerMech($this);
    MTrackNavigation::registerHelper($this);
  }

  function augmentUserInfo(&$content) {
    global $ABSWEB;
    if (isset($_SESSION['openid.id'])) {
      $content .= " | <a href='{$ABSWEB}openid.php/signout'>Log off</a>";
    } else {
      $content = "<a href='{$ABSWEB}openid.php'>Log In</a>";
    }
  }

  function augmentNavigation($id, &$items) {
  }

  function authenticate() {
    if (!strlen(session_id()) && php_sapi_name() != 'cli') {
      session_start();
    }
    if (isset($_SESSION['openid.id'])) {
      if (isset($_SESSION['openid.userid'])) {
        return $_SESSION['openid.userid'];
      }
      return $_SESSION['openid.id'];
    }
    return null;
  }

  function doAuthenticate($force = false) {
    if ($force) {
      global $ABSWEB;
      header("Location: {$ABSWEB}openid.php");
      exit;
    }
    return null;
  }

  function enumGroups() {
    return null;
  }

  function getGroups($username) {
    return null;
  }

  function addToGroup($username, $groupname) {
    return null;
  }

  function removeFromGroup($username, $groupname) {
    return null;
  }

  function getUserData($username) {
    return null;
  }
}


