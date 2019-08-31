var fs = require("fs-extra")
var filename = process.argv[2]

function trimCsvSlug(slug) {
  var result_arr = slug.split(",")
  for (let i = 0; i < result_arr.length; i++) {
    result_arr[i] = result_arr[i].replace(/^\s/,"")
    result_arr[i] = result_arr[i].replace(/\s$/,"")
  }
  return result_arr
}

function exec(import_filename, export_filename) {
  var buffer = new String
  var slug_arr = new Array
  var data_arr = new Array
  var stage_obj = new Object
  var export_arr = new Array
  var export_str = new String
  var keys = new Array
  fs.readFile('./records/'+ import_filename + ".csv", (err, data) => {
    if (err) { throw err }
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
    }
  })
}

function normal_rows(obj) {
  var keys = Object.keys(obj)
  var numbers = new String
  var calls = new String
  var trail = new String
  for (let i = 0; i < keys.length; i++) {
    trail = (i === keys.length - 1) ? "\n" : ","
    numbers += keys[i] + trail
    calls += obj[keys[i]] + trail
  }
  return numbers + calls
}


function exportCSV(filename, data) {
  var path = "./exports/" + filename + ".csv"
  fs.writeFile(path, data, function(err) {
    if(err) {
      return console.log(err);
    }
    console.log("saved " + path);
  });
}

exec(filename,'schema-export')
