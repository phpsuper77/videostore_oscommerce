<!--
// ----------------------------------------------------------------------------------------------------------------------------------------
// This section contains miscellaneous browser related routines and variables.
// You DON'T need to make any changes to this file.
// ----------------------------------------------------------------------------------------------------------------------------------------

var browserName      = "";
var browserVersion   = "";
var browserWidth     = 0;
var browserHeight    = 0;
var browserSupported = true;

   detectBrowser ();
   detectBrowserSize ();
   testBrowserVersion ();

// ----------------------------------------------------------------------------------------------------------------------------------------
// detects browser name and version
// ----------------------------------------------------------------------------------------------------------------------------------------

function detectBrowser ()
{
	browserName = navigator.appName;

	if (navigator.userAgent.indexOf ("Opera") > -1)
	{
		browserName = "Opera";
		var index = navigator.userAgent.indexOf ("Opera");
		browserVersion = extractBrowserVersion (navigator.userAgent.substring (index + 5));
	}

	else if (navigator.userAgent.indexOf ("MSIE") > -1)
	{
		browserName = "Internet Explorer";
		var index = navigator.userAgent.indexOf ("MSIE");
		browserVersion = extractBrowserVersion (navigator.userAgent.substring (index + 4));
	}

	else if (navigator.vendor == "Netscape6" || navigator.vendor == "Netscape")
	{
		browserName = "Netscape";
		browserVersion = navigator.vendorSub;
	}

	else if (navigator.userAgent.indexOf ("Gecko") > -1)
	{
		browserName = "Mozilla";
		var index = navigator.userAgent.indexOf ("rv:");
		browserVersion = extractBrowserVersion (navigator.userAgent.substring (index + 3));
	}

	else if (navigator.appName.indexOf ("Netscape") != -1)
	{
		browserName = "Netscape";
		var index = navigator.appVersion.indexOf (" ");
		browserVersion = navigator.appVersion.substring (0, index);
	}
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// extracts browser version
// ----------------------------------------------------------------------------------------------------------------------------------------

function extractBrowserVersion (browserVersion)
{
	browserVersion = trim (browserVersion);

	index = browserVersion.indexOf (";");
	if (index > -1)
		browserVersion = browserVersion.substring (0, index);
	else
	{
		index = browserVersion.indexOf (")");
		if (index > -1)
			browserVersion = browserVersion.substring (0, index);
		else
		{
			index = browserVersion.indexOf (" ");
			if (index > -1)
				browserVersion = browserVersion.substring (0, index);
		}
	}

	return browserVersion;
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// tests browser name and version
// ----------------------------------------------------------------------------------------------------------------------------------------

function testBrowserVersion ()
{
	if (! ((browserName == "Internet Explorer" && browserVersion >= "4.0") ||
	       (browserName == "Netscape"          && browserVersion >= "4.0") ||
	       (browserName == "Mozilla"           && browserVersion >= "1.0") ||
	       (browserName == "Opera"             && browserVersion >= "5.0")))
	{
		browserSupported = false;
		alert ("Warning, you are using " + browserName + " Version " + browserVersion + "\n"
			+ "The recommended browsers to view this page are:\n"
			+ "- Internet Explorer 4.0 or better\n"
			+ "- Netscape 4.0 or better\n"
		);
	}
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// detects browser size
// ----------------------------------------------------------------------------------------------------------------------------------------

function detectBrowserSize ()
{
	if (browserName == "Internet Explorer")
	{
		browserWidth  = document.body.clientWidth; // + document.body.scrollLeft;
		browserHeight = document.body.clientHeight; // + document.body.scrollTop;

		if (browserVersion >= "6.0" && browserHeight < screen.availHeight)
		{
			browserHeight = screen.availHeight;
		}
	}

	else if (browserName == "Netscape" || browserName == "Mozilla" || browserName == "Opera")
	{
		browserWidth = window.innerWidth;
		browserHeight = window.innerHeight;
	}
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// This section contains miscellaneous utility functions.
// You DON'T need to make any changes to this file.
// ----------------------------------------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------------------------------------
// utility method that trims input string off of blank spaces to left and right.
// ----------------------------------------------------------------------------------------------------------------------------------------

function trim (str)
{
	while (str.length != 0 && isSpace (str.charAt (0))) // trim to the left
		str = str.substring (1, str.length);

	while (str.length > 0 && isSpace (str.charAt (str.length - 1))) // trim to the right
		str = str.substring (0, str.length - 1);

	return str;
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// utility method that checks that the input ASCII code is a blank space (space, tab, new line, or &nbsp;).
// ----------------------------------------------------------------------------------------------------------------------------------------

function isSpace (c)
{
	return (c == ' ' || c == '\t' || c == '\n' || c == '\xA0');
}

// ----------------------------------------------------------------------------------------------------------------------------------------
// utility method that opens a browser window with the proper parameters depending on the browser version.
// ----------------------------------------------------------------------------------------------------------------------------------------
// tested on Netscape 2.02
// tested on Netscape 3.01, 3.01B1, 3.02, 3.03, 3.04
// tested on Netscape 4.03, 4.05, 4.08, 4.5, 4.51, 4.61, 4.7, 4.72, 4.73, 4.74, 4.75, 4.76, 4.77, 4.78, 4.79, 4.8
// tested on Netscape 6.01, 6.1, 6.2, 6.2.1, 6.2.2, 6.2.3
// tested on Netscape 7.0
// tested on Mozilla 1.0.1, 1.1, 1.2B
// tested on Opera 6.05, 7.0
// tested on Internet Explorer 5.0, 5.5, 6.0
// ----------------------------------------------------------------------------------------------------------------------------------------
// BUGS: in Netscape 6.x, if locationbar=1, then resizable can only be 0
// BUGS: in Netscape 2.x and 3.x and 4.x, no space is allowed after the commas
// BUGS: in Netscape 2.x and 3.x, window locations cannot be set
// BUGS: in Netscape 2.x, only unresizable windows can be created
// BUGS: in Opera 6.x, cannot hide menubar
// BUGS: in Opera 6.x, cannot hide toolbar
// BUGS: in Opera 6.x, cannot hide the status bar
// BUGS: in Opera 6.x, hiding location bar also hides all other chrome
// BUGS: in Opera 6.x and 7.x, cannot create non-resizable windows (all windows are resizable)
// BUGS: in Opera 7.x, cannot hide scrollbars if they are needed
// BUGS: in Opera 7.x, the window location is relative to the MDI container instead of the screen
// BUGS: in Opera 7.x, the toolbar is not located in the window
// BUGS: in Opera 7.x, the menubar is not located in the window
// BUGS: in Opera 7.x, there is no statusbar
// ----------------------------------------------------------------------------------------------------------------------------------------

function openWindow (url, name, size, x, y, parent, attributes)
{
	// ----------------------------------------------------------------------------------------------------------------------------------
	// compute location of window relative to parent
	// ----------------------------------------------------------------------------------------------------------------------------------

	if (parent != null && typeof (parent) != "undefined")
	{
		if (browserName == "Internet Explorer")
		{
			x += parent.screenLeft;
			y += parent.screenTop;
		}
		else
		{
			x = x + parent.screenX + parent.outerWidth - parent.innerWidth;
			y = y + parent.screenY + parent.outerHeight - parent.innerHeight;
		}
	}

	// ----------------------------------------------------------------------------------------------------------------------------------
	// create location attributes
	// ----------------------------------------------------------------------------------------------------------------------------------

	var location = "";
	if (browserName == "Netscape")
	{
		location = ",screenX=" + x + ",screenY=" + y;
	}
	else
	{
		location = ",left=" + x + ",top=" + y;
	}

	// ----------------------------------------------------------------------------------------------------------------------------------
	// create and return window
	// ----------------------------------------------------------------------------------------------------------------------------------

	if (attributes != null && attributes != "" && typeof (attributes) != "undefined")
	{
		return window.open (url, name, size + location + "," + attributes);
	}
	else
	{
		return window.open (url, name, size + location);
	}
}
//-->
function openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
