<?php
/*
  $Id: php.start.php,v 1.1.1.1 2005/04/01 21:28:31 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox
*/

  function abx_remove_magic_quotes(&$val) {

    if(is_array($val))

      array_walk($val,'abx_remove_magic_quotes');

    elseif (get_magic_quotes_gpc())

      $val = stripslashes($val);

    else

      $val = stripslashes($val);

  }

  abx_remove_magic_quotes($_GET);
  abx_remove_magic_quotes($_POST);
  //abx_remove_magic_quotes($_COOKIE);

  //Now get rid of any invalid oID requests
  function abx_sanitize_int(&$val) {

    if(is_array($val))

      array_walk($val,'abx_sanitize_int');

    elseif (!preg_match("/^[0-9]+$/",$val))

      $val = FALSE;

  }

  if(isset($_GET['oID'])) {

    abx_sanitize_int($_GET['oID']);

    if(is_array($_GET['oID']))

      $_GET['oID'] = array_filter($_GET['oID']);

    elseif ($_GET['oID'] === FALSE)

      unset($_GET['oID']);
  }

  //abxList is an array of integers
  if(isset($_POST['abxList'])) {

    abx_sanitize_int($_POST['abxList']);

    if(is_array($_POST['abxList']))

      $_POST['abxList'] = array_filter($_POST['abxList']);

    elseif ($_POST['abxList'] === FALSE)

      unset($_POST['abxList']);
  }

  //Sanitize the flow
  if(isset($_GET['abx']))
    if (!preg_match("/^[a-zA-Z0-9_-]+$/",$_GET['abx']))
      unset($_GET['abx']);


?>