<?php

class Map {

  public $nodes;
  public $hubs;
  public $links;
  public $bounds;


  function __construct($nodes) {
    $this->nodes = $nodes;
    $this->hubs =[];
    $this->bounds = [];
    $this->links = [];
    $this->crawl();

  }

  public function crawl() {

    $labels = [null,'bounds','links'];
    $distro = [];
    foreach($this->nodes as $node) {

      if (empty($distro[count($node->refs)])) {
        $distro[count($node->refs)] = [$node->id];
      } else {
        $distro[count($node->refs)][] = $node->id;
      }

      $node->field = $this->node_field($node);
    }
    
    krsort($distro);

    foreach( $distro as $key => $arr) {
      if ($key > 2) {
        $this->hubs[] = $arr;
      } else {
        $this->{$labels[$key]} = $arr;
      }
    }
  }

  public function node_field($node) {
    $arr = array();
    foreach($node->refs as $ref => $val) {
      if (empty($arr[$ref])) {
        $arr[$ref] = array_keys($this->nodes[$ref]->refs);
      }
    }
    return $arr;
  }

}

?>
