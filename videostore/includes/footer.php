<?php
	/*
	  $Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $
	
	  osCommerce, Open Source E-Commerce Solutions
	  http://www.oscommerce.com
	
	  Copyright (c) 2003 osCommerce
	
	  Released under the GNU General Public License
	
	
	<!-- Start Quantcast tag -->
	<script type="text/javascript">
	_qoptions={
	qacct:"p-01106DGIfw1bA"
	};
	</script>
	<script type="text/javascript" src="//secure.quantserve.com/quant.js"></script>
	<noscript>
	<a href="http://www.quantcast.com/p-01106DGIfw1bA" target="_blank"><img src="//secure.quantserve.com/pixel/p-01106DGIfw1bA.gif" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/></a>
	</noscript>
	
	<!-- End Quantcast tag -->

  <!-- Kontera ContentLink(TM);-->
  <?php
   $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
   if ($request_type == 'NONSSL') { 
  ?>
  <script type='text/javascript'>
  var dc_AdLinkColor = 'blue' ;
  var dc_PublisherID = 134874 ;
  </script>
  <script type='text/javascript' src='//kona.kontera.com/javascript/lib/KonaLibInline.js'>
  </script>
  <?php
	}
  ?>
  <!-- Kontera ContentLink(TM) -->

	
	  require(DIR_WS_INCLUDES . 'counter.php');
	*/

  ?>
  
  <table border="0" width="100%" cellspacing="0" cellpadding="1">
	<tr class="footer">
	  <td class="footer">&nbsp;&nbsp;<?php /* echo strftime(DATE_FORMAT_LONG); */ ?>&nbsp;&nbsp;</td>
	</tr>
  </table>
  
<div  align="center"> 
  <?php
  
  
	echo FOOTER_TEXT_BODY

	/*
	?>
	
	<br>
	<a href="http://www.travelvideostore.com"><img src="/images/footerlogos1.gif" border="0" alt="TravelVideoStore.com proudly supports the American Library Association, the International Society of Travel and Tourism Educators, American Society of Travel Agents, Cruise Line International Association, National Association of Commisioned Agents, Outside Sales Support Organization, Association of Retail Travel Agents, and many other organizations "></a>
	
		</td>
	  </tr>
	</table>
	
	<!-- Start Quantcast tag -->
	<script type="text/javascript">
	_qoptions={
	qacct:"p-01106DGIfw1bA"
	};
	</script>
	<script type="text/javascript" src="//secure.quantserve.com/quant.js"></script>
	<noscript>
	<a href="http://www.quantcast.com/p-01106DGIfw1bA" target="_blank"><img src="//secure.quantserve.com/pixel/p-01106DGIfw1bA.gif" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/></a>
	</noscript>
	<!-- End Quantcast tag -->
	<!-- Start CrossPixeltag -->
<script type="text/javascript"> (function(){ var sNew = document.createElement("script"); sNew.defer = true; sNew.src = "http://tag.crsspxl.com/s1.js?d=1028"; var s0 = document.getElementsByTagName('script')[0]; s0.parentNode.insertBefore(sNew, s0); })(); </script>
                  <!-- End CrossPixeltag -->

	<?php
	
	  if ($banner = tep_banner_exists('dynamic', '468x50')) {
	?>
	<br>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  <tr>
		<td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
	  </tr>
	</table>
	<?php
	  }
	*/
// ?>
</div>
  <?php
  // NEW RELIC 
  if(extension_loaded('newrelic')) { 
  echo newrelic_get_browser_timing_footer(); 
  }
    ?>
<?php
	require(DIR_WS_FUNCTIONS . 'user_tracking.php');
  if ( OSC_CONFIG_USER_TRACKING == 'true') { tep_update_user_tracking(); }
  
  $domain = GetHostByName($REMOTE_ADDR);

/* ($domain=="71.100.48.165") or  


//if (($domain=="194.242.117.148")){
//var_dump($_SESSION);
//echo "<hr/>";
//echo "<br/>".FOOTER_TEXT_REQUESTS_SINCE . ' ' . $db_queries_count."<br/><br/>";
//echo "All queries list:<br/>".$_db_queries_list;	
//}
//print_r($_SESSION);

//<a href='http://www.markosweb.com/www/travelvideostore.com/'><img border='0' src='http://widgets.markosweb.com/seob/travelvideostore.com.gif'/></a>
*/
?>