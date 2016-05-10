// This is an IBM-authored function -- not provided by eCap
function startCountDown(timeOutPage)
{
	// warn user 13 minutes after page is loaded -- 2 minutes before
	// WebSphere and eCap sessions time out (13*60*1000)
	setTimeout("sessionTimeOutWarning('"+timeOutPage+"')", 13*60*1000);
}
function sessionTimeOutWarning(timeOutPage)
{
	// redirect to timed out page in 2 minutes (2*60*1000)
	setTimeout("window.location.href='"+timeOutPage+"'", 2*60*1000);
	beforeWarningTime = new Date().getTime();
	if (window.showModalDialog)
		window.showModalDialog("https://sss-web.usps.com/cns/html/timedOutWarning.html",null,"dialogHeight:125px;dialogWidth:600px;resizable:0;status:0;help:0;center:1");
	else
		warningWin=window.open("https://sss-web.usps.com/cns/html/timedOutWarning.html","","height=125,width=600,left=200,top=300,resizable=0,status=0");
	afterWarningTime = new Date().getTime();
	// if user doesn't dismiss warning dialog within 2 minutes, this will
	// redirect them promptly
	if (afterWarningTime-beforeWarningTime > 2*60*1000)
		window.location.href=timeOutPage;
}
