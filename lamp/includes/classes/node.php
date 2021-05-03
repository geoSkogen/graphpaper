<?php

class Node {

  public $id;
  public $refs;
  public $field;

  function __construct($id,$ref,$rel) {
    $this->id = $id;
    $this->refs = array( $ref => $rel );
    $this->field = array();
  }

}
?>
