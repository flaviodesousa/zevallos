<?php # vim:ts=2:sw=2:et:
/* For licensing and copyright terms, see the file named LICENSE */

/* maintain cache */
function mtrack_cache_maintain()
{
  $cachedir = MTrackConfig::get('core', 'vardir') . '/cmdcache';
  $max_cache_life = MTrackConfig::get('core', 'max_cache_life');
  if (!$max_cache_life) {
    $max_cache_life = 14 * 86400;
  }
  foreach (scandir($cachedir) as $name) {
    $filename = "$cachedir/$name";
    if (!is_file($filename)) {
      continue;
    }
    $st = stat($filename);
    if ($st['mtime'] + $max_cache_life < time()) {
      unlink($filename);
    }
  }
}

/* walks the cache; loads each element and examines the keys.
 * if the key prefix matches $key, that element is removed */
function mtrack_cache_blow_matching($key)
{
  $cachedir = MTrackConfig::get('core', 'vardir') . '/cmdcache';
  foreach (scandir($cachedir) as $name) {
    $filename = "$cachedir/$name";
    if (!is_file($filename)) {
      continue;
    }
    $fp = @fopen($filename, 'r');
    if (!$fp) {
      continue;
    }
    flock($fp, LOCK_SH);
    $data = unserialize(stream_get_contents($fp));
    flock($fp, LOCK_UN);
    $fp = null;

    $match = true;
    foreach ($key as $i => $element) {
      if ($data->key[$i] != $element) {
        $match = false;
        break;
      }
    }
    if ($match) {
      unlink("$cachedir/$name");
    }
  }
}

function mtrack_cache_blow($key)
{
  $cachedir = MTrackConfig::get('core', 'vardir') . '/cmdcache';
  foreach (scandir($cachedir) as $name) {
    if (!is_file($name)) {
      continue;
    }
    $fp = @fopen("$cachedir/$name", 'r');
    if (!$fp) {
      continue;
    }
    flock($fp, LOCK_SH);
    $data = unserialize(stream_get_contents($fp));
    flock($fp, LOCK_UN);
    $fp = null;

    if ($key == $data->key) {
      unlink("$cachedir/$name");
    }
  }
}

function mtrack_cache($func, $args, $cache_life = 300, $key = null)
{
  $cachedir = MTrackConfig::get('core', 'vardir') . '/cmdcache';
  if (!is_dir($cachedir)) {
    mkdir($cachedir);
  }
  if ($key === null) {
    $fkey = var_export($args, true);
    $key = $fkey;
  } else {
    $fkey = var_export($key, true);
  }
  if (is_string($func)) {
    $fkey = "$func$fkey";
  } else {
    $fkey = var_export($func, true) . $fkey;
  }

  $cachefile = $cachedir . '/' .  sha1($fkey);

  $updating = false;
  for ($i = 0; $i < 10; $i++) {
    $fp = @fopen($cachefile, 'r+');
    if ($fp) {
      flock($fp, LOCK_SH);
      /* is it current? */
      $st = fstat($fp);
      if ($st['size'] == 0) {
        /* not valid to have 0 size; we're likely racing with the
         * creator */
        flock($fp, LOCK_UN);
        $fp = null;
        usleep(100);
        continue;
      }
      if ($st['mtime'] + $cache_life < time()) {
        /* no longer current; we'll make it current */
        $updating = true;
        flock($fp, LOCK_EX);
        /* we have exclusive access; someone else may have
         * made it current in the meantime */
        $st = fstat($fp);
        if ($st['mtime'] + $cache_life >= time()) {
          $updating = false;
        }
      }
      break;
    }
    /* we're going to create it */
    $fp = @fopen($cachefile, 'x+');
    if ($fp) {
      flock($fp, LOCK_EX);
      $updating = true;
      break;
    }
  }

  if ($fp) {
    if ($updating) {
      ftruncate($fp, 0);

      $result = call_user_func_array($func, $args);
      $data = new stdclass;
      $data->key = $key;
      $data->res = $result;
      fwrite($fp, serialize($data));
      flock($fp, LOCK_UN);
      return $result;
    }

    $data = unserialize(stream_get_contents($fp));
    flock($fp, LOCK_UN);
    return $data->res;
  }
  /* if we didn't get a file pointer, just run the command */
  return call_user_func_array($func, $args);
}

