<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

$q = $_GET['q'];

if (preg_match('/^#([a-zA-Z0-9]+)$/', $q, $M)) {
  /* ticket */
  header("Location: {$ABSWEB}ticket.php/$M[1]");
  exit;
}
if (preg_match('/^r([a-zA-Z]*\d+)$/', $q, $M)) {
  /* changeset */
  $url = mtrack_changeset_url($M[1]);
  header("Location: $url");
  exit;
}
if (preg_match('/^\[([a-zA-Z]*\d+)\]$/', $q, $M)) {
  /* changeset */
  $url = mtrack_changeset_url($M[1]);
  header("Location: $url");
  exit;
}
if (preg_match('/^\{(\d+)\}$/', $q, $M)) {
  /* report */
  header("Location: {$ABSWEB}report.php/$M[1]");
  exit;
}
mtrack_head("Search results for \"$q\"");

?>
<h1>Search results</h1>

<form action="<?php echo $ABSWEB; ?>search.php">
  <input type="text" name="q"
    size="50"
    value="<?php echo htmlentities($q, ENT_QUOTES, 'utf-8'); ?>">
  <button type="submit">Search</button>
  Read more about <a href="<?php echo $ABSWEB ; ?>help.php/Searching">Searching</a>.
  <button id='togglesummary' type='button'>Show Fields</button>
<script>
$(document).ready(function () {
  $('#togglesummary').click(function () {
    $('#fieldsummary').toggle();
  });
});
</script>
<div id='fieldsummary' style='display:none'>
  <p>The following fields are available for targeted searching:</p>
  <table>
    <tr>
      <th>Item</th>
      <th>Field</th>
      <th>Description</th>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>summary</td>
      <td>The one-line ticket summary</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>description</td>
      <td>The ticket description</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>changelog</td>
      <td>The changelog field</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>keyword</td>
      <td>The keyword field</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>date</td>
      <td>The last-changed date</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>who</td>
      <td>who last changed the ticket</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>creator</td>
      <td>who opened the ticket</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>created</td>
      <td>The date that the ticket was created</td>
    </tr>
    <tr>
      <td>Ticket</td>
      <td>owner</td>
      <td>who is responsible for the ticket</td>
    </tr>
    <tr>
      <td>Comment</td>
      <td>description</td>
      <td>The comment text</td>
    </tr>
    <tr>
      <td>Comment</td>
      <td>date</td>
      <td>Date the comment was made</td>
    </tr>
    <tr>
      <td>Comment</td>
      <td>who</td>
      <td>who made that comment</td>
    </tr>
    <tr>
      <td>Wiki</td>
      <td>wiki</td>
      <td>The content from the wiki page</td>
    </tr>
    <tr>
      <td>Wiki</td>
      <td>who</td>
      <td>Who last changed that wiki page</td>
    </tr>
    <tr>
      <td>Wiki</td>
      <td>date</td>
      <td>Date the wiki page was last changed</td>
    </tr>
<?php
$CF = MTrackTicket_CustomFields::getInstance();
foreach ($CF->fields as $f) {
  echo "<tr><td>Ticket</td><td>$f->name</td><td>",
    htmlentities($f->label, ENT_QUOTES, 'utf-8'),
    "</td></tr>\n";
}
?>
  </table>

</div>
</form>

<?php

if (strlen($q)) {
  $start = microtime(true);
  $hits = MTrackSearchDB::search($q);
  $end = microtime(true);
  $elapsed = sprintf("%.2f seconds", $end - $start);
} else {
  $hits = array();
  $elapsed = '';
}
?>


<p>Searching for <i>

<?php
echo htmlentities($q, ENT_QUOTES, 'utf-8'), "</i>:</p><br>\n";

$hits_by_object = array();
$objects = array();
/* aggregate results by canonical object; since
 * we index comments separately from the top level
 * item, we need to adjust for that here */
foreach ($hits as $hit) {
  /* get canonical object */
  list($item, $id) = explode(':', $hit->objectid, 3);

  $object = "$item:$id";
  if (isset($hits_by_object[$object])) {
    if ($hit->score > $hits_by_object[$object]) {
      $hits_by_object[$object] = $hit->score;
      $objects[$object] = $hit;
    }
  } else {
    $hits_by_object[$object] = $hit->score;
    $objects[$object] = $hit;
  }
}
arsort($hits_by_object);
?>
<table class='searchresults'>
<?php



$denied = 0;
foreach ($hits_by_object as $object => $score) {
  list($item, $id) = explode(':', $object, 2);
  $obj = $objects[$object];
  $score = (int)($score * 100);

  $html = "<tr><td valign='right'>$score%</td><td>";

  switch ($item) {
    case 'ticket':
      $tkt = MTrackIssue::loadByNSIdent($id);
      if ($tkt === null) {
        $tkt = MTrackIssue::loadById($id);
      }
      $aclid = "ticket:" . $tkt->tid;
      $html .= mtrack_ticket($tkt);
      if ($tkt->nsident) {
        $url = "{$ABSWEB}ticket.php/$tkt->nsident";
      } else {
        $url = "{$ABSWEB}ticket.php/$id";
      }
      $html .= " <a href='$url'>";
      $html .= htmlentities($tkt->summary, ENT_QUOTES, 'utf-8');
      $html .= "</a>";
      $html .= $obj->getExcerpt($tkt->description);

      break;
    case 'wiki':
      $wiki = MTrackWikiItem::loadByPageName($id);
      $aclid = "wiki:$id";
      $url = "{$ABSWEB}wiki.php/$id";
      $html .= "<a href='$url'>".
        htmlentities($id, ENT_QUOTES, 'utf-8').
        "</a>";
      $html .= $obj->getExcerpt($wiki->content);
      break;
    default:
      $aclid = $object;
      $html .= $object;
  }

  if (!MTrackACL::hasAnyRights($aclid, 'read')) {
    $denied++;
    continue;
  }

  $html .= "</td></tr>\n";
  echo $html;
}
echo "</table>\n";

if (!count($hits_by_object)) {
  echo "<em>No matches</em>";
} else {
  echo "<em>" . count($hits_by_object) . " results in $elapsed</em>\n";
}
if ($denied) {
  echo "<br>Denied access to $denied items<br>\n";
}

mtrack_foot();
