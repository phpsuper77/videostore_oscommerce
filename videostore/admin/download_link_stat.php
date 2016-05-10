<?php
/*
  $Id: cache.php,v 1.23 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

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
            <td class="pageHeading">DOWNLOAD LINK REPORT</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
		<td>
<? if ($action == '') { ?>
	<input type="button" onclick="location.href='download_link_stat.php?action=detailed'" value="Show All Log" />
<? }  ?>
		</td>
      </tr>
      <tr>
        <td>
<? if ($action == '') { ?>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Product Model</td>
                <td class="dataTableHeadingContent">Product Name</td>
                <td class="dataTableHeadingContent">Total Clicks</td>
              </tr>
<?
  $producers_query_raw = "SELECT count( * ) AS cnt, p.products_model, a.products_id, b.products_name, b.products_name_prefix, b.products_name_suffix FROM `download_link_stat` a LEFT JOIN products_description b ON a.products_id = b.products_id  LEFT JOIN products p ON a.products_id = p.products_id GROUP BY a.products_id ORDER BY `cnt` DESC";
  $producers_query = tep_db_query($producers_query_raw);

while($log = tep_db_fetch_array($producers_query)){
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='download_link_stat.php?action=detailed&products_id=<?=$log[products_id]?>'">
                <td class="dataTableContent"><? echo $log[products_model]?></td>
                <td class="dataTableContent"><? echo $log[products_name_prefix].' '.$log[products_name].' '.$log[products_name_suffix]?></td>
                <td class="dataTableContent"><?=$log['cnt']?></td>
              </tr>
<? 
		}
	} 



if ($action == 'detailed') {?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Date</td>
                <td class="dataTableHeadingContent">Customer Id</td>
                <td class="dataTableHeadingContent">IP</td>
                <td class="dataTableHeadingContent">Model</td>
                <td class="dataTableHeadingContent">Product Name</td>
                <td class="dataTableHeadingContent">Total Clicks</td>
                <td class="dataTableHeadingContent">Cart Contents</td>
              </tr>
<?
$addon_1 = ($products_id!='') ? " a.products_id='".$products_id."'" : "";
$addon_2 = ($info!='') ? " a.session_id='".$info."'" : "";

if ($addon_1!='' or $addon_2 !='') $where = 'where'; 
if ($addon_1!='' and $addon_2 !='') $and = 'and'; else  $and = '';

  $producers_query_raw = "select a.*, p. products_model, b.customers_firstname, c.products_name_prefix, c.products_name, c.products_name_suffix, b.customers_lastname from download_link_stat a LEFT JOIN customers b on (a.customers_id=b.customers_id) LEFT JOIN products p on (a.products_id=p.products_id) LEFT JOIN products_description c on (a.products_id=c.products_id) ".$where." ".$addon_1." ".$and." ".$addon_2." order by a.time_entry desc";
  $producers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $producers_query_raw, $producers_query_numrows);
  $producers_query = tep_db_query($producers_query_raw);
  $counter = tep_db_num_rows($producers_query);
if ($counter!=0){
while($log = tep_db_fetch_array($producers_query)){
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent"><?=date('H:i:s m-d-Y', $log[time_entry])?></td>
                <td class="dataTableContent"><a href='customers.php?selected_box=customers&cID=<?=$log[customers_id]?>&action=edit' target=_new><? echo $log[customers_firstname].' '.$log[customers_lastname]?></a></td>
                <td class="dataTableContent"><?=$log['IP']?></td>
                <td class="dataTableContent"><a href='categories.php?cPath=&pID=<?=$log[products_id]?>&action=new_product' target=_new><? echo $log[products_model]?></a></td>
                <td class="dataTableContent"><a href='categories.php?cPath=&pID=<?=$log[products_id]?>&action=new_product' target=_new><? echo $log[products_name_prefix].' '.$log[products_name].' '.$log[products_name_suffix]?></a></td>
                <td class="dataTableContent">
<?
	$count = tep_db_fetch_array(tep_db_query("select count(*) as cnt from download_link_stat where products_id=".$log[products_id]));
	echo $count[cnt];
?>
		</td>
                <td class="dataTableContent">
<?
	$contents = array();
	$session_data_cart_content = "";


	if (isset($log['session_id'])) {

		if (STORE_SESSIONS == 'mysql') {
			$session_data_cart_content = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $log['session_id'] . "'");
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

			if ((tep_not_null($total_content_qty)) && ($total_content_qty <> 0)){
				echo "Y &nbsp;<a href='".tep_href_link('download_link_stat.php', tep_get_all_get_params(array('info', 'action')) . 'info=' . $log['session_id']."&action=detailed&products_id=".$log['products_id'], 'NONSSL')."'>Click here</a>";
				$total_content_qty=0;
			}
			else {
				echo "N &nbsp;";
				$total_content_qty=0;
			}
?>
		</td>
              </tr>
<? 
	} 
}
	else {
?>
<tr><td colspan="3" align="center" class="smalltext"><b>No items found</b></td></tr>
<? } ?>
                  <tr>
                    <td class="smallText" valign="top" colspan="2"><?php  echo $producers_split->display_count($producers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], str_replace('producers', 'clicks', TEXT_DISPLAY_NUMBER_OF_PRODUCERS)); ?></td>
                    <td class="smallText" align="right" colspan="2"><?php echo $producers_split->display_links($producers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], '&action=detailed'); ?></td>
                  </tr>
<? if ($info != ''){?>
                  <tr>
                    <td class="smallText" valign="top" colspan="6" align="center"><a href='download_link_stat.php?products_id=<?=$products_id?>&action=detailed'><< Back</a></td>
                  </tr>
<? } else {?>
                  <tr>
                    <td class="smallText" valign="top" colspan="6" align="center"><a href='download_link_stat.php'><< Back</a></td>
                  </tr>
<? } ?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  if (isset($info)) {
    $heading[] = array('text' => '<b>Shopping Cart</b>');

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
          $contents[] = array('align' => 'right', 'text'  => 'Subtotal: ' . ' ' . $currencies->format($cart->show_total(), true, $currency));
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
        </table>
<? } ?>
</td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>