
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

  eval(point_a, point_b) {
    let result = []

    result = this.ref_locate(point_a, point_b)

    result = (!result.length) ?
      this.field_locate(point_a, point_b,true) : result

    result = (!result.length) ?
      this.field_locate(point_b, point_a,false) : result

    return result
  }

  locate(point_a, point_b) {

    const points = {
      'a' : point_a,
      'b' : point_b
    }

    var result = this.eval(point_a,point_b)
    var count = 0


    while (!result) {

      let point_a = points['a']
      let point_b = points['b']

      if (!count) {
        a_end = this.path_tracer['a'][this.path_tracer['a'].length-1]
        b_end = this.path_tracer['b'][this.path_tracer['b'].length-1]

        if (a_end!=point_a) {
          this.path_tracer['a'].push(point_a)
        }

        if (b_end!=point_b) {
          this.path_tracer['b'].push(point_b)
        }
      }

      link_point = (this.dir) ? point_a : point_b
      static_point = (!this.dir) ? point_a : point_b
      link_tracer_prop = (this.dir) ? 'a' : 'b'
      link_tracer_pos = count(this.link_tracer[link_tracer_prop])

      this.link_tracer[link_tracer_prop].push([])

      last_crawl_index = (!this.link_tracer[link_tracer_prop][link_tracer_pos-1]) ?
        this.link_tracer[link_tracer_prop][link_tracer_pos-1].length : 0

      Object.keys(this.map.nodes[link_point].field).forEach( (key) => {

        let arr = this.map.nodes[link_point].field[key]

        console.log("__link_point:key__\r\n")
        console.log("iterating: node link_point field as in-field: key\r\n")

        arr.forEach( (field_node) => {

          if (field_node != link_point) {

            console.log("iterating ref-point: key as in-field node: field_node\r\n")
            Object.keys(this.map.nodes[field_node].refs).forEach( (outfield_node) => {

              if (key != outfield_node) {
                this.link_tracer[link_tracer_prop][link_tracer_pos].push( [link_point,key,field_node,outfield_node] )

                console.log("iterating ref-points of field_node | out-field-nodes of: link_point as out-field node outfield_node\r\n")
                console.log("EVAL: is static_point within 1-2 nodes of outfield_node ?\r\n")
                result = this.eval(static_point,outfield_node)
                console.log("\r\n")
                console.log("LOOP")
                console.log("\r\n")
                if (result) {
                  console.log("RESULT\r\n")
                  console.log(result)
                  console.log("\r\n")
                  console.log("\r\n")
                  console.log("PATH tracer:")
                  console.log("\r\n")
                  console.log(this.path_tracer)
                  console.log("\r\n")
                  console.log("\r\n")
                  console.log("LINK tracer--final iteration:")
                  console.log("\r\n")

                  console.log("\r\n")
                  console.log('A')
                  console.log("\r\n")

                  console.log(this.link_tracer['a'])
                  console.log("\r\n")
                  console.log('B')
                  console.log("\r\n")

                  console.log(this.link_tracer['b'])
                  this.set_defaults()
                  return result
                }// end if result, return result
              }// end if key != outfield node
            })// end  outfield nodes iterator
          } // end if field-node != link_point
        })// end field node iterator
      }) //end link point key iterator
      this.dir = !this.dir
      count++

      if (count>1) {

        count = 0
        console.log("\r\n")
        console.log('LOOP CONFIG')
        console.log("\r\n")

        ['a','b'].forEach( (loop_tracer_prop) => {
          this.field_index[loop_tracer_prop]++
          console.log("tracer prop:\r\n")
          console.log(loop_tracer_prop)
          console.log("\r\n")
          console.log("field list index: \r\n")
          console.log( this.field_index[loop_tracer_prop] )
          console.log("\r\n")
          console.log("field list length: \r\n")
          console.log( count(this.field_list[loop_tracer_prop]) )
          console.log("\r\n")
          console.log("crawl iteration index:")
          console.log("\r\n")
          console.log(  link_tracer_pos )
          console.log("\r\n")
          console.log("index of previous crawl path array:")
          console.log("\r\n")
          console.log(  last_crawl_index )
          console.log("\r\n")
          console.log("most recent crawl path:")
          console.log("\r\n")
          console.log(  this.link_tracer[loop_tracer_prop][link_tracer_pos][last_crawl_index] )
          console.log("\r\n")
          if (this.field_index[loop_tracer_prop]>=count(this.field_list[loop_tracer_prop])) {

            this.field_list[loop_tracer_prop] = []
            this.field_index[loop_tracer_prop] = 0

            console.log("\r\n")
            console.log("link point")
            console.log("\r\n")
            console.log(  link_point )
            console.log("\r\n")

            console.log("\r\n")
            console.log("link tracer iterations:")
            console.log("\r\n")
            console.log(  count(this.link_tracer[loop_tracer_prop]) )
            console.log("\r\n")

            this.link_tracer[loop_tracer_prop][link_tracer_pos].forEach( (link_arr) => {

              console.log("\r\n")
              console.log("link arrays in this link tracer iteration:")
              console.log("\r\n")
              console.log(  count(this.link_tracer[loop_tracer_prop][link_tracer_pos]) )
              console.log("\r\n")
              console.log("\r\n")
              console.log("EVAL: does the link point points[loop_tracer_prop] appear as index 0 of:")
              console.log("\r\n")
              console.log( link_arr )
              console.log(" ?\r\n")

              if ( link_arr[0]==points[loop_tracer_prop] &&
                   this.field_list[loop_tracer_prop].indexOf(link_arr[1])===-1) {
                this.field_list[loop_tracer_prop].push( link_arr[1] )
              }

            }) // end link array iteration
          } // end comparison if
          console.log("\r\n")
          console.log("current crawl path:")
          console.log("\r\n")
          console.log(  this.field_list[loop_tracer_prop] )
          console.log("\r\n")
          // resets points a & b as the next item on
          points[loop_tracer_prop] = this.field_list[loop_tracer_prop][this.field_index[loop_tracer_prop]]
        })//end loop tracer iterator
      }// end if count
    }// end while
    return result
  }

}

module.exports = Navigator
