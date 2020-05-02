<?php

function highestInArr($arr) {
  $highest = -1;
  for ($i = 0; $i < count($arr); $i++) {
    if ($arr[$i] > $highest) {
      $highest = $arr[$i];
    }
  }
  return $highest;
}

$my_arr = array(
  99,333,11,22,33,444,44,55,66,77,88
);

$index = highestInArr($my_arr);
error_log($index);

?>
