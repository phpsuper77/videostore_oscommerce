<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Rendelés szerkesztése');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'Dátum:');
define('HEADING_SUBTITLE', 'A kívánt részek szerkesztése után nyomja meg a lenti frissítés gombot.');
define('HEADING_TITLE_STATUS', 'Státusz');
define('ADDING_TITLE', 'Termék hozzáadása a rendeléshez');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Tipp: </span>Állítsa hitelkártyára a fizetés módját a megfelelõ mezõk megjelenítéséhez.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Tipp: </span>Ha megváltoztatja a termék árát a rendszerben tárolthoz képest Önnek kell szerkesztenie az egységárat.');
define('HINT_TOTALS', '<span style="color: red;">Tipp: </span>"0" értékû mezõk törlõdnek a rendelés frissítésekor!  (kivétel: szállítás).');
define('HINT_PRESS_UPDATE', 'Kattintson a "Frissítés" gombra a változtatások elmentéséhez.');

define('TABLE_HEADING_COMMENTS', 'Megjegyzés');
define('TABLE_HEADING_STATUS', 'Új státusz');
define('TABLE_HEADING_QUANTITY', 'Db');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modell');
define('TABLE_HEADING_PRODUCTS', 'Termék');
define('TABLE_HEADING_TAX', 'Adó %');
define('TABLE_HEADING_UNIT_PRICE', 'Ár (nettó)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Ár (bruttó)');
define('TABLE_HEADING_TOTAL_PRICE', 'Összesen (nettó)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Összesen (bruttó)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total Price Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Összeg');
define('TABLE_HEADING_DELETE', 'Törlés?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Vásárló értesítve');
define('TABLE_HEADING_DATE_ADDED', 'Bevitel dátuma');

define('ENTRY_CUSTOMER_NAME', 'Név');
define('ENTRY_CUSTOMER_COMPANY', 'Cég');
define('ENTRY_CUSTOMER_ADDRESS', 'Vásárló címe');
define('ENTRY_CUSTOMER_SUBURB', 'Városrész');
define('ENTRY_CUSTOMER_CITY', 'Város');
define('ENTRY_CUSTOMER_STATE', 'Állam');
define('ENTRY_CUSTOMER_POSTCODE', 'Irányítószám');
define('ENTRY_CUSTOMER_COUNTRY', 'Ország');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Cím');

define('ENTRY_SHIPPING_ADDRESS', 'Szállítási cím');
define('ENTRY_BILLING_ADDRESS', 'Számlázási cím');
define('ENTRY_PAYMENT_METHOD', 'Fizetési mód:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kártya típusa:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kártyatulajdonos:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kártya száma:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Kártya lejárata:');
define('ENTRY_SUB_TOTAL', 'Részösszeg:');
define('ENTRY_TAX', 'Adó:');
define('ENTRY_TOTAL', 'Végösszeg:');
define('ENTRY_STATUS', 'Rendelés státusza:');
define('ENTRY_NOTIFY_CUSTOMER', 'Vásárló értesítése:');
define('ENTRY_NOTIFY_COMMENTS', 'Megjegyzés elküldése:');

define('TEXT_NO_ORDER_HISTORY', 'Nincs korábbi rendelés');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'A rendelés frissült.');
define('EMAIL_TEXT_ORDER_NUMBER', 'Rendelés száma:');
define('EMAIL_TEXT_INVOICE_URL', 'Részletes számla URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Rendelés dátuma:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Köszönjük rendelését!' . "\n\n" . 'Rendelésének státusza megváltozott.' . "\n\n" . 'Az új állapot: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'Amennyiben kérdése van, kérjüj válaszoljon erre az e-mail-re.' . "\n\n" . 'Üdvözlettel, ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Megjegyzés a rendeléshez:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Hiba: Nincs ilyen rendelés.');
define('SUCCESS_ORDER_UPDATED', 'Sikeres: a rendelés frissült.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Válasszon terméket');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Válasszon opciót');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'A terméknek nincs választható opciója, kihagyjuk...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'db ebbõl a termékbõl');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Hozzáad');
define('ADDPRODUCT_TEXT_STEP', 'Lépés');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Válasszon kategóriát. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Válasszon terméket. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Válasszon opciót. ');

define('MENUE_TITLE_CUSTOMER', '1. Vásárló adatai');
define('MENUE_TITLE_PAYMENT', '2. Fizetés módja');
define('MENUE_TITLE_ORDER', '3. Megrendelt termékek');
define('MENUE_TITLE_TOTAL', '4. Kedvezmény, szállítás, végösszeg');
define('MENUE_TITLE_STATUS', '5. Állapot, értesítés');
define('MENUE_TITLE_UPDATE', '6. Frissítés');
?>
