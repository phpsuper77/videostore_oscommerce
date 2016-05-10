<td>
<script language="javascript">
function submit_form()
{
	document.login.submit();
}
</script>
<?php
//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
$returning_customer_title = TEXT_RETURNING_VENDOR;
$returning_customer_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"50%\" id=\"AutoNumber1\" align=\"center\">
  <tr>
      </tr>
  <tr>
    <td width=\"100%\" class=\"smalltext\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td class=\"smalltext\">

<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">
  <tr>
    <td class=\"smalltext\">" . ENTRY_USERNAME . "</td>
    <td>" . tep_draw_input_field('email_address') . "</td>
  </tr>
  <tr>
    <td class=\"smalltext\">" . ENTRY_PASSWORD . "<br><br></td>
        <td>" . tep_draw_password_field('password') . "<br><br></td>
  </tr>
</table>
<tr>
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\">
  <tr>
        <td align=\"center\" class=\"smalltext\">" . tep_image_submit_vendors(DIR_WS_INCLUDES_LOCAL.'images/button_login.gif', IMAGE_BUTTON_LOGIN)."<br><br></td>
  </tr>
</table>
</tr>
</td>
  </tr>
</table>
";
//===========================================================
?>
<table width="70%" cellpadding="5" cellspacing="0" border="0" align="center">
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
</table><br><Br>
<?php
?>
</td>