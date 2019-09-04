<?php

require 'schema.php';
require 'pdf_redirects.php';

$this_schema = new Schema('data', '../records');
$these_redirects = new PDF_Redirects($this_schema->data_index);
$this_export_str = $these_redirects->get_mkdir_lines('content-dir');
$these_redirects->export_batch_commands($this_export_str,'nesting','batch_files');
//error_log($this_export_str);
?>
