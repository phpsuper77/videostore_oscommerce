<?php
/*
  $Id: bootstrap.php,v 1.9 2007/05/01 19:09:15 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2007 AuctionBlox
*/

  function require_once_cart($path)
  {
  	if(file_exists(DIR_FS_ABX5_EXTERNAL_CART . 'overrides/' . $path))
  		require_once(DIR_FS_ABX5_EXTERNAL_CART . 'overrides/' . $path);
    else
    	require_once(DIR_FS_ABX5_EXTERNAL_CART . $path);
  }
  function abxlog($str) {
    $myFile = DIR_FS_ABX5 . "auctionblox.log";
    $fh = fopen($myFile, 'a');
    @fwrite($fh, $str);
    @fclose($fh);  
  }
?>