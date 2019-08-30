var Schema = require('./schema.js')
var filename = process.argv[2]
var this_schema = new Schema(filename, '../records')
var assoc = this_schema.data.assoc
var index = this_schema.data.index
var export_str = this_schema.make_export_str(assoc)

this_schema.export_csv( export_str, 'myexport', '../exports' )
