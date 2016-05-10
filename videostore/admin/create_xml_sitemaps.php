<?php
/*
  $Id: create_xml_sitemaps.php,v1.2 2007/10/05 Kevin L. Shelton

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
$sitemap_url = urlencode(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'sitemaps.xml');
$ping = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . $sitemap_url;
echo "<script type=\"text/javascript\">\n";
echo "<!--\n";
echo "function opennotifywindows(){\n";
echo " window.open(\"$ping\",\"Google Notify\",\"toolbar=no,location=yes,directories=no,status=yes,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=200\");\n";
$ping = "http://submissions.ask.com/ping?sitemap=" . $sitemap_url;
echo " window.open(\"$ping\",\"Ask.com Notify\",\"toolbar=no,location=yes,directories=no,status=yes,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=200\");\n";
$ping = "http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=" . $sitemap_url;
echo " window.open(\"$ping\",\"Yahoo Notify\",\"toolbar=no,location=yes,directories=no,status=yes,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=200\");\n";
echo "}\n";
echo "//-->\n";
echo "</script>\n\n";
?>
</head>
<body onclick="opennotifywindows()" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr><td class="main">
<?php 
 require DIR_WS_FUNCTIONS . 'dynamic_sitemap.php';
// function to escape code xml data as required by Google
function smspecialchars($input)
{ return str_replace("'", '&apos;', htmlspecialchars($input));}
// set values sent from site map maintenance
$wording = array('always','hourly','daily','weekly','monthly','yearly','never');
$cmcf = (isset($HTTP_POST_VARS['cmcf']) && in_array($HTTP_POST_VARS['cmcf'], $wording) ? $HTTP_POST_VARS['cmcf'] : 'weekly');
$scf = (isset($HTTP_POST_VARS['scf']) && in_array($HTTP_POST_VARS['scf'], $wording) ? $HTTP_POST_VARS['scf'] : 'monthly');
$zones = array("au_cdt" => '+09:30',
  "au_cst" => '+09:30',
  "au_cxt" => '+07:00',
  "au_edt" => '+10:00',
  "au_est" => '+10:00',
  "au_nft" => '+11:30',
  "au_wdt" => '+08:00',
  "au_wst" => '+08:00',
  "na_adt" => '-03:00',
  "na_akdt" => '-08:00',
  "na_akst" => '-09:00',
  "na_ast" => '-04:00',
  "na_cdt" => '-05:00',
  "na_cst" => '-06:00',
  "na_edt" => '-04:00',
  "na_est" => '-05:00',
  "na_hadt" => '-09:00',
  "na_hast" => '-10:00',
  "na_mdt" => '-06:00',
  "na_mst" => '-07:00',
  "na_ndt" => '-02:30',
  "na_nst" => '-03:30',
  "na_pdt" => '-07:00',
  "na_pst" => '-08:00',
  "eu_bst" => '+01:00',
  "eu_cest" => '+02:00',
  "eu_cet" => '+01:00',
  "eu_eest" => '+03:00',
  "eu_eet" => '+02:00',
  "eu_gmt" => '+00:00',
  "eu_ist" => '+01:00',
  "eu_west" => '+01:00',
  "eu_wet" => '+00:00');
$tzone =(isset($HTTP_POST_VARS['tz']) && isset($zones[$HTTP_POST_VARS['tz']]) ? $zones[$HTTP_POST_VARS['tz']] : '-08:00');

//create sitemap index
$now = date("Y-m-d\TH:i:s") . $tzone;
echo TEXT_CREATE_INDEX . $now . '<br>';
$smi = '<?xml version="1.0" encoding="UTF-8"?>' ."\n".
'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n".
"<sitemap><loc>" . smspecialchars(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'smmain.xml') . "</loc><lastmod>".$now."</lastmod></sitemap>\n".
"<sitemap><loc>" . smspecialchars(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'smcats.xml') . "</loc><lastmod>".$now."</lastmod></sitemap>\n".
'<sitemap><loc>' . smspecialchars(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'smproducts.xml') . "</loc><lastmod>".$now."</lastmod></sitemap>\n".
'<sitemap><loc>' . smspecialchars(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'smmfg.xml') . "</loc><lastmod>".$now."</lastmod></sitemap>\n".
'<sitemap><loc>' . smspecialchars(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'smspecials.xml') . "</loc><lastmod>".$now."</lastmod></sitemap>\n".
'</sitemapindex>';
$sm = DIR_FS_CATALOG . 'sitemaps.xml';
$fh = fopen($sm, 'w') or die(ERROR_INDEX_FILE);
fwrite($fh, utf8_encode($smi));
fclose($fh);

//get all files in catalog that aren't set as excluded
echo TEXT_FINDING_FILES;
	 $excluded_query = tep_db_query('select exclude_file from '.TABLE_SITEMAP_EXCLUDE.' where exclude_type != "0" and is_box="0"');
	 $excluded_array = array();
	 $files = array(); $cnt = 0;
	 if (tep_db_num_rows($excluded_query))
	  while($ex = tep_db_fetch_array($excluded_query))
   			$excluded_array[] = $ex['exclude_file'];
	 if ($handle = opendir(DIR_FS_CATALOG)){
    while ($file = readdir($handle)){
		    if(!is_dir($file) && (strtolower(substr($file, -4, 4)) === ".php")) //only look at php files
		    {
						if (!in_array($file ,$excluded_array)){
				          $engFile = DIR_FS_CATALOG.DIR_WS_LANGUAGES . $language . '/' . $file;
				          if (file_exists($engFile) && IsViewable(DIR_FS_CATALOG.$file)){
				            //see if this file should be linked via ssl				            $securelink= 'NONSSL'; // assume a non ssl page				            $SSLfp = file(DIR_FS_CATALOG.$file ); // load the root file into a variable				            for ($SSLidx = 0; $SSLidx < count($SSLfp); ++$SSLidx){ //go through root file line by line until the doctype tag is encountered				              if ((!(strpos($SSLfp[$SSLidx], "breadcrumb->add") === FALSE)) && (!(strpos($SSLfp[$SSLidx], "'SSL") === FALSE))) { // determine if the bread crumb variable is in this line and it has the letters 'SSL' in it				                $securelink= 'SSL'; // set the ssl link to true				                break;				              }elseif (!(strpos(strtolower($SSLfp[$SSLidx]), "<!doctype") === FALSE)) { //doctype tag is found(too soon?), exit loop and do not use SSL				                break; // exit the loop and do not set ssl link to true				              }				            }				            $files[] = array('path' => $file,				                             'modified' => date("Y-m-d\TH:i:s", filemtime(DIR_FS_CATALOG.$file)) . $tzone,				                             'securelink' => $securelink);
				            $cnt++;
				          }
				        }
		        }
		    }
		
		closedir($handle);
 } else echo ERROR_CANNOT_OPEN_CATALOG_DIR . DIR_FS_CATALOG.'<br>';
// create main sitemap
$xml_head = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$xml_foot = "</urlset>";
echo TEXT_CREATE_MAIN;
$sm = DIR_FS_CATALOG . 'smmain.xml';
$fh = fopen($sm, 'w') or die(ERROR_MAIN_FILE);
fwrite($fh, utf8_encode($xml_head));
for ($b = 0; $b < $cnt; ++$b) { 
  if (($files[$b]['securelink'] == 'SSL') || (ENABLE_SSL_CATALOG == 'true')) {    $fPath=HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;  } else {    $fPath=HTTP_CATALOG_SERVER . DIR_WS_CATALOG;  }  echo $fPath . $files[$b]['path'] . ' --> ' . $files[$b]['modified'] . '<br>';  fwrite($fh, utf8_encode('<url><loc>' . smspecialchars($fPath . $files[$b]['path']) . '</loc><lastmod>' . $files[$b]['modified'] . '</lastmod></url>'."\n"));
   }
fwrite($fh, utf8_encode($xml_foot));
fclose($fh); 
echo $cnt.TEXT_TOTAL_FILES;

//create products listing sitemap
echo TEXT_CREATE_PRODUCTS;            
$sm = DIR_FS_CATALOG . 'smproducts.xml';
$fh = fopen($sm, 'w') or die(ERROR_PRODUCTS_FILE);
fwrite($fh, utf8_encode($xml_head));
$cnt = 0;
$base_url = HTTP_CATALOG_SERVER.DIR_WS_CATALOG."product_info.php?products_id="; // url to your product pages (must end with the products_id=)
	$urls_query = tep_db_query("select products_id,  products_last_modified, products_date_added from " . TABLE_PRODUCTS . " where products_status = 1 order by products_id"); //all in stock items
	while($urls = tep_db_fetch_array($urls_query)) {
	  $this_url = smspecialchars($base_url . $urls['products_id']);
		if($urls['products_last_modified'] > 0) {
			$date_mod = $urls['products_last_modified'];
			} else {
				$date_mod = $urls['products_date_added'];
			}
		$lastmod = "<lastmod>" . str_replace(' ', 'T', $date_mod) . $tzone . "</lastmod>";
    $output = "<url><loc>" . $this_url . "</loc>" . $lastmod . "</url>\n";
    echo $this_url . ' --> ' . $date_mod .'<br>';
		fwrite($fh, utf8_encode($output));
		$cnt++;
		}
fwrite($fh, utf8_encode($xml_foot));
fclose($fh); 
echo $cnt.TEXT_TOTAL_PRODUCTS;

//create categories listing sitemap
echo TEXT_CREATE_CATEGORIES;            
$sm = DIR_FS_CATALOG . 'smcats.xml';
$fh = fopen($sm, 'w') or die(ERROR_CATEGORIES_FILE);
fwrite($fh, utf8_encode($xml_head));
  function get_paths($categories_array = '', $parent_id = '0', $path ='') {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "' order by parent_id");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($parent_id=='0'){
	$categories_array[] = array('pathid' => $categories['categories_id'],
                              'catid' => $categories['categories_id']);
      }
      else{
	$categories_array[] = array('pathid' => $path . $parent_id . '_' .$categories['categories_id'],
        	                    'catid' => $categories['categories_id']);
      }

      if ($categories['categories_id'] != $parent_id) {
	$this_path=$path;
	if ($parent_id != '0')
	  $this_path = $path . $parent_id . '_';
        $categories_array = get_paths($categories_array, $categories['categories_id'], $this_path);
      }
    }

    return $categories_array;
  }
$categories = get_paths();
$base_url = HTTP_CATALOG_SERVER.DIR_WS_CATALOG."index.php?cPath="; // url to your category pages (must end with the cPath=)
$cnt = 0;
$totalpages = 0;
while ($cnt < count($categories))
  {$prod_query = tep_db_query("select count(ptc.products_id) as numprods from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where products_status = 1 and p.products_id = ptc.products_id and categories_id = " . (int)$categories[$cnt]['catid']);
  $num = tep_db_fetch_array($prod_query);
  $numpages = ((int)$num['numprods'] = 0 ? 1 : ceil((int)$num['numprods'] / MAX_DISPLAY_SEARCH_RESULTS));
  $totalpages += $numpages;
  for ($page = 1; $page <= $numpages; $page++) // create a url for every page of each category
    {$url = $base_url . $categories[$cnt]['pathid'] . '&page=' . $page;
    echo $categories[$cnt]['catid'] .' --> '. $url .'<br>';
    fwrite($fh, utf8_encode('<url><loc>' . smspecialchars($url) . "</loc><changefreq>".$cmcf."</changefreq></url>\n"));
    }
  $cnt++;
  }
fwrite($fh, utf8_encode($xml_foot));
fclose($fh); 
echo $cnt.TEXT_TOTAL_CATEGORIES . $totalpages .TEXT_TOTAL_PAGES;

//create manufacturers listing sitemap
echo TEXT_CREATE_MANUFACTURERS;            
$sm = DIR_FS_CATALOG . 'smmfg.xml';
$fh = fopen($sm, 'w') or die(ERROR_MANUFACTURERS_FILE);
fwrite($fh, utf8_encode($xml_head));
$base_url = HTTP_CATALOG_SERVER.DIR_WS_CATALOG."index.php?manufacturers_id="; // url to your manufacturer pages (must end with the manufacturers_id=)
$cnt = 0;
$totalpages = 0;
$mfg_query = tep_db_query('select manufacturers_id, manufacturers_name from ' . TABLE_MANUFACTURERS);
while ($mfg = tep_db_fetch_array($mfg_query))
  {$prod_query = tep_db_query("select count(products_id) as numprods from " . TABLE_PRODUCTS . " where products_status = 1 and manufacturers_id = " . (int)$mfg['manufacturers_id']);
  $num = tep_db_fetch_array($prod_query);
  $numpages = ((int)$num['numprods'] = 0 ? 1 : ceil((int)$num['numprods'] / MAX_DISPLAY_SEARCH_RESULTS));
  $totalpages += $numpages;
  for ($page = 1; $page <= $numpages; $page++) // create a url for every page of each manufacturer
    {$url = $base_url . $mfg['manufacturers_id'] . '&page=' . $page;
    echo $mfg['manufacturers_name'] .' --> '. $url .'<br>';
    fwrite($fh, utf8_encode('<url><loc>' . smspecialchars($url) . "</loc><changefreq>".$cmcf."</changefreq></url>\n"));
    }
  $cnt++;
  }
fwrite($fh, utf8_encode($xml_foot));
fclose($fh); 
echo $cnt.TEXT_TOTAL_MANUFACTURERS . $totalpages .TEXT_TOTAL_PAGES;

//create specials listing sitemap
echo TEXT_CREATE_SPECIALS;            
$sm = DIR_FS_CATALOG . 'smspecials.xml';
$fh = fopen($sm, 'w') or die(ERROR_SPECIALS_FILE);
fwrite($fh, utf8_encode($xml_head));
$base_url = HTTP_CATALOG_SERVER.DIR_WS_CATALOG."specials.php?page="; // url to your specials pages (must end with the page=)
$cnt = 0;
$special_query = tep_db_query('select count(specials_id) as numspecials from ' . TABLE_SPECIALS . ' s join ' . TABLE_PRODUCTS . ' p where p.products_status = 1 and s.products_id = p.products_id and s.status = 1');
$num = tep_db_fetch_array($special_query);
$numpages = ((int)$num['numspecials'] = 0 ? 1 : ceil((int)$num['numspecials'] / MAX_DISPLAY_SEARCH_RESULTS));
for ($page = 1; $page <= $numpages; $page++) // create a url for every page of each manufacturer
  {$url = $base_url . $page;
  echo $page .' --> '. $url .'<br>';
  fwrite($fh, utf8_encode('<url><loc>' . smspecialchars($url) . "</loc><changefreq>".$scf."</changefreq></url>\n"));
  }
fwrite($fh, utf8_encode($xml_foot));
fclose($fh); 
echo $numpages . TEXT_COMPLETED;

echo '<p><a href="'.tep_href_link(FILENAME_SITEMAP,'selected_box=tools').'">'.TEXT_TO_MAINTENANCE.'</a><p>&nbsp;';
?>    
     </td></tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
