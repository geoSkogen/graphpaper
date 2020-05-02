<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];

/*

$lookup_schema = new Schema('california-ids','../records');
$rtk_schema = new Schema('valid','../records');
$rtk_nocrit = new Schema('rtk-no-crit-id', '../records');
$district_table = $district_schema->data_index;
$rtk_table = $rtk_schema->data_index;
$lookup_table = $lookup_schema->data_index;
$rtk_nocrit_table = $rtk_nocrit->data_index;
*/

//$google_schema = new Schema('criteria-ids-ltd-zip-lookup','../records');
//$google_schema = new Schema('crit-ids-unused','../records');
$rtk_schema = new Schema('equips-rtk-valid-export','../records');
$ca_schema = new Schema('equips-ca-test-export','../exports');
$nj_schema = new Schema('equips-nj-test-export','../exports');
$fl_schema = new Schema('equips-fl-test-export','../exports');
$az_schema = new Schema('equips-az-test-export','../exports');
$district_schema = new Schema('district-table-2.3','../records');
$no_district_schema = new Schema('no-district-table-2.3','../records');
/*
$rtk_no_schema = new Schema('equips-rtk-invalid-zips','../records');
$rtk_district_schema = new Schema('ca-western','../records');
$google_no_schema = new Schema('crit-ids-not-looked-up-by-zip','../records');
*/
//$google_ids_by_place = new Schema('crit-ids-by-locale-name','../records');

//$google_table = $google_schema->data_index;
$rtk_table = $rtk_schema->data_index;
/*
$ca_table = $ca_schema->data_index;
$nj_table = $nj_schema->data_index;
$fl_table = $fl_schema->data_index;
$az_table = $az_schema->data_index;
*/
$district_table = $district_schema->data_index;
$no_district_table = $no_district_schema->data_index;
$states = [null,'NC'];

foreach($rtk_table as $rtk_row) {
//foreach($az_table as $rtk_row) {
  if (array_search($rtk_row[14],$states)) {
    $new_row = [];
    $this_row_arr = [$rtk_row[0],$rtk_row[1],'US'];
    //$new_data = return_district_data('AZ');
    $new_data = return_district_data($rtk_row[8]);
    if (count($new_data)) {
      $new_row = array_merge($this_row_arr,$new_data);
    }
    $new_schema[] = $new_row;
  }
}
//$pops = ['401'=>array(),'273'=>array()];
/*
foreach ($rtk_table as $rtk_row) {
  $crits[] = $rtk_row[0];
}
*/
/*
foreach ($google_table as $google_row) {
  $arr = explode(',',$google_row[2]);
  if ($arr[1]==="Arizona") {
    $new_schema[] = $google_row;
  }
}
*/
/*
foreach ($rtk_table as $rtk_row) {
  if (array_key_exists($rtk_row[8],$pops)) {
    $pops[$rtk_row[8]][] = $rtk_row[15];
  }
}

$new_arr = ['401'=>array(),'273'=>array()];
$keys = array_keys($pops);
foreach ($keys as $key) {
  $new_arr[$key] = rsort($pops[$key]);
}

print_r($pops);
*/
/*
$rtk_no_table = $rtk_no_schema->data_index;
$google_no_table = $google_no_schema->data_index;
$district_table = $rtk_district_schema->data_index;
*/
//$google_ids_by_place_table = $google_ids_by_place->data_index;

$tally = array();
$pop_report = array();


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
foreach($rtk_table as $rtk_row) {
  switch ($rtk_row[8]) {
    case '32' :
    case '23' :
    case '36' :
      if (!$tally[$rtk_row[8]]) {
        $tally[$rtk_row[8]] = ['name'=>array(),'pop'=>array()];
        $tally[$rtk_row[8]]['name'][] = $rtk_row[13];
        $tally[$rtk_row[8]]['pop'][] = $rtk_row[15];
      } else {
        $tally[$rtk_row[8]]['name'][] = $rtk_row[13];
        $tally[$rtk_row[8]]['pop'][] = $rtk_row[15];
      }
      break;
  }
}

$keys = array_keys($tally);

foreach( $keys as $key) {
  array_multisort($tally[$key]['pop'],$tally[$key]['name']);
  $pop_report[$key] = ['pop' => $tally[$key]['pop'], 'name' => $tally[$key]['name'] ];
}

error_log(print_r($pop_report));
*/


/*
$blanks = [];
for ($i = 0; $i < 10; $i++) {
  $blanks[] = '(not set)';
}
foreach($google_ids_by_place_table as $google_place_row) {
  $this_district = '';
  $blanks = [];
  for ($i = 0; $i < 10; $i++) {
    $blanks[] = '(not set)';
  }
  foreach($rtk_district_table as $rtk_district_row) {
    if ($google_place_row[0] === $rtk_district_row[0]) {
      $this_district = $rtk_district_row[1];
      $city_state = explode('_',$google_place_row[0]);
      $blanks[1] = $this_district;
      $blanks[6] = $city_state[0];
      $blanks[7] = $city_state[1];
      foreach ($google_place_row as $google_place) {
        if (is_numeric($google_place)) {
          foreach($google_no_table as $google_row) {
            if ($google_row[0] === $google_place) {
              $new_row = array_merge($google_row,$blanks);
              $new_schema[] = $new_row;
            }
          }
        }
      }
    }
  }
}
*/
/*
foreach ($google_no_table as $google_no_row) {
  $found = false;
  $this_place_arr = explode(',',$google_no_row[2]);
  $this_place_lookup = (!$this_place_arr[count($this_place_arr) - 2]) ?
    '(not set)' : $this_place_arr[count($this_place_arr) - 2];
  foreach ($state_table as $state_row) {
    if ($state_row[0] === $this_place_lookup) {
      $found = true;
      $this_place_name = $google_no_row[1] . '_' . $state_row[1];
      if (!$google_tally[$this_place_name]) {
        $google_tally[$this_place_name] = [$google_no_row[0]];
      } else {
        $google_tally[$this_place_name][] = $google_no_row[0];
      }
    }
  }
  if (!$found) {
    error_log("place not found:");
    error_log(print_r($this_place_arr));
  }
}

$total = 0;
$keys = array_keys($google_tally);
foreach ($keys as $key) {
  $total += count($google_tally[$key]);
  //$new_row = [$key,count($google_tally[$key])];
  $new_row = array_merge([$key],$google_tally[$key]);
  $new_schema[] = $new_row;
}
*/
/*
foreach ($rtk_no_table as $rtk_no_row) {
  $this_city = $rtk_no_row[13];
  $this_state = $rtk_no_row[14];
  $this_place_name = $this_city . '_' . $this_state;

  if (!$tally[$this_place_name]) {
    $tally[$this_place_name] = [$rtk_no_row[8]];
  } else {
    if (!array_search($rtk_no_row[8],$tally[$this_place_name]) &&
      array_search($rtk_no_row[8],$tally[$this_place_name]) != 0) {
      $tally[$this_place_name][] = $rtk_no_row[8];
    }
  }
}

$total = 0;
$keys = array_keys($tally);
foreach ($keys as $key) {
  $total += count($tally[$key]);
  //$new_row = [$key,count($tally[$key])];
  $new_row = array_merge([$key],$tally[$key]);
  $new_schema[] = $new_row;
}

error_log('total:');
error_log($total);
*/
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

//error_log(strval(count($rtk_table)));
//error_log(print_r($trunc_zips));
//$district_data = Schema::get_labeled_rows($district_table);

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
Schema::export_csv($rtk_str,'nc-0','exports');

?>
