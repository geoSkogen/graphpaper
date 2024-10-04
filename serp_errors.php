<?php

include 'php/includes/Util/Schema.php';

$not_found_schema = new Schema('../../../records/404');
$not_found_table = $not_found_schema->getTable();
$crawled_schema = new Schema('../../../records/crawled');
$crawled_table = $crawled_schema->getTable();

$flat_crawled_arr = [];
$result_table = [];


foreach ($crawled_table as $crawled_row) {
  $flat_crawled_arr[] = $crawled_row[0];
}


foreach ($not_found_table as $not_found_row) {
  if (array_search($not_found_row[0],$flat_crawled_arr) || array_search($not_found_row[0],$flat_crawled_arr)==='0') {
    $result_table[] = [$not_found_row[0]];
    print($not_found_row[0]);
    print("\r\n");
  }
}







?>
