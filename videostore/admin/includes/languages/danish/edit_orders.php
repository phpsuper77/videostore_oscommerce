<?php
/*
  $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
  danish
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License

  Translation by Webmaster Seekers - Emil J. Larsen
  Date: 3 June 2005
*/

define('HEADING_TITLE', 'Ændre ordre');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'of');
define('HEADING_SUBTITLE', 'Please edit all parts as desired and click on the "Update" button below.');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Tilføj produkt til ordre');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Hint: </font>Set payment to "Credit Card" to show some additional fields.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Hint: </font>To delete a product set its quantity to "0".<br />If you edit the price associated with a product attribute, you have to calculate the new item cost manually.');
define('HINT_TOTALS', '<span style="color: red;">Hint: </font>Feel free to give discounts by adding negative amounts to the list.<br />Fields with "0" values are deleted when updating the order (exception: shipping).');
define('HINT_PRESS_UPDATE', 'Please click on "Update" to save all changes.');

define('TABLE_HEADING_COMMENTS', 'Bemærkninger');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_QUANTITY', 'Stk.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produkter');
define('TABLE_HEADING_TAX', 'Moms');
define('TABLE_HEADING_UNIT_PRICE', 'Pris per enhed');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Price (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total pris');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total Price Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Amount'); 
define('TABLE_HEADING_DELETE', 'Slette?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde er underrettet');
define('TABLE_HEADING_DATE_ADDED', 'Tilføjet:');

define('ENTRY_CUSTOMER_NAME', 'Navn');
define('ENTRY_CUSTOMER_COMPANY', 'Firma');
define('ENTRY_CUSTOMER_ADDRESS', 'Adresse');
define('ENTRY_CUSTOMER_SUBURB', 'Suburb');
define('ENTRY_CUSTOMER_CITY', 'By');
define('ENTRY_CUSTOMER_STATE', 'Lands del');
define('ENTRY_CUSTOMER_POSTCODE', 'Post nr.');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');
define('ENTRY_CUSTOMER_PHONE', 'Phone');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Address');
 
define('ENTRY_SHIPPING_ADDRESS', 'Modtager adresse:');
define('ENTRY_BILLING_ADDRESS', 'Betalings adresse:');
define('ENTRY_PAYMENT_METHOD', 'Betallings metode:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kreditkort type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kreditkort ejer:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kreditkort nr.:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Kreditkort udløbsdato:');
define('ENTRY_SUB_TOTAL', 'Sub-totalt:');
define('ENTRY_TAX', 'Moms:');
define('ENTRY_TOTAL', 'totalt:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Underret kunde:');
define('ENTRY_NOTIFY_COMMENTS', 'Tilføj bemærkning:');

define('TEXT_NO_ORDER_HISTORY', 'Ingen baggrund for denne ordre');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Din ordre er blevet odateret');
define('EMAIL_TEXT_ORDER_NUMBER', 'Ordre ID:');
define('EMAIL_TEXT_INVOICE_URL', 'Faktura:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestillings dato:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Din ordre er blevet opdateret til følgene status' . "\n\n" . 'Ny status: %s' . "\n\n" . 'Besvar endelig denne e-mail hvis du har nogen spørgsmål.' . "\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Der er følgene bemærkning til dig:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fejl: Ordre findes ikke.');
define('SUCCESS_ORDER_UPDATED', 'Succes: Ordren er blevet opdateret');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Vælg produkt');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Vælg muligheder');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Choose a catalogue. ');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Ingen muligheder: skipped...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Stk.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Tilføj');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Choose a product. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Choose an option. ');

define('MENUE_TITLE_CUSTOMER', '1. Customer Data');
define('MENUE_TITLE_PAYMENT', '2. Payment Method');
define('MENUE_TITLE_ORDER', '3. Ordered Products');
define('MENUE_TITLE_TOTAL', '4. Discount, Shipping and Total');
define('MENUE_TITLE_STATUS', '5. Status and Notification');
define('MENUE_TITLE_UPDATE', '6. Update Data');

?>