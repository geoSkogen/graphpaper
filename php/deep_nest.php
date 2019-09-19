<?php

class DeepNest {

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
    foreach($this->_from as $url) {
      $result_str .= $this->get_mkdir_line($this->get_dir_slugs($url,$content_dir));
      $result_str .= "\r\n";
    }
    return $result_str;
  }

  public function get_nested_index_echo($dir_slugs,$filename_w_ext,$content) {
    $result_str = 'echo ';
    $result_str .= $content;
    $result_str .= ' > ';
    foreach ($dir_slugs as $slug) {
      $result_str .= $slug;
      $result_str .= '/';
    }
    $result_str .= $filename_w_ext;
    $result_str .= "\r\n";
    return $result_str;
  }

  public function get_nested_index_echoes($content_obj) {
    $result_str = "";
    $dir_slugs = [];
    $these_slugs = [];
    $dir_key = -1;
    $nest_key = -1;
    foreach ($this->_from as $source_url) {
      $dir_key = array_search($source_url, $this->_from);
      $these_slugs = [];
      $dir_slugs = $this->get_dir_slugs($source_url,$content_obj->_DIR);
      foreach ($dir_slugs as $slug) {
        array_push($these_slugs,$slug);
        $nest_key = array_search($slug, $dir_slugs);
        $these_files = ($content_obj->_files[$nest_key]) ?
          $content_obj->_files[$nest_key] : [false];
        $these_cmds = ($content_obj->_cmds[$nest_key]) ?
          $content_obj->_cmds[$nest_key] : [false];
        for ($i = 0; $i < count($these_files); $i++) {
          $filename_w_ext = $content_obj->filename_builder($these_files[$i]);
          $content = $content_obj->body_builder($these_cmds[$i]);
          $result_str .= $this->get_nested_index_echo($these_slugs,$filename_w_ext,$content);
        }
      }
    }
    return $result_str;
  }

  public function export_batch_commands($export_str, $filename, $dir_path) {
    file_put_contents("../" . $dir_path . "/" . $filename . ".bat" , $export_str);
  }

}

?>
