
var eg_width = __width__;
var eg_height = __height__;
var eg_bordercolor = '__bordercolor__';
var eg_bgTitle = '__bgtitle__';
var eg_htmlfile = '__url__';
var eg_overlayurl = '__overlayurl__';
var eg_timeout = 0;
var eg_disable_scrollbar = false;

////////////////////////////////////////////////////////////
//// DO NOT EDIT BELOW THIS LINE ///////////////////////////
////////////////////////////////////////////////////////////
var myWidth = 0;
var myHeight = 0;
var opened = false;
var is_in = false;
var egwind = null;
var eg_overlay = null;
var eg_x = 100;
var eg_y = 100;
var nrp = -1;
var nrp_show = 1;
var autoclose = null;

function sformat()
{
	if( arguments.length == 0 ) { return null; }
	var str = arguments[0];
	for(var i=1;i<arguments.length;i++)
	{
		var re = new RegExp('\\{' + (i-1) + '\\}','gm');
		str = str.replace(re, arguments[i]);
	}
	return str;
};

function mmove(e)
{
	if(opened) return true;
	if( typeof( window.innerWidth ) == 'number' ) {
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) 	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}

	eg_x = myWidth/2 - eg_width/2;
	eg_y = myHeight/2 - eg_height/2 + document.body.scrollTop;
	if(posy < 10 + document.body.scrollTop){
		if(!is_in) nrp++;
		is_in = true;
		openWindow();
		return true;
	}
	else {
		is_in = false;
	}
	return true;
};


function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
};

addLoadEvent( eg_init ); 
function eg_init() {
	document.onmousemove = mmove;

	var y = document.createElement('div');
	y.setAttribute('id','eg_overlay');
	document.body.appendChild(y);

	var x = document.createElement('div');
	x.setAttribute('id','egwind');
	y.appendChild(x);

	x.style.position = 'absolute';
	x.style.top =  '-1000px';
	x.style.left =  '-1000px';
	x.style.background = '#ffffff';
	x.style.border = '1px solid '+eg_bordercolor;
	x.style.width = eg_width+'px';
	x.style.height = eg_height+'px';
	x.style.display = 'block';
	var xtext = "<div style='border:1px solid "+eg_bordercolor+" !important;text-align:right !important;background:"+eg_bgTitle+" !important;padding:5px !important;'><a href='#' style='font-family:verdana !important;font-weight:bold !important;font-size:13px !important;color:#990000 !important;' onClick='closeWindow(); return false;'>[Close]</a></div>";
	xtext += "<iframe style='border:1px solid "+eg_bordercolor+";background:white;' id='eg_iframe' name='eg_iframe' border='0' src='"+eg_htmlfile+"' width='"+(eg_width-2)+"' height='"+(eg_height-30)+"'></iframe>";
	x.innerHTML = xtext;
	egwind = x;
	eg_overlay = document.getElementById('eg_overlay');
};

function openWindow()
{
	if(opened) return false;
	if( nrp%nrp_show != 0 ) { opened = false; return false; }
	opened=true;

	var x= egwind;
	x.style.top = eg_y + 'px';
	x.style.left = eg_x + 'px';
	x.style.display = 'block';

	var y = eg_overlay;
	y.style.position = 'absolute';
	y.style.top = '0px';
	y.style.left = '0px';
	y.style.width = document.body.scrollWidth+'px';
	y.style.height = document.body.scrollHeight+'px';
	y.style.zIndex = '999';
	y.style.display = 'block';
	y.style.background = "url('"+eg_overlayurl+"')";
	document.body.style.height = '100%';
	if(eg_disable_scrollbar) document.body.style.overflow = 'hidden';
	if(eg_timeout > 0) { autoclose = setTimeout("closeWindow()", eg_timeout); }
	return false;
};

function closeWindow()
{
	opened = false;
	egwind.style.display = 'none';
	eg_overlay.style.display = 'none';
	if(eg_disable_scrollbar) document.body.style.overflow = 'auto';
	if(autoclose) clearTimeout(autoclose);
	return false;
};
