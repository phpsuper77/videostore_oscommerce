<?php
/**
 * paypal_cc.php
 *
 * Holds the Credit Card values for OsCommerce
 *
 * Edit the array in  paypal_cc that coresponds to your installed
 * country module.
 *
 * @package    includes/modules/payment/paypal/paypal_cc.php
 * @author     Rock Mutchler <rock@drivedev.com>
 * @copyright  2009
 * @version    $Id:
 */
class paypal_cc {
    /**
     * holds the current country CC array
     *
     * @var array
     */
    var $ccArr = array();
    /**
     * constructor
     *
     * @param str(ISO2) $countryIso2
     * @return array
     */
    function paypal_cc($countryIso2='US'){
        // select the array
        switch ($countryIso2){
            // United Kingdom
            case 'UK':
                $this->ccArr = array('VISA'         => 'Visa',
                                    'MASTERCARD'    => 'MasterCard',
                                    'MAESTRO'       => 'Maestro',
                                    'SOLO'          => 'Solo'
                                    );
            break;
            // Canada
            case 'CA':
                $this->ccArr = array('VISA'         => 'Visa',
                                    'MASTERCARD'    => 'MasterCard'
                                    );
            break;
            // US and catch all
            default:
                $this->ccArr = array('VISA'         => 'Visa',
                                    'MASTERCARD'    => 'MasterCard',
                                    'DISCOVER'      => 'Discover Card',
                                    'AMEX'          => 'American Express'
                                    );
            break;
        }
        // return the array
        return $this->ccArr;
    }
    /**
     * gets the CC array
     *
     * @return array
     */
    function getCcArr(){
        return $this->ccArr;
    }
}
