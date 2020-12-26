
#### node_modules not included; run 'npm install' in the node_js directory after cloning
# CSV-schema
## - handmade tools for crunching spreadsheet data in PHP  or JavaScript
##### I've found this useful for braiding, subdividing, general sorting of data when I immediately intuit the functionality, but not the Excel-ese; I can fall back on my stronger languages and get the work done without a hiccup. 
### import raw data into your native programming environment | export CSV results
#### Move a CSV file into the /records/ folder; for basic data manipulation:
##### The following PHP script imports 'raw_data.csv,' creates a space for your row-wise operations, pushes the new rows to new data object, and exports it as a new CSV file:
"
require 'schema.php';
$my_schema = new Schema('raw_data','../records');
$my_table = $my_schema->data_index;
$new_schema = [];
foreach($my_table as $row) {
  $new_row = [];
  // row, row, row your boat . . .
  // (operation for $new_row)
  // . . . gently down the stream
  $new_schema[] = $new_row;
}
$export_str = Schema::make_export_str($new_schema);
Schema::export_csv($export_str,'clean_data','../exports');
"
