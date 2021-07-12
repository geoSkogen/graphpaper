<?php

$sm_bools = [
  '0'=>false,
  '1'=>true,
  '2'=>false,
  '9'=>false
];

$ranges = [
  //0
  function ($datum,$col_index) {
    return $datum > 43 ? 0 : $datum;
  },
  // 1
  function ($datum, $col_index) {
    return $datum;
  },
  // 2
  function ($datum,$col_index) {
    return $datum > 89 ? 35.5 : $datum;
  },
  // 3
  function ($datum, $col_index) {
    return $datum;
  },
  // 4
  function ($datum, $col_index) {
    return $datum > 20 ? 0 : $datum;
  },
  // 5
  function ($datum, $col_index) {
    return $datum > 20 ? 'mean_' . strval($col_index) : $datum;
  },
  // 6
  function ($datum, $col_index) {
    return $datum > 20 ? 'mean_' . strval($col_index) : $datum;
  },
  // 7
  function ($datum, $col_index) {
    return $datum > 4 ? 2 : $datum;
  },
  // 8
  function ($datum, $col_index) {
    return $datum > 4 ? 2 : $datum;
  },
  // 9
  function ($datum, $col_index) {
    return $datum > 4 ? 2 : $datum;
  },
  //10
  function ($datum, $col_index) {
    return $datum > 5 ? 3 : $datum;
  },
  // 11
  function ($datum, $col_index) {
    return $datum;
  },
  // 12
  function ($datum, $col_index) {
    return $datum > 12 ? 6.5 : $datum;
  },
  // 13
  function ($datum, $col_index) {
    return $datum > 26 ? 13.5 : $datum;
  },
  // 14
  function ($datum, $col_index) {
    return $datum > 7 ? 4 : $datum;
  },
  // 15
  function ($datum, $col_index) {
    return $datum > 7 ? 4 : $datum;
  },
  // 16
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum > 4) {
      $result = 2;
    }
    if ($datum < 1) {
      $result = 5;
    }
    return $result;
  },
  // 17
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 18
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 19
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 20
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 21
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 22
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 3) {
      $result = 2;
    }
    return $result;
  },
  // 23
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 5) {
      $result = 5;
    }
    return $result;
  },
  // 24
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1 || $datum > 24) {
      $datum = 'mean_' . strval($col_index);
    }
    return $datum;
  },
  // 25
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 4) ? 2.5 : $datum;
  },
  // 26
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 5) ? 3 : $datum;
  },
  // 27
  function ($datum, $col_index) {
    return ($datum < 0 || $datum > 168) ? 'mean_' . strval($col_index) : $datum;
  },
  // 28
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  4 : 0;
  },
  // 29
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  5 : 0;
  },
  // 30
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  2 : 0;
  },
  // 31
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  3 : 0;
  },
  // 32
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  1 : 0;
  },
  // 33
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  1 : 0;
  },
  // 34
  function ($datum, $col_index) {
    global $sm_bools;
    $bool = $sm_bools[$datum];
    return $bool ?  1 : 0;
  },
  // 35
  function ($datum, $col_index) {
    return $datum;
  },
  // 36
  function ($datum, $col_index) {
    return ($datum < 0 || $datum > 30) ? 0 : $datum;
  },
  // 37
  function ($datum, $col_index) {
    return ($datum > 4) ? 0 : $datum;
  },
  // 38
  function ($datum, $col_index) {
    $result = $datum;
    if ($datum < 1) {
      $result = 2;
    }
    if ($datum > 3) {
      $result = 3;
    }
    return $result;
  },
  // 39
  function ($datum, $col_index) {
    return ($datum > 7) ? 0 : $datum;
  },
  // 40
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 4) ? 2.5 : $datum;
  },
  // 41
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 4) ? 2.5 : $datum;
  },
  // 42
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 5) ? 3 : $datum;
  },
  // 43
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 5) ? 3 : $datum;
  },
  // 44
  function ($datum, $col_index) {
    return ($datum < 1 || $datum > 5) ? 3 : $datum;
  },
  // 45
  function ($datum, $col_index) {
    return $datum;
  },
  // 46
  function ($datum, $col_index) {
    return $datum;
  }
];

?>
