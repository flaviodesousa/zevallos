<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $rid = $_POST['repoid'];

  MTrackACL::requireAllRights("repo:$rid", 'delete');

  $S = MTrackRepo::loadById($rid);
  $CS = MTrackChangeset::begin("repo:$rid", "Delete repo $S->shortname");
  $S->deleteRepo($CS);
  $CS->commit();
}

header("Location: ${ABSWEB}browse.php");
exit;

