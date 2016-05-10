<?php
/*
  $Id: abxDatabaseQuery.php,v 1.5 2006/10/02 03:06:47 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  class abxDatabaseQuery {

    var $dbClass,
        $fetch_type,
        $resource,
        $results,
        $num_rows,
        $affected_rows,
        $insert_id;

    function abxDatabaseQuery(&$dbClass)
    {
      $this->dbClass = $dbClass;
    }

    function execute_cached_query($cache_name, $query_string, $fetch_type = '')
    {
      if($this->dbClass->is_cached($cache_name)) {

        $results = $this->dbClass->read_cache($cache_name);

        $this->setResults($results);

      } else {

        $this->execute($query_string,$fetch_type);

        $results =  $this->results();

        $this->dbClass->cache($cache_name,$results,'5/seconds');

      }
    }

    function execute($query_string, $fetch_type = '')
    {
      $this->resource = $this->dbClass->sqlQuery($query_string);

      if ($this->resource === false)
        return $this->setError('[<b>Query</b>] '.$query_string);

      //How should the results be returned (associative, array or object)
      $this->fetch_type = $fetch_type;

      //SELECT
      $this->num_rows = preg_match('/^SELECT/i', ltrim($query_string)) ? $this->dbClass->numRows($this->resource) : null;

      //INSERT, UPDATE or DELETE
      $this->affected_rows = preg_match('/^INSERT|^UPDATE|^DELETE/i', ltrim($query_string)) ? $this->dbClass->affectedRows($this->resource) : null;

      //INSERT
      $this->insert_id = preg_match('/^INSERT/i', ltrim($query_string)) ? $this->dbClass->insertID($this->resource) : null;

      return $this->resource;
    }

    function &results($key = null)
    {
      $numRows = 0;
      if ($this->numRows())
	  {
        while($row = $this->next())
        {
          if($key != null)
          	$this->results[$row[$key]] = $row;
          else
	        $this->results[$numRows++] = $row;
        }
	    
	    $this->num_rows = $numRows;
	  }
      return $this->results;
    }

    function setResults(&$array)
    {
      $this->results = $array;

      $this->freeResult();

      $this->num_rows = count($array);
    }

    function numRows()
    {
      if (is_resource($this->resource))

        return $this->dbClass->numRows($this->resource);

      elseif (isset($this->results))

        return $this->num_rows;
    }

    function freeResult()
    {
      if (!is_null($this->resource))
        $this->dbClass->freeResult($this->resource);

      unset($this->result);

      $this->resource = null;
    }

    function next()
    {
      if (is_resource($this->resource)) {

        return $this->fetch();

      } else {

        static $index = 0;

        if ($index < $this->numRows())

          return $this->results[$index++];

      }
    }

    function fetch()
    {
      $fetch_function = $this->dbClass->fetch_function($this->fetch_type);

      $row = @$this->dbClass->$fetch_function($this->resource);

      if ($row === false) {

        $this->freeResult();

        return false;

      } else {

        return $this->result = $row;

      }
    }

    function insertID()
    {
      return $this->insert_id;
    }

    function affectedRows()
    {
      return $this->affected_rows;
    }

    function insert($table, $array)
    {
      $query  = 'INSERT INTO '.$table.' ('.implode(', ',array_keys($array)).') ';

      $query .= 'VALUES ('.implode(', ',array_values(array_map(array(&$this,'escape_string'),$array))).');';

      $this->execute($query);

      return $this->insertID();
    }

    function update($table, $array, $where = '')
    {
      foreach($array as $key => $val)

        $data[] = $key . '=' . $this->escape_string($val);

      $query = 'UPDATE ' . $table . ' SET ' . implode(',', $data) . (empty($where) ? '' : ' WHERE ' . $where);

      $this->execute($query);

      return $this->affectedRows();
    }

    function delete($table, $where = '')
    {
      if (empty($where) === true)
        $where = '1';

      $this->execute('DELETE FROM ' . $table . ' WHERE ' . $where);

      return $this->affectedRows();
    }

    function resultValue($column, $type = 'string')
    {
      if(isset($this->result) === false)
        $this->next();

      switch($type) {

        case 'integer':

          return (int) $this->result[$column];

          break;

        case 'string':
        default:

          return $this->result[$column];

          break;

      }
    }

    function value($column)
    {
      return $this->resultValue($column,'string');
    }

    function valueInt($column)
    {
      return $this->resultValue($column,'integer');
    }

    function stripslashes($value)
    {
      if (is_object($value) === true) {

        $objArray = get_object_vars($value);

        foreach($objArray as $key => $val)

          $value->{$key} = $this->stripslashes($val);

        return $value;

      } elseif (is_array($value)) {

        foreach($value as $key => $val)

          $array[$key] = $this->stripslashes($val);

        return $array;

      } elseif (get_magic_quotes_runtime()) {

        return stripslashes(stripslashes($value));

      }

      return stripslashes($value);
    }

    function escape_string($value)
    {
	if (is_numeric($value))
          return "'" . $value . "'";
	  
	 if(is_string($value))
	 {
	   if (in_array(strtolower($value),array('now()','null')))
             return $value;	 
	   else 
	     return "'" . $this->dbClass->escape_string($value) . "'";
	 }
	 
	 return "''";
    }

    function setError($str='')
    {
      return $this->dbClass->setError($str);
    }

  }//end class
?>