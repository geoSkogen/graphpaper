<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];

$my_schema = new Schema('test','../records');
$my_table = $my_schema->data_index;
error_log(print_r($my_table));
/*
$rtk_schema = new Schema('equips-rtk-valid-export','../records');
$district_schema = new Schema('district-table-2.3','../records');
$no_district_schema = new Schema('no-district-table-2.3','../records');

$rtk_table = $rtk_schema->data_index;
$district_table = $district_schema->data_index;
$no_district_table = $no_district_schema->data_index;
$states = [null,'.*'];
*/
/*
foreach($rtk_table as $rtk_row) {
  if (array_search($rtk_row[14],$states)) {
    $new_row = [];
    $this_row_arr = [$rtk_row[0],$rtk_row[1],'US'];

    $new_data = return_district_data($rtk_row[8]);
    if (count($new_data)) {
      $new_row = array_merge($this_row_arr,$new_data);
    }
    $new_schema[] = $new_row;
  }
}
*/
function return_district_data($arg) {
  global $district_table;
  global $no_district_table;
  $data = [];
  foreach ($district_table as $district_row) {
    if ($district_row[0] === $arg) {
      $data = array_slice($district_row,1);
    }
  }
  if (!count($data)) {
    foreach ($no_district_table as $no_district_row) {
      if ($no_district_row[0] === $arg) {
        $data = array_slice($no_district_row,1);
      }
    }
  }
  return $data;
}

/*
//Commits no-postal format to rtk-valid format via zip lookup
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

//Commits rtk-valid to equips1 format via district lookup
/*
foreach($rtk_table as $rtk_row) {
  $new_row = [];
  $this_row = [];
  $this_row_arr = [$rtk_row[0],$rtk_row[1],'US'];

  $new_data = return_district_data($rtk_row[8]);
  if (count($new_data)) {
    $new_row = array_merge($this_row_arr,$new_data);
  }
  $new_schema[] = $new_row;
}
*/
error_log('schema size:');
error_log(strval(count($new_schema)));

$rtk_str = Schema::make_export_str($new_schema);
//Schema::export_csv($rtk_str,'nc-0','exports');

?>
