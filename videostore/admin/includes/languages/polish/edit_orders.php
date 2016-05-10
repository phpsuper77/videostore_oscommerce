<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  T³umaczenie: Mariusz Gawdziñski
  http://www.gawdzinski.pl
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Edytuj Zamówienie');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'z');
define('HEADING_SUBTITLE', 'Wykonaj zmiany w zamówieniu i naci¶nij znajduj±cy siê poni¿ej przycisk Aktualizuj.');
define('HEADING_TITLE_STATUS', 'Status');
define('ADDING_TITLE', 'Dodaj produkt do tego zamówienia');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Uwaga: </span>Wybierz p³atno¶æ Kart± Kredytow± aby pokazaæ dodatkowe opcje.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Uwaga: </span>Je¶li edytujesz cenê powi±zan± atrybutami (cechami) produktu musisz przeliczyæ now± cenê produktu samodzielnie.');
define('HINT_TOTALS', '<span style="color: red;">Uwaga: </span>Pole zawieraj±ce "0" zostanie usuniête podczas aktualizacji (z wy³±czeniem wysy³ki).');
define('HINT_PRESS_UPDATE', 'Przycisk "Aktualizuj" zachowa wykonane zmiany.');

define('TABLE_HEADING_COMMENTS', 'Komentarz');
define('TABLE_HEADING_STATUS', 'Nowy Status');
define('TABLE_HEADING_QUANTITY', 'Ilo¶æ');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produkt');
define('TABLE_HEADING_TAX', 'Podatek %');
define('TABLE_HEADING_UNIT_PRICE', 'Cena (netto)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Cena (brutto)');
define('TABLE_HEADING_TOTAL_PRICE', 'Warto¶æ (netto)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Warto¶æ (brutto)');
define('TABLE_HEADING_TOTAL_MODULE', 'Ca³kowity Koszt');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Koszt');
define('TABLE_HEADING_DELETE', 'Usun±æ?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Stan Powiadomienia');
define('TABLE_HEADING_DATE_ADDED', 'Data Powiadomienia');

define('ENTRY_CUSTOMER_NAME', 'Imiê i Nazwisko');
define('ENTRY_CUSTOMER_COMPANY', 'Firma');
define('ENTRY_CUSTOMER_ADDRESS', 'Adres Klienta');
define('ENTRY_CUSTOMER_SUBURB', 'Suburb');
define('ENTRY_CUSTOMER_CITY', 'Miasto');
define('ENTRY_CUSTOMER_STATE', 'Województwo');
define('ENTRY_CUSTOMER_POSTCODE', 'Kod Pocztowy');
define('ENTRY_CUSTOMER_COUNTRY', 'Kraj');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Adres');

define('ENTRY_SHIPPING_ADDRESS', 'Adres Dostawy');
define('ENTRY_BILLING_ADDRESS', 'Adres P³atnika');
define('ENTRY_PAYMENT_METHOD', 'Metoda P³atno¶ci:');
define('ENTRY_CREDIT_CARD_TYPE', 'Typ Karty:');
define('ENTRY_CREDIT_CARD_OWNER', 'W³a¶ciciel Karty:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numer Karty:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Data Wa¿no¶ci:');
define('ENTRY_SUB_TOTAL', 'Podsuma:');
define('ENTRY_TAX', 'Podatek:');
define('ENTRY_TOTAL', 'Razem:');
define('ENTRY_STATUS', 'Status Zamówienia:');
define('ENTRY_NOTIFY_CUSTOMER', 'Powiadomiæ Klienta:');
define('ENTRY_NOTIFY_COMMENTS', 'Wys³aæ Komentarz:');

define('TEXT_NO_ORDER_HISTORY', 'Brak Zamówieñ');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Aktualizacja Statusu Zamówienia');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numer Zamówienia:');
define('EMAIL_TEXT_INVOICE_URL', 'Szczegó³y Zamówienia pod adresem URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data Zamówienia:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Dziêkujemy bardzo za zakupy w naszym sklepie!' . "\n\n" . 'Status Twojego zamówienia zosta³ zaktualizowany' . "\n\n" . 'Nowy status: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'W razie jakichkolwiek pytañ, w±tpliwo¶ci prosimy o kontakt z nami. Mo¿esz to zrobiæ odpowiadaj±c na ten e-mail lub dzwoni±c bezpo¶rednio do naszego sklepu.' . "\n\n" . 'Pozdrawiamy i Zapraszamy Ponownie<br> ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Poni¿ej znajduje siê komentarz do z³o¿onego zamówienia:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'B£¡D: Brak zamówienia');
define('SUCCESS_ORDER_UPDATED', 'Wykonano: zamówienie zosta³o zaktualizowane.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Wybierz produkt');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Wybierz cechê');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Produkt nie posiada dodatkowych cech, pomijamy ...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'sztuk tego produktu');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Dodaj');
define('ADDPRODUCT_TEXT_STEP', 'Krok');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Wybierz Katalog. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Wybierz Produkt. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Wybierz Cechê. ');

define('MENUE_TITLE_CUSTOMER', '1. Dane Klienta');
define('MENUE_TITLE_PAYMENT', '2. Metoda P³atno¶ci');
define('MENUE_TITLE_ORDER', '3. Zamówione Produkty');
define('MENUE_TITLE_TOTAL', '4. Zni¿ki, Wysy³ka , Razem');
define('MENUE_TITLE_STATUS', '5. Status i Powiadomienia');
define('MENUE_TITLE_UPDATE', '6. Aktualizacja Danych');
?>