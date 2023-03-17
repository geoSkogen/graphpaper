<?php

include_once 'Junction.php';

class Map {

  protected array $raw_data;
  protected array $junctions;
  protected array $paths;

  public function __construct(array $data) {
    $this->raw_data = $data;
    $this->junctions = [];
    $this->paths = [];
    $this->current_junction = null;
    $this->createNodeList($data);
    //$this->mapTestPattern();
  }

  protected function createNodeList(array $data) {
    foreach ($data as $row_index => $path_arr) {

      $point_a = $path_arr[0];
      $point_b = $path_arr[1];
      $port_key = $path_arr[2];

      if (isset($this->junctions[$point_a])) {
        $this->junctions[strval($point_a)]->addPorts([$point_b=>$port_key]);
      } else {
        $this->junctions[strval($point_a)] = new Junction($point_a,[$point_b=>$port_key]);
      }

      if (isset($this->junctions[$point_b])) {
        $this->junctions[strval($point_b)]->addPorts([$point_a=>$port_key]);
      } else {
        $this->junctions[strval($point_b)] = new Junction($point_b,[$point_a=>$port_key]);
      }
    }
  }

  public function getJunctions() {
    return $this->junctions;
  }

  public function getJunctionByName(string $name) {
    return $this->junctions[strval($name)];
  }

  public function getPortConnection(string $port_a_name, string $port_b_name) {
    $result = null;
    print("__LOCATE: {$port_a_name},{$port_b_name}\r\n\r\n");
    print("Get Port Connection: Is $port_a_name in the ports of $port_b_name ");
    print_r($this->junctions[$port_b_name]->getPorts());
    print("?\r\n");
    if ($this->junctions[$port_a_name] && $this->junctions[$port_b_name]) {
      if (in_array($port_a_name,$this->junctions[$port_b_name]->getPorts())) {
        print("|__Found common port: $port_a_name in\r\n");
        print_r($this->junctions[$port_b_name]->getPorts());
        print("of $port_a_name and $port_b_name");
        $result = [$port_a_name,$port_b_name];
      } else {
        print("If not, does $port_a_name share a port with $port_b_name?\r\n");
        $common_ports = array_intersect(
          $this->junctions[$port_a_name]->getPorts(),
          $this->junctions[$port_b_name]->getPorts()
        );
        if (count($common_ports)) {
          $first_common_port = $common_ports[array_keys($common_ports)[0]];
          print("|__Found common port $first_common_port in\r\n");
          print_r($common_ports);
          print("of $port_a_name and $port_b_name");
          $result = [$port_a_name,$first_common_port,$port_b_name];
        }
      }
    }
    return $result;
  }

  public function mapTestPattern() {
    foreach ($this->junctions as $keyname => $junction) {
      print("\r\n|_________\r\n");
      print("I am junction " . $junction->getName() . "\r\n");
      print("My ports are: \r\n");
      foreach($junction->getPorts() as $port_name) {
        print($port_name . "\r\n");
      }
    }
  }

}

?>
