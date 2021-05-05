<?php

class Navigator {

  public $map;
  public $link_tracer;
  public $dir;
  public $field_list;
  public $field_index;
  public $path_tracer;

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
    $this->set_defaults();
  }

  public function set_defaults() {
    $this->dir = true;

    $this->field_list = array('a'=>[],'b'=>[]);
    $this->link_tracer = array('a'=>[],'b'=>[]);
    $this->path_tracer = array('a'=>[],'b'=>[]);
    $this->field_index = -1;
  }


  public function ref_locate($point_a, $point_b) {
    $result = [];

    if (in_array($point_a,array_keys($this->map->nodes[$point_b]->refs))) {

      $result = [$point_a,$point_b];
    }
    return count($result) ? $result : [];
  }


  public function field_locate($haystack,$needle,$arg) {
    $result = [];
    foreach($this->map->nodes[$haystack]->field as $key => $arr) {

      if (in_array($needle,$arr)) {

        $result[] = $arg ? [$haystack,$key,$needle] : [$needle,$key,$haystack];
      }
    }
    return count($result) ? $result : [];
  }

  public function locate($point_a, $point_b) {

    $points = array(
      'a' => $point_a,
      'b' => $point_b
    );

    $result = $this->eval($point_a,$point_b);
    $count = 0;


    while (!$result) {

      $point_a = $points['a'];
      $point_b = $points['b'];

      if (!$count) {
        $path_tracer_pos = count($this->path_tracer['a'])-1;

        if (
          (!empty($this->path_tracer['a'][$path_tracer_pos]) &&
            $this->path_tracer['a'][$path_tracer_pos]!=$point_a) ||
            ($path_tracer_pos<0)
        ) {
          $this->path_tracer['a'][] = $point_a;
        }

        if (
          (!empty($this->path_tracer['b'][$path_tracer_pos]) &&
          $this->path_tracer['b'][$path_tracer_pos]!=$point_b) ||
          ($path_tracer_pos<0)
        ) {
            $this->path_tracer['b'][] = $point_b;
        }
      }

      $link_point = ($this->dir) ? $point_a : $point_b;
      $static_point = (!$this->dir) ? $point_a : $point_b;
      $link_tracer_prop = ($this->dir) ? 'a' : 'b';
      $link_tracer_pos = count($this->link_tracer[$link_tracer_prop]);

      $this->link_tracer[$link_tracer_prop][] = [];

      foreach($this->map->nodes[$link_point]->field as $key => $arr ) {
        print("__$link_point=>$key" . "__\r\n");
        print("iterating: node $link_point field as in-field: $key\r\n");
        foreach($arr as $field_node) {
          if ($field_node != $link_point) {

            print("iterating ref-point: $key as in-field node: $field_node\r\n");
            foreach(array_keys($this->map->nodes[$field_node]->refs) as $outfield_node) {

              if ($key != $outfield_node) {
                $this->link_tracer[$link_tracer_prop][$link_tracer_pos][] = [$link_point,$key,$field_node,$outfield_node];

                print("iterating ref-points of $field_node | out-field-nodes of: $link_point as out-field node $outfield_node\r\n");
                print("EVAL: is $static_point within 1-2 nodes of $outfield_node ?\r\n");
                $result = $this->eval($static_point,$outfield_node);
                print("\r\n");
                print("LOOP");
                print("\r\n");
                if ($result) {
                  print("RESULT\r\n");
                  print_r($result);
                  print("\r\n");
                  print("\r\n");
                  print("PATH tracer:");
                  print("\r\n");
                  print_r($this->path_tracer);
                  print("\r\n");
                  print("\r\n");
                  print("LINK tracer:");
                  print("\r\n");
                  print_r($this->link_tracer);
                  $this->set_defaults();
                  return $result;
                }
              }
            }
          }
        }
      }
      $this->dir = !$this->dir;
      $count++;

      if ($count>1) {

        $count = 0;
        print("\r\n");
        print('LOOP CONFIG');
        print("\r\n");
        $this->field_index++;
        if ($this->field_index===count($this->field_list[$link_tracer_prop])) {
          $this->field_list[$link_tracer_prop] = [];
          $this->field_index = 0;
          foreach( $this->link_tracer[$link_tracer_prop][$link_tracer_pos] as $link_arr ) {
            if ($link_arr[0]===$link_point)
            $this->field_list[$link_tracer_prop][] = $link_arr[1];
          }
        }

        $points[$link_tracer_prop] = $this->field_list[$link_tracer_prop][$this->field_index];

      }

    }
    return $result;
  }


  public function eval($point_a, $point_b) {
    $result = [];

    $result = $this->ref_locate($point_a, $point_b);

    $result = (!count($result)) ?
      $this->field_locate($point_a, $point_b,true) : $result;

    $result = (!count($result)) ?
      $this->field_locate($point_b, $point_a,false) : $result;

    return count($result) ? $result : false;
  }
}

?>
