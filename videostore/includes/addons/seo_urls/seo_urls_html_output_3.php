<?php
		$sql = "
			SELECT
				pd.products_upc,
				pd.products_isbn,
				pd.products_gtin,
				pd.products_brand
			FROM
				" . TABLE_PRODUCTS . " p,
				" . TABLE_PRODUCTS_DESCRIPTION . " pd
			WHERE
				p.products_id = '" . (int)$_GET['pID'] . "'
			AND
				p.products_id = pd.products_id
			AND
				pd.language_id = '" . (int)$languages_id . "'
		";
		$product_query = tep_db_query($sql);

		$product = tep_db_fetch_array($product_query);

		$pInfo->objectInfo($product);
?>