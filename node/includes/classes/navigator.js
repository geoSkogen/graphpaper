
class Navigator {

  constructor(data) {

    var Map = require('./map.js')
    var Node = require('./node.js')

    this.field_list
    this.link_tracer
    this.path_tracer
    this.field_index

    var roll = []

    for (let i = 0; i < data.length; i++) {

      var row = data[i]
      var node

      if (Object.keys(roll).indexOf(row[0])<0) {

        node = new Node(row[0],row[1],row[2])
        roll[row[0]] = node
      } else {
        roll[row[0]].refs[row[1]] = row[2]
      }

      if (Object.keys(roll).indexOf(row[1])<0) {

        node = new Node(row[1],row[0],row[2])
        roll[row[1]] = node
      } else {
        roll[row[1]].refs[row[0]] = row[2]
      }

    }

    this.map  = new Map(roll)
    this.set_defaults()
  }

  set_defaults() {
    this.dir = true

    this.field_list = {'a':[],'b':[]}
    this.link_tracer = {'a':[],'b':[]}
    this.path_tracer = {'a':[],'b':[]}
    this.field_index = {'a':-1,'b':-1}
  }

  ref_locate(point_a, point_b) {
    let result = []
    if (Object.keys(this.map.nodes[point_b].refs).indexOf( point_a.toString() )>-1) {

      result = [point_a,point_b]
    }
    return result.length ? result : []
  }

  field_locate(haystack,needle,arg) {
    let result = []

    Object.keys(this.map.nodes[haystack].field).forEach( (key) => {

      if (this.map.nodes[haystack].field[ key.toString() ].indexOf( needle.toString() )>-1) {

        let push_val = arg ? [ haystack.toString(), key, needle.toString() ] :
          [ needle.toString(), key, haystack.toString() ]

        result.push(push_val)
      }
    })
    return result.length ? result : []
  }

}

module.exports = Navigator
