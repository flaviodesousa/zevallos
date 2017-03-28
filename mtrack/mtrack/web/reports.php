<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

mtrack_head("Reports");
?>
<h1>Available Reports</h1>

<p>
  The reports below are constructed using SQL.  You may also
  use the <a href="<?php echo $ABSWEB ?>query.php">Custom Query</a>
  page to create a report on the fly.
</p>

<table>
<tr>
  <th>Report</th>
  <th>Title</th>
</tr>
<?php
foreach (MTrackDB::q("select rid, summary from reports order by rid"
    )->fetchAll(PDO::FETCH_ASSOC) as $row)
{
  $url = "${ABSWEB}report.php/$row[rid]";
  $t = "<a href='$url'>{" . $row['rid'] . "}</a>";
  $s = htmlentities($row['summary'], ENT_COMPAT, 'utf-8');
  $s = "<a href='$url'>$s</a>";

  echo <<<HTML
<tr><td>$t</td><td>$s</td></tr>
HTML;
}
?>
</table>
<?php
if (MTrackACL::hasAllRights('Reports', 'create')) {
?>
<form action="report.php" method="get">
<button type="submit" name="edit">Create Report</button>
</form>
<?php
}

mtrack_foot();

