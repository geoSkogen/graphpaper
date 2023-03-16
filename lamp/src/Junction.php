<?php

class Junction {

  protected string $name;
  protected array $port_names;
  protected array $port_keys;

  public function __construct(string $name, array $port_data = null) {
    $this->port_names = [];
    $this->port_keys = [];
    $this->name = $name;
    $this->validatePorts($port_data);
  }


  protected function validatePorts(array $port_names_input) {
    foreach ($port_names_input as $name_input => $port_key_input) {
      if (intval($name_input)) {
        $this->port_names[] = strval(intval($name_input));
        $this->port_keys[$name_input] = strval($port_key_input);
      }
    }
  }


  public function addPorts(array $port_names_input) {
    $this->validatePorts($port_names_input);
  }


  public function getPorts() {
    return $this->port_names;
  }


  public function getPortKeyByName(string $input_string) {
    if (isset($this->port_keys[$input_string])) {
      return $this->port_keys[$input_string];
    }
  }


  public function getName() {
    return $this->name;
  }
}

?>
