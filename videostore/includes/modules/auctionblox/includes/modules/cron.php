<?php
/*
  $Id: cron.php,v 1.7 2005/04/12 14:08:11 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2007 AuctionBlox
*/

  class abxModule_Cron extends abxModule {

    function abxModule_Cron()
    {
        $this->process();
    }

    function process()
    {
       require_once(DIR_FS_ABX5_CLASSES . 'order/abxOrderManager.php');

       $orderManager = new abxOrderManager;
       $orderManager->synchronize();

//       $imagefile = DIR_FS_ABX . "images/spacer.gif";
//    
//       $length = filesize($imagefile);
//       $fh = fopen($imagefile, 'r');
//       $imagedata  = fread($fh, $length);
//       fclose($fh);    
//
//       ob_start();
//       $length = strlen($imagedata);
//       header('Last-Modified: '.date('r'));
//       header('Content-Length: '.$length);
//       header('Content-Type: image/jpeg');
//       print($imagedata);
//       ob_end_flush();       
    }

  }//end class
?>