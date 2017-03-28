<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

class MTrackReport {
  public $rid = null;
  public $summary = null;
  public $description = null;
  public $query = null;
  public $changed = null;

  static function loadByID($id) {
    return new MTrackReport($id);
  }

  static function loadBySummary($summary) {
    list($row) = MTrackDB::q('select rid from reports where summary = ?',
      $summary)->fetchAll();
    if (isset($row[0])) {
      return new MTrackReport($row[0]);
    }
    return null;
  }

  function __construct($id = null) {
    $this->rid = $id;
    if ($this->rid) {
      $q = MTrackDB::q('select * from reports where rid = ?', $this->rid);
      foreach ($q->fetchAll() as $row) {
        $this->summary = $row['summary'];
        $this->description = $row['description'];
        $this->query = $row['query'];
        $this->changed = (int)$row['changed'];
        return;
      }
      throw new Exception("report $id not found");
    }
  }

  function save(MTrackChangeset $changeset) {
    if ($this->rid) {
      
      /* figure what we actually changed */
      $q = MTrackDB::q('select * from reports where rid = ?', $this->rid);
      list($row) = $q->fetchAll();
    
      $changeset->add("report:" . $this->rid . ":summary",
        $row['summary'], $this->summary);
      $changeset->add("report:" . $this->rid . ":description",
        $row['description'], $this->description);
      $changeset->add("report:" . $this->rid . ":query",
        $row['query'], $this->query);

      $q = MTrackDB::q('update reports set summary = ?, description = ?, query = ?, changed = ? where rid = ?',
            $this->summary, $this->description, $this->query,
            $changeset->cid, $this->rid);
    } else {
      $q = MTrackDB::q('insert into reports (summary, description, query, changed) values (?, ?, ?, ?)',
            $this->summary, $this->description, $this->query,
            $changeset->cid);
      $this->rid = MTrackDB::lastInsertId('reports', 'rid');
      $changeset->add("report:" . $this->rid . ":summary",
        null, $this->summary);
      $changeset->add("report:" . $this->rid . ":description",
        null, $this->description);
      $changeset->add("report:" . $this->rid . ":query",
        null, $this->query);

    }
  }
  static function renderReport($repstring, $passed_params = null,
      $format = 'html') {
    global $ABSWEB;
    static $jquery_init = false;

    $db = MTrackDB::get();

    /* process the report string; any $PARAM in there is recognized
     * as a parameter and the query munged accordingly to pass in the data */

    $params = array();
    try {
      $n = preg_match_all("/\\$([A-Z]+)/m", $repstring, $matches);
      for ($i = 1; $i <= $n; $i++) {
        /* default the parameter to no value */
        $params[$matches[$i][0]] = '';
        /* replace with query placeholder */
        $repstring = str_replace('$' . $matches[$i][0], ':' . $matches[$i][0],
          $repstring);
      }

      /* now to summon parameters */
      if (isset($params['USER'])) {
        $params['USER'] = MTrackAuth::whoami();
      }
      foreach ($params as $p => $v) {
        if (isset($_GET[$p])) {
          $params[$p] = $_GET[$p];
        }
      }
      if (is_array($passed_params)) {
        foreach ($params as $p => $v) {
          if (isset($passed_params[$p])) {
            $params[$p] = $passed_params[$p];
          }
        }
      }

      $q = $db->prepare($repstring);
      $q->execute($params);

      $results = $q->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      return "<div class='error'>" . $e->getMessage() . "<br>" . 
        htmlentities($repstring, ENT_QUOTES, 'utf-8') . "</div>";
    }

    $out = '';

    if (count($results) == 0) {
      return "No records matched";
    }

    /* figure out the table headings */
    $captions = array();
    $span = array();
    $rules = array();
    foreach ($results[0] as $name => $value) {
      if (preg_match("/^__.*__$/", $name)) {
        if ($format == 'html') {
          /* special meaning, not a column */
          continue;
        }
      }
      $captions[$name] = preg_replace("/^_(.*)_$/", "\\1", $name);
    }
    /* for spanning purposes, calculate the longest row */
    $max_width = 0;
    $width = 0;
    foreach ($captions as $name => $caption) {
      if ($name[0] == '_' && substr($name, -1) == '_') {
        $width = 1;
      } else {
        $width++;
      }
      if ($width > $max_width) {
        $max_width = $width;
      }
      if (substr($name, -1) == '_') {
        $width = 1;
      }
    }

    $group = null;
    foreach ($results as $nrow => $row) {
      $starting_new_group = false;

      if ($nrow == 0) {
        $starting_new_group = true;
      } else if ($format == 'html' &&
          (isset($row['__group__']) && $group !== $row['__group__'])) {
        $starting_new_group = true;
      }

      if ($starting_new_group) {
        /* starting a new group */
        if ($nrow) {
          /* close the old one */
          if ($format == 'html') {
            $out .= "</tbody></table>\n";
          }
        }
        if ($format == 'html' && isset($row['__group__'])) {
          $out .= "<h2 class='reportgroup'>" .
            htmlentities($row['__group__'], ENT_COMPAT, 'utf-8') .
            "</h2>\n";
          $group = $row['__group__'];
        }

        if ($format == 'html') {
          $out .= "<table class='report'><thead><tr>";
        }

        foreach ($captions as $name => $caption) {

          /* figure out sort info for javascript bits */
          $sort = null;
          switch (strtolower($caption)) {
            case 'priority':
            case 'ticket':
            case 'severity':
              $sort = strtolower($caption);
              break;
            case 'created':
            case 'modified':
            case 'date':
            case 'due':
              $sort = 'mtrackdate';
              break;
            case 'remaining':
              $sort = 'digit';
              break;
            case 'updated':
            case 'time':
            case 'content':
            case 'summary':
            default:
              break;
          }

          $caption = ucfirst($caption);
          if ($name[0] == '_' && substr($name,-1) == '_') {
            if ($format == 'html') {
              $out .= "</tr><tr><th colspan='$max_width'>$caption</th></tr><tr>";
            } else if ($format == 'tab') {
              $out .= "$caption\t";
            }
          } elseif ($name[0] == '_') {
            continue;
          } else {
            if ($format == 'html') {
              $out .= "<th";
              if ($sort !== null) {
                $out .= " class=\"{sorter: '$sort'}\"";
              }
              $out .= ">$caption</th>";
              if (substr($name, -1) == '_') {
                $out .= "</tr><tr>";
              }
            } else if ($format == 'tab') {
              $out .= "$caption\t";
            }
          }
        }
        if ($format == 'html') {
          $out .= "</tr></thead><tbody>\n";
        } else if ($format == 'tab') {
          $out .= "\n";
        }
      }

      /* and now the column data itself */
      if (isset($row['__style__'])) {
        $style = " style=\"$row[__style__]\"";
      } else {
        $style = "";
      }
      $class = $nrow % 2 ? "even" : "odd";
      if (isset($row['__color__'])) {
        $class .= " color$row[__color__]";
      }
      if (isset($row['__status__'])) {
        $class .= " status$row[__status__]";
      }

      if ($format == 'html') {
        $begin_row = "<tr class=\"$class\"$style>";
        $out .= $begin_row;
      }
      $href = null;

      /* determine if we should link to something for this row */
      if (isset($row['ticket'])) {
        $href = $ABSWEB . "ticket.php/$row[ticket]";
      }

      foreach ($captions as $name => $caption) {
        $v = $row[$name];

        /* apply special formatting rules */
        if ($format == 'html') {
          switch (strtolower($caption)) {
            case 'created':
            case 'modified':
            case 'date':
            case 'due':
            case 'updated':
            case 'time':
              if ($v !== null) {
                $v = mtrack_date($v);
              }
              break;
            case 'content':
              $v = MTrackWiki::format_to_html($v);
              break;
            case 'owner':
              $v = mtrack_username($v, array('no_image' => true));
              break;
            case 'docid':
            case 'ticket':
              $v = mtrack_ticket($row);
              break;
            case 'summary':
              if ($href) {
                $v = htmlentities($v, ENT_QUOTES, 'utf-8');
                $v = "<a href=\"$href\">$v</a>";
              } else {
                $v = htmlentities($v, ENT_QUOTES, 'utf-8');
              }
              break;
            case 'milestone':
              $oldv = $v;
              $v = '';
              foreach (preg_split("/\s*,\s*/", $oldv) as $m) {
                if (!strlen($m)) continue;
                $v .= "<span class='milestone'>" .
                      "<a href=\"{$ABSWEB}milestone.php/" .
                      urlencode($m) . "\">" .
                      htmlentities($m, ENT_QUOTES, 'utf-8') .
                      "</a></span> ";
              }
              break;
            case 'keyword':
              $oldv = $v;
              $v = '';
              foreach (preg_split("/\s*,\s*/", $oldv) as $m) {
                if (!strlen($m)) continue;
                $v .= mtrack_keyword($m) . ' ';
              }
              break;
            default:
              $v = htmlentities($v, ENT_QUOTES, 'utf-8');
          }
        } else if ($format == 'tab') {
          $v = trim(preg_replace("/[\t\n\r]+/sm", " ", $v));
        }

        if ($name[0] == '_' && substr($name, -1) == '_') {
          if ($format == 'html') {
            $out .= "</tr>$begin_row<td class='$caption' colspan='$max_width'>$v</td></tr>$begin_row";
          } else if ($format == 'tab') {
            $out .= "$v\t";
          }
        } elseif ($name[0] == '_') {
          if ($format == 'tab') {
            $out .= "$v\t";
          } else {
            continue;
          }
        } else {
          if ($format == 'html') {
            $out .= "<td class='$caption'>$v</td>";
            if (substr($name, -1) == '_') {
              $out .= "</tr>$begin_row";
            }
          } else if ($format == 'tab') {
            $out .= "$v\t";
          }
        }
      }
      if ($format == 'html') {
        $out .= "</tr>\n";
      } else if ($format == 'tab') {
        $out .= "\n";
      }
    }
    if ($format == 'html') {
      $out .= "</tbody></table>";
    } else if ($format == 'tab') {
      $out = str_replace("\t\n", "\n", $out);
    }

    return $out;
  }

  static function macro_RunReport($name, $url_style_params = null) {
    $params = array();
    parse_str($url_style_params, $params);
    $rep = self::loadBySummary($name);
    if ($rep) {
      if (MTrackACL::hasAllRights("report:" . $rep->rid, 'read')) {
        return $rep->renderReport($rep->query, $params);
      } else {
        return "Not authorized to run report $name";
      }
    } else {
      return "Unable to find report $name";
    }
  }

  static function parseQuery()
  {
    $macro_params = array(
      'group' => true,
      'col' => true,
      'order' => true,
      'desc' => true,
      'format' => true,
      'compact' => true,
      'count' => true,
      'max' => true
    );

    $mparams = array(
      'col' => array('ticket', 'summary', 'state', 
                'priority',
                'owner', 'type', 'component',
                'remaining'),
      'order' => array('pri.value'),
      'desc' => array('0'),
    );
    $params = array();

    $args = func_get_args();
    foreach ($args as $arg) {
      if ($arg === null) continue;
      $p = explode('&', $arg);

      foreach ($p as $a) {
        $a = urldecode($a);
        preg_match('/^([a-zA-Z_]+)(!?(?:=|~=|\^=|\$=))(.*)$/', $a, $M);

        $k = $M[1];
        $op = $M[2];
        $pat = explode('|', $M[3]);

        if (isset($macro_params[$k])) {
          $mparams[$k] = $pat;
        } else if (isset($params[$k])) {
          if ($params[$k][0] == $op) {
            // compatible operator; add $pat to possible set
            $params[$k][1] = array_merge($pat, $params[$k][1]);
          } else {
            // ignore
          }
        } else {
          $params[$k] = array($op, $pat);
        }
      }
    }
    return array($params, $mparams);
  }

  static function macro_TicketQuery()
  {
    $args = func_get_args();
    list($params, $mparams) = call_user_func_array(array(
      'MTrackReport', 'parseQuery'), $args);

    /* compose that info into a query */
    $sql = 'select ';

    $colmap = array(
      'ticket' => '(case when t.nsident is null then t.tid else t.nsident end) as ticket',
      'component' => '(select mtrack_group_concat(name) from ticket_components
            tcm left join components c on (tcm.compid = c.compid)
            where tcm.tid = t.tid) as component',
      'keyword' => '(select mtrack_group_concat(keyword) from ticket_keywords
            tk left join keywords k on (tk.kid = k.kid)
            where tk.tid = t.tid) as keyword',
      'type' => 'classification as type',
      'remaining' => "(case when t.status = 'closed' then 0 else (t.estimated - (select sum(expended) from effort where effort.tid = t.tid)) end) as remaining",
      'state' => "(case when t.status = 'closed' then coalesce(t.resolution, 'closed') else t.status end) as state",
      'milestone' => '(select mtrack_group_concat(name) from ticket_milestones
            tmm left join milestones tmmm on (tmm.mid = tmmm.mid)
            where tmm.tid = t.tid) as milestone',
    );

    $cols = array(
     ' pri.value as __color__ ',
     ' (case when t.nsident is null then t.tid else t.nsident end) as ticket ',
     " t.status as __status__ ",
    );

    foreach ($mparams['col'] as $colname) {
      if ($colname == 'ticket') {
        continue;
      }
      if (isset($colmap[$colname])) {
        $cols[$colname] = $colmap[$colname];
      } else {
        if (!preg_match("/^[a-zA-Z_]+$/", $colname)) {
          throw new Exception("column name $colname is invalid");
        }
        $cols[$colname] = $colname;
      }
    }

    $sql .= join(', ', $cols);
   
    if (!isset($params['milestone'])) {
      $sql .= <<<SQL

FROM
tickets t 
left join priorities pri on (t.priority = pri.priorityname)
left join severities sev on (t.severity = sev.sevname)
WHERE
 1 = 1

SQL;
    } else {
      $sql .= <<<SQL

FROM milestones m 
left join ticket_milestones tm on (m.mid = tm.mid)
left join tickets t on (tm.tid = t.tid)
left join priorities pri on (t.priority = pri.priorityname)
left join severities sev on (t.severity = sev.sevname)
WHERE
 1 = 1

SQL;
    }

    $critmap = array(
      'milestone' => 'm.name',
      'tid' => 't.tid',
      'id' => 't.tid',
      'ticket' => 't.tid',
    );

    foreach ($params as $k => $v) {
      list($op, $values) = $v;

      if (isset($critmap[$k])) {
        $k = $critmap[$k];
      }

      $sql .= " AND ";

      if ($op[0] == '!') {
        $sql .= " NOT ";
        $op = substr($op, 1);
      }
      $sql .= "(";

      if ($op == '=') {

        if ($k == 't.tid' && count($values) == 1 &&
            preg_match('/[,-]/', $values[0])) {

          $crit = array();
          foreach (explode(',', $values[0]) as $range) {
            list($rfrom, $rto) = explode('-', $range, 2);
            $type = 'integer';
            if (!ctype_digit($rfrom)) {
              $rfrom = MTrackDB::esc($rfrom);
              $type = 'text';
            }
            if ($rto) {
              if (!ctype_digit($rto)) {
                $rto = MTrackDB::esc($rto);
                $type = 'text';
              }
              $crit[] = "(cast(t.tid as $type) between $rfrom and $rto)";
              $crit[] = "(cast(t.nsident as $type) between $rfrom and $rto)";
            } else {
              $crit[] = "(t.tid = $rfrom)";
              $crit[] = "(t.nsident = $rfrom)";
            }
          }
          $sql .= join(' OR ', $crit);
        } else if (count($values) == 1) {
          $sql .= " $k = " . MTrackDB::esc($values[0]) . " ";
        } else {

          $sql .= " $k in (";
          foreach ($values as $i => $val) {
            $values[$i] = MTrackDB::esc($val);
          }
          $sql .= join(', ', $values) . ") ";
        }
      } else {
        /* variations on like */
        if ($op == '~=') {
          $start = '%';
          $end = '%';
        } else if ($op == '^=') {
          $start = '';
          $end = '%';
        } else {
          $start = '%';
          $end = '';
        }
      
        $crit = array();

        foreach ($values as $val) {
          $crit[] = "($k LIKE " . MTrackDB::esc("$start$val$end") . ")";
        }
        $sql .= join(" OR ", $crit);
      }

      $sql .= ") ";

    }
    if (isset($mparams['group'])) {
      $g = $mparams['group'][0];
      if (!ctype_alpha($g)) {
        throw new Exception("group $g is not alpha");
      }
      $sql .= ' GROUP BY ' . $g;
    }

    if (isset($mparams['order'])) {
      $k = $mparams['order'][0];
      if ($k == 'tid') {
        $k = 't.tid';
      }

      $sql .= ' ORDER BY ' . $k;
      if (isset($mparams['desc']) && $mparams['desc'][0]) {
        $sql .= ' DESC';
      }
    }

    if (isset($mparams['max'])) {
      $sql .= ' LIMIT ' . (int)$mparams['max'][0];
    }
#    return htmlentities($sql);
#    return var_export($sql, true); 

    return self::renderReport($sql);


  }
};

MTrackWiki::register_macro('RunReport',
  array('MTrackReport', 'macro_RunReport'));

MTrackWiki::register_macro('TicketQuery',
  array('MTrackReport', 'macro_TicketQuery'));

MTrackACL::registerAncestry('report', 'Reports');

