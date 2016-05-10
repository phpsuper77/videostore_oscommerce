<?php
/*
  $Id: allcategories.php,v 1.0 2003/30/04

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 HMCservices

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CATEGORIES);

// Set number of columns in listing
define ('NR_COLUMNS', 1);
//
  $breadcrumb->add(HEADING_TITLE, tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?> 
  <title><?php echo TITLE ?></title>
<?php
}
// EOF: WebMakers.com Changed: Header Tag Controller v1.0
?>

<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
			<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <?php	
			 $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
			 while ($languages = tep_db_fetch_array($languages_query)) {
				$languages_array[] = array('id'			=> $languages['languages_id'],
										   'name'		=> $languages['name'],
                                 		   'code'		=> $languages['code'],
                                 		   'image'		=> $languages['image'],
                                           'directory'	=> $languages['directory']);
			 }
          	 for ($i=0; $i<sizeof($languages_array); $i++) {			 
             	$this_language_id = $languages_array[$i]['id'];
				$this_language_name = $languages_array[$i]['name'];
				$this_language_code = $languages_array[$i]['code'];
				$this_language_image = $languages_array[$i]['image'];
				$this_language_directory = $languages_array[$i]['directory'];
				echo " <tr>\n";
             	$categories_query = tep_db_query("SELECT categories_id, categories_name FROM " . TABLE_CATEGORIES_DESCRIPTION . "  WHERE  language_id = $this_language_id ORDER BY categories_name");
				$categories_array = array();
				while($categories = tep_db_fetch_array($categories_query)) {
				   $categories_array[] = array('id'   => $categories['categories_id'],
				   							 'name' => $categories['categories_name']);
				}
				for ($j=0; $j<NR_COLUMNS; $j++) {
					echo "   <td class=main valign=\"top\">\n";
					for ($k=$j; $k<sizeof($categories_array); $k+=NR_COLUMNS) {
						$this_categories_id   = $categories_array[$k]['id'];
						$this_categories_name = $categories_array[$k]['name'];
						echo "     <a href=\"" . tep_href_link(FILENAME_CATEGORIES_INFO, 'name=' .str_replace("/", "&#47;", rawurlencode($this_categories_name)). "/" . "cPath/" . $categories_id . $this_categories_id . (($this_language_code == DEFAULT_LANGUAGE) ? '' : ('&language=' . $this_language_code)), 'NONSSL', false) . "\">" . $this_categories_name . "</a>\n";
					}
					echo "   </td>\n";
				}
			    echo " </tr>\n";
			}
?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
    <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
