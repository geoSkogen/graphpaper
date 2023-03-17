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
  public int $reverse_counter;
  public mixed $result;

  public string $test_junction_names_pointer_a;
  public string $test_junction_names_pointer_b;



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

    $this->test_junction_names_pointer_a = 0;
    $this->test_junction_names_pointer_b = 0;

    $this->test_junction_names_counter_a = 0;
    $this->test_junction_names_counter_b = 0;

  }


  public function getPoint() {
    if ($this->direction) {
      return 'a';
    } else {
      return 'b';
    }
  }


  public function getReversePoint() {
    if ($this->direction) {
      return 'b';
    } else {
      return 'a';
    }
  }


  public function reverseDirection() {
    $this->direction = !$this->direction;
    $this->reverse_counter+=1;
    print("reverse counter has been set to: " . strval($this->reverse_counter) . "\r\n");
    if ($this->reverse_counter>1) {
      print("second consecutive call to reverse -- returning 1\r\n");
      $this->reverse_counter = 0;
      return 1;
    } else {
      print("first call to reverse -- or reset -- returning 0\r\n");
      return 0;
    }
  }


  public function setCurrentJunction(string $name) {
    $this->{'using_junction_'. $this->getPoint()} = $this->map->getJunctionByName($name);
    $this->{'current_ports_' . $this->getPoint()} = $this->{'using_junction_'. $this->getPoint()}->getPorts();
  }


  public function getNextPort() {
    print("using junction: " . $this->{'using_junction_'. $this->getPoint()}->getName() . "\r\n");
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
    print("Get Next Junction - of current junctions ");
    print_r($this->{'current_junctions_' . $this->getPoint()});
    $result = isset($this->{'current_junctions_' . $this->getPoint()}[ $this->{'junction_pointer_' . $this->getPoint()} ]) ?
      $this->{'current_junctions_' . $this->getPoint()}[ $this->{'junction_pointer_' . $this->getPoint()} ] : '';
    if ($result) {
      print_r("calling junction index: " .   strval($this->{'junction_pointer_' . $this->getPoint()}) . "\r\n");
      $this->{'junction_pointer_' . $this->getPoint()}++;
    } else {
      print_r("this was the last junction in the iteration\r\n");
      if ( $this->{'junction_pointer_' . $this->getPoint()} >= count($this->{'current_junctions_' . $this->getPoint()})) {
        $this->{'junction_pointer_' . $this->getPoint()} = 0;
      }
    }
    return $result;
  }

  public function getNextTestJunctionName() {
    if (!$this->{'test_junction_names_counter_'  . $this->getPoint()}>=count($this->{'current_junctions_' . $this->getPoint()})) {
      print("Get Next Test Junction - of reverse direction's current junctions ");
      print_r($this->{'current_junctions_' . $this->getReversePoint()});
      $result = isset($this->{'current_junctions_' . $this->getReversePoint()}[ $this->{'test_junction_names_pointer_' . $this->getPoint()} ]) ?
        $this->{'current_junctions_' . $this->getReversePoint()}[ $this->{'test_junction_names_pointer_' . $this->getPoint()} ] : '';
      if ($result) {
        print_r("calling junction index: " .   strval($this->{'test_junction_names_pointer_' . $this->getPoint()}) . "\r\n");
        $this->{'test_junction_names_pointer_' . $this->getPoint()}++;
      } else {
        if ( $this->{'test_junction_names_pointer_' . $this->getPoint()} >= count($this->{'current_junctions_' . $this->getReversePoint()})) {
          $this->{'test_junction_names_pointer_'  . $this->getPoint()} = 0;
          $this->{'test_junction_names_counter_'  . $this->getPoint()}++;
        }
      }
    } else {
      $result = '';
    }
    if (!$result) {
      print("The test returned false\r\n");
    }
    return $result;
  }

  public function nextTest() {
    return $next_junction = $this->getNextTestJunctionName();
  }



  public function nextDrilldown() {
    if ($next_port = $this->getNextPort()) {
      print("Next port\r\n");
      $this->updatePortPaths($next_port,true);
    } else {
      if ($junction_name = $this->getNextJunction()) {
        print("\r\nNext junction: ");
        print($junction_name . "\r\n");
        $this->setCurrentJunction($junction_name);
        print("Next port\r\n");
        $next_port = $this->getNextPort();
        $this->updatePortPaths($next_port,true);
      } else {
        print_r($this->{'current_junctions_' . $this->getPoint()});
        print("The counter is: " . strval($this->{'test_junction_names_counter_'  . $this->getPoint()}) . "\r\n");

        print("\r\nSwitch current direction\r\n");
        $this->{'current_junctions_' . $this->getPoint()} = $this->{'next_junctions_' . $this->getPoint()};
        $this->{'next_junctions_' . $this->getPoint()} = [];
        $this->setCurrentJunction( $this->{'current_junctions_' . $this->getPoint()}[0] );
        print("the first junction in this direction's next iteration will be: ");
        print($this->{'current_junctions_' . $this->getPoint()}[0] . "\r\n");
        $this->{'test_junction_names_counter_'  . $this->getPoint()} = 0;
        $this->iteration_index += $this->reverseDirection();
        print("the iteration index is " . strval($this->iteration_index) . "\r\n");
        print("iterating first reverse junction\r\n");
        $this->setCurrentJunction($this->{'current_junctions_' . $this->getPoint()}[0]);
        $next_port = $this->getNextPort();
        $this->updatePortPaths($next_port,true);
      }
    }
    return $next_port;
  }


  public function updatePortPaths(string $next_port,bool $add_to_next) {
    if (isset($this->{'paths_' . $this->getPoint()}[$this->iteration_index-1])) {
      foreach($this->{'paths_' . $this->getPoint()}[$this->iteration_index-1] as $path_arr) {
        print("Update port path with $next_port?\r\n");
        print("is end of ");
        print_r($path_arr);
        print("equal to " . $this->{'using_junction_'. $this->getPoint()}->getName() . "?\r\n");
        if (end($path_arr)===$this->{'using_junction_'. $this->getPoint()}->getName()) {

          $backward = false;

          if (!in_array($next_port,$path_arr)) {
            $path_arr[] = $next_port;
            print("updated path array with $next_port \r\n");
          } else {
            $backward = true;
          }

          if (!$backward) {
            if (!in_array($next_port,$this->{'next_junctions_' . $this->getPoint()}) && $add_to_next) {
              print("updated next junctions array with $next_port\r\n");
              $this->{'next_junctions_' . $this->getPoint()}[] = $next_port;
            }

            if (!isset($this->{'paths_' . $this->getPoint()}[$this->iteration_index])) {
              $this->{'paths_' . $this->getPoint()}[$this->iteration_index] = [];
            }
            if (!in_array($path_arr,$this->{'paths_' . $this->getPoint()}[$this->iteration_index])) {
              $this->{'paths_' . $this->getPoint()}[$this->iteration_index][] = $path_arr;
            }
          }
        }
      }
    }
  }


  public function getResultPath() {
    print("\r\nPATHS A:\r\n");
    print_r($this->paths_a);
    print("PATHS B:\r\n");
    print_r($this->paths_b);
    print_r("PORT JUNCTION:\r\n");
    print_r($this->result);
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
    $loop_limit = 35;

    $loop_iterator = 0;

    while (!$this->result = $this->map->getPortConnection($var_point_start,$var_point_end)) {

      $current_start_point = $var_point_start;
      $current_end_point = $var_point_end;
      $current_drilldown = '';
      $current_test = '';

      if (!$current_test = $this->nextTest()) {
        $current_drilldown = $this->nextDrilldown();
        $current_test = $this->nextTest();
        if (!$current_test) {
          $current_test = $this->direction ? $current_end_point : $current_start_point;
        }
        $var_point_start = $this->direction ? $current_drilldown : $current_test;
        $var_point_end = $this->direction ? $current_test : $current_drilldown;
        print("TEST ROUND COMPLETED, Drilling Down -- drillown:$current_drilldown , next-test: $current_test  ");
      } else {

        $var_point_start = $this->direction?  $current_start_point : $current_test;
        $var_point_end = $this->direction? $current_test : $current_end_point;
        print("TEST METRIC FOUND -- start:$var_point_start  ,end:$var_point_end\r\n");
      }
      $loop_iterator++;
      if ($loop_iterator>=$loop_limit) {
        break;
      }
    }
    if ($this->result) {
      return $this->getResultPath();
    }
  }
}

?>
