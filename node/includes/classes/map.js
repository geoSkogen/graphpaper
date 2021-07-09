class Map {

  constructor(nodes) {
    this.nodes = nodes
    this.hubs = []
    this.bounds = []
    this.links = []
  }

  crawl() {
    const labels = [null, 'bounds', 'links']
    let distro = []
    let distro_keys = []
    let distribution = []
    this.nodes.forEach( (node) => {

      let refs_len = Object.keys(node.refs).length

      if (!distro[refs_len]) {

        distro[refs_len] = [node.id]
      } else {
        distro[refs_len].push(node.id)
      }

      node.field = this.node_field(node)
    })

    distro_keys = Object.keys(distro).sort()
    distro_keys.forEach( (key) => {
      distribution[Number(key)] = distro[Number(key)]
    })

    for (let i = 1; i < distribution.length; i++)  {

      if (i > 2) {

        this.hubs.push( distribution[i] )
      } else {

        this[ labels[ i ] ] = distribution[ i ]
      }
    }
  }

  node_field(node) {
    let arr = {}

    Object.keys(node.refs).forEach( (ref_key) => {

      if (!arr[ref_key]) {

        arr[ ref_key ] = Object.keys( this.nodes[ ref_key ].refs )
      }
    })
    return arr
  }
}

module.exports = Map
