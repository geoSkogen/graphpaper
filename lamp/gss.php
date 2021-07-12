<?php

if (!class_exists('Schema')) {
  require_once 'schema.php';
}

require_once 'methods.php';

function get_mean($data_arr) {
  // calculate a mean using only the valid values
  $total = 0;
  $n = 0;
  foreach($data_arr as $datum) {

    if (intval($datum)) {
      $total+=$datum;
      $n++;
    }
  }
  return $total/$n;
}

function get_col($table,$index) {
  $result = [];
  for ($i = 1; $i < count($table); $i++) {
    //
    for ($ii = 0; $ii < count($table[$i]); $ii++) {
      if ($ii===$index) {
        $result[] = $table[$i][$ii];
      }
    }
  }
  return $result;
}

$schema = new Schema('gss_2016_myvars_3','../records');
// import data as 3D indexed array
$table = $schema->data_index;
$new_table = [$table[0]];
// store means by column index for later search and replace
$means = [
  '5' => 0,
  '6' => 0,
  '24' => 0,
  '27' => 0
];

// iterate each table row
for ($i = 1; $i < count($table); $i++) {
  $row = $table[$i];
  $new_row = [];
  $sm_score = 0;
  //

  for ($ii = 0;$ii < count($row); $ii++) {

    $datum = strpos($row[$ii],'.') ? floatval($row[$ii]) : intval($row[$ii]);
    // pass the data cell through its indexed filter
    $cell_val = $ranges[strval($ii)]($datum,$ii);
    // insert filtered value into current column index
    $new_row[$ii] = $cell_val;

    if ($ii > 27 && $ii < 35) {
      // tally the social media score
      $sm_score+=$cell_val;
    }

  }
  // insert social media score into unused column 35

  $new_row[35] = $sm_score;
  $new_table[] = $new_row;

}

$export_str = $schema::make_export_str($new_table);

// get means for col indices
foreach (array_keys($means) as $col_index) {
  $var_column = get_col($new_table,intval($col_index));
  $var_mean = get_mean($var_column);
  $means[$col_index] = $var_mean;
  $export_str = str_replace('mean_' . $col_index,$var_mean,$export_str);
}

$schema::export_csv($export_str,'gss_clean','../exports');

?>
