<?php
    function determineProtection($cardNumber, $ECI) {

		$resultStatus = "NOT PROTECTED";

		$cardType = determineCardType($cardNumber);

		if(strcasecmp($cardType, "VISA") == 0){

			 if((strcasecmp($ECI, "05") == 0) || (strcasecmp($ECI, "06") == 0)) {
				$resultStatus = "PROTECTED";
			 } else {
				$resultStatus = "NOT PROTECTED";
			 }
		}
		else if(strcasecmp($cardType, "MASTERCARD") == 0){

			if(strcasecmp($ECI, "02") == 0) {
				$resultStatus = "PROTECTED";
			} else {
				$resultStatus = "NOT PROTECTED";
			}
		}
		else if(strcasecmp($cardType, "JCB") == 0){

			 if((strcasecmp($ECI, "05") == 0) || (strcasecmp($ECI, "06") == 0)) {
				$resultStatus = "PROTECTED";
			 } else {
				$resultStatus = "NOT PROTECTED";
			 }
		}
		return $resultStatus;
	}



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