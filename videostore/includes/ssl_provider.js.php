<?php
/*
  $Id: ssl_provider.js.php,v 1.0 2003/10/29 21:49:23 daemonj Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (defined('SSL_PROVIDER') && strtolower(SSL_PROVIDER) != 'none') {
    switch (strtolower(SSL_PROVIDER)) {
      case 'comodo':
// Comodo TrustLogo
?>
  <script  type="text/javascript" src="<?php echo (($request_type=='SSL') ? 'https://secure.comodo.net' : 'http://www.trustlogo.com'); ?>/trustlogo/javascript/trustlogo.js"></script>
<?php
        break;
      case 'verisign':
// VeriSign Secure Site
?>
  <script  type="text/javascript">
    <!--
      function popUp(url) {
        sealWin=window.open(url,"win",'toolbar=0,location=0,directories=0,status=1,menubar=1,scrollbars=1,resizable=1,width=500,height=450');
        self.name = "mainWin";
      }
    -->
  </script>
<?php
    }
  }
?>
