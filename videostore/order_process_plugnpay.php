<?PHP
$PHPSESSID = $HTTP_GET_VARS["PHPSESSID"];
$shipinfo = $HTTP_GET_VARS["shipinfo"];
$shipping_method = $HTTP_GET_VARS["shipping_method"];
?>

<html><center>
<div align="center"><br>
  <br>
  <font color="#000000" size="2" face="Arial, Helvetica, sans-serif"><strong>Thank 
  you for your order.</strong></font> </div>
<div align="center"><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">To 
  complete the final process click on the Finish button below to return to the 
  shopping cart.</font><br>
</div>
<form method="POST" action="checkout_process.php">
  <div align="center">
    <input type="hidden" name="PHPSESSID" value="<?echo $PHPSESSID;?>">
    <input type="hidden" name="shipinfo" value="<?echo $shipinfo;?>">
    <input type="hidden" name="shipping_method" value="<?echo $shipping_method;?>">
    <input type="submit" name="submit" value="Finish">
  </div>
</form>
</html>