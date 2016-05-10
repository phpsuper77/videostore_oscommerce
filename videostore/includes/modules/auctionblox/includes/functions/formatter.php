<?php
/*
  $Id: formatter.php,v 1.10 2008/02/27 15:38:13 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/
  // Abx formatted timestamps are always Y-d-M H:i:s.  This is necessary for translation
  // back using strtotime() function
  $ABX_SYSTEM_TIMESTAMP_FORMAT = "Y-m-d H:i:s";

  function now_in_utc()
  {
    global $ABX_SYSTEM_TIMESTAMP_FORMAT;
        
    $utc_str = gmdate($ABX_SYSTEM_TIMESTAMP_FORMAT, time());
    return strtotime($utc_str);    
  }
  
  function now_in_usertime()
  {
  	return from_utc_to_user_timestamp(now_in_utc());
  }
  
  // formatted date is expected to be in UTC
  function abxformat_to_timestamp($strTime)
  {
  	return strtotime($strTime);
  }
  
  // timestamp is expected to be in UTC
  function timestamp_to_anyformat($timestamp = null, $format)
  {
    if($timestamp === null ) $timestamp = now_in_utc();
   
    return date($format, $timestamp);
  }

  function timestamp_to_abxformat($timestamp = null)
  {
    global $ABX_SYSTEM_TIMESTAMP_FORMAT;
    return timestamp_to_anyformat($timestamp, $ABX_SYSTEM_TIMESTAMP_FORMAT);
  }

  function timestamp_to_simpledateformat($timestamp = null)
  {
    return timestamp_to_anyformat($timestamp, DATE_FORMAT . " h:i A");
  }  
  
  function from_utc_to_user_timestamp($timestamp = null, $timezone = STORE_TIME_ZONE)
  {
    if($timestamp === null) $timestamp = now_in_utc();
    
    $timestamp += 60 * 60 * doubleval($timezone);
    
    return $timestamp;
  }
  
  function from_user_timestamp_to_utc($timezone = STORE_TIME_ZONE)
  {
    $timestamp -= 60 * 60 * doubleval($timezone);
    return $timestamp;
  }

  // Output a raw date string in the sql date format
  // $raw_date needs to be in this format: MM/DD/YYYY HH:MM:SS
  // return format: YYYY-MM-DD HH:MM:SS
  function gmt_date_to_local_timestamp($utctime, $timezone = STORE_TIME_ZONE)
  {
    if ( ($utctime == '0000-00-00 00:00:00') || ($utctime == '') ) return false;

    $time = strtotime($utctime);
    $time = gmt_timestamp_to_local_timestamp($time, $timezone);

    return $time;
  }

  function gmt_timestamp_to_local_timestamp($timestamp, $timezone = STORE_TIME_ZONE)
  {
    $timestamp += 60 * 60 * doubleval($timezone);

    return $timestamp;
  }

  function local_date_to_gmt_sql_format($date)
  {
    return sql_date_format( local_date_to_gmt_timestamp($date) );
  }

  function local_date_to_gmt_date_short($date)
  {
    return short_date_format( local_date_to_gmt_timestamp($date) );
  }

  function local_date_to_gmt_timestamp($date, $timezone = STORE_TIME_ZONE)
  {
    if ( ($date == '0000-00-00 00:00:00') || ($date == '') )
      return false;

    $aDateTime = explode(' ', $date);

    $sRawDate = abx_date_raw($date);

    $sDate = substr($sRawDate, 0, 4).'/'.
             substr($sRawDate, 4, 2).'/'.
             substr($sRawDate, 6, 2).' '.
             $aDateTime[1].' '.$aDateTime[2];

    $nTime = strtotime($sDate);

    return local_timestamp_to_gmt_timestamp($nTime, $timezone);

    //return local_timestamp_to_gmt_timestamp( strtotime($date) , $timezone);
  }

  function local_timestamp_to_gmt_date_short($timestamp)
  {
    return short_date_format( local_timestamp_to_gmt_timestamp($timestamp) );
  }

  function local_timestamp_to_gmt_timestamp($timestamp, $timezone = STORE_TIME_ZONE)
  {
    $timestamp -= 60 * 60 * doubleval($timezone);

    return $timestamp;
  }

  function date_to_sql_date_format($date)
  {
    if($date === null || 
	   !is_string($date) ||
	   empty($date))
	   return null;
	   
    $temp = strtotime($date);

	if($temp === -1 || $temp === false)
  	  return null;
	  
    return sql_date_format($temp);
  }

  function sql_date_format($timestamp)
  {
  	if(!isset($timestamp))
  	  return null;
  	  
    return strftime("%Y/%m/%d %H:%M:%S" , $timestamp);
  }

  // ex: 07/11/04 03:45 pm
  function simple_date_format($timestamp)
  {
    //return strftime("%m/%d/%y %I:%M %p" , $timestamp);
    return strftime(DATE_TIME_FORMAT, $timestamp);
  }

  function abx_short_date($timestamp)
  {
    if ( ($timestamp == '0000-00-00 00:00:00') || empty($timestamp) )
      return false;

    $nTime = strtotime(date_to_sql_date_format($timestamp));

    $datetime = explode(' ', short_date_format($nTime));

    //$datetime = explode(' ',date_to_sql_date_format($timestamp));

    return $datetime[0];
  }

  function short_date_format($timestamp)
  {
     return strftime(DATE_TIME_FORMAT , $timestamp);
  }

  function gmt_date_to_local_pretty_format($utctime)
  {
    return simple_date_format( gmt_date_to_local_timestamp($utctime) );
  }

  function gmt_date_to_local_short($utctime)
  {
    return short_date_format( gmt_date_to_local_timestamp($utctime) );
  }
  
function parse_iso8601_datetime($datetime) {
     $currentTime = time();
     $offset = date("Z", $currentTime);

     $matches = array();

     // Check to see if we're dealing with a UTC dateTime (ends in 'Z')
     // or if there's an offset specified (ends in '[+-]hh:mm’).
     if(preg_match("/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})([+-])(\d{2}):(\d{2})$/",
          $datetime, $matches) === 1) {
     	// Offset specified.
     	$dateString = $matches[1];

     	// Calculate the custom offset.
     	$customOffset = $matches[3] * 60 * 60;
     	$customOffset += $matches[4] * 60;

     	// Invert the custom offset as necessary.
			if($matches[2] == "+") {
     		$customOffset = -1 * $customOffset;
     	}

     	// Add the custom offset to the UTC offset to get the offset
     	// from the local timezone.
     	$offset += $customOffset;
     }
     else if(preg_match("/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})Z$/",
           $datetime, $matches) === 1) {
     	// Using the UTC timezone.
     	$dateString = $matches[1];
     }

     // Parse the date and time portion of the string.
     $time = strtotime($dateString);

      // Return the calculated UNIX time from above along with the offset
      // necessary to correct for the timezone specified.
      return $time + $offset;// 60 * 60 * doubleval($timezone);//+ $offset;                                     
}  
?>