<?php

include 'php/includes/Util/Schema.php';

$ranked_sites_schema = new Schema('../../../records/acsf-sites-i');
$ranked_sites_table = $ranked_sites_schema->getTable();

$custom_domains_schema = new Schema('../../../records/01live-sites-custom-domains');
$custom_domains_table = $custom_domains_schema->getTable();

$sites_by_rank = [];
$sites_by_alias = [];
$custom_domains_tables_by_rank = [null];

$batch_size = $argv[1] ?? null;
$batch_counter = 0;

// Store domain+database data rows on an associative array keyed by rank
foreach($ranked_sites_table as $row) {
  if (!isset($sites_by_rank[$row[0]])) {
    $sites_by_rank[$row[0]] = [];
  }
  $sites_by_rank[$row[0]][] = $row;
}
// Store the domain data rows (currently in use) on associative array keyed by domain alias
foreach($custom_domains_table as $row) {
  $sites_by_alias[$row[1]] = $row;
}
if ($batch_size) {
  // Iterate the ranked domain+database array,
  //  use each row's domain alias as an array key to locate the currently-in-use data row,
  //  every 10 rows, export a new table.
  $file_suffix = 'batch-of-' . strval($batch_size);
  $batch_counter = 1;
  $batch_table = [];
  foreach($ranked_sites_table as $row_index => $data_row) {
    if (isset($sites_by_alias[$data_row[2]])) {
      $alias_row = $sites_by_alias[$data_row[2]];
      $alias_row[0] = strval($row_index+1);
      $alias_row[] = end($data_row);
      $batch_table[] = $alias_row;
      if (count($batch_table)===intval($batch_size) || $row_index===count($ranked_sites_table)-1) {
        $ranked_sites_schema->exportCSV("../../../exports/01live-sites-custom-domains--$file_suffix-" . strval($batch_counter), $batch_table);
        $batch_counter++;
        $batch_table = [];
      }
    }
  }
} else {
  // Iterate each rank, iterate each row in that rank,
  //  use its domain alias as an array key to locate the currently-in-use data row,
  //  push the rank value onto the data row and add the data row to the new table created for that rank only,
  //  export each new table into CSV file
  $file_suffix = 'rank';
  $total_row_index = 1;
  foreach($sites_by_rank as $rank => $data_rows) {
    $table = [];
    foreach($data_rows as $row_index => $data_row) {
      if (isset($sites_by_alias[$data_row[2]])) {
        $alias_row = $sites_by_alias[$data_row[2]];
        $alias_row[0] = strval($total_row_index);
        $alias_row[] = $rank;
        $table[] = $alias_row;
        $total_row_index++;
      }
    }
    $batch_counter = $rank;
    $ranked_sites_schema->exportCSV("../../../exports/01live-sites-custom-domains--$file_suffix-" . strval($batch_counter), $table);
  }
}
?>
