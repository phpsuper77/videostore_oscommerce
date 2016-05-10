<?
  require('includes/application_top.php');
  $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, c.customers_allow_purchase_order_entry, c.iswholesale, c.distribution_percentage, c.nondistribution_percentage, c.disc1, c.disc2, c.shipping1, c.shipping2, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$HTTP_GET_VARS['customer_id'] . "'");
  $customers = tep_db_fetch_array($customers_query);
  $cInfo = new objectInfo($customers);

?>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<table>
          <tr>
            <td class="main">Is Wholesaler?</td>
            <td class="main">
<?php
        if($processed == true)
  			{
          if($cInfo->iswholesale == '1')
  				{
            echo TEXT_YES;
          }
  				else
  				{
            echo TEXT_NO;
          } // if - else
          echo tep_draw_hidden_field('iswholesale');
        }
  			else
  			{
if ($cInfo->iswholesale){
   $sel1 = 'checked';
   $sel2 = '';
}
else{
   $sel2 = 'checked';
   $sel1 = '';
}

?>
          <input type="radio" name="iswholesale" value="1" disabled="true" <?=$sel1?> /> <? echo '&nbsp;&nbsp;' . TEXT_YES . '&nbsp;&nbsp;'?><input type="radio" name="iswholesale" value="0" disabled="true" <?=$sel2?> /><? echo '&nbsp;&nbsp;' . TEXT_NO;
        } // if - else
?>
	    </td>
	  </tr>
	  <tr>
	    <td class="main">Distribution percentage: </td>
	    <td class="main">
<?php
        if($processed == true)
  			{
          echo $cInfo->distribution_percentage;
          echo tep_draw_hidden_field('distribution_percentage');
        }
  			else
  			{	
	echo tep_draw_input_field('distribution_percentage', $cInfo->distribution_percentage, 'id="id1" maxlength="20" size="10" disabled');
        } // if - else
?>
	    </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	    <td class="main">
<?php
        if($processed == true)
  			{
          if ($cInfo->disc1==1) echo 'List Price'; else echo 'Sale Price';
          echo tep_draw_hidden_field('disc1');
        }
  			else
  			{	
if ($cInfo->disc1 == '1') {
	$status1 = 'selected';
	$status2 = '';
	}
	else{
	$status1 = '';
	$status2 = 'selected';
}

?>
	<select name="disc1" disabled><option value="1" <?=$status1?>>List Price</option><option value="2" <?=$status2?>>Sale Price</option></select>
<?	
        } // if - else
?>
	    </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="main">Is free shipping?</td>
	<td class="main">
<?php
        if($processed == true)
  			{
          if ($cInfo->shipping1=='1') echo "YES"; else echo "NO";
          echo tep_draw_hidden_field('shipping1');
        }
  			else
  			{	
?>
	<input type="checkbox" disabled name="shipping1" value="1" <? echo ($cInfo->shipping1)?'checked':''?>/>
<?
        } // if - else
?>

	</td>

	  </tr>
	  <tr>
	    <td class="main">Non-Distribution percentage: </td>
	    <td class="main">
<?php
        if($processed == true)
  			{
          echo $cInfo->nondistribution_percentage;
          echo tep_draw_hidden_field('nondistribution_percentage');
        }
  			else
  			{	
	echo tep_draw_input_field('nondistribution_percentage', $cInfo->nondistribution_percentage, 'id="id2" maxlength="10" size="10" disabled');
        } // if - else
?>
	    </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	    <td class="main">
<?php
        if($processed == true)
  			{
          if ($cInfo->disc2==1) echo 'List Price'; else echo 'Sale Price';
          echo tep_draw_hidden_field('disc2');
        }
  			else
  			{	
if ($cInfo->disc2 == '1') {
	$status1 = 'selected';
	$status2 = '';
	}
	else{
	$status1 = '';
	$status2 = 'selected';
}
?>
	<select name="disc2" disabled><option value="1" <?=$status1?>>List Price</option><option value="2" <?=$status2?>>Sale Price</option></select>
<?	
        } // if - else
?>
	    </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="main">Is free shipping?</td>
	<td class="main">
<?php
        if($processed == true)
  			{
          if ($cInfo->shipping2=='1') echo "YES"; else echo "NO";
          echo tep_draw_hidden_field('shipping2');
        }
  			else
  			{	
?>
	<input type="checkbox" disabled name="shipping2" value="1" <? echo ($cInfo->shipping2)?'checked':''?>/>
<?
        } // if - else
?>

	</td>
	  </tr>
</table>