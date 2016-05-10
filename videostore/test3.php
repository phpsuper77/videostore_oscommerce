<script>

var card_type;

function isCreditCard(CC)
{
if (CC.length > 19)
return (false);

sum = 0; mul = 1; l = CC.length;
for (i = 0; i < l; i++)
{
digit = CC.substring(l-i-1,l-i);
tproduct = parseInt(digit ,10)*mul;
if (tproduct >= 10)
sum += (tproduct % 10) + 1;
else
sum += tproduct;
if (mul == 1)
mul=mul+1;
else
mul=mul-1;
}
if ((sum % 10) == 0)
return (true);
else
return (false);
}


function isAmex(cc){
if ((cc.substring(0,2) == 34) || (cc.substring(0,2) == 37))
	return true;
	else
	return false;
}

function checkInput(cc){
	obj = document.getElementById('cc_number1');
	obj2 = document.getElementById('cc_number2');
	obj3 = document.getElementById('cc_number3');
	obj4 = document.getElementById('cc_number4');
	
	obj.value = cc;

	if (isAmex(cc)){
	if (card_type==0){
			obj2.value = 'xxxxxx';enterFirstTimeValue2='xxxxxx';
			obj3.value = 'xxxxx';enterFirstTimeValue3='xxxxx';
			obj4.value = '';		
	}
	
	card_type='1';
	obj.maxLength='4';
	obj2.maxLength='6';	
	obj3.maxLength='5';		
	obj4.style.display='none';
	
	if (obj.value.length==4)  { obj2.focus(); if (obj2.value=='xxxxxx') obj2.value='';}
	if (obj.value.length==15)	document.getElementById('monthes').focus();
	
	}
	else{	
	if (card_type==1){
			obj2.value = 'xxxx'; enterFirstTimeValue2='xxxx';
			obj3.value = 'xxxx'; enterFirstTimeValue3='xxxx';
			obj4.value = 'xxxx'; enterFirstTimeValue4='xxxx';
	}	
	card_type='0';	
	obj.maxLength='4';
	obj2.maxLength='4';	
	obj3.maxLength='4';	
	obj4.maxLength='4';	
	obj4.style.display='';	
	if (obj.value.length==4)  { obj2.focus(); if (obj2.value=='xxxx') obj2.value='';}
	if (obj.value.length==16)	document.getElementById('monthes').focus();	
	
	}	
}

function jumpNext(val){
	obj = document.getElementById('cc_number1');
	obj2 = document.getElementById('cc_number2');	
	obj3 = document.getElementById('cc_number3');
	obj4 = document.getElementById('cc_number4');	
	
	if (isAmex(obj.value)){
		if ((val==2) && (obj2.value.length==6)) { obj3.focus(); if (obj3.value=='xxxxx') obj3.value='';}
		if ((val==3) && (obj3.value.length==5)) document.getElementById('monthes').focus();		
	}
	else{
		if ((val==2) && (obj2.value.length==4)) { obj3.focus();  if (obj3.value=='xxxx') obj3.value='';}
		if ((val==3) && (obj3.value.length==4)) { obj4.focus();  if (obj4.value=='xxxx') obj4.value='';}
		if ((val==4) && (obj4.value.length==4)) document.getElementById('monthes').focus();	
	}

}

function checkDigit() 
{
	if ((event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode == 13))
	{
	return true;
	}
	else
	{
	if((event.keyCode > 45 && event.keyCode < 58) || (event.keyCode > 95 && event.keyCode < 106) || (event.keyCode > 36 && event.keyCode < 41))
	{
	return true;
	}
	else
	{
	return false;
	}
	}
}

function check(){
	cc = document.getElementById('cc_number1').value+document.getElementById('cc_number2').value+document.getElementById('cc_number3').value+document.getElementById('cc_number4').value;
	cc = cc.replace(/x/g,'');
	if (isCreditCard(cc)){
			alert("Credit Card number is correct!\n");
		return true;
		}
		else {
			alert("Wrong Credit Card number!\n");
			return false;
		}
}
</script>

<form>
<script language="JavaScript" type="text/javascript">var enterFirstTime1; var enterFirstTimeValue1 = 'xxxx';</script>
<script language="JavaScript" type="text/javascript">var enterFirstTime2; var enterFirstTimeValue2 = 'xxxx';</script>
<script language="JavaScript" type="text/javascript">var enterFirstTime3; var enterFirstTimeValue3 = 'xxxx';</script>
<script language="JavaScript" type="text/javascript">var enterFirstTime4; var enterFirstTimeValue4 = 'xxxx';</script>
<b>INPUT CC NUMBER</b><br/>
<input type="hidden" name="cc_number" value="" />
<input style="width:60px;" type="text" onkeyup="checkInput(this.value);" maxlength="4" name="cc_number1" value="xxxx" id="cc_number1" onKeyDown="return checkDigit();" onpaste="return false" onfocus="if(!enterFirstTime1){this.value='';enterFirstTime1=true;}" onblur="if(this.value==''){this.value=enterFirstTimeValue1;enterFirstTime1=false;}">
<input style="width:60px;" type="text" onkeyup="jumpNext(2);" maxlength="4" name="cc_number2" value="xxxx" id="cc_number2" onKeyDown="return checkDigit();" onpaste="return false" onfocus="if(!enterFirstTime2){this.value='';enterFirstTime2=true;}" onblur="if(this.value==''){this.value=enterFirstTimeValue2;enterFirstTime2=false;}">
<input style="width:60px;" type="text" onkeyup="jumpNext(3);" maxlength="4" name="cc_number3" value="xxxx" id="cc_number3" onKeyDown="return checkDigit();" onpaste="return false" onfocus="if(!enterFirstTime3){this.value='';enterFirstTime3=true;}" onblur="if(this.value==''){this.value=enterFirstTimeValue3;enterFirstTime3=false;}">
<input style="width:60px;" type="text" onkeyup="jumpNext(4);" maxlength="4" name="cc_number4" value="xxxx" id="cc_number4" onKeyDown="return checkDigit();" onpaste="return false" onfocus="if(!enterFirstTime4){this.value='';enterFirstTime4=true;}" onblur="if(this.value==''){this.value=enterFirstTimeValue4;enterFirstTime4=false;}">
<select name="cc_expires_month" id="monthes"><option value="01">01 January</option><option value="02">02 February</option><option value="03">03 March</option><option value="04">04 April</option><option value="05">05 May</option><option value="06">06 June</option><option value="07">07 July</option><option value="08">08 August</option><option value="09">09 September</option><option value="10">10 October</option><option value="11">11 November</option><option value="12">12 December</option></select>&nbsp;<select name="cc_expires_year"><option value="07">2007</option><option value="08">2008</option><option value="09">2009</option><option value="10">2010</option><option value="11">2011</option><option value="12">2012</option><option value="13">2013</option><option value="14">2014</option><option value="15">2015</option><option value="16">2016</option></select>
<input type="button" value="Check Number" onclick="check();">
</form>
