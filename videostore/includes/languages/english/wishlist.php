<?php
/*
  $Id: wishlist.php,v 3.0  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_WISHLIST', 'My Wish List * be sure to login to your account to save this for the future use, otherwise it will not be saved');
define('HEADING_TITLE', 'My Wish List contains:');
define('HEADING_TITLE2', '\'s Wish List contains:');
define('BOX_TEXT_PRICE', 'Price');
define('BOX_TEXT_PRODUCT', 'Product Name');
define('BOX_TEXT_IMAGE', 'Image');
define('BOX_TEXT_SELECT', 'Select');

define('BOX_TEXT_VIEW', 'Show');
define('BOX_TEXT_HELP', 'Help');
define('BOX_WISHLIST_EMPTY', '0 items');
define('BOX_TEXT_NO_ITEMS', 'No products are in your Wishlist. <br /><br /><b><a href="' . tep_href_link(FILENAME_WISHLIST_HELP) . '"><u>Click here</u></a> for help on using your Wish List</b>');

define('TEXT_NAME', 'Name: ');
define('TEXT_EMAIL', 'Email: ');
define('TEXT_YOUR_NAME', 'Your Name: ');
define('TEXT_YOUR_EMAIL', 'Your Email: ');
define('TEXT_MESSAGE', 'Message: ');
define('TEXT_ITEM_IN_CART', 'Item in Cart');
define('TEXT_ITEM_NOT_AVAILABLE', 'Item no longer available');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> items on your wish list.)');
define('WISHLIST_EMAIL_TEXT', 'If you would like to email your wishlist to multiple friends or family, just enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people you want to email your wishlist link too.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all the emails you send.');
define('WISHLIST_EMAIL_TEXT_GUEST', 'If you would like to email your wishlist to multiple friends or family, please enter your name and email address.  Then enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people you want to email your wishlist link too.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all the emails you send.');
define('WISHLIST_EMAIL_SUBJECT', 'has sent you their wishlist from ' . STORE_NAME);  //Customers name will be automatically added to the beginning of this.
define('WISHLIST_SENT', 'Your wishlist has been sent.');
define('WISHLIST_EMAIL_LINK', '

$from_name\'s public wishlist is located here:
$link

Thank you,
' . STORE_NAME); //$from_name = Customers name  $link = public wishlist link

define('WISHLIST_EMAIL_GUEST', 'Thank you,
' . STORE_NAME);

define('ERROR_YOUR_NAME' , 'Please enter your Name.');
define('ERROR_YOUR_EMAIL' , 'Please enter your Email.');
define('ERROR_VALID_EMAIL' , 'Please enter a valid email address.');
define('ERROR_ONE_EMAIL' , 'You must include atleast one name and email.');
define('ERROR_ENTER_EMAIL' , 'Please enter an email address.');
define('ERROR_ENTER_NAME' , 'Please enter the email recipents name.');
define('ERROR_MESSAGE', 'Please include a brief message.');

/*** Begin Visual Verify Code ***/
define('VISUAL_VERIFY_CODE_TEXT_INTRO', 'If you are human, please type the security code you see below into the provided box.');
define('VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS', 'Enter security code here:');
define('VISUAL_VERIFY_CODE_BOX_IDENTIFIER', '<- Security Code<br>(refresh page to renew)');
/*** end Visual Verify Code ***/
?>
