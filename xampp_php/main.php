<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
$district_schema = new Schema('ca-western','../records');
$lookup_schema = new Schema('california-ids','../records');
$rtk_schema = new Schema('valid','../records');
$rtk_nocrit = new Schema('rtk-no-crit-id', '../records');
$district_table = $district_schema->data_index;
$rtk_table = $rtk_schema->data_index;
$lookup_table = $lookup_schema->data_index;
$rtk_nocrit_table = $rtk_nocrit->data_index;


//$district_data = Schema::get_labeled_rows($district_table);

function return_district_data($num_arg) {
  global $district_table;
  $data = [];
  foreach ($district_table as $district_row) {
    if ($district_row[0] === $num_arg) {
      for ($i = 1; $i < count($district_row); $i++) {
        $data[] = $district_row[$i];
      }
    }
  }
  return $data;
}
/*
//Commits no postal format to trk-valid format via zip lookup
$counts = [];
$zips = [];
$notfound = 0;
$zip_not_found = 0;
foreach ($lookup_table as $lookup_row) {
  $new_row = [];
  $lookups = [$lookup_row[17],$lookup_row[18],$lookup_row[19]];
  foreach ($lookups as $lookup) {
    if (is_numeric($lookup)) {
      $this_zip = $lookup;
      $zips[] = $lookup;
      foreach ($rtk_nocrit_table as $rtk_nocrit_row) {
        if ($rtk_nocrit_row[7] === $this_zip) {
          $new_row[] = $lookup_row[0];
          $new_row[] = $lookup_row[1];
          $new_row[] = $lookup_row[2];
          $new_row[] = $lookup_row[3];
          $new_row[] = $lookup_row[4];
          $new_row[] = $lookup_row[5];
          $new_row[] = $lookup_row[6];
          $new_row[] = $rtk_nocrit_row[7];
          $new_row[] = $rtk_nocrit_row[8];
          $new_row[] = $rtk_nocrit_row[9];
          $new_row[] = $rtk_nocrit_row[10];
          $new_row[] = $rtk_nocrit_row[11];
          $new_row[] = $rtk_nocrit_row[12];
          $new_row[] = $rtk_nocrit_row[13];
          $new_row[] = $rtk_nocrit_row[14];
          $new_row[] = $rtk_nocrit_row[15];
          $new_row[] = $rtk_nocrit_row[16];
        }
      }
      $new_schema[] = $new_row;
    } else {
      $notfound++;
    }
  }
}
*/
/*
error_log('notfound:');
error_log(strval($notfound));
error_log('found:');
error_log(strval(count($zips)));
error_log(print_r($zips));
*/
/*
$data = return_district_data('151');
error_log(print_r($data));
*/

//Commits rtk-valid to equips1 format via district lookup
/*
foreach($rtk_table as $rtk_row) {
  $new_row = [];
  $this_row = [];

  $new_data = return_district_data($rtk_row[8]);
  if (count($new_data)) {
    $new_row[] = $rtk_row[0];
    $new_row[] = $rtk_row[1];
    $new_row[] = 'US';
    $new_row[] = $new_data[0];
    $new_row[] = $new_data[1];
    $new_row[] = $new_data[2];
    $new_row[] = $new_data[3];
    $new_row[] = $new_data[4];
    //etc.
  }
  $new_schema[] = $new_row;
}
*/

$rtk_str = Schema::make_export_str($new_schema);
Schema::export_csv($rtk_str,'equips-ca-export','exports');

?>
