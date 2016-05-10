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
			<h3>View Alerts</h3>
			<p>Displayed below is a list of the current alerts from the Rimage System.</p>
		<?php
			//Check to see if there are currently any alerts
			
			//Create Variables for the other required information needed by the Image File to Disc job.
			//You can view this in the HTML help for the Rimage Web Service
			$callerId = "Alerts"; //Caller ID

			//first, we must have the built in PHP SOAP extension parse the WSDL file
			//Alerts use the RmSystemService instead of the RMJobService
			try 
			{
				//Create a soap client object, specifying the path to the system where Rimage WebServices is installed.
				//Note the 55555 port is the default, but may change based on your selections during installation. '
				//Test8100n should be changed to reflect the name of the system where webservices was installed. 
				//Tracing is enabled to help with debugging, likely you will not use it in production code. 
				$sclient = new SoapClient('http://remote.travelvideostore.com:55555/RmSystemService.svc?wsdl', array(
					"trace" => 1, "soap_version" => SOAP_1_1)
				);
			}
			catch (SoapFault $ex)
			{
				echo $exception->getMessage();
			}
			//PHP's built in soap extension requires a certain format in order to pass the data to the Web Service
			//We create an object, and specify the values in each object as they appear in the documentation. 
			//We then build an ImageFileToDiscJob request object, which contains the parameters object, and pass that into 
			//the SubmitImageToDiscJob method of the soap client object.
			
			//Note, the names that appear here after the -> MUST be identical to the names in the documentation for 
			//Rimage Web Service API. 
			$parms->CallerId = $callerId;

			//Here is the  object, which can be called anything, as long as it = the name of the object we created above. 
			$job->request = $parms;
			
			//Attempt to submit the GetActiveAlerts request. 
			try
			{
				//call the CleanupFinishedJobs method of the soap client object, passing in the SubmitImageToDiscJob request object.
				//set it equal to a variable if you wish to parse the returned GetActiveAlerts response object. 
				$response = $sclient->GetActiveAlerts($job);
			}
			catch (SoapFault $exception)
			{
				echo $exception->getMessage();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
				
			$arrAlerts = $response->GetActiveAlertsResult->Alerts;
			if ($arrAlerts == null)
			{
				echo ("No active Alerts");
			}
			else
			{
				//check to see if there were any active alerts in the response object. 
				//you can view this structure by outputting the last response header
				//or view the documentation
				//or look at the variable contents at run time if using an IDE with a PHP plugin such as eclipse. 
				
				$alertsArray = $arrAlerts;
				if ( !is_array($arrAlerts))
				{
					$alertsArray = array();
					$alertsArray[0] = $arrAlerts;
				}

				if (count($alertsArray)>0)
				{
					foreach ($alertsArray as $alert)
					{
						if ( !property_exists($alert, "Alert"))
						{
							echo ("No active Alerts");
							break;
						}
						else
						{
							echo ($alert->Alert->Description);
						}
					}
				}
			}
		?>
			
		</div>
		<?php
			require('include/footer.php');
		?>
		</div>

	</body>
</html>	