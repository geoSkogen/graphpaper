<?php
if (!class_exists('Navigator')) {
  include_once 'includes/classes/navigator.php';
}

$data = [
  [1,2,3],
  [2,3,4],
  [3,4,5],
  [2,5,4],
  [3,6,3],
  [4,7,2],
  [3,5,5],
  [1,5,6],
  [2,6,7],
  [3,7,8],
  [3,8,9]
];

$nav = new Navigator($data);
$nav->map->crawl();


?>
