var Schema = require('./schema.js')
var PDF_Redirects = require('./pdf_redirects.js')
var filename = process.argv[2]
var this_schema = new Schema(filename, '../records')
var these_redirects = new PDF_Redirects(this_schema.data.index)
var mkdir_export_str = these_redirects.get_mkdir_lines('content-dir')
var echo_export_str = these_redirects.get_echo_lines(
  'content-dir',
  'index.php',
  '^<^?php ^/^/Silence is golden.'
)
these_redirects.export_batch_commands(mkdir_export_str,'nesting','batch_files')
these_redirects.export_batch_commands(echo_export_str,'indexing','batch_files')

//this_schema.export_csv( export_str, 'myexport', '../exports' )
