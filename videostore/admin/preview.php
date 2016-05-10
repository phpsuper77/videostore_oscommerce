<?php
require('includes/application_top.php');
tep_db_query("Update products_to_allied set 
			product_set_total_disks='".$_POST['product_set_total_disks']."',
			product_media_definition='".$_POST['product_media_definition']."',
			product_disk_one_iso='".$_POST['product_disk_one_iso']."',
			product_disk_one_wrap='".$_POST['product_disk_one_wrap']."',
			product_disk_one_disclabel='".$_POST['product_disk_one_disclabel']."',
			product_disk_one_silkscreen='".$_POST['product_disk_one_silkscreen']."',
			product_disk_one_cdpackage='".$_POST['product_disk_one_cdpackage']."',
			
			product_disk_one_cdlabel='".$_POST['product_disk_one_cdlabel']."',
			product_disk_one_barcode_placement='".$_POST['product_disk_one_barcode_placement']."',
			product_disk_one_media_format='".$_POST['product_disk_one_media_format']."',
			product_disk_two_iso='".$_POST['product_disk_two_iso']."',
			product_disk_two_disclabel='".$_POST['product_disk_two_disclabel']."',
			product_disk_two_silkscreen='".$_POST['product_disk_two_silkscreen']."',
			product_disk_two_cdpackage='".$_POST['product_disk_two_cdpackage']."',
			product_disk_two_cdlabel='".$_POST['product_disk_two_cdlabel']."',
			product_disk_two_media_format='".$_POST['product_disk_two_media_format']."',
			status='".$_POST['status']."'
			where product_id='" . $_POST['product_id'] . "'");

echo '<script type="text/javascript"> window.location="'.$_POST['previewbut_url'].'";</script>';

exit();
  
           
?>