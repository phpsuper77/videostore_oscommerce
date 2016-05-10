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
			<h3>Job Status Page</h3>
			<p>This page will autorefresh every 10 seconds</p>
			
			<?php
			//Determine Which webpage form we are coming from. 
			
			//if submitted from Image to Disc Job page, run the Image to Disc job code. View the include file to see
			//how to perform the Image to Disc submission via Rimage Web Services API. 
			if (isset($_POST['submit_ImageToDiscJob']))
			{
				require ("include/CODE_ImageToDisc.php");
			}
			
			//check to see if we came from the Custom Job submission page and pull in the 
			if (isset($_POST['submit_customJob']))
			{
				require ("include/CODE_customJob.php");
			}
			
			//Check if we came from the cleanup Job page, if so, Submit a CleanupFinishedJobs request to the Rimage web service
			//view the code in the include directory for CODE_cleanup.php to see the source. 
			if (isset($_POST['submit_cleanup']))
			{
				require ("include/CODE_cleanup.php");
			}

			//The page is autorefreshing, or we have not come from any form. Display the Current Job status table
			//Display all current job status. 
			
			//Set the WSDL file to the Rimage Job Service
			$wsdl = "http://remote.travelvideostore.com:55555/rmjobservice.svc?wsdl";
			//try creating a soap client to the WSDL
			try
			{
				$jobclient = new SoapClient($wsdl, array("trace" => 1));
			}				
			catch (SoapFault $exception)
			{
				echo $exception->getMessage();
			}
			
			//Perform a GetJobStatuses Request. First build the parameters based on WSDL structure (see html documentation)
			$parms->CallerId = "Rimage Sample";
			$parms->Filter = "All"; //This parameter can be used to filter on "All", "Pending", "Active", "Completed", "Failed", or "Cancelled"
			$parms->CallerOnly = false; //Set to false to see all jobs submitted via the Web Service. True will show only jobs submitted by this client through the Web Service
			
			//Place the parameters into a Request Object
			$status->request = $parms;
			
			//submit the SOAP request to the SOAP client, passing in the Request Object. 
			//Call the method GetJobStatuses passing in the $status object
			$response = $jobclient->GetJobStatuses($status);
			
			//$response should now contain an array of job status arrays. We will parse through them for certain information.
			//Remember, you can output the last response from SOAP to see how the XML is structured to determine how to get a piece of information.
			$arrStatus = $response->GetJobStatusesResult->JobStatuses;
			if ($arrStatus == null)
			{
				echo ("No Job Statuses");
			}
			else if ( !property_exists($arrStatus, "JobStatus") )
			{
				echo ("No Job Statuses");
			}
			else 
			{
				//Build a table to output the data we are collecting.
				echo ("<table><tr><th>Job ID</th>");
				echo ("<th>State</th><th>Stage</th><th>Percent</th></tr>");	
				
				// make a local array in case there is only one object in the 
				// list coming back from web service
				$statusArray = $arrStatus->JobStatus;
				if ( !is_array($arrStatus->JobStatus))
				{
					$statusArray = array();
					$statusArray[0] = $arrStatus->JobStatus;
				}
				
				//cycle through each array in $arrStatus				
				foreach ($statusArray as $status)
				{
					echo ("<tr>");
					echo ("<td>".$status->JobId."</td>");
					echo ("<td>".$status->State."</td>");
					echo ("<td>".$status->Stage."</td>");
					echo ("<td>".$status->PercentComplete."</td>");
					echo ("</tr>");
				}
				echo ("</table>");
			}
			?>
							
			</div>
			<?php
				require('include/footer.php');
			?>
		</div>

	</body>
</html>	