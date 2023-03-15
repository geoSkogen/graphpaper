<?php
if (!class_exists('Navigator')) {
  include_once 'src/Navigator.php';
}

$data = [
  [1,2,11],
  [1,3,12],
  [1,5,13],
  [1,7,14],
  [3,4,15],
  [4,5,16],
  [7,8,17],
  [8,9,18],
  [2,10,21],
  [3,11,22],
  [5,12,23],
  [8,15,24],
  [9,17,25],
  [11,12,31],
  [12,19,32],
  [13,19,34],
  [13,20,35],
  [6,20,36],
  [14,20,37],
  [14,15,38],
  [16,17,39],
  [16,21,40],
  [11,18,41]
];
/*
$nav = new Navigator($data);
$nav->map->crawl();

$result = $nav->locate(1,16);

print_r($result);
*/


$map = new Map($data);
$navigator = new Navigator($map);
$navigator->locate('1','18');


?>
