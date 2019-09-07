class Schema {
  constructor( filename, path ) {
    this.fs = require("fs-extra")
    this.data = this.import_csv( filename, path )
  }

  import_csv( import_filename, dir_path ) {
    var buffer = new String
    var slug_arr = new Array
    var data_arr = new Array
    var stage_obj = new Object
    var export_arr = new Array
    var keys = new Array
    var data = this.fs.readFileSync('./' + dir_path + '/'+ import_filename + ".csv")
    for (var i = 0; i < data.length; i++) {
      buffer += String.fromCharCode(data[i])
    }
    slug_arr = buffer.split("\r\n")
    for (var i = 0; i < slug_arr.length; i++) {
      data_arr[i] = slug_arr[i].split(",")
      stage_obj = {}
      for (var ii = 0; ii < data_arr[i].length; ii++) {
        if (i === 0) {
          keys.push(data_arr[i][ii])
        } else {
          stage_obj[keys[ii]] = data_arr[i][ii]
        }
      }
      if (i != 0) { export_arr.push(stage_obj) }
    }
    return { assoc: export_arr, index: data_arr }
  }

  lookup_val( keyname, index ) {
    var result = false
    var key_index = -1
    if (index != 0) {
      key_index = this.data.index[0].indexOf(keyname)
      result = (key_index > -1) ? this.data.index[index][key_index] : result
    }
    return result
  }

  make_export_str( data ) {
    var export_str = new String
    var keys = new Array
    for (var i = 0; i < data.length; i++) {
      if (Array.isArray(data[i])) {
        for (var ii = 0; ii < data[i].length; ii++) {
          export_str += data[i][ii]
          export_str += (ii === data[i].length-1) ? "\r\n" : ","
        }
      } else {
        keys = Object.keys(data[i])
        for (var ii = 0; ii < keys.length; ii++) {
          export_str += keys[ii]
          export_str += ","
          export_str += data[i][keys[ii]]
          export_str += (ii === keys.length-1) ? "\r\n" : ","
        }
      }
    }
    return export_str
  }



  export_csv( export_str, filename, dir_path ) {
    var path = "../" + dir_path + "/" + filename + ".csv"
    this.fs.writeFile(path, export_str, function(err) {
      if(err) {
        return console.log(err)
      }
      console.log("saved " + path)
    })
  }

}

module.exports = Schema
