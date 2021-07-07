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
    let distribution =
    this.nodes.forEach( (node) => {

      if (distro[node.refs.length]) {

        distro[node.refs.length] = [node.id]
      } else {
        distro[node.refs.length].push(node.id)
      }
    })

    distro_keys = Object.keys(distro).sort
    distro_keys.forEach( (key) => {
      distrubiton[key] = distro[key]
    })

    Object.keys(distribution).forEach( (distro_key) => {
      if (Number(distro_key) > 2) {
        this.hubs.push[ distribution[ distro_key] ]
      } else {
        this[ labels[ Number(distro_key) ] ] = distribution[ distro_key ]
      }
    })
  }

  node_field(node) {
    let arr = {}
    Object.keys(node.refs).forEach( (ref_key) => {

      if (!arr[ref_key]) {

        arr[ ref_keys ] = Object.keys( this.nodes[ ref_keys.refs  ])
      }
    })
    return arr
  }
}

module.exports = Map
