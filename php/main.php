<?php

require 'schema.php';
require 'deep_nest.php';

$this_schema = new Schema('data', '../records');
$this_nester = new DeepNest($this_schema->data_index);
$mkdir_export_str = $this_nester->get_mkdir_lines('content-dir');
$echo_export_str = $this_nester->get_nested_index_echoes(
  'content-dir',
  'index.php',
  '^<^?php ^/^/Silence is golden.'
);
$this_nester->export_batch_commands($mkdir_export_str,'nesting','batch_files');
$this_nester->export_batch_commands($echo_export_str,'indexing','batch_files');
?>
