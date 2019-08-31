<?php

require 'schema.php';

$this_schema = new Schema('data', '../records');
$assoc = $this_schema->data_assoc;
$export_str = $this_schema->make_export_str($assoc);
$this_schema->export_csv($export_str,'myexport','exports');


?>
