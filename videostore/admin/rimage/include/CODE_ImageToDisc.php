<?php
ini_set('display_errors', 'On');
 error_reporting(E_ALL);
	//Check if we are coming from the "submit Image to Disc job" type, and perform code to submit a job and display that to user.
	if (isset($_POST['submit_ImageToDiscJob']))
	{
		//collect the variables passed from the form through POST
		$copies = $_POST['copies']; //# of copies from form
		$imgPath = $_POST['imgPath']; //Image path chosen by user. 
		$lblPath = $_POST['lblPath']; //path to Label File from form.
		$media = $_POST['media'];  //Media type selected from form.
				
	$callerId = "Image To Disc Sample"; //Caller ID
	$jobId = time(); //JobID must be unique. For this sample, we use the current time in seconds. 
	$DVDprotect = false; //Rimage Video Protect is false. You must have special hardware to use Video protect. 
				
	try 
	{
		$sclient = new SoapClient('http://remote.travelvideostore.com:55555/RmJobService.svc?wsdl', array(
			"trace" => 1, "soap_version" => SOAP_1_1)
		);
	}
	catch (SoapFault $ex)
	{
		echo $exception->getMessage();
	}

	$parms->CallerId = $callerId;
	$parms->JobId = $jobId;
	$parms->Copies = $copies;
	$parms->MediaType = $media;
	$parms->DvdProtect = $DVDprotect;
	$parms->ImageFile = $imgPath;
	$parms->LabelFile = $lblPath;
				
	$job->request = $parms;
				
	try
	{
		$response = $sclient->SubmitImageToDiscJob($job);
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
	}
?>	