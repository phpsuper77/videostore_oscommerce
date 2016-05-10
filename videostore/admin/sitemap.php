<?php
/*
  $Id: sitemap.php,v 2.0 2006/07/07
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

	require(DIR_WS_FUNCTIONS. 'dynamic_sitemap.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'ex_file':
        if ($HTTP_POST_VARS['ex_type'] == 'all')
        	$ex_type = 1;
        	
        if ($HTTP_POST_VARS['ex_type'] == 'unreg')
        	$ex_type = 2;

      	$file = tep_db_prepare_input($HTTP_POST_VARS['file']);
				if (tep_not_null($file) && tep_not_null($ex_type))
							 tep_db_query('insert into '.TABLE_SITEMAP_EXCLUDE.' values(NULL,"'.$file.'","'.$ex_type.'",0)');

        tep_redirect(tep_href_link(FILENAME_SITEMAP));
        break;
      case 'ex_box':
        if ($HTTP_POST_VARS['ex_type'] == 'all')
        	$ex_type = 1;

        if ($HTTP_POST_VARS['ex_type'] == 'unreg')
        	$ex_type = 2;

      	$file = $HTTP_POST_VARS['box'];
				if (tep_not_null($file) && tep_not_null($ex_type))
							 tep_db_query('insert into '.TABLE_SITEMAP_EXCLUDE.' values(NULL,"'.$file.'","'.$ex_type.'",1)');

        tep_redirect(tep_href_link(FILENAME_SITEMAP));
        break;
      case 'include':
				if ($eID = tep_db_prepare_input($HTTP_GET_VARS['eID']))
							tep_db_query('delete from '.TABLE_SITEMAP_EXCLUDE.' where exclude_id="'.$eID.'"');

        tep_redirect(tep_href_link(FILENAME_SITEMAP));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <script language="javascript" src="includes/general.js"></script>
  </head>

  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
    <!-- header //--><?php require(DIR_WS_INCLUDES . 'header.php'); ?><!-- header_eof //--><!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
            <!-- left_navigation //-->
            <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
            <!-- left_navigation_eof //-->
          </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="100%">
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo tep_draw_form('set_frequencies', FILENAME_CREATE_XML_SITEMAPS, 'selected_box=tools');
          $freq = array('always' => TEXT_ALWAYS,
                        'hourly' => TEXT_HOURLY,
                        'daily' => TEXT_DAILY,
                        'weekly' => TEXT_WEEKLY,
                        'monthly' => TEXT_MONTHLY,
                        'yearly' => TEXT_YEARLY,
                        'never' => TEXT_NEVER); ?>
                      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="headerBar">
                        <tr class="main">
                          <td width="50%" valign="top" class="infoBoxContent"><?php echo TEXT_CHOOSE_CATMFG_FREQ; ?></td>
                          <td width="50%" valign="top" class="infoBoxContent"><?php echo TEXT_CHOOSE_SALE_FREQ; ?></td>
                        </tr>
                        <tr class="main">
                          <td width="50%" class="infoBoxContent"><?php
            foreach ($freq as $name => $text)
              {echo tep_draw_radio_field('cmcf',$name,($name == 'weekly'));
              echo $text.'<br>';} ?></td>
                          <td width="50%" class="infoBoxContent"><?php
            foreach ($freq as $name => $text)
              {echo tep_draw_radio_field('scf',$name,($name == 'monthly'));
              echo $text.'<br>';} ?></td>
                        </tr>
                      </table>
                      <p><?php echo TEXT_SELECT_TIMEZONE; ?><br>
                        <select id="tz" name="tz" size="1">
                          <option value="au_cdt">ACDT - Australian Central Daylight Time (Australia)</option>
                          <option value="au_cst">ACST - Australian Central Standard Time (Australia)</option>
                          <option value="na_adt">ADT - Atlantic Daylight Time (North America)</option>
                          <option value="au_edt">AEDT - Australian Eastern Daylight Time (Australia)</option>
                          <option value="au_est">AEST - Australian Eastern Standard Time (Australia)</option>
                          <option value="na_akdt">AKDT - Alaska Daylight Time (North America)</option>
                          <option value="na_akst">AKST - Alaska Standard Time (North America)</option>
                          <option value="na_ast">AST - Atlantic Standard Time (North America)</option>
                          <option value="au_wdt">AWDT - Australian Western Daylight Time (Australia)</option>
                          <option value="au_wst">AWST - Australian Western Standard Time (Australia)</option>
                          <option value="eu_bst">BST - British Summer Time (Europe)</option>
                          <option value="na_cdt">CDT - Central Daylight Time (North America)</option>
                          <option value="eu_cest">CEDT - Central European Daylight Time (Europe)</option>
                          <option value="eu_cest">CEST - Central European Summer Time (Europe)</option>
                          <option value="eu_cet">CET - Central European Time (Europe)</option>
                          <option value="au_cst">CST - Central Standard Time (Australia)</option>
                          <option value="na_cst">CST - Central Standard Time (North America)</option>
                          <option value="au_cdt">CST - Central Summer(Daylight) Time (Australia)</option>
                          <option value="au_cxt">CXT - Christmas Island Time (Australia)</option>
                          <option value="na_edt">EDT - Eastern Daylight Time (North America)</option>
                          <option value="eu_eest">EEDT - Eastern European Daylight Time (Europe)</option>
                          <option value="eu_eest">EEST - Eastern European Summer Time (Europe)</option>
                          <option value="eu_eet">EET - Eastern European Time (Europe)</option>
                          <option value="au_est">EST - Eastern Standard Time (Australia)</option>
                          <option value="na_est">EST - Eastern Standard Time (North America)</option>
                          <option value="au_edt">EST - Eastern Summer(Daylight) Time (Australia)</option>
                          <option value="eu_gmt">GMT - Greenwich Mean Time (Europe)</option>
                          <option value="na_adt">HAA - Heure Avanc&#233;e de l'Atlantique (North America)</option>
                          <option value="na_cdt">HAC - Heure Avanc&#233;e du Centre (North America)</option>
                          <option value="na_hadt">HADT - Hawaii-Aleutian Daylight Time (North America)</option>
                          <option value="na_edt">HAE - Heure Avanc&#233;e de l'Est (North America)</option>
                          <option value="na_pdt">HAP - Heure Avanc&#233;e du Pacifique (North America)</option>
                          <option value="na_mdt">HAR - Heure Avanc&#233;e des Rocheuses (North America)</option>
                          <option value="na_hast">HAST - Hawaii-Aleutian Standard Time (North America)</option>
                          <option value="na_ndt">HAT - Heure Avanc&#233;e de Terre-Neuve (North America)</option>
                          <option value="na_akdt">HAY - Heure Avanc&#233;e du Yukon (North America)</option>
                          <option value="na_ast">HNA - Heure Normale de l'Atlantique (North America)</option>
                          <option value="na_cst">HNC - Heure Normale du Centre (North America)</option>
                          <option value="na_est">HNE - Heure Normale de l'Est (North America)</option>
                          <option value="na_pst">HNP - Heure Normale du Pacifique (North America)</option>
                          <option value="na_mst">HNR - Heure Normale des Rocheuses (North America)</option>
                          <option value="na_nst">HNT - Heure Normale de Terre-Neuve (North America)</option>
                          <option value="na_akst">HNY - Heure Normale du Yukon (North America)</option>
                          <option value="eu_ist">IST - Irish Summer Time (Europe)</option>
                          <option value="na_mdt">MDT - Mountain Daylight Time (North America)</option>
                          <option value="eu_cest">MESZ - Mitteleurop&#228;ische Sommerzeit (Europe)</option>
                          <option value="eu_cet">MEZ - Mitteleurop&#228;ische Zeit (Europe)</option>
                          <option value="na_mst">MST - Mountain Standard Time (North America)</option>
                          <option value="na_ndt">NDT - Newfoundland Daylight Time (North America)</option>
                          <option value="au_nft">NFT - Norfolk (Island) Time (Australia)</option>
                          <option value="na_nst">NST - Newfoundland Standard Time (North America)</option>
                          <option value="na_pdt">PDT - Pacific Daylight Time (North America)</option>
                          <option value="na_pst" selected>PST - Pacific Standard Time (North America)</option>
                          <option value="eu_gmt">UTC - Coordinated Universal Time (Europe)</option>
                          <option value="eu_west">WEDT - Western European Daylight Time (Europe)</option>
                          <option value="eu_west">WEST - Western European Summer Time (Europe)</option>
                          <option value="eu_wet">WET - Western European Time (Europe)</option>
                          <option value="au_wst">WST - Western Standard Time (Australia)</option>
                          <option value="au_wdt">WST - Western Summer(Daylight) Time (Australia)</option>
                        </select></p>
                      <p><?php echo tep_draw_input_field('submit','','',false,'submit'); 
         echo TEXT_CREATE_XML .'</form><p>'.TEXT_CONTROL_XML; ?></p>
                    </td>
                    <td></td>
                  </tr>
                </table>
              </td>
            </tr>
            <?php

//get all files in catalog/

	 $excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type != "0" and is_box="0"');
	 $excluded_array = array();
	 $files = array();
	 if (tep_db_num_rows($excluded_query))
	  while($ex = tep_db_fetch_array($excluded_query))
   			$excluded_array[] = $ex['exclude_file'];


	 if ($handle = opendir(DIR_FS_CATALOG)){
    while ($file = readdir($handle)){
		    if(!is_dir($file) && (strtolower(substr($file, -4, 4)) === ".php")) //only look at php files
		    {
						if (!in_array($file ,$excluded_array)){
				        $engFile = DIR_FS_CATALOG.DIR_WS_LANGUAGES . $language . '/' . $file;
				        if (file_exists($engFile) && IsViewable(DIR_FS_CATALOG.$file))
				        {
				           $fp = file($engFile);

				           for ($idx = 0; $idx < count($fp); ++$idx)
				           {
				             if (!(strpos($fp[$idx], "define('HEADING_TITLE") === FALSE))
				             {
				                $fp[$idx] = stripslashes($fp[$idx]);
				                $p_start = strpos($fp[$idx], ",");
				                $p_start = strpos($fp[$idx], "'", $p_start);
				                $p_stop = strpos($fp[$idx], "'", $p_start + 2);
				                $files[] = array('id' => $file,
																				 'text' => ucfirst(substr($fp[$idx], $p_start + 1, $p_stop - $p_start - 1)).' ('.$file.')');
				                break;
				             }
				           }
						   if($idx == count($fp))
							$files[] = array('id' => $file, 'text' => '? ('.$file.')');
				        }
		        }
		    }
		}
		closedir($handle);

 } else echo ERROR_CANNOT_OPEN_CATALOG_DIR . DIR_FS_CATALOG.'<br>';
 
 
 //get all catalog boxes
 
	 $excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type != "0" and is_box="1"');
	 $excluded_array = array();

	 if (tep_db_num_rows($excluded_query))
	  while($ex = tep_db_fetch_array($excluded_query))
   			$excluded_array[] = $ex['exclude_file'];

  if ($handle = opendir(DIR_FS_CATALOG.DIR_WS_BOXES)){
    $ctr = 0;
    while ($file = readdir($handle))
    {
       if (strtolower(substr($file, -4, 4)) != ".php")
          continue;
			 elseif (in_array($file ,$excluded_array))
			    continue;

       $file = DIR_FS_CATALOG.DIR_WS_BOXES . $file;
       $fp = file($file);

       for ($idx = 0; $idx < count($fp); ++$idx)
       {
         if (!(strpos($fp[$idx], "BOX_HEADING") === FALSE))
         {
             $parts = explode(" ", $fp[$idx]);
             for ($i = 0; $i < count($parts); ++$i)
             {
                if (strpos($parts[$i], "BOX_HEADING") === FALSE)
                  continue;
                $parts = explode(")", $parts[$i]);  //$parts has full box heading text
                $name = explode("_", $parts[0]);    //ignore the BOX_HEADING part
                for ($x = 3; $x < count($name); ++$x) //name may be more than one word
                {
                  if (tep_not_null($name[$x]))
                    $name[2] .= ' ' . $name[$x];
                }
                $name[2] = strtolower($name[2]);
                $name[2] = ucfirst($name[2]);
                $boxes[$ctr]['text'] = $name[2].' ('.basename($file).')';
             }
              $boxes[$ctr]['id'] = basename($file);
         }
       }
       $ctr++;
    }
    closedir($handle);

	}  else echo ERROR_CANNOT_OPEN_CATALOG_BOXES_DIR . DIR_FS_CATALOG.DIR_WS_BOXES.'<br>';
?>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top">
                      <table border="0" cellspacing="0" cellpadding="2" width="100%">
                        <tr>
                          <td class="main" width="50%"><?php echo TITLE_CATALOG_FILES?></td>
                          <td class="main" width="50%"><?php echo TITLE_CATALOG_BOXES?></td>
                        </tr>
                        <tr>
                          <td class="main" width="50%" valign="top" align="left">
                            <?php echo tep_draw_form('ex_file', FILENAME_SITEMAP,'action=ex_file');?>
                            <table border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td colspan="2" class="main"><?php echo tep_draw_pull_down_menu('file',$files);?></td>
                              </tr>
                              <tr>
                                <td class="main"><?php echo TEXT_EXCLUDE_FOR_ALL.tep_draw_radio_field('ex_type','all', true).'<br>'.TEXT_EXCLUDE_FOR_UNREG.tep_draw_radio_field('ex_type','unreg');?></td>
                                <td class="main" align="right"><?php echo tep_image_submit('button_exclude.gif', IMAGE_EXCLUDE)?></td>
                              </tr>
                            </table>
                            </form>
                            <table border="1" cellspacing="0" cellpadding="2" width="95%" align="left">
                              <tr>
                                <td class="formAreaTitle"><?php echo TITLE_EXCLUDED_CATALOG_FILES?></td>
                              </tr>
                              <tr>
                                <td class="main"><?php
                                $exclude_query = tep_db_query('select exclude_id, exclude_file, exclude_type from '.TABLE_SITEMAP_EXCLUDE.' where is_box="0" and exclude_type!="0" order by exclude_type, exclude_file');
                                while($exclude = tep_db_fetch_array($exclude_query)){
                                  echo '<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr><td class="main">'.$exclude['exclude_file'].'</td><td class="main" align="right">('.( $exclude['exclude_type'] == 1 ? TEXT_ALL : TEXT_UNREG ).')</td><td class="main" align="right" width="50"><a href="'.tep_href_link(FILENAME_SITEMAP, 'action=include&amp;eID='.$exclude['exclude_id']).'">'.TEXT_INCLUDE.'</a></td></tr></table>';
                                }
                                ?></td>
                              </tr>
                            </table>
                          </td>
                          <td class="main" width="50%" valign="top" align="left">
                            <?php echo tep_draw_form('ex_box', FILENAME_SITEMAP,'action=ex_box');?>
                            <table border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td><?php echo tep_draw_pull_down_menu('box',$boxes);?></td>
                              </tr>
                              <tr>
                                <td class="main"><?php echo TEXT_EXCLUDE_FOR_ALL.tep_draw_radio_field('ex_type','all', true).'<br>'.TEXT_EXCLUDE_FOR_UNREG.tep_draw_radio_field('ex_type','unreg');?></td>
                                <td class="main" align="right"><?php echo tep_image_submit('button_exclude.gif', IMAGE_EXCLUDE)?></td>
                              </tr>
                            </table>
                            </form>
                            <table border="1" cellspacing="0" cellpadding="2" width="95%" align="left">
                              <tr>
                                <td class="formAreaTitle"><?php echo TITLE_EXCLUDED_CATALOG_BOXES?></td>
                              </tr>
                              <tr>
                                <td class="main"><?php
                                $exclude_query = tep_db_query('select exclude_id, exclude_file, exclude_type from '.TABLE_SITEMAP_EXCLUDE.' where is_box="1" and exclude_type!="0" order by exclude_type, exclude_file');
                                while($exclude = tep_db_fetch_array($exclude_query)){
                                  echo '<table border="0" cellspacing="0" cellpadding="2" width="100%"><tr><td class="main">'.$exclude['exclude_file'].'</td><td class="main" align="right">('.( $exclude['exclude_type'] == 1 ? TEXT_ALL : TEXT_UNREG ).')</td><td class="main" align="right" width="50"><a href="'.tep_href_link(FILENAME_SITEMAP, 'action=include&amp;eID='.$exclude['exclude_id']).'">'.TEXT_INCLUDE.'</a></td></tr></table>';
                                }
                                ?></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <!-- body_text_eof //-->		
      </tr>
    </table>
	<!-- body_eof //-->

	<!-- footer //-->
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	<!-- footer_eof //-->
  </body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
