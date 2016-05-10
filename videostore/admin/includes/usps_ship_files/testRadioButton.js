function radioButtonSelected (theRadioButton) 
{
	returnVal = false;
	if (!theRadioButton.length) 
  		returnVal = true;
	for (i=0;i<theRadioButton.length;i++)
	{
		if (theRadioButton[i].checked)
			returnVal = true;
	}
	return returnVal;
}

function getRadioValue (theRadioButton)
{
	if (!theRadioButton.length && theRadioButton.checked)
		return theRadioButton.value;
	else
	{
		for (i=0; i < theRadioButton.length; i++)
		{
			if (theRadioButton[i].checked)
				return theRadioButton[i].value;
		}
	}
	return "";
}

function radioButtonSelectedValue (theRadioButton)
{
	if (!theRadioButton.length && theRadioButton.checked)
                return theRadioButton.value;
        else
        {
                for (i=0; i < theRadioButton.length; i++) 
                {
                        if (theRadioButton[i].checked)
				return theRadioButton[i].value;
                }
        }
        return null;
}
