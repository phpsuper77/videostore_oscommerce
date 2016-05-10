<?php
/*
  $Id: edit_orders.php,v 2.1 2006/03/21 10:42:44 ams Exp $
   portugues version by apopularvendas@hotmail.com

traduzido para o Portugues(brasil) por Marcelo Eduardo

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Editar pedido ');
define('HEADING_TITLE_NUMBER', 'Nr. ');
define('HEADING_TITLE_DATE', ' of ');
define('HEADING_SUBTITLE', 'modifique os campos necess�rios e clique no bot�o atualizar ');
define('HEADING_TITLE_STATUS', ' Status ');
define('ADDING_TITLE', 'Adicionar produto a este pedido ');

define('HINT_UPDATE_TO_CC', '<span style="color: red;".');
define('HINT_DELETE_POSITION', '<span style="color: red;">Hint: </span>If you edit the price associated with a product attribute, you have to calculate the new item cost manually.');
define('HINT_TOTALS', '<span style="color: red;">Hint: </span>Os campos com valores "0" s�o suprimidos ao atualizar a ordem (exce��o:  Transporte) .');
define('HINT_PRESS_UPDATE', 'Clique em atualizar para salvar as altera��es');

define('TABLE_HEADING_COMMENTS', 'Coment�rios ');
define('TABLE_HEADING_STATUS', 'Novo Status');
define('TABLE_HEADING_QUANTITY', 'Qty');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Produto');
define('TABLE_HEADING_TAX', 'Tax %');
define('TABLE_HEADING_UNIT_PRICE', 'Pre�o (excl.)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Pre�o (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total (excl.)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl.)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Amount');
define('TABLE_HEADING_DELETE', 'Exclui?');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Notificar cliente');
define('TABLE_HEADING_DATE_ADDED', 'Entre data');

define('ENTRY_CUSTOMER_NAME', 'Nome');
define('ENTRY_CUSTOMER_COMPANY', 'Compania');
define('ENTRY_CUSTOMER_ADDRESS', 'Endere�o');
define('ENTRY_CUSTOMER_SUBURB', 'Bairro');
define('ENTRY_CUSTOMER_CITY', 'Cidade');
define('ENTRY_CUSTOMER_STATE', 'Estado');
define('ENTRY_CUSTOMER_POSTCODE', 'CEP');
define('ENTRY_CUSTOMER_COUNTRY', 'Pais');
define('ENTRY_CUSTOMER_PHONE', 'Telefone');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');
define('ENTRY_ADDRESS', 'Endere�o');

define('ENTRY_SHIPPING_ADDRESS', 'Endere�o de entrega');
define('ENTRY_BILLING_ADDRESS', 'Endere�o de cobran�a');
define('ENTRY_PAYMENT_METHOD', 'Forma de pagamento:');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo do cart�o');
define('ENTRY_CREDIT_CARD_OWNER', 'Propriet�rio Do Cart�o: ');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numero do Cart�o');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Data de validade');
define('ENTRY_SUB_TOTAL', 'Sub Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_STATUS', 'Estatus do pedido');
define('ENTRY_NOTIFY_CUSTOMER', 'Notificar cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'Enviar coment�rio:');

define('TEXT_NO_ORDER_HISTORY', 'Pedido n�o encontrado');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Seu pedido foi atualizado');
define('EMAIL_TEXT_ORDER_NUMBER', 'Pedido: ');
define('EMAIL_TEXT_INVOICE_URL', 'URL do pedido: ');
define('EMAIL_TEXT_DATE_ORDERED', 'Data: ');
define('EMAIL_TEXT_STATUS_UPDATE', 'Muito obrigado por comprar conosco!!! ' . "\n\n" . ' O estatus de seu pedido foi atualizado. ' . "\n\n" . 'Novo Estatus: %s' . "\n\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'Se voc� tem duvidas responda a este email.' . "\n\n" . 'Atenciosamente, <br>
Equipe de suporte da loja virtual: ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Este � o coment�rio sobre o seu pedido: ' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: No such order.');
define('SUCCESS_ORDER_UPDATED', 'Completo !!! Seu pedido foi atualizado.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Escolha um produto');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Escolha uma op��o');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'O produto n�o tem nenhuma op��o...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Op��es deste produto');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Adicionar');
define('ADDPRODUCT_TEXT_STEP', 'Step');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Escolha uma categoria. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Escolha um produto. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Escolha uma op��o ');

define('MENUE_TITLE_CUSTOMER', '1. Dados do Cliente');
define('MENUE_TITLE_PAYMENT', '2. Metodo de Pagamento');
define('MENUE_TITLE_ORDER', '3. Produtos Pedidos');
define('MENUE_TITLE_TOTAL', '4. Desconto, transporte e total ');
define('MENUE_TITLE_STATUS', '5. Status e Notifica��o ');
define('MENUE_TITLE_UPDATE', '6. Atualizar Dados');
?>