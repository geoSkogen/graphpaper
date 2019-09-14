<?php

class Schema {

  public $data_index = array();
  public $data_assoc = array();
  public $labeled_columns = array();
  public $labeled_rows = array();

  function __construct($filename, $path) {
    $this->data_index = $this->import_csv_index($filename, $path);
    $this->data_assoc = $this->import_csv_assoc($filename, $path);
    $this->labeled_columns = $this->get_labeled_columns($this->data_index);
    $this->labeled_rows = $this->get_labeled_rows($this->data_index);
  }

  public function import_csv_index($filename, $path) {
    $result = array();
    if (($handle = fopen(__DIR__ . "/" . $path . "/" . $filename . ".csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $result[] = $data;
      }
      fclose($handle);
    //  error_log("Lookup found " . strval(sizeof($result)) . " rows of data");
      return $result;
    } else {
      error_log('could not open file');
      return false;
    }
  }

  public function import_csv_assoc($filename, $path) {
    $row_index = 0;
    $cell_index = 0;
    $keys = array();
    $result = array();
    $labeled_data = array();
    if (($handle = fopen(__DIR__ . "/" . $path . "/" . $filename . ".csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row_index === 0) {
            foreach ($data as $key) {
              $keys[] = $key;
            }
        } else {
          foreach ($data as $item) {
            if ($cell_index === sizeof($data))  { $cell_index = 0; }
            $labeled_data[$keys[$cell_index]] = $item;
            $cell_index++;
          }
          array_push($result, $labeled_data);
        }
        $row_index++;
      }
      fclose($handle);
      return $result;
    } else {
      error_log('could not open file');
      return false;
    }
  }

  public function get_labeled_columns($data_arr) {
    $keys = [];
    $result = array();
    for ($row_index = 0; $row_index < count($data_arr); $row_index++) {
      for ($i = 0; $i < count($data_arr[$row_index]); $i++) {
        if ($row_index === 0) {
          $result[strval($data_arr[$row_index][$i])] = array();
          array_push($keys, $data_arr[$row_index][$i]);
        } else {
          if ($data_arr[$row_index][$i]) {
            array_push($result[$keys[$i]],$data_arr[$row_index][$i]);
          }
        }
      }
    }
    return $result;
  }

  public function get_labeled_rows($data_arr) {
    $key = "";
    $valid_data = [];
    $result = array();
    for ($row_index = 0; $row_index < count($data_arr); $row_index++) {
      for ($i = 0; $i < count($data_arr[$row_index]); $i++) {
        $valid_data = [];
        if ($i === 0) {
          $key = strval($data_arr[$row_index][$i]);
        } else {
          if ($data_arr[$row_index][$i]) {
            array_push($valid_data,$data_arr[$row_index][$i]);
          }
          if ($i === count($data_arr[$row_index])-1) {
            $result[$key] = $valid_data;
          }
        }
      }
    }
    return $result;
  }

  public function lookup_val($keyname, $id) {
    $result = false;
    if ($id != 0) {
      $key_index = array_search($keyname, $this->data_index[0]);
      if ($key_index || $key_index === 0) {
        $result = $this->data_index[$id][$key_index];
      }
    }
    return $result;
  }

  public function make_export_str($data) {
    $export_str = "";
    $keys = array();
    for ($i = 0; $i < count($data); $i++) {
      $keys = array_keys($data[$i]);
      for ($ii = 0; $ii < count($keys); $ii++) {
        $export_str .= $keys[$ii];
        $export_str .= ",";
        $export_str .= $data[$i][$keys[$ii]];
        $export_str .= ($ii === count($keys)-1) ? "\r\n" : ",";
      }
    }
    return $export_str;
  }

  public function export_csv($export_str, $filename, $dir_path) {
    file_put_contents("../" . $dir_path . "/" . $filename . ".csv" , $export_str);
  }

}
