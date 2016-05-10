<?php
/*
  $Id: affiliate_banners_build.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));

  $affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php include ('includes/ssl_provider.js.php'); ?>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=300,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" height="28" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
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
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_links.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
	      <tr>
            <td colspan=2 class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
        </table>
	   </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table width="95%" align="center" border="0" cellpadding="4" cellspacing="0"><td>
          <tr>
            <td class="infoBoxHeading" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER . ' ' . $affiliate_banners['affiliate_banners_title']; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_INFO . tep_draw_form('individual_banner', tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD) ) . "\n" . tep_draw_input_field('individual_banner_id', '', 'size="5"')?>&nbsp;&nbsp;</td>
          </tr>
     <tr>
       <td align="center" class="infoBoxHeading"><b>Build a product link:</b></td>
     </tr>
     <tr>
	<td align="center">
	<TABLE>
	<tr>
<?
if ($HTTP_POST_VARS['gen_height']=='' & $HTTP_POST_VARS['gen_image_type']=='medium') $HTTP_POST_VARS['gen_height'] = 360;
if ($HTTP_POST_VARS['gen_width']=='' & $HTTP_POST_VARS['gen_image_type']=='medium') $HTTP_POST_VARS['gen_width'] = 200;

if ($HTTP_POST_VARS['gen_height']=='' & $HTTP_POST_VARS['gen_image_type']=='small')  $HTTP_POST_VARS['gen_height'] = 210;
if ($HTTP_POST_VARS['gen_width']=='' & $HTTP_POST_VARS['gen_image_type']=='small')  $HTTP_POST_VARS['gen_width'] = 160;
?>
       <td align="right" class="smallText">Height:</td>
	<td>	
	<input type="text" value="<?=$HTTP_POST_VARS['gen_height']?>" name="gen_height" maxlength="5" style="width:50px;" />
	</td>
     </tr>
     <tr>
       <td align="right" class="smallText">Width:</td>
	<td>	
	<input type="text" value="<?=$HTTP_POST_VARS['gen_width']?>" name="gen_width" maxlength="5"  style="width:50px;" />
	</td>
     </tr>
     <tr>
       <td align="right" class="smallText">Price Font Size:</td>
	<td>	
	<select name="gen_font_header">
	<?
if ($HTTP_POST_VARS['gen_font_header']=='') $HTTP_POST_VARS['gen_font_header'] = 12;
		for ($i=8;$i<17;$i=$i+2){
	?>
		<option value="<?=$i?>" <?if ($HTTP_POST_VARS['gen_font_header']==$i) echo 'selected';?>><?=$i?> px</option>
	<?
	}		
	?>
	</select>
	</td>
     </tr>
     <tr>
       <td align="right" class="smallText">Regular Font Size:</td>
	<td>	
	<select name="gen_font">
	<?
if ($HTTP_POST_VARS['gen_font']=='') $HTTP_POST_VARS['gen_font'] = 12;
		for ($i=8;$i<15;$i=$i+2){
	?>
		<option value="<?=$i?>" <?if ($HTTP_POST_VARS['gen_font']==$i) echo 'selected';?>><?=$i?> px</option>
	<?
	}		
	?>
	</select>
	</td>
     </tr>
     <tr>
       <td align="right" class="smallText">Product Image:</td>
	<td>	
	<select name="gen_image">
		<option value="YES" <?if ($HTTP_POST_VARS['gen_image']=='YES') echo 'selected';?> >YES</option>
		<option value="NO" <?if ($HTTP_POST_VARS['gen_image']=='NO') echo 'selected';?>>NO</option>
	</select>
	</td>
     </tr>
     <tr>
       <td align="right" class="smallText">Image Type:</td>
	<td>	
	<select name="gen_image_type">
		<option value="small" <?if ($HTTP_POST_VARS['gen_image_type']=='small') echo 'selected';?> >Small</option>
		<option value="medium" <?if ($HTTP_POST_VARS['gen_image_type']=='medium') echo 'selected';?> >Medium</option>
	</select>
	</td>
     </tr>

	</TABLE>
	</td>
	</tr>
	  <tr>
		<TD align="center"><?=tep_image_submit('button_affiliate_build_a_link.gif', IMAGE_BUTTON_BUILD_A_LINK); ?></td>
	</TR>
</form>
     <tr>
       <td class="smallText" align="center"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODUCTS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?><br><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
     </tr>
     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_not_null($HTTP_POST_VARS['individual_banner_id']) || tep_not_null($HTTP_GET_VARS['individual_banner_id'])) {

    if (tep_not_null($HTTP_POST_VARS['individual_banner_id'])) $individual_banner_id = $HTTP_POST_VARS['individual_banner_id'];
    if ($HTTP_GET_VARS['individual_banner_id']) $individual_banner_id = $HTTP_GET_VARS['individual_banner_id'];

    if ($HTTP_POST_VARS['gen_image']=='') $HTTP_POST_VARS['gen_image'] = "YES";
    if ($HTTP_POST_VARS['gen_width']=='') $HTTP_POST_VARS['gen_width'] = "190";
    if ($HTTP_POST_VARS['gen_height']=='') $HTTP_POST_VARS['gen_height'] = "340";
    if ($HTTP_POST_VARS['gen_image_type']=='') $HTTP_POST_VARS['gen_image_type'] = "small";

   if (file_exists('tmp/top_affiliate_product-english.cache__'.$affiliate_id.'_'.$individual_banner_id.$affiliate_id))
		unlink('tmp/top_affiliate_product-english.cache__'.$affiliate_id.'_'.$individual_banner_id.$affiliate_id);

    $affiliate_pbanners_values = tep_db_query("select p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $individual_banner_id . "' and pd.products_id = '" . $individual_banner_id . "' and p.products_status = '1' and pd.language_id = '" . $languages_id . "'");
    if ($affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values)) {
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1:
   			$link = '<a href="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank"><img src="' . HTTPS_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_pbanners['affiliate_banners_image'] . '" border="0" alt="Buy This Video - ' . $affiliate_pbanners['products_name'] . '"></a>';
   			$link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_pbanners['affiliate_banners_image'] . '" border="0" alt="Buy This Video - ' . $affiliate_pbanners['products_name'] . '"></a>';
   			$link2 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank">Buy This Video - ' . $affiliate_pbanners['products_name'] . '</a>'; 
   		break; 
  		case 2: 
   // Link to Products 
   			$link = '<a href="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank"><img src="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="Buy This Video - ' . $affiliate_pbanners['products_name'] . '"></a>';
   			$link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="Buy this video - ' . $affiliate_pbanners['products_name'] . '"></a>';
   			$link2 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&products_id=' . $individual_banner_id . '&affiliate_banner_id=1" target="_blank">Buy this video - ' . $affiliate_pbanners['products_name'] . '</a>'; 
   		break; 
     } 
} 
?>
      <tr>
        <td><table width="100%" align="center" border="0" cellpadding="4" cellspacing="0" class="infoBoxContents">
          <tr>
            <td class="infoBoxHeading" align="center"><?php echo TEXT_AFFILIATE_NAME; ?>&nbsp;<?php echo $affiliate_pbanners['products_name']; ?></td>
          </tr>
          <tr>
            <td class="smallText" align="center"><?php echo $link; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" align="center"> 
             <textarea cols="60" rows="4" class="boxText"><?php echo $link1; ?></textarea> 
            </td> 
          </tr> 
          <tr> 
            <td>&nbsp;<td> 
          </tr> 
          <tr> 
            <td class="smallText" align="center"><b>Text Version:</b> <?php echo $link2; ?></td> 
          </tr>
          <tr> 
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr>
          <tr> 
            <td class="smallText" align="center"> 
             <textarea cols="60" rows="3" class="boxText"><?php echo $link2; ?></textarea> 
            </td> 
          </tr>
	<TR><td align="center"><b>Product Display Box</b><br></td></tr>  
          <tr> 
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr>
	<tr>
	   <td align="center">
<table width=190 border=0 cellpadding=0 cellspacing=0>
<tr>
<td valign=top align=center>
<div>
	<iframe src="<?=HTTP_SERVER?>/product_generator.php?ref=<?=$affiliate_id?>&products_id=<?=$individual_banner_id?>&affiliate_banner_id=1&gen_header=<?=$HTTP_POST_VARS['gen_header']?>&height=<?=$HTTP_POST_VARS['gen_height']?>&width=<?=$HTTP_POST_VARS['gen_width']?>&gen_font=<?=$HTTP_POST_VARS['gen_font']?>&gen_font_header=<?=$HTTP_POST_VARS['gen_font_header']?>&gen_image=<?=$HTTP_POST_VARS['gen_image']?>&gen_image_type=<?=$HTTP_POST_VARS['gen_image_type']?>" marginwidth="0" marginheight="0" width="<?=$HTTP_POST_VARS['gen_width']?>" height="<?=$HTTP_POST_VARS['gen_height']?>" border="0" frameborder="0" style="background-color:#F8F8F9;border: 1px solid black" scrolling="no"></iframe>
</div>
</td>
<td align=center valign=top style="padding-left:5px" class="smallText">
<textarea style="width:450px;height:90px;" class="boxText">
<iframe src="<?=HTTP_SERVER?>/product_generator.php?ref=<?=$affiliate_id?>&products_id=<?=$individual_banner_id?>&affiliate_banner_id=1&gen_header=<?=$HTTP_POST_VARS['gen_header']?>&height=<?=$HTTP_POST_VARS['gen_height']?>&width=<?=$HTTP_POST_VARS['gen_width']?>&gen_font=<?=$HTTP_POST_VARS['gen_font']?>&gen_font_header=<?=$HTTP_POST_VARS['gen_font_header']?>&gen_image=<?=$HTTP_POST_VARS['gen_image']?>&gen_image_type=<?=$HTTP_POST_VARS['gen_image_type']?>" marginwidth="0" width="<?=$HTTP_POST_VARS['gen_width']?>" height="<?=$HTTP_POST_VARS['gen_height']?>" marginheight="0" border="0" frameborder="0" style="background-color:#F8F8F9;border: 1px solid black" scrolling="no"></iframe>
</textarea>
* Pleaze, correct height of your iframe to work properly.
</td>
</tr>
</table>
	   </td>
        </tr>

          </table>
<?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
<?php
}
?>
	 </td></tr>
	 </td>
      </tr></table>
	 </td>
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
