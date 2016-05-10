<?php
/*
  $Id: abxModule.php,v 1.15 2008/04/02 18:46:24 devosc Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  class abxModule {

    function process()
    {
      return FALSE;
    }

    function display()
    {
      return FALSE;
    }

    function adminModules()
    {
      return array(
     );
    }

    function catalogModules()
    {
      return array(
        'cron',

        'feed_products',
        'orders_import',
        'retrieve_orders',
        'ipn_import',
        'test_cart',
        'test_import',
        'get_configuration',
        'get_categories',   
	'install',   
      );
    }

    function &moduleDisplay($module)
    {
      if(include(DIR_FS_ABX5_MODULES . $module . '.php')) {
        $module = explode('_', $module);

        for($i=0,$n=count($module);$i<$n;$i++)
          $module[$i] = ucfirst($module[$i]);
        
        $module = 'abxModule_'.implode('_',$module);
        $module =  new $module;

        if (is_object($module) === true && is_subclass_of($module, 'abxModule') === true)
          return $module->display();
        else
          return FALSE;
      }
      return FALSE;
    }

    function &adminPage($module)
    {
      $module = in_array($module,abxModule::adminModules()) ? $module : 'auctionblox';
      return abxModule::moduleDisplay($module);
    }

    function &catalogPage($module)
    {
      $module = in_array($module,abxModule::catalogModules()) ? $module : 'catalog_checkout';
      return abxModule::moduleDisplay($module);
    }
  }//end class
?>