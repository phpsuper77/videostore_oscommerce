<?php echo 'Your IP address has been logged -- Your IP is: ' ?><h4><?php print($ip=$_SERVER['REMOTE_ADDR']); $log=("iplog.txt"); $logip=fopen($log,"a"); fputs($logip,gmdate('m-d-y@H:i:sT')." - ".$ip."\n"); fclose($logip); ?></h4>

<?php echo 'You shouldn\'t be here, so go away!' ?>