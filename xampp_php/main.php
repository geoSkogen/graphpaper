<?php

require 'schema.php';
require 'sitemap_monster.php';
//require 'deep_nest.php';
//require 'content_monster.php';

$new_map = [];
$my_domain = "https://mynewimage.net";

$this_schema = new Schema('mni-map', '../records');
$map_cols = Schema::get_labeled_columns($this_schema->data_index);
$sitemap_monster = new Sitemap_Monster($my_domain,$map_cols["URL"]);
$map_str = Schema::make_export_str($sitemap_monster->new_map);
Schema::export_csv($map_str,'map','exports');
Schema::export_csv($sitemap_monster->csv_str,'struct','exports');

/*
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

function get_nested_page_arr($map) {
  global $new_map;

  function parent_find_child($this_root,$this_slug,$this_tier,$map) {
    global $new_map;
    $child_dirs = is_parent_dir_of($this_root,$this_slug,$this_tier,$map);
    if (count($child_dirs)) {
      //error_log('got match');
      foreach($child_dirs as $child_dir) {
        $new_map[] = $child_dir;
        //error_log('child dir test slug: ' . $child_dir[$this_tier]);
        if (isset($map[$this_tier+1])) {
          parent_find_child($this_root,$child_dir[$this_tier],$this_tier+1,$map);
        }
      }
    }
    //return;
  }

  $roots = $map[1];
  $depth = 0;
  foreach($roots as $root) {
    $root_slug = $root[0];
    $this_slug = $root_slug;
    //$depth = get_dir_depth($root_slug,$map);
    $new_map[] = $root;
    parent_find_child($root_slug,$this_slug,1,$map);
  }
  return $new_map;
}

function is_parent_dir_of($root,$slug,$slug_tier,$branches) {
  $result = [];
  if ($branches[$slug_tier+1]) {
    foreach ($branches[$slug_tier+1] as $uri) {

      if ($uri[$slug_tier-1] === $slug && $uri[0] === $root) {
        $result[] = $uri;
      }
    }
  }
  return $result;
}

function get_path_arrs($domain, $url_col) {
  $uris = [];
  $slug_arr = [];
  $dirs = [];
  foreach($url_col as $url) {
    $slug_arr = explode( '/', str_replace($domain,'',$url) );
    array_splice($slug_arr,0,1);
    array_splice($slug_arr,-1,1);

    $uris[] = $slug_arr;

  }
  return $uris;
}

function get_dir_depth($slug,$map) {
  $result = -1;
  for ($i = count($map)-1; $i > 0; $i--) {
    foreach($map[$i] as $slug_arr) {
      if ($slug_arr[0] === $slug) {
        $result = ($i > $result) ? $i : $result;
        break;
      }
    }
  }
  return $result;
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
//$table = get_table($branches,$paths);
$page_arr = get_nested_page_arr($branches);
$hrefs = urls_from_arrays($my_domain,$page_arr);
$map_str = Schema::make_export_str($hrefs);
$struct_str = get_csv_nest($page_arr,count($branches));
Schema::export_csv($map_str,'map','exports');
Schema::export_csv($struct_str,'struct','exports');
*/

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
