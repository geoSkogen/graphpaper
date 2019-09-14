<?php

class Content_Monster {

  public $_cmds = [];
  public $_strs = [];
  public $_format = '';
  public $_default_body = '';
  public $_DIR = '';


  function __construct($cmd_arr,$filename_arr,$format,$default,$content_dir) {
    $this->_cmds = $cmd_arr;
    $this->_strs = $filename_array;
    $this->_format = $format;
    $this->_default_body = $default;
    $this->__DIR = $content_dir;
  }

  public function body_builder($index_num) {
    $result_str = $this->default_body;
    if ($index > -1) {
      error_log('body builder says ' . strval($index));

    }
  }

  public function filename_builder($index_num) {

  }


}

?>
