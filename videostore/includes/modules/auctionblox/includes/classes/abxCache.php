<?php
/*
  $Id: abxCache.php,v 1.2 2005/04/05 12:52:25 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
  /*
  CREATE TABLE abx_cache (
    id varchar(32) NOT NULL default '',
    name varchar(127) NOT NULL default '',
    group_name varchar(127) NOT NULL default 'global',
    data longtext NOT NULL,
    expires datetime NOT NULL default '0000-00-00 00:00:00',
    date_added datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY (id,group_name),
    INDEX (expires)
  ) TYPE=MyISAM;
  */

  class abxCache {

    function is_cached($cache_name)
    {
      $id = $this->cache_id($cache_name);

      $expires = $this->fetch_value('expires',TABLE_ABX_CACHE,"id = '" . addslashes($id) . "'");

      if(empty($expires) === true)
        return FALSE;

      if ($expires <= $this->date()) {

        $this->clear_cache($cache_name);

        return FALSE;

      }

      return TRUE;
    }

    function cache_id($cache_name)
    {
      return @md5($cache_name);
    }

    function clear_cache($cache_name)
    {
      $this->delete(TABLE_ABX_CACHE,"id = '" . addslashes($this->cache_id($cache_name)) . "'");
    }

    function cache($cache_name, &$object, $expires = '2/seconds')
    {
      $sql_array = array(
        'id' => @md5($cache_name),
        'name' => $cache_name,
        'data' => @serialize($object),
        'expires' => $this->expiry_date($expires),
        'date_added' => 'now()'
      );

      return $this->insert(TABLE_ABX_CACHE,$sql_array);
    }

    function eval_cache($cache_name)
    {
      $cache = $this->read_cache($cache_name);

      if(empty($cache) === false) {

        $result = eval("{$cache}");

        if(empty($result) === false)

          return $result;

        else if ($result == NULL)

          return TRUE;

      }

      return FALSE;
    }

    function read_cache($cache_name)
    {
      $cache = $this->fetch_row("SELECT data, expires FROM " . TABLE_ABX_CACHE . " WHERE id='" . md5($cache_name) . "'");

      if ( empty($cache) === false && $cache['expires'] > $this->date() )

        return @unserialize($cache['data']);

      return NULL;
    }

    function date($timestamp = '')
    {
      return empty($timestamp) ? date("Y-m-d h:i:s") : date("Y-m-d h:i:s",$timestamp);
    }

    function expiry_date($expires)
    {

      list($value,$interval) = explode('/', strtolower($expires));

      if (empty($value) === true || in_array($interval,array('hours','minutes','seconds','months','days','years')) === false) {

        $value = '1';

        $interval = 'months';

      }

      $$interval = $value;

      return $this->date(mktime(date("h") + (int)"${hours}", date("i") + (int)"${minutes}", date("s") + (int)"${seconds}", date("m") + (int)"${months}", date("d") + (int)"${days}", date("Y") + (int)"${years}"));
    }

  }//end class
?>