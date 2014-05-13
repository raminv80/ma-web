<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="false">
	<company></company>
	<database>
		<host>m4-mysql1-1.ilisys.com.au</host>
		<user>steelsa</user>
		<password>30bwgSo1</password>
		<dbname>steelsa_db</dbname>
	</database>
	<page_strut>
		<type>1</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<where>listing_flag2 = 1</where>
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<template>standardpage.tpl</template>
		</table>
		<template>standardpage.tpl</template><!-- The template used if the field is matched -->
	</page_strut>
	<index_page>
		<template>home.tpl</template>
		<pageID>1</pageID>
		<additional>
		  <tag>product_page</tag>
		  <name>products</name>
		  <data>categories</data>
		</additional>
	</index_page>
	<error404>
	  <header>HTTP/1.0 404 Not Found</header>
		<template>404.tpl</template>
		<pageID>302</pageID>
	</error404>
	<error403>
	  <header>HTTP/1.0 403 Forbidden</header>
		<template>403.tpl</template>
		<pageID>302</pageID>
	</error403>
	<error503>
	  <header>HTTP/1.0 503 Service Temporarily Unavailable</header>
		<template>503.tpl</template>
		<pageID>302</pageID>
	</error503>
	<search>
		<template>search.tpl</template>
		<pageID>323</pageID>
	</search>
	<static_page>
		<url>contact-us</url>
		<template>contact-us.tpl</template>
		<pageID>44</pageID>
	</static_page>
	<!-- <static_page>
		<url>colorbond</url>
		<template>colorbond.tpl</template>
		<pageID>326</pageID>
		<additional>
		  <tag>product_page</tag>
		  <name>products</name>
		  <data>categories</data>
		</additional>
	</static_page>
	<static_page>
		<url>colorbond/roofing</url>
		<template>colorbond-roofing.tpl</template>
		<pageID>344</pageID>
		<associated> 
			<name>colorbond</name>
			<table>tbl_colorbond</table>
			<linkfield>listing_id</linkfield>
			<field>colorbond_listing_id</field> 
			<orderby>colorbond_category DESC, colorbond_order ASC</orderby>
		</associated>
		<additional>
		  <tag>product_page</tag>
		  <name>products</name>
		  <data>categories</data>
		</additional>
	</static_page>
	<static_page>
		<url>colorbond/fencing</url>
		<template>colorbond-fencing.tpl</template>
		<pageID>345</pageID>
		<associated> 
			<name>colorbond</name>
			<table>tbl_colorbond</table>
			<linkfield>listing_id</linkfield>
			<field>colorbond_listing_id</field> 
			<orderby>colorbond_category DESC, colorbond_order ASC</orderby>
		</associated>
		<additional>
		  <tag>product_page</tag>
		  <name>products</name>
		  <data>categories</data>
		</additional>
	</static_page> -->
 	<product_page name="products">
 	  <depth>2</depth>
		<url>products</url>
		<pageID>2</pageID>
		<root_parent_id>2</root_parent_id>
		<type>2</type>
		<file>ProductClass</file>
		<orderby>listing_order ASC, listing_name ASC</orderby>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<tags>
				<name>associated_categories</name>
				<table>tbl_tag</table>
				<object_table>tbl_listing</object_table>
				<object_value>listing_name</object_value>
			</tags>
		</table>
		<template>products.tpl</template>
		<producttable>
			<id>product_id</id>
			<name>product_info</name>
			<table>tbl_product</table>
			<linkfield>listing_object_id</linkfield>
			<field>product_listing_id</field>
			<orderby>product_order ASC</orderby>
			<extends>
	  			<table>tbl_productspec</table>
	  			<linkfield>product_id</linkfield>
	  			<field>productspec_product_id</field>
	  		</extends>
			<associated> 
				<id>attribute_id</id>
				<name>attribute</name>
				<table>tbl_attribute</table>
				<linkfield>product_id</linkfield>
				<field>attribute_product_id</field> 
				<orderby>attribute_order ASC</orderby>
				<associated> 
					<id>attr_value_id</id>
					<name>attr_value</name>
					<table>tbl_attr_value</table>
					<linkfield>attribute_id</linkfield>
					<field>attr_value_attribute_id</field> 
					<orderby>attr_value_order ASC</orderby>
				</associated>
			</associated>
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>product_id</linkfield>
				<field>gallery_product_id</field>
				<orderby>gallery_ishero DESC</orderby> 
			</associated>
			<associated>
				<name>tags</name>
				<table>tbl_tag</table>
				<linkfield>product_id</linkfield>
				<field>tag_object_id</field>
				<where>tag_object_table = 'tbl_product'</where> 
			</associated>
			<tags>
				<name>associated_products</name>
				<table>tbl_tag</table>
				<object_table>tbl_product</object_table>
				<object_value>product_group</object_value>
			</tags>
			<template>product-details.tpl</template>
		</producttable>
 	</product_page>

 	<listing_page name="find-a-store"> 
		<url>find-a-store</url> 
		<pageID>11</pageID> 
		<type>5</type> 
		<file>ListClass</file>
		<table>			<!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field>	<!-- The field used to match the URL -->
			<associated> 
				<id>files_id</id>
				<name>files</name>
				<table>tbl_files</table>
				<linkfield>listing_id</linkfield>
				<field>files_listing_id</field> 
			</associated>
			<associated> 
				<id>service_id</id>
				<name>services</name>
				<table>tbl_service</table>
				<linkfield>listing_id</linkfield>
				<field>service_listing_id</field> 
			</associated>
			<extends>
				<table>tbl_location</table>
				<linkfield>listing_id</linkfield>
				<field>location_listing_id</field>
			</extends>
			<template>store.tpl</template> 
		</table>
		<template>find-a-store.tpl</template> 
		<!-- The template used if the field is matched --> 
	</listing_page>
	
	<listing_page name="colorbond"> 
		<url>colorbond</url> 
		<pageID>326</pageID> 
		<type>1</type> 
		<file>ListClass</file>
		<table>			<!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field>	<!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<associated listing="true"> 
  			<name>colorbond</name>
  			<table>tbl_colorbond</table>
  			<linkfield>listing_id</linkfield>
  			<field>colorbond_listing_id</field> 
  			<orderby>colorbond_category DESC, colorbond_order ASC</orderby>
  		</associated>
  		<additional>
  		  <tag>product_page</tag>
  		  <name>products</name>
  		  <data>categories</data>
  		</additional>
		</table>
		<template>colorbond.tpl</template> 
	</listing_page>
	
	<listing_page name="documents"> 
		<pageID>303</pageID> 
		<root_parent_id>303</root_parent_id>
		<type>6</type> 
		<file>ListClass</file>
		<orderby>listing_order ASC, listing_name ASC</orderby>
		<table>			<!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field>	<!-- The field used to match the URL -->
		</table>
	</listing_page>
	
	<account restricted="true">
		<url>my-account</url>
		<template>myaccount.tpl</template>
		<pageID>303</pageID>
		<additional>
		  <tag>listing_page</tag>
		  <name>documents</name>
		  <data>documents</data>
		</additional>
	</account>
 	<cart> 
		<url>store/shopping-cart</url> 
		<pageID>13</pageID> 
		<type>1</type> 
		<file>ListClass</file>
		<table>			<!-- This table will be the details table -->
			<name>tbl_cart</name>
			<field>listing_url</field>	<!-- The field used to match the URL -->
		</table>
		<template>shopping-cart.tpl</template> 
		<!-- The template used if the field is matched --> 
	</cart>
	<checkout guest="true"> 
		<url>store/checkout</url> 
		<pageID>15</pageID> 
		<type>1</type> 
		<file>ListClass</file>
		<table>			<!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field>	<!-- The field used to match the URL -->
		</table>
		<template>checkout.tpl</template> 
		<!-- The template used if the field is matched --> 
	</checkout>

	<process>
		<url>process/cart</url>
		<file>includes/processes/processes-cart.php</file>
		<return_url></return_url>
	</process>	
	<process>
		<url>process/user</url>
		<file>includes/processes/processes-user.php</file>
		<return_url></return_url>
	</process>
	<process>
		<url>process/contact-us</url>
		<file>includes/processes/processes-contactus.php</file>
		<return_url></return_url>
	</process>
	<process>
		<url>process/general</url>
		<file>includes/processes/processes-general.php</file>
		<return_url></return_url>
	</process>	
	<process>
		<url>process/e-newsletter</url>
		<file>includes/processes/process-campaignmonitor.php</file>
		<return_url></return_url>
	</process>	 	
	<process>
		<url>process/estimate</url>
		<file>includes/processes/processes-estimate.php</file>
		<return_url></return_url>
	</process>	
	<process>
		<url>process/neareststockist</url>
		<file>includes/processes/processes-neareststockist.php</file>
		<return_url></return_url>
	</process>	
	<process>
		<url>process/stayintouch</url>
		<file>includes/processes/processes-stayintouch.php</file>
		<return_url></return_url>
	</process>	
	
	<responsiveImages>
		<resolutions></resolutions><!-- the resolution break-points to use (screen widths, in pixels) -->
		<cache></cache> <!-- where to store the generated re-sized images. Specify from your document root! -->
		<quality></quality> <!-- the quality of any generated JPGs on a scale of 0 to 100 -->
		<sharpen></sharpen> <!-- Shrinking images can blur details, perform a sharpen on re-scaled images? -->
		<watch_cache></watch_cache> <!-- check that the adapted image isn't stale (ensures updated source images are re-cached) -->
		<browser_cache></browser_cache><!-- How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default) -->
	</responsiveImages>
	<smartytemplate_config>
		<templates>templates/diy</templates>
		<templates_c>templates_c/diy</templates_c>
		<cache>cache</cache>
		<configs>configs</configs>
		<plugins>plugins</plugins>
	</smartytemplate_config>
</config>