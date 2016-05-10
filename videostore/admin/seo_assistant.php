<?php
/*
  $Id: SEO_Assistant.php,v 1.0 2004/08/07 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  require('includes/application_top.php');

	$maxEntries = '10';
  $google_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_GOOGLE ) or die("Query failed");;
  $google = tep_db_fetch_array($google_query);
	
  $searchurl = tep_db_prepare_input($HTTP_POST_VARS['search_url_google']);
 	if (empty($searchurl)) {
	  $firstpass = true;
    $searchurl = $google['search_url'];
	}
	else 
	  $firstpass = false;
		
  $searchquery = tep_db_prepare_input($HTTP_POST_VARS['search_term_google']);
  if (empty($searchquery))
    $searchquery = $google['search_term'];
 
  $searchtotal = tep_db_prepare_input($HTTP_POST_VARS['search_total_google']);
  if (empty($searchtotal))
    $searchtotal = $google['sites_searched'];

  $showlinks = tep_db_prepare_input($HTTP_POST_VARS['show_links']);
  $showlinks = (empty($showlinks)) ? '' : '1';
 
  $showhistory = tep_db_prepare_input($HTTP_POST_VARS['show_history']);
  $showhistory = (empty($showhistory)) ? '' : '1';
		
	$yahoo_query = tep_db_query("select search_url, search_term, rank, sites_searched, date from " . TABLE_SEO_YAHOO ) or die("Query failed");;
  $yahoo = tep_db_fetch_array($yahoo_query);
	 		
	$action_google = (isset($HTTP_POST_VARS['search_url_google']) ? $HTTP_POST_VARS['search_url_google'] : '');
  if (tep_not_null($action_google)) {
		$a = explode ("http://", $searchurl );
	  if (empty($a[0]))
     $searchurl = $a[1];
	
    include(DIR_WS_MODULES . 'seo_google_position.php');
    include(DIR_WS_MODULES . 'seo_yahoo_position.php');
	}	else	{
	  require(DIR_WS_FUNCTIONS . FILENAME_SEO_ASSISTANT);

	  $rank_url = tep_db_prepare_input($HTTP_POST_VARS['rank_url']);
 	  if (! empty($rank_url)) {
	    $pageRank = getPR($rank_url);
	    $prRating = array("Very poor","Poor","Below average","Average","Above Average","Good","Good","Very Good","Very Good","Excellent");
  	} 
	  else
	    $pageRank ='';
	}
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
  		 <!-- BEGIN GOOGLE CODE --> 
		   <tr>
       <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>				
				  <tr>	
					 <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">
            <tr> 
             <td>&nbsp;</td>
             <td align="right" > <?php echo tep_draw_form('google', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'post' ); ?></td>
            </tr>    
            <tr> 
			       <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>			
			      <tr class="infoBoxContents">
             <td><table border="0" cellspacing="2" cellpadding="2">
				      <tr>
			         <td><p>Enter total searches: </p></td>
               <td><?php  echo tep_draw_input_field('search_total_google', tep_not_null($searchtotal) ? $searchtotal : '100', 'maxlength="255"', false); ?> </td>
              </tr>
			        <tr>
		           <td><p>Enter search term: </p></td>
               <td><?php  echo tep_draw_input_field('search_term_google', tep_not_null($searchquery) ? $searchquery : 'search word', 'maxlength="255"', false); ?> </td>
              </tr>
              <tr> 
				       <td>Enter URL to search for: </td>
               <td><?php   echo tep_draw_input_field('search_url_google', tep_not_null($searchurl) ? $searchurl : 'http://', 'maxlength="255", size="40"',   false); ?> </td>
    	        </tr>
             </table></td>
            </tr>
			      <tr> 
			       <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
            </tr>						
				    <tr class="infoBoxContents">
             <td><table border="0" cellspacing="2" cellpadding="2">
              <tr> 
			  		   <td class="main">Show results: </td>
               <td ><?php echo tep_draw_checkbox_field('show_links', '', false, ''); ?> </td>
	 					   <td>&nbsp;</td>
							 <td class="main">Show History: </td>
               <td ><?php echo tep_draw_checkbox_field('show_history', '', false, ''); ?> </td>
	 					   <td>&nbsp;</td>
						   <td ><?php echo (tep_image_submit('button_search.gif', IMAGE_SEARCH) ) . ' <a href="' . tep_href_link(FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action'))) .'">' . '</a>'; ?></td>
              </tr>
             </table></td>
				     </tr>		
						 <?php if (tep_not_null($action_google)) { ?>	
						 <tr> 
			       <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
            </tr>	
						<tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_GOOGLE; ?></td>
            </tr>
						<tr> 
             <td colspan="3"><?php print($result_google);?></td>
            </tr>
						<tr> 
				     <td>&nbsp;</td>
		        </tr>					 	
						<?php if ($found_google && $show_history && mysql_num_rows($google_prev_query)) {	?>	
						<tr>
						 <td><table border="1" cellpadding="3" width="100%">
               <tr>      
                <td class="smallText" align="center" width="20%"><?php echo "DATE"; ?></td>
          	    <td class="smallText" align="center" width="30%"><?php echo "URL"; ?></td>     
                <td class="smallText" align="center" width="5%"><?php echo "RANK"; ?></td> 
          		  <td class="smallText" align="center" width="45%"><?php echo "WORD(S)"; ?></td>    
               </tr>
						  </table></td>
						</tr> 
						<?php while ($google = tep_db_fetch_array($google_prev_query)) { ?>
	   				 <tr>
						  <td><table border="1" cellpadding="3" width="100%">                
			         <tr>
				        <td class="smallText" align="center" width="20%"><?php echo $google['date']; ?></td>
			          <td class="smallText" align="left" width="30%"><?php echo $google['search_url']; ?></td>
			          <td class="smallText" align="center" width="5%"><?php echo $google['rank']; ?></td>
			          <td class="smallText" align="left" width="45%"><?php echo $google['search_term']; ?></td>
		           </tr>	
							</table></td>
						 </tr>
			       <?php  } } 		
			       if ($showlinks) {
    			    for ($i = 0; $i<$searchtotal; $i++) { 	
						   $j = $i + 1;
					      if (empty($siteresults_google[$i]))
						     break;						 
			       ?>			
			       <tr>
						  <td><table>
               <tr>
						    <?php if (substr($siteresults_google[$i], 'https')) { ?> 
					  	   <td class="main"><?php echo $j. ' ' .'<a   href="' . $siteresults_google[$i] . '" target="_blank">' . $siteresults_google[$i] . '</a>'; ?></td>
                <?php } else { ?>
		  	         <td class="main"><?php echo $j. ' ' .'<a   href="' .'http://' . $siteresults_google[$i] . '" target="_blank">' . $siteresults_google[$i] . '</a>'; ?></td>
                <?php } ?>	
		           </tr>
              </table></td>
             </tr>		 
             <?php } } } ?>
						</form>
					 </table></td>	
					</tr>			
					
					<!-- BEGIN YAHOO CODE --> 
					 <tr> 
			       <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
            </tr>			 		
            </tr>		
 						<?php if (tep_not_null($action_google)) {	?>		
						 <tr> 
			       <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
            </tr>	
						<tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_YAHOO; ?></td>
            </tr>
						<tr> 
             <td colspan="3"><?php print($result_yahoo);?></td>
            </tr>
						<tr> 
				     <td>&nbsp;</td>
		        </tr>				
						<?php if ($found_yahoo && $show_history && mysql_num_rows($yahoo_prev_query)) {	?>							 
						<tr>
						 <td><table border="1" cellpadding="3" width="100%">
              <tr>      
               <td class="smallText" align="center" width="20%"><?php echo "DATE"; ?></td>
          	   <td class="smallText" align="center" width="30%"><?php echo "URL"; ?></td>     
               <td class="smallText" align="center" width="5%"><?php echo "RANK"; ?></td> 
          	   <td class="smallText" align="center" width="45%"><?php echo "WORD(S)"; ?></td>    
              </tr>
						 </table></td>
						</tr>
						<?php while ($yahoo = tep_db_fetch_array($yahoo_prev_query)) { ?>
	   				<tr>
						 <td><table border="1" cellpadding="3" width="100%">                
			        <tr>
				       <td class="smallText" align="center" width="20%"><?php echo $yahoo['date']; ?></td>
			         <td class="smallText" align="left" width="30%"><?php echo $yahoo['search_url']; ?></td>
			         <td class="smallText" align="center" width="5%"><?php echo $yahoo['rank']; ?></td>
			         <td class="smallText" align="left" width="45%"><?php echo $yahoo['search_term']; ?></td>
		          </tr>	
						 </table></td>
						</tr>
			      <?php  } } 		
			      if ($showlinks) {
    			   for ($i = 0; $i<$searchtotal; $i++) { 	
						  $j = $i + 1;
				      if (empty($siteresults_yahoo[$i]))
						    break;						 
			      ?>			
			      <tr>
					   <td><table>
              <tr>
						   <?php if (substr($siteresults_yahoo[$i], 'https')) { ?> 
					      <td class="main"><?php echo $j. ' ' .'<a   href="' . $siteresults_yahoo[$i] . '" target="_blank">' . $siteresults_yahoo[$i] . '</a>'; ?></td>
               <?php } else { ?>
		  	        <td class="main"><?php echo $j. ' ' .'<a   href="' .'http://' . $siteresults_yahoo[$i] . '" target="_blank">' . $siteresults_yahoo[$i] . '</a>'; ?></td>
               <?php } ?>	
		          </tr>
             </table></td>
            </tr>		 
            <?php } } } ?>								
							
        <!-- BEGIN RANK CODE --> 			
				<tr> 
			   <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
        </tr>	
				<tr>
         <td><?php echo tep_black_line(); ?></td>
        </tr>
				<tr>
				 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
           <td class="pageHeading"><?php echo HEADING_TITLE_RANK; ?></td>
          </tr>					 
        </table></td>
				</tr>				
				<tr>
			   <td align="right" > <?php echo tep_draw_form('seotips', FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action2')) . 'action2=' . $form_action, 'post' ); ?></td>
        </tr>          
			  <tr> 
			   <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '10'); ?></td>
        </tr>
				<tr class="infoBoxContents">
         <td><table border="0" cellspacing="2" cellpadding="2">
          <tr> 
			  	 <td>Enter URL: </td>
           <td><?php echo tep_draw_input_field('rank_url', tep_not_null($searchurl) ? $searchurl : '', 'maxlength="255", size="40"',   false); ?> </td>
    	     <td ><?php echo (tep_image_submit('button_admin_get_page_rank.gif', IMAGE_GET_PAGE_RANK) ) . ' <a href="' . tep_href_link(FILENAME_SEO_ASSISTANT, tep_get_all_get_params(array('action2'))) .'">' . '</a>'; ?></td>
			  	</tr> 
				  <?php if (! empty($pageRank)) { ?>
			 		<tr>
					 <td  >Page Rank:</td>
				   <td ><?php echo sprintf("%d ( %s )",$pageRank, $prRating[(int)$pageRank]); ?> </td>
				  </tr>					
				  <?php } ?>
			   </table></td>				 
				</tr>					 		  
      </form>
			<!-- END RANK CODE --> 
			
			</table></td>   
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>