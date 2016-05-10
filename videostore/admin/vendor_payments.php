<?php
/*
  $Id: vendor_payments.php,v 1.00 2003/06/26 23:21:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Fernando Borcel
  Copyright (c) 2004 Joseph Passavanti

  Released under the GNU General Public License
*/
function quote_smart($value)
{
   $value = "'" . mysql_real_escape_string($value) . "'";
   return $value;
}
  require('includes/application_top.php');
	$vendor_id = (int) $_GET['vID'];
	$payment_id = tep_db_prepare_input($_GET['pID']);
  switch ($_GET['action']) {
    case 'delete':
      tep_db_query("delete from vendor_payments where id='".$payment_id."'");
      tep_redirect(tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id));
    break;

    case 'insert':
    case 'save':
    	$payment_id     = tep_db_prepare_input($_POST['pID']);
    	$payment     = tep_db_prepare_input($_POST['payment']);
    	$poID           = tep_db_prepare_input($_POST['poID']);
      $payment_date   = tep_db_prepare_input($_POST['date']);
      if (!$payment_date) {
      	$payment_date = 'now()';
      }
      $payment_method = tep_db_prepare_input($_POST['method']);
      $type           = tep_db_prepare_input($_POST['type']);
      $status         = tep_db_prepare_input($_POST['status']);

      $sql_data_array = array(
      	'id'        => $payment_id,
      	'vendor_id' => $vendor_id,
      	'payment' => $payment,
      	'date'      => $payment_date,
      	'method'    => $payment_method,
				'po_id'     => $poID,
				'type'      => $type,
				'status'    => $status
      );

      if ($_GET['action'] == 'insert') {
        tep_db_perform(TABLE_VENDOR_PAYMENTS, $sql_data_array);
      } elseif ($_GET['action'] == 'save') {
      	$sql_data_array['id'] = tep_db_prepare_input($_POST['newpID']);
        tep_db_perform(TABLE_VENDOR_PAYMENTS, $sql_data_array, 'update', "id = '" . $payment_id . "'");
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('vendors');
      }

      tep_redirect(tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&pID=' . urlencode($sql_data_array['id'])));
      break;
  }
///jim+ MSHM  20100220 keep vendor in view
  $vend = tep_db_fetch_array(tep_db_query("select * from vendors where vendors_id=".$vendor_id));
///jim-
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          <td class="pageHeading"><?php

           if (trim($vend[vendors_name])>"")
           $tailpiece= " for ". $vend[vendors_name];

           echo HEADING_TITLE . $tailpiece; ?></td>
                    </tr>
					<tr>
						<td class="smallText" align="right"><?php echo 'Select Vendor:'; ?><?php echo tep_draw_form('vendors_report', FILENAME_VENDOR_PAYMENTS,'','get') . tep_draw_pull_down_menu('vID', tep_get_vendors(),'','onChange="this.form.submit()";');?></form></td>
   			</tr>
					<tr>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
if ( $payment_id && !$vendor_id ) {
	$query_vendor_id = "select `vendor_id` from `".TABLE_VENDOR_PAYMENTS."` where `id` = '{$payment_id}'";
	$res_vendor_id = tep_db_query($query_vendor_id);
	$vendor_id = tep_db_result($res_vendor_id, 0, 'vendor_id');
}
if ( $vendor_id ) {
?>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo('Payment ID - Payment Date - PO ID - Method - Type'); ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
	$query_payments = "select `id`, `vendor_id`, `po_id`, `date`, `method`, `type`, `status`, `payment` from `".TABLE_VENDOR_PAYMENTS."` where `vendor_id` = '{$vendor_id}' order by `id` desc";
	$payments_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $query_payments, $query_payments_numrows);
	$res_payments = tep_db_query($query_payments);
	while ($payment = tep_db_fetch_array($res_payments)) {
		if ( $payment['id'] == $payment_id ) {
			$payment_info = $payment;
			echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&pID=' . urlencode($payment['id']) . '&action=edit') . '\'">' . "\n";
		} else {
			echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&pID=' . urlencode($payment['id'])) . '\'">' . "\n";
		}
?>
                <td class="dataTableContent"><?php echo($payment['id'].' - '.$payment['date'].' - '.$payment['po_id'].' - '.$payment['method'].' - '.$payment['type'].' - $ '.$payment['payment']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( $payment['id'] == $payment_id ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID='.$vendor_id).'&pID=' . urlencode($payment['id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
	}
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $payments_split->display_count($query_payments_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?></td>
                    <td class="smallText" align="right"><?php echo $payments_split->display_links($query_payments_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if ($_GET['action'] != 'new') {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
	$formOptions = array(
		'method' => array(),
		'type' => array(),
		'status' => array()
	);
	foreach ($formOptions as $key => $value) {
		$query_options = "SHOW COLUMNS FROM `".TABLE_VENDOR_PAYMENTS."` LIKE '{$key}'";
		$res_options = tep_db_query($query_options);
		$options = tep_db_result($res_options, 0, 'Type');
		$replacements = array('enum(', '\'', ')');
		$options = str_replace($replacements, '', $options);
		$options = explode(',', $options);
		$options2 = array();
		foreach ($options as $option) {
			$option = array('id' => $option, 'text' => $option);
			$options2[] = $option;
		}
		$formOptions[$key] = $options2;
	}

  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('vendors', FILENAME_VENDOR_PAYMENTS, 'action=insert' . '&vID=' . $vendor_id, 'post' ));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_ID . '<br>' . tep_draw_input_field('pID'));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_PO_ID . '<br>' . tep_draw_input_field('poID'));
      $contents[] = array('text' => '<br>Total Payments:<br>' . tep_draw_input_field('payment'));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_DATE . '<br>' . tep_draw_input_field('date'));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_METHOD . '<br>' . tep_draw_pull_down_menu('method', $formOptions['method']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_TYPE . '<br>' . tep_draw_pull_down_menu('type', $formOptions['type']));
		  $contents[] = array('text' => '<br>' . TEXT_PAYMENT_STATUS . '<br>' . tep_draw_pull_down_menu('status', $formOptions['status']));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('vendors', FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id . '&action=save', 'post'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_ID . '<br>' . tep_draw_input_field('newpID', $payment_id) . "<input type=hidden name=pID value=\"{$payment_id}\">");
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_PO_ID . '<br>' . tep_draw_input_field('poID', $payment_info['po_id']));
      $contents[] = array('text' => '<br>Total Payments:<br>' . tep_draw_input_field('payment', $payment_info['payment']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_DATE . '<br>' . tep_draw_input_field('date', $payment_info['date']));
			$contents[] = array('text' => '<br>' . TEXT_PAYMENT_METHOD . '<br>' . tep_draw_pull_down_menu('method', $formOptions['method'], $payment_info['method']));
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_TYPE . '<br>' . tep_draw_pull_down_menu('type', $formOptions['type'], $payment_info['type']));
		  $contents[] = array('text' => '<br>' . TEXT_PAYMENT_STATUS . '<br>' . tep_draw_pull_down_menu('status', $formOptions['status'], $payment_info['status']));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $vendor_id) . '&pID=' . urlencode($payment_info['id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      $heading[] = array('text' => '<b>' . $payment_info['id'] . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENTS, 'page=' . $_GET['page'] . '&vID=' . $payment_info['vendor_id'] . '&pID=' . urlencode($payment_info['id']) . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;<a onclick="return confirm(\'Do you really wnat to delete this payment?\')" href="vendor_payments.php?page=' . $_GET['page'] . '&vID=' . $payment_info['vendor_id'] . '&pID=' . urlencode($payment_info['id']) . '&action=delete">'.tep_image_button('button_delete.gif', IMAGE_DELETE).'</a>' );

      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_ID . '<br>' . $payment_id);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_PO_ID . '<br>' . $payment_info['po_id']);
      $contents[] = array('text' => '<br>Total Payments:<br>' . $payment_info['payment']);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_DATE . '<br>' . $payment_info['date']);
			$contents[] = array('text' => '<br>' . TEXT_PAYMENT_METHOD . '<br>' . $payment_info['method']);
      $contents[] = array('text' => '<br>' . TEXT_PAYMENT_TYPE . '<br>' . $payment_info['type']);
		  $contents[] = array('text' => '<br>' . TEXT_PAYMENT_STATUS . '<br>' . $payment_info['status']);

      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>