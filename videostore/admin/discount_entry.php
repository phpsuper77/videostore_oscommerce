<?php
/*
  $Id: customers.php,v 1.82 2003/06/30 13:54:14 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  ob_start();


?>


<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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

    <td width="100%" valign="top">

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          	<? $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : ''); ?>
            <td class="pageHeading"><?php echo TEXT_DISCOUNT_ENTRY; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		   <tr>
			   <td valign=middle><br>
					<table border="0" width="75%" cellspacing="0" cellpadding="2">
						<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent"><?php echo "S.no"; ?></td>
						<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DISCOUNT_CODE; ?></td>
						<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DISCOUNT_AMOUNT; ?></td>
						<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DISCOUNT_NAME; ?></td>
						<td class="dataTableHeadingContent">Action</td>
						</tr>
					<?php

						  $discount_query_raw = "select discount_id,discount_code,discount_amount,discount_name from " . TABLE_DISCOUNTS . " order by discount_id";
						  $discount_query = tep_db_query($discount_query_raw);
						  $sno = 1;
						  $aid=$HTTP_GET_VARS['osCAdminID'];
						  while ($discount = tep_db_fetch_array($discount_query)) {
						  $dInfo = new objectInfo($discount);
					?>
								<tr class="dataTableRow" onmouseover='rowOverEffect(this)' onmouseout='rowOutEffect(this)'>

								<td class="dataTableContent"><?php echo $sno; ?></td>
								<td class="dataTableContent"><?php echo $discount['discount_code']; ?></td>
								<td class="dataTableContent"><?php echo $discount['discount_amount']; ?></td>
								<td class="dataTableContent"><?php echo $discount['discount_name']; ?></td>
								<td class="dataTableContent">&nbsp;<a href="<?php PHP_SELF?>?disid=<?echo $discount['discount_id'];?>&discode=<?echo $discount['discount_code'];?>&action=edit&osCAdminID=<?php echo $aid ?>" ><?php echo tep_image_button('button_edit.gif',IMAGE_EDIT)?></a>&nbsp<a href="discount_entry.php?disid=<?echo $discount['discount_id'];?>&osCAdminID=<?php echo $aid ?>&action=delete" onclick="return check();" ><?php echo tep_image_button('button_delete.gif',IMAGE_DELETE)?></a></TD>
								<!--<td class="dataTableContent">&nbsp;<a href="discount_entry.php?osCAdminID=<?php echo $aid ?>&discode=<?echo $discount['discount_code'];?>&action=edit" >Edit</a>&nbsp / &nbsp<a href="discount_entry.php?discode=<?echo $discount['discount_code'];?>" onclick="return check();" >Delete</a></TD>-->
								</tr>
					<?
							$sno++;
					      }
					  ?>
			   </td>
		   </tr>

        </table>
        <br>


			  <table border="0" width="30%" cellspacing="0" cellpadding="2">
			   <tr class="dataTableRow">
			   <td>
				<form name="discount_entry" method="post" action=<?php PHP_SELF;?>>

				<?php
				$heading = array();
				$contents = array();
				if ($HTTP_POST_VARS[action])
					$action = $HTTP_POST_VARS[action];

				switch ($action) {

				  case 'edit':

				  	  $discount_query_raw = "select discount_id,discount_code,discount_amount,discount_name from " . TABLE_DISCOUNTS . " where discount_id = '$disid'";
				  	  $discount_query = tep_db_query($discount_query_raw);
					  $discount = tep_db_fetch_array($discount_query);
					  $aid=$HTTP_GET_VARS['osCAdminID'];
					  $did=$discount['discount_id'];

				      $heading[] = array('text'  => BOX_HEADING_DISCOUNT);
					  $contents[] = array('text' => 'Enter Discount Code  &nbsp;&nbsp;&nbsp;&nbsp; '. tep_draw_input_field('couponcode',$discount['discount_code'], 'size="15"') .'<br>');
					  $contents[] = array('text' => 'Enter Discount Amount &nbsp;'.tep_draw_input_field('discountamt', $discount['discount_amount'], 'size="15"').'<br>');
					  $contents[] = array('text' => 'Enter Discount Name &nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_input_field('discountname', $discount['discount_name'], 'size="15"').'<br>');
					  $contents[] = array('text' => '<br><CENTER>'.tep_image_submit('button_save.gif', IMAGE_SAVE));
					  echo "<input type=hidden name=action value='update'>";
					  $box = new box;
  				  	  echo $box->infoBox($heading, $contents);

      				  break;
      			  case 'update' :

      			  		$id=$HTTP_GET_VARS['disid'];
      			  		$cod=$HTTP_POST_VARS['couponcode'];
      			  		$amt=$HTTP_POST_VARS['discountamt'];
      			  		$discname=$HTTP_POST_VARS['discountname'];
						$update_discount="update ".TABLE_DISCOUNTS." set discount_code='$cod', discount_amount=$amt, discount_name='$discname' where discount_id=$id";
						$update_dis_query=tep_db_query($update_discount);
						header("Location: discount_entry.php?osCAdminID=$aid");


      			 default:
				    	  $heading[] = array('text'  => BOX_HEADING_DISCOUNT);
						  $contents[] = array('text' => 'Enter Discount Code  &nbsp;&nbsp;&nbsp;&nbsp; '. tep_draw_input_field('couponcode', '', 'size="15"') .'<br>');
						  $contents[] = array('text' => 'Enter Discount Amount &nbsp;'.tep_draw_input_field('discountamt', '', 'size="15"').'<br>');
						  $contents[] = array('text' => 'Enter Discount Name &nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_input_field('discountname', '', 'size="15"').'<br>');
						  $contents[] = array('text' => '<br><CENTER>'.tep_image_submit('button_save.gif', IMAGE_SAVE));
					      $box = new box;
  				  		  echo $box->infoBox($heading, $contents);
   					break;
   				}



                ?>
				</form>
      			</tr>
				</table>

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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');


			if(isset($HTTP_POST_VARS['couponcode']) && $HTTP_POST_VARS['couponcode']!=NULL)
			{
				$discount_query_raw = "select discount_id,discount_code,discount_amount,discount_name from " . TABLE_DISCOUNTS . " where discount_code='$HTTP_POST_VARS[couponcode]' order by discount_id";
				$discount_query = tep_db_query($discount_query_raw);
				$discount_row=tep_db_num_rows($discount_query);

				if ($discount_row == 0)
				{
					$aid=$HTTP_GET_VARS['osCAdminID'];
					$codee=$HTTP_POST_VARS['couponcode'];
					$amount=$HTTP_POST_VARS['discountamt'];
					$dname=$HTTP_POST_VARS['discountname'];

					$insert_discount="insert into ". TABLE_DISCOUNTS ."(discount_code,discount_amount,discount_name) values ('$codee','$amount','$dname')";
					$insert_query=tep_db_query($insert_discount);
					header("Location: discount_entry.php?osCAdminID=$aid");
					require(DIR_WS_INCLUDES . 'application_bottom.php');
					exit;
				}
				else
				{
					$aid=$HTTP_GET_VARS['osCAdminID'];
					header("Location: discount_entry.php?osCAdminID=$aid");
					exit;
				}
				print $discount_row;
				exit;
			 }

			 if(isset($HTTP_GET_VARS['discode']) && $HTTP_GET_VARS['action']=="edit")
			 {

			 		$cod=$HTTP_GET_VARS['discode'];
			 		$discount_query_raw = "select * from " . TABLE_DISCOUNTS . " where discount_code='$cod'";
			 		$discount_query = tep_db_query($discount_query_raw);

			 }
			 if(isset($HTTP_GET_VARS['disid']) && $HTTP_GET_VARS['action']=="delete")
			 {

			 	$cod=$HTTP_GET_VARS['disid'];
			 	$discount_query = "delete from discounts where discount_id = '$cod'";
			 	$discount_query_ex=tep_db_query($discount_query);
				header("Location: discount_entry.php?osCAdminID=$aid");
			 }



?>

<SCRIPT LANGUAGE="JAVASCRIPT">

	function check()
	{
 	   if(confirm("Are you sure to delete !!!"))
 	   {
		  return true;
		}
	 	else
	 	{
  		  return false;
		}
	}

</SCRIPT>