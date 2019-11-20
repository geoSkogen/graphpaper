<?php

require 'schema.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
$zip_index = -1;
$place_name = '';
$crit_schema = new Schema('criteria-ids', '../records');
$zip_schema = new Schema('zipcodes', '../records');

$crit_table = $crit_schema->data_index;

$zip_table = $zip_schema->data_index;
$zip_cols = Schema::get_labeled_columns($zip_table);

foreach($crit_table as $crit_row) {
  if ($crit_row[4] === 'US') {
    if ($crit_row[5] === 'Postal Code') {
      $zip_index = array_search($crit_row[1],$zip_cols['Zipcode']);
      $place_name = $zip_cols['City'][$zip_index];
      //error_log($place_name);
      $new_row = $crit_row;
      $new_row[1] = ucwords(strtolower($place_name));
    } else {
      $new_row = $crit_row;
    }
    $new_schema[] = $new_row;
  }
}

$crit_str = Schema::make_export_str($new_schema);
Schema::export_csv($crit_str, 'myexport', 'exports' );

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
