<?php
/*
  $Id: cat_request.php,v 1.0 01/10/2004 Fred Doherty $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>

<script>
	function newDownloads(name){
		window.open('pdf/files/'+name);
	}
</script>

<?
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CAT_REQUEST);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {
    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $addressline1 = tep_db_prepare_input($HTTP_POST_VARS['addressline1']);
	$addressline2 = tep_db_prepare_input($HTTP_POST_VARS['addressline2']);
	$town = tep_db_prepare_input($HTTP_POST_VARS['town']);
	$county = tep_db_prepare_input($HTTP_POST_VARS['county']);
	$country = tep_db_prepare_input($HTTP_POST_VARS['country']);
	$postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
                $catrequestadd =   "The following customer has requested a copy of our print catalog<br><br>" . $name .  "<br>" . $addressline1 .   "<br>" . $addressline2 .   "<br>" . $town .   ",&nbsp;" . $county .   "&nbsp;&nbsp;". $postcode .   "<br>" . $country .   "<br>" . $email_address;

    if (tep_validate_email($email_address)) {

      	tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT,  $catrequestadd, $name, $email_address);

		$date = date ("Y-m-d");
		$cat_query = tep_db_query("Insert into " . TABLE_CATALOG_REQUEST . " values ('".$date."','". $email_address ."','". mysql_real_escape_string($name) ."','". mysql_real_escape_string($addressline1) ."','". mysql_real_escape_string($addressline2) ."','". mysql_real_escape_string($town) ."','". mysql_real_escape_string($county) ."','". mysql_real_escape_string($country) ."','". mysql_real_escape_string($postcode) . "')");

		tep_redirect(tep_href_link(FILENAME_CAT_REQUEST, 'action=success'));
    } else {
      $error = true;

      $messageStack->add('cat_request', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CAT_REQUEST));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
				<?php
				// osCoders.biz - Analystics - start
				/*
				Conditional code added thank to  rrodkey and bfcase
				IMPORTANT -- IMPORTANT - IMPORTANT
				You'll need to update the "xxxx-x" in the samples (twice) above with your own Google Analytics account and profile number. 
				To find this number you can access your personalized tracking code in its entirety by clicking Check Status in the Analytics Settings page of your Analytics account.
				*/
				if ($request_type == 'SSL') {
				?>
				 <script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				 <script type="text/javascript">
				   _uacct="UA-195024-1";
				   urchinTracker();
				 </script>
				<?php
				} else {
				?>
				 <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
				 </script>
				   <script type="text/javascript">
				    _uacct="UA-195024-1";
				    urchinTracker();
				 </script>
				<?
				}
				// osCoders.biz - Analistics - end
				?>
<?php include ('includes/ssl_provider.js.php'); ?>

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
    <td width="100%" valign="top"><?php echo tep_draw_form('cat_request', tep_href_link(FILENAME_CAT_REQUEST, 'action=send')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_cat_request.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
<tr><td>
<br><br>By adding your name to our catalog list, you will receive our new catalog when it is shipped. Thank you for visiting TravelVideoStore.com.  You may also download a catalog that you can print from this screen by selecting one of the buttons below, or on any category or search results page.</td></tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('cat_request') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('cat_request'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>
      <tr>
        <td class="main" align="center"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td width="23%">
		<table>
              <tr>
                <td class="main"><?php echo ENTRY_NAME; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('name'); ?></td>
              </tr>
		</table>
		</td>
		<td width="100%">
		<table>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('email'); ?></td>
              </tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
              <tr>
                <td class="main"><?php echo ENTRY_ADDRESSLINE1; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('addressline1'); ?></td>
              </tr>
			 </table>
		</td>
		<td>
			<table>
			  <tr>
                <td class="main"><?php echo ENTRY_ADDRESSLINE2; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('addressline2'); ?></td>
              </tr>
			 </table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
	   <table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td width="23%">
		<table border="0" width="100%">
			<tr>
                <td class="main"><?php echo ENTRY_TOWN; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('town'); ?></td>
              </tr>
		</table></td>
		<td width="23%">
		<table border="0" width="100%">
			  <tr>
                <td class="main"><?php echo ENTRY_COUNTY; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('county'); ?></td>
              </tr>
		</table></td>
		<td width="54%">
		<table border="0" width="100%">
			  <tr>
                <td class="main"><?php echo ENTRY_POSTCODE; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('postcode'); ?></td>
              </tr>
		</table>
		</td></tr></table>
		</td>
	</tr>
	<tr><td colspan="2">
		<table>
			  <tr>
                <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('country'); ?></td>
              </tr>
            </table>
</td></tr></table>			
	   </td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
      </tr>
      <tr>
	<td align="center">
		<table cellspacing="0" cellpadding="3" border="0">
		<tr>
			<td><a href="#" onclick="newDownloads('FULL.pdf'); return false;"><img src="images/fullcatalog.gif" alt="Download Full Catalog" title="Download Full Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('DVD.pdf'); return false;"><img src="images/dvdcatalog.gif" alt="Download DVD Catalog" title="Download DVD Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('VHS.pdf'); return false;"><img src="images/vhscatalog.gif" alt="Download VHS Catalog" title="Download VHS Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('399.pdf'); return false;"><img src="images/usnationalpark.gif" alt="Download US National Park Catalog" title="Download US National Park Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('401.pdf'); return false;"><img src="images/cruisevideocat.gif" alt="Download Cruise Video Catalog" title="Download Cruise Video Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('444.pdf'); return false;"><img src="images/scenicmusiccat.gif" alt="Download Scenic Music Catalog" title="Download Scenic Music Catalog" border="0"/></a>
			<a href="#" onclick="newDownloads('516.pdf'); return false;"><img src="images/imaxcat.gif" alt="Download IMAX Catalog" title="Download IMAX Catalog" border="0"/></a></td>
		</tr>
		</table>
	</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
      </tr>

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></form></td>
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