<?php
ini_set('display_errors', 'On');
 error_reporting(E_ALL);
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
			<h3>Sample Image File to Disc Job.</h3>

			<p>Fill out the following form to submit a job to the Rimage System.</p>
			<?php 
			//Create a form to select options for Job Submission.
			//Note, the form will have an action to go to the job status screen, and display the job submission information passed through the $_POST variables
			//which we will use to submit the actual job. 
				echo ("<form id=\"ImageToDiscJob\" onsubmit=\"return checkFields_job();\" action=\"status.php\" method=\"POST\">");
				echo ("<fieldset>");
				echo ("<legend>New Image to Disc Job</legend>");
			/*
			 An Image file to Disc job requires the following information
			1. CallerId (String)
			2. JobId (String) - This is a Unique Id for the job being submitted.
			3. Copies (Integer) - # of copies you want of this job
			4. MediaType (MediaType) - Enumeration specifying the MediaType. Since there are no ENUM in PHP, see below for implementation
			5. DVDProtect (boolean) - specifies whether or not to use Rimage Video Protect. Requires special dongle to use. Will be false in most implementations.
			6. ImageFile (String) - Full UNC path to the pre-created Image file to be used. 
			7. LabelFile (String) - Full UNC path to the label file (.btw or .pdf)
			8. Merge (Merge) - OPTIONAL when utilizing a merge file with the label file (to insert data into label at run time.
			
			Using the form, we will collect the ImageFile, LabelFile, MediaType, Copies. 
			We will generate the JobId, CallerId, DVDprotect options after the form has been submitted.
			*/
				//Build form input to select Image and Label file paths
				//Hidden fields are due to Firefox only passing the file name, and not the entire path. 
				//Uses javascript to set the Image and Label paths to the hidden field using the onchange event. 
				echo ("Image File Path: <br />\\TVSSERVER\ISO Files\\7DY-DVD-101\\7DY-DVD-101.iso<br />");	
				//echo ("<input type=\"file\" name=\"imgFile\" onchange=\"document.forms[0].imgPath.value=document.forms[0].imgFile.value\" size=\"35\" /> <br />");
				echo ("<input type=\"hidden\" name=\"imgPath\" value=\"\\TVSSERVER\ISO Files\\7DY-DVD-101\\7DY-DVD-101.iso\" /> <br />");
				//echo ("<input type=\"hidden\" name=\"imgPath\" value=\"\" /> <br />");
				echo ("Label File Path: <br />D:\Rimage\Labels\\7DY-DVD-101.pdf<br />");	
				//echo ("<input type=\"file\" name=\"lblFile\" onchange=\"document.forms[0].lblPath.value=document.forms[0].lblFile.value\" size=\"35\"  /> <br />");
				//echo ("<input type=\"hidden\" name=\"lblPath\" value=\"\" /> <br />");
echo ("<input type=\"hidden\" name=\"lblPath\" value=\"D:\Rimage\Labels\\7DY-DVD-101.pdf\" /> <br />");

				//# of copies the user would like
				echo ("Copies: <br />");	
				echo ("<input type=\"text\" name=\"copies\" size=\"5\" value=1> <br />");
				//Use Radio Buttons to get the user to select the correct Media type for the Image they selected.
				echo ("Media Type: <br />");
				echo ("<input type=\"radio\" name=\"media\" value=\"Cdr\" checked=\"checked\" />CD &nbsp;&nbsp;");
				echo ("<input type=\"radio\" name=\"media\" value=\"Dvdr\" />DVD <br />");
				echo ("<input type=\"submit\" name=\"submit_ImageToDiscJob\" value=\"Submit Job\" />");
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