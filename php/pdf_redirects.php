<?php

class PDF_Redirects {

  public $_from = array();
  public $_to = array();

  function __construct($data_array) {
    foreach ($data_array as $row) {
      $this->_from[] = $row[0];
      $this->_to[] = $row[1];
    }
  }

  public function get_dir_slugs($url_string,$content_dir) {
    $url_indexed = explode('/',$url_string);
    $content_index = ($content_dir) ? array_search($content_dir,$url_indexed) : 0;
    $result_arr = array();
    for ($i = $content_index+1; $i < count($url_indexed); $i++) {
      array_push($result_arr, $url_indexed[$i]);
    }
    return $result_arr;
  }

  public function get_mkdir_line($dir_slugs) {
    $result_str = 'mkdir ';
    $result_str .= '"';
    foreach ($dir_slugs as $slug) {
      $result_str .= $slug;
      $result_str .= '/';
    }
    $result_str .= '"';
    return $result_str;
  }

  public function get_mkdir_lines($content_dir) {
    $result_str = "";
    foreach($this->_from as $old_url) {
      $result_str .= $this->get_mkdir_line($this->get_dir_slugs($old_url,$content_dir));
      $result_str .= "\r\n";
    }
    return $result_str;
  }

  public function export_batch_commands($export_str, $filename, $dir_path) {
    file_put_contents("../" . $dir_path . "/" . $filename . ".bat" , $export_str);
  }

}

?>
