<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>
    yuck
  </title>

  <link rel="stylesheet" href="./css/yuckstyle.css"/>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
<!--<script>
  src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js">
</script>-->
  <?php
    function do_indexed_input($index,$base_name) {
      $prop = $base_name . $index;
      $str = '<input name="' . $prop . '" id="' . $prop . '" class="zeroTest"/>';
      return $str;
    }

    function do_centered_form_fields($field_count) {
      $result = '';
      for ($i = 0; $i < $field_count; $i++) {
        $result .= '<div class="flexOuterCenter">';
        $result .= do_indexed_input($i,'test');
        $result .= '</div>';
      }
      return $result;
    }

   ?>
</head>
<body>
  <div id="app">
    <form id='test_form' method="POST" action="next.php">
      <?php
      echo do_centered_form_fields(5);
      ?>
      <div class="flexOuterCenter">
        <input type="submit" value="GO"/>
      </div>
    </form>
  </div>
</body>

<script src="./lib/select-submit.js"></script>
</html>
