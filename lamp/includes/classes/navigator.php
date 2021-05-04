<?php

class Navigator {

  public $map;
  public $link_index;

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
    $link_index = array( 'a'=> [], 'b'=> [] );
    $this->map = new Map($roll);
  }


  public function ref_locate($point_a, $point_b) {
    $result = [];

    if (in_array($point_a,array_keys($this->map->nodes[$point_b]->refs))) {

      $result[] =  $point_a;
    }
    return count($result) ? $result : [];
  }


  public function field_locate($haystack,$needle,$arg) {
    $result = [];
    foreach($this->map->nodes[$haystack]->field as $key => $arr) {

      if (in_array($needle,$arr)) {

        $result = $arg ? [$haystack,$key,$needle] : [$needle,$key,$haystack];
      }
    }
    return count($result) ? $result : [];
  }


  public function audit($haystack, $needle) {
    $result = [];

    $result = $this->ref_locate($haystack,$needle);

    $result = (!count($result)) ?
      $this->field_locate($haystack,$needle,true) : $result;
      
    $result = (!count($result)) ?
      $this->field_locate($needle,$haystack,false) : $result;

    return count($result) ? $result : false;
  }
}

?>
