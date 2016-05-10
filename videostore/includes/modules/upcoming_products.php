<?php
/*
  $Id: upcoming_products.php,v 1.24 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  $expected_query = tep_db_query("select p.products_id, pd.products_name, pd.products_name_prefix, pd.products_name_suffix, mt.products_media_type_name, se.series_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p left join " . TABLE_MEDIA_TYPE . " mt on (p.products_media_type_id = mt.products_media_type_id) left join " . TABLE_SERIES . " se on (p.series_id = se.series_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where to_days(products_date_available) >= to_days(now()) and p.products_status =1 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
  if (tep_db_num_rows($expected_query) > 0) {
?>
<!-- upcoming_products //-->
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
<?php
    $row = 0;
    while ($expected = tep_db_fetch_array($expected_query)) {
      $row++;
      if (($row / 2) == floor($row / 2)) {
        echo '              <tr class="upcomingProducts-even">' . "\n";
      } else {
        echo '              <tr class="upcomingProducts-odd">' . "\n";
      }

      echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id']) . '"><b>' . $expected['series_name'] . '</b>&nbsp;' . $expected['products_name_prefix'] . '&nbsp;<b>' . $expected['products_name'] . '</b>&nbsp;' . $expected['products_name_suffix'] . '</a>&nbsp;-' . $expected['products_media_type_name'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">&nbsp;' . tep_date_short($expected['date_expected']) . '&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
          </tr>
<!-- upcoming_products_eof //-->
<?php
  }
?>
