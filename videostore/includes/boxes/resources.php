<?php
/*
  Google Infobox, v 1.0 03/16/2005 by Mike Baker

  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

  Released under the GNU General Public License
  http://www.websolutionsbymike.com
  http://www.n2scents.com
  http://www.davesjerky.com
*/
?>
<!-- google ads Info Box //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => 'Advertising'
                              );
  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text'  => '<script type="text/javascript"><!--
google_ad_client = "pub-8269488350960681";
google_ad_width = 120;
google_ad_height = 240;
google_ad_format = "120x240_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "B4D0DC";
google_color_bg = "ECF8FF";
google_color_link = "0000CC";
google_color_url = "008000";
google_color_text = "6F6F6F";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>');

							    new infoBox($info_box_contents);
?>
</td></tr>
<!-- google ads_eof //-->