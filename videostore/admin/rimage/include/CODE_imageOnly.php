<?php
	$callerId = "Image Only Sample"; //Caller ID
	$jobId = time(); //JobID must be unique. For this sample, we use the current time in seconds. 

	try 
	{
		$sclient = new SoapClient('http://remote.travelvideostore.com:55555/RmJobService.svc?wsdl', array(
			"trace" => 1, "soap_version" => SOAP_1_1));
	}
	catch (SoapFault $ex)
	{
		echo $exception->getMessage();
	}
				
	$parms->CallerId = $callerId;
	$parms->JobId = $jobId;
	$ParentFolders = array();
	$ParentFolders[0] = "C:\\rimage\\merge";
	$parms->Files = $ParentFolders;
	$imgPath = "C:\\Rimage\\CD-R_Images\\ImageOnlyVanishingPHP.img";
	$parms->ImageFile = $imgPath;
	$parms->AllowSpanning = "true";
    $parms->UdfVersion = "None";
    $parms->IsoLevel = "Joliet";
    $parms->IsoApple = "None";
				
	//Here is the  object, which can be called anything, as long as it = the name of the object we created above. 
	$job->request = $parms;
				
	try
	{
		$response = $sclient->SubmitImageOnlyJob($job);
	}
	catch (SoapFault $exception)
	{
		echo $exception->getMessage();
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}

	echo ("<p>Job ".$jobId." submitted successfully with the following parameters.<br />");
	echo ("Image Path: ".$imgPath."<br />");
	echo ("Label Path: ".$lblPath."<br />");
	echo ("Media ".$media."<br />");
	echo ("Copies: ".$copies."<br /></p>");
?>	