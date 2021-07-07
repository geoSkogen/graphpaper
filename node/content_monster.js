'use strict'

var Schema = require('./schema.js')

class ContentMonster {
  constructor(cmd_table,filename_table,format,default_txt,content_dir) {
    this._cmds = Schema.get_indexed_rows(cmd_table)
    this._files = Schema.get_indexed_rows(cmd_table)
    this._format = format
    this._default_body = default_txt
    this._DIR = content_dir
  }

  body_builder (cmd) {
    var head_str = this.head_builder()
    var body_str = this._default_body
    if (cmd) {
      switch (cmd) {
        case '301' :
          body_str = 'header^(^"Location^: http^:^/^/lotuseaters^/saturn^_3^/^"^, true^, 301^)^;';
          break
        default :
          body_str = this._default_body
      }
    }
    var foot_str = this.foot_builder()
    return head_str + body_str + foot_str
  }

  head_builder() {
    var result = ''
    switch(this._format) {
      case 'php' :
        result = '^<^?php '
        break
    }
    return result
  }

  foot_builder() {
    var result = ''
    switch(this._format) {
      case 'php' :
        result = ' ^?^>'
        break
    }
    return result
  }

  filename_builder(str) {
    var result = (str) ? str : 'index'
    result += '.'
    result += this._format
    return result
  }
}

module.exports = ContentMonster
