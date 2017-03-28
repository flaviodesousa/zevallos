<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';

mtrack_head("Custom Query");

echo "<h1>Custom Query</h1>\n";

/* This logic matches up to equivalent logic in the macro_RunReport
 * function in inc/report */

$params = array();

if (strlen($_SERVER['QUERY_STRING'])) {
  $qs = $_SERVER['QUERY_STRING'];
} else {
  $qs = mtrack_get_pathinfo();
}

list ($params, $mparams) = MTrackReport::parseQuery($qs);

echo "<form action='{$ABSWEB}query.php' method='get' id='qform' onsubmit='return false;'>";
echo "<table id='qtable'></table></form>";

$params = json_encode($params);

$milestones = json_encode(array_values(MTrackMilestone::enumMilestones(true)));
echo <<<HTML
<form id='customqryaddfilter'>
Add Filter: <select id='addfilt'>
<option value="">- Select to add a filter</option>
HTML;

$fields = array('cc', 'component', 'milestone', 'status', 'owner',
  'type', 'summary', 'ticket', 'priority', 'keyword');

asort($fields);
$labels = array();

foreach ($fields as $field) {
  echo "<option value='$field'>" . ucfirst($field) . "</option>\n";
  $labels[$field] = ucfirst($field);
}
$C = MTrackTicket_CustomFields::getInstance();
$custom_fields = new stdclass;
foreach ($C->fields as $f) {
  echo "<option value='$f->name'>" .
    htmlentities($f->label, ENT_QUOTES, 'utf-8') . "</option>\n";
  $labels[$f->name] = $f->label;
  if ($f->type == 'select' || $f->type == 'multiselect') {
    $d = $f->ticketData();
    $custom_fields->{$f->name} = array_values($d['options']);
  }
}
echo <<<HTML
</select>
<br>
<div id='colselector'>
<h3><a href='#'>Choose Columns (drag to re-order)</a></h3>
<div style="display:none">
<ul id='columns'>
HTML;

$labels = json_encode($labels);
$custom_fields = json_encode($custom_fields);
$c = new MTrackClassification;
$classifications = json_encode(array_values($c->enumerate()));
$c = new MTrackPriority;
$priorities = json_encode(array_values($c->enumerate()));
/* Allow selection of columns */
function add_col($name, $label) {
  global $mparams;
  $checked = in_array($name, $mparams['col']) ? ' checked="yes" ' : '';
  $label = htmlentities($label, ENT_QUOTES, 'utf-8');
  echo "<li class='ui-state-default'><input type='checkbox' name='col_$name' mtrackcol='$name' class='qrycol' $checked> ";
  echo "<label for='col_$name'>$label</label></li> ";
}
$all_cols = array();

// Add in the selected columns in order first
foreach ($mparams['col'] as $col) {
  $field = $C->fieldByName($col);
  if ($field) {
    $all_cols[$field->name] = $field->label;
  } else {
    $all_cols[$col] = ucfirst($col);
  }
}
// Add in other possible fields
foreach ($fields as $field) {
  $all_cols[$field] = ucfirst($field);
}
$possible_fields = array(
  'severity', 'remaining'
);
foreach ($possible_fields as $name) {
  $all_cols[$name] = ucfirst($name);
}
foreach ($C->fields as $f) {
  $all_cols[$f->name] = $f->label;
}

foreach ($all_cols as $name => $label) {
  add_col($name, $label);
}

echo <<<HTML
</ul>
</div>
</div>
</form>
<button id='updfilt'>Update</button><br>
<script language='javascript' type='text/javascript'>
var initq = $params;
var milestones = $milestones;
var classifications = $classifications;
var priorities = $priorities;
var next_field_id = 1;
var adding_field = false;
var custom_fields = $custom_fields;
var field_labels = $labels;

function mtrack_add_sel(sel, a, b)
{
  sel.options[sel.options.length] = new Option(a, b);
}

// given a field name, operator and value, create a new entry in the form
function mtrack_add_field(name, op, value)
{
  var qtable = document.getElementById('qtable');

  // <tr><td>X</td><td>name</td><td>op select</td><td>value</td></tr>
  var tr = document.createElement('tr');
  var xcell = document.createElement('td');
  var but = document.createElement('button');
  but.innerHTML = "X";
  xcell.appendChild(but);
  xcell.onclick = function() {
    qtable.removeChild(tr);
    return false;
  };
  tr.appendChild(xcell);

  var ncell = document.createElement('td');
  ncell.innerHTML = field_labels[name];
  ncell.align = "right";
  var ntype = document.createElement('input');
  ntype.type = 'hidden';
  ntype.id = "optyp_" + next_field_id;
  ntype.name = ntype.id;
  ntype.value = name;
  ncell.appendChild(ntype);
  tr.appendChild(ncell);

  var opcell = document.createElement('td');
  // create the operator map
  var sel = document.createElement('select');
  sel.id = "opsel_" + next_field_id;
  sel.name = sel.id;
  mtrack_add_sel(sel, "is", "=");
  mtrack_add_sel(sel, "is not", "!=");

  if (name != 'milestone' && name != 'status' && name != 'type') {
    mtrack_add_sel(sel, "contains", "~=");
    mtrack_add_sel(sel, "does not contain", "!~=");
    mtrack_add_sel(sel, "starts with", "^=");
    mtrack_add_sel(sel, "does not start with", "!^=");
    mtrack_add_sel(sel, "ends with", "\$=");
    mtrack_add_sel(sel, "does not end with", "!\$=");
  }
  var i;
  for (i = 0; i < sel.length; i++) {
    if (sel.options[i].value == op) {
      sel.selectedIndex = i;
      break;
    }
  }

  opcell.appendChild(sel);
  tr.appendChild(opcell);

  var vid = "opval_" + next_field_id;

  var vcell = document.createElement('td');
  var vele = null;

  if (name == 'milestone') {
    vele = document.createElement('select');
    for (i in milestones) {
      mtrack_add_sel(vele, milestones[i], milestones[i]);
      if (milestones[i] == value) {
        vele.selectedIndex = vele.length - 1;
      }
    }
  } else if (name == 'status') {
    vele = document.createElement('select');
    mtrack_add_sel(vele, 'new', 'new');
    mtrack_add_sel(vele, 'open', 'open');
    mtrack_add_sel(vele, 'closed', 'closed');
    mtrack_add_sel(vele, 'assigned', 'assigned');
    switch (value) {
      case 'new': vele.selectedIndex = 0; break;
      case 'open': vele.selectedIndex = 1; break;
      case 'closed': vele.selectedIndex = 2; break;
      case 'assigned': vele.selectedIndex = 3; break;
    }
  } else if (name == 'type') {
    vele = document.createElement('select');
    for (i in classifications) {
      mtrack_add_sel(vele, classifications[i], classifications[i]);
      if (classifications[i] == value) {
        vele.selectedIndex = vele.length - 1;
      }
    }
  } else if (name == 'priority') {
    vele = document.createElement('select');
    for (i in priorities) {
      mtrack_add_sel(vele, priorities[i], priorities[i]);
      if (priorities[i] == value) {
        vele.selectedIndex = vele.length - 1;
      }
    }
  } else if (custom_fields[name]) {
    vele = document.createElement('select');
    var opts = custom_fields[name];
    for (i in opts) {
      mtrack_add_sel(vele, opts[i], opts[i]);
      if (opts[i] == value) {
        vele.selectedIndex = vele.length - 1;
      }
    }
  }

  if (vele == null) {
    // default to a text entry field
    vele = document.createElement('input');
    vele.type = 'text';
    vele.value = value;
  }
  vele.name = vid;
  vele.id = vid;
  vcell.appendChild(vele);
  tr.appendChild(vcell);

  qtable.appendChild(tr);
  \$(vele).bind('keypress', function (e) {
    switch (e.keyCode) {
      case $.ui.keyCode.ENTER:
      case $.ui.keyCode.BACKSPACE:
        return false;
    }
  });

  next_field_id++;
}

$(document).ready(function (){
  $('#colselector').accordion({
    collapsible: true,
    active: false
  });
  $('#columns').sortable();

  // decode the parameters and build out the form
  var prop;
  for (prop in initq) {
    var d = initq[prop];
    var op = d[0];
    var values = d[1];
    var val;
    for (val in values) {
      mtrack_add_field(prop, op, values[val]);
    }
  }

  $('#addfilt').change(function () {
    if (!adding_field) {
      adding_field = true;
      mtrack_add_field(this.options[this.selectedIndex].value, null, null);
      this.selectedIndex = 0;
      adding_field = false;
    }
  });

  $('#updfilt').click(function (){
    var filt = [];
    // Iterate the form elements and build up the query string
    var i;
    var f = document.getElementById('qform');
    for (i = 0; i < f.length; i++) {
      var ele = f.elements[i];
      if (ele.name.match(/^op(sel|val|typ)_/)) {
        var fid = ele.name.substr(6);
        var oper = document.getElementById('opsel_' + fid);
        var val = document.getElementById('opval_' + fid);
        var type = document.getElementById('optyp_' + fid);
        filt[fid] = [type.value, oper.value, val.value];
      }
    }
    var qs = "";
    for (i in filt) {
      f = filt[i];
      if (qs.length) {
        qs += "&";
      }
      qs += f[0] + f[1] + f[2];
    }
    // And the columns
    var col = [];
    $('input.qrycol:checked').each(function () {
      col[col.length] = $(this).attr('mtrackcol');
    });
    qs = qs + "&col=" + col.join('|');
    document.location.href = "{$ABSWEB}query.php?" + qs;
    return false;
  });
});
</script>
HTML;

if (strlen(trim($qs))) {
  echo MTrackReport::macro_TicketQuery($qs);
}

mtrack_foot();

