<?php
/*
  $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
  german

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE', 'Bestellung bearbeiten');
define('HEADING_SUBTITLE', 'Bitte bearbeiten Sie alle Abschnitte wie gew&uuml;nscht und klicken Sie danach unten auf "Aktualisieren".');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Artikel zu Bestellung hinzuf&uuml;gen');
define('HEADING_TITLE_DATE', 'of');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Tipp: </font>Zahlungsart auf "Kreditkarte" setzen, um Kreditkarten-Eingabefelder anzuzeigen. (e.g. Credit Card Type, Owner, Number, Expires).');
define('HINT_DELETE_POSITION', '<span style="color: red;">Tipp: </font>Anzahl eines Artikels zur Löschung auf "0" setzen.');
define('HINT_TOTALS', '<span style="color: red;">Tipp: </font>Vergeben Sie Gutscheine oder Rabatte, indem Sie Minus-Betr&auml;ge in die Liste einf&uuml;gen.<br>Posten mit "0"-Betr&auml;gen werden beim Aktualisieren entfernt (Ausnahme: Versand).');
define('HINT_PRESS_UPDATE', 'Klicken Sie auf "Aktualisieren", um alle oben vorgenommenen &Auml;nderungen abzuspeichern.');

define('TABLE_HEADING_COMMENTS', 'Kommentar');
define('TABLE_HEADING_STATUS', 'Neuer Status');
define('TABLE_HEADING_QUANTITY', 'Anz.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel-Nr.');
define('TABLE_HEADING_PRODUCTS', 'Produkte');
define('TABLE_HEADING_TAX', 'Steuer');
define('TABLE_HEADING_UNIT_PRICE', 'Preis (exkl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Preis (inkl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total (exkl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (inkl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Totalpreis-Komponente');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Betrag');
define('TABLE_HEADING_DELETE', 'Löschung?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'Eintragsdatum');

define('ENTRY_CUSTOMER_NAME', 'Name');
define('ENTRY_CUSTOMER_COMPANY', 'Firma');
define('ENTRY_CUSTOMER_ADDRESS', 'Adresse');
define('ENTRY_CUSTOMER_SUBURB', 'Ort');
define('ENTRY_CUSTOMER_CITY', 'Stadt');
define('ENTRY_CUSTOMER_STATE', 'Kanton');
define('ENTRY_CUSTOMER_POSTCODE', 'PLZ');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Address');

//define('ENTRY_DELIVERY_TO', 'Lieferung an:');
define('ENTRY_SHIPPING_ADDRESS', 'Versand-Adresse');
define('ENTRY_BILLING_ADDRESS', 'Rechnungs-Adresse');
define('ENTRY_PAYMENT_METHOD', 'Zahlungsart:');
define('ENTRY_CREDIT_CARD_TYPE', 'Karten-Typ:');
define('ENTRY_CREDIT_CARD_OWNER', 'Inhaber:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Nummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'G&uuml;ltig bis:');
define('ENTRY_SUB_TOTAL', 'Zwischensumme:');
define('ENTRY_TAX', 'Steuer:');
define('ENTRY_TOTAL', 'Gesamt:');
//define('ENTRY_DATE_PURCHASED', 'Kaufdatum:');
define('ENTRY_STATUS', 'Status der Bestellung:');
define('ENTRY_NOTIFY_CUSTOMER', 'Kunde benachrichtigen:');
define('ENTRY_NOTIFY_COMMENTS', 'Kommentar mitsenden:');

define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellungen vorhanden');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at ' . STORE_NAME . "\n");
define('EMAIL_TEXT_SUBJECT', 'Ihre Bestellung bei BatterienOnline wurde aktualisiert');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Details zur Rechnung:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Der Status Ihrer Bestellung wurde aktualisiert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bei Fragen antworten Sie bitte auf diese eMail.' . "\n\n" . 'Mit freundlichen Grüssen,' . "\n". 'Ihr BatterienOnline-Team' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Anmerkungen zur Bestellung' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fehler: Bestellung existiert nicht.');
define('SUCCESS_ORDER_UPDATED', 'Fertig: Bestellung erfolgreich aktualisiert.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'W&auml;hle Artikel');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'W&auml;hle Optionen');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Artikel hat keine Optionen, wird &uuml;bersprungen...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'St&uuml;ck des Artikels');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Hinzuf&uuml;gen');
define('ADDPRODUCT_TEXT_STEP', 'Schritt');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; W&auml;hlen Sie einen Katalog. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; W&auml;hlen Sie einen Artikel. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; W&auml;hlen Sie eine Option. ');

define('MENUE_TITLE_CUSTOMER', '1. Kunden-Daten');
define('MENUE_TITLE_PAYMENT', '2. Zahlungsmodalit&auml;ten');
define('MENUE_TITLE_ORDER', '3. Bestellte Artikel');
define('MENUE_TITLE_TOTAL', '4. Rabatte, Lieferung und Total');
define('MENUE_TITLE_STATUS', '5. Status und Benachrichtigung');
define('MENUE_TITLE_UPDATE', '6. Daten aktualisieren');
?>