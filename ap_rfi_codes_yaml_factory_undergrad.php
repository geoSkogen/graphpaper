<?php


include 'php/includes/Util/Schema.php';

$ap_schema = new Schema('../../../records/ap_rfi_codes_undergrad');
$ap_table = $ap_schema->getTable();
print("__AP_Forms\n");
foreach( $ap_table as $key => $ap_row) {
  $url_arr = explode('?',$ap_row[1]);
  $form_path = basename($url_arr[0]);
  $query_arg = explode('=',$url_arr[1])[0];
  print($ap_row[0] . "\n");
  print("  form_path: '/{$form_path}'\n");
  print("  form_query_arg: '{$query_arg}'\n");
  print("  form_id: {$ap_row[2]}\n");
}


?>
