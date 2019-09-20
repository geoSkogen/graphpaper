<?php

require 'schema.php';
require 'deep_nest.php';
require 'content_monster.php';

$this_schema = new Schema('geo2-csvnest-row', '../records');
$arr = $this_schema->data_index;
$i = 0;
$base_url = "'https://ehrlich-ne.pestcontroloffers.com/home/";
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
