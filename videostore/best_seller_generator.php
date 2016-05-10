<?php
 include ('includes/application_top.php');
if (USE_CASHE==true)
	echo tep_cache_category_affiliate_box();
else
	include "top_affiliate_category.php";
?>