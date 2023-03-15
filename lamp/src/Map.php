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
    print("Is $port_a_name in ");
    print_r($this->junctions[$port_b_name]->getPorts());
    print("?\r\n");
    if ($this->junctions[$port_a_name] && $this->junctions[$port_b_name]) {
      if (in_array($port_a_name,$this->junctions[$port_b_name]->getPorts())) {
        print("found $port_a_name in");
        print_r($this->junctions[$port_b_name]->getPorts());
        $result = [$port_a_name,$port_b_name];
      } else {
        print("Does $port_a_name share a port with $port_b_name?\r\n");
        $common_ports = array_intersect(
          $this->junctions[$port_a_name]->getPorts(),
          $this->junctions[$port_b_name]->getPorts()
        );
        if (count($common_ports)) {
          print("found common port $common_ports[0] in");
          print_r($common_ports);
          print("of $port_a_name and $port_b_name");
          $result = [$port_a_name,$common_ports[0],$port_b_name];
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
