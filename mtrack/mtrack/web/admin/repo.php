<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../../inc/common.php';

$rid = mtrack_get_pathinfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($rid == 'new') {
    MTrackACL::requireAnyRights('Browser', array('create', 'fork'));
    $P = new MTrackRepo;
  } else {
    MTrackACL::requireAnyRights("repo:$rid", 'modify');
    $P = MTrackRepo::loadById($rid);
  }
  $links = $P->getLinks();
  $plinks = array();

  foreach ($_POST as $name => $value) {
    if (preg_match("/^link:(\d+|new):project$/", $name, $M)) {
      $lid = $M[1];
      $plinks[$lid] = array(
        (int)$_POST["link:$lid:project"],
        trim($_POST["link:$lid:regex"]));
    }
  }
  if (isset($plinks['new'])) {
    $n = $plinks['new'];
    unset($plinks['new']);
    if (strlen($n[1])) {
      $P->addLink($n[0], $n[1]);
    }
  }
  foreach ($plinks as $lid => $n) {
    if (isset($links[$lid])) {
      if ($n != $links[$lid] || !strlen($n[1])) {
        $P->removeLink($lid);
        if (strlen($n[1])) {
          $P->addLink($n[0], $n[1]);
        }
      }
    } else if (strlen($n[1])) {
      $P->addLink($n[0], $n[1]);
    }
  }

  $restricted = !MTrackACL::hasAnyRights('Browser', 'create');
  if ($rid == 'new') {
    if (isset($_POST['repo:name'])) {
      $P->shortname = $_POST["repo:name"];
    }
    if (isset($_POST['repo:type'])) {
      $P->scmtype = $_POST["repo:type"];
    }
    if (isset($_POST['repo:path'])) {
      if ($restricted) throw new Exception("cannot set the repo path");
      $P->repopath = $_POST["repo:path"];
    }
    if (isset($_POST['repo:parent']) && strlen($_POST['repo:parent'])) {
      $P->parent = $_POST["repo:parent"];
    }
  } else {
    $editable = !strlen($P->parent);

    if (isset($_POST['repo:name']) && $_POST['repo:name'] != $P->shortname) {
      if (!$editable) throw new Exception("cannot change the repo name");
      $P->shortname = $_POST["repo:name"];
    }
    if (isset($_POST['repo:type']) && $_POST['repo:type'] != $P->scmtype) {
      if (!$editable) throw new Exception("cannot change the repo type");
      $P->scmtype = $_POST["repo:type"];
    }
    if (isset($_POST['repo:path']) && $_POST['repo:path'] != $P->repopath) {
      if (!$editable) throw new Exception("cannot change the repo path");
      $P->repopath = $_POST["repo:path"];
    }
    if (isset($_POST['repo:parent']) && $_POST['repo:parent'] != $P->parent) {
      if (!$editable) throw new Exception("cannot change the repo parent");
      $P->parent = $_POST["repo:parent"];
    }
  }
  if (isset($_POST["repo:description"])) {
    $P->description = $_POST["repo:description"];
  }

  $CS = MTrackChangeset::begin("repo:$rid", "Edit repo $P->shortname");
  $P->save($CS);
  $CS->setObject("repo:$P->repoid");

  if (isset($_POST['perms'])) {
    $perms = json_decode($_POST['perms']);
    MTrackACL::setACL("repo:$P->repoid", 0, $perms);
  }

  $CS->commit();
  header("Location: ${ABSWEB}browse.php/" . $P->getBrowseRootName());
  exit;
}

mtrack_head("Administration - Repositories");
if (!strlen($rid)) {
  MTrackACL::requireAnyRights('Browser', 'modify');
?>
<h1>Repositories</h1>

<p>
Repositories are version controlled folders that remember your files and
folders at various points in time.  Mtrack has support for multiple different
Software Configuration Management systems (also known as Version Control
Systems; SCM and VCS are the common acronyms).
</p>
<p>
Listed below are the repositories that mtrack is configured to use.
The <em>wiki</em> repository is treated specially by mtrack; it stores the
wiki pages.  Click on the repository name to edit it, or click on the "Add"
button to tell mtrack to use another repository.
</p>
<ul>
<?php
  foreach (MTrackDB::q(
      'select repoid, shortname, parent from repos order by parent, shortname')
      as $row) {
    $rid = $row[0];
    if (MTrackACL::hasAnyRights("repo:$rid", 'modify')) {
      $name = MTrackSCM::makeDisplayName($row);
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');
      echo "<li><a href='{$ABSWEB}admin/repo.php/$rid'>$name</a></li>\n";
    }
  }
  echo "</ul>";
  if (MTrackACL::hasAnyRights('Browser', 'create')) {
    echo "<a href='{$ABSWEB}admin/repo.php/new'>Add new repo</a><br>\n";
  }
  mtrack_foot();
  exit;
}

$repotypes = array();
foreach (MTrackRepo::getAvailableSCMs() as $t => $r) {
  $d = $r->getSCMMetaData();
  $repotypes[$t] = $d['name'];
}

echo "<form method='post'>";

if ($rid == 'new') {
  MTrackACL::requireAnyRights('Browser', 'create');
?>
<h2>Add new or existing Repository</h2>
<p>
  Use the form below to tell mtrack where to find an existing
  repository and add it to its list.  Leave the "Path" field
  blank to create a new repository.
</p>
<table>
<?php
  echo "<tr><th>Name</th>" .
    "<td><input type='text' name='repo:name' value=''></td>" .
    "</tr>";
  echo "<tr><th>Type</th>" .
    "<td>" .
    mtrack_select_box("repo:type", $repotypes, null, true) .
    "</td></tr>\n";
  echo "<tr><th>Path</th>" .
    "<td><input type='text' name='repo:path' size='50' value=''></td>" .
    "</tr>\n";
  echo "<tr><td colspan='2'>Description<br><em>You may use <a href='{$ABSWEB}help.php/WikiFormatting' target='_blank'>WikiFormatting</a></em><br>\n";
  echo "<textarea name='repo:description' class='wiki shortwiki' rows='5' cols='78'>";
  echo "</textarea></td></tr>\n";
  echo "</table>";
} else {
  $P = MTrackRepo::loadById($rid);
  MTrackACL::requireAnyRights("repo:$P->repoid", 'modify');

  $name = htmlentities($P->shortname, ENT_QUOTES, 'utf-8');
  $type = htmlentities($P->scmtype, ENT_QUOTES, 'utf-8');
  $path = htmlentities($P->repopath, ENT_QUOTES, 'utf-8');
  $desc = htmlentities($P->description, ENT_QUOTES, 'utf-8');

  echo "<h2>Repository: $name</h2>\n";
  echo "<table>\n";

  if (!$P->parent) {
    /* not created/managed by us; some fields are editable */
    $name = "<input type='text' name='repo:name' value='$name'>";
    $type = mtrack_select_box("repo:type", $repotypes, $type);
    $path = "<input type='text' name='repo:path' size='50' value='$path'>";
  } else {
    $name = htmlentities($P->getBrowseRootName(), ENT_QUOTES, 'utf-8');
  }

  echo "<tr><th>Name</th><td>$name</td></tr>";
  echo "<tr><th>Type</th><td>$type</td></tr>\n";
  echo "<tr><th>Path</th><td>$path</td></tr>\n";
  echo "<tr><td colspan='2'>Description<br><em>You may use <a href='{$ABSWEB}help.php/WikiFormatting' target='_blank'>WikiFormatting</a></em><br>\n";
  echo "<textarea name='repo:description' class='wiki shortwiki' rows='5' cols='78'>$desc";
  echo "</textarea></td></tr>\n";

  echo "<tr><td colspan='2'>\n";

  $action_map = array(
    'Web' => array(
      'read'   => 'Browse via web UI',
      'modify' => 'Administer via web UI',
      'delete' => 'Delete repo via web UI',
    ),
    'SSH' => array(
      'checkout' => 'Check-out repo via SSH',
      'commit' => 'Commit changes to repo via SSH',
    ),
  );

  MTrackACL::renderACLForm('perms', "repo:$P->repoid", $action_map);

  echo "</tr>\n";
  echo "</table>";
}

$projects = array();
foreach (MTrackDB::q('select projid, name, shortname from projects
    order by name')->fetchAll() as $row) {
  if ($row[1] != $row[2]) {
    $projects[$row[0]] = $row[1] . " ($row[2])";
  } else {
    $projects[$row[0]] = $row[1];
  }
}

if (count($projects)) {

  echo <<<HTML
<h3>Linked Projects</h3>
<p>
Project links help associate code changes made in a repository with a project,
and this in turn helps mtrack decide who to notify about the change.
</p>
<p>
When assessing a change, mtrack will try each regex listed below and then take
the project that corresponds with the longest match--not the longest pattern;
the longest actual match.
</p>
<p>
The regex should just be the bare regex string--you must not enclose it in
regex delimiters.
</p>
<p>
You can remove a link by setting the regex to the empty string.
</p>
HTML;

  echo "<table>";
  echo "<tr><th>Regex</th><th>Project</th></tr>\n";

  if ($rid != 'new') {
    foreach ($P->getLinks() as $lid => $n) {
      list($pid, $regex) = $n;

      $regex = htmlentities($regex, ENT_QUOTES, 'utf-8');
      echo "<tr><td>" .
        "<input type='text' name='link:$lid:regex' value='$regex'></td>".
        "<td>" . mtrack_select_box("link:$lid:project", $projects, $pid) .
        "</td></tr>\n";
    }
  }

  if ($rid == 'new') {
    $newre = '/';
  } else {
    $newre = '';
  }

  echo "<tr><td>" .
    "<input type='text' name='link:new:regex' value='$newre'></td>".
    "<td>" . mtrack_select_box("link:new:project", $projects) .
    "</td><td>Add new link</td></tr>\n";

  echo "</table>";
}

echo "<button>Save Changes</button></form>";

mtrack_foot();

