<?php
/**
 * paypal_rp_product_info.php
 *
 *
 * @package    includes/classes/paypal_rp_product_info.php
 * @author     Rock Mutchler <rock@drivedev.com>
 * @copyright  2009
 * @version    $Id;
 */
class paypal_rp_product_info
{

    var $product_info;

    var $rp_info;

    var $price;

    function paypal_rp_product_info($product_info, $rp_info, $price){
            global $currencies;
        $this->product_info = $product_info;
        $this->rp_info      = $rp_info;
        $this->price        = $price;
    }

    function getProductInfoHeader(){
        global $currencies;
        switch ($this->rp_info['billingPeriod']){
            case 'day':
            case 'week':
            case 'month':
            case 'year':
                if(!is_null($this->rp_info['trialBillingPeriod'])){
                    $pural = '';
                    if($this->rp_info['trialBillingFrequency'] > 1){
                        $pural = 's';
                    }
                    if($pural == 's'){
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial for '.$this->rp_info['billingFrequency'] . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural .' for ' . $this->rp_info['trialTotalBillingCycles'] . ' billing' . $pural;
                    }else{
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial for '.$this->rp_info['billingFrequency'] . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural;
                    }
                }
                $pural = '';
                if($this->rp_info['billingFrequency'] > 1){
                    $pural = 's';
                }
                $arr['normal'] = $this->price . ' every ' . $this->rp_info['billingFrequency'] . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural;
            break;
            case 'semimonth':
                if(!is_null($this->rp_info['trialBillingPeriod'])){
                    $pural = '';
                    if($this->rp_info['trialBillingFrequency'] > 1){
                        $pural = 's';
                    }
                    if($pural == 's'){
                        $timeSemi = ($this->rp_info['totalBillingCycles'] * $this->rp_info['billingFrequency'])/2;
                        if(strstr($timeSemi, '.5')){
                            list($timeSemi,) = explode('.', $timeSemi);
                            $timeSemi = $timeSemi . ' 1/2';
                        }
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial Twice a Month for '. $timeSemi .  ' Months';
                    }else{
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial for 1/2 a Month for 1 billing';
                    }
                }
                $arr['normal'] = $this->price . ' Twice a Month';
            break;
        }
        return $arr;
    }

    function getProductInfoFull(){
        global $currencies;
        switch ($this->rp_info['billingPeriod']){
            case 'day':
            case 'week':
            case 'month':
            case 'year':
                if(!is_null($this->rp_info['trialBillingPeriod'])){
                    $pural = '';
                    if($this->rp_info['trialBillingFrequency'] > 1){
                        $pural = 's';
                    }
                    $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial for '.$this->rp_info['billingFrequency'] . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural .' for ' . $this->rp_info['trialTotalBillingCycles'] . ' Billing' . $pural;
                }
                $pural = '';
                if($this->rp_info['billingFrequency'] > 1){
                    $pural = 's';
                }
                $pural2 = '';
                if(($this->rp_info['totalBillingCycles'] * $this->rp_info['billingFrequency'])>1){
                    $pural2 = 's';
                }
                $arr['normal'] = $this->price . ' Every ' . $this->rp_info['billingFrequency'] . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural .
                ' for ' . ($this->rp_info['totalBillingCycles'] * $this->rp_info['billingFrequency']) . ' ' . ucfirst($this->rp_info['billingPeriod']) . $pural2;
            break;
            case 'semimonth':
                if(!is_null($this->rp_info['trialBillingPeriod'])){
                    $pural = '';
                    if($this->rp_info['trialBillingFrequency'] > 1){
                        $pural = 's';
                    }
                    if($pural == 's'){
                        $timeSemi = ($this->rp_info['totalBillingCycles'] * $this->rp_info['billingFrequency'])/2;
                        if(strstr($timeSemi, '.5')){
                            list($timeSemi,) = explode('.', $timeSemi);
                            $timeSemi = $timeSemi . ' 1/2';
                        }
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial Twice a Month for '. $timeSemi .  ' Months';
                    }else{
                        $arr['trial'] = $currencies->display_price($this->rp_info['trialAmt'], $this->product_info['products_tax_class_id']) . ' Trial for 1/2 a Month for 1 Billing';
                    }
                }
                $arr['normal'] = $this->price . ' Twice a Month';
                ' for ' . ($this->rp_info['totalBillingCycles'] * $this->rp_info['billingFrequency']) / 2 . ' Months';
            break;
        }
        return $arr;
    }
}