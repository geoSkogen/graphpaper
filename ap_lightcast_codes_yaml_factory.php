<?php

include 'php/includes/Util/Schema.php';

$program_codes_schema = new Schema('../../../records/lightcast-program-codes-2024-05-28');
$program_codes_table = $program_codes_schema->getTable();
$widget_code_column_index = $argv[2]==='iframe' ? 7 : 6;

print_r($argv);

print_r("\r\nWCCI:\r\n" . $widget_code_column_index . "\r\n");

foreach($program_codes_table as $row_index => $program_codes_row) {
  $widget_code = '';
  $program_title = isset($program_codes_row[0]) ? $program_codes_row[0] : '';
  $program_code = isset($program_codes_row[4]) ? $program_codes_row[4] : '';
  $program_path = '';

  if (isset($program_codes_row[$widget_code_column_index])) {
    preg_match('/program\-widget\-(\w|\d)+/',$program_codes_row[$widget_code_column_index],$matches);
    if ($matches[0]) {
      $match_arr = explode('-',$matches[0]);
      $widget_code = end($match_arr);
    }
  } else {
    print('__No match for widget code, row: ' . strval($row_index) . "\r\n");
  }

  if (isset($program_codes_row[2])) {
    preg_match('/(under)?graduate\/(\w|\-)+/',$program_codes_row[2],$matches);
    $program_path = isset($matches[0]) ? $matches[0] : '';
  }
  if (!$program_path) {
    print('__No match for program_path, row: ' . strval($row_index) . "\r\n");
    print('__Raw URL: ' . $program_codes_row[2] . "\r\n");
  } else {
    $degree_type = substr($program_path,0,strpos($program_path,'/'));
    if ($degree_type) {
      if ($degree_type!=$argv[1]) {
        continue;
      }
    } else {
      print('__No program_path parent directory found, row: ' . strval($row_index) . "\r\n");
    }
  }

  if ($widget_code && $program_code && $program_title && $program_path) {
    print("$program_path:\r\n");
    print("  program_title: '$program_title'\r\n");
    print("  program_code: '$program_code'\r\n");
    print("  widget_code: '$widget_code'\r\n");
  } else {
    print('__EXCLUDED ROW: ' . strval($row_index) . "\r\n");
  }

}
?>
