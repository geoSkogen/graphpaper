<?php
include 'php/includes/Util/Schema.php';

$tags_schema = new Schema('../../../records/subscription-tags');
$tags_table = $tags_schema->getTable();
$new_table = [];

foreach ($tags_table as $tag_row) {
  $new_row = [];
  if ($tag_row[0]) {
    print(strtolower(str_replace(' ','-',$tag_row[0])) . ": ''\r\n");
  }
}

?>
