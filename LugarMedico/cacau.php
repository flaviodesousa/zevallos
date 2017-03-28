<?php

// 36 - Pessoas
// 13 - Rodadas
//  8 - Posicaos

// 01 02 03 04  05 06 07 08   09 10 11 12  13 14 15 16  17 18 19 20  21 22 23 24  25 26 27 28  29 30 31 32  33 34 35 36

// 01 05 09 13  17 31 25 29   33 02 06 10  14 18 22 26  30 34 03 07  11 15 19 23  27 31 35 04  08 12 16 20  24 28 32 36

// 01 06 10 14  18 22 26 30   34 02 07 11  15 19 23 27  31 35 03 08  12 16 20 24  28 32 36 03  05 09 13 17  21 25 29 33

// 01 07 11 15  19 23 27 31   35 04 08 12  16 20 24 28  32 36 05 09  13 17 21 25  29 33 02 06  10 14 18 22  26 30 34



$Rodada = array();
$Posicao = array();
$Jogadores = array();
$Jogaram = array();

for ($x=1;$x<=36;$x++) {
$Jogaram[$x] = array();
}

// $Rodada[1][1];
// $Jogadores[1][1]=1;

function Rodada($Ro, &$P1) {
  global $Rodada;

  while ($Rodada[$Ro][$P1 = rand(1, 36)]) {
    // echo " RoI[$P1]";

    $Rodada[$Ro][$P1] = 1;
  }

   // echo " RoD[$P1]";

  $Rodada[$Ro][$P1] = 1;
}

function RodadaDisponivel($Ro, &$P2) {
  global $Rodada;

  while ($Rodada[$Ro][$P2 = rand(1, 36)]) {
    // echo " RDI[$Ro][$P2]";
    // if ($P2++ > 36) $P2 = 1;
  }

  // echo " RDD[$P2]";
}

function Posicao($P, $Ro, &$P2) {
  global $Posicao, $Jogadores, $Rodada, $Jogaram;

  $Maximo = 1;
  $Loop = array();

  while (1) {
    RodadaDisponivel($Ro, $P2);

    $Loop[$P2] = 1; // Contar se já passou dos 36, se passou, aceitar o que tiver com 2, 3, 4 e assim por diante.

    $NaoJogou = 1;

    // echo " P[$Ro, $P, $P2]";

    if ($P > 1) {
      foreach($Posicao as $key => $value) {
        if ($Jogaram[$P2][$value] == $Maximo || $Jogaram[$value][$P2] == $Maximo) $NaoJogou = 0;
      }

      if (!$NaoJogou && count($Loop) + count($Rodada[$Ro]) == 36) {
        $Maximo++;
        $Loop = array();
      }


      if ($NaoJogou) {
        $Posicao[$P] = $P2;

        foreach($Posicao as $key => $value) {
          $Jogadores[$P2][$value] = 1;
          $Jogadores[$value][$P2] = 1;

          // if ($Jogadores[$P2][$value]++ > 1) echo " [$P][$value]=".$Jogadores[$P][$value];
          // if ($Jogadores[$value][$P2]++ > 1) echo " [$value][$P]=".$Jogadores[$value][$P];
        }
        break;
      } else {
        if ($P2++ > 36) $P2 = 1;
      }
    }
  }
}

for ($Ro = 1; $Ro <= 13; $Ro++) {
  echo "\r\n\r\n$Ro rodada\r\n";

  $G = 0;
  for ($j = 1; $j <= 36; $j += 4) {
    Rodada($Ro, $P1);

    $P2 = $P1;

    $Posicao = array();

    $Posicao[1] = $P1;

    $J1 = $P1;
    $G++;
    echo "$P2";

    Posicao(2, $Ro, $P2);
    $Rodada[$Ro][$P2] = 1;
    $J2 = $P2;
    echo ", $P2";

    Posicao(3, $Ro, $P2);
    $Rodada[$Ro][$P2] = 1;
    $J3 = $P2;
    echo ", $P2";

    Posicao(4, $Ro, $P2);
    $Rodada[$Ro][$P2] = 1;
    $J4 = $P2;
    echo ", $P2  ";

    $Jogaram[$J1][$J2]++;
    $Jogaram[$J1][$J3]++;
    $Jogaram[$J1][$J4]++;

    $Jogaram[$J2][$J1]++;
    $Jogaram[$J2][$J3]++;
    $Jogaram[$J2][$J4]++;

    $Jogaram[$J3][$J1]++;
    $Jogaram[$J3][$J2]++;
    $Jogaram[$J3][$J4]++;

    $Jogaram[$J4][$J1]++;
    $Jogaram[$J4][$J2]++;
    $Jogaram[$J4][$J3]++;

  }
}

echo "\r\n\r\nJogadores X Jogadores";

foreach($Jogaram as $key => $value) {
  echo "\r\n$key(".count($value).")=";
  $Total = 0;
  $MoreThen = array();

  foreach($value as $keyP => $valueP) {
    echo "$keyP";

    $Total += $valueP;

    if ($valueP > 1) echo "($valueP)";

    $MoreThen[$valueP]++;

    echo ", ";
  }

  echo " <-[$Total]";

  if ($MoreThen[2]) echo "-[(2)".$MoreThen[2]."]";
  if ($MoreThen[3]) echo "-[(3)".$MoreThen[3]."]";
  if ($MoreThen[4]) echo "-[(4)".$MoreThen[4]."]";
  if ($MoreThen[5]) echo "-[(5)".$MoreThen[5]."]";
}

echo "\r\n\r\nRodadas";

$Jogaram = array();

for ($x=1;$x<=36;$x++) {
  $Jogaram[$x] = array();
}

foreach($Rodada as $key => $value) {
  echo "\r\n$key=";
  $g=0;
  $Grupo = array();

  foreach($value as $keyP => $valueP) {
    echo "$keyP";

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
  echo "\r\n$key(".count($value).")=";
  $Total = 0;
  $MoreThen = array();

  foreach($value as $keyP => $valueP) {
    echo "$keyP";

    $Total += $valueP;

    if ($valueP > 1) echo "($valueP)";

    $MoreThen[$valueP]++;

    echo ", ";
  }

  echo " <-[$Total]";

  if ($MoreThen[2]) echo "-[(2)".$MoreThen[2]."]";
  if ($MoreThen[3]) echo "-[(3)".$MoreThen[3]."]";
  if ($MoreThen[4]) echo "-[(4)".$MoreThen[4]."]";
  if ($MoreThen[5]) echo "-[(5)".$MoreThen[5]."]";
}

die();
echo "\r\n\r\n";

foreach($Player as $key => $value) {
  echo "\r\n$key=";
  foreach($value as $keyP => $valueP) {
    echo "$keyP";

    if ($valueP) echo "($valueP)";

    echo ", ";
  }
}

echo "\r\n\r\n";

foreach($Pl as $key => $value) {
  echo "\r\n$key=";
  foreach($value as $keyP => $valueP) {
    if ($keyP) {
      echo "$keyP";

      if ($valueP > 1) echo "($valueP)";

      echo ", ";
    }
  }
}

die();
$P = array();

$Player = array();

for ($x = 1;$x<=36;$x++) {
  $Pl[$x] = array();

  $Pl[$x][0] = "Player $x";

  $Player[$x] = array();
}

for ($Ro = 1; $Ro <= 13; $Ro++) {
  echo "\r\n\r\n$Ro rodada\r\n";
  $P = 0;

  switch ($Ro) {
    case 2:
      $P  = 1;
      $PI = 4;
      break;

    case 3:
      $P  = 2;
      $PI = 4;
      break;

    case 4:
      $P  = 3;
      $PI = 4;
      break;

    case 5:
      $P  = 0;
      $PI = 12;
      break;

    case 6:
      $P  = 1;
      $PI = 12;
      break;

    case 7:
      $P  = 2;
      $PI = 12;
      break;

    case 8:
      $P  = 3;
      $PI = 12;
      break;

    case 9:
      $P  = 36;
      $PI = -4;
      break;

    case 10:
      $P  = 35;
      $PI = -4;
      break;

    case 11:
      $P  = 34;
      $PI = -4;
      break;

    case 12:
      $P  = 33;
      $PI = -4;
      break;

    case 13:
      $P  = 36;
      $PI = -12;
      break;

  default:
    $P = 0;
    $PI = 1;
    $PP = 0;

  }

  // echo "\r\nP=$P";
  // echo "\r\nPI=$PI";
  // echo "\r\nPP=$PP";
  echo "\r\n";

  $PlayerG = array();

  for ($j = 1; $j <= 36; $j++) {
    $P += $PI;

    if ($P > 36) $P = $P - 36 + 1;
    if ($P < 1) $P = $P + 36 - 1;

    echo $P;

    foreach($PlayerG as $key => $value) {
      $Player[$P][$value]++;
      $Player[$value][$P]++;
    }
    if ($P > 1) {
      $PlayerG[$P] = $P;
    } else {
      $PlayerG = array();
    }

    if ($P++ >= 3) {
      echo "   ";

      $P = 0;
    } else {
      echo ", ";
    }

    $R[1][$Ro] = $P;

    $blnOk = 1;

    for ($i = 1;$i <= $PG; $i++) {
      $PX = $AG[$i];

      if (($Pl[$PX][$P] || $Pl[$P][$PX]) && $P != $PX) $blnOk = 0;

      // echo "\r\n      $PN - AG[$i]=".$AG[$i]." - P[$PN][$PX]=".$Pl[$PN][$PX]." - $blnOk";;
    }

    if ($blnOk && $PA != $P) {
      $PG++;
      $AG[$PG] = $P;
      $strGrupo .= ",".$P;

      $P[$Ro][$PR] .= ",".$P;

      for ($i = 1;$i < $PG; $i++) {
        $PX = $AG[$i];

        $Pl[$P][$PX]++;
        $Pl[$PX][$P]++;
      }
    }
  }
}

echo "\r\n\r\n";

foreach($Player as $key => $value) {
  echo "\r\n$key=";
  foreach($value as $keyP => $valueP) {
    echo "$keyP";

    if ($valueP) echo "($valueP)";

    echo ", ";
  }
}

echo "\r\n\r\n";

foreach($Pl as $key => $value) {
  echo "\r\n$key=";
  foreach($value as $keyP => $valueP) {
    if ($keyP) {
      echo "$keyP";

      if ($valueP > 1) echo "($valueP)";

      echo ", ";
    }
  }
}

die();


$P = array();
$AG = array();
$PR = 0;

for ($PA = 1;$PA <= 37; $PA++) {
  // echo "\r\nPA=$PA";

  $PN = 0;

  $PR++;

  for ($R = 1; $R <= 13; $R++) {
    // echo "\r\n  R=$R";

    $PG = 1;
    $strGrupo = "";

    $P[$R][$PR] = $PA;

    $AG[1] = $PA;

    while ($PG < 4 && $PN < 36) {
      $PN++;
      // echo "\r\n    PG=$PG - PN=$PN";

      $blnOk = 1;

      for ($i = 1;$i <= $PG; $i++) {
        $PX = $AG[$i];

        if (($Pl[$PX][$PN] || $Pl[$PN][$PX]) && $PN != $PX) $blnOk = 0;

        // echo "\r\n      $PN - AG[$i]=".$AG[$i]." - P[$PN][$PX]=".$Pl[$PN][$PX]." - $blnOk";;
      }

      if ($blnOk && $PA != $PN) {
        $PG++;
        $AG[$PG] = $PN;
        $strGrupo .= ",".$PN;

        $P[$Ro][$PR] .= ",".$PN;

        for ($i = 1;$i < $PG; $i++) {
          $PX = $AG[$i];

          $Pl[$PN][$PX] = 1;
          $Pl[$PX][$PN] = 1;
        }
      }
    }

    if ($strGrupo) {
      echo "\r\n      ".$PA.$strGrupo;

      $P[$R][$PR] = $PA.$strGrupo;

    }
  }
}

die();

$PN = 1;
$strGrupo = $PN;
$P = 1;
$R = 1;
$PG = 1;

$PA = $PN;

echo "\r\n$R Rodada\r\n";

while ($R <= 13) {
  $PN++;

  if ($PN > 36) $PN = 1;

  $blnOk = 1;

  for ($i = 1;$i <= $PG; $i++) {
    $PX = $AG[$i];

    if (($Pl[$PX][$PN] || $Pl[$PN][$PX]) && $PN != $PX) $blnOk = 0;

    // echo "\r\n$PN - AG[$i]=".$AG[$i]." - P[$PN][$PX]=".$Pl[$PN][$PX]." - $blnOk";;
  }

  if ($blnOk && $PA != $PN) {
    $PG++;
    $AG[$PG] = $PN;
    $strGrupo .= ",".$PN;

    for ($i = 1;$i < $PG; $i++) {
      $PX = $AG[$i];

      $Pl[$PN][$PX] = 1;
      $Pl[$PX][$PN] = 1;
    }
  }

  if ($strGrupo && $PG > 3) {
    $PG = 1;
    echo $strGrupo." | ";
    $PN++;
    if ($PN > 36) $PN = 1;
    $strGrupo = $PN;
    $P++;
    $PA = $PN;

  }

  if ($P > 9) {
    $P = 1;
    $R++;
    echo "\r\n\r\n$R Rodada\r\n";
  }
}
die();

// $Pl[$x][1] = 1;

$intJogadores = 0;

for ($P1 = 1;$P1 <= 18;$P1++) {
  echo "\r\n\r\n$P1 Pessoa\r\n";

  for ($P = 1;$P <= 8;$P++) {
    echo "\r\n  $P grupo\r\n";

    $PG = 1;
    $strGrupo = $P1;

    $PN=0;

    $AG = array();

    $AG[$PG] = $P1;

    while ($PG < 4 && $PN < 36) {
      $PN++;

      $blnOk = 1;

      for ($i = 1;$i <= $PG; $i++) {
        $PX = $AG[$i];

        if (($Pl[$PX][$PN] || $Pl[$PN][$PX]) && $PN != $PX) $blnOk = 0;

        // echo "\r\n$PN - AG[$i]=".$AG[$i]." - P[$PN][$PX]=".$Pl[$PN][$PX]." - $blnOk";;
      }

      if ($blnOk && $P1 != $PN) {
        $PG++;
        $AG[$PG] = $PN;
        $strGrupo .= ",".$PN;

        for ($i = 1;$i < $PG; $i++) {
          $PX = $AG[$i];

          $Pl[$PN][$PX] = 1;
          $Pl[$PX][$PN] = 1;
        }
      }
    }
    if ($strGrupo) echo "    ".$strGrupo." ";
    // ob_flush(); flush();
  }
}
die();

  if (!$Pl[$P1][$PN] && $P1 != $PN) {
    $Pl[$P1][$PN] = 1;
    $PG++;
    $RG[$intRodada][$P][$PN]=1;
    $strGrupo .= ",".$PN;
  }

  for ($P1 = 1;$P1 < 37;$P1++) {
    echo "\r\n\r\n$P1 Pessoa\r\n";

    for ($intRodada = 1; $intRodada < 14; $intRodada++) {
      echo "\r\n$intRodada Rodada";

      while ($PG < 4 && $PN < 36) {
        $PN++;
        // echo "$PN, ";

        echo "\r\nP[$P1][$PN]=".$Pl[$P1][$PN];

        if (!$Pl[$P1][$PN] && $P1 != $PN) {
          $Pl[$P1][$PN] = 1;
          $PG++;
          $RG[$intRodada][$P][$PN]=1;
          $strGrupo .= ",".$PN;
        }
      }
      if ($strGrupo) echo $strGrupo." ";
    }
  }

?>