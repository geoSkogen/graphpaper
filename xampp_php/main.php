<?php

require 'schema.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_schema = [];
$new_row = [];
$this_schema = new Schema('mni-map', '../records');
$map_cols = Schema::get_labeled_columns($this_schema->data_index);

$my_domain = "https://mynewimage.net";

function get_path_arrs($domain, $url_col) {
  $uris = [];
  $slug_arr = [];
  foreach($url_col as $url) {
    $slug_arr = explode( '/', str_replace($domain,'',$url) );
    array_splice($slug_arr,0,1);
    array_splice($slug_arr,-1,1);
    $uris[] = $slug_arr;
  }
  return $uris;
}

function get_branches($paths) {
  $branches = [];
  foreach ($paths as $slug_arr) {
    if (count($slug_arr)) {
      if ($branches[count($slug_arr)]) {
        $branches[count($slug_arr)][] = $slug_arr;
      } else {
        $branches[count($slug_arr)] = [$slug_arr];
      }
    }
  }
  $branches[0] = '/';
  return $branches;
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

function get_table($branches, $uris) {
  $table = [];
  $diff = 0;
  foreach( $uris as $uri ) {
    if ( count($uri) != count($branches) ) {
      $diff = count($branches) - count($uri);
      for ($i = 0; $i < $diff; $i++) {
        $uri[] = '';
      }
    }
    $table[] = $uri;
  }
  return $table;
}

function nest_dirs($path_arrs, $tier_1_arrs) {
  $new_table = [];
  $dir_depth = 0;
  
  foreach( $tier_1_arrs as $tier_1_arr ) {
    $dir_depth = get_dir_depth($tier_1_arr[0],$path_arrs);
  }
  return $new_table;
}

function get_dir_depth($slug,$path_arrs) {
  $result = 0;
  foreach($path_arrs as $path_arr) {
    $result = (
      ($path_arr[0] === $slug) &&
      (count($path_arr) > $result)
      )? count($path_arr) : $result;
  }
  return $result;
}

function urls_from_arrays($domain,$url_arrs) {
  $table = [];
  foreach ($url_arrs as $url_arr) {
    $table[] = $domain . '/' . join('/', $url_arr) . '/';
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
  $str .= repeat_me(',', ($range-$depth-1) );
  $str .= "\r\n";
  return $str;
}

function get_csv_nest($table, $nest_range) {
  $csv_str = get_nested_csv_line(0,'/',$nest_range);
  foreach($table as $slug_arr) {
    $nest_index = count($slug_arr)-1;
    $slug =  '/' . $slug_arr[$nest_index] . '/';
    $line = get_nested_csv_line($nest_index,$slug,$nest_range);
    $csv_str .= $line;
  }
  return $csv_str;
}

$paths = get_path_arrs($my_domain,$map_cols["URL"]);
$branches = get_branches($paths);
$table = get_table($branches,$paths);
$page_arr = page_array_sequence($branches);
$hrefs = urls_from_arrays($my_domain,$page_arr);
$map_str = Schema::make_export_str($hrefs);
$struct_str = get_csv_nest($page_arr,count($branches));
Schema::export_csv($map_str,'map','exports');
Schema::export_csv($struct_str,'struct','exports');


error_log(print_r($table));



//error_log(print_r($map_cols["URL"]));
//error_log(print_r($hrefs));
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
