<?php
/*
  $Id: auctionblox.php,v 1.2 2008/02/27 15:38:09 auctionblox Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004-2010 AuctionBlox

  Released under the GNU General Public License
*/
  if (file_exists('includes/local/configure.php') === true)
    require_once('includes/local/configure.php');

  // include server parameters
  if (file_exists('includes/configure.php') === true)
    require_once('includes/configure.php');

  require_once('includes/modules/auctionblox/catalog.php');
?>