<?php
/*
  $Id: cron.php,v 1.7 2005/04/12 14:08:11 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2007 AuctionBlox
*/
  require_once_cart('includes/functions/integration.php');
  
  class abxModule_Install extends abxModule {

    function abxModule_Install()
    {
        $this->process();
    }

    function process()
    {
       abx_install();
       echo 'done';

    }

  }//end class
?>