<?php
/*
  $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
  italian
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE_NUMBER', 'Nr.');
define('HEADING_TITLE', 'Modifica Ordine');
define('HEADING_SUBTITLE', 'Modifica tutte le sezioni come desiderato e clicca sul pulsante "Aggiorna" in basso.');
define('HEADING_TITLE_STATUS', 'Stato:');
define('ADDING_TITLE', 'Aggiungi un prodotto all\' ordine');
define('HEADING_TITLE_DATE', 'of');

define('HINT_UPDATE_TO_CC', '<span style="color: red;">Consiglio: </font>Aggiorna il metodo di pagamento come "Carta di Credito" per mostrare dei campi aggiuntivi.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Consiglio: </font>Per cancellare un prodotto aggiorna la quantità al valore "0".');
define('HINT_TOTALS', '<span style="color: red;">Consiglio: </font>Effettua uno sconto aggiungendo un importo con segno negativo alla lista.<br>I campi con il valore "0" verranno cancellati con l\'aggiornamento dell\'ordine(eccetto: spedizione).');
define('HINT_PRESS_UPDATE', 'Cliccare su "Aggiorna" per salvare tutti i cambiamenti apportati.');

define('TABLE_HEADING_COMMENTS', 'Note - Commenti');
define('TABLE_HEADING_STATUS', 'Nuovo Stato');
define('TABLE_HEADING_QUANTITY', 'Qta');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Mod. Prodotto');
define('TABLE_HEADING_PRODUCTS', 'Prodotti');
define('TABLE_HEADING_TAX', 'Tasse');
define('TABLE_HEADING_UNIT_PRICE', 'Prezzo (escl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Prezzo (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Totale (escl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Totale (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Prezzo Totale');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Importo');
define('TABLE_HEADING_DELETE', 'Cancellazione? ');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente notificato');
define('TABLE_HEADING_DATE_ADDED', 'Data aggiunta');

define('ENTRY_CUSTOMER_NAME', 'Nome');
define('ENTRY_CUSTOMER_COMPANY', 'Azienda');
define('ENTRY_CUSTOMER_ADDRESS', 'Indirizzo');
define('ENTRY_CUSTOMER_SUBURB', 'Frazione');
define('ENTRY_CUSTOMER_CITY', 'Città');
define('ENTRY_CUSTOMER_STATE', 'Stato');
define('ENTRY_CUSTOMER_POSTCODE', 'CAP');
define('ENTRY_CUSTOMER_COUNTRY', 'Nazione');
define('ENTRY_CUSTOMER_PHONE', 'Telefono');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Address');

define('ENTRY_SHIPPING_ADDRESS', 'Indirizzo di Spedizione');
define('ENTRY_BILLING_ADDRESS', 'Indirizzo di Fatturazione');
define('ENTRY_PAYMENT_METHOD', 'Metodo di Pagamento:');
define('ENTRY_CREDIT_CARD_TYPE', 'Typo Carta:');
define('ENTRY_CREDIT_CARD_OWNER', 'Intestatario Carta:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numero Carta:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Scadenza Carta:');
define('ENTRY_SUB_TOTAL', 'Sub Totale:');
define('ENTRY_TAX', 'Tasse:');
define('ENTRY_TOTAL', 'Totale:');
define('ENTRY_STATUS', 'Stato Ordine:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifica Cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'Invia Commenti:');

define('TEXT_NO_ORDER_HISTORY', 'No order found');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_STATUS_UPDATE2', 'If you have questions, please reply to this email.' . "\n\n" . 'With warm regards from your friends at ' . STORE_NAME . "\n");
define('EMAIL_TEXT_SUBJECT', 'Il tuo ordine è stato aggiornato');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numero ordine:');
define('EMAIL_TEXT_INVOICE_URL', 'Fattura dettagliata a questo URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data ordine:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Lo status del tuo ordine è cambiato.' . "\n\n" . 'Nuovo status: %s' . "\n\n" . 'Per qualsiasi informazione rispondi a questa email.' . "\n\n" . 'Cordiali saluti,' . "\n". 'Lo staff' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Commenti' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Errore: Nessun ordine.');
define('SUCCESS_ORDER_UPDATED', 'Completato: L\' ordine è stato completato correttamente.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Scegli un prodotto');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Scegli una opzione');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Il prodotto non ha opzioni quindi andiamo avanti...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'unità di questo prodotto');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Aggiungi');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Scegli un catalogo. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Scegli un prodotto. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Scegli una opzione. ');

define('MENUE_TITLE_CUSTOMER', '1. Dati Cliente');
define('MENUE_TITLE_PAYMENT', '2. Metodo di Pagamento');
define('MENUE_TITLE_ORDER', '3. Prodotti Ordinati');
define('MENUE_TITLE_TOTAL', '4. Sconti, Spedizione e Totale');
define('MENUE_TITLE_STATUS', '5. Status e Notifiche');
define('MENUE_TITLE_UPDATE', '6. Aggiorna i dati');
?>
