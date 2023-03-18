<?php

require 'includes/Util/Schema.php';

$schema = new Schema('../../../records/gss_2016_myvars');

print_r($schema->getTable());

?>
