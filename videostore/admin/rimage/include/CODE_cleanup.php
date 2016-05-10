<?php	
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
	$callerId = "Cleanup"; 
	$parms->CallerId = $callerId;
	
	$job->request = $parms;
	
	try
	{
		$response = $sclient->CleanupFinishedJobs($job);
	}
	catch (SoapFault $exception)
	{
		echo $exception->getMessage();
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}

	echo ("<p>Successfully removed jobs from the WebService</p>");
?>	