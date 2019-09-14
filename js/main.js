'use strict'

var Schema = require('./schema.js')
var DeepNest = require('./deep_nest.js')
var filename = process.argv[2]
var this_schema = new Schema(filename, '../records')
var this_nester = new DeepNest(this_schema.data.index)
var mkdir_export_str = this_nester.get_mkdir_lines('content-dir')
var echo_export_str = this_nester.get_nested_index_echoes(
  'content-dir',
  'index.php',
  '^<^?php ^/^/Silence is golden.'
)
this_nester.export_batch_commands(mkdir_export_str,'nesting','batch_files')
this_nester.export_batch_commands(echo_export_str,'indexing','batch_files')

//this_schema.export_csv( export_str, 'myexport', '../exports' )
header("Location: http://lotuseaters/saturn_3/", true, 301);
