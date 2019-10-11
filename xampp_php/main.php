<?php

require 'schema.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
$this_schema = new Schema('mni-map', '../records');
$map_cols = Schema::get_labeled_columns($this_schema->data_index);

$my_domain = "https://mynewimage.net";

function get_tree($domain, $url_col) {
  $urls = [];
  $slug_arr = [];
  $map = [];
  $range = 0;
  foreach($url_col as $url) {
    $urls[] = str_replace($domain,'',$url);
  }
  for ($i = 0; $i < count($urls); $i++) {
    $slug_arr = explode('/',$urls[$i]);
    array_splice($slug_arr,0,1);
    array_splice($slug_arr,-1,1);
    if (count($slug_arr)) {
      if ($map[count($slug_arr)]) {
        $map[count($slug_arr)][] = $slug_arr;
      } else {
        $map[count($slug_arr)] = [$slug_arr];
      }
    }
  }
  return $map;
}

function page_array_sequence($map) {
  $new_map = [];
  foreach ( $map[1] as $tier_1_url) {
    $new_map[] = $tier_1_url;
    for ($i = 2; $i < count($map); $i++) {
      foreach($map[$i] as $tier_i_url) {
        $newest_url_index = count($new_map)-1;
        $newest_url_length = count($new_map[$newest_url_index]);
        $newest_slug_index = $newest_url_length-1;
        $test_slug_index = ($newest_url_length === $i) ?
          $newest_slug_index-1 : $newest_slug_index;

        if ( $tier_i_url[count($tier_i_url)-2] ===
          $new_map[$newest_url_index][$test_slug_index] ) {
            $new_map[] = $tier_i_url;
        }
      }
    }
  }
  return $new_map;
}

function urls_from_arrays($domain,$url_arrs) {
  $table = [];
  foreach ($url_arrs as $url_arr) {
    $table[] = [$domain . '/' . join('/', $url_arr)];
  }
  return $table;
}

function repeat_me($str,$int) {
  $result = "";
  for ($i = 0; $i < $int; $i++) {
    $result .= ",";
  }
  return $result;
}

function get_nested_csv_line($depth,$arg,$range) {
  $str = repeat_me(',',$depth);
  $str .= $arg;
  $str .= repeat_me(',' ,($range-$depth-1) );
  $str .= "\r\n";
  return $str;
}

function get_nest($map) {
  $line = get_nested_csv_line(0,'/',5);
  error_log($line);
}

$map = get_tree($my_domain,$map_cols["URL"]);
$page_arr = page_array_sequence($map);
error_log(strval(count($page_arr)));
$hrefs = urls_from_arrays($my_domain,$page_arr);
$str = Schema::make_export_str($hrefs);
Schema::export_csv($str,'struct','exports');

/*
$cmd_schema = new Schema('cmds-data', '../records');
$c_monster = new ContentMonster(
  $cmd_schema->data_index,
  [],
  'php',
  "//Silence is Golden.",
  'content-dir'
);
$this_nester = new DeepNest($this_schema->data_index);
$mkdir_export_str = $this_nester->get_mkdir_lines($c_monster->_DIR);
$echo_export_str = $this_nester->get_nested_index_echoes($c_monster);
$this_nester->export_batch_commands($mkdir_export_str,'nesting','batch_files');
$this_nester->export_batch_commands($echo_export_str,'indexing','batch_files');
*/
?>
