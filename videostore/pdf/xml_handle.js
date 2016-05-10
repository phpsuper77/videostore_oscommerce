var data_url = "get_catalog.php";


function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
	  xmlhttp.overrideMimeType("text/xml");
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}


var isWorking = false;
var http = getHTTPObject();
var counter = 0;


function GetData(cPath, filter, sort) {
  if (!isWorking && http) {
  http.open("GET", data_url+'?cPath='+cPath+'&filter='+filter+'&sort='+sort+'&rnd='+Math.random(), true);
  http.onreadystatechange = useHttpResponse;
  http.send(null);
 }
} 


function useHttpResponse() {
  if (http.readyState == 4) {
	var xmldoc = http.responseXML;
	var root_node = xmldoc.getElementsByTagName('top')[0];
	if (root_node.getElementsByTagName('errors')[0].firstChild != null)                       
	error = root_node.getElementsByTagName('errors')[0].firstChild.data;
           else
        error = "";                                        

	if (root_node.getElementsByTagName('cPaths')[0].firstChild != null)                       
	cPath = root_node.getElementsByTagName('cPaths')[0].firstChild.data;
           else
        cPath = "";                                        

	if (root_node.getElementsByTagName('filters')[0].firstChild != null)                       
	filt = root_node.getElementsByTagName('filters')[0].firstChild.data;
           else
        filt = "";                                        

	if (root_node.getElementsByTagName('sorts')[0].firstChild != null)                       
	sor = root_node.getElementsByTagName('sorts')[0].firstChild.data;
           else
        sor = "";                                        


	if (error!='NO'){
	document.getElementById('msgChanges').style.display='block';
	document.getElementById('msgChanges').innerHTML='<h1>'+error+'</h1>';
	counter = counter+1;
	if (counter<40) setTimeout('GetData(cPath, filt, sor);',1000);
		else{
		counter = 0;
		document.getElementById('msgChanges').style.display='none';
		alert('Plz, try again later...');
		}
		
	}
		else {

			window.open('pdf/generate_catalog.php?cPath='+cPath+'&filter='+filt+'&sort='+sor);
			}
  }
}

