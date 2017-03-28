<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

if (!isset($_REQUEST['pid'])) {
  throw new Exception("missing project id");
}
$pid = (int)$_REQUEST['pid'];

MTrackACL::requireAnyRights("project:$pid", 'modify');

$P = MTrackProject::loadById($pid);
if (!$P) {
  throw new Exception("invalid project " . htmlentities($pid));
}

if (isset($_REQUEST['group'])) {
  $group = $_REQUEST['group'];
} else {
  $group = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!strlen($group)) {
    throw new Exception("missing group name");
  }
  if (isset($_POST['members'])) {
    $members = $_POST['members'];
  } else {
    $members = array();
  }

  $CS = MTrackChangeset::begin("project:$pid", "Changed group $group");
  if (isset($_POST['isnew'])) {
    MTrackDB::q('insert into groups (name, project) values (?, ?)',
      $group, $pid);
  }

  MTrackDB::q(
    'delete from group_membership where groupname = ? and project = ?',
    $group, $pid);
  foreach ($members as $username) {
    MTrackDB::q(
      'insert into group_membership (groupname, project, username) values (?,?,?)',
      $group, $pid, $username);
  }
  $CS->commit();
  header("Location: {$ABSWEB}admin/project.php?edit=$pid");
  exit;
}

mtrack_head($group ? "$P->name - $group" : "$P->name - New Group");

echo "<form method='post'><input type='hidden' name='pid' value='$pid'>";
if ($group) {
  echo "<h1>" . htmlentities("$P->name - $group", ENT_QUOTES, 'utf-8') . "</h1>";
  echo "<input type='hidden' name='group' value='" .
    htmlentities($group, ENT_QUOTES, 'utf-8') .
    "'>";
} else {
  echo "<h1>" . htmlentities("$P->name - New Group", ENT_QUOTES, 'utf-8') . "</h1>";
  echo "Group: <input type='text' name='group'>";
  echo "<input type='hidden' name='isnew' value='1'>";
}

$users = array();
foreach (MTrackDB::q('select userid, fullname from userinfo where active = 1 order by userid')
    ->fetchAll() as $row) {
  if (strlen($row[1])) {
    $disp = "$row[0] - $row[1]";
  } else {
    $disp = $row[0];
  }
  $users[$row[0]] = $disp;
}
$members = array();
foreach (MTrackDB::q('select username from group_membership where project = ? and groupname = ?', $pid, $group)->fetchAll(PDO::FETCH_COLUMN, 0) as $name) {
  $members[$name] = $name;
}
echo mtrack_multi_select_box('members', "Members", $users, $members);

echo "<input type='submit' value='Save'>";

echo "</form>";

mtrack_foot();

