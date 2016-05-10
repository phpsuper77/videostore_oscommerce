<?php
/*
// WebMakers.com Added - Banner Manager - Column Banner
// Created by: Linda McGrath osCOMMERCE@WebMakers.com
// Test at: http://www.thewebmakerscorner.com
*/
?>
<?php
// BOF: WebMakers.com Added: Banner Manager - Column Banner
  $my_page_ssl=$HTTPS;

  // echo $my_page_ssl."<br>";
// define banner group per page
// add new case statement for additional pages  
  switch (true) {
  case ( (strstr($_SERVER['PHP_SELF'],'default') or strstr($PHP_SELF,'default')) ):
    $use_banner_group = '125x125';
    break;
//  case ( (strstr($_SERVER['PHP_SELF'],'product_info') or strstr($PHP_SELF,'product_info')) ):
//    $use_banner_group = 'product_info 125x125';
//    break;
  default:
    $use_banner_group = '125x125';
    break;
  }

  if ($banner = tep_banner_exists('dynamic', $use_banner_group)) {    

    switch (true) {
    case ( BANNERS_SHOW_ON_SSL=='1' and $my_page_ssl=='on' ):
      $show_banner_ad='1';
      break;
    case ( BANNERS_SHOW_ON_SSL=='0' and $my_page_ssl=='on' ):
      $show_banner_ad='0';
      break;
    default:
      $show_banner_ad='1';
      break;
    }
// echo "BANNERS_SHOW_ON_SSL: ".BANNERS_SHOW_ON_SSL."<br>";
// echo "show banner: ".$show_banner_ad."<br>";
    if ( $show_banner_ad=='1' ) {
?>
<!-- column_banner //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => BOX_HEADING_COLUMN_BANNER
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => tep_display_banner('static', $banner)
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- column_banner_eof //-->
<?php
    }
  }
// EOF: WebMakers.com Added: Banner Manager - Column Banner
?>