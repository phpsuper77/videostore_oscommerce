<?php
/*
 * Dynamo Checkout by Dynamo Effects
 * Copyright 2008 - Dynamo Effects.  All Rights Reserved
 * http://www.dynamoeffects.com
 */
define('NAVBAR_TITLE', 'Checkout');
define('CHECKOUT_RETURNING_CUSTOMER', '<b>Are you already a customer? <a href="#checkout-login" class="checkout-lightbox-login">' . tep_image_button('button_login.gif', IMAGE_BUTTON_LOGIN) . '</a></b>');
define('PAGE_HEADING', 'Fast and Secure Checkout');
define('CHECKOUT_EMAIL_ADDRESS', 'Email Address:');
define('CHECKOUT_PASSWORD', 'Password:');
define('CHECKOUT_FIRST_NAME', 'First Name:');
define('CHECKOUT_LAST_NAME', 'Last Name:');
define('CHECKOUT_DOB', 'Birthdate:');
define('CHECKOUT_GENDER', 'Gender:');
define('CHECKOUT_STREET_ADDRESS', 'Address line 1:');
define('CHECKOUT_SUBURB', 'Address line 2: <i>(optional)</i>');
define('CHECKOUT_COUNTRY', 'Country:');
define('CHECKOUT_ZONE', 'State:');
define('CHECKOUT_ZIP', 'Post Code:');
define('CHECKOUT_CITY', 'City:');
define('CHECKOUT_TELEPHONE', 'Telephone:');
define('CHECKOUT_COMPANY', 'Company:');
define('CHECKOUT_FASTER_SHOPPING', 'For Faster Shopping');
define('CHECKOUT_LOGIN', 'Log In to Your Account');
define('CHECKOUT_CONFIRM_PASSWORD', 'Re-type Password:');
define('CHECKOUT_VERIFY_COUPON', '<b>Verifying coupon, please wait...</b>');
define('CHECKOUT_VERIFY_GIFT_CARD', '<b>Verifying gift card code, please wait...</b>');
define('CHECKOUT_COUPON_SUCCESS', 'Coupon successfully added!');           
define('CHECKOUT_COUPON_FAILURE', 'Coupon was not valid.');    
define('CHECKOUT_COUPON_REMOVE', '[Remove Coupon]');
define('CHECKOUT_GIFT_CARD_SUCCESS', 'Gift card balance was successfully added!');           
define('CHECKOUT_GIFT_CARD_FAILURE', 'Gift card code was not valid.');    
define('CHECKOUT_GIFT_CARD_REMOVE', '[Remove]');
define('CHECKOUT_TIP_NAME', '');
define('CHECKOUT_TIP_STREET_ADDRESS', '');
define('CHECKOUT_TIP_COUNTRY', '');
define('CHECKOUT_TIP_SUBURB', '(Suite, Apt #, etc)');
define('CHECKOUT_TIP_ZONE', '');
define('CHECKOUT_TIP_ZIP', '');
define('CHECKOUT_TIP_CITY', '');
define('CHECKOUT_TIP_DOB', '(eg. 05/21/1970)');
define('CHECKOUT_TIP_GENDER', '');
define('CHECKOUT_TIP_TELEPHONE', '<b>Tip:</b> Enter a daytime number where you can be reached if there are any questions regarding your order.');
define('CHECKOUT_TIP_COMPANY', '');
define('CHECKOUT_TIP_COMMENTS', '<i>This does not appear on your invoice or your Packing Slip.</i>');
define('CHECKOUT_TIP_SHIPPING', '<div class="shipping_quotes">Fill out your shipping address information above to calculate your shipping cost.</div>');
define('CHECKOUT_TIP_EMAIL', 'Email address is needed to send a receipt and tracking numbers');
define('CHECKOUT_TIP_PASSWORD', '');
define('CHECKOUT_PROBLEM_FOUND', 'A small problem has been found in your order!');
define('CHECKOUT_SHIPPING_SAME', '<b>Is your shipping address different?</b>');
define('CHECKOUT_ENTER_PASSWORD', 'Tip: Create a password below to view the status of your order 24/7.');
define('CHECKOUT_MADE_CHANGES', '<b>Did you make any changes above?</b>');
define('CHECKOUT_PROMO_CODE', '<b>If you have a Coupon or Gift Card code, Enter Below and press update to apply</b>');
define('CHECKOUT_GIFT_CARD', '<b>Gift Card Code:</b>');
define('CHEKCOUT_DOB_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');

define('CHECKOUT_INVOICE_SUBJECT_CUSTOMER', 'Confirmation of Order from ' . STORE_NAME);
define('CHECKOUT_INVOICE_SUBJECT_MERCHANT', 'Order Number: %d');

define('CHECKOUT_ITEM', 'Item #');
define('CHECKOUT_ITEM_NAME', 'Product Name');
define('CHECKOUT_QTY', 'Qty');
define('CHECKOUT_UNIT_PRICE', 'Unit Price');
define('CHECKOUT_TOTAL_PRICE', 'Total Price');
define('CHECKOUT_ERROR_SUBMITTED', 'You have already submitted your order and it is being processed.  Please wait until you have received your order confirmation.');
define('CHECKOUT_STEP_1', 'Step %d: Review Your Cart');
define('CHECKOUT_STEP_2', 'Step %d: Billing / Shipping Information');
define('CHECKOUT_STEP_3', 'Step %d: Select a Payment Method');
define('CHECKOUT_STEP_4', 'Step %d: Select a Shipping Method');
define('CHECKOUT_STEP_5', 'Step %d: Confirm Order!');
define('CHECKOUT_REMOVE', 'Remove Product');

define('CHECKOUT_HEADING_BILLING_ADDRESS', 'Billing Address');
define('CHECKOUT_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('CHECKOUT_HEADING_BILLING_SHIPPING_ADDRESS', 'Billing & Shipping Address');
define('CHECKOUT_HEADING_EMAIL', 'Email Address');
define('CHECKOUT_JAVASCRIPT_REQUIRED', '<h2>Javascript Not Detected!</h2>
Our Fast and Secure Checkout requires certain technologies that your browser either doesn\'t support or has disabled.  Follow the instructions below to enable JavaScript or <a href="' . tep_href_link('checkout_shipping.php', '', 'SSL') . '"><b>CLICK HERE</b></a> to use our standard checkout process.
<br /><br />
<div class="checkout-js-pane">
        <b>Internet Explorer (6.0)</b>
          <ol>
            <li>Select \'Tools\' from the top menu</li>
            <li>Choose \'Internet Options\'</li>
            <li>Click on the \'Security\' tab</li>
            <li>Click on \'Custom Level\'</li>
            <li>Scroll down until you see section labled \'Scripting\'</li>
            <li>Under \'Active Scripting\', select \'Enable\' and click OK</li>
          </ol>
        </li>
        <br />
        <b>Netscape Navigator (4.8)</b>
          <ol>
            <li>Select \'Edit\' from the top menu</li>
            <li>Choose \'Preferences\'</li>
            <li>Choose \'Advanced\'</li>
            <li>Choose \'Scripts & Plugins\'</li>
            <li>Select the \'Enable JavaScript\' checkbox and click OK</li>
          </ol>
        </li>
        <br />
        <b>Mozilla Firefox (1.0)</b>
          <ol>
            <li>Select \'Tools\' from the top menu</li>
            <li>Choose \'Options\'</li>
            <li>Choose \'Web Features\' from the left navigation</li>
            <li>Select the checkbox next to \'Enable JavaScript\' and click OK</li>
          </ol>
        </li>
        <br />
        <b>Opera (8.0+)</b>
          <ol>
            <li>Select \'Tools\' from the top menu</li>
            <li>Choose \'Preferences\'</li>
            <li>Click on the \'Advanced\' tab</li>
            <li>Click on \'Content\'</li>
            <li>Select the checkbox next to \'Enable JavaScript\' and click OK</li>
          </ol>
        </li>
        <br />
        <b>Apple Safari (1.0)</b>
          <ol>
            <li>Select \'Safari\' from the top menu</li>
            <li>Choose \'Preferences\'</li>
            <li>Choose \'Security\'</li>
            <li>Select the checkbox next to \'Enable JavaScript\'</li>
          </ol></div>');
          
define('CHECKOUT_REMOVE_PRODUCT', 'Are you sure you want to remove this product from your cart?');
          
define('CHECKOUT_BOX_HEADING_1', 'Safe & Secure');
define('CHECKOUT_BOX_HEADING_2', 'Ordering Information');

define('CHECKOUT_BOX_CONTENT_1', '<center><img src="' . CHECKOUT_WS_INCLUDES . 'images/site_seal.jpg" /></center>
                      <br />
                      This website is <b>secured by a high-grade 256-bit SSL certificate</b> that encrypts (scrambles) your information to ensure a <b>safe and secure</b> transaction.<br /><br />
                      All credit card information passes directly to our payment processor and is <u>never</u> stored by us.  Your <b>security</b> is our first priority.');
define('CHECKOUT_BOX_CONTENT_2', '<b>Order Errors:</b><br />
If an error is believed to have been found, we will call you or send you an e-mail to confirm the order.  <br /><br />
<b>Processing Time:</b><br />
Orders are generally processed and shipped within 1 business day. Orders are not processed on national holidays or weekends. Time of delivery will depend upon your chosen shipping method and your shipping location.<br /><br />
<b>Order Tracking:</b><br />
When an order has shipped, a shipping confirmation email will be provided so that you may watch the status of your order. You may also log in 24/7 to view your order status via our website.
');
define('CHECKOUT_COMMENTS', 'Special Instructions');
define('CHECKOUT_COMMENTS_SLIP', 'Comments to appear on your Packing Slip');
define('CHECKOUT_TIP_COMMENTS_SLIP', '<i>ie. Happy Birthday, Ship together, etc.</i>');
           
define('CHECKOUT_SHIPPING_ERROR', 'Please select a shipping method.');      
                      
define('EMAIL_ACCOUNT_CREATED', 'An account was automatically created for you to allow you to check the status of your order on our website 24/7.  Your login information is:' . "\n\n");
define('EMAIL_USERNAME', 'Username: ');
define('EMAIL_PASSWORD', 'Password: ');
define('CHECKOUT_EMAIL_ADDRESS_INVALID', 'The e-mail address that you entered is invalid.  Please check it and try again.');
define('CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS', 'The e-mail address that you entered already exists in our records,<br />but the password you provided did not match.<br /><br />If you have forgotten your password, <a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN) . '">click here</a> to have a new one sent to you.');
define('CHECKOUT_EMAIL_ADDRESS_ERROR_EXISTS_NO_ACCOUNT', 'The e-mail address that you entered already exists in our records.<br /><br />Please log into your account or if you have forgotten your password, <a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN) . '">click here</a> to have a new one sent to you.');
define('CHECKOUT_THANK_YOU_FOR_YOUR_BUSINESS', 'Thank you for your business, ');
define('CHECKOUT_PLEASE_WAIT', 'Please wait while your order is processed...');
define('CHECKOUT_PROCESSING_ORDER', 'Processing order...');

define('CHECKOUT_PROCESS_10', 'Loading order details...');
define('CHECKOUT_PROCESS_20', 'Verifying order information...');
define('CHECKOUT_PROCESS_30', 'Preparing the order...');
define('CHECKOUT_PROCESS_40', 'Calculating order totals...');
define('CHECKOUT_PROCESS_50', 'Processing payment method...');
define('CHECKOUT_PROCESS_60', 'Creating new user...');
define('CHECKOUT_PROCESS_70', 'Saving the order...');
define('CHECKOUT_PROCESS_80', 'Sending order emails...');
define('CHECKOUT_PROCESS_90', 'Logging you in...');
define('CHECKOUT_PROCESS_100', 'Order successfully completed!');
define('CHECKOUT_PROCESS_100_OFFSITE', 'Sending customer to payment processor...');

define('CHECKOUT_ERROR_PROCESSING_TITLE', 'Oops!  We had a problem processing your order.');
define('CHECKOUT_ERROR_PROCESSING_MSG', "Your order couldn\'t be successfully processed using our one page checkout, so you are now being forwarded to our standard checkout process to complete your order.<br /><br />Thank you for your patience and for shopping at " . str_replace("'", "\'", STORE_NAME) . '!');
define('CHECKOUT_EMAIL_LOGIN_INFORMATION', "You can log in 24/7 to view the status of your order and check out quicker!  Your login details are:\n\nUsername: %s\nPassword: %s");
define('CHECKOUT_PAYMENT_ERROR', 'Please select a payment method.');


/* Points and Rewards */
define('CHECKOUT_HEADING_REDEEM_SYSTEM', 'Shopping Points Redemption');
define('CHECKOUT_REDEEM_SYSTEM_START', 'You have a credit balance of %s.  Would you like to use this balance towards your order?');
define('CHECKOUT_REDEEM_SYSTEM_SPENDING', 'Yes, use the maximum number of %s points allowed.');
define('TEXT_REDEEM_SYSTEM_NOTE', '<font color="ff0000">Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method</font>');
define('CHECKOUT_REFERRAL_REFERRED', 'If you were referred to us by a friend,<br />please enter their email address here:');

/* Giftwrap Contribution */
define('CHECKOUT_GIFTWRAP_METHOD', 'Would you like to gift wrap this order?');

define('CHECKOUT_PAYMENT_DECLINED', 'Our payment processor has declined your payment.  Please try again using a different method of payment or contact us for assistancce.');

define('CHECKOUT_ACCEPT_TERMS', '<b>Yes, I accept the %sterms and conditions</a> of this website.</b>');

define('CHECKOUT_ACCEPT_TERMS_ERROR', 'You must agree to the terms and conditions of this website before continuing.');
define('CHECKOUT_PASSWORD_ERROR', 'Your password must be at least ' . CHECKOUT_PASSWORD_MIN_LENGTH . ' characters long.');
define('CHECKOUT_SUBMIT_FORM_AGAIN', 'It appears that you were not successfully forwarded to the payment processor\'s website, which means that payment for your order has not yet been received.\n\nWould you like to try again?');
define('CHECKOUT_SUBMIT_FORM_ERROR', 'The payment method that you chose is temporarily unavailable.  Please select another payment method and try again.');

define('CHECKOUT_HEADING_NEWSLETTER', 'Newsletter Settings');
define('CHECKOUT_NEWSLETTER', '<b>Yes, please sign me up for the newsletter!</b>');
?>