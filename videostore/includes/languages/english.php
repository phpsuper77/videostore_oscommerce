<?php

/*

  $Id: english.php,v 1.114 2003/07/09 18:13:39 dgw_ Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



// look in your $PATH_LOCALE/locale directory for available locales

// or type locale -a on the server.

// Examples:

// on RedHat try 'en_US'

// on FreeBSD try 'en_US.ISO_8859-1'

// on Windows try 'en', or 'English'

@setlocale(LC_TIME, 'en_US.ISO_8859-1');



define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()

define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()

define('DATE_FORMAT', 'm/d/Y'); // this is used for date()

define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

define('PHP_DATE_TIME_FORMAT', 'DATE_TIME_FORMAT');

////

// Return date in raw format

// $date should be in format mm/dd/yyyy

// raw date is in format YYYYMMDD, or DDMMYYYY

function tep_date_raw($date, $reverse = false) {

  if ($reverse) {

    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);

  } else {

    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);

  }

}



// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)

define('LANGUAGE_CURRENCY', 'USD');



// Global entries for the <html> tag

define('HTML_PARAMS','dir="LTR" lang="en"');



// charset for web pages and emails

define('CHARSET', 'iso-8859-1');



// page title

define('TITLE', 'Travel Video Store.com'.'-');



// header text in includes/header.php

define('HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');

define('HEADER_TITLE_MY_ACCOUNT', 'My Account');

define('HEADER_TITLE_CART_CONTENTS', 'Shopping Cart');

define('HEADER_TITLE_CHECKOUT', 'Checkout');

define('HEADER_TITLE_TOP', '');

define('HEADER_TITLE_CATALOG', 'Home');

define('HEADER_TITLE_LOGOFF', 'Log Off');

define('HEADER_TITLE_LOGIN', 'Log In');



// footer text in includes/footer.php

define('FOOTER_TEXT_REQUESTS_SINCE', 'requests since');
define('FOOTER_TEXT_QUERIES_COUNT', 'Queries count:'); 



// text for gender

define('MALE', 'Male');

define('FEMALE', 'Female');

define('MALE_ADDRESS', 'Mr.');

define('FEMALE_ADDRESS', 'Ms.');



// text for date of birth example

define('DOB_FORMAT_STRING', 'mm/dd/yyyy');



// categories box text in includes/boxes/categories.php

define('BOX_HEADING_CATEGORIES', 'Categories');

// Discount_coupon box text in includes/boxes/discount_coupon.php
define('BOX_HEADING_DISCOUNT_COUPON', 'Discount Coupon');


// manufacturers box text in includes/boxes/manufacturers.php

define('BOX_HEADING_MANUFACTURERS', 'Manufacturers');

define('BOX_HEADING_DISTRIBUTORS', 'Videos by Distributors');

define('BOX_HEADING_PRODUCERS', 'Videos by Producers');

define('BOX_HEADING_SERIES', 'Videos by Series');

// whats_new box text in includes/boxes/whats_new.php

define('BOX_HEADING_WHATS_NEW', 'New Releases');



// quick_find box text in includes/boxes/quick_find.php

define('BOX_HEADING_SEARCH', 'Search');

define('BOX_SEARCH_TEXT', 'Use keywords to find the product you are looking for.');

define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');

define('TEXT_ALL_CATEGORIES', 'All Categories ');

define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');



// specials box text in includes/boxes/specials.php

define('BOX_HEADING_SPECIALS', 'Specials');



// Favourites box text in includes/boxes/add_favourites.php

define('BOX_INFORMATION_BOOKMARK', 'Add us to Your Favorites');

// VJ Links Manager v1.00 begin
define('BOX_INFORMATION_LINKS', 'Links');
// VJ Links Manager v1.00 end



// reviews box text in includes/boxes/reviews.php

define('BOX_HEADING_REVIEWS', 'Reviews');

define('BOX_REVIEWS_WRITE_REVIEW', 'Write a review on this product!');

define('BOX_REVIEWS_NO_REVIEWS', 'There are currently no product reviews');

define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 Stars!');



// shopping_cart box text in includes/boxes/shopping_cart.php

define('BOX_HEADING_SHOPPING_CART', 'Shopping Cart');

define('BOX_SHOPPING_CART_EMPTY', '0 items');

// wishlist box text in includes/boxes/wishlist.php
define('BOX_HEADING_CUSTOMER_WISHLIST', 'My Wishlist');
define('TEXT_WISHLIST_COUNT', 'Currently %s items are on your Wish List.');



// order_history box text in includes/boxes/order_history.php

define('BOX_HEADING_CUSTOMER_ORDERS', 'Order History');



// best_sellers box text in includes/boxes/best_sellers.php

define('BOX_HEADING_BESTSELLERS', 'Top 10 Bestsellers');

define('BOX_HEADING_BESTSELLERS_IN', 'Bestsellers in<br>&nbsp;&nbsp;');



// notifications box text in includes/boxes/products_notifications.php

define('BOX_HEADING_NOTIFICATIONS', 'Notifications');

define('BOX_NOTIFICATIONS_NOTIFY', 'Notify me of updates to <b>%s</b>');

define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Do not notify me of updates to <b>%s</b>');



// manufacturer box text

define('BOX_HEADING_MANUFACTURER_INFO', 'Producer Info');

define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');

define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Other products');



// languages box text in includes/boxes/languages.php

define('BOX_HEADING_LANGUAGES', 'Languages');



// currencies box text in includes/boxes/currencies.php

define('BOX_HEADING_CURRENCIES', 'Currencies');



// information box text in includes/boxes/information.php

define('BOX_HEADING_INFORMATION', 'Information');

define('BOX_INFORMATION_PRIVACY', 'Privacy Notice');

define('BOX_INFORMATION_CONDITIONS', 'Conditions of Use');

define('BOX_INFORMATION_SHIPPING', 'Shipping Information');

define('BOX_INFORMATION_CONTACT', 'Contact Us');

//BOF Dynamic Sitemap
 define('BOX_INFORMATION_DYNAMIC_SITEMAP', 'Site Map');
//EOF Dynamic Sitemap


// tell a friend box text in includes/boxes/tell_a_friend.php

define('BOX_HEADING_TELL_A_FRIEND', 'Tell A Friend');

define('BOX_TELL_A_FRIEND_TEXT', 'Tell someone you know about this product.');



// checkout procedure text

define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');

define('CHECKOUT_BAR_PAYMENT', 'Payment Information');

define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');

define('CHECKOUT_BAR_FINISHED', 'Finished!');



// pull down default text

define('PULL_DOWN_DEFAULT', 'Please Select');

define('TYPE_BELOW', 'Type Below');

define('PULL_DOWN_OTHER', 'Other - (please specifiy)'); //rmh referral



// Free Count Shipping Module

define('TEXT_ADD_TO_GET_FREE_SHIPPING', '<FONT COLOR="red">[add %s more item(s) for free worldwide shipping]</FONT>');

define('TEXT_FREE_SHIPPING_RECEIVED', '<FONT COLOR="blue">You have qualified for free worldwide shipping.</FONT>');

define('TEXT_FREE_SHIPPING_LIMIT', '<FONT COLOR="green">Orders with %s or more items receive FREE standard worldwide shipping</FONT>');

define('TEXT_FREE_SHIPPING_LIMIT_INTERNATIONAL', '<FONT COLOR="green">All orders having over %s items receive FREE standard worldwide shipping</FONT>');

define('TEXT_FREE_SHIPPING_LIMIT_NATIONAL', '<FONT COLOR="green">Orders with  %s or more items receive FREE standard worldwide shipping.</FONT>');

define('TEXT_FREE_SHIPPING_RECEIVED_INTERNATIONAL', '<FONT COLOR="blue">You have qualified for free worldwide shipping</FONT>');

define('TEXT_FREE_SHIPPING_RECEIVED_NATIONAL', '<FONT COLOR="blue">You have qualified for free worldwide shipping</FONT>');

// define('TEXT_FREE_SHIPPING_LIMIT', '<u>US Delivered</u> orders with %s or more items receive FREE standard shipping');

// define('TEXT_FREE_SHIPPING_LIMIT_INTERNATIONAL', 'All orders having over %s items receive FREE shipping outside of %s.');

// define('TEXT_FREE_SHIPPING_LIMIT_NATIONAL', '<u>US Delivered</u> orders with  %s or more items receive FREE Regular shipping.');

// define('TEXT_FREE_SHIPPING_RECEIVED_INTERNATIONAL', 'You have qualified for free shipping outside of %s.');

// define('TEXT_FREE_SHIPPING_RECEIVED_NATIONAL', 'You have qualified for free shipping within %s.');

// javascript messages

define('JS_ERROR', 'Errors have occured during the process of your form.  Please make the following corrections:  ');

define('JS_REVIEW_TEXT', '* The Review Text must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.n');

define('JS_REVIEW_RATING', '* You must rate the product for your review.n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.n');

define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');



define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');



define('CATEGORY_COMPANY', 'Company Details');

define('CATEGORY_PERSONAL', 'Your Personal Details');

define('CATEGORY_ADDRESS', 'Your Address');

define('CATEGORY_CONTACT', 'Your Contact Information');

define('CATEGORY_OPTIONS', 'Options');

define('CATEGORY_SOURCE', 'Referral Source'); //rmh referral

define('CATEGORY_PASSWORD', 'Your Password');



define('ENTRY_COMPANY', 'Company Name:');

define('ENTRY_COMPANY_ERROR', '');

define('ENTRY_COMPANY_TEXT', '');

define('ENTRY_GENDER', 'Gender:');

define('ENTRY_GENDER_ERROR', 'Please select your Gender.');

define('ENTRY_GENDER_TEXT', '*');

define('ENTRY_FIRST_NAME', 'First Name:');

define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');

define('ENTRY_FIRST_NAME_TEXT', '*');

define('ENTRY_LAST_NAME', 'Last Name:');

define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');

define('ENTRY_LAST_NAME_TEXT', '*');

define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');

define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');

define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');

define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');

define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');

define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');

define('ENTRY_EMAIL_ADDRESS_TEXT', '*');

define('ENTRY_STREET_ADDRESS', 'Address Line 1:');

define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');

define('ENTRY_STREET_ADDRESS_TEXT', '*');

define('ENTRY_SUBURB', 'Address Line 2:');

define('ENTRY_SUBURB_ERROR', '');

define('ENTRY_SUBURB_TEXT', '');

define('ENTRY_POST_CODE', 'Postal Code:');

define('ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');

define('ENTRY_POST_CODE_TEXT', '*');

define('ENTRY_CITY', 'City:');

define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');

define('ENTRY_CITY_TEXT', '*');

define('ENTRY_STATE', 'State/Province:');

define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');

define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');

define('ENTRY_STATE_TEXT', '*');

define('ENTRY_COUNTRY', 'Country:');

define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');

define('ENTRY_COUNTRY_TEXT', '*');

define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');

define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');

define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');

define('ENTRY_FAX_NUMBER', 'Fax Number:');

define('ENTRY_FAX_NUMBER_ERROR', '');

define('ENTRY_FAX_NUMBER_TEXT', '');

define('ENTRY_NEWSLETTER', 'Newsletter:');

define('ENTRY_NEWSLETTER_TEXT', '');

define('ENTRY_NEWSLETTER_YES', 'Subscribed');

define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');

define('ENTRY_NEWSLETTER_ERROR', '');

define('ENTRY_PASSWORD', 'Password:');

define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');

define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');

define('ENTRY_PASSWORD_TEXT', '*');

define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');

define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');

define('ENTRY_PASSWORD_CURRENT', 'Current Password:');

define('ENTRY_PASSWORD_CURRENT_TEXT', '*');

define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');

define('ENTRY_PASSWORD_NEW', 'New Password:');

define('ENTRY_PASSWORD_NEW_TEXT', '*');

define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');

define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');

define('PASSWORD_HIDDEN', '--HIDDEN--');

//rmh referral begin
define('ENTRY_SOURCE', 'How did you hear about us:');
define('ENTRY_SOURCE_ERROR', 'Please select how you first heard about us.');
define('ENTRY_SOURCE_OTHER', '(if "Other" please specify)');
define('ENTRY_SOURCE_OTHER_ERROR', 'Please enter how you first heard about us.');
if (REFERRAL_REQUIRED == 'true') {
  define('ENTRY_SOURCE_TEXT', '*');
  define('ENTRY_SOURCE_OTHER_TEXT', '*');
} else {
  define('ENTRY_SOURCE_TEXT', '');
  define('ENTRY_SOURCE_OTHER_TEXT', '');
}
//rmh referral end



define('FORM_REQUIRED_INFORMATION', '* Required information');



// constants for use in tep_prev_next_display function

define('TEXT_RESULT_PAGE', 'Result Pages:');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');

define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> reviews)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new products)');

define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)');



define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');

define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');

define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');

define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');

define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');

define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');

define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');

define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');

define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');

define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');

define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');



define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');

define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');

define('IMAGE_BUTTON_BACK', 'Back');

define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');

define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');

define('IMAGE_BUTTON_CHECKOUT', 'Checkout');

define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');

define('IMAGE_BUTTON_CONTINUE', 'Continue');

define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');

define('IMAGE_BUTTON_DELETE', 'Delete');

define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');

define('IMAGE_BUTTON_HISTORY', 'Order History');

define('IMAGE_BUTTON_LOGIN', 'Sign In');

define('IMAGE_BUTTON_IN_CART', 'Add DVD to Shopping Cart - Purchase downloads/rentals via the download button - You can add multiple DVD\'s to your cart at one time by just checking the box next to each item and then pressing the add to cart button at the bottom of the pag.e');

define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');

define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');

define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');

define('IMAGE_BUTTON_REVIEWS', 'Reviews');

define('IMAGE_BUTTON_SEARCH', 'Search');

define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');

define('IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');

define('IMAGE_BUTTON_UPDATE', 'Update');

define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');

define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');



// categories box text in includes/boxes/ssl_provider.php

define('BOX_HEADING_SSL_PROVIDER_COMODO', 'Secured by Comodo');

define('BOX_HEADING_SSL_PROVIDER_GEOTRUST', 'Secured by GeoTrust');

define('BOX_HEADING_SSL_PROVIDER_VERISIGN', 'Secured by VeriSign');



define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');

define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');

define('SMALL_IMAGE_BUTTON_VIEW', 'View');



define('ICON_ARROW_RIGHT', 'more');

define('ICON_CART', 'In Cart');

define('ICON_ERROR', 'Error');

define('ICON_SUCCESS', 'Success');

define('ICON_WARNING', 'Warning');



define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?');

define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');

define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? <BR>Or would you prefer to <a href="%s"><u>create an account</u></a>?');



define('TEXT_SORT_PRODUCTS', 'Sort products ');

define('TEXT_DESCENDINGLY', 'descendingly');

define('TEXT_ASCENDINGLY', 'ascendingly');

define('TEXT_BY', ' by ');



define('TEXT_REVIEW_BY', 'by %s');

define('TEXT_REVIEW_WORD_COUNT', '%s words');

define('TEXT_REVIEW_RATING', 'Rating: %s [%s]');

define('TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');

define('TEXT_NO_REVIEWS', 'There are currently no product reviews.');



define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');



define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');



define('TEXT_REQUIRED', '<span class="errorText">Required</span>');



define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');

define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Please remove this directory for security reasons.');

define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');

define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');

define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');

define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');

define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.');



define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiration date entered for the credit card is invalid.  Please check the date and try again.');

define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid.  Please check the number and try again.');

//define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s  If that number is correct, we do not accept that type of credit card.  If it is wrong, please try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The credit card number that you entered was not valid, please review the number that you entered and make any necessary corrections.');

/*Begin Checkout Without Account images*/

define('IMAGE_BUTTON_CREATE_ACCOUNT', 'Create Account');

/*End Checkout WIthout Account images*/
// Who's online
define('BOX_HEADING_WHOS_ONLINE', 'Who\'s online?');
define('BOX_WHOS_ONLINE_THEREIS', 'There currently is');
define('BOX_WHOS_ONLINE_THEREARE', 'There currently are');
define('BOX_WHOS_ONLINE_GUEST', 'shopper');
define('BOX_WHOS_ONLINE_GUESTS', 'shoppers');
define('BOX_WHOS_ONLINE_AND', 'and');
define('BOX_WHOS_ONLINE_MEMBER', 'Logged in customer');
define('BOX_WHOS_ONLINE_MEMBERS', 'Logged in customers');
/*

  The following copyright announcement can only be

  appropriately modified or removed if the layout of

  the site theme has been modified to distinguish

  itself from the default osCommerce-copyrighted

  theme.



  For more information please read the following

  Frequently Asked Questions entry on the osCommerce

  support site:



  http://www.oscommerce.com/community.php/faq,26/q,50



  Please leave this comment intact together with the

  following copyright announcement.

*/

define('FOOTER_TEXT_BODY', '<table cellspacing="0" cellpadding="0">
  <tr>
    <td height="18" width="25%"><font size="3"><b><u>COMPANY INFO</u></b></font></td>
    <td width="25%"><font size="3"><b><u>INFORMATION</u></b></font></td>
    <td width="25%"><font size="3"><b><u>CUSTOMERS</u></b></font></td>
    <td width="25%"><font size="3"><b><u>GENERAL INFO</u></b></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=3" target="">About    Us</a></font></td>
    <td width="25%"><font size="2"><a href="../redeem.php" target="">Gift Card Redeem</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=4" target="">Educators</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=11" target="">Satisfaction    Guarantee</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=19" target="">Conditions    of Use</a></font></td>
    <td width="25%"><font size="2"><a href="../faq.php" target="">Frequently Ask    Questions&nbsp&nbsp&nbsp</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=5" target="">Libraries</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=40" target="">Quantity    Discounts</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=44" target="">Join    Our Team</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=33" target="">Payment    Methods</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=6" target="">Travel    Agents</a></font></td>
    <td width="25%"><font size="2"><a href="../events_calendar.php?view=all_events" target="">Travel Show Appearances</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=7" target="">Speakers    Bureau</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=10" target="">Shipping    Information</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=48" target="">Senior Centers</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=8" target="">Product Submissions</a></font></td>  
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../info_pages.php?pages_id=35" target="">Press    Room</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=32" target="">Free    Shipping Offer</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=49" target="">BookStores/Video Stores&nbsp&nbsp&nbsp</a></font></td>
<td width="25%"><font size="2"><a href="https://secure.travelvideostore.com/vendors/vendor_account.php" target="">Producer Login</a></font></td>
  </tr>
  <tr>
    <td height="14" width="25%"><font size="2"><a href="../contact_us.php" target="">Contact Us</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=9" target="">Purchase    Orders</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=50" target="">Gift Shops</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=53"  target="">Public Performance</a></font></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=51" target="">Catalog Companies</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td width="25%"></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=52" target="">Websites</a></font></td>
    <td></td>
  </tr>
  <tr>
    <td height="14"></td>
    <td></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=54" target="">Tour Bus Companies</a></font></td>
    <td></td>
  </tr>
   <tr>
    <td height="14"></td>
    <td></td>
    <td width="25%"><font size="2"><a href="../info_pages.php?pages_id=43" target="">Wholesale    - Distribution</a></font></td>
    <td></td>
  </tr>

</table><br>
<b><A href="contact_us.php">Contact Us</A> |  <A href="info_pages.php?pages_id=19">Conditions of Use</A> |  <A href="sitemap.php">Sitemap</A> |  <A href="info_pages.php?pages_id=18">Privacy Policy</A></b><BR>TravelVideoStore.com 5420 Boran Dr Tampa FL 33610<BR><b>Tollfree (800) 288-5123</b>   Direct 813-630-9778   <b>FAX 813-627-0334</b><BR>
<span style="display:inline-block; vertical-align:middle"> 
 Copyright &copy; 2003-2015 
<a href="http://www.dmca.com/Protection/Status.aspx?id=4b9105a3-8c22-46a4-a64c-c85fb982b842" title="DMCA"> <img src ="/images/dmca_protected_sml_120l.png"  align="middle" alt="DMCA.com" /></a>
</span> ');


// BOF: WebMakers.com Added: Banner Manager

$language = 'english';
require(DIR_WS_LANGUAGES . $language . '/' . 'banner_manager.php');

// EOF: WebMakers.com Added: Banner Manager

// +Country-State Selector
define ('DEFAULT_COUNTRY', '223');
// -Country-State Selector

define('ENTRY_STATE_TEXT', '* (Select country first)');
define('ENTRY_COUNTRY_TEXT', '* (Page will refresh when changed)');

require(DIR_WS_LANGUAGES . 'add_ccgvdc_english.php');  // ICW CREDIT CLASS Gift Voucher Addittion
define('BOX_INFORMATION_PRINT_CATALOG', 'Printable Catalog');

// box text in includes/boxes/cat_request.php
define('BOX_HEADING_CAT_REQUEST', 'Catalog Request');
define('BOX_INFO_CAT_REQUEST_TEXT', 'Click here to request a Free catalog!');
// FAQ SYSTEM 2.1

  define('BOX_INFORMATION_FAQ', 'FAQ');

// FAQ SYSTEM 2.1
define('BOX_HEADING_PAGES', 'Information');
// testimonials box text in includes/boxes/customer_testimonials.php
define('BOX_HEADING_CUSTOMER_TESTIMONIALS', 'Testimonials');
define('TABLE_HEADING_TESTIMONIALS_ID', 'ID');
define('TABLE_HEADING_TESTIMONIALS_NAME', 'Name');
define('TABLE_HEADING_TESTIMONIALS_URL', 'URL');
define('TABLE_HEADING_TESTIMONIALS_DESCRIPTION', 'Testimonial');

//SHIPPING INSURANCE
define('TEXT_SHIPPING_INSURANCE_TITLE', 'Shipping Insurance');
define('TEXT_SHIPPING_INSURANCE_CHOICE', 'Do You Want Shipping Insurance? ');
// ADD to CART MESSAGE TEXT
define('TEXT_ITEM_ADDED','Your item has been added to your shopping cart');
define('ENTRY_EMAIL_ADDRESS_WHAT', 'What is your e-mail address?');

define('ENTRY_PASSWORD_WHAT', 'Do you have a travelvideostore.com password?');

define('ENTRY_NEW_CUSTOMER', 'No, I am a new customer.');

define('ENTRY_EXISTS_CUSTOMER', 'Yes, I have a password:');

/*** Begin Visual Verify Code ***/
define('VISUAL_VERIFY_CODE_CHARACTER_POOL', 'abcdefghkmnpstwxyABCDEFGHJKMNPRSTWXY23456789FJWNVB63HDLAJAF');  //no zeros or O
define('VISUAL_VERIFY_CODE_CATEGORY', '<br>Anti-Spam Security Check (Case SEnSiTive)<br>');
define('VISUAL_VERIFY_CODE_ENTRY_ERROR', 'The security code you entered did not match the one displayed. Please try again.');
define('VISUAL_VERIFY_CODE_ENTRY_TEXT', '*');
define('ALREADY_PURCHASED', 'Previously purchased');
  /*** End Visual Verify Code ***/
// Credit Card 
define('MODULE_PAYMENT_CC_TEXT_TITLE', 'Credit Card');
  define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiration Date:');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', '  The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.');
  define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', '  The credit card number must be at least 14 digits.');
  define('MODULE_PAYMENT_CC_TEXT_ERROR', 'Credit Card Error!');

?>