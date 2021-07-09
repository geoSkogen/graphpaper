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
    $this->field_index = array('a'=>-1,'b'=>-1);
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
    error_log(print_r($this->map->nodes[$haystack]->field, true) ); 
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
    //for ($i = 0; $i < 12; $i++) {
      $point_a = $points['a'];
      $point_b = $points['b'];

      if (!$count) {
        $a_end = end($this->path_tracer['a']);
        $b_end = end($this->path_tracer['b']);

        if ($a_end!=$point_a) {
          $this->path_tracer['a'][] = $point_a;
        }

        if ($b_end!=$point_b) {
          $this->path_tracer['b'][] = $point_b;
        }
      }

      $link_point = ($this->dir) ? $point_a : $point_b;
      $static_point = (!$this->dir) ? $point_a : $point_b;
      $link_tracer_prop = ($this->dir) ? 'a' : 'b';
      $link_tracer_pos = count($this->link_tracer[$link_tracer_prop]);

      $this->link_tracer[$link_tracer_prop][] = [];

      $last_crawl_index = (!empty($this->link_tracer[$link_tracer_prop][$link_tracer_pos-1])) ?
        count($this->link_tracer[$link_tracer_prop][$link_tracer_pos-1]) : 0;

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
                  print("LINK tracer--final iteration:");
                  print("\r\n");
                  //$collections_a = end($this->link_tracer['a']);
                  //$collections_b = end($this->link_tracer['b']);
                  //$collection_a = (is_array($collections_a)) ? end($collections_a) : ['&Omega;'];
                  //$collection_b = (is_array($collections_b)) ? end($collections_b) : ['&Omega;'];
                  print("\r\n");
                  print('A');
                  print("\r\n");
                  //print_r($collection_a);
                  print_r($this->link_tracer['a']);
                  print("\r\n");
                  print('B');
                  print("\r\n");
                  //print_r($collection_b);
                  print_r($this->link_tracer['b']);
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

        foreach(['a','b'] as $loop_tracer_prop) {
          $this->field_index[$loop_tracer_prop]++;
          print("tracer prop:\r\n");
          print($loop_tracer_prop);
          print("\r\n");
          print("field list index: \r\n");
          print( $this->field_index[$loop_tracer_prop] );
          print("\r\n");
          print("field list length: \r\n");
          print( count($this->field_list[$loop_tracer_prop]) );
          print("\r\n");
          print("crawl iteration index:");
          print("\r\n");
          print(  $link_tracer_pos );
          print("\r\n");
          print("index of previous crawl path array:");
          print("\r\n");
          print(  $last_crawl_index );
          print("\r\n");
          print("most recent crawl path:");
          print("\r\n");
          print_r(  $this->link_tracer[$loop_tracer_prop][$link_tracer_pos][$last_crawl_index] );
          print("\r\n");
          if ($this->field_index[$loop_tracer_prop]>=count($this->field_list[$loop_tracer_prop])) {

            $this->field_list[$loop_tracer_prop] = [];
            $this->field_index[$loop_tracer_prop] = 0;

            print("\r\n");
            print("link point");
            print("\r\n");
            print(  $link_point );
            print("\r\n");

            print("\r\n");
            print_r("link tracer iterations:");
            print("\r\n");
            print(  count($this->link_tracer[$loop_tracer_prop]) );
            print("\r\n");

            foreach( $this->link_tracer[$loop_tracer_prop][$link_tracer_pos] as $link_arr ) {

              print("\r\n");
              print_r("link arrays in this link tracer iteration:");
              print("\r\n");
              print(  count($this->link_tracer[$loop_tracer_prop][$link_tracer_pos]) );
              print("\r\n");
              print("\r\n");
              print("EVAL: does the link point $points[$loop_tracer_prop] appear as index 0 of:");
              print("\r\n");
              print_r( $link_arr );
              print(" ?\r\n");

              if ( $link_arr[0]==$points[$loop_tracer_prop] &&
                   !in_array($link_arr[1],$this->field_list[$loop_tracer_prop]) ) {
                $this->field_list[$loop_tracer_prop][] = $link_arr[1];
              }

            }
          }
          print("\r\n");
          print("current crawl path:");
          print("\r\n");
          print_r(  $this->field_list[$loop_tracer_prop] );
          print("\r\n");
          // resets points a & b as the next item on
          $points[$loop_tracer_prop] = $this->field_list[$loop_tracer_prop][$this->field_index[$loop_tracer_prop]];
        }
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
