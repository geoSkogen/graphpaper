<?php

require 'schema.php';
require 'pdf_redirects.php';

$this_schema = new Schema('data', '../records');
$these_redirects = new PDF_Redirects($this_schema->data_index);
$mkdir_export_str = $these_redirects->get_mkdir_lines('content-dir');
$echo_export_str = $these_redirects->get_echo_lines(
  'content-dir',
  'index.php',
  '^<^?php ^/^/Silence is golden.'
);
$these_redirects->export_batch_commands($mkdir_export_str,'nesting','batch_files');
$these_redirects->export_batch_commands($echo_export_str,'indexing','batch_files');
?>
