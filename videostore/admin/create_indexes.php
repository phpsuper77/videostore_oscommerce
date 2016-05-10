<?php
/*
  $Id: stats_products_viewed.php,v 1.27 2003/01/29 23:22:44 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
  <td width="100%" valign="top">
    <table border="0" width="600" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" class="pageHeading">Create Indexes<br><br></td>
      </tr>
<?php if($create_indexes){ ?>
      <tr>
        <td colspan="2" class="smallText"><b>Create indexes again</b><a href="<?php echo $PHP_SELF; ?>?create_indexes=again">Click here</a>!<br><br></td>
      </tr>
      <tr>
        <td class="smallText"><b>First all indexes and primary keys will be droped...</b><br></td>
        <td class="smallText"><b>... and then re-created!</b><br></td>
      </tr>
      <tr>
        <td class="smallText">
        <?php
        //Drop all indexes and primary keys 
        if(mysql_query("ALTER TABLE address_book DROP INDEX idx_address_book_id, DROP INDEX idx_customers_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> address_book</font><br>";
        if(mysql_query("ALTER TABLE categories DROP INDEX idx_parent_id, DROP INDEX idx_sort_order")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> categories</font><br>";
        if(mysql_query("ALTER TABLE categories_description DROP INDEX idx_categories_name, DROP INDEX idx_language_id, DROP INDEX idx_categories_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> categories_description</font><br>";
        if(mysql_query("ALTER TABLE configuration DROP INDEX idx_configuration_key, DROP INDEX idx_configuration_value, DROP INDEX idx_sort_order, DROP INDEX idx_date_added")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> configuration</font><br>";
        if(mysql_query("ALTER TABLE counter DROP INDEX idx_counter, DROP INDEX idx_startdate")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> counter</font><br>";
        if(mysql_query("ALTER TABLE countries DROP INDEX idx_countries_id, DROP INDEX idx_countries_name")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> countries</font><br>";
        if(mysql_query("ALTER TABLE customers DROP INDEX idx_customers_id, DROP INDEX idx_customers_email_address, DROP INDEX idx_customers_password, DROP INDEX idx_customers_firstname, DROP INDEX idx_customers_lastname")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> customers</font><br>";
        if(mysql_query("ALTER TABLE customers_info DROP INDEX idx_customers_info_id, DROP INDEX idx_customers_info_number_of_logons, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> customers_info</font><br>";
        if(mysql_query("ALTER TABLE geo_zones DROP INDEX idx_geo_zone_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> geo_zones</font><br>";
        if(mysql_query("ALTER TABLE languages DROP INDEX idx_languages_id, DROP INDEX idx_name, DROP INDEX idx_sort_order")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> languages</font><br>";
        if(mysql_query("ALTER TABLE manufacturers DROP INDEX idx_manufacturers_id, DROP INDEX idx_manufacturers_name")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> manufacturers</font><br>";
        if(mysql_query("ALTER TABLE manufacturers_info DROP INDEX idx_manufacturers_id, DROP INDEX idx_languages_id, DROP INDEX idx_date_last_click, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> manufacturers_info</font><br>";
        if(mysql_query("ALTER TABLE orders DROP INDEX idx_orders_id, DROP INDEX idx_customers_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders</font><br>";
        if(mysql_query("ALTER TABLE orders_products DROP INDEX idx_orders_products_id, DROP INDEX idx_orders_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products</font><br>";
        if(mysql_query("ALTER TABLE orders_products_attributes DROP INDEX idx_orders_products_attributes_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products_attributes</font><br>";
        if(mysql_query("ALTER TABLE orders_products_download DROP INDEX idx_orders_products_download_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products_download</font><br>";
        if(mysql_query("ALTER TABLE orders_status DROP INDEX idx_orders_status_id, DROP INDEX idx_language_id, DROP INDEX idx_orders_status_name, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_status</font><br>";
        if(mysql_query("ALTER TABLE orders_status_history DROP INDEX idx_orders_status_history_id, DROP INDEX idx_orders_id, DROP INDEX idx_date_added")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_status_history</font><br>";
        if(mysql_query("ALTER TABLE orders_total DROP INDEX idx_orders_total_id, DROP INDEX idx_orders_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_total</font><br>";
        if(mysql_query("ALTER TABLE products DROP INDEX idx_products_id, DROP INDEX idx_products_model, DROP INDEX idx_products_image, DROP INDEX idx_products_price, DROP INDEX idx_products_status, DROP INDEX idx_manufacturers_id, DROP INDEX idx_products_ordered")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products</font><br>";
        if(mysql_query("ALTER TABLE products_attributes DROP INDEX idx_products_attributes_id, DROP INDEX idx_products_id, DROP INDEX idx_options_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_attributes</font><br>";
        if(mysql_query("ALTER TABLE products_attributes_download DROP INDEX idx_products_attributes_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_attributes_download</font><br>";
        if(mysql_query("ALTER TABLE products_description DROP INDEX idx_products_id, DROP INDEX idx_language_id, DROP INDEX idx_products_name")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_description</font><br>";
        if(mysql_query("ALTER TABLE products_notifications DROP INDEX idx_products_id, DROP INDEX idx_customers_id, DROP INDEX idx_date_added, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_notifications</font><br>";
        if(mysql_query("ALTER TABLE products_options DROP INDEX idx_products_options_id, DROP INDEX idx_language_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_options</font><br>";
        if(mysql_query("ALTER TABLE products_options_values DROP INDEX idx_products_options_values_id, DROP INDEX idx_language_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_options_values</font><br>";
        if(mysql_query("ALTER TABLE products_to_categories DROP INDEX idx_products_id, DROP INDEX idx_categories_id, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_to_categories</font><br>";
        if(mysql_query("ALTER TABLE sessions DROP INDEX idx_sesskey, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> sessions</font><br>";
        if(mysql_query("ALTER TABLE tax_class DROP INDEX idx_tax_class_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> tax_class</font><br>";
        if(mysql_query("ALTER TABLE tax_rates DROP INDEX idx_tax_rates_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> tax_rates</font><br>";
        if(mysql_query("ALTER TABLE whos_online DROP INDEX idx_customer_id, DROP INDEX idx_session_id, DROP INDEX idx_ip_address, DROP PRIMARY KEY")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> whos_online</font><br>";
        if(mysql_query("ALTER TABLE zones DROP INDEX idx_zone_id, DROP INDEX idx_zone_country_id, DROP INDEX idx_zone_name, DROP INDEX idx_zone_code")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> zones</font><br>";
        if(mysql_query("ALTER TABLE zones_to_geo_zones DROP INDEX idx_association_id")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> zones_to_geo_zones</font><br>";
        ?>
        </td>
        <td class="smallText">
        <?php
        //Re-create all indexes and primary keys 
        if(mysql_query("ALTER TABLE address_book ADD INDEX idx_address_book_id (address_book_id), ADD INDEX idx_customers_id (customers_id), ADD PRIMARY KEY (address_book_id,customers_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> address_book</font><br>";
        if(mysql_query("ALTER TABLE categories ADD INDEX idx_parent_id (parent_id), ADD INDEX idx_sort_order (sort_order)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> categories</font><br>";
        if(mysql_query("ALTER TABLE categories_description ADD INDEX idx_categories_name (categories_name), ADD INDEX idx_language_id (language_id), ADD INDEX idx_categories_id (categories_id), ADD PRIMARY KEY (categories_id,language_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> categories_description</font><br>";
        if(mysql_query("ALTER TABLE configuration ADD INDEX idx_configuration_key (configuration_key), ADD INDEX idx_configuration_value (configuration_value), ADD INDEX idx_sort_order (sort_order), ADD INDEX idx_date_added (date_added)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> configuration</font><br>";
        if(mysql_query("ALTER TABLE counter ADD INDEX idx_counter (counter), ADD INDEX idx_startdate (startdate)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> counter</font><br>";
        if(mysql_query("ALTER TABLE countries ADD INDEX idx_countries_id (countries_id), ADD INDEX idx_countries_name (countries_name)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> countries</font><br>";
        if(mysql_query("ALTER TABLE customers ADD INDEX idx_customers_id (customers_id), ADD INDEX idx_customers_email_address (customers_email_address), ADD INDEX idx_customers_password (customers_password), ADD INDEX idx_customers_firstname (customers_firstname), ADD INDEX idx_customers_lastname (customers_lastname)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> customers</font><br>";
        if(mysql_query("ALTER TABLE customers_info ADD INDEX idx_customers_info_id (customers_info_id), ADD INDEX idx_customers_info_number_of_logons (customers_info_number_of_logons), ADD PRIMARY KEY (customers_info_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> customers_info</font><br>";
        if(mysql_query("ALTER TABLE geo_zones ADD INDEX idx_geo_zone_id (geo_zone_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> geo_zones</font><br>";
        if(mysql_query("ALTER TABLE languages ADD INDEX idx_languages_id (languages_id), ADD INDEX idx_name (name), ADD INDEX idx_sort_order (sort_order)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> languages</font><br>";
        if(mysql_query("ALTER TABLE manufacturers ADD INDEX idx_manufacturers_id (manufacturers_id), ADD INDEX idx_manufacturers_name (manufacturers_name)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> manufacturers</font><br>";
        if(mysql_query("ALTER TABLE manufacturers_info ADD INDEX idx_manufacturers_id (manufacturers_id), ADD INDEX idx_languages_id (languages_id), ADD INDEX idx_date_last_click (date_last_click), ADD PRIMARY KEY (manufacturers_id,languages_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> manufacturers_info</font><br>";
        if(mysql_query("ALTER TABLE orders ADD INDEX idx_orders_id (orders_id), ADD INDEX idx_customers_id (customers_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders</font><br>";
        if(mysql_query("ALTER TABLE orders_products ADD INDEX idx_orders_products_id (orders_products_id), ADD INDEX idx_orders_id (orders_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products</font><br>";
        if(mysql_query("ALTER TABLE orders_products_attributes ADD INDEX idx_orders_products_attributes_id (orders_products_attributes_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products_attributes</font><br>";
        if(mysql_query("ALTER TABLE orders_products_download ADD INDEX idx_orders_products_download_id (orders_products_download_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_products_download</font><br>";
        if(mysql_query("ALTER TABLE orders_status ADD INDEX idx_orders_status_id (orders_status_id), ADD INDEX idx_language_id (language_id), ADD INDEX idx_orders_status_name (orders_status_name), ADD PRIMARY KEY (orders_status_id,language_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_status</font><br>";
        if(mysql_query("ALTER TABLE orders_status_history ADD INDEX idx_orders_status_history_id (orders_status_history_id), ADD INDEX idx_orders_id (orders_id), ADD INDEX idx_date_added (date_added)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_status_history</font><br>";
        if(mysql_query("ALTER TABLE orders_total ADD INDEX idx_orders_total_id (orders_total_id), ADD INDEX idx_orders_id (orders_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> orders_total</font><br>";
        if(mysql_query("ALTER TABLE products ADD INDEX idx_products_id (products_id), ADD INDEX idx_products_model (products_model), ADD INDEX idx_products_image (products_image), ADD INDEX idx_products_price (products_price), ADD INDEX idx_products_status (products_status), ADD INDEX idx_manufacturers_id (manufacturers_id), ADD INDEX idx_products_ordered (products_ordered)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products</font><br>";
        if(mysql_query("ALTER TABLE products_attributes ADD INDEX idx_products_attributes_id (products_attributes_id), ADD INDEX idx_products_id (products_id), ADD INDEX idx_options_id (options_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_attributes</font><br>";
        if(mysql_query("ALTER TABLE products_attributes_download ADD INDEX idx_products_attributes_id (products_attributes_id), ADD PRIMARY KEY (products_attributes_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_attributes_download</font><br>";
        if(mysql_query("ALTER TABLE products_description ADD INDEX idx_products_id (products_id), ADD INDEX idx_language_id (language_id), ADD INDEX idx_products_name (products_name)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_description</font><br>";
        if(mysql_query("ALTER TABLE products_notifications ADD INDEX idx_products_id (products_id), ADD INDEX idx_customers_id (customers_id), ADD INDEX idx_date_added (date_added), ADD PRIMARY KEY (products_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_notifications</font><br>";
        if(mysql_query("ALTER TABLE products_options ADD INDEX idx_products_options_id (products_options_id), ADD INDEX idx_language_id (language_id), ADD PRIMARY KEY (products_options_id,language_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_options</font><br>";
        if(mysql_query("ALTER TABLE products_options_values ADD INDEX idx_products_options_values_id  (products_options_values_id ), ADD INDEX idx_language_id (language_id), ADD PRIMARY KEY (products_options_values_id,language_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_options_values</font><br>";
        if(mysql_query("ALTER TABLE products_to_categories ADD INDEX idx_products_id (products_id), ADD INDEX idx_categories_id (categories_id), ADD PRIMARY KEY (products_id,categories_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> products_to_categories</font><br>";
        if(mysql_query("ALTER TABLE sessions ADD INDEX idx_sesskey  (sesskey), ADD PRIMARY KEY (sesskey)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> sessions</font><br>";
        if(mysql_query("ALTER TABLE tax_class ADD INDEX idx_tax_class_id (tax_class_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> tax_class</font><br>";
        if(mysql_query("ALTER TABLE tax_rates ADD INDEX idx_tax_rates_id (tax_rates_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> tax_rates</font><br>";
        if(mysql_query("ALTER TABLE whos_online ADD INDEX idx_customer_id (customer_id), ADD INDEX idx_session_id (session_id), ADD INDEX idx_ip_address (ip_address), ADD PRIMARY KEY (customer_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> whos_online</font><br>";
        if(mysql_query("ALTER TABLE zones ADD INDEX idx_zone_id (zone_id), ADD INDEX idx_zone_country_id  (zone_country_id), ADD INDEX idx_zone_name (zone_name), ADD INDEX idx_zone_code (zone_code)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> zones</font><br>";
        if(mysql_query("ALTER TABLE zones_to_geo_zones ADD INDEX idx_association_id (association_id)")){$status = '<font color="#009933">';}else{$status = '<font color="#FF0000">';}
        echo $status . "<b>Table:</b> zones_to_geo_zones</font><br>";
        ?>
        </td>
      </tr>
<?php }else{ ?>
      <tr>
        <td colspan="2" class="smallText"><b>Create indexes now:</b> <a href="<?php echo $PHP_SELF; ?>?create_indexes=yes">Click here</a>!</td>
      </tr>
<?php } ?>
    </table>
  </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
