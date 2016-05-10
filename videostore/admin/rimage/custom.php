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
			<h3>Sample Custom Job Type.</h3>

			<p>Press the button below to submit a Custom Job type. Note, the variables are hard coded. Please
			view the CODE_customJob.php page in the \include file to see the actual job submission.</p>
			<?php 
			//Create a form to allow someone to trigger the custom Job type. 
			//Note, the form will have an action to go to the job status screen, where we will trigger the job. 
				echo ("<form id=\"customJob\"  action=\"status.php\" method=\"POST\">");
				echo ("<fieldset>");
				echo ("<legend>Submit Custom Job</legend>");

				echo ("<input type=\"submit\" name=\"submit_customJob\" value=\"Submit Custom Job\" />");
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