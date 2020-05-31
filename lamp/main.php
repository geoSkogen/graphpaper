<?php

require 'schema.php';
//require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];

$my_schema = new Schema('test','../records');
$my_table = $my_schema->data_index;
error_log(print_r($my_table));

error_log('schema size:');
error_log(strval(count($new_schema)));

$my_str = Schema::make_export_str($new_schema);
Schema::export_csv($my_str,'my-file','../exports');

?>
