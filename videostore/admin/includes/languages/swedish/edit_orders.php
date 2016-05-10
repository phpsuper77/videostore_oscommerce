<?php
/*
   $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
   swedish

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Edit Order');
define('HEADING_SUBTITLE', 'Editera din order och tryck sedan "Update" knappen nedanför.');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Lägg till en product på denna order');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Tips: </font>Ändra betalning till "Credit Card" för att se dessa fält.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Tips: </font>För att radera en produkt sätt antalet "0".');
define('HINT_TOTALS', '<span style="color: red;">Hint: </font>För att ge rabatt sätt in negativa tal.<br>Fält med "0" i värde annulleras när du uppdaterar ordern (ej: frakt).');
define('HINT_PRESS_UPDATE', 'Klicka på "Update" för att spara alla ändringar.');

define('TABLE_HEADING_COMMENTS', 'Kommentar');
define('TABLE_HEADING_CUSTOMERS', 'Kunder');
define('TABLE_HEADING_ORDER_TOTAL', 'Order total');
define('TABLE_HEADING_DATE_PURCHASED', 'Order datum');
define('TABLE_HEADING_STATUS', 'Ny Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Kvantitet');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel nr.');
define('TABLE_HEADING_PRODUCTS', 'Produkter');
define('TABLE_HEADING_TAX', 'Moms');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_UNIT_PRICE', 'Pris (excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Pris (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total (excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total Pris Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Tot');
define('TABLE_HEADING_DELETE', 'Radera?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kund meddelad');
define('TABLE_HEADING_DATE_ADDED', 'Skriv datum');

define('ENTRY_CUSTOMER', 'Kund general');
define('ENTRY_CUSTOMER_NAME', 'Namn');
define('ENTRY_CUSTOMER_COMPANY', 'Företag');
define('ENTRY_CUSTOMER_ADDRESS', 'Adress');
define('ENTRY_CUSTOMER_SUBURB', 'Förort');
define('ENTRY_CUSTOMER_CITY', 'Stad');
define('ENTRY_CUSTOMER_STATE', 'State');
define('ENTRY_CUSTOMER_POSTCODE', 'Postadress');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');

define('ENTRY_SHIPPING_ADDRESS', 'Leverans Adress');
define('ENTRY_BILLING_ADDRESS', 'Faktura Adress');
define('ENTRY_PAYMENT_METHOD', 'Betalningsmetod:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kort typ:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kort ägare:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kort nr:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Card Expires:');
define('ENTRY_SUB_TOTAL', 'Tot:');
define('ENTRY_TAX', 'Moms:');
define('ENTRY_SHIPPING', 'Leverans:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Order Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Meddela kund:');
define('ENTRY_NOTIFY_COMMENTS', 'Skicka kommentarer:');
define('ENTRY_PRINTABLE', 'Skriv faktura');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Radera Order');
define('TEXT_INFO_DELETE_INTRO', 'Skall denna order raderas?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Andra antal');
define('TEXT_DATE_ORDER_CREATED', 'skapad:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Sist uppdaterad:');
define('TEXT_DATE_ORDER_ADDNEW', 'Lägg till en ny produkt');
define('TEXT_INFO_PAYMENT_METHOD', 'Betalningsmetod:');

define('TEXT_ALL_ORDERS', 'Alla Order');
define('TEXT_NO_ORDER_HISTORY', 'Inga order funna');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Din order har uppdaterats');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order nummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detaljerad faktura:');
define('EMAIL_TEXT_DATE_ORDERED', 'Order datum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Statusen på din order har blitt uppdaterad.' . "\n\n" . 'New status: %s' . "\n\n" . 'If you have questions, please reply to this email.' . "\n\n" . 'Kind regards,' . "\n". 'Your Onlineshop-Team' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Kommentarer' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fel: finns ingen order med detta nummret.');
define('SUCCESS_ORDER_UPDATED', 'Klar: Ordern har blitt uppdaterad.');
define('WARNING_ORDER_NOT_UPDATED', 'Obs!: Inga ändringar har gjorts.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Välj en produkt');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Välj ett val');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Produkten har inga val..så skippa detta...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'pieces of this product');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Lägg till');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Välj en katalog. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Välj en produkt. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Välj ett val. ');

define('MENUE_TITLE_CUSTOMER', '1. Kund Data');
define('MENUE_TITLE_PAYMENT', '2. Betalningsmetod');
define('MENUE_TITLE_ORDER', '3. Beställda produkter');
define('MENUE_TITLE_TOTAL', '4. Rabattt, Frakt och Total');
define('MENUE_TITLE_STATUS', '5. status och meddelanden');
define('MENUE_TITLE_UPDATE', '6. Updatera Data');
?>
