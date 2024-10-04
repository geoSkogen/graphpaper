<?php

include 'php/includes/Util/Schema.php';

$sites_schema = new Schema('../../../records/acsf-sites-i');
$sites_table = $sites_schema->getTable();
$new_table = [];

foreach ($sites_table as $site_row) {
  $new_row = [];
  $new_row[] = $site_row[3];
  $new_row[] = $site_row[2] . 'web.wdt.pdx.edu';

  $new_table[] = $new_row;
}
$sites_schema->exportCSV("../../../exports/acsf-aws-map", $new_table);


?>
