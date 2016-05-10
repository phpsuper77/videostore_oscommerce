<?php
/*
  $Id: add_favourites.php, v 1.0
  
  Original Script: Burt
  Updated: Ryan Hobbs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- Add Favourites //-->
<?php
if (strstr ($HTTP_USER_AGENT, "MSIE")) {
    $self = "http://".$HTTP_HOST.$REQUEST_URI;
?>
          <tr>
            <td>
<img src="images/bar-clap.gif">
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_INFORMATION_BOOKMARK
                                );
    new infoBoxHeading($info_box_contents, false, false);

    $rows = 0;
    $info_box_contents = array();
    
    if ($HTTP_GET_VARS['products_id']) {
        $product_box_info = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.language_id = '" . $languages_id . "'");
        $product_box_info_values = tep_db_fetch_array($product_box_info);
        $text = "<a href=\"javascript:window.external.AddFavorite('$self','$HTTP_HOST Travel Videos to The World - " . str_replace("'", "\'", $product_box_info_values["products_name"])."')\">Bookmark ".$product_box_info_values['products_name']."</a>";
    }
    else {
        $text = "<a href=\"javascript:window.external.AddFavorite('$self','$HTTP_HOST Travel Videos to The World')\">Click here to Bookmark Us</a>";
    }

    $info_box_contents[] = array('align' => 'center',
                                   'text'  => $text);

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- Add Favourites_eof //-->
<?php
}
?>



