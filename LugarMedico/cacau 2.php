<?PHP
$Rodada = array();

$Cache[1] = explode(',', '1,2,3,4,5,6,8,9,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36');
$Cache[2] = explode(',', '1,10,19,23,6,15,24,33,2,21,26,29,7,16,25,34,3,20,30,32,8,11,17,35,4,13,22,31,9,18,27,36,5,12,14,28');
$Cache[3] = explode(',', '1,11,21,31,5,15,25,35,9,10,24,30,4,14,20,34,8,18,19,29,3,13,23,33,2,7,17,28,12,22,27,32,6,16,26,36');
$Cache[4] = explode(',', '1,8,20,27,4,6,23,25,2,18,26,30,14,16,28,35,9,12,21,33,5,7,15,22,3,11,24,31,10,17,29,36,13,19,32,34');
$Cache[5] = explode(',', '1,22,30,33,4,15,26,28,18,20,21,35,11,16,23,34,2,6,8,29,5,10,13,24,12,17,19,27,3,9,31,32,7,14,25,36');
$Cache[6] = explode(',', '1,5,17,18,9,13,28,31,11,12,29,30,14,26,27,34,6,10,25,33,8,15,21,35,3,7,22,23,2,19,20,36,4,16,24,32');
$Cache[7] = explode(',', '1,3,6,12,4,7,27,31,9,20,22,29,13,14,16,18,5,11,15,33,2,23,24,25,8,10,19,26,17,21,30,32,28,34,35,36');
$Cache[8] = explode(',', '1,15,19,29,22,24,27,28,2,9,12,30,7,17,18,34,6,10,20,25,4,5,26,35,3,13,21,33,8,11,16,36,14,23,31,32');
$Cache[9] = explode(',', '1,9,21,25,17,22,31,35,2,10,27,29,8,14,24,34,4,11,15,30,6,13,23,28,3,5,7,19,12,16,20,33,18,26,32,36');
$Cache[10] = explode(',', '1,2,8,13,12,17,23,24,4,7,9,20,14,19,33,35,11,18,25,32,10,15,27,31,3,21,28,36,5,16,29,30,6,22,26,34');
$Cache[11] = explode(',', '1,14,24,36,3,10,18,21,2,6,11,19,15,23,32,34,7,13,29,33,12,20,26,31,4,8,25,30,9,16,27,35,5,17,22,28');
$Cache[12] = explode(',', '1,7,19,30,9,11,14,29,6,12,24,35,20,23,27,36,3,16,31,34,10,13,17,26,4,5,18,25,8,21,28,32,2,15,22,33');
$Cache[13] = explode(',', '2,3,30,35,5,10,21,34,8,18,31,33,1,11,20,28,6,14,22,19,9,17,24,25,7,12,15,16,23,26,29,36,4,13,27,32');

foreach($Cache as $key => $value) {
  foreach($value as $keyP => $valueP) {
    $Rodada[$key][$valueP] = 1;
  }
}

echo "\r\n\r\nRodadas";

$Jogaram = array();

for ($x=1;$x<=36;$x++) {
  $Jogaram[$x] = array();
}

foreach($Rodada as $key => $value) {
  echo "\r\n".str_pad($key, 2, " ", STR_PAD_LEFT)."=";
  $g=0;
  $Grupo = array();

  foreach($value as $keyP => $valueP) {
    echo str_pad($keyP, 2, " ", STR_PAD_LEFT);

    $g++;

    $Grupo[$g] = $keyP;

    if ($g == 4) {
      $g=0;
      echo "\t";

    } else {
      echo "\t";
    }

  }
}
foreach($Rodada as $key => $value) {
  echo "\r\n$".str_pad($key, 2, " ", STR_PAD_LEFT)."=";
  $g=0;
  $Grupo = array();

  foreach($value as $keyP => $valueP) {
    echo str_pad($keyP, 2, " ", STR_PAD_LEFT);

    $g++;

    $Grupo[$g] = $keyP;

    if ($g == 4) {
      $g=0;
      echo " | ";

      // echo "<pre>".print_r($Grupo, 1);

      $Jogaram[$Grupo[1]][$Grupo[2]]++;
      $Jogaram[$Grupo[1]][$Grupo[3]]++;
      $Jogaram[$Grupo[1]][$Grupo[4]]++;

      $Jogaram[$Grupo[2]][$Grupo[1]]++;
      $Jogaram[$Grupo[2]][$Grupo[3]]++;
      $Jogaram[$Grupo[2]][$Grupo[4]]++;

      $Jogaram[$Grupo[3]][$Grupo[1]]++;
      $Jogaram[$Grupo[3]][$Grupo[2]]++;
      $Jogaram[$Grupo[3]][$Grupo[4]]++;

      $Jogaram[$Grupo[4]][$Grupo[1]]++;
      $Jogaram[$Grupo[4]][$Grupo[2]]++;
      $Jogaram[$Grupo[4]][$Grupo[3]]++;

      $Grupo = array();

    } else {
      echo ", ";
    }

  }
}

echo "\r\n\r\nJogadores X Jogadores";

foreach($Jogaram as $key => $value) {
  echo "\r\n".str_pad($key, 2, " ", STR_PAD_LEFT)."(".str_pad(count($value), 2, " ", STR_PAD_LEFT).")=";
  $Total = 0;
  $MoreThen = array();

  ksort($value);

  foreach($value as $keyP => $valueP) {
    echo str_pad($keyP, 2, " ", STR_PAD_LEFT);

    $Total += $valueP;

    echo "($valueP)";

    $MoreThen[$valueP]++;

    echo " ";
  }

  echo " <-[$Total]";

  if ($MoreThen[2]) echo "-[(2)".$MoreThen[2]."]";
  if ($MoreThen[3]) echo "-[(3)".$MoreThen[3]."]";
  if ($MoreThen[4]) echo "-[(4)".$MoreThen[4]."]";
  if ($MoreThen[5]) echo "-[(5)".$MoreThen[5]."]";
}

die();
?>