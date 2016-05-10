<?php
/*
  $Id: ssl_provider.php,v 1.0 2003/10/29 21:49:23 daemonj Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

<img src="images/bar-clap.gif">

  Released under the GNU General Public License
*/

if (defined('SSL_PROVIDER') && strtolower(SSL_PROVIDER) != 'none') {
?>
<!-- ssl_provider info box //-->
<tr>
	<td>

		<?php
// used a switch statment to accomodate additional providers for future releases.
		    switch (strtolower(SSL_PROVIDER)) {
		      case 'comodo':
            $provider_heading = BOX_HEADING_SSL_PROVIDER_COMODO;
            $path = ((getenv('HTTPS')=='on') ? HTTPS_SERVER : HTTP_SERVER);
            if (defined('DIR_WS_CATALOG')) {
// Using pre-MS2 release
              $path .= DIR_WS_CATALOG;
            } else {
// Using MS2 or later release
              $path .= ((getenv('HTTPS')=='on') ? DIR_WS_HTTPS_CATALOG : DIR_WS_HTTP_CATALOG);
            }
		        $provider_script = '<script language="javascript" type="text/javascript">TrustLogo("' . $path . DIR_WS_IMAGES . 'secure_site.gif' . '", "SC", "none");</script>';
		        break;
		      case 'geotrust':
            $provider_heading = BOX_HEADING_SSL_PROVIDER_GEOTRUST;
		        $provider_script = '<script language="javascript" type="text/javascript" src="//smarticon.geotrust.com/si.js"></script>';
		        break;
		      case 'verisign':
            $provider_heading = BOX_HEADING_SSL_PROVIDER_VERISIGN;
		        $provider_script = '<a href="javascript:popUp(\'https://digitalid.verisign.com/as2/PASTE YOUR ISSUER DIGEST NUMBER HERE\')"><img src="\' . (($request_type==\'SSL\') ? HTTPS_SERVER : HTTP_SERVER) . \'/\' . DIR_WS_IMAGES . \'verisign_secure_site_gold_seal.gif\' . \'" width="98" height="102" border="0"></a>';
            break;
		    }
  			$info_box_contents = array();
  			$info_box_contents[] = array('align' => 'left', 'text'  => $provider_heading);
  			new infoBoxHeading($info_box_contents, false, false);

		  	$info_box_contents = array();
		  	$info_box_contents[] = array('align' => 'center', 'text'  => $provider_script);
		  	new infoBox($info_box_contents);
/*		?>
<center>
<?php
if($_SERVER['HTTPS']) { ?>
<a href="https://www.securitymetrics.com/site_certificate.adp?s=96%2e31%2e78%2e87&amp;i=173533" target="_blank" >
<img src="https://www.securitymetrics.com/images/sm_ccsafe_wh.gif" alt="SecurityMetrics for PCI Compliance, QSA, IDS, Penetration Testing, Forensics, and Vulnerability Assessment" border="0"></a> 
<?php
}
*/
?>
</td>
</tr>
<!-- ssl_provider_eof //-->
<?php
  }
?>