<?php
ob_start();
/*
  $Id: vendor_account_products_disp.php,v 1.80 2005/22/08 17:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  // function tep_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
    // global $languages_id;

    // if (!is_array($categories_array)) $categories_array = array();

    // if ($from == 'product') {
      // $categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$id . "'");
      // while ($categories = tep_db_fetch_array($categories_query)) {
        // if ($categories['categories_id'] == '0') {
          // $categories_array[$index][] = array('id' => '0', 'text' => 'Top');
        // } else {
          // $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '1'");
          // $category = tep_db_fetch_array($category_query);
          // $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
          // if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
          // $categories_array[$index] = array_reverse($categories_array[$index]);
        // }
        // $index++;
      // }
    // } elseif ($from == 'category') {
      // $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '1'");
      // $category = tep_db_fetch_array($category_query);
      // $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
      // if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
    // }

    // return $categories_array;
  // }

  // function tep_output_generated_category_path($id, $from = 'category') {
    // $calculated_category_path_string = '';
    // $calculated_category_path = tep_generate_category_path($id, $from);
    // for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      // for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        // $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      // }
      // $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    // }
    // $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    // if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = 'Top';

    // return $calculated_category_path_string;
  // }

  function tep_draw_pull_down_menu_catg($name, $values, $default , $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"  multiple';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if (isset($default))
      {
      	foreach($default as $val)
      		if ($val == $values[$i]['id']) {
      		  $field .= ' SELECTED';
      	}
      }
      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;


    return $field;
  }

  // function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    // global $languages_id;

    // if (!is_array($category_tree_array)) $category_tree_array = array();
    // if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => 'Top');

    // if ($include_itself) {
      // $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '1' and cd.categories_id = '" . (int)$parent_id . "'");
      // $category = tep_db_fetch_array($category_query);
      // $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    // }

    // $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '1' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    // while ($categories = tep_db_fetch_array($categories_query)) {
      // if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      // $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    // }

    // return $category_tree_array;
  // }


  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . 'english/'.  FILENAME_VENDOR_PRODUCTS_DISP);

  require(DIR_WS_INCLUDES .'database_tables.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


  tep_session_start();
  if (tep_session_is_registered('vendors_id'))
  {
  	$vendors_id = $_SESSION['vendors_id'];

  	$products_sel = tep_db_query("select * from ". TABLE_PRODUCTS_TO_VENDORS ." where vendors_id = ". $vendors_id);
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/main.css">
<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}


//--></script>

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
  document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php
	if (isset($HTTP_GET_VARS['prod_id']) && $HTTP_GET_VARS['prod_id'] <> '')
		$productsid = (int)$HTTP_GET_VARS['prod_id'];
	if (isset($HTTP_POST_VARS['prod_id']) && $HTTP_POST_VARS['prod_id'] <> '')
		$productsid = (int)$HTTP_POST_VARS['prod_id'];

	$error = false;

	$stat = "";
	if ($HTTP_GET_VARS['action'] == "update")
	{
		$products_prefix = tep_db_input($HTTP_POST_VARS['prefix']);
		$products_name = tep_db_input($HTTP_POST_VARS['name']);
		$products_suffix = tep_db_input($HTTP_POST_VARS['suffix']);
		$products_description = stripslashes(tep_db_input($HTTP_POST_VARS['description']));
		//$products_price = $HTTP_POST_VARS['product_price'];
		//$products_sale_price = $HTTP_POST_VARS['sale_price'];

/*
		if (!(tep_db_query("update ".TABLE_PRODUCTS." set products_price = '".$products_price."' where products_id = ". $productsid)))
		{
			$error = true;
			$stat = "update";
		}

		if (!(tep_db_query("update ".TABLE_SPECIALS." set specials_new_products_price = '".$products_sale_price."' where products_id = ". $productsid)))
		{
			$error = true;
			$stat = "update";
		}
*/

$sql_query = "select products_name_prefix, products_name_suffix, products_name, products_description from ".TABLE_PRODUCTS_DESCRIPTION." where products_id = ". $productsid;
$pop = tep_db_fetch_array(tep_db_query($sql_query));
$old_title = $pop[products_name_prefix]." ".$pop[products_name]." ".$pop[products_name_suffix];
$old_description = $pop[products_description];


		if (!(tep_db_query("update ".TABLE_PRODUCTS_DESCRIPTION." set products_name_prefix = '".$products_prefix."', products_name = '".$products_name."', products_name_suffix = '".$products_suffix."', products_description = '".$products_description."' where products_id = ". $productsid)))
		{
			$error = true;
			$stat = "update";
		}
		else{
		$vend = tep_db_fetch_array(tep_db_query("select vendors_name, vendors_email from vendors where vendors_id=".$vendors_id));
		$letter = 'Product Information has been changed:<br><br>';
		$letter .= '<b>OLD</b><br>';
		$letter .= '<b>Name:</b> '.$old_title.'<br>';
		$letter .= '<b>Description</b>: '.stripslashes($old_description).'<br>';
		$letter .= '=========================================================================================';
		$letter .= '<b>NEW</b><br>';
		$letter .= '<b>Name:</b> '.$products_prefix.' '.$products_name.' '.$products_suffix.'<br>';
		$letter .= '<b>Description</b>: '.stripslashes($products_description).'<br>';
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: ".$vend[vendors_name]." <".$vend[vendors_email].">\r\n";
		$headers .= "To: TravelVideoStore.com Supplier Support <suppliersupport@travelvideostore.com>\r\n";
		mail('suppliersupport@travelvideostore.com', 'TravelVideoStore.com Product Changes Notification', $letter, $headers);
		//mail('x0661t@d-net.kiev.ua', 'TravelVideoStore.com Product Changes Notification', $letter, $headers);
	
}

		tep_db_query("delete from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id = '".$productsid ."'");
		foreach ($_POST['categoriesid'] as $cat) {
	    tep_db_query("insert into `".TABLE_PRODUCTS_TO_CATEGORIES."` (`products_id`, `categories_id`) values ('" . $productsid . "', '" . $cat. "')");
		}
	}

	$prod_disp_sql = tep_db_query("select p.products_price, p.products_id, p.products_model, pd.products_name_prefix, pd.products_name, pd.products_name_suffix, pd.products_description from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd where pd.products_id = p.products_id and p.products_id = ".$productsid);
	$prod_disp_fetch = tep_db_fetch_array($prod_disp_sql);
?>
<!-- header //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>
	<td width="100%" class="headerNavigation">&nbsp;<a href="../index.php" class="headerNavigation"><?php echo TXT_HOME?></span></a>&nbsp;-&nbsp;<a href="vendor_account_products.php" class="headerNavigation"><?php echo TXT_PRODUCT_INFO;?></a>&nbsp;-&nbsp;<a href="vendor_account_products_disp.php?prod_id=<?php echo $productsid;?>" class="headerNavigation"><?php echo TXT_EDIT_PRODUCTS;?></a></td>
</tr>
</table>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
	if (tep_session_is_registered('vendors_id'))
		require('./includes/column_left.php');

?>

<!-- left_navigation_eof //-->
    </table></td>

    <td width="100%" valign="top"><table border="0" width="85%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0" >
        <?php echo tep_draw_form('product_edit', tep_href_link('vendors/'.FILENAME_VENDOR_PRODUCTS_DISP, 'action=update', 'NONSSL')); ?>
          <tr>
			<td colspan=6 width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr><td class="pageHeading1" colspan=6><?php echo HEADING_TITLE; ?></td></tr>
			</table>
			</td>
		</tr>

          </table>
         </td></tr>
		<tr>
  		<td>
	<?php
			if (($error == false) && ($HTTP_GET_VARS['action'] == "update"))
			{
	?>
		<table border="0" width="85%" cellspacing="0" cellpadding="1">
					<tr>
						<td bgcolor="#99FF00" class="main"> <?php echo TXT_PRODUCTS_UPDATE_SUCCESS?></td>
					</tr>
					</table>
			<?
			}
		?>


  		</td></tr>
  		<tr><td><table border="0" width="60%" cellspacing="1" cellpadding="2" class="infoBox">
			<tr class="infoBoxContents">
			  <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr class="infoBoxContents" >
					<td class="smalltext" width="40%"><?php echo TXT_MODEL_NO;?></td>
					<td class="smalltext" width="60%" align="left">
						<?php echo $prod_disp_fetch['products_model']; ?>
					</td>
				</tr>
				<tr class="infoBoxContents" >
					<td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
				<tr class="infoBoxContents" >
					<td class="smalltext" width="40%"><?php echo TXT_PRODUCTS_TITLE_PREFIX;?></td>
					<td class="smalltext" width="60%" align="left">
						<?php echo tep_draw_input_field('prefix',tep_db_prepare_input($prod_disp_fetch['products_name_prefix']));?>
					</td>
				</tr>
				<tr class="infoBoxContents" >
					<td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
				<tr class="infoBoxContents" >
					<td class="smalltext" width="20%"><?php echo TXT_PRODUCTS_TITLE_MAIN;?></td>
					<td class="smalltext" width="80%" align="left">
						<?php echo tep_draw_input_field('name',tep_db_prepare_input($prod_disp_fetch['products_name']));?>
					</td>
				</tr>
				<tr class="infoBoxContents" >
					<td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
				<tr class="infoBoxContents" >
					<td class="smalltext" width="20%"><?php echo TXT_PRODUCTS_TITLE_SUFFIX;?></td>
					<td class="smalltext" width="80%" align="left">
						<?php echo tep_draw_input_field('suffix',tep_db_prepare_input($prod_disp_fetch['products_name_suffix']),'','',true);?>
					</td>
				</tr>
				<tr >
					<td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
				<tr class="infoBoxContents" >
					<td class="smalltext" align="left" width="100%" colspan=2><?php echo TXT_DESC;?></td>
				<tr>
				<tr class="infoBoxContents" >
					<td class="smalltext" width="100%" colspan="2" align="left">
						<textarea name="description" style="width:800; height:150"><?php echo tep_output_string($prod_disp_fetch['products_description']);?></textarea>


					 <script language="JavaScript1.2" defer>
					          // MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.6.5 Products Description HTML - Body
					             var config = new Object();  // create new config object
					             config.width = 650;
					             config.height = 300;
					             config.bodyStyle = 'background-color: #FFFFF; font-family: Arial; color: #000000; font-size: 10pt;';
					             config.debug = 0;
					             // More Configs can added here:
					             // Dreamscape added Dynamic Language Function
					          editor_generate('description',config);

  						</script>
					</td>
				</tr>
				<tr>
					<td colspan=2></td>
				</tr>
				<tr >
					<td colspan=2>
				<?php		
$catg_query = tep_db_query("select categories_id from products_to_categories where products_id ='".$prod_disp_fetch['products_id'] ."'");
			$cat = array();
      while($categories=tep_db_fetch_array($catg_query))
      {
			$cat[] = $categories['categories_id'];
      }
				echo '<br>Current Categories<br><b>' . tep_output_generated_category_path($prod_disp_fetch['products_id'], 'product') . '</b><br>';
              echo 'Categories <br>' . tep_draw_pull_down_menu_catg('categoriesid[]', tep_get_category_tree(),$cat, "size='22'");
        ?>
					</td>
				</tr>
				<tr>
					<td align="left" colspan=2>
					<?php echo "<a href='vendor_account_products.php'>".tep_image(DIR_WS_INCLUDES_LOCAL.'images/button_back.gif', IMAGE_BUTTON_UPDATE)."</a>"; ?>
					<?php echo tep_draw_separator('pixel_trans.gif', '66%', '1'); ?>
					<?php echo tep_image_submit_vendors(DIR_WS_INCLUDES_LOCAL.'images/button_update.gif', IMAGE_BUTTON_UPDATE); ?>
					</td>
				</tr>
				<?php echo tep_draw_hidden_field('prod_id',$prod_disp_fetch[products_id]);?>
        </table></td>
      </tr>
        </table><br></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require('footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
