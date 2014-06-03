<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true"> 
	<company></company> 
	<database>
		<host>m4-mysql1-1.ilisys.com.au</host>
		<user>steelsa</user>
		<password>30bwgSo1</password>
		<dbname>steelsa_db</dbname>
	</database>
	<resource> 
		<url>file-manager</url> 
		<template>filemanager.tpl</template> 
	</resource>
	<sequence> 
		<table>tbl_sequence</table> 
	</sequence> 
	<!-- THIS SECTION IS USED TO MANAGE THE ADMINISTRATOR TABLE. ADMINISTRATORS AND USERS FOR THE CMS ARE MANAGED HERE. -->
	<section level="1">
		<showlist>FALSE</showlist>
		<url>users</url>
		<title>Administrators</title>
		<type>TABLE</type>
		<table>
			<name>tbl_admin</name>
			<id>admin_id</id>
			<field>admin_name</field>
			<deleted>admin_deleted</deleted>
			<associated>
				<name>accesses</name>
				<table>tbl_access</table>
				<linkfield>admin_id</linkfield>
				<field>access_admin_id</field>
			</associated>
			<options> 
				<field> 
					<name>store_id</name>
					<table>tbl_listing</table>
					<id>listing_object_id</id>
					<reference>listing_name</reference> 
					<where>listing_type_id = '5' AND listing_published = '1'</where> 
				</field> 
			</options>
		</table>
		<list_template>list_admin.tpl</list_template>
		<edit_template>edit_admin.tpl</edit_template>
	</section>
	<section level="1">
		<showlist>FALSE</showlist>
		<url>page</url>
		<title>Pages</title>
		<type>LISTING</type>
		<type_id>1</type_id>
		<options> 
			<field> 
				<name>listing_parent_id</name>
				<table>tbl_listing</table>
				<id>listing_object_id</id>
				<reference>listing_name</reference> 
				<where>listing_parent_flag = '1' AND listing_type_id = '1' AND listing_published = '1'</where> 
			</field> 
		</options>
		<associated> 
			<name>gallery</name>
			<table>tbl_gallery</table>
			<linkfield>listing_id</linkfield>
			<field>gallery_listing_id</field> 
		</associated>
		<associated>
			<name>tags</name>
			<table>tbl_tag</table>
			<linkfield>listing_id</linkfield>
			<field>tag_object_id</field>
			<where>tag_object_table = 'tbl_listing'</where> 
		</associated>
		<list_template>page-list.tpl</list_template>
		<edit_template>edit_page.tpl</edit_template>
		<custom_template field="listing_object_id" value="343">custom_landing_edit_page.tpl</custom_template>	<!-- Landing -->
		<custom_template field="listing_object_id" value="303">custom_myaccount_edit_page.tpl</custom_template>	<!-- MyAccount -->
		<custom field="listing_object_id" value="344">
		  <template>custom_colorbond_edit_page.tpl</template>
		  <associated> 
  			<name>colorbond</name>
  			<table>tbl_colorbond</table>
  			<linkfield>listing_id</linkfield>
  			<field>colorbond_listing_id</field> 
  			<orderby>colorbond_category DESC, colorbond_order ASC</orderby>
  		</associated>
		</custom>	<!-- COLORBOND Roofing -->
		<custom field="listing_object_id" value="345">
		  <template>custom_colorbond_edit_page.tpl</template>
		  <associated> 
  			<name>colorbond</name>
  			<table>tbl_colorbond</table>
  			<linkfield>listing_id</linkfield>
  			<field>colorbond_listing_id</field> 
  			<orderby>colorbond_category DESC, colorbond_order ASC</orderby>
  		</associated>
		</custom>	<!-- COLORBOND Roofing -->
	</section>

	 <!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE "PRODUCT CATEGORY". THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR THIS CATEGORY.--> 
	 <section level="1">
		<showlist>FALSE</showlist>
		<url>prodcat</url>
		<title>Product Categories</title>
		<type>LISTING</type>
		<type_id>2</type_id>
		<root_parent_id>2</root_parent_id>
		<orderby>listing_order ASC, listing_name ASC, listing_published ASC</orderby>
		<options> 
			<field recursive="true"> 
				<name>listing_parent_id</name>
				<table>tbl_listing</table>
				<id>listing_object_id</id>
				<reference>listing_name</reference> 
				<where>listing_parent_flag = '1' AND listing_type_id = '2' AND listing_published = '1'</where> 
			</field> 
		</options>
		<associated>
			<name>tags</name>
			<table>tbl_tag</table>
			<linkfield>listing_id</linkfield>
			<field>tag_object_id</field>
			<where>tag_object_table = 'tbl_listing'</where> 
		</associated>
		<list_template>list.tpl</list_template>
		<edit_template>edit_prodcategory.tpl</edit_template>
	</section>  
	
	<!-- THIS SECTION IS USED TO MANAGE THE "PRODUCT" TABLE. -->
	<section level="1">
		<showlist>FALSE</showlist>
		<url>products</url>
		<title>Products</title>
		<type>PRODUCT</type>
		<type_id>2</type_id>
		<root_parent_id>2</root_parent_id>
		<orderby>listing_order ASC, listing_name ASC, listing_published ASC</orderby>
		<table>
			<name>tbl_product</name>
			<id>product_id</id>
			<field>product_name</field>
			<deleted>product_deleted</deleted>
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
			</associated>
			<associated>
				<name>tags</name>
				<table>tbl_tag</table>
				<linkfield>product_id</linkfield>
				<field>tag_object_id</field>
				<where>tag_object_table = 'tbl_product'</where> 
			</associated>
			<associated>
				<name>locations</name>
				<table>tbl_availability</table>
				<linkfield>product_id</linkfield>
				<field>availability_product_id</field>
			</associated>
			<associated>
				<name>additional_category</name>
				<table>tbl_additional_category</table>
				<linkfield>product_id</linkfield>
				<field>additional_category_product_id</field>
			</associated>
			<options> 
				<field> 
					<name>products_list</name>
					<table>tbl_product</table>
					<id>product_object_id</id>
					<reference>product_group</reference> 
					<orderby>product_order ASC, product_name ASC</orderby>
					<where>product_group IS NOT NULL AND product_group != '' AND product_published = '1'</where> 
				</field>
				<field> 
					<name>product_object_id</name>
					<table>tbl_product</table>
					<id>product_object_id</id>
					<reference>product_name</reference> 
					<orderby>product_order ASC, product_name ASC</orderby>
				</field>
				<field> 
					<name>location_object_id</name>
					<table>tbl_listing</table>
					<id>listing_object_id</id>
					<reference>listing_name</reference> 
					<orderby>listing_order ASC, listing_name ASC</orderby>
					<where>listing_type_id = '5'</where> 
				</field>
				<field recursive="true"> 
					<name>product_listing_id</name>
					<table>tbl_listing</table>
					<id>listing_object_id</id>
					<reference>listing_name</reference> 
					<orderby>listing_order ASC, listing_name ASC</orderby>
					<where>listing_parent_flag = '1' AND listing_type_id = '2' AND listing_published = '1'</where> 
				</field>  
			</options>
		</table>
		<list_template>listall.tpl</list_template>
		<edit_template>edit_product.tpl</edit_template>
	</section> 
	
	<!-- THIS SECTION IS USED TO MANAGE THE "CART/ORDERS" TABLE. -->
	<section hideadd="true" level="2">
		<showlist>FALSE</showlist>
		<url>orders</url>
		<title>Orders</title>
		<type>TABLE</type>
		<type_id>2</type_id>
		<storefield>cart_store_id</storefield>
		<table>
			<name>tbl_cart</name>
			<id>cart_id</id>
			<field>cart_closed_date</field>
			<deleted>cart_deleted</deleted>
			<where>DATE(cart_closed_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE() </where> 
			<orderby>cart_closed_date DESC</orderby>
			<associated> 
				<id>cartitem_id</id>
				<name>items</name>
				<table>tbl_cartitem</table>
				<linkfield>cart_id</linkfield>
				<field>cartitem_cart_id</field> 
				<associated> 
					<id>cartitem_attr_id</id>
					<name>attributes</name>
					<table>tbl_cartitem_attr</table>
					<linkfield>cartitem_id</linkfield>
					<field>cartitem_attr_cartitem_id</field> 
				</associated>
			</associated>
			<associated inlist="true"> 
				<id>user_id</id>
				<name>user</name>
				<table>tbl_user</table>
				<linkfield>cart_user_id</linkfield>
				<field>user_id</field> 
				<associated> 
					<id>address_id</id>
					<name>address</name>
					<table>tbl_address</table>
					<linkfield>user_id</linkfield>
					<field>address_user_id</field>
					<orderby>address_modified DESC</orderby>  
				</associated>
			</associated>
			<associated inlist="true"> 
				<id>payment_id</id>
				<name>payment</name>
				<table>tbl_payment</table>
				<linkfield>cart_id</linkfield>
				<field>payment_cart_id</field> 
				<associated> 
					<id>order_id</id>
					<name>order</name>
					<table>tbl_order</table>
					<linkfield>payment_id</linkfield>
					<field>order_payment_id</field> 
					<orderby>order_modified DESC</orderby> 
				</associated>
				<associated> 
					<id>listing_id</id>
					<name>store</name>
					<table>tbl_listing</table>
					<linkfield>payment_store_id</linkfield>
					<field>listing_object_id</field>
					<where>listing_published = 1</where>
				</associated>
			</associated>
			<options> 
				<field inlist="true"> 
					<name>status</name>
					<table>tbl_status</table>
					<id>status_id</id>
					<reference>status_name</reference> 
					<orderby>status_order</orderby> 
				</field> 
			</options>
		</table>
		<list_template>list_order.tpl</list_template>
		<edit_template>edit_order.tpl</edit_template>
	</section> 
		
	<!-- THIS SECTION IS USED TO MANAGE THE "DISCOUNT CODE" TABLE. -->
<!-- 	<section>
		<showlist>FALSE</showlist>
		<url>discounts</url>
		<title>Discount Codes</title>
		<type>TABLE</type>
		<type_id>2</type_id>
		<root_parent_id>10</root_parent_id>
		<table>
			<name>tbl_discount</name>
			<id>discount_id</id>
			<field>discount_name</field>
			<deleted>discount_deleted</deleted>
			<orderby>discount_id DESC</orderby>
			<options> 
				<field recursive="true"> 
					<name>categories</name>
					<table>tbl_listing</table>
					<id>listing_object_id</id>
					<reference>listing_name</reference> 
					<where>listing_parent_flag = '1' AND listing_type_id = '2' AND listing_published = '1'</where> 
				</field> 
				<field> 
					<name>products</name>
					<table>tbl_product</table>
					<id>product_object_id</id>
					<reference>product_name</reference> 
					<orderby>product_name AND product_published = '1'</orderby>
				</field> 
			</options>
		</table>
		<list_template>list.tpl</list_template>
		<edit_template>edit_discount.tpl</edit_template>
	</section>  -->
	
	 <!-- THIS SECTION IS USED TO MANAGE THE "STORES" LISTINGS. -->
	 <section level="2">
		<showlist>FALSE</showlist>
		<url>stores</url>
		<title>Stores</title>
		<type>LISTING</type>
		<type_id>5</type_id>
		<root_parent_id>11</root_parent_id>
		<storefield>listing_object_id</storefield>
		<associated>
			<name>files</name>
			<table>tbl_files</table>
			<linkfield>listing_id</linkfield>
			<field>files_listing_id</field>
		</associated>
		<associated>
			<name>services</name>
			<table>tbl_service</table>
			<linkfield>listing_id</linkfield>
			<field>service_listing_id</field>
			<orderby>service_order</orderby>
		</associated>
		<associated>
			<name>tags</name>
			<table>tbl_tag</table>
			<linkfield>listing_id</linkfield>
			<field>tag_object_id</field>
			<where>tag_object_table = 'tbl_listing'</where> 
		</associated>
		<extends>
			<table>tbl_location</table>
			<linkfield>listing_id</linkfield>
			<field>location_listing_id</field>
		</extends>
		<list_template>list.tpl</list_template>
		<edit_template>edit_store.tpl</edit_template>
	</section> 
	
	<!-- THIS SECTION IS USED TO MANAGE CONTACTS -->
	<section hideadd="true" level="2">
		<showlist>FALSE</showlist>
		<url>enquiries</url>
		<title>Enquiries</title>
		<storefield>contact_store_id</storefield>
		<type>TABLE</type>
		<table>
			<name>tbl_contact</name>
			<id>contact_id</id>
			<field>contact_name</field>
			<deleted>contact_deleted</deleted>
			<orderby>contact_created DESC</orderby>
		</table>
		<list_template>list_noadd.tpl</list_template>
		<edit_template>edit_contact.tpl</edit_template>
	</section>
	
	<section level="1">
		<showlist>FALSE</showlist>
		<url>documents</url>
		<title>Documents</title>
		<type>LISTING</type>
		<type_id>6</type_id>
		<options> 
			<field> 
				<name>listing_parent_id</name>
				<table>tbl_listing</table>
				<id>listing_object_id</id>
				<reference>listing_name</reference> 
				<where>listing_parent_flag = '1' AND listing_type_id = '6' AND listing_published = '1'</where> 
			</field> 
		</options>
		<list_template>list.tpl</list_template>
		<edit_template>edit_documents.tpl</edit_template>
	</section>
	
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>