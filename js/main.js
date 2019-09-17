'use strict'

var Schema = require('./schema.js')
var DeepNest = require('./deep_nest.js')
var CMonster = require('./content_monster.js')

var this_schema = new Schema('url-data', '../records')
var cmd_schema = new Schema('cmds-data', '../records')
var c_monster = new CMonster(
  cmd_schema.data.index,
  [],
  'php',
  '//Silence is golden.',
  'content-dir'
)
var this_nester = new DeepNest(this_schema.data.index)
var mkdir_export_str = this_nester.get_mkdir_lines(c_monster._DIR)
var echo_export_str = this_nester.get_nested_index_echoes(c_monster)
this_nester.export_batch_commands(mkdir_export_str,'nesting','batch_files')
this_nester.export_batch_commands(echo_export_str,'indexing','batch_files')
