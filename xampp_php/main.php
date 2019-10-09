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
  $patts = array(
    'cc' => '/^\/[a-z|\-]+\/$/',
    'child' => '/^\/([a-z|\-]+\/){2}$/',
    'g_child' => '/^\/([a-z|\-]+\/){3}$/',
  );
  $map =  array(
    'cc' => [],
    'child' => [],
    'g_child' => [],
  );
  $urls = [];
  $keys = array_keys($patts);
  foreach($url_col as $url) {
    $urls[] = str_replace($domain,'',$url);
  }
  foreach($urls as $url) {
    foreach($keys as $key) {
      $test = preg_match($patts[$key],$url,$matches);
      if ($test) {
        $map[$key][] = $url;
      }
    }
  }
  return $map;
}

function get_nest($map) {
  $depth = 0;
}

$map = get_tree($my_domain,$map_cols["URL"]);
error_log(var_dump($map));

$str = Schema::make_export_str($new_schema);
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
