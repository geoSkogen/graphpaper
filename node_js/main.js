'use strict'

var Schema = require('./schema.js')
<<<<<<< HEAD
=======
var this_schema = new Schema('faq-you', '../records')
var new_array = []
var new str = ''
>>>>>>> revisions-2
/*
var DeepNest = require('./deep_nest.js')
var CMonster = require('./content_monster.js')
*/

var perf_schema = new Schema('performance', '../records')
var map_cols = Schema::get_labeled_columns(perf_schema->data_index)
var tt_schema = new Schema('page_titles_all', '../records')
var map_cols = Schema::get_labeled_columns(tt_schema->data_index)

/*
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
*/
<<<<<<< HEAD
=======
var jsonData = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": []
}
var faqObj = {
  "@type": "Question",
  "name": "",
  "acceptedAnswer": {
    "@type": "Answer",
    "text": ""
  }
}
var this_faq = faqObj
var even = true
var even_even = false
this_schema.data.index.forEach(
   function (e) {
    if (even) {
      if (even_even) {
        e.forEach( function (ee) {
          this_faq.acceptedAnswer.text += ee
        })
        new_array.push(this_faq)
        this_faq = {
          "@type": "Question",
          "name": "",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": ""
          }
        }
      } else {
        e.forEach( function (eee) {
          this_faq.name += eee
        })
      }
      even_even = !even_even
    }
  even = !even
  }
)
jsonData.mainEntity = new_array
new_str = JSON.stringify(jsonData)
this_schema.export_csv(new_data, 'myjson', 'exports')
>>>>>>> revisions-2
