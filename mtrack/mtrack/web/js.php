<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */
include '../inc/common.php';

$age = 3600;

header('Content-Type: text/javascript');
header("Cache-Control: public, max-age=$age, pre-check=$age");
header('Expires: ' . date(DATE_COOKIE, time() + $age));

$scripts = array(
  'excanvas.pack.js',
  'jquery-1.4.2.min.js',
  'jquery-ui-1.8.2.custom.min.js',
  'jquery.asmselect.js',
  'jquery.flot.pack.js',
  'jquery.MultiFile.pack.js',
  'jquery.cookie.js',
  'jquery.treeview.js',
  'jquery.tablesorter.js',
  'jquery.metadata.js',
  'jquery.markitup.js',
  'jquery.timeago.js',
  'json2.js',
);

echo "var ABSWEB = '$ABSWEB';\n";

foreach ($scripts as $name) {
  echo "\n// $name\n";
  readfile("js/$name");
  echo "\n;\n";
}


$PRI_SWITCH = '';
foreach (MTrackDB::q('select priorityname, value from priorities')
    ->fetchAll() as $row) {
  $PRI_SWITCH .= "case '$row[0]': return $row[1];\n";
}
$SEV_SWITCH = '';
foreach (MTrackDB::q('select sevname, ordinal from severities')
    ->fetchAll() as $row) {
  $SEV_SWITCH .= "case '$row[0]': return $row[1];\n";
}

echo <<<JAVASCRIPT
$(document).ready(function() {
  jQuery.timeago.settings.allowFuture = true;
  $('abbr.timeinterval').timeago();
  $("select[multiple]").asmSelect({
    addItemTarget: 'bottom',
    animate: false,
    highlight: false,
    removeLabel: '[x]',
    sortable: false
  });
  if ($.browser.mozilla) {
    // http://www.ryancramer.com/journal/entries/radio_buttons_firefox/
    $("form").attr("autocomplete", "off");
  }
  $("textarea.wiki").markItUp({
    nameSpace:          "wiki",
    previewParserPath:  "{$ABSWEB}markitup-preview.php",
    root: "{$ABSWEB}js",
    onShiftEnter:       {keepDefault:false, replaceWith:'\\n\\n'},
    markupSet:  [
      {
        name:'Heading 1', key:'1',
        openWith:'== ', closeWith:' ==', placeHolder:'Your title here...'
      },
      {
        name:'Heading 2', key:'2',
        openWith:'=== ', closeWith:' ===', placeHolder:'Your title here...'
      },
      {
        name:'Heading 3', key:'3',
        openWith:'==== ', closeWith:' ====', placeHolder:'Your title here...'
      },
      {
        name:'Heading 4', key:'4',
        openWith:'===== ', closeWith:' =====', placeHolder:'Your title here...'
      },
      {
        name:'Heading 5', key:'5',
        openWith:'====== ', closeWith:' ======',
        placeHolder:'Your title here...'
      },
      {separator:'---------------' },
      {name:'Bold', key:'B', openWith:"'''", closeWith:"'''"},
      {name:'Italic', key:'I', openWith:"''", closeWith:"''"},
      {name:'Stroke through', key:'S', openWith:'~~', closeWith:'~~'},
      {separator:'---------------' },
      {name:'Bulleted list', openWith:' * '},
      {name:'Numeric list', openWith:' 1. '},
      {separator:'---------------' },
      {name:'Quotes', openWith:'(!(> |!|>)!)'},
      {name:'Code', openWith:'{{{\\n', closeWith:'\\n}}}'},
      {separator:'---------------' },
      {name:'Preview', call:'preview', className:'preview'}
    ]
});

  $.tablesorter.addParser({
    id: 'ticket',
    is: function(s) {
      return /^#\d+/.test(s);
    },
    format: function(s) {
      return $.tablesorter.formatFloat(s.replace(new RegExp(/#/g), ''));
    },
    type: 'numeric'
  });
  $.tablesorter.addParser({
    id: 'priority',
    is: function(s) {
      // don't auto-detect
      return false;
    },
    format: function(s) {
      switch (s) {
        $PRI_SWITCH
      }
      return s;
    },
    type: 'numeric'
  });
  $.tablesorter.addParser({
    id: 'severity',
    is: function(s) {
      // don't auto-detect
      return false;
    },
    format: function(s) {
      switch (s) {
        $SEV_SWITCH
      }
      return s;
    },
    type: 'numeric'
  });
  $.tablesorter.addParser({
    id: 'mtrackdate',
    is: function(s) {
      // don't auto-detect
      return false;
    },
    format: function(s) {
      // relies on the textExtraction routine below to pull a
      // date/time string out of the title portion of the abbr tag
      return $.tablesorter.formatFloat(new Date(s).getTime());
    },
    type: 'numeric'
  });
  $("table.report, table.wiki").tablesorter({
    textExtraction: function(node) {
      var kid = node.childNodes[0];
      if (kid && kid.tagName == 'ABBR') {
        // assuming that this abbr is of class='timeinterval'
        return kid.title;
      }
      // default 'simple' behavior
      if (kid && kid.hasChildNodes()) {
        return kid.innerHTML;
      }
      return node.innerHTML;
    }
  });
  $('input.search[type=text]').each(function () {
    if ($.browser.webkit) {
      this.type = 'search';
      $(this).attr('autosave', ABSWEB);
      $(this).attr('results', 5);
    } else {
      $(this).addClass('roundsearch');
    }
  });
  // Convert links that are styled after buttons into actual buttons
  $('a.button[href]').each(function () {
    var href = $(this).attr('href');
    var but = $('<button type="button"/>');
    but.text($(this).text());
    $(this).replaceWith(but);
    but.click(function () {
      document.location.href = href;
      return false;
    });
  });

  $.fn.mtrackWatermark = function () {
    this.each(function () {
      var ph = $(this).attr('title');
      if ($.browser.webkit) {
        // Use native safari placeholder for watermark
        $(this).attr('placeholder', ph);
      } else {
        // http://plugins.jquery.com/files/jquery.tinywatermark-2.0.0.js.txt
        var w;
        var me = $(this);
        me.focus(function () {
          if (w) {
            w = 0;
            me.removeClass('watermark').data('w', 0).val('');
          }
        })
        .blur(function () {
          if (!me.val()) {
            w = 1;
            me.addClass('watermark').data('w', 1).val(ph);
          }
        })
        .closest('form').submit(function () {
          if (w) {
            me.val('');
          }
        });
        me.blur();
      }
    });
  };
  // Watermarking
  $('input[title!=""]').mtrackWatermark();

  // Toggle line number display in diff visualizations, to make it easier
  // to copy the diff contents
  var diff_visible = true;
  $('.togglediffcopy').click(function () {
    diff_visible = !diff_visible;
    if (diff_visible) {
      $('table.code.diff tr td.lineno').show();
      $('table.code.diff tr td.linelink').show();
    } else {
      $('table.code.diff tr td.lineno').hide();
      $('table.code.diff tr td.linelink').hide();
    }
  });

  // Syntax highlighting
  var hl_color_scheme = 'wezterm';
  function applyhl(name) {
    if (hl_color_scheme != '') {
      $('.source-code').removeClass(hl_color_scheme);
    }
    if (name != '') {
      $('.source-code').addClass(name);
    }
    hl_color_scheme = name;
  }
  $('.select-hl-scheme').change(function () {
    applyhl($(this).val());
    var val = $(this).val();
    $('.select-hl-scheme').each(function () {
      $(this).val(val);
    });
  });

  // Arrange for the footer to sink to the bottom of the window, if the window
  // contents are not very tall
  var last_dh = 0;
  var last_wh = 0;
  function mtrack_footer_position(force) {
    var ele = $('#footer');
    if (!force &&
        (last_dh != $(document).height() || last_wh != $(window).height)) {
      force = true;
    }
    if (force) {
      // Force a from-scratch layout assessment; put the footer back in
      // it's natural location in the doc
      ele.css({
        position: "relative",
        "margin-top": "3em",
        top: 0,
      });
    }
    if ($(document).height() <= $(window).height()) {
      ele.css({
        position: "absolute",
        "margin-top": "0",
        top: (
            $(window).scrollTop() +
            $(window).height() -
            ele.height() - 1
          )+"px"
      });
    } else {
      ele.css({
        position: "relative",
        "margin-top": "3em"
      });
    }
    last_dh = $(document).height();
    last_wh = $(window).height();
  }
  window.mtrack_footer_position = mtrack_footer_position;
  $(window)
    .scroll(mtrack_footer_position)
    .resize(mtrack_footer_position);
  function mtrack_footer_set_and_wait() {
    mtrack_footer_position();
    setTimeout(function () {
      mtrack_footer_set_and_wait();
    }, 1500);
  }
  mtrack_footer_set_and_wait();
});

JAVASCRIPT;
