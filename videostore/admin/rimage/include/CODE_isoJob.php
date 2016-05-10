#!/usr/bin/php
<?php

$page_str="script/scanning_server/rimage_image_to_disk";
$wsdl = 'http://remote.travelvideostore.com:55555/RmJobService.svc?wsdl';

try 
{
	$sclient = new SoapClient($wsdl, array(
		"trace" => 1, "soap_version" => SOAP_1_1));
}
catch (SoapFault $ex)
{
	echo $ex->getMessage();
}

$job_id = time();
$caller_id = "Test Rimage";
$image_path = 'C:\Rimage\CD-R_Images\rimage_test_job.img';
$label_path = 'C:\Rimage\Labels\GoPhoto DVD Label Template.btw';

$merge->MergeFile->MergeFilePath = 'C:\Rimage\merge\merge1.txt';
$merge->MergeFile->DeleteMergeFileAfterPrinting = false;
$merge->MergeFile->MergeFileHasFieldNames = true; 

$parms->CallerId = $caller_id;
$parms->JobId = 'test_'.$job_id;
$parms->Files = array();
$parms->Files[] = 'C:\Rimage\merge';
$parms->ImageFile = $image_path;
$parms->UdfVersion = 'None'; //UdfVersion.None;
$parms->IsoLevel = 'Joliet'; //IsoLevel.Joliet;
$parms->AppleFormat = 'None'; //Apple.None;
$parms->AllowSpanning = true;
$parms->ImageFileSize = "Cdr80";
$parms->Copies = 1;
$parms->LabelFile = $label_path;
$parms->Merge = $merge;

$job->request = $parms;

echo ("\nJob Started: test_".$job_id."\n");

try
{
    echo "Start SubmitIsoJob\n";
	$response = $sclient->SubmitIsoJob($job);
  
    echo 'print_r';
    print_r($response);
    echo "\n";
    
    if ($response == null)
    {
        echo "SubmitIsoJob response is null.\n";
    }
    else
    {
        if (($response->ErrorCode == 0) && ($response->ErrorMessage == null))
        {
            echo "SubmitIsoJob succeeded, JobId = " . $response->SubmitIsoJobResult->JobStatus->JobId . "\n";
        }
        else
        {
            echo "SubmitIsoJob error: " . $response->SubmitIsoJobResult->ErrorCode + " : " . $response->SubmitIsoJobResult->ErrorMessage . "\n";
        }
    }
} 
catch (SoapFault $exception)
{
	echo $exception->getMessage();
}
catch (Exception $e)
{
	echo $e->getMessage();
}


$parms = null; 
$parms->CallerId = $caller_id;
$parms->JobId = 'test_'.$job_id;

$job->request = $parms;

$i = 0;
$status_complete = false;
$intMaxRetries = 80;

while((!$status_complete) && ($i < $intMaxRetries))
{
  try
  {
    $response = $sclient->GetJobStatus($job);
    $status = $response->GetJobStatusResult->JobStatus->Stage;
    
    
    if ((strpos($status,'COMPLETED') !== false) || (strpos($status,'DONE') !== false))
      $status_complete = true;
    else if (strpos($status,'FAILED') !== false)
      $status_complete = true;
    else
    {
      echo "Sleeping...\n";
      sleep(10);
    }
    $i++;
    
    echo "Current Job(test_$job_id) Status: $status - $i\n";
  } 
  catch (SoapFault $exception)
  {
  	echo $exception->getMessage();
    $i = $intMaxRetries;
  }
  catch (Exception $e)
  {
  	echo $e->getMessage();
    $i = $intMaxRetries;
  }
}

echo "\nDone\n";
script_finish(); // see main.inc, logs things, wraps up.
exit();

?>
