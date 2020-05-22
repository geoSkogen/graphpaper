'use strict'
/*
var Schema = require('./schema.js')
var this_schema = new Schema('allied_geo_targets', '../records')
var this_table = this_schema.data.index

var new_str = ''
var json_obj = {
       "@type":"City",
       "name":"",
       "hasMap":"",
       "geo": {
         "@type" :"GeoCoordinates",
         "latitude":"",
         "longitude":""
       }
     }
function iterate_geos(this_table) {
  var new_array = []
  this_table.forEach( function (e) {
    var city = e[0]
    var url = ''
    for (var i = 1; i < e.length-2; i++)  {
      url += e[i]
      url += (i===e.length-3) ? '' : ','
    }
    var json_obj = {
           "@type":"City",
           "name": city,
           "hasMap":url,
           "geo": {
             "@type" :"GeoCoordinates",
             "latitude":e[e.length-2],
             "longitude":e[e.length-1]
           }
         }
  new_array.push(json_obj)
  })
  return new_array
}

var my_json = iterate_geos(this_table)
console.log(my_json)
*/
/*
var DeepNest = require('./deep_nest.js')
var CMonster = require('./content_monster.js')
*/
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

var Schema = require('./schema.js')
var this_schema = new Schema('faq-you', '../records')
var new_array = []
var new_str = ''
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
this_schema.export_csv(new_str, 'myjson', 'exports')
