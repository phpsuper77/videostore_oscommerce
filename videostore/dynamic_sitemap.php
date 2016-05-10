<?php
/*
  $Id: sitemap.php,v2.0 2006/07/07 web4pro

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DYNAMIC_SITEMAP);

 function getFileName($file, $define)        //retrieve the defined filename
 { 
   $fp = file($file);
   for ($idx = 0; $idx < count($fp); ++$idx)
   {
      if (!(strpos($fp[$idx], $define) === FALSE))
      {
          $parts = explode("'", $fp[$idx]);   
          return $parts[3];     
      }
   }    
   return NULL;
 }
 function getBoxText($file, $define)          //retrieve the defined box name
 { 
   $fp = file($file);
   for ($idx = 0; $idx < count($fp); ++$idx)
   {
      if (!(strpos($fp[$idx], $define) === FALSE))
      {
        $fp[$idx] = stripslashes($fp[$idx]);
        $p_start = strpos($fp[$idx], ",");
        $p_start = strpos($fp[$idx], "'", $p_start);
        $p_stop = strrpos($fp[$idx], '\'');
        return str_replace('%s', '', ucfirst(substr($fp[$idx], $p_start + 1, $p_stop - $p_start - 1)));
      }
   }
   return NULL;
 }
 function IsViewable($file)
 {
   $fp = file($file);
   for ($idx = 0; $idx < count($fp); ++$idx)
   {
      if (!(strpos($fp[$idx], "<head>") === FALSE))
        return true;
   }  
   return false;
 }

  function get_cat_paths($categories_array = '', $parent_id = '0', $path='') {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
	// hide empty categories hack
	  if (tep_count_products_in_category($categories['categories_id']) > 0) {
	  // hide empty categories hack
        if ($parent_id=='0'){
	        $categories_array[] = array('id' => $categories['categories_id'],
                                      'text' => $categories['categories_name']);
        }else{
         	$categories_array[] = array('id' => $path . $parent_id . '_' .$categories['categories_id'],
                                        'text' => $categories['categories_name']);
        }
        if ($categories['categories_id'] != $parent_id) {
         	$this_path=$path;
        	if ($parent_id != '0')
      	      $this_path = $path . $parent_id . '_';
              $categories_array = get_cat_paths($categories_array, $categories['categories_id'], $this_path);
        }
	  // hide empty categories hack
	  }
	  // hide empty categories hack
    }

    return $categories_array;
  }
 
 $path = DIR_WS_BOXES;
 $pathFileName = DIR_WS_INCLUDES . 'filenames.php';
 $pathLanguage = DIR_WS_LANGUAGES . $language . '.php';
 $boxText = array();
 
 /********************* Find the infoboxes to add ***********************/
 if ($handle = opendir($path))
 {
	 if (!tep_session_is_registered('customer_id'))
	 		$excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type != "0" and is_box="1"');
	 else
	 		$excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type = "1" and is_box="1"');
	 $excluded_array = array();
	 if (tep_db_num_rows($excluded_query))
	  while($ex = tep_db_fetch_array($excluded_query))
   			$excluded_array[] = $ex['exclude_file'];

 
    $found = false;
    $ctr = 0;
    while ($file = readdir($handle))
    {     
       if (strtolower(substr($file, -4, 4)) != ".php")
          continue;
       elseif (in_array($file, $excluded_array))
          continue;

       $file = $path . '/' . $file; 
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
                $boxHeading[$ctr]['heading'][$ctr] = getBoxText($pathLanguage, $parts[0]);
             }
         }  
         else if (!(strpos($fp[$idx], "FILENAME") === FALSE))
         {
           $str = str_replace("'<a href=\"' . tep_href_link(", "", $fp[$idx]);
           $str = str_replace("\$info_box_contents[] = array('text' => ", "", $str);
           
           $parts = explode(")", $str);
           $parts[0] = trim($parts[0]);
           
           $boxParts = explode(".", $parts[1]);
           $boxParts[2] = trim($boxParts[2]);      
           
           if (tep_not_null($boxParts[2]))
           {     
              $boxHeading[$ctr]['filename'][] = getFileName($pathFileName, $parts[0]);
              $boxHeading[$ctr]['boxtext'][] = getBoxText($pathLanguage, $boxParts[2]);
           }
           else
           { 
              if (tep_not_null($box_heading))
              {
                echo 'Invalid code for this module found in the following infobox: '.$boxHeading[$ctr]['heading'][$ctr].'<br>';
                array_pop($boxHeading);
                $ctr--;
              }
           }
         }               
       } 
       $ctr++;
    }
    closedir($handle); 
 } 
 
 /********************* Find the pages to add ***********************/
 $ctr = 0;
	($dir = opendir('.')) || die("Failed to open dir");
 $files = array();

 	 if (!tep_session_is_registered('customer_id'))
	 		$excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type != "0" and is_box="0"');
	 else
	 		$excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type = "1" and is_box="0"');
	 $excluded_array = array();
	 if (tep_db_num_rows($excluded_query))
	  while($ex = tep_db_fetch_array($excluded_query))
   			$excluded_array[] = $ex['exclude_file'];

 while($file = readdir($dir)) 
 {
    if((!is_dir($file) && strtolower(substr($file, -4, 4)) === ".php") && !in_array($file ,$excluded_array))//only look at php files and skip that are excluded
    {
        $engFile = DIR_WS_LANGUAGES . $language . '/' . $file;
        if (file_exists($engFile) && IsViewable($file)) 
        {

           $fp = file($engFile);
  
           for ($idx = 0; $idx < count($fp); ++$idx)
           {
             if (!(strpos($fp[$idx], "define('HEADING_TITLE") === FALSE))
             {
                $fp[$idx] = stripslashes($fp[$idx]);
                $p_start = strpos($fp[$idx], ",");
                $p_start = strpos($fp[$idx], "'", $p_start);
                $p_stop = strrpos($fp[$idx], '\'');
                $files['name'][] = str_replace('%s', '', ucfirst(substr($fp[$idx], $p_start + 1, $p_stop - $p_start - 1)));
                $files['path'][] = $file;
                break;
             }
           }  
        }  
    } 
 }
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_DYNAMIC_SITEMAP));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href=stylesheet.css>
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
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
          <tr>
            <td width="50%" class="main" valign="top"> 
<?php
              // categories map
              $cats = get_cat_paths();
              $prevlevel = 0;
              for ($cnt = 0; $cnt < sizeof($cats); $cnt++)
              {
                $level = substr_count($cats[$cnt]['id'], '_') + 1;
                if ($prevlevel < $level) echo '<ul class="sitemap">';
                if ($prevlevel > $level) echo str_repeat('</li></ul>', $prevlevel - $level);
                if ($prevlevel >= $level)
                  echo '</li>';
                echo '<li><a title="'.$cats[$cnt]['text'].'" href="'.tep_href_link(FILENAME_DEFAULT,'cPath='.$cats[$cnt]['id']).'">'.$cats[$cnt]['text'].'</a>';
                $prevlevel = $level;
              } 
              echo str_repeat('</li></ul>', $prevlevel);
?>
            </td>
            <td width="50%" class="main" valign="top">
              <ul class="sitemap">
<?php
                // main files map
                for ($b = 0; $b < count($files['name']); ++$b)
                {
                  // check each page to see if it should be ssl                  $securelink= 'NONSSL'; // assume a non ssl page                  $SSLfp = file($files['path'][$b] ); // load the root file into a variable                  for ($SSLidx = 0; $SSLidx < count($SSLfp); ++$SSLidx){ //go through root file line by line until the doctype tag is encountered                    if ((!(strpos($SSLfp[$SSLidx], "breadcrumb->add") === FALSE)) && (!(strpos($SSLfp[$SSLidx], "'SSL") === FALSE))) { // determine if the bread crumb variable is in this line and it has the letter SSL in it                      $securelink= 'SSL'; // set the ssl link to true                      break;                    } elseif (!(strpos(strtolower($SSLfp[$SSLidx]), "<!doctype") === FALSE)) { //doctype tag is found(too soon?), exit loop and do not use SSL                      break; // exit the loop and do not set ssl link to true                    }                  }                  echo '<li><a title="'. $files['name'][$b] .'" href="' . tep_href_link($files['path'][$b], '', $securelink) . '">' . $files['name'][$b] . '</a></li>';
                }
                // Box Files
                for ($b = 0; $b < count($boxHeading); ++$b)
                {
                    echo '<li>'.$boxHeading[$b]['heading'][$b];
                    $nb_elements = count($boxHeading[$b]['filename']);
                    if($nb_elements > 0)
                    {
                        if (tep_not_null($boxHeading[$b]['filename'][0]))
                        {
                        echo '<ul>';
                        for ($f = 0; $f < $nb_elements ; ++$f)
                            if (tep_not_null($boxHeading[$b]['filename'][$f]))
                                echo '<li><a title="'. $boxHeading[$b]['boxtext'][$f] .'" href="' . tep_href_link($boxHeading[$b]['filename'][$f]) . '">' . $boxHeading[$b]['boxtext'][$f] . '</a></li>';
                        echo '</ul>';
                        }
                    }
                    echo '</li>';
                }
?>
              </ul>
            </td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>