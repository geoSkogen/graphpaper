<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>
  </title>

  <link rel="stylesheet" href="./css/yuckstyle.css"/>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!--<script>
  src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js">
</script>-->


</head>
<body>
<?php

include_once './schema.php';

function get_text_line($prop, $index) {
 $result = false;
 $str = "";
 $delimiter = " ";
 if ($_POST[$prop . $index]) {
   $str = $_POST[$prop . $index];
   if (strpos($str," ") > 0 && (strpos($str," ") < strlen($str)-1) ) {
     $result = explode($delimiter, $str);
   } else {
     $result = [$str];
   }
 }
 return $result;
}

function make_export_line($arr) {
  $result = "";
  for ($i = 0; $i < count($arr); $i++) {
    $delimiter = ($i === count($arr)-1) ? "\r\n" : ",";
    $result .= $arr[$i] . $delimiter;
  }
  return $result;
}

function build_export_string($fields_count, $fields_base) {
  $text_val = "";
  $result = "";
  for ($i = 0; $i < $fields_count; $i++) {
    $text_line = get_text_line($fields_base, strval($i));
    $result .= make_export_line($text_line);
    echo make_div_soup($text_line);
  }
  return $result;
}

function make_div_soup($arr) {
  $str = '<div class="flexOuterCenter">';
  foreach ($arr as $el) {
    $str .= '<div class="soup">';
    $str .= $el;
    $str .= '</div>';
  }
  $str .= '</div>';
  return $str;
}

$export_str = build_export_string(5,'test');
Schema::export_csv($export_str,'testy',__DIR__);

?>
</body>
</html>
