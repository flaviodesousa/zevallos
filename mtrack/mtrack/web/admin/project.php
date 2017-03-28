<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

MTrackACL::requireAnyRights('Projects', 'modify');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['cancel'])) {
    header("Location: ${ABSWEB}admin/");
    exit;
  }

  $pid = $_GET['edit'];
  if ($pid == 'new') {
    MTrackACL::requireAnyRights('Projects', 'create');
    $P = new MTrackProject;
  } else {
    $P = MTrackProject::loadById($pid);
    if (!$P) {
      throw new Exception("invalid project " . htmlentities($pid));
    }
    MTrackACL::requireAnyRights("project:$pid", 'modify');
  }

  $P->name = $_POST["name"];
  $P->shortname = $_POST["shortname"];
  $P->ordinal = $_POST["ordinal"];
  $P->notifyemail = $_POST["email"];
  $CS = MTrackChangeset::begin("project:X",
      $pid == 'new' ?
      "added project $P->name" :
      "edit project $P->name");
  $P->save($CS);

  if (MTrackACL::hasAnyRights('Components', 'modify')) {
    MTrackDB::q('delete from components_by_project where projid = ?', $P->projid);
    if (isset($_POST['components'])) {
      $comps = $_POST['components'];
      foreach ($comps as $cid) {
        MTrackDB::q(
            'insert into components_by_project (compid, projid) values (?, ?)',
            $cid, $P->projid);
      }
    }
  }

  $CS->setObject("project:$P->projid");
  if (isset($_POST['perms'])) {
    MTrackACL::setACL("project:$P->projid", 0, json_decode($_POST['perms']));
  }
  $CS->commit();

  header("Location: ${ABSWEB}admin/project.php");
  exit;
}

mtrack_head("Administration - Projects");

?>
<h1>Projects</h1>
<p>
Projects can be created to track development on a per-project or per-product
basis.  Components may be associated with a project, as well as a default
email distribution address.
</p>
<?php

if (isset($_GET['edit'])) {
  $pid = $_GET['edit'];
  if ($pid != 'new') {
    $q = MTrackDB::q('select * from projects where projid = ?', $pid);
    $p = null;
    foreach ($q as $row) {
      $p = $row;
    }
    if ($p == null) {
      throw new Exception("no such project " . htmlentities($pid));
    }
  } else {
    $p = array(
      'projid' => 'new',
      'name' => 'My New Project',
      'shortname' => 'newproject',
      'ordinal' => 5,
      'notifyemail' => null
    );
  }
  echo "<form method='post' action=\"{$ABSWEB}admin/project.php?edit=$pid\">";

  echo "<table>";
  $name = htmlentities($p['name'], ENT_QUOTES, 'utf-8');
  $sname = htmlentities($p['shortname'], ENT_QUOTES, 'utf-8');
  $ord = htmlentities($p['ordinal'], ENT_QUOTES, 'utf-8');
  $email = htmlentities($p['notifyemail'], ENT_QUOTES, 'utf-8');
  echo "<tr><th>Name</th>",
    "<td><input type='text' name='name' value='$name'></td></tr>";
  echo "<tr><th>Short Name</th>",
    "<td><input type='text' name='shortname' value='$sname'></td></tr>";
  echo "<tr><th>Sorting</th>",
    "<td><input type='text' name='ordinal' value='$ord'></td></tr>";
  echo "<tr><th>Group Email Address</th>",
    "<td><input type='text' name='email' value='$email'></td></tr>";
  echo "</table>";

  if (MTrackACL::hasAnyRights('Components', 'modify')) {
    $components = array();
    foreach (MTrackDB::q(
        'select compid, name, deleted from components order by name')
        ->fetchAll() as $row) {
      if ($row[2]) {
        $row[1] .= " (deleted)";
      }
      $components[$row[0]] = $row[1];
    }
    $p_by_c = array();
    if ($pid != 'new') {
      foreach (MTrackDB::q(
          'select compid from components_by_project where projid = ?', $pid)
          ->fetchAll() as $row) {
        $p_by_c[$row[0]] = $row[0];
      }
    }
    echo "<h2>Components</h2>";
    echo "<p>Associate component(s) with this project</p>";
    echo mtrack_multi_select_box('components', "(select to add)",
        $components, $p_by_c);
  }

  $repos = array();
  foreach (MTrackDB::q('select distinct r.repoid, shortname from project_repo_link p left join repos r on p.repoid = r.repoid where projid = ?', (int)$pid) as $row) {
    $repos[$row[0]] = $row[1];
  }
  foreach (MTrackDB::q("select repoid, shortname from repos where parent = 'project:' || ?", $p['shortname']) as $row) {
    $repos[$row[0]] = $row[1];
  }

  if ($pid != 'new') {
    echo "<h2>Groups</h2>";
    echo "<p>The following groups are associated with this project. You may assign permissions to groups to make it easier to manage groups of users.</p>";

    foreach (MTrackDB::q('select name from groups where project = ?', $pid)
        as $row) {
      echo "<a href='{$ABSWEB}admin/group.php?pid=$pid&amp;group=$row[0]'>"
       . htmlentities($row[0], ENT_QUOTES, 'utf-8') . '</a><br>';
    }

    echo "<a class='button' href=\"{$ABSWEB}admin/group.php?pid=$pid\">New Group</a>";
  }

  echo "<h2>Linked Repositories</h2>";
  if (count($repos)) {
    echo "<ul>\n";
    foreach ($repos as $rid => $name) {
      echo "<li><a href=\"{$ABSWEB}admin/repo.php/$rid\">" .
        htmlentities($name, ENT_QUOTES, 'utf-8') . "</a></li>\n";
    }
    echo "</ul>\n";
  } else {
    echo "<i>No linked repositories</i>\n";
  }
  echo "<br><br>\n";

  if (MTrackACL::hasAnyRights("project:$pid", 'modify')) {
    $action_map = array(
      'Admin' => array(
        'modify' => 'Administer via web UI',
      ),
    );

    MTrackACL::renderACLForm('perms', "project:$pid", $action_map);
  }

  echo "<button type='submit'>Save</button>";
  echo "<button type='submit' name='cancel'>Cancel</button>";

  echo "</form>";
} else {
?>
<p>
Select a project below to edit it, or click the "Add" button to create
a new project.
</p>
<?php

  echo "<table>\n";
  foreach (MTrackDB::q(
      'select projid, name, shortname, ordinal, notifyemail
      from projects order by ordinal') as $row) {

    $pid = $row[0];
    $name = htmlentities($row[1], ENT_QUOTES, 'utf-8');
    $sname = htmlentities($row[2], ENT_QUOTES, 'utf-8');
    if ($sname != $name) {
      $sname = " ($sname)";
    } else {
      $sname = '';
    }
    $email = htmlentities($row[4], ENT_QUOTES, 'utf-8');

    echo "<tr>",
      "<td><a href=\"{$ABSWEB}admin/project.php?edit=$pid\">$name$sname</a></td>",
      "<td>$email</td>",
      "</tr>\n";

  }
  echo "</table><br>";

  echo "<form method='get' action=\"{$ABSWEB}admin/project.php\">";
  echo "<input type='hidden' name='edit' value='new'>";
  echo "<button type='submit'>Add Project</button></form>";
}

mtrack_foot();

