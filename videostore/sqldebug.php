<?php
  ini_set('display_errors', '0');
  include('includes/application_top.php');
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>SQLDEBUG</title>
  </head>
  <body>
<?php
  if ($_POST['sql']) {
    $result_query = tep_db_query(stripslashes($_POST['sql']));
    if (tep_db_num_rows($result_query) > 0) {
      $header = '';
      $headerset = false;
      $body = '';
      while ($result = tep_db_fetch_array($result_query)) {
        $body .= '<tr>';

        foreach ($result as $key => $val) {
          if ($headerset == false) {
            $header .= '<th>' . $key . '</th>';
          }
          $body .= '<td>' . $val . '</td>';
        }
        $body .= '</tr>';
        
        $headerset = true;
      }
      $header = '<tr>' . $header . '</tr>';
      echo '<table border="1" width="100%">' . $header . $body . '</table>';
    } else {
      echo 'Affected rows: ' . mysql_affected_rows();
    }
  }
?>
  <form name="sql" action="sqldebug.php" method="post">
  <textarea name="sql" style="width: 100%;height:200px"><?php echo stripslashes($_POST['sql']); ?></textarea>
  <input type="submit" name="submit" value="Submit">
  </form>
</body>
</html>
  