<HTML>
<HEAD>
 <TITLE>www.travelvideostore.com</TITLE>
 <script language='javascript'>
   var arrTemp=self.location.href.split("?");
   var picUrl = (arrTemp.length>0)?arrTemp[1]:"";
   var NS = (navigator.appName=="Netscape")?true:false;

     function FitPic() {
       iWidth = (NS)?window.innerWidth:document.body.clientWidth;
       iHeight = (NS)?window.innerHeight:document.body.clientHeight;
       iWidth = document.images[0].width - iWidth;
       iHeight = document.images[0].height - iHeight;
       window.resizeBy(iWidth, iHeight+4);
       self.focus();
     };

 </script>
</HEAD>
<BODY bgcolor="#FFFFFF" onload='FitPic();' topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">
 <script language='javascript'>
 document.write( "<img src='images/" + picUrl + "' border=0>" );
 </script>
</BODY>
</HTML>

