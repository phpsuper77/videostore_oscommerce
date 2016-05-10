<?php
/*
  $Id: abxDatabase.php,v 1.10 2008/03/13 14:33:11 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  if (class_exists('abxDatabaseQuery') === false) {

    require_once(dirname(__FILE__) . '/abxDatabaseQuery.php');

  }

  if (class_exists('abxCache') === false) {

    require_once(dirname(__FILE__) . '/abxCache.php');

  }

  require_once(realpath(dirname(__FILE__) . '/../database_tables.php'));

  class abxDatabase extends abxCache {

    function abxDatabase($dbhost, $dbuser, $dbpassword)
    {
      $this->setErrorReporting(true);

      $this->connect($dbhost,$dbuser,$dbpassword);

			// This no longer works in PHP5
      //@register_shutdown_function(array(&$this,'disconnect'));
    }

    function disconnect()
    {
      //if (strtolower(get_class($this)) != 'abxDatabase' && is_subclass_of($this, 'abxDatabase'))
        //@call_user_func(array(&$this,'disconnect'));
    }

    function &connect($dbhost, $dbuser, $dbpassword, $dbType = 'MySQL')
    {
      if (include_once(dirname(__FILE__) . '/Databases/' . $dbType . '.php')) {

        $classname = 'abxDatabase_' . $dbType;

        return new $classname($dbhost,$dbuser,$dbpassword);

      } else {

        echo "<div class=\"smallText\" style=\"border:1px solid #F4F4F4; background-color: #f1f1ec; padding: 5px;\">\n<span style=\"font-size: larger;font-weight: bold;color: red;\">Database Error</span><br />\nCannot load database class: {$dbType}\n</div>\n";

        exit();

      }

      return FALSE;
    }

    function &query($query_string = '', $fetch_type = '')
    {
      $abxDatabaseQuery = new abxDatabaseQuery($this);

      if (empty($query_string) === false)
        $abxDatabaseQuery->execute($query_string,$fetch_type);

      return $abxDatabaseQuery;
    }

    function &fetch_results($query_string, $fetch_type = '', $key = null)
    {
      $abxDatabaseQuery = $this->query($query_string, $fetch_type);

      return $abxDatabaseQuery->results($key);
    }

    function &fetch_row($query_string, $fetch_type = '')
    {
      $abxDatabaseQuery =  &$this->query($query_string, $fetch_type);

      $row = $abxDatabaseQuery->next();

      $abxDatabaseQuery->freeResult();

      return (empty($row) ? array() : $row);
    }

    function &fetch_value($name, $table, $where)
    {
      $abxDatabaseQuery =  &$this->query("select $name from $table where $where");

      $value = $abxDatabaseQuery->next();

      $abxDatabaseQuery->freeResult();

      return empty($value) ? '' : $value[$name];
    }

    function insert($table, $data)
    {
      $abxDatabaseQuery = new abxDatabaseQuery($this);

      return $abxDatabaseQuery->insert($table, $data);
    }

    function update($table, $data, $where = '')
    {
      $abxDatabaseQuery = new abxDatabaseQuery($this);

      return $abxDatabaseQuery->update($table,$data,$where);
    }

    function delete($table, $where = '1')
    {
      $abxDatabaseQuery = new abxDatabaseQuery($this);

      return $abxDatabaseQuery->delete($table,$where);
    }

    function calcFoundRows(&$query_string)
    {
      $total_query = substr($query_string, 0, strpos($query_string, ' limit'));

      $results = $this->fetch_row('select count(*) as total ' . substr($total_query, strpos($query_string, 'from ')), 'array');

      return @$results[0];
    }

    function setErrorReporting($errorReport = true)
    {
      $this->errorReport = $errorReport;
    }

    function isError($error = true)
    {
      if (empty($error) === false)
        return $this->error === $error;

      return $this->error;
    }

    function setError($str = '')
    {
      $this->error = (bool)$error;

      $str = empty($str) ? 'An error has occurred...' : $str;

      $this->errorString = "<div class=\"smallText\" style=\"border:1px solid #F4F4F4; background-color: #f1f1ec; padding: 5px;\">\n<span style=\"font-size: larger;font-weight: bold;color: red;\">Database Error</span><br />\n{$str}\n</div>\n";

      if ($this->errorReport === true)
        echo $this->getError();

      return FALSE;
    }

    function getError()
    {
      return $this->errorString;
    }

  }//end class
?>