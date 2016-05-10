<?php
	//ensure we are coming from the Submit Custom Job page
	if (isset($_POST['submit_customJob']))
	{
		//A Custom job utilizes the RmJobService
		//Looking at the documentation for a SubmitJobRequest, we see that each job requires to pieces of information
		//string CallerId and a Job	of custom type Job
		
		//Create Variables to hold these items
		
		$CallerId = "PHP Sample Custom";
		$Job = "";
		
		//We will use these varibales below:
		$ProductionOptions = ""; //Production Options Object
		$Label = ""; //Label file object
		$ImagingOptions = ""; //Imaging Options Object
		$IsoOptions = ""; //Object to hold the ISO options of the ImagingOptions
		$ParentFolders = ""; //Object to hold the Parent Folders for the job content to be burned to disc. 

		$Job->JobId = time(); // Must be a unique identifier
		$Job->Type = "ImageAndRecord";
		$Job->Priority = "Normal";
		//Production Options consist of the Job settings for this job
	
		$ProductionOptions->MediaType = "Cdr";
		$ProductionOptions->Copies = 1;
		$ProductionOptions->FixateType = "Sao";

		//LabelType consists of the following:
		$Label->LabelFilePath = "C:\\rimage\\quickdisctemplates\\0409\\beta_everest.btw";
		$Label->LabelType = "Btw"; // must specify whether a BTW or PDF label.
		//MUST set the Perfect Print angle to -1 in order to not use Perfect Print
		$Label->PerfectPrintAngle = -1;
		$ProductionOptions->LabelFile = $Label;
		//other label settings are optional.
		
		//Add the Production Options to the Job Object 
		$Job->ProductionOptions = $ProductionOptions;
		
		//Imaging Options consist of the Imaging Options for this job.
		$ImagingOptions->VolumeName = "PHP Sample Custom";

		//ISO Options Object. ISO level is required. The rest of the settings are optional. 
		$IsoOptions->Level = "Level2";
		//Add the ISO options to the Imaging Options Object
		$ImagingOptions->IsoOptions = 	$IsoOptions;
		$ImagingOptions->AllowSpanning = true;
		
		//build the Parent Folder(s) for content
		//Build Array of File Paths
		$ParentFolder = array();
		$ParentFolder[0]->ParentFolderPath = "C:\\rimage\\xml";
		$ParentFolders = $ParentFolder;
				
		//Add parent folder path to the Imaging Options Object
		$ImagingOptions->ParentFolders = $ParentFolders;
		$ImagingOptions->ImageFile = "C:\\Rimage\\cd-r_Images";
		
		//Add the Imaging Options to the Job
		$Job->ImagingOptions = $ImagingOptions;

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
		
		$parms->CallerId = $CallerId;
		$parms->Job = $Job;
		
		//Here is the  object, which can be called anything, as long as it = the name of the object we created above. 
		$JobRequest->request = $parms;
		try
		{
			$response = $sclient->SubmitJob($JobRequest);
		}
		catch (SoapFault $exception)
		{
			echo $exception->getMessage();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}

		echo ("<p>Job ".$Job->JobId." submitted successfully.<br />");
	}
?>