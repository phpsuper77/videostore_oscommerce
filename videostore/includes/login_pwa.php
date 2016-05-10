<td>
<?php
//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================

$checked1 = ($exists==0) ? 'checked' : '';
$checked2 = ($exists==1) ? 'checked' : '';

$returning_customer_title = '&nbsp;&nbsp;&nbsp;';
$returning_customer_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber1\">
  <tr>
      </tr>
  <tr>
    <td width=\"100%\" class=\"smalltext\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td class=\"smalltext\">

<table width=\"70%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">
  <tr>
    <td>".ENTRY_EMAIL_ADDRESS_WHAT."</td>
  </tr>
  <tr>
    <td class=\"smalltext\">" . ENTRY_EMAIL_ADDRESS . "&nbsp;&nbsp;" . tep_draw_input_field('email_address') . "</td>
  </tr>
  <tr>
    <td>".ENTRY_PASSWORD_WHAT."</td>
  </tr>
  <tr>
    <td class=\"smalltext\"><input id='exists_0' type='radio' name='exists' value='0' ".$checked1.">&nbsp;&nbsp;" . ENTRY_NEW_CUSTOMER . "<br></td>
  </tr>
  <tr>
    <td class=\"smalltext\"><input id='exists_1' type='radio' name='exists' value='1' ".$checked2.">&nbsp;&nbsp;" . ENTRY_EXISTS_CUSTOMER . "&nbsp;&nbsp;<input type='password' name='password' maxlength='40' onfocus='document.getElementById(\"exists_1\").checked=true'><br></td>
  </tr>
</table>
<table width=\"30%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\">
  <tr>
        <td align=\"center\" class=\"smalltext\">" . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "<br><br>";
/*$returning_customer_info .= '<div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js"></script>
      <script>
         FB.init({ 
            appId:"132014316878113", cookie:true, 
            status:true, xfbml:true 
         });
      </script>
      <fb:login-button>Login with Facebook</fb:login-button> <br><br>';
*/
$returning_customer_info .= '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br><br><a href='change_email.php'>Has your e-mail address changed since your last order?</a><br><br><a href='info_pages.php?pages_id=41'><font color='blue' style='font-weight: bold'>Email questions</font></a></td>
  </tr>
</table>
</td>
  </tr>
</table>  
";
//===========================================================
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
    <tr>
     <td class="smalltext" width=100% valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_title );
  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_info);
  new infoBox($info_box_contents);
?>
  </td>
 </tr>
</table>
</td>