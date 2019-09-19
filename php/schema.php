<?php

class Schema {

  public $data_index = array();
  public $data_assoc = array();
  public $labeled_columns = array();
  public $labeled_rows = array();

  function __construct($filename, $path) {
    $this->data_index = $this->import_csv_index($filename, $path);
    $this->data_assoc = $this->import_csv_assoc($filename, $path);
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

  public static function get_labeled_columns($data_arr) {
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

  public static function get_labeled_rows($data_arr) {
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

  public static function get_indexed_rows($data_arr) {
    $key = "";
    $valid_data = [];
    $result = array();
    for ($row_index = 0; $row_index < count($data_arr); $row_index++) {
      for ($i = 0; $i < count($data_arr[$row_index]); $i++) {
        $valid_data = [];
        $key = $i;
        if ($data_arr[$row_index][$i]) {
          array_push($valid_data,$data_arr[$row_index][$i]);
        }
        if ($i === count($data_arr[$row_index])-1) {
          $result[$key] = $valid_data;
        }
      }
    }
    return $result;
  }

  public function table_lookup($col, $row) {
    $result = false;
    if ( ($col || $col === 0) && ($row || $row === 0) ){
      if ($this->data_index[$row][$col]) {
        $result = $this->data_index[$row][$col];
      }
    }
    return $result;
  }

  public static function make_export_str($data_table) {
    $export_str = "";
    foreach ($data_table as $data_row) {
      for ($i = 0; $i < count($data_row); $i++) {
        $export_str .= $data_row[$i];
        $export_str .= ($i === count($data_row)-1) ? "\r\n" : ",";
      }
    }
    return $export_str;
  }

  public static function export_csv($export_str, $filename, $dir_path) {
    file_put_contents("../" . $dir_path . "/" . $filename . ".csv" , $export_str);
  }

}
