<?php
										$categories_id2 = (isset($cInfo) && isset($cInfo->categories_id)) ? $cInfo->categories_id : "";
										$seo_url_sql = "
											select 
												su.surls_id,
												su.surls_name
											from
												categories_description cd
											left join
												".TABLE_SEO_URLS." su on (cd.categories_surls_id = su.surls_id)
											where
												cd.categories_id = '" . $categories_id2 . "' and su.language_id = '1'";
												
										// echo $seo_url_sql;
												
										$categories_query = mysql_query($seo_url_sql) or die("failed: ".mysql_error());
										$category_seo = mysql_fetch_array($categories_query);
										// $category_seo_url = new objectInfo($category_seo);
										
										$categories_surls_id = $category_seo['surls_id'];
										$categories_surls_name = $category_seo['surls_name'];
        $contents[] = array('text' => '
                                  <tr>
                                    <td valign="top" class="infoBoxContent">
												
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr>
                                          <td class="main"><strong>Category SEO URL:</strong></td>
                                        </tr>
										
                                        <tr>
                                          <td class="main">
											<font style="font-size: 8pt; font-family: arial; font-weight: bold;">http://'.$_SERVER["HTTP_HOST"].'/</font><input type=text name="surls_name" value="'.$categories_surls_name.'" size=55>
											<font style="font-size: 8pt; font-family: arial; font-weight: bold;">/</font>
											<input type="hidden" name="surls_id" value="'.$categories_surls_id.'">
											<input type="hidden" name="surls_oldname" value="'.$categories_surls_name.'">
										  </td>
										</tr>
                                      </table>
									</td>
                                  </tr>
								  ');
?>