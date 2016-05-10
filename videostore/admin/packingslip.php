<?php
/*
  $Id: packingslip.php,v 1.7 2003/06/20 00:40:10 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

if ($_GET['action']=='setstatus'){
		if (intval($_POST['orders_id'])!=0){
			$sql_query = "UPDATE ".TABLE_ORDERS." SET orders_status='22' where orders_id='".$_POST[orders_id]."'";
			//echo $sql_query."<br/>";
			tep_db_query($sql_query);
			}
	echo "<script>window.location.href='packingslip.php?oID=".$_POST['orders_id']."';</script>";
	exit;

	}

$arr = array(
'0'=>'!',
'1'=>'"',
'2'=>'#',
'3'=>'$',
'4'=>'%',
'5'=>'&',
'6'=>'\'',
'7'=>'(',
'8'=>')',
'9'=>'*',
'10'=>'+',
'11'=>',',
'12'=>'-',
'13'=>'.',
'14'=>'/',
'15'=>'0',
'16'=>'1',
'17'=>'2',
'18'=>'3',
'19'=>'4',
'20'=>'5',
'21'=>'6',
'22'=>'7',
'23'=>'8',
'24'=>'9',
'25'=>':',
'26'=>';',
'27'=>'&lt',
'28'=>'=',
'29'=>'&gt',
'30'=>'?',
'31'=>'@',
'32'=>'A',
'33'=>'B',
'34'=>'C',
'35'=>'D',
'36'=>'E',
'37'=>'F',
'38'=>'G',
'39'=>'H',
'40'=>'I',
'41'=>'J',
'42'=>'K',
'43'=>'L',
'44'=>'M',
'45'=>'N',
'46'=>'O',
'47'=>'P',
'48'=>'Q',
'49'=>'R',
'50'=>'S',
'51'=>'T',
'52'=>'U',
'53'=>'V',
'54'=>'W',
'55'=>'X',
'56'=>'Y',
'57'=>'Z',
'58'=>'[',
'59'=>'\\',
'60'=>']',
'61'=>'^',
'62'=>'_',
'63'=>'`',
'64'=>'a',
'65'=>'b',
'66'=>'c',
'67'=>'d',
'68'=>'e',
'69'=>'f',
'70'=>'g',
'71'=>'h',
'72'=>'i',
'73'=>'j',
'74'=>'k',
'75'=>'l',
'76'=>'m',
'77'=>'n',
'78'=>'o',
'79'=>'p',
'80'=>'q',
'81'=>'r',
'82'=>'s',
'83'=>'t',
'84'=>'u',
'85'=>'v',
'86'=>'w',
'87'=>'x',
'88'=>'y',
'89'=>'z',
'90'=>'&Acirc;',
'91'=>'&Atilde;',
'92'=>'&Auml;',
'93'=>'&Aring;',
'94'=>'&AElig;',
'95'=>'&Ccedil;',
'96'=>'&Egrave;',
'97'=>'&Eacute;',
'98'=>'&Ecirc;',
'99'=>'&Euml;'
);

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");


  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);

//  $products_id_query = tep_db_query("select products_id from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" $oID "'");
//  $products_id = tep_db_fetch_array($products_id_query);

 // $warehouse_location_query = tep_db_query("select products_warehouse_location from " . TABLE_PRODUCTS . " where products_id = '" $order->products[$i]['id'] "'");
 // $warehouse_location = tep_db_fetch_array($warehouse_location_query);

  $shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $oID . "' and class = 'ot_shipping'");
  $shipping_method = tep_db_fetch_array($shipping_method_query);

  $gift_wrap_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $oID . "' and class = 'ot_giftwrap'");
  $gift_wrap = tep_db_fetch_array($gift_wrap_query);

  $affiliate_image = tep_db_fetch_array(tep_db_query("select a.affiliate_logo from affiliate_affiliate a LEFT JOIN affiliate_sales b on (b.affiliate_id=a.affiliate_id) where b.affiliate_orders_id='".$oID."' limit 1"));
  $orders_po = tep_db_fetch_array(tep_db_query("select purchase_order_number from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "' limit 1"));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
.bar-code{
	font-family: Bar 25i b HR;
	font-size:36pt;
}

.bar-code_upc{
	font-family: Bar 25i b HR;
	font-size:30pt;
}

</style>

<script>

	function printIt(){
		document.getElementById('topTable').style.display = 'none';
		window.print();
		document.body.onfocus = doneyet;
	}

	function doneyet() {   
		oID = '<?=$oID?>';
		document.body.onfocus = "";   
		if (confirm("Do you want to set status SENT TO WAREHOUSE for those order?"))
			document.chgStatus.submit();
		else
			window.location.href="packingslip.php?oID="+oID;
	} 
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<table border="0" width="743" cellspacing="0" cellpadding="0" id="topTable"><tr><td colspan="2" align="center"><br/><input type="button" value="&nbsp;Print Orders&nbsp;" onclick="javascript:printIt();" /></td></tr></table>
<table><form action="packingslip.php?action=setstatus" method="post" name="chgStatus"><input type="hidden" name="orders_id" value="<?=$oID?>" /></form></table>
<!-- body_text //-->
<table border="0" width="743" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
	   <td colspan="2" style="font-size:25pt;"><center>Packing Slip</center>
	   </td>
	  </tr>
	  <tr>
        <td class="smallText"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td align="right">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
<?
	if ($gift_wrap['title'] <> '')
		 	  echo "<img src='images/giftwrap.jpg' border='0' />";
?>
		</td>
		<td align="right">

<?
if (trim($affiliate_image[affiliate_logo])=="") {
?>
	<img src="../images/header/logo_th.png">
<?} else{ ?>
	<img src="../images/<?=trim($affiliate_image[affiliate_logo])?>">
<? } ?>
</td></tr></table>
</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><?php echo tep_draw_separator(); ?></td>
      </tr>
      <tr>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><b>PURCHASED BY:</b></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo strtoupper(tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>')); ?></td>
          </tr>

          <tr>
            <td class="smallText"><?php echo $order->customer['telephone']; ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
          </tr>
        </table></td>
        <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><b>SHIPPED TO:</b></td>
          </tr>
          <tr>
            <td class="smallText">
		<?php echo strtoupper(tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>')); ?>
<?
	if (trim($order->info['comments_slip'])!=''){
	echo "<br/><br/><div style='width: 250px;padding: 5px;background:#B4AEAE;border: 1px black solid'>".nl2br($order->info['comments_slip'])."</div>";
	}
?>      

	    </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2" width="100%">
	  <!--<tr>      
        <td class="smallText" nowrap><b>Order Number:</b></td>
        <td class="smallText"><?php echo $oID; ?></td>
      </tr>-->
	  <tr>
        <td class="smallText" nowrap><b>Order number:</b></td>
        <td colspan="2"><span class="bar-code">
<?php 
if ((strlen($oID)%2)!=0)
	$ids = "0".$oID; else $ids = $oID;

$tot = strlen($ids)/2;
$final = $ids;
$string = '{';
	for ($i=0;$i<$tot; $i++){
		$show = substr($final, $i*2,2);
		$string .=$arr[intval($show)];
	}
$string .= '}';
echo $string;
?></span>
&nbsp;&nbsp;<b class="smallText">Date of Purchase:</b>&nbsp;<span class="smallText"><?php echo $order->info['date_purchased']; ?></span></td>
	</td>
      </tr>
<?
    $tot_count = 0;
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) $tot_count = $tot_count + $order->products[$i]['qty'];
?>
      <tr>
        <td class="smallText" nowrap><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="smallText" width="85%"><?php echo $order->info['payment_method']; 
		if (trim($orders_po[purchase_order_number])!=''){
				echo "&nbsp;(".trim($orders_po[purchase_order_number]).")";
				}
?>
	</td><td align="right" style="padding: 3px;border:1px black solid;"><?=$tot_count?></td>
      </tr>
	  <tr>
        <td class="smallText" nowrap><b>Shipping Method:</b></td>
        <td class="smallText" colspan="2"><?php echo $shipping_method['title'] ?></td>
      </tr>
      <tr>
	 <?php   
	  if ($gift_wrap['title'] <> '')
		  echo "<td class='smallText' nowrap><b>Gift Wrap:</b></td><td class='smallText'>Yes</td>";
	 ?>      
      <tr>
    </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr>
	<td class="smallText"></td>
	<td class="smallText" align="center"><u><b>QTY</b></u></td>
	    <td class="smallText"><u><b>Products</b></u></td>
        <td class="smallText" width="80"><u><b>Model</b></u></td>
        <td class="smallText" width="80"><u><b>Set type</b></u></td> 
	<td class="smallText" width="80" align="center"><u><b>Prev_Shp_Dte</b></u></td>
	<td class="smallText" width="80"><u><b>Rights</b></u></td>
<!--Changes for set_type-->
		<td class="smallText" width="3" ></td>
      </tr>
<tr><td class="dataTableContent" colspan="9" align="center"><?=tep_draw_separator()?></td></tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

	  $warehouse_location_query = tep_db_query("select products_date_available, has_rights, products_upc, products_image, products_warehouse_location, products_set_type_id, products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $order->products[$i]['id'] . "'");
   	  $warehouse_location = tep_db_fetch_array($warehouse_location_query);

////////////
	  $product_set_query = tep_db_query("select products_set_type_name from " . TABLE_SET_TYPE . " where products_set_type_id = '" . $warehouse_location['products_set_type_id'] . "'");
	  $product_set_type = tep_db_fetch_array($product_set_query);

	  if ($product_set_type['products_set_type_name'] == '')
	  	$product_set_type['products_set_type_name'] = '-';
////////////////
$date_avail = '';
$part = explode("-",substr($warehouse_location[products_date_available],0,10));
$date_available = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
$curr_date = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
if (intval($date_available)>$curr_date) $date_avail = '&nbsp;<span class=markProductOutOfStock>**Pre-Ordered Item will ship on '.$part[1].'-'.$part[2].'-'.$part[0].'**</span>';
	else
		$date_avail = '';

$has_rights = '';
$letter = '';
if ($warehouse_location[products_quantity]==0) $letter = 'Z'; 
if ($warehouse_location[products_quantity]<0) $letter = 'N';

if ($warehouse_location[has_rights]==0) 
	$has_rights = 'Home Use Only';
	else
	$has_rights = '<font color=red>Limited Public Perfromance Rights</font>';


$shipped = tep_db_fetch_array(tep_db_query("select date_shipped, date_shipped_checkbox from orders_products where products_id='".$order->products[$i]['id']."' and orders_id='".$oID."' limit 1"));
//$shipped = tep_db_fetch_array(tep_db_query("select date_shipped from orders_products where products_id='".$order->products[$i]['id']."' and date_shipped is NOT NULL and date_shipped_checkbox='1' order by date_shipped DESC limit 1"));

//if ($is_shipped[date_shipped_checkbox]!=1) $shipped[date_shipped]=0;

if (trim($warehouse_location[products_image])!='')
	$img = '<img src="../images/'.trim($warehouse_location[products_image]).'"' . 'style="width:60px;height:90px;" />';
	else
	$img = '<img src="../images/spacer.gif" />';


$upc = '';
$upc_line = '';
if (trim($warehouse_location['products_upc']!='')) {

$upc = trim($warehouse_location['products_upc']);
if ((strlen($upc)%2)!=0)
	$ids = "0".$upc; else $ids = $upc;

$tot = strlen($ids)/2;
$final = $ids;

$upc_line = '{';
	for ($k=0;$k<$tot; $k++){
		$show = substr($final, $k*2,2);
		$upc_line .=$arr[intval($show)];
	}
$upc_line .= '}';
}

$date_shipped = '';

if (trim($shipped['date_shipped'])!=''){
$part = explode("-",$shipped['date_shipped']);
$date_shipped = mktime(0, 0, 1, $part[1], $part[2], $part[0]);
if (intval($date_shipped)>0) 
	$date_shipped = $shipped['date_shipped'];
	else
	$date_shipped = '';
}

$class = '';
if (intval($shipped[date_shipped_checkbox])==1) $class="#C3C3C3";

      echo '      <tr bgcolor="'.$class.'">' . "\n" .
	   '	    <td class="dataTableContent" valign="top" align="center" rowspan="2">'.$img.'</td>'.
	   '        <td class="dataTableContent" valign="top" align="center" style="font-weight:bold;font-size:14px;">'. $order->products[$i]['qty'] . '&nbsp; </td>' .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name']  .$date_avail. '</td>' .
           '        <td class="dataTableContent" valign="top" nowrap>' . $order->products[$i]['model'] . '</td>' .
           '        <td class="dataTableContent" valign="top" nowrap>' . $product_set_type['products_set_type_name'] . '&nbsp;</td>' .//Chages for set_type
	   '        <td class="dataTableContent" valign="top" nowrap>' . $date_shipped . '&nbsp;</td>' .
		 //'        <td class="dataTableContent" valign="top">' . $order->products[$i]['id'] . '</td>' .
		   '        <td class="dataTableContent" valign="top" width="5"><i>' . $has_rights . '</i></td>' .
		   '        <td class="dataTableContent" valign="top" align="right" width="5"><i>' . $warehouse_location['products_warehouse_location'] . '</i></td>' .
           '        <td class="dataTableContent" valign="top" nowrap>&nbsp;' . $letter . '&nbsp;</td>'.
		 //'        <td class="dataTableContent" valign="top">' . '$' . round($order->products[$i]['price'] ,2) . '</td>' .
           '      </tr><tr bgcolor="'.$class.'"><Td colspan="2">&nbsp;</td><td colspan="3" align="center" valign="top"  class="bar-code_upc">'.$upc_line.'</td><td colspan="3">&nbsp;</td></tr>' .
		   '      <tr class="dataTableRow">' . "\n" .
		   '        <td class="dataTableContent" colspan="9" align="center">' .  tep_draw_separator() . '</td>' .
		   '      </tr>';
    }
?>
		<tr>
<td class="smallText" style="text-align:justify" colspan="9">
<b>Limited Public Performance Rights & Classroom Use of Media</b> - This TravelVideoStore.com program is sold with limited, non - revenue producing public performance rights to show these programs to any group in a school, public library, prison, professional office, hospital, travel agent, church, senior center or military base without further written permission from TravelVideoStore.com when purchased directly through us. Your TravelVideoStore.com packing slip will include a statement of Limited Public Performance License for each title that is authorized.  This program may be played in a videocassette or DVD player in the same room as the audience or over a closed circuit television system within a single building for viewing by an audience in the same building. These programs may neither be broadcast, duplicated or reproduced for any purpose (including for example making a copy to a hard drive for use on a server) nor digitally or electronically delivered on any network unless written permission is obtained and additional fees are quoted by TravelVideoStore.com. No direct or indirect fees may be charged for showing this program in a single room or building setting. Digital rights are available for this program. If you would like to license additional rights, including digital rights, please contact us.	</td></tr>
<tr>
<td class="smallText"  style="text-align:justify; padding-top:5px;" colspan="9"><B>Return and Refund Policy:</B><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We want you to be completely satisfied with every item you purchase from TravelVideoStore.com. If you are not completely satisfied, simply return the item within 30-days of purchase, excluding Royalty-Free Video Clip DVD's. We’ll gladly exchange it or refund your purchase price, excluding shipping and handling charges.  If you received free shipping, the shipping charge would be subtracted from your refund. We will replace any product that is delivered defective at no charge. To return your product, please place in appropriate packaging to insure the safe return and include a copy of your packing slip and mail via an insured method to:<BR><BR>

TravelVideoStore.com<img src="../images/spacer.gif" width="198" height="0" />For copy of your receipt or invoice, log into your account from our website.<BR>
ATTN: RETURNS<img src="../images/spacer.gif" width="153" height="0" />If you have any questions about your order contact customer service at (800) 288-5123<BR>
5420 Boran Dr<img src="../images/spacer.gif" width="193" height="0" />We thank you for your order and look forward to serving you again in the future...<br/>
Tampa, FL 33610
<BR>
<BR><center>www.TravelVideoStore.com</center>
</td>
</tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

