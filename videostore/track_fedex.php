<?php
/*
  track_fedex.php
*/

  require('includes/application_top.php');
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TRACK_FEDEX);

	// debugging? 
	
	$debug = 0; // 1 for yes, 0 for no
	
	// get tracking number from form
	$tracking_number = $_GET['track'];
	
	if ($tracking_number) {
		$title = 'Package Tracking Results';
		}
	else {
		$title = HEADING_TITLE;
		}
	
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
			<tr>
				<td class="main">		
		
<?php

if (!$tracking_number) {

?>

	<form name="track_fedex" action="<?php FILENAME_TRACK_FEDEX; ?>" method="get"><b>
Tracking number: <input type="text" name="track">
<input type="submit" value="Submit"></b>
</form>
<?
}

else {

	include(DIR_WS_INCLUDES . 'fedexdc.php');

// get tracking number from form
	$tracking_number = $_GET['track'];

// create new FedExDC object
// For tracking results you do not need an account# or meter#
	$fed = new FedExDC();
	
//tracking
	$track_Ret = $fed->ref_track(array(1537 => $tracking_number,));

// debug (prints array of all data returned)

if ($debug) {

	echo '<pre>';

	if ($error = $fed->getError()) {
	  echo "ERROR :". $error;
		} else {
	  echo $fed->debug_str. "\n<BR>";
	  print_r($track_Ret);
	  echo "\n\n";
	  for ($i=1; $i<=$track_Ret[1584]; $i++) {
	  	echo PACKAGE_DELIVERED_ON . ' ' . $track_Ret['1720-'.$i];
	    echo '\n' . PACKAGE_SIGNED_BY . $track_Ret['1706-'.$i];
		  }
		}

	echo '</pre>';
	}

	if ($error = $fed->getError()) {
	  if (strstr($error, '500139')) {
			echo PACKAGE_NOT_IN_SYSTEM . '<br/><br/>';
			}
		else {
			echo 'ERROR :'. $error;
			}
		} 
	else {
	  for ($i=1; $i<=$track_Ret[1584]; $i++) {
			// list destination
			$dest_city = $track_Ret['15-'.$i];
			$dest_state = $track_Ret['16-'.$i];
			$dest_zip = $track_Ret['17-'.$i];
			$signed_by = $track_Ret['1706-'.$i];
			$delivery_date = $track_Ret['1720-'.$i];
			$delivery_time = $track_Ret['1707-'.$i];
			
			// format date
			$delivery_date = strtotime($delivery_date);
			$delivery_date = date("F j, Y", $delivery_date);
			
			// format time, determine am or pm
			$hour = substr($delivery_time,0,2);
			$minute = substr($delivery_time,2,2);
			if ($hour >= 12) {
				$time_mod = 'pm';
				// make pm hours non-military time
				if ($hour > 12) {
					$hour = ($hour - 12);
					}
				}
			else {
				$time_mod = 'am';
				}
				
// everyone (other than error messages) gets a status report
			if (!$error) {
				echo '<b>' . PACKAGE_DESTINATION . '</b><br/> ' . $dest_city . ', ' . $dest_state . ' ' . $dest_zip . '<br/><br/>';
				echo '<b><b>' . PACKAGE_STATUS . '</b><br/>';
			
// for delivered packages
				if ($signed_by) {
						
// if left without signature, let them know
// (add more as they appear)
					if (strstr($signed_by,'F.RONTDOOR')) {
						$signed_by = DELIVERED_FRONTDOOR . '<br/>';
						}
					if (strstr($signed_by,'S.IDEDOOR')) {
						$signed_by = DELIVERED_SIDEDOOR . '<br/>';
						}
					if (strstr($signed_by,'G.ARAGE')) {
						$signed_by = DELIVERED_GARAGE . '<br/>';
						}
					if (strstr($signed_by,'B.ACKDOOR')) {
						$signed_by = DELIVERED_BACKDOOR . '<br/>';
						}
					echo PACKAGE_DELIVERED_ON . ' ' . $delivery_date . ' at ' . $hour . ':' . $minute . ' ' . $time_mod . '<br/><br/>';

		      echo '<b>' . PACKAGE_SIGNED_BY . '</b><br/>' . $signed_by . '<br/>';					
					}
				else {
					$status_note = $track_Ret['1159-'.$i.'-1'];
					$status_city = $track_Ret['1160-'.$i.'-1'];
					$status_state = $track_Ret['1161-'.$i.'-1'];
					echo '<b>' . PACKAGE_IN_TRANSIT . '</b><br/>';
					echo $status_note . ': ' . $status_city . ', ' . $status_state;
					}
				}
			else {
				echo '&nbsp;';
				}
			}
		}
	}
?>	

      	</td>
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