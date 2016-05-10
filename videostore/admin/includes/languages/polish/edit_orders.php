<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  T�umaczenie: Mariusz Gawdzi�ski
  http://www.gawdzinski.pl
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Edytuj Zam�wienie');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'z');
define('HEADING_SUBTITLE', 'Wykonaj zmiany w zam�wieniu i naci�nij znajduj�cy si� poni�ej przycisk Aktualizuj.');
define('HEADING_TITLE_STATUS', 'Status');
define('ADDING_TITLE', 'Dodaj produkt do tego zam�wienia');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Uwaga: </span>Wybierz p�atno�� Kart� Kredytow� aby pokaza� dodatkowe opcje.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Uwaga: </span>Je�li edytujesz cen� powi�zan� atrybutami (cechami) produktu musisz przeliczy� now� cen� produktu samodzielnie.');
define('HINT_TOTALS', '<span style="color: red;">Uwaga: </span>Pole zawieraj�ce "0" zostanie usuni�te podczas aktualizacji (z wy��czeniem wysy�ki).');
define('HINT_PRESS_UPDATE', 'Przycisk "Aktualizuj" zachowa wykonane zmiany.');

define('TABLE_HEADING_COMMENTS', 'Komentarz');
define('TABLE_HEADING_STATUS', 'Nowy Status');
define('TABLE_HEADING_QUANTITY', 'Ilo��');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produkt');
define('TABLE_HEADING_TAX', 'Podatek %');
define('TABLE_HEADING_UNIT_PRICE', 'Cena (netto)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Cena (brutto)');
define('TABLE_HEADING_TOTAL_PRICE', 'Warto�� (netto)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Warto�� (brutto)');
define('TABLE_HEADING_TOTAL_MODULE', 'Ca�kowity Koszt');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Koszt');
define('TABLE_HEADING_DELETE', 'Usun��?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Stan Powiadomienia');
define('TABLE_HEADING_DATE_ADDED', 'Data Powiadomienia');

define('ENTRY_CUSTOMER_NAME', 'Imi� i Nazwisko');
define('ENTRY_CUSTOMER_COMPANY', 'Firma');
define('ENTRY_CUSTOMER_ADDRESS', 'Adres Klienta');
define('ENTRY_CUSTOMER_SUBURB', 'Suburb');
define('ENTRY_CUSTOMER_CITY', 'Miasto');
define('ENTRY_CUSTOMER_STATE', 'Wojew�dztwo');
define('ENTRY_CUSTOMER_POSTCODE', 'Kod Pocztowy');
define('ENTRY_CUSTOMER_COUNTRY', 'Kraj');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Adres');

define('ENTRY_SHIPPING_ADDRESS', 'Adres Dostawy');
define('ENTRY_BILLING_ADDRESS', 'Adres P�atnika');
define('ENTRY_PAYMENT_METHOD', 'Metoda P�atno�ci:');
define('ENTRY_CREDIT_CARD_TYPE', 'Typ Karty:');
define('ENTRY_CREDIT_CARD_OWNER', 'W�a�ciciel Karty:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numer Karty:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Data Wa�no�ci:');
define('ENTRY_SUB_TOTAL', 'Podsuma:');
define('ENTRY_TAX', 'Podatek:');
define('ENTRY_TOTAL', 'Razem:');
define('ENTRY_STATUS', 'Status Zam�wienia:');
define('ENTRY_NOTIFY_CUSTOMER', 'Powiadomi� Klienta:');
define('ENTRY_NOTIFY_COMMENTS', 'Wys�a� Komentarz:');

define('TEXT_NO_ORDER_HISTORY', 'Brak Zam�wie�');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Aktualizacja Statusu Zam�wienia');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numer Zam�wienia:');
define('EMAIL_TEXT_INVOICE_URL', 'Szczeg�y Zam�wienia pod adresem URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data Zam�wienia:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Dzi�kujemy bardzo za zakupy w naszym sklepie!' . "\n\n" . 'Status Twojego zam�wienia zosta� zaktualizowany' . "\n\n" . 'Nowy status: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'W razie jakichkolwiek pyta�, w�tpliwo�ci prosimy o kontakt z nami. Mo�esz to zrobi� odpowiadaj�c na ten e-mail lub dzwoni�c bezpo�rednio do naszego sklepu.' . "\n\n" . 'Pozdrawiamy i Zapraszamy Ponownie<br> ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Poni�ej znajduje si� komentarz do z�o�onego zam�wienia:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'B��D: Brak zam�wienia');
define('SUCCESS_ORDER_UPDATED', 'Wykonano: zam�wienie zosta�o zaktualizowane.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Wybierz produkt');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Wybierz cech�');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Produkt nie posiada dodatkowych cech, pomijamy ...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'sztuk tego produktu');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Dodaj');
define('ADDPRODUCT_TEXT_STEP', 'Krok');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Wybierz Katalog. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Wybierz Produkt. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Wybierz Cech�. ');

define('MENUE_TITLE_CUSTOMER', '1. Dane Klienta');
define('MENUE_TITLE_PAYMENT', '2. Metoda P�atno�ci');
define('MENUE_TITLE_ORDER', '3. Zam�wione Produkty');
define('MENUE_TITLE_TOTAL', '4. Zni�ki, Wysy�ka , Razem');
define('MENUE_TITLE_STATUS', '5. Status i Powiadomienia');
define('MENUE_TITLE_UPDATE', '6. Aktualizacja Danych');
?>