<?php
/**
 * paypal_base.php
 *
 *
 * @package    includes/modules/payment/paypal/paypal_base.php
 * @author     Rock Mutchler <rock@drivedev.com>
 * @copyright  2009
 * @version    $Id:
 */
class paypal_base {
    var $code, $title, $description, $enabled, $rp_enabled, $rp_profile, $fmf_enabled, $new_account_notify, $country_iso2, $secure_description, $secure_title, $secure_enabled, $secure_chargeBackProtection;
    /**
     * PAYPAL NVP API URL
     *
     * @var str
     */
    var $nvp_api_url = 'https://api-3t.paypal.com/nvp';
    /**
     * PAYPAL SANDBOX NVP API URL
     *
     * @var str
     */
    var $nvp_api_url_sandbox = 'https://api-3t.sandbox.paypal.com/nvp';
    /**
     * holds the current api version
     *
     * @var str
     */
    var $api_version = '60.0';
    /**
     * holds the location of the paypal log file
     *
     * @var str
     */
    var $log_file_location;
    /**
     * get the overrid address
     *
     * @return array | boolean (false)
     */
    function getOverrideAddress()
    {
        global $customer_id, $sendto;

        if (!empty($_GET['markflow']) && tep_session_is_registered('customer_id')) {
            // From now on for this user we will edit addresses on the
            // osc install, not by going to PayPal.
            tep_session_register('paypal_ec_markflow');
            $_SESSION['paypal_ec_markflow'] = 1;

            // find the users default address id
            if (!empty($sendto)) {
                $address_id = $sendto;
            } else {
                $default_address_id_query = tep_db_query('SELECT customers_default_address_id
                    FROM ' . TABLE_CUSTOMERS . '
                    WHERE customers_id = \'' . $customer_id . '\'');
                if (tep_db_num_rows($default_address_id_query) > 0) {
                    // grab the data
                    $default_address_id_arr = tep_db_fetch_array($default_address_id_query);
                    $address_id = $default_address_id_arr['customers_default_address_id'];
                } else {
                    // couldn't find an address.
                    return false;
                }
            }

            // now grab the address from the database and set it
            $address_query = tep_db_query('SELECT entry_firstname, entry_lastname,
                entry_street_address, entry_suburb, entry_city, entry_postcode,
                entry_country_id, entry_zone_id
                FROM ' . TABLE_ADDRESS_BOOK . '
                WHERE address_book_id = \'' . $address_id . '\' AND
                customers_id = \'' . $customer_id . '\'
                LIMIT 1');

            // see if we found a record, if not well we have nothing to override with
            if (tep_db_num_rows($address_query) > 0) {
                // grab the data
                $address_arr = tep_db_fetch_array($address_query);

                // get the state/prov code
                $state_query = tep_db_query('SELECT zone_code
                    FROM ' . TABLE_ZONES . '
                    WHERE zone_id = \'' . $address_arr['entry_zone_id'] . '\'');
                if (tep_db_num_rows($state_query) > 0) {
                    $state_code_arr = tep_db_fetch_array($state_query);
                } else {
                    $state_code_arr['zone_code'] = '';
                }
                $address_arr['zone_code'] = $state_code_arr['zone_code'];

                // get the country code
                // ISO 3166 standard country code
                $country_query = tep_db_query('SELECT countries_iso_code_2
                    FROM ' . TABLE_COUNTRIES . '
                    WHERE countries_id = \'' . $address_arr['entry_country_id'] . '\'');
                if (tep_db_num_rows($country_query) > 0) {
                    $country_code_arr = tep_db_fetch_array($country_query);
                } else {
                    // default to go old US
                    $country_code_arr['countries_iso_code_2'] = 'US';
                }
                $address_arr['countries_iso_code_2'] = $country_code_arr['countries_iso_code_2'];

                // return address data.
                return $address_arr;
            }
        }

        return false;
    }

    /**
     * This method attempts to match items in an address book, to avoid
     * duplicate entries to the addres book.  On a successfull match it
     * returns the address_book_id(int) on failure it returns false.
     *
     * @param int $customer_id
     * @param array $address_question_arr
     * @return int|boolean
     */
    function findMatchingAddressBookEntry($customer_id, $address_question_arr) {
        // first get the zone id's from the 2 digit iso codes
        // country first
        $country_query = tep_db_query('SELECT countries_id, address_format_id
            FROM ' . TABLE_COUNTRIES . '
            WHERE countries_name = \'' . $address_question_arr['country'] . '\'
            LIMIT 1');

        // see if we found a record, if not default to American format
        if (tep_db_num_rows($country_query) > 0) {
            $country = tep_db_fetch_array($country_query);
            // grab the country id and address format
            $country_id = $country['countries_id'];
            $address_format_id = $country['address_format_id'];
        } else {
            // default
            $country_id = '223';
            $address_format_id = '2'; //2 is the American format
        }

        // see if the country code has a state
        $country_zone_check_query = tep_db_query('SELECT zone_country_id
            FROM ' . TABLE_ZONES . '
            WHERE zone_country_id = \'' . $country_id . '\'
            LIMIT 1');
        if (tep_db_num_rows($country_zone_check_query) > 0) {
            $check_zone = true;
        } else {
            $check_zone = false;
        }

        // now try and find the zone_id (state/province code)
        // use the country id above
        if ($check_zone) {
            $zone_query = tep_db_query('SELECT zone_id
                FROM ' . TABLE_ZONES . '
                WHERE zone_country_id = \'' . $country_id . '\' AND
                zone_code = \'' . $address_question_arr['state'] . '\'
                LIMIT 1');
            if (tep_db_num_rows($zone_query) > 0) {
                // grab the id
                $zone = tep_db_fetch_array($zone_query);
                $zone_id = $zone['zone_id'];
            } else {
                $check_zone = false;
            }
        }

        // so how to match, hmmm, lets do a match on address subrb
        if ($check_zone) {
            $question_query = tep_db_query('SELECT address_book_id, entry_street_address, entry_suburb
                FROM ' . TABLE_ADDRESS_BOOK . '
                WHERE customers_id = \'' . $customer_id . '\' AND
                entry_country_id = \'' . $country_id . '\' AND
                entry_zone_id = \'' . $zone_id .'\'');
        } else {
            $question_query = tep_db_query('SELECT address_book_id, entry_street_address, entry_suburb
                FROM ' . TABLE_ADDRESS_BOOK . '
                WHERE customers_id = \'' . $customer_id . '\' AND
                entry_country_id = \'' . $country_id . '\'');
        }
        $num = tep_db_num_rows($question_query);
        if ($num > 0) {
            // the match
            $matchQuestion = str_replace("\n", '', $address_question_arr['street_address']);
            $matchQuestion = trim($matchQuestion);
            $matchQuestion = $matchQuestion . str_replace("\n", '', $address_question_arr['suburb']);
            $matchQuestion = str_replace("\t", '', $matchQuestion);
            $matchQuestion = trim($matchQuestion);
            $matchQuestion = strtolower($matchQuestion);
            $matchQuestion = str_replace(' ', '', $matchQuestion);

            // go through the data
            for ($i = 0; $i < $num; $i++) {
                $answers_arr = tep_db_fetch_array($question_query);
                // now the matching logic

                // first from the db
                $fromDb = '';
                $fromDb = str_replace("\n", '', $answers_arr['entry_street_address']);
                $fromDb = trim($fromDb);
                $fromDb = $fromDb . str_replace("\n", '', $answers_arr['entry_suburb']);
                $fromDb = str_replace("\t", '', $fromDb);
                $fromDb = trim($fromDb);
                $fromDb = strtolower($fromDb);
                $fromDb = str_replace(' ', '', $fromDb);

                // check the strings
                if (strlen($fromDb) == strlen($matchQuestion)) {
                    if ($fromDb == $matchQuestion) {
                        // exact match return the id
                        return $answers_arr['address_book_id'];
                    }
                } elseif (strlen($fromDb) > strlen($matchQuestion)) {
                    $fromDb = substr($fromDb, 0, strlen($matchQuestion));
                    if ($fromDb == $matchQuestion) {
                        // we have a match return it
                        return $answers_arr['address_book_id'];
                    }
                } else {
                    $matchQuestion = substr($matchQuestion, 0, strlen($fromDb));
                    if ($fromDb == $matchQuestion) {
                        // we have a match return it
                        return $answers_arr['address_book_id'];
                    }
                }
            }
        }

        // no matches found
        return false;
    }

    /**
     * This method adds an adress book entry to the database, this allows us to add addresses
     * to the system that we get back from paypal that are not in the system
     *
     * @param int $customer_id
     * @param array $address_question_arr
     * @return int
     */
    function addAddressBookEntry($customer_id, $address_question_arr, $make_default = false) {
        // first get the zone id's from the 2 digit iso codes
        // country first
        $country_query = tep_db_query('SELECT countries_id, address_format_id
            FROM ' . TABLE_COUNTRIES . '
            WHERE countries_name = \'' . $address_question_arr['country'] . '\'
            LIMIT 1');

        // see if we found a record, if not default to American format
        if (tep_db_num_rows($country_query) > 0) {
            $country = tep_db_fetch_array($country_query);
            // grab the country id and address format
            $country_id = $country['countries_id'];
            $address_format_id = $country['address_format_id'];
        } else {
            // default
            $country_id = '223';
            $address_format_id = '2'; //2 is the American format
        }

        // see if the country code has a state
        $country_zone_check_query = tep_db_query('SELECT zone_country_id
            FROM ' . TABLE_ZONES . '
            WHERE zone_country_id = \'' . $country_id . '\'
            LIMIT 1');
        if (tep_db_num_rows($country_zone_check_query) > 0) {
            $check_zone = true;
        } else {
            $check_zone = false;
        }

        // now try and find the zone_id (state/province code)
        // use the country id above
        if ($check_zone) {
            $zone_query = tep_db_query('SELECT zone_id
                FROM ' . TABLE_ZONES . '
                WHERE zone_country_id = \'' . $country_id . '\' AND
                zone_code = \'' . $address_question_arr['state'] . '\'
                LIMIT 1');
            if (tep_db_num_rows($zone_query) > 0) {
                // grab the id
                $zone = tep_db_fetch_array($zone_query);
                $zone_id = $zone['zone_id'];
            } else {
                $check_zone = false;
            }
        }

        // now run the insert
        // this isnt the best way but it will get the majority of cases
        list($fname, $lname) = explode(' ', $address_question_arr['name']);
        if ($check_zone) {
            tep_db_query('INSERT INTO ' . TABLE_ADDRESS_BOOK . '
                (address_book_id, customers_id, entry_gender, entry_firstname, entry_lastname,
                entry_street_address, entry_suburb, entry_postcode, entry_city, entry_country_id,
                entry_zone_id)
                VALUES
                (NULL, \'' . $customer_id . '\', \'\', \'' . $fname . '\', \'' . $lname . '\',
                \'' . $address_question_arr['street_address'] . '\', \'' . $address_question_arr['suburb'] . '\',
                \'' . $address_question_arr['postcode'] . '\', \'' . $address_question_arr['city'] . '\',
                \'' . $country_id . '\', \'' . $zone_id . '\')');
        } else {
            tep_db_query('INSERT INTO ' . TABLE_ADDRESS_BOOK . '
                (address_book_id, customers_id, entry_gender, entry_firstname, entry_lastname,
                entry_street_address, entry_suburb, entry_postcode, entry_city, entry_country_id)
                VALUES
                (NULL, \'' . $customer_id . '\', \'\', \'' . $fname . '\', \'' . $lname . '\',
                \'' . $address_question_arr['street_address'] . '\', \'' . $address_question_arr['suburb'] . '\',
                \'' . $address_question_arr['postcode'] . '\', \'' . $address_question_arr['city'] . '\',
                \'' . $country_id . '\')');
        }
        $address_book_id = tep_db_insert_id();

        // make default if set, update
        if ($make_default) {
            tep_db_query('UPDATE '. TABLE_CUSTOMERS . '
                SET customers_default_address_id = \'' . $address_book_id . '\'
                WHERE customers_id = \'' . $customer_id . '\'');
            $_SESSION['customer_default_address_id'] = $address_book_id;
        }

        // set the sendto
        $_SESSION['sendto'] = $address_book_id;

        // return the address_id
        return $address_book_id;
    }
    /**
     * Log a user in
     *
     * @param str $email_address
     * @param str $redirect
     * @return boolean
     */
    function user_login($email_address, $redirect = true) {
        global $order, $customer_id, $customer_default_address_id, $customer_first_name, $customer_country_id, $customer_zone_id;

        global $session_started, $language, $cart;
        if ($session_started == false) {
            tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
        }

        require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

        $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, customers_paypal_payerid from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
        $check_customer = tep_db_fetch_array($check_customer_query);

        if (!tep_db_num_rows($check_customer_query)) {
            $this->away_with_you(MODULE_PAYMENT_PAYPAL_DP_TEXT_BAD_LOGIN, true);
        }

        if (SESSION_RECREATE == 'True') {
            tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

        $order->customer['id'] = $customer_id;

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

        $cart->restore_contents();
        if ($redirect) {
            $this->away_with_you();
        }

        return true;
    }
    /**
     * remove a temp ec checkout user
     *
     * @param int $cid
     */
    function ec_delete_user($cid) {
      global $customer_id, $customers_default_address_id, $customer_first_name, $customer_country_id, $customer_zone_id, $comments;
      tep_session_unregister('customer_id');
      tep_session_unregister('customer_default_address_id');
      tep_session_unregister('customer_first_name');
      tep_session_unregister('customer_country_id');
      tep_session_unregister('customer_zone_id');
      tep_session_unregister('comments');

      tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$cid . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$cid . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$cid . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$cid . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$cid . "'");
      tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$cid . "'");
    }
    /**
     * reformats, and gets proper name by language for an FMF error from a response return
     * from paypal, after made into response array
     *
     * @param array $returnArr
     * @param int $lang_id
     * @return array | boolean (false on failure)
     */
    function getReturnFMFErrors($returnArr, $lang_id=1){
        if(count($returnArr)<1 or !is_numeric($lang_id)){
            return false;
        }
        $stillChk = true;
        $i=0;
        $errArr = array();
        while($stillChk){
            if(array_key_exists('L_FMFPENDINGID'.$i, $returnArr)){
                // FMF decline, flag, review
                $errArr[]['id']                                 = $returnArr['L_FMFPENDINGID'.$i];
                $errArr[count($errArr)-1]['fmf_status_name']    = $this->getFMFStatusName($returnArr['L_FMFPENDINGID'.$i], $lang_id);
                $errArr[count($errArr)-1]['fmf_status_desc']    = $returnArr['L_FMFPENDINGDESCRIPTION'.$i];
                // next record
                $i++;
            }else{
                $stillChk = false;
            }
        }
        if(count($errArr)==0){
            return false;
        }
        // return the array
        return $errArr;
    }
    /**
     * Grab the fmf status code name from the db by the fmf statusId by language as well
     *
     * @param int $fmfId
     * @param int $lang_id
     * @return str | boolean (false failure)
     */
    function getFMFStatusName($fmfId, $lang_id=1){
        if(!is_numeric($fmfId) or !is_numeric($lang_id)){
            return false;
        }
        $rs = tep_db_query('SELECT fmf_status_name FROM ' . TABLE_FMF_PAYPAL_STATUS . ' WHERE paypal_fmf_status_id = ' . $fmfId . ' AND language_id = ' . $lang_id . ' limit 1');
        if (tep_db_num_rows($rs) > 0){
            $row = tep_db_fetch_array($rs);
            return $row['fmf_status_name'];
        }
        // if where still here try land_id 1 if not tried
        if($lang_id != 1){
            $rs = tep_db_query('SELECT fmf_status_name FROM ' . TABLE_FMF_PAYPAL_STATUS . ' WHERE paypal_fmf_status_id = ' . $fmfId . ' AND language_id = 1 limit 1');
            if (tep_db_num_rows($rs) > 0){
                $row = tep_db_fetch_array($rs);
                return $row['fmf_status_name'];
            }
        }
        // still nada
        return false;
    }
    /**
     * This method simply writes an array out to a log file
     *
     * @param array $arr
     * @param enum (request, response) str
     */
    function recordToLog($arr, $type='request'){
        $id = time();
        $date = date('Y-m-d H:i:s');
        // create the str
        $str = $id . "\t" . $date . "\t" . $type . "\t";
        foreach ($arr as $key => $value){
            switch ($key){
                case 'CVV2':
                    $value = 'xxx';
                break;
                case 'ACCT':
                    $len = strlen($value) - 4;
                    $value = substr($value, 0, 4) . str_repeat('x', $len);
                break;
                case '3dRequest':
                    if(stristr($value, '<CardNumber>')){
                        list($before,) = split('<CardNumber>', $value);
                        list(,$after) = split('</CardNumber>', $value);
                        $value = $before .'<CardNumber>XXXX</CardNumber>'.$after;
                    }
                break;
                case '3dResponse':
                break;
            }
            $str .= $key . '::' . $value . "\t";
        }
        // open/write/close the file
        $fp = fopen($this->log_file_location, 'a+');
        fwrite($fp, $str . "\n");
        fclose($fp);
    }
    /**
     * get the start date for a profile subscription
     *
     * @param arr $rpArr
     * @return str (date
     */
    function getSubscriptionStartDate($rpArr){
        // find it
        list($profieDate,) = explode(' ', $rpArr['profileStartDate']);
        list($y,$m,$d) = explode('-', $profieDate);
        if($y == '0000'){
            $profieStartDate = gmdate("Y-m-d\TH:i:s\Z");
        }else{
            $uPTime = mktime(0,0,0,$m,$d,$y);
            if($uPTime < time()){
                // next one
                while ($uPTime < time()){
                    $m = date('m', $uPTime);
                    $d = date('d', $uPTime);
                    $y = date('Y', $uPTime);
                    switch ($rpArr['billingPeriod']){
                        case 'month':
                            $uPTime = mktime(0,0,0,$m+1,$d,$y);
                        break;
                        case 'day':
                            $uPTime = mktime(0,0,0,$m,$d+1,$y);
                        break;
                        case 'year':
                            $uPTime = mktime(0,0,0,$m,$d,$y+1);
                        break;
                        case 'semiMonth':
                            $uPTime = mktime(0,0,0,$m,$d+14,$y);
                        break;
                        case 'week':
                            $uPTime = mktime(0,0,0,$m,$d+7,$y);
                       break;
                    }
                }
            }
            $profieStartDate = gmdate("Y-m-d\TH:i:s\Z", $uPTime);
        }
        // return
        return $profieStartDate;
    }
    /**
     * base SQL install for EC and DP - RP / FMF
     *
     */
    function baseStructureInstall(){
        // products table alter
        $alter=true;
        $fl = tep_db_query('SHOW COLUMNS FROM ' . TABLE_PRODUCTS);
        if (tep_db_num_rows($fl) > 0) {
            while ($row = tep_db_fetch_array($fl)) {
                if($row['Field'] == 'products_type'){
                    $alter = false;
                }
            }
        }
        if($alter){
            tep_db_query('alter table ' . TABLE_PRODUCTS . ' add products_type enum(\'standard\', \'recurring\') not null default \'standard\'');
        }
        // orders alters
        $alter1=true;
        $alter2=true;
        $fl = tep_db_query('SHOW COLUMNS FROM ' . TABLE_ORDERS);
        if (tep_db_num_rows($fl) > 0) {
            while ($row = tep_db_fetch_array($fl)) {
                if($row['Field'] == 'payment_module'){
                    $alter1 = false;
                }elseif($row['Field'] == 'paypal_transaction_id'){
                    $alter2 = false;
                }
            }
        }
        if($alter1){
            tep_db_query('ALTER TABLE ' . TABLE_ORDERS . ' ADD payment_module CHAR(35) DEFAULT NULL');
        }
        if($alter2){
            tep_db_query('ALTER TABLE ' . TABLE_ORDERS . ' ADD paypal_transaction_id CHAR(18) DEFAULT NULL');
        }
        // orders_products alter
        $alter=true;
        $fl = tep_db_query('SHOW COLUMNS FROM ' . TABLE_ORDERS_PRODUCTS);
        if (tep_db_num_rows($fl) > 0) {
            while ($row = tep_db_fetch_array($fl)) {
                if($row['Field'] == 'rp_profile_id'){
                    $alter = false;
                }
            }
        }
        if($alter){
            tep_db_query('alter table ' . TABLE_ORDERS_PRODUCTS . ' add rp_profile_id char(20) default null');
        }
        // customers alter
        $alter=true;
        $fl = tep_db_query('SHOW COLUMNS FROM ' . TABLE_CUSTOMERS);
        $custFieldArr = array();
        if (tep_db_num_rows($fl) > 0) {
            while ($row = tep_db_fetch_array($fl)) {
                $custFieldArr[] = $row['Field'];
                if($row['Field'] == 'customers_paypal_payerid'){
                    $alter = false;
                }
            }
        }
        if($alter){
            tep_db_query('alter table ' . TABLE_CUSTOMERS . ' ADD customers_paypal_payerid VARCHAR( 20 )');
        }
        $alter = true;
        if(count($custFieldArr)>0){
            foreach ($custFieldArr as $field){
                if($field == 'customers_paypal_ec'){
                    $alter = false;
                }
            }
        }
        if($alter){
            tep_db_query('alter table ' . TABLE_CUSTOMERS . ' ADD customers_paypal_ec TINYINT (1) UNSIGNED DEFAULT \'0\'');
        }
        // list all the tables
        $tablesArr=array();
        $tablesSql = 'SHOW TABLES';
        $tl = tep_db_query($tablesSql);
        while ($row = tep_db_fetch_array($tl)) {
            $tablesArr[] = $row['Tables_in_'.DB_DATABASE];
        }
        // add table - rp
        $create = true;
        if(in_array(TABLE_RP_PAYPAL_PRODUCT_PROFILE, $tablesArr)){
            $create = false;
        }
        if($create){
            $createSql = "
                CREATE TABLE " . TABLE_RP_PAYPAL_PRODUCT_PROFILE . " (
                    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    products_id int(11) UNSIGNED NOT NULL DEFAULT 0,
                    profileStartDate datetime DEFAULT NULL,
                    billingPeriod enum('day','week','semimonth','month','year') DEFAULT NULL,
                    billingFrequency smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                    totalBillingCycles smallint(5) UNSIGNED NOT NULL DEFAULT 0,
                    trialProfileStartDate datetime DEFAULT NULL,
                    trialBillingPeriod enum('day','week','semimonth','month','year') DEFAULT NULL,
                    trialBillingFrequency smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                    trialTotalBillingCycles smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                    trialAmt decimal(15,4) NOT NULL,
                    initAmt smallint(5) unsigned NOT NULL DEFAULT 0,
                    failedInitAmtAction enum('ContinueOnFailure', 'CancelOnFailure') NOT NULL DEFAULT 'ContinueOnFailure',
                    PRIMARY KEY (`id`),
                    KEY `products_id` (`products_id`)
               )
            ";
            tep_db_query($createSql);
            // add to array
            $tablesArr[] = TABLE_RP_PAYPAL_PRODUCT_PROFILE;
        }
        // add table - rp
        $create = true;
        if (in_array(TABLE_RP_PAYPAL_PRODUCT_LOOKUP, $tablesArr)){
            $create = false;
        }
        if($create){
            $createSql = "
                CREATE TABLE " . TABLE_RP_PAYPAL_PRODUCT_LOOKUP . " (
                    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    products_id int(11) UNSIGNED NOT NULL DEFAULT 0,
                    paypal_rp_product_profile_id int(11) UNSIGNED NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY  `rpProd` (`products_id`, `paypal_rp_product_profile_id`)
                )
            ";
            tep_db_query($createSql);
            // add to array
            $tablesArr[] = TABLE_RP_PAYPAL_PRODUCT_LOOKUP;
        }
        // add table - fmf
        $create = true;
        if (in_array(TABLE_FMF_PAYPAL_ORDERS_STATUS, $tablesArr)){
            $create = false;
        }
        if($create){
            $createSql = "
                CREATE TABLE " . TABLE_FMF_PAYPAL_ORDERS_STATUS . " (
                    orders_id int(11) UNSIGNED NOT NULL DEFAULT 0,
                    paypal_fmf_status_id tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                    action_taken enum('None', 'Accepted', 'Denied') NOT NULL DEFAULT 'None',
                    action_date datetime DEFAULT NULL,
                    UNIQUE KEY `orderStatus` (`orders_id`, `paypal_fmf_status_id`)
                )
            ";
            tep_db_query($createSql);
            // add to array
            $tablesArr[] = TABLE_FMF_PAYPAL_ORDERS_STATUS;
        }
        // add table - fmf
        $create = true;
        if (in_array(TABLE_FMF_PAYPAL_STATUS, $tablesArr)){
            $create = false;
        }
        if($create){
            $createSql = "
                CREATE TABLE " . TABLE_FMF_PAYPAL_STATUS . " (
                    paypal_fmf_status_id tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
                    language_id tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
                    fmf_status_name char(45) NOT NULL,
                    PRIMARY KEY (`paypal_fmf_status_id`,`language_id`),
                    KEY `fmf_status_name` (`fmf_status_name`)
                )
            ";
            tep_db_query($createSql);
            // add to array
            $tablesArr[] = TABLE_FMF_PAYPAL_STATUS;
        }
        // load data - fmf TABLE_FMF_PAYPAL_STATUS
        $cntSql = 'SELECT count(*) as `count` FROM ' . TABLE_FMF_PAYPAL_STATUS;
        $rs = tep_db_query($cntSql);
        $row = tep_db_fetch_array($rs);
        $loadData = false;
        if(is_array($row)){
            if($row['count'] == 0){
                $loadData = true;
            }
        }else{
            $loadData = true;
        }
        if($loadData){
            $sql = "
            INSERT INTO " . TABLE_FMF_PAYPAL_STATUS . " VALUES (1, 1, 'AVS No Match'), (2, 1, 'AVS Partial Match'), (3, 1, 'AVS Unavailable/Unsupported'),
             (4, 1, 'Card Security Code (CSC) Mismatch'), (5, 1, 'Maximum Transaction Amount'), (6, 1, 'Unconfirmed Address'), (7, 1, 'Country Monitor'),
             (8, 1, 'Large Order Number'), (9, 1, 'Billing/Shipping Address Mismatch'), (10, 1, 'Risky ZIP Code'), (11, 1, 'Suspected Freight Forwarder Check'),
             (12, 1, 'Total Purchase Price Minimum'), (13, 1, 'IP Address Velocity'), (14, 1, 'Risky Email Address Domain Check'),
             (15, 1, 'Risky Bank Identification Number (BIN) Check'), (16, 1, 'Risky IP Address Range'), (17, 1, 'PayPal Fraud Model')
            ";
            tep_db_query($sql);
        }
        // load data - orders_status
        $status_sql = 'select count(*) as `count` from ' . TABLE_ORDERS_STATUS . ' WHERE orders_status_name like \'%Paypal FMF%\'';
        $rs = tep_db_query($status_sql);
        $row = tep_db_fetch_array($rs);
        $loadData = false;
        if(is_array($row)){
            if($row['count'] == 0){
                $loadData = true;
            }
        }else{
            $loadData = true;
        }
        if($loadData){
            $sql = "
            INSERT INTO " . TABLE_ORDERS_STATUS . " VALUES (5, 1, 'Review [Paypal FMF]', 0, 0),(5, 2, 'Review [Paypal FMF]', 0, 0),(5, 3, 'Review [Paypal FMF]', 0, 0)
            ";
            tep_db_query($sql);
        }
    }


    //////////////////////////////////////////


	/**
	* Clean out the session vars
	*/
	function clean_session() {
	      tep_session_unregister('enroll_lookup_attempted');
	      tep_session_unregister('authentication_attempted');
	      tep_session_unregister('transactionId');
	      tep_session_unregister('enrolled');
	      tep_session_unregister('acsURL');
	      tep_session_unregister('payload');
	      tep_session_unregister('auth_status');
	      tep_session_unregister('sig_status');
	      tep_session_unregister('auth_xid');
	      tep_session_unregister('auth_cavv');
	      tep_session_unregister('auth_eci');
	}
	/**
	 * lookup
	 *
	 * @param array $lookup_data_array
	 * @return array
	 *
	 */
    function lookup($lookup_data_array) {
      /**
       * register the cc vars
       */
      $_SESSION['cc_number'] = $lookup_data_array['cc_number'];
      $_SESSION['cc_expires_month'] = $lookup_data_array['cc_expires_month'];
      $_SESSION['cc_expires_year'] = $lookup_data_array['cc_expires_year'];
      $_SESSION['cc_cvv'] = $HTTP_POST_VARS['cc_cvv'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_TXN_URL'");
      $resultset = tep_db_fetch_array($query);
      $url = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_PROCESSOR'");
      $resultset = tep_db_fetch_array($query);
      $processorId = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_MERCHANT'");
      $resultset = tep_db_fetch_array($query);
      $merchantId = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_PASSWORD'");
      $resultset = tep_db_fetch_array($query);
      $password = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_AUTHENTICATE_REQ'");
      $resultset = tep_db_fetch_array($query);
      $authBusinessRule = $resultset['configuration_value'];

      // get the ISO 4217 currency
      $iso_currency = $this->getISOCurrency($lookup_data_array['currency']);

      // format the transaction amounts
      $raw_amount = $this->formatRawAmount($lookup_data_array['txn_amount'], $iso_currency);

      // format the card expiration
      $cc_expires = substr($lookup_data_array['cc_expires'], 2, 4) . substr($lookup_data_array['cc_expires'], 0, 2);
      // Build the XML cmpi_lookup message
      $data = '<CardinalMPI>';
      $data = $data . '<MsgType>cmpi_lookup</MsgType>';
      $data = $data . '<Version>1.7</Version>';
      $data = $data . '<ProcessorId>' . $this->escapeXML($processorId) . '</ProcessorId>';
      $data = $data . '<MerchantId><![CDATA[' . $this->escapeXML($merchantId) . ']]></MerchantId>';
      $data = $data . '<TransactionPwd><![CDATA[' . $this->escapeXML($password) . ']]></TransactionPwd>';
	  $data = $data . '<TransactionType>C</TransactionType>';
	  $data = $data . '<OrderNumber>' . $this->escapeXML($lookup_data_array['order_number']) . '</OrderNumber>';
      $data = $data . '<OrderDescription>' . $this->escapeXML($lookup_data_array['order_desc']) . '</OrderDescription>';
      $data = $data . '<Amount>' . $this->escapeXML($raw_amount) . '</Amount>';
      $data = $data . '<CurrencyCode>' . $this->escapeXML($iso_currency) . '</CurrencyCode>';
      $data = $data . '<CardNumber>' . $this->escapeXML($lookup_data_array['cc_number']) . '</CardNumber>';
	  $data = $data . '<CardExpMonth>' . $this->escapeXML($lookup_data_array['cc_expires_month']) . '</CardExpMonth>';
	  $data = $data . '<CardExpYear>' . $this->escapeXML($lookup_data_array['cc_expires_year']) . '</CardExpYear>';
      $data = $data . '<UserAgent>' . $this->escapeXML($lookup_data_array['user_agent']) . '</UserAgent>';
      $data = $data . '<BrowserHeader>' . $this->escapeXML($lookup_data_array['browser_header']) . '</BrowserHeader>';
      $data = $data . '</CardinalMPI>';


      if (strcmp(MODULE_PAYMENT_PAYPAL_DIRECT_3D_DEBUGGING, 'true') == 0) {
        $this->recordToLog(array('3dRequest' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_lookup request (' . $url . ') - ' . $data), 'request');
      }

      $responseString = $this->sendHttp($url, $data);

      if (strcmp(MODULE_PAYMENT_PAYPAL_DIRECT_3D_DEBUGGING, 'true') == 0) {
        $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_lookup response - ' . $responseString), 'response');
      }

      // parse the XML
      $parser = new CardinalXMLParser;
      $parser->deserializeXml($responseString);

      $errorNo = $parser->deserializedResponse['ErrorNo'];
      $errorDesc = $parser->deserializedResponse['ErrorDesc'];
      $enrolled = $parser->deserializedResponse['Enrolled'];

      if (strcasecmp('0', $errorNo) != 0) {
        $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_lookup error - ' . $errorNo . ' - ' . $errorDesc), 'response');
      }

      // default the continue flag to 'N'
      $continue_flag = 'N';

      // determine whether the transaction should continue or fail based upon
      // the enrollment lookup results
      if (strcasecmp($authBusinessRule, 'No') == 0) {
          $continue_flag = 'Y';
      }
      else if (strcmp($errorNo, '0') == 0) {
          if (strcasecmp($enrolled, 'Y') == 0) {
            $continue_flag = 'Y';
          }
          else if (strcasecmp($enrolled, 'N') == 0) {
            $cardType = $this->determineCardType($cc_card_number);
            if (strcasecmp($cardType, 'VISA') == 0 ||
                strcasecmp($cardType, 'JCB') == 0) {
                $continue_flag = 'Y';
            }
          }
      }

      if (strcasecmp('Y', $continue_flag) == 0) {
        // For validation/security purposes, mark the session that the
        // lookup result was acceptable.
        global $enroll_lookup_attempted;
        $enroll_lookup_attempted = 'Y';
        tep_session_register('enroll_lookup_attempted');

      } else {
        // For validation/security purposes, mark the session that the
        // lookup result was not acceptable.
        tep_session_unregister('enroll_lookup_attempted');
      }

      $result = array('continue_flag' => $continue_flag,
                      'enrolled' => $enrolled,
                      'transaction_id' => $parser->deserializedResponse['TransactionId'],
                      'error_no' => $errorNo,
                      'error_desc' => $errorDesc,
                      'acs_url' => $parser->deserializedResponse['ACSUrl'],
                      'spa_hidden_fields' => $parser->deserializedResponse['SPAHiddenFields'],
                      'payload' => $parser->deserializedResponse['Payload']);

      return $result;
    }
	/**
	 * authenticate the user against cardinal
	 *
	 * @param array $lookup_data_array
	 * @return array
	 */
    function authenticate($authenticate_data_array) {

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_TXN_URL'");
      $resultset = tep_db_fetch_array($query);
      $url = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_PROCESSOR'");
      $resultset = tep_db_fetch_array($query);
      $processorId = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_MERCHANT'");
      $resultset = tep_db_fetch_array($query);
      $merchantId = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_PASSWORD'");
      $resultset = tep_db_fetch_array($query);
      $password = $resultset['configuration_value'];

      $query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_AUTHENTICATE_REQ'");
      $resultset = tep_db_fetch_array($query);
      $authBusinessRule = $resultset['configuration_value'];

      // Build the XML cmpi_authenticate message
      $data = '<CardinalMPI>';
      $data = $data . '<MsgType>cmpi_authenticate</MsgType>';
      $data = $data . '<Version>1.7</Version>';
      $data = $data . '<ProcessorId>' . $this->escapeXML($processorId) . '</ProcessorId>';
      $data = $data . '<MerchantId><![CDATA[' . $this->escapeXML($merchantId) . ']]></MerchantId>';
      $data = $data . '<TransactionPwd><![CDATA[' . $this->escapeXML($password) . ']]></TransactionPwd>';
	  $data = $data . '<TransactionType>C</TransactionType>';
      $data = $data . '<TransactionId>' . $this->escapeXML($authenticate_data_array['transaction_id']) . '</TransactionId>';
      $data = $data . '<PAResPayload>' . $this->escapeXML($authenticate_data_array['payload']) . '</PAResPayload>';
      $data = $data . '</CardinalMPI>';

	  if (strcmp(MODULE_PAYMENT_PAYPAL_DIRECT_3D_DEBUGGING, 'true') == 0) {
        $this->recordToLog(array('3dRequest' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_authenticate request (' . $url . ') - ' . $data), 'request');
      }

      $responseString = $this->sendHttp($url, $data);

      if (strcmp(MODULE_PAYMENT_PAYPAL_DIRECT_3D_DEBUGGING, 'true') == 0) {
        $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_authenticate response - ' . $responseString), 'response');
      }

      // parse the XML
      $parser = new CardinalXMLParser;
      $parser->deserializeXml($responseString);

      $errorNo = $parser->deserializedResponse['ErrorNo'];
      $errorDesc = $parser->deserializedResponse['ErrorDesc'];
      $authStatus = $parser->deserializedResponse['PAResStatus'];
      $sigStatus = $parser->deserializedResponse['SignatureVerification'];
      $xid = $parser->deserializedResponse['Xid'];
      $cavv = $parser->deserializedResponse['Cavv'];
      $eci = $parser->deserializedResponse['EciFlag'];

      // default the continue flag to 'N'
      $continue_flag = 'N';

      if (strcmp($errorNo, '0') == 0) {
          if (strcasecmp($authStatus, 'Y') == 0 ||
              strcasecmp($authStatus, 'A') == 0) {
            $continue_flag = 'Y';
          }
          else if (strcasecmp($authStatus, 'N') == 0) {
            $continue_flag = 'N';
          }
          else if (strcasecmp($authStatus, 'U') == 0) {
            if (strcasecmp($authBusinessRule, 'No') == 0) {
                $this->recordToLog(array('3dResponse' => 'Business rule in effect, setting to continue to Y'), 'response');
                $continue_flag = 'Y';
            }
          }
      }
      else {
        $this->recordToLog(array('3dresponse' => '[' . tep_session_id() . '] Cardinal Centinel - cmpi_authenticate returned an error - ' . $errorNo . ' - ' . $errorDesc), 'response');
        $continue_flag = 'N';
      }

      if (strcasecmp($continue_flag, 'Y') == 0 &&
          strcasecmp($sigStatus, 'N') == 0) {
            // Signature status is 'N', do not continue
            $continue_flag = 'N';
      }

      if (strcasecmp('Y', $continue_flag) == 0) {
        // For validation/security purposes, mark the session that the
        // authentication result was acceptable.
        global $authentication_attempted;
        $authentication_attempted = 'Y';
        tep_session_register('authentication_attempted');

      } else {
        // For validation/security purposes, mark the session that the
        // authentication result was not acceptable.
        tep_session_unregister('authentication_attempted');
      }

      $result = array('continue_flag' => $continue_flag,
                      'auth_status' => $authStatus,
                      'sig_status' => $sigStatus,
                      'error_no' => $errorNo,
                      'error_desc' => $errorDesc,
                      'auth_xid' => $xid,
                      'auth_cavv' => $cavv,
                      'auth_eci' => $eci);

      return $result;
    }
	/**
	 * post the payload using cURL
	 * 
	 * @param str $url
	 * @param str $data
	 * @return str
	 */
    function sendHttp($url, $data) {

        // verify that the URL uses a supported protocol.
        if( (strpos($url, "http://")=== 0) || (strpos($url, "https://")=== 0) ) {

            // create a new cURL resource
            $ch = curl_init($url);

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "cmpi_msg=".urlencode($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);

            // Execute the request.
            $result = curl_exec($ch);
            $succeeded  = curl_errno($ch) == 0 ? true : false;

            // close cURL resource, and free up system resources
            curl_close($ch);

            // If Communication was not successful set error result, otherwise
            if (!$succeeded) {
                $this->recordToLog(array('3dRequest' => '[' . tep_session_id() . '] Centinel Request:  ' . $data), 'request');
                $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - ' . CENTINEL_ERROR_CODE_8030_DESC, $result), 'response');
                $result = $this->setErrorResponse(CENTINEL_ERROR_CODE_8030, CENTINEL_ERROR_CODE_8030_DESC);

            } else {

                // Assert that we received an expected Centinel Message in reponse.
                if (strpos($result, "<CardinalMPI>") === false) {
                    $this->recordToLog(array('3dRequest' => '[' . tep_session_id() . '] Centinel Request:  ' . $data), 'request');
                    $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - ' . CENTINEL_ERROR_CODE_8010_DESC, $result), 'response');
                    $result = $this->setErrorResponse(CENTINEL_ERROR_CODE_8010, CENTINEL_ERROR_CODE_8010_DESC);
                }
            }

        } else {
            $this->recordToLog(array('3dResponse' => '[' . tep_session_id() . '] Cardinal Centinel - ' . CENTINEL_ERROR_CODE_8000_DESC . ' - ' . $url), 'response');
            $result = $this->setErrorResponse(CENTINEL_ERROR_CODE_8000, CENTINEL_ERROR_CODE_8000_DESC);
        }

        return $result;
    }
	/**
	 * Escape the XML on element
	 * 
	 * @param str $elementValue
	 * @return str
	 */
    function escapeXML($elementValue){

        $escapedValue = str_replace("&", "&amp;", $elementValue);
        $escapedValue = str_replace("<", "&lt;", $escapedValue);

        return $escapedValue;

    }
	/**
	 * Set the error response
	 * 
	 * @param int $errorNo
	 * @param str $errorDesc
	 * @return array
	 */
    function setErrorResponse($errorNo, $errorDesc) {

      $resultText  = "<CardinalMPI>";
      $resultText = $resultText."<ErrorNo>".($errorNo)."</ErrorNo>" ;
      $resultText = $resultText."<ErrorDesc>".($errorDesc)."</ErrorDesc>" ;
      $resultText  = $resultText."</CardinalMPI>";

      return $resultText;
    }
	/**
	 * get the error
	 * 
	 * @return array
	 */
    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_PAYPAL_DIRECT_3D_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }
	/**
	 * get the friendly error message for auth
	 *
	 * @return str
	 */
    function get_authentication_error() {
      return MODULE_PAYMENT_PAYPAL_DIRECT_3D_AUTHENTICATION_ERROR;
    }
	/**
	 * check if this is enabled
	 *
	 * @return boolean
	 */
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_DIRECT_3D_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
    /**
     * Convert Currency to ISO4217 3 digit code
	 * If curr is char code will convert to digit code
	 * If curr is digits less than 3, will pad with leading zeros
	 * If we are unable to format curr, curr is returned unformatted.
	 * MAPs will return the appropriate error code.
	 * 
	 * @return str
     */
    function getISOCurrency($curr) {
        $out = "";
        if(ctype_digit($curr) || is_int($curr)) {
            $numCurr = $curr + 0;
            if($numCurr < 10) {
                $out = "00" . $numCurr;
            } else if ($numCurr < 100) {
                $out = "0" . $numCurr;
            } else {
                //Assume 3 digits (if greater let MAPs handle error)
                $out = "" . $numCurr;
            }
        } else {
            // Convert char to digit (if no convertion exists let MAPs handle error)

            $curCode = Array();
            $curCode["ADP"]="020";
            $curCode["AED"]="784";
            $curCode["AFA"]="004";
            $curCode["ALL"]="008";
            $curCode["AMD"]="051";
            $curCode["ANG"]="532";
            $curCode["AON"]="024";
            $curCode["ARS"]="032";
            $curCode["ATS"]="040";
            $curCode["AUD"]="036";
            $curCode["AWG"]="533";
            $curCode["AZM"]="031";
            $curCode["BAM"]="977";
            $curCode["BBD"]="052";
            $curCode["BDT"]="050";
            $curCode["BEF"]="056";
            $curCode["BGL"]="100";
            $curCode["BHD"]="048";
            $curCode["BIF"]="108";
            $curCode["BMD"]="060";
            $curCode["BND"]="096";
            $curCode["BOB"]="068";
            $curCode["BRL"]="986";
            $curCode["BSD"]="044";
            $curCode["BTN"]="064";
            $curCode["BWP"]="072";
            $curCode["BYR"]="974";
            $curCode["BZD"]="084";
            $curCode["CAD"]="124";
            $curCode["CDF"]="976";
            $curCode["CHF"]="756";
            $curCode["CLP"]="152";
            $curCode["CNY"]="156";
            $curCode["COP"]="170";
            $curCode["CRC"]="188";
            $curCode["CUP"]="192";
            $curCode["CVE"]="132";
            $curCode["CYP"]="196";
            $curCode["CZK"]="203";
            $curCode["DEM"]="276";
            $curCode["DJF"]="262";
            $curCode["DKK"]="208";
            $curCode["DOP"]="214";
            $curCode["DZD"]="012";
            $curCode["EEK"]="233";
            $curCode["EGP"]="818";
            $curCode["ERN"]="232";
            $curCode["ETB"]="230";
            $curCode["EUR"]="978";
            $curCode["FIM"]="246";
            $curCode["FJD"]="242";
            $curCode["FKP"]="238";
            $curCode["FRF"]="250";
            $curCode["GBP"]="826";
            $curCode["GEL"]="981";
            $curCode["GHC"]="288";
            $curCode["GIP"]="292";
            $curCode["GMD"]="270";
            $curCode["GNF"]="324";
            $curCode["GTQ"]="320";
            $curCode["GWP"]="624";
            $curCode["GYD"]="328";
            $curCode["HKD"]="344";
            $curCode["HNL"]="340";
            $curCode["HRK"]="191";
            $curCode["HTG"]="332";
            $curCode["HUF"]="348";
            $curCode["IDR"]="360";
            $curCode["IEP"]="372";
            $curCode["ILS"]="376";
            $curCode["INR"]="356";
            $curCode["IQD"]="368";
            $curCode["IRR"]="364";
            $curCode["ISK"]="352";
            $curCode["ITL"]="380";
            $curCode["JMD"]="388";
            $curCode["JOD"]="400";
            $curCode["JPY"]="392";
            $curCode["KES"]="404";
            $curCode["KGS"]="417";
            $curCode["KHR"]="116";
            $curCode["KMF"]="174";
            $curCode["KPW"]="408";
            $curCode["KRW"]="410";
            $curCode["KWD"]="414";
            $curCode["KYD"]="136";
            $curCode["KZT"]="398";
            $curCode["LAK"]="418";
            $curCode["LBP"]="422";
            $curCode["LKR"]="144";
            $curCode["LRD"]="430";
            $curCode["LSL"]="426";
            $curCode["LTL"]="440";
            $curCode["LUF"]="442";
            $curCode["LVL"]="428";
            $curCode["LYD"]="434";
            $curCode["MAD"]="504";
            $curCode["MDL"]="498";
            $curCode["MGF"]="450";
            $curCode["MKD"]="807";
            $curCode["MMK"]="104";
            $curCode["MNT"]="496";
            $curCode["MOP"]="446";
            $curCode["MRO"]="478";
            $curCode["MTL"]="470";
            $curCode["MUR"]="480";
            $curCode["MVR"]="462";
            $curCode["MWK"]="454";
            $curCode["MXN"]="484";
            $curCode["MYR"]="458";
            $curCode["MZM"]="508";
            $curCode["NAD"]="516";
            $curCode["NGN"]="566";
            $curCode["NIO"]="558";
            $curCode["NLG"]="528";
            $curCode["NOK"]="578";
            $curCode["NPR"]="524";
            $curCode["NZD"]="554";
            $curCode["OMR"]="512";
            $curCode["PAB"]="590";
            $curCode["PEN"]="604";
            $curCode["PGK"]="598";
            $curCode["PHP"]="608";
            $curCode["PKR"]="586";
            $curCode["PLN"]="985";
            $curCode["PTE"]="620";
            $curCode["PYG"]="600";
            $curCode["QAR"]="634";
            $curCode["ROL"]="642";
            $curCode["RUB"]="643";
            $curCode["RUR"]="810";
            $curCode["RWF"]="646";
            $curCode["SAR"]="682";
            $curCode["SBD"]="090";
            $curCode["SCR"]="690";
            $curCode["SDD"]="736";
            $curCode["SEK"]="752";
            $curCode["SGD"]="702";
            $curCode["SHP"]="654";
            $curCode["SIT"]="705";
            $curCode["SKK"]="703";
            $curCode["SLL"]="694";
            $curCode["SOS"]="706";
            $curCode["SRG"]="740";
            $curCode["STD"]="678";
            $curCode["SVC"]="222";
            $curCode["SYP"]="760";
            $curCode["SZL"]="748";
            $curCode["THB"]="764";
            $curCode["TJS"]="972";
            $curCode["TMM"]="795";
            $curCode["TND"]="788";
            $curCode["TOP"]="776";
            $curCode["TPE"]="626";
            $curCode["TRL"]="792";
            $curCode["TTD"]="780";
            $curCode["TWD"]="901";
            $curCode["TZS"]="834";
            $curCode["UAH"]="980";
            $curCode["UGX"]="800";
            $curCode["USD"]="840";
            $curCode["UYU"]="858";
            $curCode["UZS"]="860";
            $curCode["VEB"]="862";
            $curCode["VND"]="704";
            $curCode["VUV"]="548";
            $curCode["WST"]="882";
            $curCode["XAF"]="950";
            $curCode["XCD"]="951";
            $curCode["XOF"]="952";
            $curCode["XPF"]="953";
            $curCode["YER"]="886";
            $curCode["YUM"]="891";
            $curCode["ZAR"]="710";
            $curCode["ZMK"]="894";
            $curCode["ZWD"]="716";

            $out = $curCode[$curr];
        }

        return $out;
    }
    /**
	 * Format Amount to rawamount
	 * Rawamount does not contain a decimal and is rounded and padded
	 * based on the currency exponenet value
	 * amount - Double floating point
   	 * curr - ISO4217 Currency code, 3char or 3digit
   	 * 
   	 * @return str
     */
    function formatRawAmount($amount, $curr) {
        $dblAmount = $amount + 0.0;

        // Build Currency format table
        $curFormat = Array();
        $curFormat["020"]=0;
        $curFormat["784"]=2;
        $curFormat["044"]=2;
        $curFormat["004"]=2;
        $curFormat["008"]=2;
        $curFormat["051"]=2;
        $curFormat["532"]=2;
        $curFormat["024"]=2;
        $curFormat["032"]=2;
        $curFormat["040"]=2;
        $curFormat["036"]=2;
        $curFormat["533"]=2;
        $curFormat["031"]=2;
        $curFormat["977"]=2;
        $curFormat["052"]=2;
        $curFormat["050"]=2;
        $curFormat["056"]=0;
        $curFormat["100"]=2;
        $curFormat["048"]=3;
        $curFormat["108"]=0;
        $curFormat["060"]=2;
        $curFormat["096"]=2;
        $curFormat["068"]=2;
        $curFormat["986"]=2;
        $curFormat["064"]=2;
        $curFormat["072"]=2;
        $curFormat["974"]=0;
        $curFormat["084"]=2;
        $curFormat["124"]=2;
        $curFormat["976"]=2;
        $curFormat["756"]=2;
        $curFormat["152"]=0;
        $curFormat["156"]=2;
        $curFormat["170"]=2;
        $curFormat["188"]=2;
        $curFormat["192"]=2;
        $curFormat["132"]=2;
        $curFormat["196"]=2;
        $curFormat["203"]=2;
        $curFormat["276"]=2;
        $curFormat["262"]=0;
        $curFormat["208"]=2;
        $curFormat["214"]=2;
        $curFormat["012"]=2;
        $curFormat["233"]=2;
        $curFormat["818"]=2;
        $curFormat["232"]=2;
        $curFormat["230"]=2;
        $curFormat["978"]=2;
        $curFormat["246"]=2;
        $curFormat["242"]=2;
        $curFormat["238"]=2;
        $curFormat["250"]=2;
        $curFormat["826"]=2;
        $curFormat["981"]=2;
        $curFormat["288"]=2;
        $curFormat["292"]=2;
        $curFormat["270"]=2;
        $curFormat["324"]=0;
        $curFormat["320"]=2;
        $curFormat["624"]=2;
        $curFormat["328"]=2;
        $curFormat["344"]=2;
        $curFormat["340"]=2;
        $curFormat["191"]=2;
        $curFormat["332"]=2;
        $curFormat["348"]=2;
        $curFormat["360"]=2;
        $curFormat["372"]=2;
        $curFormat["376"]=2;
        $curFormat["356"]=2;
        $curFormat["368"]=3;
        $curFormat["364"]=2;
        $curFormat["352"]=2;
        $curFormat["380"]=0;
        $curFormat["388"]=2;
        $curFormat["400"]=3;
        $curFormat["392"]=0;
        $curFormat["404"]=2;
        $curFormat["417"]=2;
        $curFormat["116"]=2;
        $curFormat["174"]=0;
        $curFormat["408"]=2;
        $curFormat["410"]=0;
        $curFormat["414"]=3;
        $curFormat["136"]=2;
        $curFormat["398"]=2;
        $curFormat["418"]=2;
        $curFormat["422"]=2;
        $curFormat["144"]=2;
        $curFormat["430"]=2;
        $curFormat["426"]=2;
        $curFormat["440"]=2;
        $curFormat["442"]=0;
        $curFormat["428"]=2;
        $curFormat["434"]=3;
        $curFormat["504"]=2;
        $curFormat["498"]=2;
        $curFormat["450"]=0;
        $curFormat["807"]=2;
        $curFormat["104"]=2;
        $curFormat["496"]=2;
        $curFormat["446"]=2;
        $curFormat["478"]=2;
        $curFormat["470"]=2;
        $curFormat["480"]=2;
        $curFormat["462"]=2;
        $curFormat["454"]=2;
        $curFormat["484"]=2;
        $curFormat["458"]=2;
        $curFormat["508"]=2;
        $curFormat["516"]=2;
        $curFormat["566"]=2;
        $curFormat["558"]=2;
        $curFormat["528"]=2;
        $curFormat["578"]=2;
        $curFormat["524"]=2;
        $curFormat["554"]=2;
        $curFormat["512"]=3;
        $curFormat["590"]=2;
        $curFormat["604"]=2;
        $curFormat["598"]=2;
        $curFormat["608"]=2;
        $curFormat["586"]=2;
        $curFormat["985"]=2;
        $curFormat["620"]=0;
        $curFormat["600"]=0;
        $curFormat["634"]=2;
        $curFormat["642"]=2;
        $curFormat["643"]=2;
        $curFormat["810"]=2;
        $curFormat["646"]=0;
        $curFormat["682"]=2;
        $curFormat["090"]=2;
        $curFormat["690"]=2;
        $curFormat["736"]=2;
        $curFormat["752"]=2;
        $curFormat["702"]=2;
        $curFormat["654"]=2;
        $curFormat["705"]=2;
        $curFormat["703"]=2;
        $curFormat["694"]=2;
        $curFormat["706"]=2;
        $curFormat["740"]=2;
        $curFormat["678"]=2;
        $curFormat["222"]=2;
        $curFormat["760"]=2;
        $curFormat["748"]=2;
        $curFormat["764"]=2;
        $curFormat["972"]=2;
        $curFormat["795"]=2;
        $curFormat["788"]=3;
        $curFormat["776"]=2;
        $curFormat["626"]=0;
        $curFormat["792"]=0;
        $curFormat["780"]=2;
        $curFormat["901"]=2;
        $curFormat["834"]=2;
        $curFormat["980"]=2;
        $curFormat["800"]=2;
        $curFormat["840"]=2;
        $curFormat["858"]=2;
        $curFormat["860"]=2;
        $curFormat["862"]=2;
        $curFormat["704"]=2;
        $curFormat["548"]=0;
        $curFormat["882"]=2;
        $curFormat["950"]=0;
        $curFormat["951"]=2;
        $curFormat["952"]=0;
        $curFormat["953"]=0;
        $curFormat["886"]=2;
        $curFormat["891"]=2;
        $curFormat["710"]=2;
        $curFormat["894"]=2;
        $curFormat["716"]=2;

        $digCurr = $this->getISOCurrency("" . $curr);
        $exponent = $curFormat[$digCurr];



        $strAmount = "" . Round($dblAmount, $exponent);
        $strRetVal = "" . $strAmount;

        // decimal position
        $curpos = strpos($strRetVal, ".");

        // Pad with zeros
        if($curpos == true) {
            $padCount = $exponent - (strlen($strRetVal) - $curpos - 1);
            for($i=0;$i<$padCount;$i++) {
                $strRetVal .= "0";
            }
        }
        else {
            $padCount = $exponent;
            for($i=0;$i<$padCount;$i++) {
                $strRetVal .= "0";
            }
        }

        if($curpos !== false) {
            $strRetVal = substr($strRetVal, 0, $curpos) . substr($strRetVal, $curpos+1);
        }
        return $strRetVal;
    }
    /**
     * see if the card requires a lookup
     * 
     * @params str $Card_Number
     * @return boolean
     */
    function requiresLookup($Card_Number) {
        $cardType = $this->determineCardType($Card_Number);
        if (strcasecmp("VISA", $cardType) == 0 ||
            strcasecmp("MASTERCARD", $cardType) == 0 ||
            strcasecmp("JCB", $cardType) == 0) {
            return true;
        } else {
            return false;
        }
    }
	/**
	 * get the card type
	 * 
	 * @params str $Card_Number
	 * @return enum (predifined card names)
	 */
    function determineCardType($Card_Number) {

        $Card_Number = trim($Card_Number);

        $cardType = "UNKNOWN";  // VISA, MASTERCARD, JCB, AMEX, DISCOVER, UNKNOWN

        if (strlen($Card_Number) == "16" AND strpos($Card_Number, "4") === 0) {
            $cardType = "VISA";
        } else if (strlen($Card_Number) == "13" AND strpos($Card_Number, "4") === 0) {
            $cardType = "VISA";
        } else if (strlen($Card_Number) == "13" AND strpos($Card_Number, "5") === 0) {
            $cardType = "MASTERCARD";
        } else if (strlen($Card_Number) == "16" AND strpos($Card_Number, "5") === 0) {
            $cardType = "MASTERCARD";
        } else if (strlen($Card_Number) == "15" AND strpos($Card_Number, "2131") === 0) {
            $cardType = "JCB";
        } else if (strlen($Card_Number) == "15" AND strpos($Card_Number, "1800") === 0) {
            $cardType = "JCB";
        } else if (strlen($Card_Number) == "16" AND strpos($Card_Number, "3") === 0) {
            $cardType = "JCB";
        } else if (strlen($Card_Number) == "15" AND strpos($Card_Number, "34") === 0) {
            $cardType = "AMEX";
        } else if (strlen($Card_Number) == "15" AND strpos($Card_Number, "37") === 0) {
            $cardType = "AMEX";
        } else if (strlen($Card_Number) == "16" AND strcmp(substr(Card_Number, 0, 4), "6011") == 0) {
            $cardType = "DISCOVER";
        }

        return $cardType;
    }
  }
	/*
	 * cardinal class for XML parsing
	 */
    class CardinalXMLParser{

		var $xml_parser;
		var $deseralizedResponse;
		var $elementName;
		var $elementValue;

	    /////////////////////////////////////////////////////////////////////////////////////////////
		// Function CardinalXMLParser()
		//
		// Initialize the XML parser.
		/////////////////////////////////////////////////////////////////////////////////////////////

		function CardinalXMLParser() {
		  $this->xml_parser = xml_parser_create();
		}

		/////////////////////////////////////////////////////////////////////////////////////////////
		// Function startElement(parser, name, attribute)
		//
		// Start Tag Element Handler
		/////////////////////////////////////////////////////////////////////////////////////////////

		function startElement($parser, $name, $attrs='') {
			$this->elementName = $name;
		}

		/////////////////////////////////////////////////////////////////////////////////////////////
		// Function elementData(parser, data)
		//
		// Element Data Handler
		/////////////////////////////////////////////////////////////////////////////////////////////

		function elementData($parser, $data) {
			$this->elementValue .= $data;
		}

		/////////////////////////////////////////////////////////////////////////////////////////////
		// Function endElement(name, value)
		//
		// End Tag Element Handler
		/////////////////////////////////////////////////////////////////////////////////////////////

		function endElement($parser, $name) {
			 $this->deserializedResponse[$this->elementName]= $this->elementValue;
			 $this->elementName = "";
			 $this->elementValue = "";
		}

		/////////////////////////////////////////////////////////////////////////////////////////////
		// Function deserialize(xmlString)
		//
		// Deserilize the XML reponse message and add each element to the deseralizedResponse collection.
		// Once complete, then each element reference will be available using the getValue function.
		/////////////////////////////////////////////////////////////////////////////////////////////

		function deserializeXml($responseString) {

			  xml_set_object($this->xml_parser, $this);
			  xml_parser_set_option($this->xml_parser,XML_OPTION_CASE_FOLDING,FALSE);
			  xml_set_element_handler($this->xml_parser, "startElement", "endElement");
			  xml_set_character_data_handler($this->xml_parser, "elementData");

			  if (!xml_parse($this->xml_parser, $responseString)) {

					$this->deserializedResponse["ErrorNo"]= CENTINEL_ERROR_CODE_8020;
					$this->deserializedResponse["ErrorDesc"]= CENTINEL_ERROR_CODE_8020_DESC;
			  }

			  xml_parser_free($this->xml_parser);
		}

}