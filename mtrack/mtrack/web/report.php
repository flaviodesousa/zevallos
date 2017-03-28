<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';

$pi = mtrack_get_pathinfo();
$edit = isset($_REQUEST['edit']);

if (!strlen($pi)) {
  if ($edit) {
    MTrackACL::requireAllRights('Reports', 'create');
    $rep = new MTrackReport;
  } else {
    throw new Exception("no report to render");
  }
} elseif (ctype_digit($pi)) {
  $rep = MTrackReport::loadByID($pi);
  MTrackACL::requireAllRights("report:" . $rep->rid, $edit ? 'modify' : 'read');
} else {
  $rep = MTrackReport::loadBySummary($pi);
  MTrackACL::requireAllRights("report:" . $rep->rid, $edit ? 'modify' : 'read');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $rep->summary = $_POST['name'];
  $rep->description = $_POST['description'];
  $rep->query = $_POST['query'];

  if (isset($_POST['cancel'])) {
    header("Location: {$ABSWEB}reports.php");
    exit;
  }

  if (isset($_POST['save'])) {
    try {
      $cs = MTrackChangeset::begin(
              "report:" . $rep->summary, $_POST['comment']);
      $rep->save($cs);
      $cs->commit();
      header("Location: {$ABSWEB}report.php/$rep->rid");
      exit;
    } catch (Exception $e) {
      $message = $e->getMessage();
    }
  }
}

if (isset($_GET['format'])) {
  // targeted report format; omit decoration
  $params = $_GET;
  unset($params['format']);
  switch ($_GET['format']) {
    case 'tab':
      header('Content-Type: text/plain');
      break;
  }
  echo $rep->renderReport($rep->query, $params, $_GET['format']);
  exit;
}

if ($rep->rid) {
  if ($edit) {
    mtrack_head('{' . $rep->rid . '} ' . $rep->summary . " (edit)");
  } else {
    mtrack_head('{' . $rep->rid . '} ' . $rep->summary);
  }
} else {
  mtrack_head("Create Report");
}

if (!empty($message)) {
  echo "<div class='error'>" . htmlentities($message, ENT_COMPAT, 'utf-8') . "</div>\n";
}

if (!$edit || isset($_POST['preview'])) {
  echo "<h1>" . htmlentities($rep->summary, ENT_COMPAT, 'utf-8') . "</h1>";
  echo MTrackWiki::format_to_html($rep->description);
  echo $rep->renderReport($rep->query);

  if ($edit) {
    echo "<hr>";
  } else if (MTrackACL::hasAllRights("report:" . $rep->rid, 'modify')) {
    echo <<<HTML
<form name="editreport" method="GET" action="{$ABSWEB}report.php/$rep->rid">
<button type="submit" name="edit">Edit Report</button>
</form>
HTML;
  }
}


if ($edit) {
  echo <<<HTML
<form name="editreport" method="POST" action="{$ABSWEB}report.php/$rep->rid">
<input type="hidden" name="edit" value="1">
HTML;

  if ($rep->rid) {
    echo "<input type='hidden' name='rid' value='$rep->rid'/>\n";
    echo '{' . $rep->rid . '} ';
  }

  $name = htmlentities($rep->summary, ENT_QUOTES, 'utf-8');
  $desc = htmlentities($rep->description, ENT_QUOTES, 'utf-8');
  $query = htmlentities($rep->query, ENT_QUOTES, 'utf-8');

  echo <<<HTML
<label>Name: <input type="text" size="60" name='name' value="$name"></label><br/>
<label>Description:<br/>
<textarea name="description" rows="12" cols="76">$desc</textarea>
</label><br/>
<label>SQL Query:<br/>
<textarea name="query" class="code" rows="20" cols="76">$query</textarea>
</label>
<div class="buttons">
  <button type="submit" name="preview">Preview</button>
  <button type="submit" name="cancel">Cancel</button>
</div>
  Reason for change: <input type="text" name="comment">
  <button type="submit" name="save">Save changes</button>

</form>
HTML;

}
mtrack_foot();
