<?php

class Schema {

  protected array $data_index;
  protected array $data_assoc;
  protected array $labeled_columns;
  protected array $labeled_rows;
  protected string $export_str;


  function __construct(string $path) {
    $this->data_index = $this->importCsv($path);
    $this->data_assoc = [];
    $this->labeled_columns = [];
    $this->labeled_rows = [];
    $this->export_str = '';
  }


  public function importCsv(string $path) {
    $result = [];
    if (($handle = fopen(__DIR__ . "/" . $path . ".csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $result[] = $data;
      }
      fclose($handle);
      return $result;
    } else {
      error_log('could not open file');
      return [];
    }
  }


  public function getTable() {
    return $this->data_index;
  }


  public function loadCsv(string $path) {
    $this->data_index = $this->importCsv($path);
  }


  public function loadTable(array $data) {
    $result = [];
    foreach($data as $row_index => $data_row) {
      if (!is_array($data_row)) {
        $result[] = [$data_row];
      } else {
        $result[] = $data_row;
      }
    }
    if (count($result)) {
      $this->data_index = $result;
    }
  }


  public function getAssoc(bool $label_rows = false) {
    /* bool > false - assumes columns are labeled, returns indexed associative rows */
    /* bool > true - assumes columns and rows are labeled, returns 2D associative array */
    $result = [];
    $table_col_index = $label_rows ? 1 : 0;
    $keys = $this->data_index[0];
    for ($i = 1; $i < count($this->data_index); $i++) {
      $row = [];
      if (!empty($this->data_index[$i]) && is_array($this->data_index[$i])) {

        for ($col_index = $table_col_index; $col_index < count($this->data_index[$i]); $col_index++) {
          $row[ $keys[$col_index] ] = $this->data_index[$i][$col_index];
        }
        $row_key = ($label_rows) ? $this->data_index[$i][0] : $i;
        $result[$row_key] = $row;

      }
    }
    $this->data_assoc = $result;
    return $result;
  }


  public function getLabeledColumns() {
    $keys = [];
    $result = [];
    for ($row_index = 0; $row_index < count($this->data_index); $row_index++) {
      for ($i = 0; $i < count($this->data_index[$row_index]); $i++) {
        if ($row_index === 0) {
          $result[strval($this->data_index[$row_index][$i])] = [];
          array_push($keys, $this->data_index[$row_index][$i]);
        } else {
          if ($this->data_index[$row_index][$i]) {
            array_push($result[$keys[$i]],$this->data_index[$row_index][$i]);
          }
        }
      }
    }
    $this->labeled_columns = $result;
    return $result;
  }


  public function getLabeledRows() {
    $key = "";
    $valid_data = [];
    $result = [];
    foreach ($this->data_index as $row) {
      $key = $row[0];
      $valid_data = array_slice($row,1);
      $result[$key] = $valid_data;
    }
    $this->$labeled_rows = $result;
    return $result;
  }


  public function tableLookup(int $col, int $row) {
    $result = null;
    if ($this->data_index[$row][$col]) {
      $result = $this->data_index[$row][$col];
    }
    return $result;
  }


  public function getExportCSV(array $table) {
    $export_str = "";
    $data_table = $table ? $table : $this->data_index;
    foreach ($data_table as $data_row) {
      if (is_array($data_row)) {
        for ($i = 0; $i < count($data_row); $i++) {
          $staging_str = "";
          if (is_array($data_row[$i])) {
            $staging_str = implode(',',$data_row[$i]);
          } else {
            $staging_str = $data_row[$i];
          }
          $export_str .= '"' . $staging_str . '"';
          $export_str .= ($i === count($data_row)-1) ? "\r\n" : ",";
        }
      } else {
        $export_str .= '"' . $data_row . '"' . "\r\n";
      }
    }
    return $export_str;
  }


  public function getExportJSON(string $data_format = '') {
    $data = '';
    if ($data_format) {
      switch($data_format) {
        case 'index' :
        case 'indexed' :
        case 'table' :
          $data = $this->data_index;
          break;
        case 'rows' :
        case 'row' :
          $data = $this->getLabeledRows();
          break;
        case 'cols' :
        case 'col' :
        case 'columns'  :
        case 'column' :
          $data = $this->getLabeledColumns();
          break;
        default :
          $data = $this->getAssoc(false);
      }
    } else {
      $data = $this->data_assoc;
    }
    return json_encode($data);
  }


  public function exportCSV(string $path, array $data = null) {
    file_put_contents(__DIR__ . "/" . $path . ".csv" , $this->getExportCSV($data));
  }


  public function exportJSON(string $path) {
    file_put_contents(__DIR__ . "/" . $path . ".json" , $this->getExportJSON());
  }

}
