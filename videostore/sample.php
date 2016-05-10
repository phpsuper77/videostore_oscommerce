<?php

// This code demonstrates how to lookup the country by IP Address

include("./includes/geoip/geoip.inc");

$gi = geoip_open("./includes/geoip/GeoIP.dat",GEOIP_STANDARD);

$ip = $_SERVER['REMOTE_ADDR'];

echo geoip_country_code_by_addr($gi, $ip);

geoip_close($gi);

?>
