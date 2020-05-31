<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';
$export_schema = [];
$new_schema = [];
$new_row = [];
$source_schema = new Schema('source_by_event_label','../records');
$source_table = $source_schema->data_index;
$lead_schema = new Schema('form_entries_x','../records');
$lead_table = $lead_schema->data_index;
$source_assoc = array();
$source_index = [0];
$results = array();

foreach($source_table as $source_row) {
  if (isset($source_row[0]) && isset($source_row[1]))
  $source_assoc[$source_row[1]] = $source_row[0];
  if (array_search($source_row[0],$source_index)) {
    $source_index[] = $source_row[0];
  }
}

foreach($lead_table as $lead_row) {
  $this_source = ( isset($lead_row[2]) && isset($source_assoc[$lead_row[2]]) ) ?
    $source_assoc[$lead_row[2]] : '(not set)';
  $new_row = $lead_row;
  $new_row[2] = $this_source;
  $new_schema[] = $new_row;
}

foreach($new_schema as $this_row) {
  if ( !isset($results[$this_row[2]]) ) {
    $results[$this_row[2]] = [$this_row];
  } else {
    $results[$this_row[2]][] = $this_row;
  }
}

foreach($results as $source_type) {
  foreach($source_type as $this_lead) {
    $export_schema[] = $this_lead;
  }
}

error_log('schema size:');
error_log(strval(count($export_schema)));

$my_str = Schema::make_export_str($export_schema);
Schema::export_csv($my_str,'my-file','../exports');

?>
