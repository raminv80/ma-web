<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true">
	<company></company>
	<database>
		<host>m4-mysql1-1.ilisys.com.au</host>
		<user>themso</user>
		<password>c@^^3L5tRu7s*n9ub11c</password>
		<dbname>themso3_db</dbname>
	</database>
	<page_strut>
		<type>1</type>
		<depth>2</depth>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
		</table>
		<template>standardpage.tpl</template><!-- The template used if the field is matched -->
	</page_strut>
	<index_page>
		<template>home.tpl</template>
		<pageID>12</pageID>
	</index_page>
	<!-- <error404 standalone="true">
		<template>404.tpl</template>
		<pageID>2</pageID>
	</error404> -->
	<error404>
		<template>standardpage.tpl</template>
		<pageID>11</pageID>
	</error404>
	<search>
		<template>search.tpl</template>
		<pageID>28</pageID>
	</search>
	<static_page>
		<url>contact-us</url>
		<template>contact-us.tpl</template>
		<pageID>44</pageID>
	</static_page>
	<static_page>
		<url>thank-you-for-buying</url>
		<template>checkout-complete.tpl</template>
		<pageID>21</pageID>
	</static_page>

	
 	<product_page name="store">
		<url>store</url>
		<pageID>10</pageID>
		<root_parent_id>10</root_parent_id>
		<type>2</type>
		<file>ProductClass</file>
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
		<template>category.tpl</template>
		<producttable>
			<id>product_id</id>
			<name>product_info</name>
			<table>tbl_product</table>
			<linkfield>listing_id</linkfield>
			<field>product_listing_id</field>
			<orderby>product_order ASC</orderby>
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
			<template>product.tpl</template>
		</producttable>
 	</product_page>

 	<login>
		<url>login-register</url>
		<template>login-register.tpl</template>
		<pageID>78</pageID>
		<fallback_redirect>my-account</fallback_redirect>
	</login>
	<account restricted="true">
		<url>my-account</url>
		<template>account.tpl</template>
		<pageID>22</pageID>
		<fallback_redirect>login-register</fallback_redirect>
	</account>
 	<cart> 
		<url>shopping-cart</url> 
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
	<checkout guest="false"> 
		<url>checkout</url> 
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
	
	<responsiveImages>
		<resolutions></resolutions><!-- the resolution break-points to use (screen widths, in pixels) -->
		<cache></cache> <!-- where to store the generated re-sized images. Specify from your document root! -->
		<quality></quality> <!-- the quality of any generated JPGs on a scale of 0 to 100 -->
		<sharpen></sharpen> <!-- Shrinking images can blur details, perform a sharpen on re-scaled images? -->
		<watch_cache></watch_cache> <!-- check that the adapted image isn't stale (ensures updated source images are re-cached) -->
		<browser_cache></browser_cache><!-- How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default) -->
	</responsiveImages>
	<smartytemplate_config>
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>