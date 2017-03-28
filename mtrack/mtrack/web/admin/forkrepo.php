<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

MTrackACL::requireAnyRights('Browser', 'fork');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $rid = $_POST['source'];
  MTrackACL::requireAnyRights("repo:$rid", 'read');
  $name = trim($_POST['name']);

  if (strlen($name) == 0) {
    throw new Exception("missing name");
  }
  if (preg_match("/[^a-zA-Z0-9_.-]/", $name)) {
    throw new Exception("$name contains illegal characters");
  }
  $owner = mtrack_canon_username(MTrackAuth::whoami());
  if (preg_match("/[^a-zA-Z0-9_.-]/", $owner)) {
    throw new Exception("$owner must be a locally defined user");
  }

  $S = MTrackRepo::loadById($rid);
  if (!$S->canFork()) {
    throw new Exception("cannot fork this repo");
  }
  $P = new MTrackRepo;
  $P->shortname = $name;
  if (isset($_POST['repo:parent'])) {
    // FIXME: ACL check to see if we're allowed to create under the specified
    // parent
    $P->parent = $_POST['repo:parent'];
  } else {
    $P->parent = "user:$owner";
  }

  $P->scmtype = $S->scmtype;
  $P->description = $S->description;
  $P->clonedfrom = $S->repoid;

  $CS = MTrackChangeset::begin("repo:X",
    "Clone repo $S->shortname as $P->shortname");
  $P->save($CS);
  $CS->setObject("repo:$P->repoid");
  $CS->commit();
  $name = $P->getBrowseRootName();
  header("Location: ${ABSWEB}browse.php/$name");
  exit;
}

header("Location: ${ABSWEB}browse.php");
exit;

