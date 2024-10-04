<?php


include 'php/includes/Util/Schema.php';

$admissions_schema = new Schema('../../../records/admissions');
$admissions_table = $admissions_schema->getTable();
print("__Admissions\n");
foreach( $admissions_table as $key => $admissions_row) {
  print(str_replace('/','',$admissions_row[0]) . ":\n");
  print("  calendar_id: '{$admissions_row[1]}'\n");
  print("  calendar_host: '" . $admissions_row[2] . "'\n");
}
print("\n__Visit\n");
$visit_schema = new Schema('../../../records/visit');
$visit_table = $visit_schema->getTable();
foreach( $visit_table as $key => $visit_row) {
  print(str_replace('/','',$visit_row[0]) . ":\n");
  print("  calendar_id: '{$visit_row[1]}'\n");
  print("  calendar_host: '" . $visit_row[2] . "'\n");
}


?>
