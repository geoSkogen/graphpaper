'use strict'

function getUrlParam(rawUrl, keyWord) {
  var result = false;
  var sliced = "";
  if (rawUrl.indexOf(keyWord + "=") != -1) {
    sliced = rawUrl.slice(
      ( rawUrl.indexOf(keyWord + "=") + (keyWord.length +1) ),
      rawUrl.length
    );
    result = sliced.indexOf("&") != -1 ?
      sliced.slice(0, sliced.indexOf("&")) :
      sliced;
  }
  return result;
}

function assembleTag(domain) {
  var tag = document.createElement('script');
  var url = "https://" + domain;
  var string = "window.onload = window.location.assign(\'" + url + "\');";
  tag.innerHTML = string;
  return tag;
}

function helloTag(tag) {
  document.body.appendChild(tag);
}

function isDomain(str) {
  var patt = new RegExp(/^\w+\.(\w{2}|com|net|info|org|gov|edu)$/);
  return patt.test(str);
}

function initLoc(param) {
  var val = getUrlParam(window.location.href, param);
  var el = (isDomain(val)) ? assembleTag(val) : false;
  if (el) {
    helloTag(el);
  }
}

initLoc("name");
