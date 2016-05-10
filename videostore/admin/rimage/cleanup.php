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
		<meta http-equiv="refresh" content="10" />
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
			<h3>Cleanup Job List</h3>
			<p>Click the cleanup button below to remove all jobs in the completed, cancelled, or failed state.</p>
			<?php
			//create a form with a cleanup button as the submit button. 
			//Clicking the cleanup button will bring us to the submit page, where we will submit a CleanupFinishedJobs request 
			//to the webservice. 
			echo ("<form id=\"Cleanup\" action=\"status.php\" method=\"POST\">");
			echo ("<fieldset>");
			echo ("<legend>Cleanup Jobs</legend>");
			echo ("<input type=\"submit\" name=\"submit_cleanup\" value=\"Cleanup Jobs\" />");
			echo ("</fieldset>");		
			echo ("</form>");
			?>
			

			
							
			</div>
			<?php
				require('include/footer.php');
			?>
		</div>

	</body>
</html>	