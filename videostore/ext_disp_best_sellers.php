<?php
/*
Copyright : (c) 2004 lasermemory.com - ken meade
Author: Ken meade
Date: 27/08/2004

for Oscommerce 2.2 MS1 
Link/Donation Ware, feel free to use this, and add to it, but
If you use this on a site, please email me and either give a link to
http://www.lasermemory.com 
or 
http://www.lasermemory.com/blog
or send a paypal donation to support@lasermemory.com
Please leave copyright message intact.

*/
// Remember current directory
$orig_dir=getcwd();

//currently in oscommerce/blogs need to be in /oscommerce so change directory 
//edit next line to change to your catalogue directory ( the .. goes up a directory level)


include('includes/application_top.php');
ob_start(); //start output buffering
// You can change the next line to use whatever box you require for example product info,best sellers,whats new,etc
include(DIR_WS_BOXES.'best_sellers.php');//include another file for the box you want
$box_buffer=ob_get_contents();//save output
ob_end_clean();//stop output buffering

//Correct image paths, edit next line for your site details
$box_buffer=str_replace("src=\"images", "src=\"http://www.travelvideostore.com/images",$box_buffer);

$box_buffer=str_replace("'", "\'" ,$box_buffer);	// escape qoutes
$box_buffer=str_replace("\n", "') \n document.write('" ,$box_buffer);  // add newlines
// alter table size if required
echo "document.write('" . '<table border="0" width="160" cellspacing="0" cellpadding="0">' . "')\n";
echo "document.write('" . $box_buffer ."')\n";
echo "document.write(' </table>')\n";
chdir($orig_dir);//move back to original directory
?>