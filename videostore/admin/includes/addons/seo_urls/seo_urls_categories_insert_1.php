									  <?php
											// retrieve product seo URL
											$i=0;
											$product_url_sql = "
												select 
													su.surls_id,
													su.surls_name
												from
													".TABLE_SEO_URLS." as su
												where
													su.surls_param LIKE '%products_id=" . $_GET['pID'] . "'";
											
											$products_query = mysql_query($product_url_sql);
											$product_seo = mysql_fetch_array($products_query);
											// $suInfo = new objectInfo($product_seo);
											$products_seo_url = $product_seo['surls_name'];
											$surls_id = $product_seo['surls_id'];
									  ?>
	  <tr>
	    <td class="main"><?php echo "Product SEO Url";?></td>
	    <td class="main">
			<img src="images/pixel_trans.gif" border="0" alt="" width="24" height="15">&nbsp;<font style="font-size: 8pt; font-weight: bold; font-family: arial;"><?php echo "http://".$_SERVER["HTTP_HOST"]."/";?></font> <input type=text name="surls_name" value="<?php echo $products_seo_url;?>" size=55><font style="font-size: 8pt; font-weight: bold; font-family: arial;">/</font>
			<input type="hidden" name="surls_id" value="<?php echo $surls_id;?>">
			<input type="hidden" name="surls_oldname" value="<?php echo $products_seo_url;?>">
		</td>
	  </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>	  