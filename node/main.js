const Schema = require ('./schema.js')
const Navigator = require('./includes/classes/navigator.js')

const schema = new Schema('test','../records')
const nav = new Navigator(schema.data.index)
/*
console.log('map links')
console.log(nav.map.links)
*/
