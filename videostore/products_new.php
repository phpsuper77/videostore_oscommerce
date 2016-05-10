<?php
/*
  $Id: products_new.php,v 1.27 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_NEW));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">New Releases descending by date added to our catalog</td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  $products_new_array = array();

  /*$products_new_query_raw = "select
  p.products_id,
  pd.products_name_prefix,
  pd.products_name,
  pd.products_name_suffix,
  pd.products_description,
  p.products_byline,
  p.products_media_type_id,
  p.products_image,
  p.products_price,
  p.products_tax_class_id,
  p.products_date_added,
  m.manufacturers_name,
  se.series_name,
  st.products_set_type_name
  from " . TABLE_PRODUCTS . " p
  left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id),
  " . TABLE_PRODUCTS_DESCRIPTION . " pd
  left join " . TABLE_SERIES . " se on (p.series_id = se.series_id)
  left join " . TABLE_SET_TYPE . " st on (p.products_set_type_id = st.products_set_type_id)
  where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'
  order by p.products_date_added DESC, pd.products_name";*/

  //################## Added Enable / Disable Categories #################"
  //$products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where c.categories_status=1 and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";

  $products_new_query_raw = "select DISTINCT p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_byline, p.products_media_type_id,p.products_image, p.products_date_added, m.manufacturers_name, se.series_name, st.products_set_type_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), ". TABLE_CATEGORIES . " c," . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join  " . TABLE_SET_TYPE . " st on  (p.products_set_type_id = st.products_set_type_id) where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and c.categories_id = p2c.categories_id and c.categories_status=1 order by p.products_date_added DESC, pd.products_name";
  //################## End Added Enable / Disable Categories #################"
  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($products_new_split->number_of_rows > 0) {
    $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($products_new = tep_db_fetch_array($products_new_query)) {
      if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';

	$strip_tag_descript = strip_tags($products_new['products_description'], '<b>');
	$products_combined = ($products_new['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_descript);
	$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 200) . '...' : $products_combined);

      } else {
        $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
		$strip_tag_descript = strip_tags($products_new['products_description'], '<b>');
		$products_combined = ($products_new['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_descript);
		$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 200) . '...' : $products_combined);
      }
?>
          <tr>
		  <td valign="middle" align="right" class="main"><?php echo '<img src="../images/media_type/' . $products_new['products_media_type_id'] . '.gif" border="0"></img><BR>' . $products_new['products_set_type_name'] ?></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $products_new['products_image'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
            <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '"><b>' . $products_new['series_name'] . '</b><br>' . $products_new['products_name_prefix'] . '&nbsp;<b>' . $products_new['products_name'] . '</b>&nbsp;' . $products_new['products_name_suffix'] . '</a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . $products_summary . '</i><BR>' . TEXT_PRICE . ' ' . $products_price; ?></td>
            <td align="right" valign="middle" class="main">
            	<?php
            		if ($session_started) {
            			echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id']) . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>';
            		} else {echo '&nbsp;';}
         		?>
            </td>
          </tr>
          <tr>
            <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
    }
  } else {
?>
          <tr>
            <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
<?php
  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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
