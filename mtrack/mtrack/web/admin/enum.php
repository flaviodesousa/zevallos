<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

MTrackACL::requireAnyRights('Enumerations', 'modify');

$ename = mtrack_get_pathinfo();
$enums = array('Priority', 'TicketState', 'Severity', 'Resolution', 'Classification');

if (!in_array($ename, $enums)) {
  throw new Exception("Invalid enum type");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $cls = 'MTrack' . $ename;
  if (isset($_POST["$ename:name:"]) && strlen($_POST["$ename:name:"])) {
    $obj = new $cls;
    $obj->name = $_POST["$ename:name:"];
    $obj->value = $_POST["$ename:value:"];
    $CS = MTrackChangeset::begin("enum:$obj->tablename:$obj->name",
        "added $ename $obj->name");
    $obj->save($CS);
    $CS->commit();
  }

  foreach ($_POST as $name => $value) {
    if (preg_match("/^$ename:value:(.+)$/", $name, $M)) {
      $n = $M[1];
      $obj = new $cls($n);
      $changed = false;

      if ($obj->value != $value) {
        $obj->value = $value;
        $changed = true;
      }
      if (isset($_POST["$ename:deleted:$n"]) &&
          $_POST["$ename:deleted:$n"] == "on") {
        $deleted = '1';
      } else {
        $deleted = '';
      }
      if ($obj->deleted != $deleted) {
        $obj->deleted = $deleted;
        $changed = true;
      }

      if ($changed) {
        $CS = MTrackChangeset::begin("enum:$obj->tablename:$obj->name",
            "changed $ename $obj->name");
        $obj->save($CS);
        $CS->commit();
      }
    }
  }
  header("Location: ${ABSWEB}admin/");
  exit;
}

mtrack_head("Administration - $ename");

echo "<form method='post'>";

$cls = 'MTrack' . $ename;
$obj = new $cls;
echo "<br><b>$ename values</b><br>\n";
$vals = $obj->enumerate(true);
echo "<table><tr><th>Name</th><th>Value</th><th>Deleted</th></tr>\n";
foreach ($vals as $V) {
  $n = htmlentities($V['name'], ENT_QUOTES, 'utf-8');
  $v = htmlentities($V['value'], ENT_QUOTES, 'utf-8');
  $del = $V['deleted'] ? ' checked="checked" ' : '';
  echo "<tr>" .
    "<td>$n</td>" .
    "<td><input type='text' name='$ename:value:$n' value='$v'></td>" .
    "<td><input type='checkbox' name='$ename:deleted:$n' $del></td>" .
    "</tr>\n";
}
echo "<tr>" .
  "<td><input type='text' name='$ename:name:' value=''></td>" .
  "<td><input type='text' name='$ename:value:' value=''></td>" .
  "<td>Add a new $ename</td>" .
  "</tr>\n";
echo "</table>\n";

echo "<button>Save Changes</button></form>";

mtrack_foot();

