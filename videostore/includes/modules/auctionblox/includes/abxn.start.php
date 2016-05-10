<?php
/*
  $Id: abxn.start.php,v 1.9 2008/03/13 14:33:28 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
  // set the level of error reporting
  
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
  ini_set("display_errors", "on");
  
  date_default_timezone_set('America/New_York');

/*
  function debugWriteFile($str,$mode="a") {
    $fp = @fopen("abxn.txt",$mode);  @flock($fp, LOCK_EX); @fwrite($fp,$str); @flock($fp, LOCK_UN); @fclose($fp);
  }

  $postString = ''; foreach($_POST as $key => $val) $postString .= $key.' = '.$val."\n";
  if($postString != '') {
    debugWriteFile($postString,"w+");
  }
*/

  // include server parameters
  require_once(dirname(__FILE__) . '/configure.php');

  //load initialization file for cart shopping cart system
  require_once(DIR_FS_ABX5_EXTERNAL_CART . 'init.php');
   
  require_once(DIR_FS_ABX5_INCLUDES.'php.start.php');
  require_once(DIR_FS_ABX5_CLASSES.'abxModule.php');
  require_once(DIR_FS_ABX5_CLASSES.'API/abxToken.php');
  
  // include the list of project filenames
  require_once(DIR_FS_ABX5_INCLUDES.'filenames.php');

  // include the list of project database tables
  require_once(DIR_FS_ABX5_INCLUDES.'database_tables.php');
  require_once(DIR_FS_ABX5_FUNCTIONS.'bootstrap.php');
?>