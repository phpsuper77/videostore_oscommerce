function checkFields_log() {
			var returned = false;
			var form = document.forms["login"];
			returned = checkNames_log();
			if (returned == false)
			{ 
				return false;
			}
			return true; 
		}
function checkNames_log() {
			var form = document.forms["login"];
				if (form["userID"].value == "")
				{
					alert ("User Name Required");
					form["userID"].focus();
					return false;
				}
				else if (form["password"].value == "")
				{
					alert ("Password Required");
					form["password"].focus();
					return false;
				}
				else
				{
					return true;
				}
			}
//check Fields for blanks when creating users. 
function checkFields_user() {
			var returned = false;
			var form = document.forms["createUser"];
			returned = checkNames_user();
			if (returned == false)
			{ 
				return false;
			}
			return true; 
	}
		function checkNames_user() {
			var form = document.forms["createUser"];
				if (form["userID"].value == "")
				{
					alert ("User Name Required");
					form["userID"].focus();
					return false;
				}
				else if (form["password"].value == "")
				{
					alert ("Password Required");
					form["password"].focus();
					return false;
				}
				else
				{
					return true;
				}
			}
//check fields for blanks when creating new job. 
			function checkFields_job() {
			var returned = false;
			var form = document.forms["ImageToDiscJob"];
			returned = checkNames_job();
			if (returned == false)
			{ 
				return false;
			}
			return true; 
		}
		function checkNames_job() {
			var form = document.forms["ImageToDiscJob"];
				if (form["copies"].value == "")
				{
					alert ("Number of Copies required");
					form["copies"].focus();
					return false;
				}

				else if (form["imgFile"].value == "")
				{
					alert ("Image Path Required");
					form["imgFile"].focus();
					return false;
				}
				else if (form["lblFile"].value == "")
				{
					alert ("Label Path Required");
					form["lblFile"].focus();
					return false;
				}
				else
				{
					return true;
				}
			}