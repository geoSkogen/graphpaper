<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
/*
$district_schema = new Schema('ca-western','../records');
$lookup_schema = new Schema('california-ids','../records');
$rtk_schema = new Schema('valid','../records');
$rtk_nocrit = new Schema('rtk-no-crit-id', '../records');
$district_table = $district_schema->data_index;
$rtk_table = $rtk_schema->data_index;
$lookup_table = $lookup_schema->data_index;
$rtk_nocrit_table = $rtk_nocrit->data_index;
*/

$google_schema = new Schema('criteria-ids-ltd-zip-lookup','../records');
$rtk_schema = new Schema('equips-rtk-valid-export','../records');
$rtk_no_schema = new Schema('equips-rtk-invalid-zips','../records');
$rtk_tally_schema = new Schema('equips-rtk-invalid-zip-locale-tally','../records');
$google_no_schema = new Schema('crit-ids-not-looked-up-by-zip','../records');

$google_table = $google_schema->data_index;
$rtk_table = $rtk_schema->data_index;
$rtk_no_table = $rtk_no_schema->data_index;
$google_no_table = $google_no_schema->data_index;
$rtk_tally_table = $rtk_tally_schema->data_index;

$blank_google_row = ['(not net)','(not net)','(not net)','(not net)','(not net)','(not net)','(not net)'];

foreach ($google_no_table as $google_no_row) {
  if ($google_no_row[5] === 'Neighborhood') {
    $new_schema[] = $google_no_row;
  }

}
/*
foreach ($rtk_tally_table as $rtk_tally_row) {
  if ($rtk_tally_row[1] === '1') {
    foreach ($google_table as $google_row) {
      if ($google_row[1] === $rtk_tally_row[0]) {
        foreach ($rtk_no_table as $rtk_no_row) {
          if ($rtk_no_row[13] === $rtk_tally_row[0]) {
            $new_row = array_merge($google_row,
            [ $rtk_no_row[7],$rtk_no_row[8],$rtk_no_row[9],$rtk_no_row[10],$rtk_no_row[11],
              $rtk_no_row[12],$rtk_no_row[13], $rtk_no_row[14], $rtk_no_row[15] ]
            );
            $new_schema[] = $new_row;
          }
        }
      }
    }
  }
}
*/
/*
$locales_tally = array();
$locale_count = 0;

foreach($rtk_no_table as $rtk_no_row) {
  $found = [];
  $new_row = [];
  $this_loc = $rtk_no_row[13];
  if (!$locales_tally[$this_loc]) {
    $locales_tally[$this_loc] = 1;
  } else {
    $locales_tally[$this_loc]++;
  }
}

$keys = array_keys($locales_tally);

foreach($locales_tally as $locale) {
  $new_schema[] = [ $keys[$locale_count],
  $locales_tally[$keys[$locale_count]] ];
  $locale_count++;
}
*/

/*
foreach ($rtk_table as $rtk_row) {
  $new_row = [];
  $this_zip = '';
  $zip_diff =  5 - strlen($rtk_row[7]);
  if ( $zip_diff ) {
    for ($i = 0; $i < $zip_diff; $i++) {
      $this_zip .= '0';
    }
  }
  $this_zip .= $rtk_row[7];
  if ( $zip_diff ) {
    $trunc_zips[] = $this_zip;
  }
  foreach($google_table as $google_row) {
    if ($google_row[5] === 'Postal Code') {
      $this_geo_scheme = explode(',',$google_row[2]);
      $this_match_zip = $this_geo_scheme[0];
      if ($this_match_zip === $this_zip) {
        $new_row = array_merge($google_row,$rtk_row);
        $new_schema[] = $new_row;
      } else {
      }
    }
  }
}
*/
error_log('schema size:');
error_log(strval(count($new_schema)));

//error_log(strval(count($rtk_table)));
//error_log(print_r($trunc_zips));
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
Schema::export_csv($rtk_str,'crit-ids-not-looked-up-by-zip-neighborhood','exports');

?>
