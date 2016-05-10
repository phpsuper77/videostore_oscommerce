<?php
//start a PHP session to pass data using session variables
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

	<head>
		<title>
			Rimage Web Services Sample with PHP
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link id="normal" rel="stylesheet" type="text/css" href="style/SamplePHP_style.css" />
		
		<script type="text/javascript" src="scripts/checkFields.js"> 
		</script>
	</head>

	<body>
		<div id="container">

			<?php
			//require the header and navigation files for page layout based on the CSS sheet
			require ("include/header.php");
			require("include/navigation.php");
			?>
			
			<div id="content">
			<h3>Welcome to the Rimage PHP Webservices Example</h3>
			
			<p>Descriptions are below for the links across the top of the screen.</p>
			
				<p><strong>Home</strong> - Brings you to this page.</p>
				<p><strong>Image to Disc</strong> - A sample job where a pre-existing image file exists, which is submitted
					to the Rimage System to be produced on Disc.</p>
				<p><strong>ISO Job</strong> - A sample job where data files are submitted to the Rimage System to be imaged, 
					and then recorded to Disc.</p>
				<p><strong>Custom Job</strong> - A job showing how to build a job that requires special settings not included in 
					one of the prebuilt job types.</p>	
				<p><strong>Job Status</strong> - View the status for all jobs submitted through the Web Service API.</p>					
				<p><strong>Alerts</strong> - View any alerts that may require acknowledgement.(Example: Out of blank discs)</p>
				<p><strong>Cleanup</strong> - Clean up the job status list of all jobs with the completed, failed, or cancelled status.</p>
							
			</div>
			<?php
				require('include/footer.php');
			?>
		</div>

	</body>
</html>	
