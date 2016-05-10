<?php
/*
    
  $Id: keyword_show.php, v1.2 2003/07/13 ola svensson Exp $
  
  osCommerce 2.2 Milestone 2

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
*/
  require('includes/application_top.php');
   
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="smallText">
<?php

echo EXPLANATION, "<p>";
// Attributes to <FONT> tags
$fonttag="size=-2";


$a=ADMIN_CONFIG_KEYWORD_SHOW;
$v=OSC_CONFIG_KEYWORD_SHOW;
$ip=CONFIG_KEYWORD_SHOW_EXCLUDED;
$logfile=KEYWORD_SHOW_LOG_PATH;

if($n==""){
	
	$n="google|googlede|googlefr|googlejp|googleother|yahoo|altavista|other|googlebot";
}
$n=strtolower($n);
$display=preg_split("/\|/",$n);
        print "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=C9C9C9 >\n";     

foreach($display as $page){
	$IPs=Array();
	$times=Array();
	$links=Array();
	$keywords=Array();
	$lines=Array();
	$robots=Array();
	
	if($page=="google"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.google.com/\">Google</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                                             
                                    
                if(($v== 'true') && (preg_match("/google.com\/search?/i",$referrer)))
                                     
                 {
                   
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
                 
                }elseif(($v== 'false') && (preg_match("/google.com\/search?/i",$referrer)) && (preg_match("/$ip/i",$IP))) 
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
              
                }
                gzclose ($fd);
	
	}elseif($page=="googlede"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.google.de/\">Google Germany</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                              
                if(($v== 'true') && (preg_match("/google.de\/search?/i",$referrer)))
                 {
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);               
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }elseif(($v== 'false') && (preg_match("/google.de\/search?/i",$referrer))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
               
                }
   
                }
      
                gzclose ($fd);
	}elseif($page=="googlefr"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.google.fr/\">Google France</a></td></tr>";
		
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
           

                if(($v== 'true') && (preg_match("/google.fr\/search?/i",$referrer)))
                {
              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
               $keyword = urldecode($out[1]);  
              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
             
                }elseif(($v== 'false') && (preg_match("/google.fr\/search?/i",$referrer))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
               
                }
                gzclose ($fd);
        }elseif($page=="googlejp"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.google.co.jp/\">Google Japan</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                              
           
                if(($v== 'true') && (preg_match("/google.co.jp\/search?/i",$referrer)))
                 {
              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]); 
                            
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
                              
                
                }elseif(($v== 'false') && (preg_match("/google.co.jp\/search?/i",$referrer))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
                
                }
              
                gzclose ($fd);
        }elseif($page=="googleother"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.google.au/\">Other Googles</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                 
                       
                if(($v== 'true') && ((preg_match("/google.(.+?)\/search?/i",$referrer)) && (!preg_match("/google.com\/search?/i",$referrer)) && (!preg_match("/google.de\/search?/i",$referrer)) && (!preg_match("/google.fr\/search?/i",$referrer)) && (!preg_match("/google.co.jp\/search?/i",$referrer))))
                                        
                 {
              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]); 
                            
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
                              
                
                }elseif(($v== 'false') && ((preg_match("/google.(.+?)\/search?/i",$referrer)) && (!preg_match("/google.com\/search?/i",$referrer)) && (!preg_match("/google.de\/search?/i",$referrer)) && (!preg_match("/google.fr\/search?/i",$referrer)) && (!preg_match("/google.co.jp\/search?/i",$referrer)))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
                
                }
              
                gzclose ($fd);
	}elseif($page=="yahoo"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.yahoo.com/\">Yahoo</a></td></tr>";
		      $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                           
                if(($v== 'true') && ((preg_match("/search.yahoo.com\/search?/i",$referrer))||(preg_match("/search.yahoo.com\/bin\/search?/i",$referrer))))
                 
                 {
                
                preg_match("/[q|p|&va]=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]); 
                
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
            
                }elseif(($v== 'false') && ((preg_match("/search.yahoo.com\/search?/i",$referrer))||(preg_match("/search.yahoo.com\/bin\/search?/i",$referrer)))&& (preg_match("/$ip/i",$IP)))
                                   
                {             
                
                preg_match("/[q|p]=(.+?)[&\"]/i",$referrer,$out);
                
                $keyword = urldecode($out[1]);     
                           
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
              
                }
                         
                gzclose ($fd);
        }elseif($page=="altavista"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://www.altavista.com/\">Altavista</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                              
           
                if(($v== 'true') && (preg_match("/altavista.com\/search?/i",$referrer)))
                 {
               
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]); 
                             
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
                
                }elseif(($v== 'false') && (preg_match("/altavista.com\/search?/i",$referrer))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                              
                preg_match("/q=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
                
                }
                gzclose ($fd);
   }elseif($page=="other"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Keywords, search from </b><a href=\"http://search.msn.com/\">Other search engines</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer , ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                              
           
                if((($v== 'true') && ((preg_match("/search.com\//i",$referrer)) || (preg_match("/search.msn.com\//i",$referrer)) || (preg_match("/websearch.com\//i",$referrer)) || (preg_match("/searchreply.com\//i",$referrer)) || (preg_match("/search.msn.com(.+?)\//i",$referrer)))))
                 {
               
                preg_match("/[q|qkw]=(.+?)[&\"]/i",$referrer,$out) || preg_match("/keywords=(.+?)[&\"]/i",$referrer,$out) || preg_match("/searchfor=(.+?)[&\"]/i",$referrer,$out);  
                     
                $keyword = urldecode($out[1]); 
                             
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
                
                }elseif((($v== 'false') && ((preg_match("/search.com\//i",$referrer)) || (preg_match("/search.msn.com\//i",$referrer)) || (preg_match("/websearch.com\//i",$referrer)) || (preg_match("/searchreply.com\//i",$referrer)) || (preg_match("/search.msn.com(.+?)\//i",$referrer))))&& (!preg_match("/$ip/i",$IP)))
                                   
                {             
                
                preg_match("/[q|qkw]=(.+?)[&\"]/i",$referrer,$out) || preg_match("/keywords=(.+?)[&\"]/i",$referrer,$out) || preg_match("/searchfor=(.+?)[&\"]/i",$referrer,$out);
                                     
                $keyword = urldecode($out[1]);     
                              
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
                }
                
                }
                gzclose ($fd);

 }elseif($page=="googlebot"){
		$news="<tr><td class=\"dataTableHeadingContent\" nowrap><b>Visits from Googlebot &nbsp;&nbsp;&nbsp;</b><a href=\"http://www.googlebot.com/\">Googlebot</a></td></tr>";
		       $fd = @gzopen("$logfile","r"); 
                while ($x = gzgets($fd,1024)) { 
                list($IP , , ,$time, , ,$link , , , , $referrer ,$robot ) = explode(" ", $x);
                $time=preg_replace("/\[/","",$time);
                              
           
                if(preg_match("/Googlebot/i",$robot))
        
                 {
             
                
                     
                $keyword = urldecode($robot); 
                
                array_push($IPs, $IP);
                array_push($times, $time);
                array_push($links, $link);
                array_push($keywords, $keyword);
              
             
                }
                
                }
	}
	$i=0;
        $j=$j+count($keywords);
             
        print "<tr><td colspan=2 align=center><font ".$fonttag.">".$news."</font></td></tr>\n";

	while($i<count($keywords)){

?>     
         
          <tr bgcolor=ffffff> 
            
            
<?php           



            if(($a== 'true') && (preg_match("/$ip/i",$IPs[$i])))
            {
?>          <td class="dataTableContent" nowrap><?php echo ($times[$i]); ?></td>             
            <td class="dataTableContent" align="left"><?php echo "<font color=red>" . ($IPs[$i]) . '</font>'; ?></td>
            <td class="dataTableContent" align="left"><?php echo trim($links[$i]); ?></td> 
            <td class="dataTableContent" align="left"><?php echo trim($keywords[$i]); ?></td>
<?php
               
            }
            
            elseif(!preg_match("/$ip/i",$IPs[$i]))
            
            {
?>          <td class="dataTableContent" nowrap><?php echo ($times[$i]); ?></td>  
            <td class="dataTableContent" align="left"><?php echo ($IPs[$i]); ?></td>         
            <td class="dataTableContent" align="left"><?php echo trim($links[$i]); ?></td> 
            <td class="dataTableContent" align="left"><?php echo trim($keywords[$i]); ?></td>
<?php
            }            
?>            

          </tr> 
          
<?php


	      $i++;

	}

}

?>

        <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr> 
      <td valign="top" align=center><table border="0" width="95%" cellspacing="0" cellpadding="2"> 
           
 
       <tr> 
        <td class="smallText" colspan="7"><?php echo " Total number of keyword searches and Googlebot visits: " . $j . "."; ?></td> 
       </tr> 
      </table></td> 
     </tr> 
    </table> 
   </td> 
   </tr> 
  </table></td> 
<!-- body_text_eof //--> 
 </tr> 
</table> 
<!-- body_eof //--> 

<!-- footer //--> 
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?> 
<!-- footer_eof //--> 
<br> 
</body> 
</html> 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>