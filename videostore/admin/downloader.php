<?
$pos = strpos($_GET['filename'], 'full');
if ($pos!==false)
	$name='generate_full.txt';
else
	$name='generate_short.txt';

header("Content-Disposition: attachment; filename=$name"); 
header("Content-type: application/octet-stream"); 
header("Expires: 0"); 
header("Cash-Control: must-revalidate, post-check=0, pre-check=0"); 
echo file_get_contents($filename); 
?>
<script>window.close();</script>
