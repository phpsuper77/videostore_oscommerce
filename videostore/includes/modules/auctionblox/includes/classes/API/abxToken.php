<?php
/*
  $Id: abxToken.php,v 1.4 2007/04/01 01:19:12 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  class abxToken {

    var $secret = array('A', 'B', 'C');

    // constructor
    function abxToken() {}

    function createStringFromArray($arr)
    {
      $str = '';
      foreach($arr as $key => $char )
        $str .= $char;

      return $str;
    }

    function selfsign($params)
    {
      $signature = '';

      foreach($params as $key => $param )
        $signature .= $param;

      $signature .= $this->createStringFromArray($this->secret);

      $signature = md5($signature);

      return $signature;
    }

    function sign($params, $passcode)
    {
      $signature = '';

      foreach($params as $key => $param )
        $signature .= $param;

      $signature .= $passcode;

      $signature = md5($signature);

      return $signature;
    }
    
    function verifyRequest($authcode)
    {
      if(isset($_SERVER['HTTP_AUCTIONBLOX_SIGNATURE']))
      {
        $sellerId  = $_SERVER['HTTP_AUCTIONBLOX_SELLERID'];
        $action    = $_SERVER['HTTP_AUCTIONBLOX_EXECUTE'];
        $version   = $_SERVER['HTTP_AUCTIONBLOX_VERSION'];
        $timestamp = $_SERVER['HTTP_AUCTIONBLOX_TIMESTAMP'];
        $signature = $_SERVER['HTTP_AUCTIONBLOX_SIGNATURE'];
      }
      else
      {
        $sellerId  = $_POST['sellerID'];
        $action    = $_POST['execute'];
        $version   = $_POST['version'];
        $timestamp = $_POST['timestamp'];
        $signature = $_POST['signature'];
      }
      
      $token = abxToken::sign(
           array(
              $sellerId,
              $action,
              $version,
              $timestamp
            ),
            $authcode
          );  
      
      if(strcasecmp($signature, $token) === 0)
        return true;

      return false;
    }
  }//end class
?>