class Node {

  constructor(id,ref,rel) {
    this.id = id
    this.refs = {}
    this.refs[ref] = rel
    this.field = []

  }
}

module.exports = Node
