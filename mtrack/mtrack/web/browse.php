<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';

$USE_AJAX = false;

MTrackACL::requireAllRights('Browser', 'read');

$pi = mtrack_get_pathinfo(true);
$crumbs = MTrackSCM::makeBreadcrumbs($pi);
if (!strlen($pi) || $pi == '/') {
  $pi = '/';
}
if (count($crumbs) > 2) {
  $repo = MTrackSCM::factory($pi);
} else {
  $repo = null;
}

if (!isset($_GET['_'])) {
  $AJAX = false;
} else {
  $AJAX = true;
}

function one_line_cl($changelog)
{
  list($one) = explode("\n", $changelog);
  return rtrim($one, " \r\n");
}

function get_browse_data($repo, $pi, $object, $ident)
{
  global $ABSWEB;

  $data = new stdclass;
  $data->dirs = array();
  $data->files = array();
  $data->jumps = array();

  if (!$repo) {
    return $data;
  }
  $branches = $repo->getBranches();
  $tags = $repo->getTags();
  if (count($branches) + count($tags)) {
    $jumps = array("" => "- Select Branch / Tag - ");
    if (is_array($branches)) {
      foreach ($branches as $name => $notcare) {
        $jumps["branch:$name"] = "Branch: $name";
      }
    }
    if (is_array($tags)) {
      foreach ($tags as $name => $notcare) {
        $jumps["tag:$name"] = "Tag: $name";
      }
    }
    $data->jumps = $jumps;
  }
  $files = array();
  $dirs = array();

  if ($repo) {
    try {
      $ents = $repo->readdir($pi, $object, $ident);
    } catch (Exception $e) {
      // Typically a freshly created repo
      $ents = array();
      $data->err = $e->getMessage();
    }
    foreach ($ents as $file) {
      $basename = basename($file->name);
      if ($file->is_dir) {
        $dirs[$basename] = $file;
      } else {
        $files[$basename] = $file;
      }
    }
  }
  uksort($files, 'strnatcmp');
  uksort($dirs, 'strnatcmp');

  $data->files = array();
  $data->dirs = array();

  $urlbase = $ABSWEB . 'browse.php';
  $pathbase = '/' . $repo->getBrowseRootName();
  $urlbase .= $pathbase;

  foreach ($dirs as $basename => $file) {
    $ent = $file->getChangeEvent();
    $url = $urlbase . '/' . $file->name;
    $d = new stdclass;
    $d->url = $url;
    $d->basename = $basename;
    $d->rev = $ent->rev;
    $d->ctime = $ent->ctime;
    $d->changeby = $ent->changeby;
    $d->changelog = one_line_cl($ent->changelog);

    $data->dirs[] = $d;
  }
  foreach ($files as $basename => $file) {
    $ent = $file->getChangeEvent();
    $url = $ABSWEB . 'file.php' . $pathbase .
            '/' . $file->name . '?rev=' . $ent->rev;
    $d = new stdclass;
    $d->url = $url;
    $d->basename = $basename;
    $d->rev = $ent->rev;
    $d->ctime = $ent->ctime;
    $d->changeby = $ent->changeby;
    $d->changelog = one_line_cl($ent->changelog);

    $data->files[] = $d;
  }

  return $data;
}

if (isset($_GET['jump']) && strlen($_GET['jump'])) {
  list($object, $ident) = explode(':', $_GET['jump'], 2);
} else {
  $object = null;
  $ident = null;
}

if ($USE_AJAX && !$AJAX) {
  mtrack_head("Browse $pi");

  // Since big dirs can take a while to gather the browse data,
  // We want to show *something* to the user while we wait for
  // the data to come in
  $g = $_GET;
  $g['_'] = '_';
  $url = $_SERVER['REQUEST_URI'] . '?' . http_build_query($g);
  echo <<<HTML
<div id='browsediv'>
  <p>Loading browse data, please wait</p>
</div>
<script>
\$(document).ready(function () {
  \$('#browsediv').load('$url');
});
</script>
HTML;
  mtrack_foot();
} else {
  if (!$USE_AJAX) {
    mtrack_head("Browse $pi");
  }

$bdata = mtrack_cache('get_browse_data',
  array($repo, $pi, $object, $ident));

if (isset($bdata->err) && strlen($pi) > 1) {
  throw new Exception($bdata->err);
}

/* Render a bread-crumb enabled location indicator */
echo "<div class='browselocation'>Location: ";
$location = null;
foreach ($crumbs as $path) {
  if (!strlen($path)) {
    $path = '[root]';
  } else {
    $location .= '/' . urlencode($path);
  }
  $path = htmlentities($path, ENT_QUOTES, 'utf-8');
  echo "<a href='{$ABSWEB}browse.php$location'>$path</a> / ";
}

if (count($bdata->jumps)) {
  echo "<form>";
  echo mtrack_select_box("jump", $bdata->jumps,
        isset($_GET['jump']) ? $_GET['jump'] : null);
  echo "<button type='submit'>Choose</button></form>\n";
}

echo "</div>";

$me = mtrack_canon_username(MTrackAuth::whoami());
if (MTrackACL::hasAllRights('Browser', 'create')) {
  /* some users may have rights to create repos that belong to projects.
    * Determine that list of projects here, because we need it for both
    * the fork and new repo cases */
  $owners = array("user:$me" => $me);

  foreach (MTrackDB::q(
      'select projid, shortname, name from projects order by ordinal')
      as $row)
  {
    if (MTrackACL::hasAllRights("project:$row[0]", 'modify')) {
      $owners["project:$row[1]"] = $row[1];
    }
  }
  if (count($owners) > 1) {
    $owners = mtrack_select_box('repo:parent', $owners, null, true);
  } else {
    $owners = '';
  }
}

if ($repo) {
  MTrackACL::requireAllRights("repo:$repo->repoid", 'read');

  $description = MTrackWiki::format_to_html($repo->description);
  $url = $repo->getCheckoutCommand();

  echo "<div class='repodesc'>$description</div>";
  if (strlen($url)) {
    echo "<div class='checkout'>\n";
    echo "Use the following command to obtain a working copy:<br>";
    echo "<pre>\$ $url</pre>";
    echo "</div>\n";
  }


  if ($repo->canFork() && MTrackACL::hasAllRights('Browser', 'fork')
      && MTrackConfig::get('repos', 'allow_user_repo_creation')) {
    $forkname = "$me/$repo->shortname";
    if ($forkname == $repo->getBrowseRootName()) {
      /* if this is mine already, make a "more unique" name for my new fork */
      $forkname = $repo->shortname . '2';
    } else {
      $forkname = $repo->shortname;
    }
    $forkname = htmlentities($forkname, ENT_QUOTES, 'utf-8');
    echo <<<FORK
<div id='forkdialog' style='display:none'
  title='Really create a fork?'>
<form id='forkform' action='${ABSWEB}admin/forkrepo.php' method='post'>
  <input type='hidden' name='source' value='$repo->repoid'>
  <p>
    A fork is your own copy of a repo that is stored and maintained
    on the server.
  </p>
  <p>
    If all you want to do is obtain a working copy so that you can
    collaborate on this repo, you should not create a fork.
  </p>
  <p>
    You may want to fork if you want the server to keep your work backed up,
    or to collaborate with others on work that you want to share
    with this repo later on.
  </p>
  <p>
    Choose a name for your fork:
    $owners <input type='text' name='name' value='$forkname'>
  </p>
</form>
</div>
<button id='forkbtn' type='button'>Fork</button>
<script>
\$(document).ready(function() {
  \$('#forkdialog').dialog({
    autoOpen: false,
    bgiframe: true,
    resizable: false,
    width: 600,
    modal: true,
    buttons: {
      'No': function() {
        $(this).dialog('close');
      },
      'Fork': function() {
        $('#forkform').submit();
      }
    }
  });
  \$('#forkbtn').click(function () {
    \$('#forkdialog').dialog('open');
    return false;
  });
});
</script>
FORK
    ;
  }
  $mine = "user:$me";
  if ($repo->parent &&
      MTrackACL::hasAllRights("repo:$repo->repoid", "delete")) {
    echo <<<FORK
      <div id='deletedialog' style='display:none'
      title='Really delete this repo?'>
      <form id='deleteform' action='${ABSWEB}admin/deleterepo.php'
        method='post'>
      <input type='hidden' name='repoid' value='$repo->repoid'>
      <p>Are you sure you want to delete this repo?</p>
      <p><b>You cannot undo this action; any data will be permanently
        deleted</b></p>
      </form>
      </div>
      <button id='deletebtn' type='button'>Delete</button>
<script>
\$(document).ready(function() {
  \$('#deletedialog').dialog({
    autoOpen: false,
    bgiframe: true,
    resizable: false,
    modal: true,
    buttons: {
      'No': function() {
        $(this).dialog('close');
      },
      'Delete': function() {
        $('#deleteform').submit();
      }
    }
  });
  \$('#deletebtn').click(function () {
    \$('#deletedialog').dialog('open');
    return false;
  });
});
</script>
FORK
;
  }
  if (MTrackACL::hasAllRights("repo:$repo->repoid", "modify")) {
    echo <<<EDIT
<a class='button' href='{$ABSWEB}admin/repo.php/$repo->repoid'>Edit</a>
EDIT
    ;
  }
  MTrackWatch::renderWatchUI('repo', $repo->repoid);

  echo "<br>\n<a href='{$ABSWEB}log.php/{$repo->getBrowseRootName()}/$pi'>Show History</a><br>\n";
}

if (!$repo && MTrackACL::hasAllRights('Browser', 'fork')
    && MTrackConfig::get('repos', 'allow_user_repo_creation')) {
$repotypes = array();
foreach (MTrackRepo::getAvailableSCMs() as $t => $r) {
  $d = $r->getSCMMetaData();
  $repotypes[$t] = $d['name'];
}
$repotypes = mtrack_select_box("repo:type", $repotypes, null, true);
echo <<<NEWREPO
<div id='newdialog' style='display:none'
  title='Create a new repo?'>
<form id='newrepoform' action='${ABSWEB}admin/repo.php/new' method='post'>
<p>
  Choose a name for your repo:
  $owners <input type='text' name='repo:name' value='myrepo'>
</p>
<p>
  Choose a repository type: $repotypes
</p>
<p>
  Description:<br>
  <em>You may use <a href='{$ABSWEB}help.php/WikiFormatting' target='_blank'>WikiFormatting</a></em><br>
  <textarea name='repo:description' class='wiki shortwiki' rows='5' cols='78'></textarea>
</form>
</div>
<button id='newrepobtn' type='button'>New</button>
<script>
\$(document).ready(function() {
  \$('#newdialog').dialog({
    autoOpen: false,
    bgiframe: true,
    resizable: false,
    width: 600,
    modal: true,
    buttons: {
      'Cancel': function() {
        $(this).dialog('close');
      },
      'Create': function() {
        $('#newrepoform').submit();
      }
    }
  });
  \$('#newrepobtn').click(function () {
    \$('#newdialog').dialog('open');
    return false;
  });
});
</script>
NEWREPO
;
}

echo "<br>\n";

?>
<table class='listing' id='dirlist'>
  <thead>
    <tr>
<?php
if (!$repo) {
?>
      <th class='name' width='1%'>Name</th>
      <th class='desc'>Description</th>
<?php
} else {
?>
      <th class='name' width='1%'>Name</th>
      <th class='rev' width='1%'>Revision</th>
      <th class='age' width='1%'>Age</th>
      <th class='change'>Last Change</th>
<?php
}
?>
    </tr>
  </thead>
  <tbody>
<?php
$even = 1;

if (count($crumbs) > 1) {
  $class = $even++ % 2 ? 'even' : 'odd';
  $url = $ABSWEB . 'browse.php' . dirname(mtrack_get_pathinfo(true));
  if (isset($_GET['jump'])) {
    $url .= '?jump=' . urlencode($_GET['jump']);
  }
  $url = htmlentities($url, ENT_QUOTES, 'utf-8');

  echo "<tr class='$class'>\n";
  echo "<td class='name'><a class='parent' href='$url'>.. [up]</a></td>";
  if ($repo) {
    echo "<td class='rev'></td>\n";
    echo "<td class='age'></td>\n";
    echo "<td class='change'></td>\n";
  } else {
    echo "<td class='desc'></td>\n";
  }
  echo "</tr>\n";
}

foreach ($bdata->dirs as $d) {
  $class = $even++ % 2 ? 'even' : 'odd';
  $url = $d->url;
  if (isset($_GET['jump'])) {
    $url .= '?jump=' . urlencode($_GET['jump']);
  }
  $url = htmlentities($url, ENT_QUOTES, 'utf-8');
  echo "<tr class='$class'>\n";
  echo "<td class='name'><a class='dir' href='$url'>$d->basename</a></td>";
  echo "<td class='rev'>" . mtrack_changeset($d->rev, $repo) . "</td>\n";
  echo "<td class='age'>" . mtrack_date($d->ctime) . "</td>\n";
  echo "<td class='change'>" .
    mtrack_username($d->changeby, array('size' => 16)) . ": " .
    MTrackWiki::format_to_oneliner($d->changelog) . "</td>\n";
  echo "</tr>\n";
}

foreach ($bdata->files as $d) {
  $class = $even++ % 2 ? 'even' : 'odd';
  $url = $d->url;
  if (isset($_GET['jump'])) {
    $url .= '&jump=' . urlencode($_GET['jump']);
  }
  $url = htmlentities($url, ENT_QUOTES, 'utf-8');
  echo "<tr class='$class'>\n";
  echo "<td class='name'><a class='file' href='$url'>$d->basename</a></td>";
  echo "<td class='rev'>" . mtrack_changeset($d->rev, $repo) . "</td>\n";
  echo "<td class='age'>" . mtrack_date($d->ctime) . "</td>\n";
  echo "<td class='change'>" .
    mtrack_username($d->changeby, array('size' => 16)) . ": " .
    MTrackWiki::format_to_oneliner($d->changelog) . "</td>\n";
  echo "</tr>\n";
}

if (!$repo) {
  $mine = 'user:' . mtrack_canon_username(MTrackAuth::whoami());
  $params = array();
  if (count($crumbs) == 2 && $crumbs[1] != 'default') {
    /* looking for a particular subset */
    $where = "parent like('%:' || ?)";
    $params[] = $crumbs[1];
  } else if (count($crumbs) == 2 && $crumbs[1] == 'default') {
    /* looking at system items */
    $where = "parent = ''";
  } else {
    /* looking for top level items */
    $where = "1 = 1";
  }
  /* have my own repos bubble up */
  $params[] = $mine;
  $sql = <<<SQL
select repoid, parent, shortname, description
from repos
where $where
order by
  case when parent = ? then 0 else 1 end,
  parent,
  shortname
SQL
  ;
  $q = MTrackDB::get()->prepare($sql);
  $q->execute($params);

  foreach ($q->fetchAll(PDO::FETCH_OBJ) as $rep) {
    if (!MTrackACL::hasAnyRights("repo:$rep->repoid", 'read')) {
      continue;
    }

    $class = $even++ % 2 ? 'even' : 'odd';
    $url = $ABSWEB . 'browse.php/';
    $label = MTrackRepo::makeDisplayName($rep);

    $url .= $label;
    echo "<tr class='$class'>\n";
    echo "<td class='name'><a class='dir' href='$url'>$label</a></td>\n";
    $desc = MTrackWiki::format_to_html($rep->description);
    echo "<td class='desc'>$desc</td>\n";
    echo "</tr>\n";
  }
}

echo "</tbody></table>\n";

  if (!$USE_AJAX) {
    mtrack_foot();
  }

}
