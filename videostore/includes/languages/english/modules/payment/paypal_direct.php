<?php
/*
paypal language settings
*/

    define('MODULE_PAYMENT_PAYPAL_DIRECT_TEXT_TITLE', 'Credit Card');
    //define('MODULE_PAYMENT_PAYPAL_DIRECT_TEXT_PUBLIC_TITLE', 'Credit or Debit Card (Processed securely by PayPal)');
    // changed per paypal
    //define('MODULE_PAYMENT_PAYPAL_DIRECT_TEXT_PUBLIC_TITLE', 'Credit or Debit Card');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_TEXT_PUBLIC_TITLE', '
    <table style="margin: -43px 0 0 25px;">
        <tr>
            <td>Credit or Debit Card</td>
            <td>
                <a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open(\'https://www.paypal.com/webapps/mpp/paypal-popup\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">
                    <img style="width: 170px; margin-left: 40px;" src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_SbyPP_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark"></a>
            </td>
        </tr>
    </table>');
    
    define('MODULE_PAYMENT_PAYPAL_DIRECT_TEXT_DESCRIPTION', '<b>Note: PayPal requires the PayPal Express Checkout payment module to be enabled if this module is activated.</b><br /><br /><img src="images/icon_popup.gif" border="0">&nbsp;<a href="https://www.paypal.com/mrb/pal=PS2X9Q773CKG4" target="_blank" style="text-decoration: underline; font-weight: bold;">Visit PayPal Website</a>&nbsp;<a href="javascript:toggleDivBlock(\'paypalDirectInfo\');">(info)</a><span id="paypalDirectInfo" style="display: none;"><br><i>Using the above link to signup at PayPal grants osCommerce a small financial bonus for referring a customer.</i></span>');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_OWNER', 'Card Owner:');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_TYPE', 'Card Type:');  
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_NUMBER', 'Card Number:');   
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_VALID_FROM', 'Card Valid From Date:');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_VALID_FROM_INFO', '(if available)');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_EXPIRES', 'Card Expiry Date:');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_CVC', 'Card Security Code (CVV2):');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_ISSUE_NUMBER', 'Card Issue Number:');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_CARD_ISSUE_NUMBER_INFO', '(for Maestro and Solo cards only)');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_ERROR_ALL_FIELDS_REQUIRED', 'Error: All payment information fields are required.');
    // 3d secure
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_TITLE', 'Cardinal Centinel');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_DESCRIPTION', 'Verified by Visa / MasterCard SecureCode Authentication<br><br>To find out more about the service and register, <a target="_blank" href="http://www.cardinalcommerce.com/html/frame_services.html"><font color="0000F0"><u>click here</u></font></a>.');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_ERROR', 'Credit Card Error!');
    define('MODULE_PAYMENT_PAYPAL_DIRECT_3D_AUTHENTICATION_ERROR', 'Authentication Failed - Your financial institution has indicated that it could not successfully authenticate this transaction. To protect against unauthorized use, this card cannot be used to complete your purchase. You may complete the purchase by selecting another form of payment.');

    define("CENTINEL_ERROR_CODE_8000", "8000");
    define("CENTINEL_ERROR_CODE_8000_DESC", "Protocol Not Recogonized, must be http:// or https://");
    define("CENTINEL_ERROR_CODE_8010", "8010");
    define("CENTINEL_ERROR_CODE_8010_DESC", "Unable to Communicate with MAPS Server");
    define("CENTINEL_ERROR_CODE_8020", "8020");
    define("CENTINEL_ERROR_CODE_8020_DESC", "Error Parsing XML Response");
    define("CENTINEL_ERROR_CODE_8030", "8030");
    define("CENTINEL_ERROR_CODE_8030_DESC", "Communication Timeout Encountered");
?>