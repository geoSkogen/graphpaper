<?php

class Navigator {

  public $map;

  function __construct($data) {

    if (!class_exists('Node')) {
      include_once 'node.php';
    }
    if (!class_exists('Map')) {
      include_once 'map.php';
    }
    $roll = [];

    for ($i = 0; $i < count($data); $i++) {

      $row = $data[$i];

      if (!in_array($row[0],array_keys($roll))) {
        $node = new Node($row[0],$row[1],$row[2]);
        $roll[$row[0]] = $node;
      } else {
        $roll[$row[0]]->refs[$row[1]] = $row[2];
      }

      if (!in_array($row[1],array_keys($roll))) {
        $node = new Node($row[1],$row[0],$row[2]);
        $roll[$row[1]] = $node;
      } else {
        $roll[$row[1]]->refs[$row[0]] = $row[2];
      }

    }

    $this->map = new Map($roll);
  }

  public function locate($point_a, $point_b) {

  }


  public function audit($haystack, $needle) {
    $result = [];
    $link_index = 0;
    if (in_array($needle,array_keys($this->map->nodes[$haystack]->refs))) {

      $result[] = array( $needle => $this->map->nodes[$haystack]->refs[$needle] );

    } else {

      foreach($this->map->nodes[$haystack]->field as $key => $arr) {

        if (in_array($needle,$arr)) {

          $result[] = array( $needle => $this->map->nodes[$needle]->refs[$key] );
          $result[] = array( $key => $this->map->nodes[$haystack] );
        }
      }
    }
    return count($result) ? $result : false;
  }
}

?>
