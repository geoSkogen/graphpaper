<?php
include 'php/includes/Util/Schema.php';

$tags_schema = new Schema('../../../records/tag-match');
$tags_table = $tags_schema->getTable();
$new_table = [];

foreach ($tags_table as $tag_row) {
  $new_row = [];
  if ($tag_row[0]!=$tag_row[1]) {
    print($tag_row[0].": '{$tag_row[1]}'\r\n");
  }
}

?>
