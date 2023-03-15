<?php
include_once 'Map.php';

class Navigator {

  public Map $map;
  public string $a;
  public string $b;
  public array $paths_b;
  public array $paths_a;

  protected mixed $using_junction_a;
  public array $current_junctions_a;
  public array $next_junctions_a;

  public array $current_ports_a;
  public int $port_pointer_a;

  protected mixed $using_junction_b;
  public array $current_junctions_b;
  public array $next_junctions_b;

  public array $current_ports_b;
  public int $port_pointer_b;

  public int $iteration_index;
  public bool $direction;
  public bool $reverse_counter;
  public mixed $result;


  public function __construct(Map $map) {
    $this->map = $map;
    $this->a = '';
    $this->b = '';
    $this->paths_a = [];
    $this->paths_b = [];
    $this->iteration_index = 0;
    $this->direction = true;
    $this->reverse_counter = 0;
    $this->result = null;

    $this->using_junction_a = null;
    $this->current_junctions_a = [];
    $this->next_junctions_a = [];
    $this->junction_pointer_a = 0;

    $this->current_ports_a = [];
    $this->port_pointer_a = 0;

    $this->using_junction_b = null;
    $this->current_junctions_b = [];
    $this->next_junctions_b = [];
    $this->junction_pointer_b = 0;

    $this->current_ports_b = [];
    $this->port_pointer_b = 0;
  }


  public function getPoint() {
    if ($this->direction) {
      return 'a';
    } else {
      return 'b';
    }
  }

  public function reverseDirection() {
    $this->direction = !$this->direction;
    $this->reverse_counter++;
    if ($this->reverse_counter>1) {
      return 1;
    } else {
      return 0;
    }
  }


  public function setCurrentJunction(string $name) {
    $this->{'using_junction_'. $this->getPoint()} = $this->map->getJunctionByName($name);
    $this->{'current_ports_' . $this->getPoint()} = $this->{'using_junction_'. $this->getPoint()}->getPorts();
  }


  public function getNextPort() {
    $result = isset($this->{'current_ports_' . $this->getPoint()}[ $this->{'port_pointer_' . $this->getPoint()} ]) ?
      $this->{'current_ports_' . $this->getPoint()}[ $this->{'port_pointer_' . $this->getPoint()} ] : '';
    if ($result) {
      $this->{'port_pointer_' . $this->getPoint()}++;
    } else {
      if ( $this->{'port_pointer_' . $this->getPoint()} >= count($this->{'current_ports_' . $this->getPoint()})) {
        $this->{'port_pointer_' . $this->getPoint()} = 0;
      }
    }
    return $result;
  }


  public function getNextJunction() {
    print("current junctions\r\n");
    print_r($this->{'current_junctions_' . $this->getPoint()});
    print_r("calling junction index: " .   strval($this->{'junction_pointer_' . $this->getPoint()}) . "\r\n");
    $result = isset($this->{'current_junctions_' . $this->getPoint()}[ $this->{'junction_pointer_' . $this->getPoint()} ]) ?
      $this->{'current_junctions_' . $this->getPoint()}[ $this->{'junction_pointer_' . $this->getPoint()} ] : '';
    if ($result) {
      $this->{'junction_pointer_' . $this->getPoint()}++;
    } else {
      if ( $this->{'junction_pointer_' . $this->getPoint()} >= count($this->{'current_junctions_' . $this->getPoint()})) {
        $this->{'junction_pointer_' . $this->getPoint()} = 1;
      }
    }
    return $result;
  }


  public function nextDrilldown() {
    if ($next_port = $this->getNextPort()) {
      print("next port\r\n");
      $this->updatePortPaths($next_port);
    } else {
      if ($junction_name = $this->getNextJunction()) {
        print("\r\ncurrent junction: ");
        print($junction_name . "\r\n");
        $this->setCurrentJunction($junction_name);
      } else {
        print("\r\nswitch current direction\r\n");
        $this->{'current_junctions_' . $this->getPoint()} = $this->{'next_junctions_' . $this->getPoint()};
        $this->{'next_junctions_' . $this->getPoint()} = [];
        $this->setCurrentJunction( $this->{'current_junctions_' . $this->getPoint()}[0] );
        print("next current junction: ");
        print($this->{'current_junctions_' . $this->getPoint()}[0] . "\r\n");
        $this->iteration_index+= $this->reverseDirection();
      }
      print("next port\r\n");
      $next_port = $this->getNextPort();
      $this->updatePortPaths($next_port);
    }
    return $next_port;
  }


  public function updatePortPaths(string $next_port) {
    if (isset($this->{'paths_' . $this->getPoint()}[$this->iteration_index-1])) {
      foreach($this->{'paths_' . $this->getPoint()}[$this->iteration_index-1] as $path_arr) {

        if (end($path_arr)===$this->{'using_junction_'. $this->getPoint()}->getName()) {

          $backward = false;

          if (!in_array($next_port,$path_arr)) {
            $path_arr[] = $next_port;
          } else {
            $backward = true;
          }

          if (!in_array($next_port,$this->{'next_junctions_' . $this->getPoint()})) {
            $this->{'next_junctions_' . $this->getPoint()}[] = $next_port;
          }

          if (!$backward) {
            if (!isset($this->{'paths_' . $this->getPoint()}[$this->iteration_index])) {
              $this->{'paths_' . $this->getPoint()}[$this->iteration_index] = [];
            }

            $this->{'paths_' . $this->getPoint()}[$this->iteration_index][] = $path_arr;
          }
        }
      }
    }
  }


  public function locate(string $input_point_start, string $input_point_end) {

    $this->a = $input_point_start;
    $this->b = $input_point_end;
    $var_point_start = $input_point_start;
    $var_point_end = $input_point_end;
    $this->setCurrentJunction($input_point_start);
    $this->junction_pointer_a = 1;
    $this->reverseDirection();
    $this->setCurrentJunction($input_point_end);
    $this->junction_pointer_b = 1;
    $this->reverseDirection();
    $this->current_junctions_a = [$input_point_start];
    $this->current_junctions_b = [$input_point_end];
    $this->paths_a[] = [[$input_point_start]];
    $this->paths_b[] = [[$input_point_end]];
    $this->iteration_index = 1;

    $new_input_point_start = $this->nextDrilldown();
    print($new_input_point_start . "\r\n");
    $new_input_point_start = $this->nextDrilldown();
    print($new_input_point_start . "\r\n");
    $new_input_point_start = $this->nextDrilldown();
    print($new_input_point_start . "\r\n");
    $new_input_point_start = $this->nextDrilldown();
    print($new_input_point_start . "\r\n");
    //print_r($this->paths_a);
    //print_r($this->next_junctions_a);
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    //print_r($this->paths_b);
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");
    $new_input_point_end = $this->nextDrilldown();
    print($new_input_point_end . "\r\n");


    /*
    while (!$this->map->getPortConnection($var_point_start,$var_point_end)) {


    }
    */
  }
}

?>
