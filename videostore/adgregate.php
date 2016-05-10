<?
  require('includes/application_top.php');

  $query = tep_db_query("select products_model from products where products_id=" . $_GET['id']);
  $model = tep_db_fetch_array($query);

  $query = tep_db_query("select adgregate_link from  products_adgregate where products_model='" . $model['products_model'] . "'");
  $adgregate  = tep_db_fetch_array($query);


echo $adgregate['adgregate_link'] . "</object>";
?>