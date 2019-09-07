class PDF_Redirects {
  constructor(data_arr) {
    this.fs = require("fs-extra")
    this._from = []
    this._to = []
    data_arr.forEach( (e) => {
      this._from.push(e[0])
      this._to.push(e[0])
    })
  }

  get_dir_slugs(url_string,content_dir) {
    var url_indexed = url_string.split('/')
    var content_index = (content_dir) ? url_indexed.indexOf(content_dir) : 0
    var result_arr = []
    for (let i = content_index+1; i < url_indexed.length; i++) {
      result_arr.push(url_indexed[i])
    }
    return result_arr
  }

  get_mkdir_line(dir_slugs) {
    var result_str = 'mkdir '
    result_str += '"'
    dir_slugs.forEach( (e) => {
      result_str += e;
      result_str += '/'
    })
    result_str += '"'
    return result_str
  }

  get_mkdir_lines(content_dir) {
    var result_str = ''
    this._from.forEach( (e) => {
      result_str += this.get_mkdir_line(this.get_dir_slugs(e, content_dir))
      result_str += "\r\n"
    })
    return result_str
  }

  get_nested_echo(dir_slugs, filename_w_ext, content) {
    var result_str = 'echo '
    result_str += content
    result_str += ' > '
    dir_slugs.forEach( (e) => {
      result_str += e
      result_str += '/'
    })
    result_str += filename_w_ext
    result_str += '\r\n'
    return result_str
  }

  get_echo_lines(content_dir, filename_w_ext, content) {
    var result_str = ''
    var dir_slugs = ''
    this._from.forEach( (e) => {
      dir_slugs = this.get_dir_slugs(e, content_dir)
      result_str += this.get_nested_echo(dir_slugs, filename_w_ext, content)
    })
    return result_str
  }

  export_batch_commands(export_str, filename, dir_path) {
    var path = "../" + dir_path + "/" + filename + ".bat"
    this.fs.writeFile(path, export_str, function(err) {
      if(err) {
        return console.log(err)
      }
      console.log("saved " + path)
    })
  }

}



module.exports = PDF_Redirects
