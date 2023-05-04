class Schema {

  constructor( path ) {
    this.fs = require("fs-extra")
    this.data_index = this.importCSV( path )
    this.data_assoc = [];
    this.labeled_columns = [];
    this.labeled_rows = [];
  }

  importCSV( path ) {
    var buffer = ""
    var slug_arr = []
    var data_arr = []
    var data = this.fs.readFileSync( './' + path + ".csv")
    for (var i = 0; i < data.length; i++) {
      buffer += String.fromCharCode(data[i])
    }
    slug_arr = buffer.split("\r\n")
    for (var i = 0; i < slug_arr.length; i++) {
      data_arr[i] = slug_arr[i].split(",")
    }
    return data_arr
  }

  getTable() {
    return this.data_index
  }

  getAssociative(is_table) {
    /* bool > false - assumes columns are labeled, returns indexed associative rows */
    /* bool > true - assumes columns and rows are labeled, returns 2D associative array */
    let result = [];
    const table_col_index = is_table ? 1 : 0;
    const keys = this->data_index[0];
    for (var i = 1; i < count(this.data_index); i) {
      let row = [];
      if (this.data_index[i])) {

        for (let col_index = table_col_index; col_index < $this.data_index[i].length); col_index++) {
          row[ keys[col_index] ] = this.data_index[i][col_index];
        }
        let row_key = (is_table) ? this.data_index[i][0] : i;
        result[row_key] = row;
      }
    }
    this.data_assoc = result;
    return result
  }  

  getLabeledColumns( data_arr ) {
    var keys =  []
    var result = []
    for (let row_index = 0; row_index < data_arr.length; row_index++) {
      for (let i = 0; i < data_arr[row_index].length; i++) {
        if (row_index === 0) {
          result[data_arr[row_index][i]] = []
          keys.push(data_arr[row_index])
        } else {
          if (data_arr[row_index][i]) {
            result[keys[i]].push(data_arr[row_index][i])
          }
        }
      }
    }
    this.labeled_columns = result
    return result
  }

  getLabeledRows( data_arr ) {
    var key = ''
    var valid_data = []
    var result = []
    for (let row_index = 0; row_index < data_arr.length; row_index++) {
      var valid_data = []
      for (let i = 0; i < data_arr[row_index].length; i++) {
        if (i === 0) {
          key = data_arr[row_index][i]
        } else {
          if (data_arr[row_index][i]) {
            valid_data.push(data_arr[row_index][i])
          }
          if (i === data_arr[row_index].length-1) {
            result[key] = valid_data
          }
        }
      }
    }
    this.labeled_rows = result
    return result
  }

  tableLookup( col, row ) {
    var result = false
    if ( (col || col === 0) && (row || row === 0) ){
      if (this.data.index[row][col]) {
        result = this.data.index[row][col]
      }
    }
    return result
  }

  getExportCSV( data ) {
    var export_str = ''
    var keys = []
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

  exportCSV( export_str, path ) {
    this.fs.writeFile(path, export_str, function(err) {
      if(err) {
        return console.log(err)
      }
      console.log("saved " + path)
    })
  }

}

module.exports = Schema
