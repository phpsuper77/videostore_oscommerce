<?php
/*
  $Id: featured_products.php,v 1.27 2003/06/09 22:35:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>

<link rel="stylesheet" type="text/css" href="menustyle.css" media="screen, print" />
<script src="menuscript.js" language="javascript" type="text/javascript"></script>
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
            <td class="pageHeading">Featured Videos</td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
///// To random featured products
//  list($usec, $sec) = explode(' ', microtime());
//  srand( (float) $sec + ((float) $usec * 100000) );
//  $mtm= rand();
//////
   $featured_products_array = array();
                $featured_products_query_raw = "select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by p.products_date_added DESC, pd.products_name";
// to random//  $featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand($mtm)";
print_r("select p.products_id, p.products_media_type_id, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name, se.series_name, p.products_byline, pd.products_description from " . TABLE_PRODUCTS . " p left join " . TABLE_SERIES . " se on (p.series_id = se.series_id) left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by p.products_date_added DESC, pd.products_name");
   $featured_products_split = new splitPageResults($featured_products_query_raw, 20);

  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
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
        <td> <!-- Featured Products Main Page Box -->
		 <table bgcolor="ffffff" border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($featured_products_split->number_of_rows > 0) {
    $featured_products_query = tep_db_query($featured_products_split->sql_query);
    while ($featured_products = tep_db_fetch_array($featured_products_query)) {


if($oplist[$featured_products['products_id']]){
$already= '<br><font color="red">' . ALREADY_PURCHASED . '</font>';
}


      if ($new_price = tep_get_products_special_price($featured_products['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id']));

      }

	$strip_tag_descript = strip_tags($featured_products['products_description'], '<b>');
	$products_combined = ($featured_products['products_byline'] . '&nbsp;&nbsp;' . $strip_tag_descript);
	//$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 200) . '...' : $products_combined);
	$products_summary ='';
	$products_summary = ((strlen($products_combined) > 100) ? substr($products_combined, 0, 200) . '</b></i><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '"><b>...(more)</b></a>' : $products_combined);

?>

          <tr>
		    <td valign="middle" class="main"><?php

		    /*echo '<img src="../images/media_type/' . $featured_products['products_media_type_id'] . '.gif" border="0"></img>'*/
		    /*changes by kamaraj for VHS/DVD */

						if($featured_products['products_media_type_id']=="1")
						   {
							echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/vhs.html","VHS","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="VHS - Click Here for More Info on VHS Formats"></img></a><BR>' . $featured_products['products_set_type_name'];
						   }
						   else if($featured_products['products_media_type_id']=="2")
						   {
								echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/dvd.html","DVD","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="DVD - Click Here for More Info on DVD Formats"></img></a><BR>' . $featured_products['products_set_type_name'];
						   }
						  else if($featured_products['products_media_type_id']=="4")
						   {
							echo '<a href="javascript:void(0)"	onClick=javascript:window.open("./includes/languages/english/cd.html","CD","width=400,height=400,scrollbars=yes")><img src=../images/media_type/' .
			$featured_products['products_media_type_id'].'.gif border=0 alt="CD - Click Here for More Info on CD Formats"></img></a><BR>' . $featured_products['products_set_type_name'];
							}
						   else
						   {
								echo '<img src=../images/media_type/' . $featured_products['products_media_type_id'] . '.gif border=0 ></img><BR>' .$featured_products['products_set_type_name'];
						}
			/*changes by kamaraj for VHS/DVD ends here */

		    ?></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
            <td valign="top" class="main"><?php echo '<b>' . $featured_products['series_name'] . '</b><br>' . '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '"><u>' . $featured_products['products_name_prefix'] . '&nbsp;<b>' . $featured_products['products_name'] . '</b>&nbsp;' . $featured_products['products_name_suffix'] . '</u></a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>' . $products_summary.$tmp. '</i><br>' . TEXT_PRICE . ' ' . $products_price . $already; ?></td>
            <td align="right" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id']) . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td>
          </tr>
          <tr>
            <td colspüan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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
        </table>
		</td>
      </tr>
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td>
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
	   </td>
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
