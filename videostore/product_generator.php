<?php

include ('includes/application_top.php');

if (USE_CASHE==true)
	echo tep_cache_category_affiliate_product();
else
	include "top_affiliate_product.php";
?>