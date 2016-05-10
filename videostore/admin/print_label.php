<?
  require('includes/application_top.php');
  $oID = $_GET['oID'];

  include(DIR_WS_CLASSES . 'order.php');

  $order = new order($oID);

$home_address = trim(nl2br(STORE_NAME_ADDRESS));
$part = explode('<br />', $home_address);
?>
<html>
<style>
body{
	margin: 0px;
	padding: 0px;
}

</style>
<body>
<table cellspacing="0" cellpadding="0" border="0" width="310" height="502" style="table-layout:fixed;">
	<tr><td valign="top" align="left"><b>FROM:</b><br/><span style="font-size:17px;"><?=strtoupper($part[0].'<br/>'.$part[1].'<br/>'.$part[2].'<br/>'.$part[3])?></span><br/><br/><br/></td></tr>
	<tr><td valign="top" align="left"><b>TO:</b></td></tr>
	<tr><td valign="top" align="right"><?php echo strtoupper(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')); ?><br/><br/><br/><br/><br/></td></tr>
	<tr><td valign="bottom" align="right"><span style="font-size:15px;"><?php echo strtoupper(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')); ?></span></td></tr>
</table>
</body>
</html>