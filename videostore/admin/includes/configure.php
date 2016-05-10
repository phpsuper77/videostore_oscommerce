<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://www.travelvideostore.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://www.travelvideostore.com');
  define('HTTPS_CATALOG_SERVER', 'https://secure.travelvideostore.com');
  define('ENABLE_SSL_CATALOG', true); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', '/home/terrae2/public_html/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/admin/'); // absolute path required
  define('DIR_FS_ADMIN', '/home/terrae2/public_html/admin/'); // absolute pate required
  define('DIR_WS_CATALOG', '/'); // absolute path required
  define('DIR_FS_CATALOG', '/home/terrae2/public_html/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('DIR_WS_FLAGS', DIR_WS_IMAGES . 'flags/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'terrae2_jay');
  define('DB_SERVER_PASSWORD', '12261957');
  define('DB_DATABASE', 'terrae2_osCommerce');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  define('DIR_WS_FEDEX_LABELS', DIR_WS_IMAGES . 'fedex/');
    // error_reporting(E_ALL);
	// ini_set('display_errors', '1');
	if (!defined('LCHAR_BACKSLASH')) define('LCHAR_BACKSLASH', str_replace(' ','','\ '));
    if (!function_exists('bugFixRequirePath')){
        function bugFixRequirePath($newPath){
            $stringPath = dirname(__FILE__);
            if (strstr($stringPath,":")) $stringExplode = LCHAR_BACKSLASH.LCHAR_BACKSLASH;
            else $stringExplode = "/";
            $paths = explode($stringExplode,$stringPath);
            $newPaths = explode("/",$newPath);
            if (count($newPaths) > 0){
                for($i=0;$i<count($newPaths);$i++){
                    if ($newPaths[$i] == "..") array_pop($paths);  
                }
                for($i=0;$i<count($newPaths);$i++){
                    if ($newPaths[$i] == "..") unset($newPaths[$i]);
                }
                reset($newPaths);
                $stringNewPath = implode($stringExplode,$paths).$stringExplode.implode($stringExplode,$newPaths);
                return $stringNewPath;
            }
        }
    }
	if (!defined('OSC_STORE_FOLDER')) define('OSC_STORE_FOLDER',bugFixRequirePath('../../'));
	if (!defined('OSC_CONFIG_FOLDER')) define('OSC_CONFIG_FOLDER',OSC_STORE_FOLDER.'includes/addons/configs/');
	if (!defined('OSC_FUNCTION_FOLDER')) define('OSC_FUNCTION_FOLDER',OSC_STORE_FOLDER.'includes/addons/');
	require_once(OSC_CONFIG_FOLDER.'osc_functions.php');
	require_once(OSC_CONFIG_FOLDER.'osc_seourls_ap.php');
?>