<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

if ($pi = mtrack_get_pathinfo()) {
  $id = $pi;
} else {
  $id = $_GET['id'];
}

if ($id == 'new') {
  $issue = new MTrackIssue;
  $issue->priority = 'normal';
} else {
  if (strlen($id) == 32) {
    $issue = MTrackIssue::loadById($id);
  } else {
    $issue = MTrackIssue::loadByNSIdent($id);
  }
  if (!$issue) {
    throw new Exception("Invalid ticket $id");
  }
}

$FIELDSET = array(
    array(
      "description" => array(
        "label" => "Full description",
        "ownrow" => true,
        "type" => "wiki",
        "rows" => 10,
        "cols" => 78,
        "editonly" => true,
        ),
      ),
    "Properties" => array(
      "milestone" => array(
        "label" => "Milestone",
        "type" => "multiselect",
        ),
      "component" => array(
        "label" => "Component",
        "type" => "multiselect",
        ),
      "classification" => array(
        "label" => "Classification",
        "type" => "select",
        ),
      "priority" => array(
        "label" => "Priority",
        "type" => "select",
        ),
      "severity" => array(
        "label" => "Severity",
        "type" => "select",
        ),
      "keywords" => array(
          "label" => "Keywords",
          "type" => "text",
          ),
      "changelog" => array(
          "label" => "ChangeLog (customer visible)",
          "type" => "multi",
          "ownrow" => true,
          "rows" => 5,
          "cols" => 78,
       #   "condition" => $issue->status == 'closed'
          ),
      ),
      "Resources" => array(
          "owner" => array(
            "label" => "Responsible",
            "type" => "select"
            ),
          "estimated" => array(
            "label" => "Estimated Hours",
            "type" => "text"
            ),
          "spent" => array(
            "label" => "Spent Hours",
            "type" => "text",
            "readonly" => true,
            ),
          "cc" => array(
            "label" => "Cc",
            "type" => "text"
            ),
          ),
      );
$issue->augmentFormFields($FIELDSET);


$preview = false;
$error = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['cancel'])) {
    header("Location: {$ABSWEB}ticket.php/$issue->nsident");
    exit;
  }
  if (!MTrackCaptcha::check('ticket')) {
    $error[] = "CAPTCHA failed, please try again";
  }
  $preview = isset($_POST['preview']) ? true : false;

  $comment = '';
  try {
    if ($id == 'new') {
      MTrackACL::requireAllRights("Tickets", 'create');
    } else {
      MTrackACL::requireAllRights("ticket:" . $issue->tid, 'modify');
    }
  } catch (Exception $e) {
    $error[] = $e->getMessage();
  }
  if ($id == 'new') {
    $comment = $_POST['comment'];
  }
  if (!strlen($comment)) {
    $comment = $_POST['summary'];
  }
  try {
    $CS = MTrackChangeset::begin("ticket:X", $comment);
  } catch (Exception $e) {
    $error[] = $e->getMessage();
    $CS = null;
  }
  if ($id == 'new') {
    // compute next id number.
    // We don't use auto-number, because we allow for importing multiple
    // projects with their own ticket sequence.
    // During "normal" user-driven operation, we do want plain old id numbers
    // so we compute it here, under a transaction
    $db = MTrackDB::get();
    if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
        // Some versions of postgres don't like that we have "abc123" for
        // identifiers, so match on the bigest number nsident fields only
      $max = "select max(cast(nsident as integer)) + 1 from tickets where nsident ~ '^\\\\d+$'";
    } else {
      $max = 'select max(cast(nsident as integer)) + 1 from tickets';
    }
    list($issue->nsident) = MTrackDB::q($max)->fetchAll(PDO::FETCH_COLUMN, 0);
    if ($issue->nsident === null) {
      $issue->nsident = 1;
    }
  }

  if (isset($_POST['action']) && !$preview) {
    switch ($_POST['action']) {
      case 'leave':
        break;
      case 'reopen':
        $issue->reOpen();
        break;
      case 'fixed':
        $issue->resolution = 'fixed';
        $issue->close();
        $_POST['estimated'] = $issue->estimated;
        break;
      case 'resolve':
        $issue->resolution = $_POST['resolution'];
        $issue->close();
        $_POST['estimated'] = $issue->estimated;
        break;
      case 'accept':
        // will be applied to the issue further down
        $_POST['owner'] = MTrackAuth::whoami();
        if ($issue->status == 'new') {
          $issue->status = 'open';
        }
        break;
      case 'changestatus':
        $issue->status = $_POST['status'];
        break;
    }
  }

  $fields = array(
    'summary',
    'description',
    'classification',
    'priority',
    'severity',
    'changelog',
    'owner',
    'cc',
  );

  $issue->applyPOSTData($_POST);

  foreach ($fields as $fieldname) {
    if (isset($_POST[$fieldname]) && strlen($_POST[$fieldname])) {
      $issue->$fieldname = $_POST[$fieldname];
    } else {
      $issue->$fieldname = null;
    }
  }

  $kw = $issue->getKeywords();
  $kill = array_values($kw);
  foreach (preg_split('/[ \t,]+/', $_POST['keywords']) as $w) {
    if (!strlen($w)) {
      continue;
    }
    $x = array_search($w, $kw);
    if ($x === false) {
      $k = MTrackKeyword::loadByWord($w);
      if ($k === null) {
        $k = new MTrackKeyword;
        $k->keyword = $w;
        $k->save($CS);
      }
      $issue->assocKeyword($k);
    } else {
      $w = array_search($w, $kill);
      if ($w !== false) {
        unset($kill[$w]);
      }
    }
  }
  foreach ($kill as $w) {
    $issue->dissocKeyword($w);
  }

  $ms = $issue->getMilestones();
  $kill = $ms;
  if (isset($_POST['milestone']) && is_array($_POST['milestone'])) {
    foreach ($_POST['milestone'] as $mid) {
      $issue->assocMilestone($mid);
      unset($kill[$mid]);
    }
  }
  foreach ($kill as $mid) {
    $issue->dissocMilestone($mid);
  }

  $ms = $issue->getComponents();
  $kill = $ms;
  if (isset($_POST['component']) && is_array($_POST['component'])) {
    foreach ($_POST['component'] as $mid) {
      $issue->assocComponent($mid);
      unset($kill[$mid]);
    }
  }
  foreach ($kill as $mid) {
    $issue->dissocComponent($mid);
  }

  $issue->addComment($_POST['comment']);
  $issue->addEffort($_POST['spent'], $_POST['estimated']);

  if (!count($error)) {
    try {
      $issue->save($CS);
      $CS->setObject("ticket:" . $issue->tid);
    } catch (Exception $e) {
      $error[] = $e->getMessage();
    }
  }

  if (!count($error)) {
    if (isset($_FILES['attachments']) && is_array($_FILES['attachments'])) {
      foreach ($_FILES['attachments']['name'] as $fileid => $name) {
        MTrackAttachment::add("ticket:$issue->tid",
            $_FILES['attachments']['tmp_name'][$fileid],
            $_FILES['attachments']['name'][$fileid],
            $CS);
      }
    }
  }
  if (!count($error) && $id != 'new') {
    MTrackAttachment::process_delete("ticket:$issue->tid", $CS);
  }

  if (isset($_POST['apply']) && !count($error)) {
    $CS->commit();
    header("Location: {$ABSWEB}ticket.php/$issue->nsident");
    exit;
  }
}

if ($id == 'new') {
  MTrackACL::requireAllRights("Tickets", 'create');
  mtrack_head("New ticket");
} else {
  MTrackACL::requireAllRights("ticket:" . $issue->tid, 'read');
  if ($issue->nsident) {
    mtrack_head("#$issue->nsident " . $issue->summary);
  } else {
    mtrack_head("#$id " . $issue->summary);
  }
}

echo "<form id='tktedit' method='post' action='{$ABSWEB}ticket.php/$id' enctype='multipart/form-data'>\n";
/* now to render the edit controls, if suitably privileged */
if ($id == 'new') {
  $editable = MTrackACL::hasAllRights("Tickets", 'create');
} else {
  $editable = MTrackACL::hasAllRights("ticket:" . $issue->tid, 'modify');
}

echo <<<HTML
<div id="issue-desc">
HTML;

if ($preview) {
  echo <<<HTML
<div class='ui-state-highlight ui-corner-all'>
    <span class='ui-icon ui-icon-info'></span>
    This is a preview of your pending changes.  It does not show
    changes to the resolution; those will be applied when you submit.
</div>

HTML;
}
if (count($error)) {
  foreach ($error as $e) {
    echo <<<HTML
<div class='ui-state-error ui-corner-all'>
    <span class='ui-icon ui-icon-alert'></span>
HTML;
    echo htmlentities($e, ENT_QUOTES, 'utf-8') . "\n</div>\n";
  }
}

if ($id != 'new') {
  echo "<h1>";
  if (!$issue->isOpen()) {
    echo "<del>";
  }
  if ($issue->nsident) {
    echo "#$issue->nsident ";
  } else {
    echo "#$id ";
  }

  echo htmlentities($issue->summary, ENT_QUOTES, 'utf-8');

  if (!$issue->isOpen()) {
    echo "</del>";
  }
  echo "</h1>\n";
}

if ($id == 'new') {
  $created = new stdClass;
  $created->when = MTrackDB::unixtime(time());
  $created->who = MTrackAuth::whoami();
} else {
  $created = MTrackChangeset::get($issue->created);
}

$opened = mtrack_date($created->when);
echo <<<HTML
<div id="ticketinfo">
HTML;

$pseudo_fields = array();

if ($id != 'new') {
  echo "<table id='ctime'><tr><td><label>Opened</label>:</td><td>",
       mtrack_date($created->when),
       "</td><td>",
       mtrack_username($created->who, array('no_image' => true)),
       "</td></tr>\n";
  if ($issue->updated != $issue->created) {
    $updated = MTrackChangeset::get($issue->updated);
    echo "<tr><td><label>Updated</label>:</td><td>",
      mtrack_date($updated->when),
      "</td><td>",
      mtrack_username($updated->who, array('no_image' => true)),
      "</td></tr>";
  }
  echo "</table>";

  $v = get_components_list(join(',', array_keys($issue->getComponents())));
  $pseudo_fields['@components'] = $v;

  $v = get_milestones_list(join(',', array_keys($issue->getMilestones())));
  $pseudo_fields['@milestones'] = $v;

  $v = get_keywords_list(join(',', array_keys($issue->getKeywords())));
  $pseudo_fields['@keywords'] = $v;

  $ROFIELDSET = $FIELDSET;
  $ROFIELDSET['Properties']['resolution'] = array(
    'label' => 'Resolution',
    'type' => 'text',
  );

  foreach ($ROFIELDSET as $fsid => $fieldset) {
    $emited = false;
    foreach ($fieldset as $propname => $info) {
      if (isset($info['editonly'])) {
        continue;
      }
      $value = null;
      switch ($propname) {
        case 'keywords':
          $value = array();
          foreach ($issue->getKeywords() as $kw) {
            $value[] = mtrack_keyword($kw);
          }
          $value = join(' ', $value);
          break;
        case 'milestone':
          $value = $pseudo_fields['@milestones'];
          break;
        case 'component':
          $value = $pseudo_fields['@components'];
          break;
        default:
          $value = $issue->$propname;
      }

      if (strlen($value)) {
        if (!$emited) {
          $rfsid = 'readonly-tkt-' .
            preg_replace('/[^a-z]+/', '', strtolower($fsid));
          echo "<fieldset id='$rfsid'><legend>$fsid</legend>\n<table>";
          $emited = true;
        }

        switch ($info['type']) {
          case 'wiki':
            $value = MTrackWiki::format_to_html($value);
            break;
          case 'multi':
            $value = nl2br(htmlentities($value, ENT_QUOTES, 'utf-8'));
            break;
        }

        if (isset($info['ownrow']) && $info['ownrow'] == 'true') {
          echo "<tr><td colspan='2'><label>$info[label]</label>:</td></tr>";
          echo "<td colspan='2'>$value</td></tr>\n";
        } else {
          echo "<tr><td><label>$info[label]</label>:</td><td width='100%'>$value</td></tr>\n";
        }
      }
    }
    if ($emited) {
      echo "</table></fieldset>\n";
    }
  }
}
echo "</div>\n";

if ($issue->tid !== null) {
  echo MTrackAttachment::renderList("ticket:$issue->tid");
}

if ($id != 'new') {
  echo "<div id='readonly-tkt-description'>";
  echo MTrackWiki::format_to_html($issue->description);
  echo "</div>";
}

if ($editable && $id != 'new' && !$preview) {
  echo "<br><div id='tkt-view-button-block' class='button-float'>";
  echo "<button class='mtrack-edit-desc'>Edit</button>";
  echo " <button class='mtrack-make-comment'>Add Comment</button>";
  MTrackWatch::renderWatchUI('ticket', $issue->tid);
  echo "</div>";
}
echo "</div>"; # issue-desc

$hide_unless_preview = ($preview || $_SERVER['REQUEST_METHOD'] == 'POST') ?
  '' :
  ' style="display:none" ';

if ($editable && $id != 'new') {
  echo <<<HTML
<div id="edit-issue-desc" $hide_unless_preview >
HTML;
}

if ($editable) {

  echo " <input class='summaryedit' id='summary' name='summary' value='" .
    htmlentities($issue->summary, ENT_QUOTES, 'utf-8') .
    "' size='80'>";

  echo renderEditForm($issue);
}

if ($editable && $id != 'new') {
  echo "</div>";
}


function get_components_list($value)
{
  $res = array();
  if (strlen($value)) foreach (MTrackDB::q(
      "select name, deleted from components where compid in ($value)")
      ->fetchAll() as $row) {
    $c = $row['deleted'] ? '<del>' : '';
    $c .= htmlentities($row['name'], ENT_QUOTES, 'utf-8');
    $c .= $row['deleted'] ? '</del>' : '';
    $res[] = $c;
  }
  return join(", ", $res);
}

function get_milestones_list($value)
{
  global $ABSWEB;

  $res = array();
  if (strlen($value)) foreach (MTrackDB::q(
      "select name, completed, deleted from milestones where mid in ($value)")
      ->fetchAll() as $row) {
    if (strlen($row['completed'])) {
      $row['deleted'] = 1;
    }
    $c = "<span class='milestone";
    if ($row['deleted']) {
      $c .= " completed";
    }
    $c .= "'><a href=\"{$ABSWEB}milestone.php/" .
          urlencode($row['name']) . '">';
    $c .= htmlentities($row['name'], ENT_QUOTES, 'utf-8');
    $c .= "</a></span>";
    $res[] = $c;
  }
  return join(", ", $res);
}

function get_keywords_list($value)
{
  $res = array();
  if (strlen($value)) foreach (MTrackDB::q(
      "select keyword from keywords where kid in ($value)")
      ->fetchAll() as $row) {
    $res[] = htmlentities($row['keyword'], ENT_QUOTES, 'utf-8');
  }
  return join(", ", $res);
}

if ($id == 'new') {
  $changes = array();
} else {
  $changes = array();
  $cids = array();
  foreach (MTrackDB::q(
        'select * from changes where object = ?
        order by changedate asc',
        "ticket:$issue->tid")->fetchAll(PDO::FETCH_OBJ) as $CS) {
    $changes[$CS->cid] = $CS;
    $cids[] = $CS->cid;
  }
  $cidlist = join(',', $cids);

  $change_audit = array();
  foreach (MTrackDB::q("select * from change_audit where cid in ($cidlist)")
      ->fetchAll(PDO::FETCH_ASSOC) as $citem) {
    $change_audit[$citem['cid']][] = $citem;
  }

  /* also need to include cases where the ticket was modified as a side-effect
   * of other manipulations (such as milestones being closed and tickets being
   * re-targeted.  Such manipulations do not directly reference this ticket,
   * and so do not need to be included in the effort_audit array that is
   * populated below. */

  $tid = $issue->tid;
  foreach (MTrackDB::q(
    "select c.cid as cid, c.who as who, c.object as object, c.changedate as changedate, c.reason as reason, ca.fieldname as fieldname, ca.action as action, ca.oldvalue as oldvalue, ca.value as value from change_audit ca left join changes c on (ca.cid = c.cid) where ca.cid not in ($cidlist) and ca.fieldname like 'ticket:$tid:%'")
    ->fetchAll(PDO::FETCH_OBJ) as $CS) {
    if (!isset($changes[$CS->cid])) {
      $changes[$CS->cid] = $CS;
    }
    $change_audit[$CS->cid][] = array(
      'cid' => $CS->cid,
      'fieldname' => $CS->fieldname,
      'action' => $CS->action,
      'oldvalue' => $CS->oldvalue,
      'value' => $CS->value
      );
  }

  $effort_audit = array();
  foreach (MTrackDB::q(
      "select * from effort where cid in ($cidlist) and tid=?", $issue->tid)
      ->fetchAll(PDO::FETCH_ASSOC) as $eff) {
    $effort_audit[$eff['cid']][] = $eff;
  }
}
ksort($changes);

$idno = 0;
$events = array();

function collapse_diff($diff)
{
  static $idnum = 1;
  $id = 'diff_' . $idnum++;
  return "<br>" .
    "<button onclick='\$(&quot;#$id&quot;).toggle(); return false;'>Toggle diff</button>".
    "<div id='$id' style='display:none'>" .
    mtrack_diff($diff) . "</div>";
}

foreach ($changes as $CS) {
  $preamble = 0;
  if ($idno == 0) {
    $cid = "top";
  } else {
    $cid = "comment:$idno";
  }
  $idno++;

  $who = $CS->who;
  $timestamp = mtrack_date($CS->changedate, true);

  $html = "<div class='ticketevent'><a class='pmark' href='#$cid'>#</a> <a name='$cid'>$timestamp</a> " .
    mtrack_username($who, array('no_image' => true)) .
    "</div>\n";

  $html .= "<div class='ticketchangeinfo'>";
  $html .= mtrack_username($who, array('no_name' => true, 'size' => 48));

  if ($CS->cid == $issue->created) {
    $html .= "<b>Opened</b><br>\n";
  }

  $comments = array();

  if (is_array($change_audit[$CS->cid]))
  foreach ($change_audit[$CS->cid] as $citem) {
    list($tbl,,$field) = explode(':', $citem['fieldname'], 3);

    if ($tbl != 'ticket') {
      // can get here if we created a new keyword, for example
      //var_dump($citem);
      continue;
    }
    if ($field == '@comment') {
      $comments[] = $citem['value'];
      continue;
    }

    if ($field == '@components') {
      $citem['value'] = get_components_list($citem['value']);
    }
    if ($field == '@milestones') {
      $citem['value'] = get_milestones_list($citem['value']);
    }
    if ($field == '@keywords') {
      $citem['value'] = get_keywords_list($citem['value']);
    }
    if ($field == 'spent') {
      continue;
    }
    if ($field == 'estimated') {
      if ($citem['value'] !== null) {
        $citem['value'] += 0;
      }
      if ($citem['oldvalue'] !== null) {
        $citem['oldvalue'] += 0;
      }
    }

    if ($field[0] == '@') {
      $main = isset($pseudo_fields[$field]) ? $pseudo_fields[$field] : '';
      $field = substr($field, 1, -1);
    } else {
      $main = $issue->$field;
    }

    $f = MTrackTicket_CustomFields::getInstance()->fieldByName($field);
    if ($f) {
      $label = htmlentities($f->label, ENT_QUOTES, 'utf-8');
    } else {
      if ($field == 'attachment' && strlen($citem['oldvalue'])) {
        $label = "Attachment: $citem[oldvalue]";
      } else {
        $label = ucfirst($field);
      }
    }

    if ($citem['oldvalue'] == null) {
      /* don't bother printing out a set if this is the initial thing
       * and if the field values are currently the same */

      if ($main != $citem['value'] || $cid != 'top') {

        /* Special case for description; since it is multi-line and often
         * very large, render it as a diff against the current ticket
         * description field */
        if ($field == 'description') {
          if ($issue->description == $citem['value']) {
            $html .= "<b>Description</b>: no longer empty; see above<br>";
            continue;
          }

          $initial_lines = count(explode("\n", $issue->description));
          $diff = mtrack_diff_strings($issue->description, $citem['value']);
          $diff_add = 0;
          $diff_rem = 0;
          foreach (explode("\n", $diff) as $line) {
            if (!strlen($line)) continue;
            if ($line[0] == '-') {
              $diff_rem++;
            } else if ($line[0] == '+') {
              $diff_add++;
            }
          }
          if (abs($diff_add - $diff_rem) > $initial_lines / 2) {
            $html .= "<b>initial $label</b><br>" .
              MTrackWiki::format_to_html($citem['value']);
          } else {
            $diff = collapse_diff($diff);
            $html .= "<b>initial $label</b> (diff to above):<br>$diff\n";
          }
        } else {
          $html .= "<b>$label</b> $citem[value]<br>\n";
        }
      }
    } elseif ($citem['action'] == 'changed') {
      $lines = explode("\n", $citem['value'], 3);
      if (count($lines) >= 2) {
        $diff = mtrack_diff_strings($citem['oldvalue'], $citem['value']);
        $diff = collapse_diff($diff);
        $html .= "<b>$label</b> $citem[action]\n$diff\n";
      } else {
        $html .= "<b>$label</b> $citem[action] to $citem[value]<br>\n";
      }
    } else {
      $html .= "<b>$label</b> $citem[action]<br>\n";
    }
  }

  if (isset($effort_audit[$CS->cid]) && is_array($effort_audit[$CS->cid])) {
    foreach ($effort_audit[$CS->cid] as $eff) {
      $exp = (float)$eff['expended'];
      if ($eff['expended'] != 0) {
        $html .= "<b>spent</b> $exp hours<br>\n";
        $preamble++;
      }
    }
  }

  if (count($comments)) {
    if ($preamble) {
      $html .= "<br>\n";
      $preamble = 0;
    }

    foreach ($comments as $text) {
      $html .= MTrackWiki::format_to_html($text);
    }
  }

  $html .= "</div>"; # ticketchangeinfo

  $events[] = $html;
}
if (count($events) > 80 && !isset($_GET['all'])) {
  $num_hidden = count($events) - 20;
  $turl = $ABSWEB . 'ticket.php/' . $issue->nsident . '?all=1';
  echo <<<HTML
<br>
<div id='show-overflow' class='ui-state-highlight ui-corner-all'>
    <span class='ui-icon ui-icon-info'></span>
    There are $num_hidden older comments that are not shown.
    <a class='button' href='$turl'>Show hidden comments</button>
</div>
HTML;
} else if (count($events) > 20 && !isset($_GET['all'])) {
  $num_hidden = count($events) - 20;
  echo <<<HTML
<br>
<div id='show-overflow' class='ui-state-highlight ui-corner-all'>
    <span class='ui-icon ui-icon-info'></span>
    There are $num_hidden older comments that are not shown.
    <button id='button-show-overflow'>Show hidden comments</button>
</div>
<div id='ticketcommentsoverflow' style='display:none'>
HTML;
  while (count($events) > 20) {
    echo array_shift($events);
  }
  echo <<<HTML
</div>
<script type='text/javascript'>
$('#button-show-overflow').click(function() {
  $('#show-overflow').hide('blind');
  $('#ticketcommentsoverflow').show('clip');
  return false;
});
</script>
HTML;

}
while (count($events)) {
  echo array_shift($events);
}


if ($id != 'new') {
?>
<br style="clear:both">
<button id="bottom-comment-button" class='mtrack-make-comment'>Add Comment</button>
<?php
}

?>
</form>
<div id="confirmCancelDialog" style="display:none"
    title="Are you sure?">
  You've entered information into the form.
  If you cancel, you will not be able to get it back.
</div>
<div id="noCommentDialog" style="display:none"
    title="Please enter comment">
  It seems you have not made any changes to the ticket,
  and haven't entered any comments.
</div>
<div id="noSummaryDialog" style="display:none"
    title="Please enter summary">
  It seems you haven't entered a summary for the ticket.
</div>
<script type='text/javascript'>
var formChanged = <?php echo $preview ? 'true' : 'false'; ?>;

var viewblock;
var view_off;
var view_pos;
var editblock;
var edit_off;
var edit_pos;

function show_edit_form()
{
  viewblock.css('position', view_pos);
  viewblock.css('top', view_off.top);

  $("#issue-desc").hide();
  $("#edit-issue-desc").show();
  $("#edit-comment-parent").append($("#comment-area"));
  $("#comment-submit-buttons").hide();
  $("#comment-area").show();
  $(".mtrack-make-comment").hide();
  $("#description").focus();

  editblock.show();
  edit_off = editblock.offset();
  edit_pos = editblock.css('position');
  viewblock.hide();
  compute_floats();
}

function compute_floats()
{
  if ($(viewblock).is(':visible')) {
    if ($(this).scrollTop() > view_off.top) {
      viewblock.css('position', 'fixed');
      viewblock.css('top', '0px');
      viewblock.addClass('button-float-floating');
    } else {
      viewblock.css('position', view_pos);
      viewblock.css('top', view_off.top);
      viewblock.removeClass('button-float-floating');
    }
  }
  if ($(editblock).is(':visible')) {
    if ($(this).scrollTop() > edit_off.top) {
      editblock.css('position', 'fixed');
      editblock.css('top', '0px');
      editblock.addClass('button-float-floating');
    } else if ($(this).scrollTop() < edit_off.top + editblock.height() - $(this).height()) {
      editblock.css('position', 'fixed');
      editblock.css('top', $(this).height() - editblock.outerHeight());
      editblock.addClass('button-float-floating');
    } else {
      editblock.css('position', edit_pos);
      editblock.css('top', edit_off.top);
      editblock.removeClass('button-float-floating');
    }
  }
}

$(document).ready(function() {
  viewblock = $('#tkt-view-button-block');
  editblock = $('#tkt-edit-button-block');
  view_off = viewblock.offset();
  view_pos = viewblock.css('position');

$(window).scroll(function () {
  compute_floats();
});


$(".mtrack-edit-desc").click(
  function() {
    show_edit_form();
    return false;
  }
);
$("input[type=radio]").click(
  function() {
    if (this.value == 'fixed') {
      $("#changelog-container").show();
    } else {
      $("#changelog-container").hide();
    }
  }
);

$(":input").change(function() {
  formChanged = true;
});
$("textarea").keyup(function() {
  // This is here because IE doesn't seem to reliably trigger the
  // change event with textareas
  formChanged = true;
});
$("#confirmCancelDialog").dialog({
  autoOpen: false,
  bgiframe: true,
  resizable: false,
  modal: true,
  buttons: {
    'Discard': function() {
      $(this).dialog('close');
      cancel_form_changes();
    },
    'Keep': function() {
      $(this).dialog('close');
    }
  }
});
$("#noCommentDialog").dialog({
  autoOpen: false,
  bgiframe: true,
  resizable: false,
  modal: true,
  buttons: {
    'OK': function() {
      $(this).dialog('close');
      $("#comment").focus();
    }
  }
});
$("#noSummaryDialog").dialog({
  autoOpen: false,
  bgiframe: true,
  resizable: false,
  modal: true,
  buttons: {
    'OK': function() {
      $(this).dialog('close');
      $("#summary").focus();
    }
  }
});

function cancel_form_changes()
{
<?php
  if ($preview) {
?>
    document.location.href = document.location.href;
    return false;
<?php
  }
?>
  editblock.css('position', edit_pos);
  editblock.css('top', edit_off.top);
  editblock.hide();
  viewblock.show();

  $("#tktedit").each(function(){
    // reset form
    this.reset();
  });
  // notify asm select of change
  $("select[multiple]").change();
  $("#edit-issue-desc").hide();

  $("#original-comment-parent").append($("#comment-area"));
  $("#comment-submit-buttons").show();

  <?php if ($id != 'new') { ?>
  $("#comment-area").hide();
  $(".mtrack-make-comment").show();
  <?php } ?>
  $("#issue-desc").show();
  formChanged = false;
  compute_floats();

  return false;
}

$(".mtrack-edit-cancel").click(
  function() {
    if (formChanged) {
      $("#confirmCancelDialog").dialog('open');
      return false;
    } else {
      return cancel_form_changes();
    }
  }
);
$(".mtrack-make-comment").click(
  function() {
    show_edit_form();
    $("#comment").focus();
    return false;
  }
);

$(".mtrack-button-submit").click(function(){

    var id = '<?php echo $id ?>';

    if ($("#summary").val() == '') {

        $("#summary").addClass('error');
        $("#noSummaryDialog").dialog('open');
        return false;

    } else {

        if (formChanged == false && $("#comment").val() == '') {
            $("#comment").addClass('error');
            $("#noCommentDialog").dialog('open');
            return false;
        }

    }

});

$("#comment").keydown(function(){
    $("#comment").removeClass('error');
});

$("#summary").keydown(function(){
    $("#summary").removeClass('error');
});

<?php
if ($issue->tid == null) {
?>
$("#summary").focus();
<?php
}
?>
});
</script>
<?php

mtrack_foot();

function renderEditForm($issue, $params = array())
{
  global $id;
  global $ABSWEB;
  global $FIELDSET;

  if (!isset($params['formname'])) {
    $params['formname'] = 'tktedit';
  } else if (!ctype_alpha($params['formname'])) {
    throw new Exception("invalid form name");
  }

  /* compute allowed field values */
  $allowed = array();

  $C = new MTrackClassification;
  $allowed['classification'] = $C->enumerate();

  $P = new MTrackPriority;
  $allowed['priority'] = $P->enumerate();

  $S = new MTrackSeverity;
  $allowed['severity'] = $S->enumerate();

  $R = new MTrackResolution;
  $allowed['resolution'] = $R->enumerate();

  $r = array();
  foreach (MTrackDB::q('select c.compid, c.name, p.name from components c left join components_by_project cbp on (c.compid = cbp.compid) left join projects p on (cbp.projid = p.projid) where deleted <> 1 order by c.name')
      ->fetchAll(PDO::FETCH_NUM) as $row) {
    if (strlen($row[2])) {
      $row[1] .= " ($row[2])";
    }
    $r[$row[0]] = $row[1];
  }
  $allowed['component'] = $r;

  $r = array();
  foreach (MTrackDB::q(
        'select mid, name from milestones where deleted <> 1
        and completed is null order by (case when duedate is null then 1 else 0 end), duedate, name'
        )->fetchAll(PDO::FETCH_NUM) as $row) {
    $r[$row[0]] = $row[1];
  }
  foreach ($issue->getMilestones() as $mid => $name) {
    if (!isset($r[$mid])) {
      $r[$mid] = $name;
    }
  }
  $allowed['milestone'] = $r;

  // FIXME: workflow should be able to influence this list of users
  $users = array();
  $inactiveusers = array();
  foreach (MTrackDB::q(
      'select userid, fullname, active from userinfo order by userid'
      )->fetchAll() as $row) {
    if (strlen($row[1])) {
      $disp = "$row[0] - $row[1]";
    } else {
      $disp = $row[0];
    }
    if ($row[2]) {
      $users[$row[0]] = $disp;
    } else {
      $inactiveusers[$row[0]] = $disp;
    }
  }
  $users[''] = 'nobody';
  // allow for inactive users to show up if they're currently responsible
  if (!isset($users[$issue->owner])) {
    if (!isset($inactiveusers[$issue->owner])) {
      $users[$issue->owner] = $issue->owner . ' (inactive)';
    } else {
      $users[$issue->owner] = $inactiveusers[$issue->owner] . ' (inactive)';
    }
  }
  // last ditch to have it show the right info
  if (!isset($users[$issue->owner])) {
    $users[$issue->owner] = $issue->owner;
  }
  $allowed['owner'] = $users;

  $html = "<input type='hidden' name='tid' value='" .
    htmlentities($issue->tid === null ? 'new' : $issue->tid) . "'>\n";

  /* render the form */
  $col = 0;

  foreach ($FIELDSET as $fsid => $fieldset) {
    if (is_string($fsid)) {
      $html .= "<fieldset id='$fsid'><legend>$fsid</legend>\n";
    }

    $html .= "<table class='fields'>";
    $col = 0;
    foreach ($fieldset as $propname => $info) {
      if (isset($info['readonly'])) {
        continue;
      }
      if (empty($info['ownrow'])) {
        $info['ownrow'] = false;
      }
      $value = null;
      switch ($propname) {
        case 'keywords':
          $value = join(' ', $issue->getKeywords());
          break;
        case 'milestone':
          $value = $issue->getMilestones();
          break;
        case 'component':
          $value = $issue->getComponents();
          break;
        case 'owner':
          $value = $issue->owner;
          if (!strlen($value)) {
            $value = '';
          }
          break;
        default:
          if (isset($issue->$propname)) {
            $value = $issue->$propname;
          }
      }
      if (isset($info['condition']) && !$info['condition']) {
        continue;
      }

      if (($info['ownrow'] && $col) || $col == 2) {
        $html .= "</tr>\n";
        $col = 0;
      }
      if ($col == 0) {
        $html .= "<tr valign='top'>";
      }
      $col++;
      if ($info['ownrow']) {
        $html .= "<td colspan='4'>";
      } else if ($info['type'] == 'multiselect') {
        $html .= "<td colspan='2'>";
      } else {
        $html .= "<td>";
      }

      if ($value === null && isset($info['default'])) {
        $value = $info['default'];
      }

      if ($info['type'] != 'multiselect') {
        $html .= "<label for='$propname'>".
          htmlentities($info['label'], ENT_QUOTES, 'utf-8').
          ":</label>";
      }

      if ($info['ownrow']) {
        $html .= "<br>\n";
      } else if ($info['type'] != 'multiselect') {
        $html .= "</td><td class='col$col'>";
      }

      switch ($info['type']) {
        case 'text':
          $size = empty($info['size']) ? "" : "size='$info[size]' ";
          $html .= "<input id='$propname' name='$propname' ".
            $size .
            "value='".htmlentities($value, ENT_QUOTES, 'utf-8').
            "'>";
          break;

        case 'multi':
          $html .= "<textarea id='$propname' name='$propname' ".
            "rows='$info[rows]' cols='$info[cols]' class='code'>".
            htmlentities($value, ENT_QUOTES, 'utf-8').
            "</textarea>\n";
          break;

        case 'wiki':
          $srows = $info['rows'] + 1;
          $html .= "<textarea id='$propname' name='$propname' ".
            "style='height: {$srows}em' " .
            "rows='$info[rows]' cols='$info[cols]' class='code wiki'>".
            htmlentities($value, ENT_QUOTES, 'utf-8').
            "</textarea>\n";
          break;

        case 'shortwiki':
          $html .= "<textarea id='$propname' name='$propname' ".
            "rows='$info[rows]' cols='$info[cols]' class='code wiki shortwiki'>"
            . htmlentities($value, ENT_QUOTES, 'utf-8').
            "</textarea>\n";
          break;

        case 'select':
          if (isset($allowed[$propname])) {
            $html .= mtrack_select_box($propname,
                $allowed[$propname], $value);
          } else {
            $html .= mtrack_select_box($propname,
                $info['options'], $value);
          }
          break;

        case 'multiselect':
          if (isset($allowed[$propname])) {
            $html .= mtrack_multi_select_box($propname,
                $info['label'] . " (select to add)",
                $allowed[$propname], $value);
          } else {
            $html .= mtrack_multi_select_box($propname,
                $info['label'] . " (select to add)",
                $info['options'], $value);
          }
          break;
      }

      $html .= "</td>\n";

      if ($info['ownrow']) {
        $html .= "</tr>\n";
        $col = 0;
      }
    }
    $html .= "</table>\n";

    if (is_string($fsid)) {
      $html .= "</fieldset>\n";
    }
  }

  $html .= "<fieldset id='action-container'><legend>Action</legend>\n";

  // FIXME: workflow inspired actions listed here
  if (!isset($_POST['action'])) {
    $_POST['action'] = 'none';
  }
  if ($id != 'new') {
    $html .= mtrack_radio('action', 'none', $_POST['action']);
    $status = $issue->status == 'closed' ? $issue->resolution : $issue->status;
    $html .= " <label for='none'>Leave status as $status</label><br>\n";

    if ($issue->status != 'closed') {
      $html .= mtrack_radio('action', 'accept', $_POST['action']);
      $html .= " <label for='accept'>Accept ticket</label><br>\n";

      $ST = new MTrackTicketState;
      $ST = $ST->enumerate();
      unset($ST['closed']);
      unset($ST[$status]);
      if (count($ST)) {
        $html .= mtrack_radio('action', 'changestatus', $_POST['action']);
        $html .= " <label for='changestatus'>Change status to:</label>";
        $html .= mtrack_select_box('status', $ST, $issue->status);
        $html .= "<br>\n";
      }

      $html .= mtrack_radio('action', 'fixed', $_POST['action']);
      $html .= " <label for='fixed'>Resolve as fixed</label><br>\n";

      $R = new MTrackResolution;
      $resolutions = $R->enumerate();
      unset($resolutions['fixed']);
      $html .= mtrack_radio('action', 'resolve', $_POST['action']);
      $html .= " <label for='resolve'>Resolve as:</label>";
      $html .= mtrack_select_box('resolution', $resolutions);
      $html .= "<br>\n";

    } else {
      $html .= mtrack_radio('action', 'reopen', $_POST['action']);
      $html .= " <label for='reopen'>Reopen ticket</label><br>\n";
    }
    $html .= "<br>\n";
  }

  $spent = empty($_POST['spent']) ? '' : htmlentities($_POST['spent'], ENT_QUOTES, 'utf-8');
  if (!strlen($spent)) {
    $spent = '0';
  }
  $html .= "<label for='spent'>Log time spent (hours)</label> ";
  $html .= "<input type='text' name='spent' value='$spent'><br>\n";

  if ($id != 'new') {
    $html .= MTrackAttachment::renderDeleteList("ticket:$issue->tid");
    $html .= <<<HTML
  <br>
  <label for='attachments[]'>Select file(s) to be attached</label>
  <input type='file' class='multi' name='attachments[]'>
HTML;
  }
  $html .= "</fieldset>";
  $html .= "<fieldset id='comment-container'><legend>Comment</legend>\n";

  $html .= <<<HTML
<textarea name='comment' id="comment"
  class="wiki shortwiki" rows="5" cols="78">
HTML;
  if (isset($_POST['comment'])) {
    $html .= htmlentities($_POST['comment'], ENT_QUOTES, 'utf-8');
  }
  $html .= "</textarea>";

  $html .= "</fieldset>";
  $html .= MTrackCaptcha::emit('ticket');

  $html .= <<<HTML
    <div id='tkt-edit-button-block' class='button-float'>
    <button class='mtrack-button-submit' type="submit" name="preview">Preview</button>
    <button class='mtrack-button-submit' type="submit" name="apply">Submit changes</button>
    <button class='mtrack-edit-cancel' type="submit" name="cancel">Cancel</button>
HTML;

  if ($id != 'new') {
    $html .= <<<HTML
<button class='mtrack-make-comment'>Add Comment</button>
HTML;
  }

  $html .= "</div>";

  return $html;
}
