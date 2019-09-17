<?php

class ContentMonster {

  public $_cmds = [];
  public $_files = [];
  public $_format = '';
  public $_default_body = '';
  public $_DIR = '';

  function __construct($cmd_table,$filename_table,$format,$default_tx,$content_dir) {
    $this->_cmds = Schema::get_indexed_rows($cmd_table);
    $this->_files = Schema::get_indexed_rows($filename_table);
    $this->_format = $format;
    $this->_default_body = $default_txt;
    $this->_DIR = $content_dir;
  }

  public function body_builder($cmd) {
    $head_str = $this->head_builder();
    if ($cmd) {
      error_log('CMD: ' . $cmd);
      switch ($cmd) {
        case '301' :
          $body_str = 'header^(^"Location^: http^:^/^/lotuseaters^/saturn^_3^/^"^, true^, 301^)^;';
          break;
        default :
          $body_str = $this->_default_body;
          break;
      }
    } else {
      $body_str = $this->_default_body;
    }
    $foot_str = $this->foot_builder();
    return $head_str . $body_str . $foot_str;
  }

  public function head_builder() {
    $result = '';
    switch($this->_format) {
      case 'php' :
        $result = '^<^?php ';
        break;
      default :
    }
    return $result;
  }

  public function foot_builder() {
    $result = '';
    switch($this->_format) {
      case 'php' :
        $result = ' ^?^>';
        break;
    }
    return $result;
  }

  public function filename_builder($str) {
    $result = ($str) ? $str : 'index';
    $result .= '.';
    $result .= $this->_format;
    return $result;
  }

}

?>
