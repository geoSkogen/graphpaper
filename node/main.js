const Schema = require ('./schema.js')
const Navigator = require('./includes/classes/navigator.js')

const schema = new Schema('test','../records')
const nav = new Navigator(schema.data.index)

nav.map.crawl()

var result = nav.field_locate(1,8,true)

console.log(result)
