<?PHP
// $Id: index.html,v 1.5 2003/09/16 09:14:42 Administrator Exp $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Hartmut König (h.koenig@snakelab.de)
// Purpose of file:  SEO
// ----------------------------------------------------------------------

  require('includes/application_top_sitemap.php');

  include('includes/sitemap_includes.php');


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo SITEMAP_TITLE; ?></title>
<meta name="Keywords" content="<?php echo SITEMAP_KEYWORDS; ?>">
<meta name="Description" content="<?php echo SITEMAP_DESCRIPTION; ?>">
<META NAME="coverage" CONTENT="Worldwide">
<META NAME="robots" CONTENT="index,follow"> 
<META NAME="country" CONTENT="<?php echo SITEMAP_COUNTRYCODE; ?>">
<META NAME="author" CONTENT="<?php echo SITEMAP_AUTHOR; ?>">
<META NAME="organization-Email" CONTENT="<?php echo SITEMAP_EMAIL; ?>">
<META NAME="copyright" CONTENT="<?php echo SITEMAP_COPYRIGHT; ?>">

<base href="<?php echo  HTTP_SERVER . DIR_WS_HTTP_CATALOG; ?>">
<?PHP 

    //-- All non-spiders will be redirected to true home page
      redirectBrowser(); 

?>


<style type="text/css">
<!--

H1 {
    font-family:Verdana, Arial, Helvetica, sans-serif;
    font-size:10pt;
    color: #000000;
    }
-->
</style>
	
</HEAD>
<BODY>
<h1>
<a href="http://travelvideostore.com/index.php"><?PHP echo SITEMAP_TITLE; ?></a>
<br><br>
</h1>

<a href="sitemap_products.html"><b><?PHP echo SITEMAP_TITLE; ?></b></a><br />

<a href="sitemap_categories.html"><b><?PHP echo SITEMAP_DESCRIPTION; ?></b></a><br />

<h1><?php echo SITEMAP_TITLE; ?></h1>
<b><?php echo  SITEMAP_KEYWORDS; ?></b><br />
<h1><?php echo SITEMAP_DESCRIPTION; ?></h1>
<h1><?php echo SITEMAP_KEYWORDS; ?></h1>
<a href="sitemap_categories.html"><?php echo SITEMAP_CATEGORYLIST; ?></a><br />
<a href="sitemap_products.html"><?php echo SITEMAP_PRODUCTLIST; ?></a><br />
<a href="sitemap_series.html"><?php echo SITEMAP_PRODUCTLIST; ?> series</a><br />
<a href="sitemap_producers.html"><?php echo SITEMAP_PRODUCTLIST; ?> producers</a><br />
<a href="sitemap_distributors.html"><?php echo SITEMAP_PRODUCTLIST; ?> distributors</a><br />
<?php echo SITEMAP_LINKS; ?>

</body>
</HTML>


