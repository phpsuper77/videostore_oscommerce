<?php
/*
  $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
  dutch
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Wijzig order');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'van');
define('HEADING_SUBTITLE', 'Wijzig de gegevens en klik op "Update" om de wijzigingen te bevestigen.');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Voeg een product toe aan de bestelling');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Hint: </span>Set payment to "Credit Card" to show some additional fields.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Hint: </font>Om een artikel te verwijderen zet het aantal op "0".');
define('HINT_TOTALS', '<span style="color: red;">Hint: </font>Geef korting door regels met negatieve aantallen toe te voegen aan de bestelling.<br>Velden met "0" worden verwijderd na het updaten van de order (behalve: verzendkosten).');
define('HINT_PRESS_UPDATE', 'Klik op "Update" om alle wijzigingen op te slaan.');

define('TABLE_HEADING_COMMENTS', 'Commentaar');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_QUANTITY', 'Aantal');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Producten');
define('TABLE_HEADING_TAX', 'BTW');
define('TABLE_HEADING_UNIT_PRICE', 'Prijs per stuk (excl. BTW)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Prijs per stuk (incl. BTW)');
define('TABLE_HEADING_TOTAL_PRICE', 'Totaal (excl. BTW');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Totaal (incl. BTW)');
define('TABLE_HEADING_TOTAL_MODULE', 'Totalisering'); 
define('TABLE_HEADING_TOTAL_AMOUNT', 'Bedrag');
define('TABLE_HEADING_DELETE', 'Wegvagen?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Klant geinformeerd');
define('TABLE_HEADING_DATE_ADDED', 'Datum toegevoegd');

define('ENTRY_CUSTOMER_NAME', 'Naam');
define('ENTRY_CUSTOMER_COMPANY', 'Bedrijf');
define('ENTRY_CUSTOMER_ADDRESS', 'Adres');
define('ENTRY_ADDRESS', 'Adres');
define('ENTRY_CUSTOMER_SUBURB', 'Wijk');
define('ENTRY_CUSTOMER_CITY', 'Stad');
define('ENTRY_CUSTOMER_STATE', 'Provincie');
define('ENTRY_CUSTOMER_POSTCODE', 'Postcode');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');
define('ENTRY_CUSTOMER_PHONE', 'Telefoonnummer');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mailadres');


define('ENTRY_SHIPPING_ADDRESS', 'Afleveradres:');
define('ENTRY_BILLING_ADDRESS', 'Factuuradres:');
define('ENTRY_PAYMENT_METHOD', 'Betalingswijzen:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Eigenaar:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Nummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Verval datum:');
define('ENTRY_SUB_TOTAL', 'Sub-Totaal:');
define('ENTRY_TAX', 'BTW:');
define('ENTRY_TOTAL', 'Totaal:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_NOTIFY_CUSTOMER', 'Informeer de klant:');
define('ENTRY_NOTIFY_COMMENTS', 'Voeg commentaar toe:');

define('TEXT_NO_ORDER_HISTORY', 'Geen bestelling historie beschikbaar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'bestelling update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellings nummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Gedetaileerde factuur:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestel datum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'De status van uw bestelling is gewijzigd.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Als u vragen heeft betreffende uw bestelling verzoeken wij een antwoord te sturen op deze e-mail.' . "\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'Als u nog vragen heeft, beantwoord dan deze email.' . "\n\n" . 'Met vriendelijke groet van ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Uw commentaar bij de bestelling is' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fout!: bestelling bestaat niet.');
define('SUCCESS_ORDER_UPDATED', 'Voltooid: Uw bestelling is succesvol gewijzigd.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Kies uw product');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Kies opties');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Geen opties: Overgeslagen..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Aantal');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Voeg toe');
define('ADDPRODUCT_TEXT_STEP', 'Stap');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Kies een categorie. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Kies een product. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Kies een optie. ');

define('MENUE_TITLE_CUSTOMER', '1. Klant gegevens');
define('MENUE_TITLE_PAYMENT', '2. Betaalmethode');
define('MENUE_TITLE_ORDER', '3. Bestelde producten');
define('MENUE_TITLE_TOTAL', '4. Korting, Verzending en Totaal'); 
define('MENUE_TITLE_STATUS', '5. Status en Notificatie');
define('MENUE_TITLE_UPDATE', '6. Update Order');
?>
