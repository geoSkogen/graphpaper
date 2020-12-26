<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';
/*
$new_schema = [];
$new_row = [];
*/
$my_schema = new Schema('geo5','../records');
$my_table = $my_schema->data_index;
//export tables
$locale_ids = [];
$criteria_ids = [];
//metadata
$locale_names = array();
$locale_index = 0;
//sorting routine
foreach($my_table as $row) {
  if (empty($locale_names[$row[3]])) {
    $locale_names[$row[3]] = $locale_index;
    $locale_ids[$locale_index] = [ $row[3],$row[4],$row[5],$row[6],$row[7] ];
    $locale_index++;
  }

  $criteria_ids[] = [ $row[0],$row[1],$row[2],$locale_names[$row[3]] ];
}
//report
error_log('schema size crit ids:');
error_log(strval(count($criteria_ids)));
error_log('schema size locale ids:');
error_log(strval(count($locale_ids)));
//export
$locale_str = Schema::make_export_str($locale_ids);
Schema::export_csv($locale_str,'geo5-locales','../exports');
$criteria_str = Schema::make_export_str($criteria_ids);
Schema::export_csv($criteria_str,'geo5-ids','../exports');

?>
