<?php

$fp = fopen(DIR_FS_CATALOG.'logs/paypal_logs/ppd-notify-'.date('YmdHis').'.log', 'a');
fwrite($fp, "POST:\r\n");
foreach ($_POST as $key => $value) {
    fwrite($fp, $key.' - '.$value."\r\n");
}
fwrite($fp, "\r\nGET:\r\n");
foreach ($_GET as $key => $value) {
    fwrite($fp, $key.' - '.$value."\r\n");
}
fclose($fp);


?>