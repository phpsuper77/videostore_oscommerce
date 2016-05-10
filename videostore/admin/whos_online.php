<?php
/*
  $Id: whos_online.php,v 1.32 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//  $xx_mins_ago = (time() - 900);
  $xx_mins_ago = ($current_time - WHOS_ONLINE_AGO);

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// remove entries that have expired
  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>




</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
 
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	  <tr><Td colspan="2"><input type="button" value="Show customers with Cart Content Only"  onclick="location.href='whos_online_cart.php'"/></td></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ONLINE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FULL_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_IP_ADDRESS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY; ?></TD>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ENTRY_TIME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CART_CONTENT; ?>&nbsp;</td><!--CHANGES MADE FOR WHOS_ONLINE.PHP-->
              </tr>
<?php
require(DIR_WS_INCLUDES . 'geoip.inc');
$gi = geoip_open(DIR_WS_INCLUDES . 'GeoIP.dat', GEOIP_STANDARD);

$s=50;
//$s=1000;
if (!isset($_GET['ctr'])) $ctr=0; else $ctr=$_GET['ctr'];
$st = $ctr*$s;
$limit="limit $st, $s";
  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, http_referer, session_id from " . TABLE_WHOS_ONLINE . " order by time_last_click desc ".$limit);
  $whos_online_query_full = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE . " order by time_last_click desc");
  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
    $time_online = ($whos_online['time_last_click'] - $whos_online['time_entry']);
    if ((!isset($HTTP_GET_VARS['info']) || (isset($HTTP_GET_VARS['info']) && ($HTTP_GET_VARS['info'] == $whos_online['session_id']))) && !isset($info)) {
      $info = $whos_online['session_id'];
    }

    if ($whos_online['session_id'] == $info) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . "\n";
    }
?>

<!------------------------------------------------------------------------->
<?php

	$contents = array();
	$session_data_cart_content = "";


	if (isset($whos_online['session_id'])) {

		if (STORE_SESSIONS == 'mysql') {
			$session_data_cart_content = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $whos_online['session_id'] . "'");
			$session_data_cart_content = tep_db_fetch_array($session_data_cart_content);
			$session_data_cart_content = trim($session_data_cart_content['value']);
		} else {
			if ( (file_exists(tep_session_save_path() . '/sess_' . $info)) && (filesize(tep_session_save_path() . '/sess_' . $info) > 0) ) {
				$session_data_cart_content = file(tep_session_save_path() . '/sess_' . $info);
				$session_data_cart_content = trim(implode('', $session_data_cart_content));
			}
		}

		if ($length_cart = strlen($session_data_cart_content)) {
			if (PHP_VERSION < 4) {
				$start_cart_content = strpos($session_data_cart_content, 'cart[==]o');
			} else {
				$start_cart_content = strpos($session_data_cart_content, 'cart|O');
			}
			$content_qty = strstr($session_data_cart_content, 'contents');
			$total_content_qty = substr($content_qty,12,1);
		}
	}
?>
<!------------------------------------------------------------------------->


                <td class="dataTableContent"><?php echo gmdate('H:i:s', $time_online); ?></td>
                <td class="dataTableContent" align="center"><?php echo $whos_online['customer_id']; ?></td>
                <td class="dataTableContent"><?php echo $whos_online['full_name']; ?></td>
                <td class="dataTableContent"><?php echo $whos_online['ip_address']; ?></td>
                <td class="dataTableContent"><?php echo tep_image(DIR_WS_FLAGS . geoip_country_code_by_addr($gi, $whos_online['ip_address']) . '.gif'); ?>&nbsp;<?php echo geoip_country_name_by_addr($gi, $whos_online['ip_address']); ?></TD>
                <td class="dataTableContent"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
                <td class="dataTableContent" align="center"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
                <td class="dataTableContent"><?php if (preg_match('/^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)/i', $whos_online['last_page_url'], $array)) { echo $array[1] . $array[2]; } else { echo $whos_online['last_page_url']; } ?></td>
                <td class="dataTableContent" align="center">
					<?php
							if ((tep_not_null($total_content_qty)) && ($total_content_qty <> 0)){
								echo "Y &nbsp;";
								$total_content_qty=0;
							}
							else {
								echo "N &nbsp;";
								$total_content_qty=0;
							}
					?>
                </td>
              </tr>
<?php
if (strlen($whos_online['full_name'])>4) {
?>
              <tr class="dataTableRow">
               <td colspan="9" class="dataTableContent"><b>User:</b> <?php echo $whos_online['full_name']; ?>&nbsp;<?echo "<a href='".$whos_online['http_referer']."' target=_new>".$whos_online[http_referer]."</a>"; ?></td>
              </tr>
<?php
}
?>
<?php
  }
?>
              <tr>
                <td class="smallText" colspan="9"><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query_full)); ?></td>
              </tr>
	      <tr>
		<td class="smalltext" colspan="9" align="left">Pages:
		
		
		<select name="pagination" id="pagination" onChange="nextPages(this.options[this.selectedIndex].value);">
        <option value="">Go to page...</option>
       <?
			$per = tep_db_num_rows($whos_online_query_full)/$s;
			for ($k=1;$k<$per+1;$k++){
				$t=$k-1;
				echo '<option value="whos_online.php?ctr='.$t.'">'.$k.'</option>'; 
			}
	 ?>
 
    </select>
	
	 <? /*
	$per = tep_db_num_rows($whos_online_query_full)/$s;
	for ($k=1;$k<$per+1;$k++){
		$t=$k-1;
	if ($ctr!=$t)
		echo "<a href='whos_online.php?ctr=".$t."' style='font-weight:bold;'>".$k."</a>&nbsp;&nbsp;";
		else
		echo "<font style='font-weight:bold; color:red;'>".$k."</font>&nbsp;&nbsp;";
		} */
	 ?>
	 <script language="javascript" type="text/javascript">
	 function nextPages(val){ 
		 window.location.href = "http://www.travelvideostore.com/admin/"+val;
	 }
	 </script>
		</td>
	      </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  if (isset($info)) {
    $heading[] = array('text' => '<b>' . TABLE_HEADING_SHOPPING_CART . '</b>');

    if (STORE_SESSIONS == 'mysql') {
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $info . "' limit 0,10");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ( (file_exists(tep_session_save_path() . '/sess_' . $info)) && (filesize(tep_session_save_path() . '/sess_' . $info) > 0) ) {
        $session_data = file(tep_session_save_path() . '/sess_' . $info);
        $session_data = trim(implode('', $session_data));
      }
    }

    if ($length = strlen($session_data)) {
      if (PHP_VERSION < 4) {
        $start_id = strpos($session_data, 'customer_id[==]s');
        $start_cart = strpos($session_data, 'cart[==]o');
        $start_currency = strpos($session_data, 'currency[==]s');
        $start_country = strpos($session_data, 'customer_country_id[==]s');
        $start_zone = strpos($session_data, 'customer_zone_id[==]s');
      } else {
        $start_id = strpos($session_data, 'customer_id|s');
        $start_cart = strpos($session_data, 'cart|O');
        $start_currency = strpos($session_data, 'currency|s');
        $start_country = strpos($session_data, 'customer_country_id|s');
        $start_zone = strpos($session_data, 'customer_zone_id|s');
      }

      for ($i=$start_cart; $i<$length; $i++) {
        if ($session_data[$i] == '{') {
          if (isset($tag)) {
            $tag++;
          } else {
            $tag = 1;
          }
        } elseif ($session_data[$i] == '}') {
          $tag--;
        } elseif ( (isset($tag)) && ($tag < 1) ) {
          break;
        }
      }

      $session_data_id = substr($session_data, $start_id, (strpos($session_data, ';', $start_id) - $start_id + 1));
      $session_data_cart = substr($session_data, $start_cart, $i);
      $session_data_currency = substr($session_data, $start_currency, (strpos($session_data, ';', $start_currency) - $start_currency + 1));
      $session_data_country = substr($session_data, $start_country, (strpos($session_data, ';', $start_country) - $start_country + 1));
      $session_data_zone = substr($session_data, $start_zone, (strpos($session_data, ';', $start_zone) - $start_zone + 1));

      session_decode($session_data_id);
      session_decode($session_data_currency);
      session_decode($session_data_country);
      session_decode($session_data_zone);
      session_decode($session_data_cart);

      if (PHP_VERSION < 4) {
        $broken_cart = $cart;
        $cart = new shoppingCart;
        $cart->unserialize($broken_cart);
      }

      if (is_object($cart)) {
        $products = $cart->get_products();
        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
          $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . $products[$i]['name']);
        }

        if (sizeof($products) > 0) {
          $contents[] = array('text' => tep_draw_separator('pixel_black.gif', '100%', '1'));
          $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total(), true, $currency));
        } else {
          $contents[] = array('text' => '&nbsp;');
        }
      }
    }
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
?>