<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

include '../inc/common.php';
#error_reporting(E_ALL | E_NOTICE);

$pi = mtrack_get_pathinfo();
if (empty($pi)) {
  $pi = "WikiStart";
}

$edit = isset($_REQUEST['edit']) ? (int)$_REQUEST['edit'] : null;
$message = null;
$conflicted = false;

function is_content_conflicted($content)
{
  if (preg_match("/^(<+)\s+(mine|theirs|original)\s*$/m", $content)) {
    return true;
  }
  return false;
}
function normalize_text($text) {
  return rtrim($text) . "\n";
}


if ($pi !== null) {
  $doc = MTrackWikiItem::loadByPageName($pi);
  if ($doc) {
    MTrackACL::requireAnyRights("wiki:$doc->pagename",
      $edit ? 'modify' : 'read');
  } else {
    MTrackACL::requireAnyRights("wiki:$pi",
      $edit ? 'modify' : 'read');
  }
  if ($doc === null && $edit) {
    $doc = new MTrackWikiItem($pi);
    $doc->content = " = $pi =\n";
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['cancel'])) {
      header("Location: ${ABSWEB}wiki.php/$pi");
      exit;
    }
    if (!MTrackCaptcha::check('wiki')) {
      $message = 'CAPTCHA failed, please try again';
    } else if (isset($_POST['save'])) {
      /* to avoid annoying "you lose because someone else edited" errors,
       * we compute the diff from the original content we had, and apply
       * that to the current content of the object */

      $saved = false;

      $orig = base64_decode($_POST['orig']);
      $content = $_POST['content'];

      /* for consistency, we always want a newline at the end, otherwise
       * we can end up with some weird output from diff3 */
      $orig = normalize_text($orig);
      $content = normalize_text($content);
      $doc->content = normalize_text($doc->content);
      $conflicted = is_content_conflicted($content);
      $tempdir = sys_get_temp_dir();

      if (!$conflicted) {
        $ofile = tempnam($tempdir, "mtrack");
        $nfile = tempnam($tempdir, "mtrack");
        $tfile = tempnam($tempdir, "mtrack");
        $pfile = tempnam($tempdir, "mtrack");
        $diff3 = MTrackConfig::get('tools', 'diff3');
        if (empty($diff3)) {
          $diff3 = 'diff3';
        }

        file_put_contents($ofile, $orig);
        file_put_contents($nfile, $content);
        file_put_contents($tfile, $doc->content);

        if (PHP_OS == 'SunOS') {
          exec("($diff3 -X $nfile $ofile $tfile ; echo '1,\$p') | ed - $nfile > $pfile",
            $output = array(), $retval = 0);
        } else {
          exec("$diff3 -mX --label mine $nfile --label original $ofile --label theirs $tfile > $pfile",
            $output = array(), $retval = 0);
        }

        if ($retval == 0) {
          /* see if there were merge conflicts */
          $content = '';
          $mine = preg_quote($nfile, '/');
          $theirs = preg_quote($tfile, '/');
          $orig = preg_quote($ofile, '/');
          $content = file_get_contents($pfile);

          if (PHP_OS == 'SunOS') {
            $content = str_replace($nfile, 'mine', $content);
            $content = str_replace($ofile, 'original', $content);
            $content = str_replace($tfile, 'theirs', $content);
          }
        }
        unlink($ofile);
        unlink($nfile);
        unlink($tfile);
        unlink($pfile);

        $conflicted = is_content_conflicted($content);
      }

      /* keep the merged version for editing purposes */
      $_POST['content'] = $content;
      /* our concept of the the original content is now what
       * is currently saved */
      $_POST['orig'] = base64_encode($doc->content);

      if ($conflicted) {
        $message = "Conflicting edits were detected; please correct them before saving";
      } else {
        $doc->content = $content;
        try {
          $cs = MTrackChangeset::begin("wiki:$pi", $_POST['comment']);
          $doc->save($cs);
          if (is_array($_FILES['attachments'])) {
            foreach ($_FILES['attachments']['name'] as $fileid => $name) {
              $do_attach = false;
              switch ($_FILES['attachments']['error'][$fileid]) {
                case UPLOAD_ERR_OK:
                  $do_attach = true;
                  break;
                case UPLOAD_ERR_NO_FILE:
                  break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                  $message = "Attachment(s) exceed the upload file size limit";
                  break;
                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_CANT_WRITE:
                  $message = "Attachment file upload failed";
                  break;
                case UPLOAD_ERR_NO_TMP_DIR:
                  $message = "Server configuration prevents upload due to missing temporary dir";
                  break;
                case UPLOAD_ERR_EXTENSION:
                  $message = "An extension prevented an upload from running";
              }
              if ($message !== null) {
                throw new Exception($message);
              }
              if ($do_attach) {
                MTrackAttachment::add("wiki:$pi",
                  $_FILES['attachments']['tmp_name'][$fileid],
                  $_FILES['attachments']['name'][$fileid],
                  $cs);
              }
            }
          }
          MTrackAttachment::process_delete("wiki:$pi", $cs);
          $cs->commit();
          MTrackWikiItem::commitNow();
          $saved = true;
        } catch (Exception $e) {
          $message = $e->getMessage();
        }
      }

      if ($saved) {
        /* we're good; go back to view mode */
        header("Location: ${ABSWEB}wiki.php/$pi");
        exit;
      }
    }
  }
}

/* now just render */

$title = $pi;
if ($edit) {
  $title .= " (edit)";
}
mtrack_head($title);
$ppi = htmlentities($pi, ENT_QUOTES, 'utf-8');
$editurl = $ABSWEB . "wiki.php/$pi";

$nav = array();

if (!$edit && MTrackACL::hasAnyRights("wiki:$pi", 'modify')) {
  $nav["$editurl?edit=1"] = 'Edit this Page';
}

if ($doc) {
  $nav["/log.php/default/wiki/$doc->filename"] = "Page History";
}

$nav["/wiki.php?action=list"] = "Help &amp; Title Index";
$nav["/wiki.php?action=recent"] = "Recent Changes";

if ($doc && $doc->file) {
  $evt = $doc->file->getChangeEvent();
  $reason = $evt->changelog;
  if (!strlen($evt->changelog)) {
    $reason = 'Changed';
  }
  $reason = htmlentities($reason, ENT_QUOTES, 'utf-8');
  echo "<div id='wikilastchange'>",
    mtrack_username($evt->changeby, array('no_name' => true,
    'class' => 'wikilastchange')),
    "$reason by ",
    mtrack_username($evt->changeby, array('no_image' => true)), " ",
    mtrack_date($evt->ctime),
    "</div>\n";
}

echo mtrack_nav("wikinav", $nav);

if (strlen($message)) {
  echo "<br><div class='ui-state-error ui-corner-all'>" .
    "<span class='ui-icon ui-icon-alert'></span>\n" .
    htmlentities($message, ENT_QUOTES, 'utf-8') .
    "</div>";
}

if (count($_GET) == 0 && ($doc === null || strlen($doc->content) == 0)) {
  if (MTrackACL::hasAnyRights("wiki:$pi", 'create')) {
    echo "Wiki page $ppi doesn't exist, would you like to create it?<br>";

    echo <<<HTML
<form name="launchwikiedit" method="GET" action="$editurl">
<input type="hidden" name="edit" value="1"/>
<button type="submit">Edit this page</button>
</form>
HTML;
  } else {
    echo "Wiki page $ppi doesn't exist.<br>";
  }

} elseif ($edit) {
  echo "<h1>Editing $ppi</h1>";
  echo "<a href=\"{$ABSWEB}/help.php/WikiFormatting\" target=\"_blank\">Wiki Formatting</a> (opens in a new window)<br>\n";

  $orig_content = isset($_POST['orig']) ? $_POST['orig']
                    : base64_encode($doc->content);
  $content = isset($_POST['content']) ? $_POST['content'] : $doc->content;
  $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
  $comment = htmlentities($comment, ENT_QUOTES, 'utf-8');

  if (isset($_POST['preview'])) {
    echo "<div class='wikipreview'>" .
      MTrackWiki::format_to_html($content) . "</div>";
  }

  echo <<<HTML
<form name="wikiedit" method="POST" action="$editurl" enctype='multipart/form-data'>
<input type="hidden" name="edit" value="1"/>
<input type="hidden" name="orig" value="$orig_content"/>
HTML;

    if ($conflicted) {
      echo "<input type='hidden' name='conflicted' value='1'/>";
    }

    echo <<<HTML
<textarea name="content" class="wiki"
  rows="36" cols="78" style="width:100%;">$content</textarea>
<fieldset>
  <legend>Attachments</legend>
HTML;
    echo MTrackAttachment::renderDeleteList("wiki:$pi");
    echo <<<HTML
  <label for='attachments[]'>Select file(s) to be attached</label>
  <input type='file' class='multi' name='attachments[]'>
</fieldset>
<fieldset id="changeinfo">
  <legend>Change Information</legend>
  <div class="field"><label>Comment about the change:<br/>
    <input type="text" name="comment" size="60" value="$comment"/>
  </label></div>
HTML;
    echo MTrackCaptcha::emit('wiki');
    echo <<<HTML
  <div class="buttons">
    <button type="submit" name="preview">Preview</button>
    <button type="submit" name="save">Save changes</button>
    <button type="submit" name="cancel">Cancel</button>
  </div>
</form>

HTML;

} else {
  $action = isset($_GET['action']) ? $_GET['action'] : 'view';

  switch ($action) {
    case 'view':
      echo MTrackWiki::format_to_html($doc->content);
      echo MTrackAttachment::renderList("wiki:$pi");
      if (MTrackACL::hasAnyRights("wiki:$doc->pagename", 'modify')) {
        echo <<<HTML
<form name="launchwikiedit" method="GET" action="$editurl">
<input type="hidden" name="edit" value="1"/>
<button type="submit">Edit this page</button>
</form>
HTML;
      }
      break;

    case 'list':
      echo "<h1>Help topics by Title</h1>\n";
      $htree = array();

      function build_help_tree(&$tree, $dir) {
        foreach (scandir($dir) as $ent) {
          if ($ent[0] == '.') {
            continue;
          }
          $full = $dir . DIRECTORY_SEPARATOR . $ent;
          if (is_dir($full)) {
            $kid = array();
            build_help_tree($kid, $full);
            $tree[$ent] = $kid;
          } else {
            $tree[$ent] = array();
          }
        }
      }
      function emit_tree($root, $parent, $phppage)
      {
        global $ABSWEB;

        if (strlen($parent)) {
          echo "<ul>\n";
        } else {
          echo "<ul class='wikitree'>\n";
        }
        $knames = array_keys($root);
        usort($knames, 'strnatcasecmp');
        foreach ($knames as $key) {
          $kids = $root[$key];
          $n = htmlentities($key, ENT_QUOTES, 'utf-8');
          echo "<li>";
          if (count($kids)) {
            echo $n;
            emit_tree($kids, "$parent$key/", $phppage);
          } else {
            echo "<a href=\"${ABSWEB}$phppage/$parent$n\">$n</a>";
          }
          echo "</li>\n";
        }
        echo "</ul>\n";
      }

      build_help_tree($htree, dirname(__FILE__) . '/../defaults/help');
      emit_tree($htree, '', 'help.php');

      echo "<h1>Wiki pages by Title</h1>\n";
      /* get the page names into a tree format */
      $tree = array();
      $root = MTrackWikiItem::getRepoAndRoot($repo);
      $suf = MTrackConfig::get('core', 'wikifilenamesuffix');

      function build_tree(&$tree, $repo, $dir, $suf) {
        $items = $repo->readdir($dir);
        foreach ($items as $file) {
          $label = basename($file->name);
          if ($file->is_dir) {
            $kid = array();
            build_tree($kid, $repo, $file->name, $suf);
            $tree[$label] = $kid;
          } else {
            if ($suf && substr($label, -strlen($suf)) == $suf) {
              $label = substr($label, 0, strlen($label) - strlen($suf));
            }
            $tree[$label] = array();
          }
        }
      }

      build_tree($tree, $repo, $root, $suf);

      emit_tree($tree, '', 'wiki.php');

      echo <<<HTML
<script type='text/javascript'>
$(document).ready(function(){
  $('ul.wikitree').treeview({
    collapsed: true,
    persist: "location"
  });
});
</script>
HTML;

      break;

    case 'recent':
      echo <<<HTML
<h1>Recently Edited Wiki Pages</h1>
<table class="history">
  <tr>
    <th>Page</th>
    <th>Date</th>
    <th>Who</th>
    <th>Reason</th>
  </tr>
HTML;
      $root = MTrackWikiItem::getRepoAndRoot($repo);
      foreach ($repo->history(null, 100) as $e) {
        $d = mtrack_date($e->ctime);
        list($page) = $e->files;
        if (strlen($root)) {
          $page = substr($page, strlen($root)+1);
        }
        $author = mtrack_username($e->changeby);
        $reason = htmlentities($e->changelog, ENT_QUOTES, 'utf-8');

        echo "<tr><td><a href=\"${ABSWEB}wiki.php/$page\">$page</a></td><td>$d</td><td>$author</td><td>$reason</td></tr>\n";
      }

      echo <<<HTML
</table>
HTML;

      break;

  }
}
mtrack_foot();
