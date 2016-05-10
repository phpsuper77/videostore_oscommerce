<?php
/*
  $Id: MySQL.php,v 1.5 2008/04/13 23:28:26 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  if (function_exists('mysql_fetch_assoc') === false) {

    function mysql_fetch_assoc($resource)
    {
      return mysql_fetch_array($resource, MYSQL_ASSOC);
    }

  }

  if (class_exists('abxDatabase') === false) {

    require_once(DIR_FS_ABX5_CLASSES . 'abxDatabase.php');

  }

  class abxDatabase_MySQL extends abxDatabase {

    var $dbHandler;

    function &connect($dbhost,$dbuser,$dbpassword)
    {
      $connect_function = (defined('ABX_DB_USE_PCONNECT') && (ABX_DB_USE_PCONNECT == 'true')) ? 'mysql_pconnect' : 'mysql_connect';

      $this->dbHandler = @$connect_function($dbhost,$dbuser,$dbpassword);



      if ($this->dbHandler === false)
        return $this->setError("Error establishing a database connection!");

    $this->mysqlVersion=$this->getVersion();

      return $this->dbHandler;
    }

    function disconnect()
    {
      return @mysql_close($this->dbHandler);
    }

    function selectDatabase($databaseName)
    {
      if ($selectDatabase = @mysql_select_db($databaseName,$this->dbHandler) === false)
        return $this->setError();

      return $selectDatabase;
    }

    function sqlQuery(&$query_string)
    {

   //BOF 3.x compatibility
   if (strpos($query_string, 'SQL_CALC_FOUND_ROWS') !== false) {
     $query_string=str_replace('SQL_CALC_FOUND_ROWS', " /*!40000 SQL_CALC_FOUND_ROWS */ ", $query_string);
   }
   //EOF

    return @mysql_query($query_string,$this->dbHandler);
    }

    function freeResult(&$resource)
    {
      return @mysql_free_result($resource);
    }

    function insertID()
    {
      return @mysql_insert_id($this->dbHandler);
    }

    function affectedRows()
    {
      return @mysql_affected_rows($this->dbHandler);
    }

    function numRows(&$resource)
    {
      return @mysql_num_rows($resource);
    }

    function calcFoundRows(&$query_string)
    {
    // 3.x compatability


   if($this->mysqlVersion>=40000){


    if (strpos($query_string, 'SQL_CALC_FOUND_ROWS') !== false) {

        $results = $this->fetch_row('select found_rows() as total','array');

        return @$results[0];

      }

  }
  $query_string=str_replace('FROM','from' ,$query_string );
      return parent::calcFoundRows($query_string);
    }

    function fetch_function($fetch_type = 'assoc')
    {
      switch($fetch_type) {

        case 'object':

          return 'fetch_object';

          break;

        case 'array':

          return 'fetch_array';

          break;

        case 'assoc':
        default:

          return 'fetch_assoc';

          break;

      }

    }

    function fetch_object(&$resource)
    {
      return @mysql_fetch_object($resource);
    }

    function fetch_array(&$resource)
    {
      return @mysql_fetch_array($resource);
    }

    function fetch_assoc(&$resource)
    {
      return @mysql_fetch_assoc($resource);
    }

    function escape($value) {
      return $this->escape_string($value);
    }

    function escape_string(&$value)
    {
      if(!is_string($value))
        return "";
      
      if (function_exists('mysql_real_escape_string'))

        return mysql_real_escape_string($value, $this->dbHandler);

      elseif (function_exists('mysql_escape_string'))

        return mysql_escape_string($value);

      return addslashes($value);
    }

    function setError($str='')
    {
      $error_string = '<ul class="noindent">';

      if (is_resource($this->dbHandler))
        $error_string .= '<li>' . '[<b>'. @mysql_errno($this->dbHandler) . '</b>]&nbsp;' . @mysql_error($this->dbHandler) . '</li>';

      if (empty($str) === false)
        $error_string .= '<li>' . $str . '</li>';

      $error_string .= '</ul>';

      return parent::setError($error_string);
    }

    // 3.x compatabitiy
    function getVersion(){
    $query="SELECT VERSION() AS version";

      $row=$this->fetch_row($query);
      $match = explode(".", $row["version"]);

       if (!isset($match) || !isset($match[0])) {
           $match[0] = 3;
       }
       if (!isset($match[1])) {
           $match[1] = 23;
       }
       if (!isset($match[2])) {
           $match[2] = 32;
       }

       $version= (int)sprintf("%d%02d%02d", $match[0], $match[1],intval($match[2]));

     return $version;
    }

  }//end class
?>