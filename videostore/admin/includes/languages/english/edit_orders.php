<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Edit Order');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'of');
define('HEADING_SUBTITLE', 'Please edit all parts as desired and click on the "Update" button below.');
define('HEADING_TITLE_STATUS', 'Status');
define('ADDING_TITLE', 'Add a product to this order');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Hint: </span>Set payment method to "Credit Card" to automatically show the other fields (e.g. Credit Card Type, Owner, Number, Expires).');
define('HINT_DELETE_POSITION', '<span style="color: red;">Hint: </span>If you edit the price associated with a product attribute, you have to calculate the new item cost manually.');
define('HINT_TOTALS', '<span style="color: red;">Hint: </span>Fields with "0" values are deleted when updating the order (exception: shipping).');
define('HINT_PRESS_UPDATE', 'Please click on "Update" to save all changes.');

define('TABLE_HEADING_COMMENTS', 'Comment');
define('TABLE_HEADING_STATUS', 'New Status');
define('TABLE_HEADING_QUANTITY', 'Qty');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product');
define('TABLE_HEADING_TAX', 'Tax %');
define('TABLE_HEADING_UNIT_PRICE', 'Price (excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Price (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total (excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Amount');
define('TABLE_HEADING_DELETE', 'Delete?');
define('TABLE_HEADING_SHIPPING_TAX', 'Shipping tax: ');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer notified');
define('TABLE_HEADING_DATE_ADDED', 'Entry date');

define('ENTRY_CREDIT_CARD', 'Credit Card');
define('ENTRY_CUSTOMER_NAME', 'Name');
define('ENTRY_CUSTOMER_COMPANY', 'Company');
define('ENTRY_CUSTOMER_ADDRESS', 'Customer Address');
define('ENTRY_CUSTOMER_SUBURB', 'Suburb');
define('ENTRY_CUSTOMER_CITY', 'City');
define('ENTRY_CUSTOMER_STATE', 'State');
define('ENTRY_CUSTOMER_POSTCODE', 'Postcode');
define('ENTRY_CUSTOMER_COUNTRY', 'Country');
define('ENTRY_CUSTOMER_PHONE', 'Phone');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Address');

//define('ENTRY_DELIVERY_TO', 'Delivery to:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address');
define('ENTRY_BILLING_ADDRESS', 'Billing Address');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_TOTAL', 'Total:');
//define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Order Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Send comments:');

define('TEXT_NO_ORDER_HISTORY', 'No order found');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Your order has been updated');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Order date:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Thank you so much for your order with us!' . "\n\n" . 'The status of your order has been updated.' . "\n\n" . 'New status: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at the ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Here are the comments for your order:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: No such order.');
define('SUCCESS_ORDER_UPDATED', 'Completed: Order has been successfully updated.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Choose a product');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Choose an option');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Product has no options, so skipping...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'pieces of this product');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Add');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Choose a catalogue. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Choose a product. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Choose an option. ');

define('MENUE_TITLE_CUSTOMER', '1. Customer Data');
define('MENUE_TITLE_PAYMENT', '2. Payment Method');
define('MENUE_TITLE_ORDER', '3. Ordered Products');
define('MENUE_TITLE_TOTAL', '4. Discount, Shipping and Total');
define('MENUE_TITLE_STATUS', '5. Status and Notification');
define('MENUE_TITLE_UPDATE', '7. Update Data');
?>