<?php
/*
  $Id: catalog.php,v 1.10 2008/03/13 14:33:30 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
 
  switch($_GET['abx']) {

    case 'feed_products':
    case 'get_categories':      
    case 'orders_import':
    case 'cron':
    case 'test_cart':
    case 'test_import': 
    case 'retrieve_orders':
    case 'get_configuration':
    case 'install':
      
      require_once(dirname(__FILE__).'/includes/abxn.start.php');
      //if(!abxToken::verifyRequest($authcode)) { echo 'Invalid authentication token'; exit; }

      $abxPage = abxModule::catalogPage($_GET['abx']);
      if ($abxPage) {
        require_once $abxPage->display();
      }
      break;

    default:
//      require_once dirname(__FILE__).'/includes/catalog.start.php';
//      $abxPage = abxModule::catalogPage($_GET['abx']);
//
//      if (!$abxPage) {
//        return; //FIXME .... error msg?
//      }
//
//      if (strpos(PROJECT_VERSION, 'CRE Loaded6') !== false) 
//      {
//        $abxPage->setTemplate('preview');
//        $content = 'catalog_checkout';
//        require DIR_WS_TEMPLATES.TEMPLATE_NAME.'/'.TEMPLATENAME_MAIN_PAGE;
//        
//      } else {
//        
//        require_once $abxPage->display();
//      }

      break;

  }//switch
?>