<?php

if (!class_exists('Schema')) {
  require_once 'schema.php';
}

require_once 'methods.php';

$schema = new Schema('gss_2016_myvars','../records');

$table = $schema->data_index;
$new_table = [];

for ($i = 0; $i < count($table); $i++) {
  $row = $table[$i];
  $new_row = [];

  for ($ii = 0; count($row); $ii++) {
    $sm_score = 0;
    $datum = intval($row[$ii]);

    $cell_val = $ranges[$ii]($datum,$ii);
    $new_row[$ii] = $cell_val;

    if ($ii > 27 && $ii < 35) {
      $sm_score+=$cell_val;
    }

  }

  $new_row[35] = $sm_score;
}
// get means for col indices


$export_str = $schema::make_export_str($new_table);

$schema::export_csv($export_str,'gss_clean','../exports');

?>
