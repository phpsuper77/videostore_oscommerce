<?php
class _IUSPS {

    var $server;
    var $user;
    var $pass;
    var $service;
    var $pounds;
    var $ounces;
    var $mailType;
    var $country;


    function setServer($server) {
        $this->server = $server;
    }

    function setUserName($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setService($service) {
        $this->service = preg_quote($service);
    }

    function setWeight($pounds, $ounces=0) {
        $this->pounds = $pounds;
        $this->ounces = $ounces;
    }

    function setMailType($mailType) {
        $this->mailType = rawurlencode($mailType);
    }

    function setCountry($country) {
        $this->country = rawurlencode($country);
    }


    function getPrice() {
        $str = $this->server. "?API=IntlRate&XML=<IntlRateRequest%20USERID=\"";
        $str .= $this->user . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\">";
        $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>";
        $str .= "<MailType>" . $this->mailType . "</MailType>";
        $str .= "<Country>" . $this->country . "</Country></Package></IntlRateRequest>";
        //echo $str;

        $fp = fopen($str, "r");
        while(!feof($fp)){
            $result = fgets($fp, 500);
            $body.=$result;
        }
        fclose($fp);
        # note: using split for systems with non-perl regex (don't know how to do it in sys v regex)
        if (!ereg("Error", $body)) {
            $split = split("</AreasServed>", $body);
            $body = split("</Service>", $split[1]);
            foreach ($body as $k=>$v) {
              if (preg_match("/".$this->service."/", $v)) {
                $spl = split("<Postage>", $v);
                $v = split("</Postage>", $spl[1]);
                $price = $v[0];
                return($price);
              }
            }
            return(false);
        } else {
            return(false);
        }
    }
}
?>

