<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>
    yuck
  </title>

<?php
$score1 = 0;
$score2 = 0;
$schema = array();
$schema_scores = array();
$result = -1;

if ( isset($_POST['query']) && isset($_POST['keywords1']) &&
  isset($_POST['keywords2']) ) {
    $raw_schema = [];
    $clean_scheme = [];
    for ($i = 0; $i < 2; $i++) {
      $raw_schema = explode(",",$_POST['keywords' . strval($i+1)]);
      $this_scheme = [];
      foreach ($raw_schema as $raw_scheme) {
          $clean_scheme = trim(preg_replace('/\s\s+/',' ', $raw_scheme));
          $this_scheme[] = explode(" ", $clean_scheme);
      }
      $schema[] = $this_scheme;
    }

  $query = $_POST['query'];
  $match_string = trim(preg_replace('/\s\s+/',' ', $query));
  $match_arr = explode(" ", $match_string);
  $keywords1 = $_POST['keywords1'];
  $keywords2 = $_POST['keywords2'];
  $error = "";
} else {
  $query = (isset($_POST['query'])) ? $_POST['query'] : "";
  $keywords1 = (isset($_POST['keywords1'])) ? $_POST['keywords1'] : "";
  $keywords2 = (isset($_POST['keywords2'])) ? $_POST['keywords2'] : "";
  $error = "incomplete form";
}

function testForExactMatch($arr1,$arr2) {
  return false;
}

function testForPartialMatch_Unequal($long_arr,$short_arr) {
  $slug_length = count($short_arr);
  $tiers = count($short_arr);
  $trials = 0;
  $test_slug = "";
  $score = 0;
  for ($tier_index = $tiers; $tier_index > 0; $tier_index--) {
    $trials = count($short_arr) - $slug_length + 1;
    $slug_index = 0;
    for ($trial_index = 0; $trial_index < $trials; $trial_index++) {
      print("<br/>tier " . strval($tier_index) );
      print("<br/><p style='padding-left:2em'>trial " . strval($trial_index) . "</p>");
      print("<p style='padding-left:2em'>of " . strval($trials) . " trials</p>");
      $test_slug = "";
      for ($elm_index = 0; $elm_index < $slug_length; $elm_index++) {
        $test_slug .= ($elm_index === $slug_length-1) ? $short_arr[$elm_index + $slug_index] :
          $short_arr[$elm_index + $slug_index] . " ";
      }
      print("<br/><p style='padding-left:2em'>test slug: " . $test_slug);
      $score += ( strpos(implode(" ",$long_arr),$test_slug) || strpos(implode(" ",$long_arr),$test_slug) === 0 ) ?
        $slug_length + $slug_length-1 : 0;
      print("<br/><p style='padding-left:2em'>tested against: " . implode(" ",$long_arr)  . "</p>");
      print("<br/><p style='padding-left:2em'>score: " . strval($score) . "</p>");
      $slug_index++;
    }
    $slug_length--;
  }
  return $score;
}

for ($i = 0; $i < count($schema); $i++) {
    $this_scheme = $schema[$i];
    $this_scheme_score = 0;
    for ($ii = 0; $ii < count($this_scheme); $ii++) {
      $this_query = $this_scheme[$ii];
      $match_score = 0;
      print("<br>SCHEME[" . strval($i). "]<br/>QUERY[" . strval($ii). "]");
      if ( count($this_query) === count($match_arr) ) {
        if (!testForExactMatch($this_scheme, $match_arr)) {
          $match_score += testForPartialMatch_Unequal($this_query, $match_arr);
        } else {
          $result = $i;
        }
      } else {
        $match_score += ( count($this_query) > count($match_arr) ) ?
           testForPartialMatch_Unequal($this_query, $match_arr) :
           testForPartialMatch_Unequal($match_arr, $this_query);
      }
    $this_scheme_score += $match_score;
   }
   $schema_scores[] = $this_scheme_score;
}
/*
print("schema: ");
foreach ($schema as $key => $value) {
  print("key: " . $key);
  print("<br/>value: ");
  print("<br\><p style='padding-left:2em;'>scheme:</p> ");
  foreach ($value as $key1 => $value1) {
    print("<br\><p style='padding-left:2em;'>key: " . $key1 . "</p>");
    print("<br\><p style='padding-left:2em;'>value: </p>");
    print("<br\><p style='padding-left:4em;'>query: </p>");
    foreach ($value1 as $key2 => $value2) {
      print("<br\><p style='padding-left:4em;'>key: " . $key2 . "</p>");
      print("<br\><p style='padding-left:4em;'>value: " . $value2 . "</p>");
    }
  }
}
*/

print("schema scores: ");
foreach ($schema_scores as $key => $value) {
  print("<br/>scheme key: " . $key);
  print("<br\><p style='padding-left:2em;'>score: " . $value . "</p>");

}
?>

  <link rel="stylesheet" href="./css/yuckstyle.css"/>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!--<script>
  src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js">
</script>-->

</head>
<body>

  <div id="app">
    <form action="rank_schema.php" method="POST">
      <input name="query" type=text value="<?php $query ?>"/>
      <input name="keywords1" type=text value="<?php $keywords1 ?>"/>
      <div id="score1" class="score">
        <?php $score1 ?>
      </div>
      <input name="keywords2" type=text value="<?php $keywords2 ?>"/>
      <div id="score2" class="score">
        <?php $score2 ?>
      </div>
      <div class="error">
        <?php $error ?>
      </div>
      <input name="submit" type="submit" value="go"/>
    </form>
  </div>
</body>

</html>

<?php


?>
