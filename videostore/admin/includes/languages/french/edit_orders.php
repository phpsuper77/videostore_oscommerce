<?php
/*
  $Id: edit_orders.php,v 2.0 2006/03/14 10:42:44 ams Exp $
  french
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Translated by Maverick

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE_NUMBER', 'N°');
define('HEADING_TITLE_DATE', 'en date du');
define('HEADING_SUBTITLE', 'Modifiez tous les champs souhait&eacute;s puis cliquez sur le bouton "Mise &agrave; jour" ci-dessous.');
define('HEADING_TITLE_STATUS', 'Statut');
define('HINT_UPDATE_TO_CC', '<span style="color: red;">Remarque: </span>Choisir comme paiement "Carte de Cr&eacute;dit" pour voir de nouveaux champs.');
define('HINT_DELETE_POSITION', '<span style="color: red;">Remarque: </span>Pour supprimer un produit, mettre sa quantit&eacute; &agrave; "0".<br />Si vous &eacute;ditez le prix associ&eacute; &agrave; un attribut de produit, vous devez calculer le nouveau co&ucirc;t de l\'article manuellement.');
define('HINT_TOTALS', '<span style="color: red;">Remarque: </span>N\'h&eacute;sitez pas &agrave; saisir des remises en ajoutant des montants négatifs &agrave; la liste.<br />Les champs dont la valeur est "0" seront effac&eacute;s lors de la mise &agrave; jour de la commande (&agrave; l\'exception des frais de transport).');
define('HEADING_TITLE', 'Modification de commande');
define('ADDING_TITLE', 'Ajouter un produit');
define('HINT_PRESS_UPDATE', 'Cliquez sur le bouton "Mise &agrave; jour" pour enregistrer toutes vos modifications.');

define('TABLE_HEADING_COMMENTS', 'Commentaires');
define('TABLE_HEADING_STATUS', 'Statut de la commande');
define('TABLE_HEADING_QUANTITY', 'Qté');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modèle');
define('TABLE_HEADING_PRODUCTS', 'Produits');
define('TABLE_HEADING_TAX', 'Taxes');
define('TABLE_HEADING_UNIT_PRICE', 'Prix unitaire HT');
define('TABLE_HEADING_TOTAL_PRICE', 'Prix total HT');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Prix TTC');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total TTC');
define('TABLE_HEADING_TOTAL_MODULE', 'R&eacute;capitulatif de la commande');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Montant');
define('TABLE_HEADING_DELETE', 'Effacement?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Client avisé');
define('TABLE_HEADING_DATE_ADDED', 'Date de modification');

define('ENTRY_CUSTOMER_NAME', 'Nom');
define('ENTRY_CUSTOMER_COMPANY', 'Société');
define('ENTRY_CUSTOMER_ADDRESS', 'Adresse');
define('ENTRY_CUSTOMER_SUBURB', 'Banlieue');
define('ENTRY_CUSTOMER_PHONE', 'T&eacute;l&eacute;phone');
define('ENTRY_CUSTOMER_CITY', 'Ville');
define('ENTRY_CUSTOMER_STATE', 'D&eacute;partement');
define('ENTRY_CUSTOMER_POSTCODE', 'Code postal');
define('ENTRY_CUSTOMER_COUNTRY', 'Pays');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Adresse');

define('ENTRY_SHIPPING_ADDRESS', 'Adresse de livraison:');
define('ENTRY_BILLING_ADDRESS', 'Adresse de facturation:');
define('ENTRY_PAYMENT_METHOD', 'Moyen de paiement:');
define('ENTRY_CREDIT_CARD_TYPE', 'Type de carte de crédit:');
define('ENTRY_CREDIT_CARD_OWNER', 'Proporiétaire de la carte:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numéro de la carte:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Expiration de la carte:');
define('ENTRY_SUB_TOTAL', 'Sous-Total:');
define('ENTRY_TAX', 'Taxes:');
define('ENTRY_SHIPPING', 'Envoi:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Statut:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifier le client:');
define('ENTRY_NOTIFY_COMMENTS', 'Commentaires:');


define('EMAIL_TEXT_STATUS_UPDATE2', 'Si vous avez des questions, n\'h&eacute;sitez pas &agrave; nous contacter.' . "\n\n" . 'Cordialement. Le Service Commercial ' . STORE_NAME . "\n");
define('TEXT_NO_ORDER_HISTORY', 'Pas d\'historique disponible');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Mise à jour de votre commande');
define('EMAIL_TEXT_ORDER_NUMBER', 'Commande n°:');
define('EMAIL_TEXT_INVOICE_URL', 'Facture détaillée:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date de commande:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Votre commande a été mise à jour au statut suivant.' . "\n\n" . 'Nouveau statut: %s' . "\n\n" . 'Répondez à cette email, dans le cas où vous désirez obtenir plus d\'informations.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Les commentaires pour votre commande sont' . "\n\n%s\n\n");
define('ERROR_ORDER_DOES_NOT_EXIST', 'Erreur: aucune commande existante');
define('SUCCESS_ORDER_UPDATED', 'Succès: la commande a bien été mise à jour.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Choisissez un article');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Choisissez une option');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_STEP', 'Etape n°');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Choisir une cat&eacute;gorie. ');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Pas d\'options: sauté..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Qté.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Ajouter maintenant');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Choisir un article. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Choisir une option. ');

define('MENUE_TITLE_CUSTOMER', '1. Informations Clients');
define('MENUE_TITLE_PAYMENT', '2. Moyen de paiement');
define('MENUE_TITLE_ORDER', '3. Produits Command&eacute;s');
define('MENUE_TITLE_TOTAL', '4. Remise, Frais de Port et Total');
define('MENUE_TITLE_STATUS', '5. Status et Notification');
define('MENUE_TITLE_UPDATE', '6. Mise &agrave; jour');

?>