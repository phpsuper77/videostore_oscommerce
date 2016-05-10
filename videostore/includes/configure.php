<?php
//error_reporting(error_reporting() & ~E_DEPRECATED);

/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://local.travelvideostore.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://local.travelvideostore.com'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', 'local.travelvideostore.com');
  define('HTTPS_COOKIE_DOMAIN', 'local.travelvideostore.com');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_MEDIA_TYPES', DIR_WS_IMAGES . 'media_type/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', 'C:/xampp/htdocs/travelvideostore/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', 'root');
  define('DB_DATABASE', 'travelvideostore1');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'

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
	if (!defined('OSC_STORE_FOLDER')) define('OSC_STORE_FOLDER','/');
	if (!defined('OSC_CONFIG_FOLDER')) define('OSC_CONFIG_FOLDER',OSC_STORE_FOLDER.'includes/addons/configs/');
	if (!defined('OSC_FUNCTION_FOLDER')) define('OSC_FUNCTION_FOLDER',OSC_STORE_FOLDER.'includes/addons/');
//	 echo OSC_CONFIG_FOLDER;exit;

	require_once(OSC_CONFIG_FOLDER.'osc_functions.php');
	require_once(OSC_CONFIG_FOLDER.'osc_seourls_ap.php');
	require_once(OSC_CONFIG_FOLDER.'osc_metatags_ap.php');
?>