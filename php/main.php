<?php

require 'schema.php';
require 'deep_nest.php';
require 'content_monster.php';

$new_schema = [];
$new_row = [];
$this_schema = new Schema('criteria-ids-ltd', '../records');
$zip_schema = new Schema('zipcodes', '../records');
$zip_lookup = array('zips'=>[], 'names'=>[]);
$index = '';
$found_name = '';
$zip_not_found = [];

foreach($zip_schema->data_index as $zip_row) {
  $zip_lookup['zips'][] = $zip_row[0];
  $zip_lookup['names'][] = ucwords(strtolower($zip_row[1]));
}
foreach ($this_schema->data_index as $data_row) {
  $region = '';
  $new_row = [];
  if (is_numeric($data_row[1])) {
    if ( array_search($data_row[1],$zip_lookup['zips']) ||
      array_search($data_row[1],$zip_lookup['zips']) === 0) {
      $index = array_search($data_row[1],$zip_lookup['zips']);
      $found_name =  $zip_lookup['names'][$index];
      $new_row = array(
        $data_row[0],
        $found_name,
        $data_row[2],
        $data_row[3],
        $data_row[4],
        $data_row[5],
        $data_row[6],
        $data_row[7],
        $data_row[8],
      );
      array_push($new_schema,$new_row);
    } else {
      $zip_not_found[] = $data_row[1];
    }
  } else {
    array_push($new_schema,$data_row);
  }

  /*
  switch($data_row[3]) {
    case 'Baltimore North Pest':
    case 'Arundel / Biddeford Atlantic Pest':
    case 'Buffalo Pest':
    case 'Reading Pest':
    case 'Pittsburgh Pest':
      $region = 'Northeast';
      break;
    case 'Virginia':
      $region = 'Southeast';
      break;
  }
  $new_row = array(
    $data_row[0],
    $data_row[1],
    $data_row[2],
    $data_row[3],
    $data_row[4],
    $region,
    $data_row[5]
  );
  */
}
error_log('not found :' . strval(count($zip_not_found)));
error_log(var_dump($zip_not_found));
$str = Schema::make_export_str($new_schema);
Schema::export_csv($str,'criteria-ids-ltd-nozip','exports');

/*
$this_schema = new Schema('geo3-csvnest-row', '../records');
$arr = $this_schema->data_index;
$i = 0;
$base_url = "'https://wordpress_1/page/";
$buffalo = [];
$pittsburgh = [];
$reading = [];
$baltimore = [];
$arundel = [];
$result = array();
$col = "";
$keys = [];

foreach ($arr as $row) {
  $url_w_query = $base_url . "?location=" . $row[0] . "',";
  switch($row[3]) {
    case 'Baltimore North Pest':      $baltimore[] = $url_w_query;
      break;
    case 'Arundel / Biddeford Atlantic Pest':
      $arundel[] = $url_w_query;
      break;
    case 'Buffalo Pest':
      $buffalo[] = $url_w_query;
      break;
    case 'Reading Pest':
      $reading[] = $url_w_query;
      break;
    case 'Pittsburgh Pest':
      $pittsburgh[] = $url_w_query;
      break;
  }
  $i++;
}
$result = array(
  'baltimore' => $baltimore,
  'arundel' => $arundel,
  'buffalo' => $buffalo,
  'reading' => $reading,
  'pittsburgh'=> $pittsburgh
);
$keys = array_keys($result);
foreach ($keys as $key) {
  for ($i = 0 ; $i < count($result[$key]); $i++) {
    $col .= $result[$key][$i] . "\r\n";
  }
  file_put_contents("../" . "exports" . "/" . $key . ".txt" , $col);
}
*/

/*
foreach($this_schema->data_index as $row) {
}
*/
//$this_str = Schema::make_export_str($new_csv_arr);
//Schema::export_csv($this_str,'locales-ltd-zip','exports');
/*
$cmd_schema = new Schema('cmds-data', '../records');
$c_monster = new ContentMonster(
  $cmd_schema->data_index,
  [],
  'php',
  "//Silence is Golden.",
  'content-dir'
);
$this_nester = new DeepNest($this_schema->data_index);
$mkdir_export_str = $this_nester->get_mkdir_lines($c_monster->_DIR);
$echo_export_str = $this_nester->get_nested_index_echoes($c_monster);
$this_nester->export_batch_commands($mkdir_export_str,'nesting','batch_files');
$this_nester->export_batch_commands($echo_export_str,'indexing','batch_files');
*/
?>
