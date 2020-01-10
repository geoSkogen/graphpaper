<?php

require 'schema.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
$new_arr = [];
$zip_index = -1;
$place_name = '';
$crit_schema = new Schema('rtk-full-zips-etc', '../records');
$zip_schema = new Schema('crit-zips', '../records');
$rtk_schema = new Schema('rtk-full-zips','../records');

$crit_table = $crit_schema->data_index;
$zip_table = $zip_schema->data_index;
$rtk_table = $rtk_schema->data_index;

$zip_obj = Schema::get_labeled_rows($zip_table);
$crit_obj = Schema::get_labeled_rows($crit_table);

$this_crit = '';
$this_zip = '';
$blank_arr = ["(not set)","(not set)","(not set)","(not set)","(not set)","(not set)","(not set)"];
$found_crits = [];

foreach($crit_table as $crit_row) {
  $new_arr[] = $crit_row[7];
}

foreach($rtk_table as $rtk_row) {
  if ( array_search($rtk_row[0],$new_arr) || array_search($rtk_row[0],$new_arr) === 0) {

  } else {
    $new_row = array_merge($blank_arr,$rtk_row);
    $new_schema[] = $new_row;
  }
}

$rtk_str = Schema::make_export_str($new_schema);
Schema::export_csv($rtk_str, 'trk-zips-no-lookup', 'exports' );

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
