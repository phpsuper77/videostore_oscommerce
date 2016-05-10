<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Rendel�s szerkeszt�se');
define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE_DATE', 'D�tum:');
define('HEADING_SUBTITLE', 'A k�v�nt r�szek szerkeszt�se ut�n nyomja meg a lenti friss�t�s gombot.');
define('HEADING_TITLE_STATUS', 'St�tusz');
define('ADDING_TITLE', 'Term�k hozz�ad�sa a rendel�shez');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Tipp: </span>�ll�tsa hitelk�rty�ra a fizet�s m�dj�t a megfelel� mez�k megjelen�t�s�hez.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Tipp: </span>Ha megv�ltoztatja a term�k �r�t a rendszerben t�rolthoz k�pest �nnek kell szerkesztenie az egys�g�rat.');
define('HINT_TOTALS', '<span style="color: red;">Tipp: </span>"0" �rt�k� mez�k t�rl�dnek a rendel�s friss�t�sekor!  (kiv�tel: sz�ll�t�s).');
define('HINT_PRESS_UPDATE', 'Kattintson a "Friss�t�s" gombra a v�ltoztat�sok elment�s�hez.');

define('TABLE_HEADING_COMMENTS', 'Megjegyz�s');
define('TABLE_HEADING_STATUS', '�j st�tusz');
define('TABLE_HEADING_QUANTITY', 'Db');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modell');
define('TABLE_HEADING_PRODUCTS', 'Term�k');
define('TABLE_HEADING_TAX', 'Ad� %');
define('TABLE_HEADING_UNIT_PRICE', '�r (nett�)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', '�r (brutt�)');
define('TABLE_HEADING_TOTAL_PRICE', '�sszesen (nett�)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', '�sszesen (brutt�)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total Price Component');
define('TABLE_HEADING_TOTAL_AMOUNT', '�sszeg');
define('TABLE_HEADING_DELETE', 'T�rl�s?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'V�s�rl� �rtes�tve');
define('TABLE_HEADING_DATE_ADDED', 'Bevitel d�tuma');

define('ENTRY_CUSTOMER_NAME', 'N�v');
define('ENTRY_CUSTOMER_COMPANY', 'C�g');
define('ENTRY_CUSTOMER_ADDRESS', 'V�s�rl� c�me');
define('ENTRY_CUSTOMER_SUBURB', 'V�rosr�sz');
define('ENTRY_CUSTOMER_CITY', 'V�ros');
define('ENTRY_CUSTOMER_STATE', '�llam');
define('ENTRY_CUSTOMER_POSTCODE', 'Ir�ny�t�sz�m');
define('ENTRY_CUSTOMER_COUNTRY', 'Orsz�g');
define('ENTRY_CUSTOMER_PHONE', 'Telefon');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'C�m');

define('ENTRY_SHIPPING_ADDRESS', 'Sz�ll�t�si c�m');
define('ENTRY_BILLING_ADDRESS', 'Sz�ml�z�si c�m');
define('ENTRY_PAYMENT_METHOD', 'Fizet�si m�d:');
define('ENTRY_CREDIT_CARD_TYPE', 'K�rtya t�pusa:');
define('ENTRY_CREDIT_CARD_OWNER', 'K�rtyatulajdonos:');
define('ENTRY_CREDIT_CARD_NUMBER', 'K�rtya sz�ma:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'K�rtya lej�rata:');
define('ENTRY_SUB_TOTAL', 'R�sz�sszeg:');
define('ENTRY_TAX', 'Ad�:');
define('ENTRY_TOTAL', 'V�g�sszeg:');
define('ENTRY_STATUS', 'Rendel�s st�tusza:');
define('ENTRY_NOTIFY_CUSTOMER', 'V�s�rl� �rtes�t�se:');
define('ENTRY_NOTIFY_COMMENTS', 'Megjegyz�s elk�ld�se:');

define('TEXT_NO_ORDER_HISTORY', 'Nincs kor�bbi rendel�s');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'A rendel�s friss�lt.');
define('EMAIL_TEXT_ORDER_NUMBER', 'Rendel�s sz�ma:');
define('EMAIL_TEXT_INVOICE_URL', 'R�szletes sz�mla URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Rendel�s d�tuma:');
define('EMAIL_TEXT_STATUS_UPDATE', 'K�sz�nj�k rendel�s�t!' . "\n\n" . 'Rendel�s�nek st�tusza megv�ltozott.' . "\n\n" . 'Az �j �llapot: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'Amennyiben k�rd�se van, k�rj�j v�laszoljon erre az e-mail-re.' . "\n\n" . '�dv�zlettel, ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Megjegyz�s a rendel�shez:' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Hiba: Nincs ilyen rendel�s.');
define('SUCCESS_ORDER_UPDATED', 'Sikeres: a rendel�s friss�lt.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'V�lasszon term�ket');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'V�lasszon opci�t');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'A term�knek nincs v�laszthat� opci�ja, kihagyjuk...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'db ebb�l a term�kb�l');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Hozz�ad');
define('ADDPRODUCT_TEXT_STEP', 'L�p�s');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; V�lasszon kateg�ri�t. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; V�lasszon term�ket. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; V�lasszon opci�t. ');

define('MENUE_TITLE_CUSTOMER', '1. V�s�rl� adatai');
define('MENUE_TITLE_PAYMENT', '2. Fizet�s m�dja');
define('MENUE_TITLE_ORDER', '3. Megrendelt term�kek');
define('MENUE_TITLE_TOTAL', '4. Kedvezm�ny, sz�ll�t�s, v�g�sszeg');
define('MENUE_TITLE_STATUS', '5. �llapot, �rtes�t�s');
define('MENUE_TITLE_UPDATE', '6. Friss�t�s');
?>
