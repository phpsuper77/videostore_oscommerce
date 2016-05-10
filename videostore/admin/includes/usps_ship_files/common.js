function hideElement(elementId)
{
	if (document.getElementById(elementId) != null)
	{
		document.getElementById(elementId).style.display = 'none';
    	document.getElementById(elementId).style.visibility = 'hidden';
    }
}
function showElement(elementId)
{
	if (document.getElementById(elementId) != null)
	{
		document.getElementById(elementId).style.display = '';
    	document.getElementById(elementId).style.visibility = 'visible';
    }
}
function submitToShoppingCartFromToolBar() {
	document.forms[0].submitControl.value = "ShoppingCartLink";
	document.forms[0].submit();
}

//var actionString = null;
//var batchEnabled = null;

// newFunction
//function submitCustomsForm(form, actionType) {
//	form.actionType.value=actionType;
//	form.submit();
//}
//function submitEditDelete(form, actionType, itemNo) {
//	form.actionType.value=actionType;
//	form.itemID.value=itemNo;
//	form.submit();
//}
//function submitPageView(form, pageValue) {
//	form.actionType.value='page';
//	form.pageNo.value=pageValue;
//	form.submit();
//}

//function Depricated_submitFromSignInLabelInformationPage(form) {
///	var url = "/cns/signInLabelInformation.do?x1=0";
//	if (actionString != null)
//	{
//		url = url + "&Action=" + actionString;
//	}
//	if (batchEnabled != null)
//	{
//		url = url + "&BatchEnabled=" + batchEnabled;
//	}
//	form.action = url;
//	form.submit();
//}

//function doSetParameters(actionP, batchEnabledP)
//{
//	actionString = actionP;
//	batchEnabled = batchEnabledP;
//}

///////////////////////////////////////////////////////////
//Processing... tag functionality for printQuestion.jsp
///////////////////////////////////////////////////////////
function hideProcessingElement(elementId) {
	if (document.getElementById(elementId) != null) {
		document.getElementById(elementId).style.display = 'none' ;
    	document.getElementById(elementId).style.visibility = 'hidden' ;
    }
}

function enableProcessingRadio() {
	hideProcessingElement('Processing') ;
	radioGroup = document.forms[0].submitControl ;
	for (var b = 0; b < radioGroup.length; b++)
      		radioGroup[b].disabled = false ;
}

function processingAction() {
	var the_timeout = setTimeout("enableProcessingRadio();",8000) ;
}
///////////////////////////////////////////////////////////
//End Processing... tag functionality for printQuestion.jsp
///////////////////////////////////////////////////////////
