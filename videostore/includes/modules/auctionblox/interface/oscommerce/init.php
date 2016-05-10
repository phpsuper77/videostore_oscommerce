<?php
  // create additional constants for file system level access
  define('DIR_FS_INCLUDES', DIR_FS_CATALOG . DIR_WS_INCLUDES);
  define('DIR_FS_FUNCTIONS', DIR_FS_CATALOG . DIR_WS_FUNCTIONS);
  define('DIR_FS_CLASSES', DIR_FS_CATALOG . DIR_WS_CLASSES);
  define('DIR_FS_MODULES', DIR_FS_CATALOG . DIR_FS_MODULES);
  define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . DIR_WS_TEMPLATES);
  define('DIR_FS_EXTENSIONS', DIR_FS_CATALOG . 'ext/');
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);  

  require(DIR_FS_INCLUDES . 'database_tables.php');
  require(DIR_FS_INCLUDES . 'filenames.php');
  require(DIR_FS_FUNCTIONS . 'general.php');
  require(DIR_FS_FUNCTIONS . 'database.php');
  require(DIR_FS_CLASSES . 'currencies.php');
  
  // make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');
  
  $currencies = new currencies();  
  
  // define our database connection
  define('ABX_DB_SERVER', DB_SERVER); // eg, localhost - should not be empty for production servers
  define('ABX_DB_USERNAME', DB_SERVER_USERNAME);
  define('ABX_DB_PASSWORD', DB_SERVER_PASSWORD);
  define('ABX_DB_DATABASE', DB_DATABASE);
  define('ABX_DB_USE_PCONNECT', 'false'); // use persistent connections?
  define('ABX_DB_STORE_SESSIONS', STORE_SESSIONS); // leave empty '' for default handler or set to 'mysql'

  require_once(DIR_FS_ABX5_CLASSES.'abxDatabase.php');
  $abxDatabase = &abxDatabase::connect(ABX_DB_SERVER, ABX_DB_USERNAME, ABX_DB_PASSWORD);
  $abxDatabase->selectDatabase(ABX_DB_DATABASE);

  $config = &$abxDatabase->query('select configuration_value as cfgValue from ' . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_CURRENCY'");
  if($config->next())  define('DEFAULT_CURRENCY', $config->value('cfgValue')); 
  
  $config = &$abxDatabase->query('select configuration_value as cfgValue from ' . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_LANGUAGE'");
  if(!$config->next())  
  {
    echo 'Failed to find default language in config file';
  }
  
  $lang = $config->value('cfgValue');
  $config = &$abxDatabase->query('select * from ' . TABLE_LANGUAGES . " where code='$lang'");
  if(!$config->next())  
  {
    echo 'Failed to find default language in language file';
  }
  
  $_SESSION['language'] = $config->value('directory');
  $_SESSION['languages_id'] = $config->value('languages_id');
  
  $language = $_SESSION['language'];
  $languages_id = $_SESSION['languages_id'];  
  
  //echo 'lang='.$languages_id . $language; exit;
?>