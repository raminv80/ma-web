<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true">
	<company></company>
	<database>
		<host>n7-mysql5-3.ilisys.com.au</host>
		<user>themso</user>
		<password>c@^^3L5tRu7s*n9ub11c</password>
		<dbname>themso5_db</dbname>
	</database>
	<resource>
		<url>file-manager</url>
		<template>filemanager.tpl</template>
	</resource>
	
<!-- THIS SECTION IS USED TO MANAGE THE ADMINISTRATOR TABLE. ADMINISTRATORS AND USERS FOR THE CMS ARE MANAGED HERE. -->
	<section>
		<showlist>FALSE</showlist>
		<url>users</url>
		<title>Administrators</title>
		<type>TABLE</type>
		<table>
			<name>tbl_admin</name>
			<id>admin_id</id>
			<field>admin_name</field>
			<deleted>admin_deleted</deleted>
		</table>
		<list_template>list_admin.tpl</list_template>
		<edit_template>edit_admin.tpl</edit_template>
	</section>

<!-- THIS SECTION IS USED TO MANAGE THE LISTING TYPES TABLE. IT SHOULD NOT BE VISIBLE ON THE LIVE VERSION. -->
	<!-- <section>
		<staging>TRUE</staging>
		<showlist>TRUE</showlist>
		<url>type</url>
		<title>Types</title>
		<type>TABLE</type>
		<table>
			<name>tbl_type</name>
			<id>type_id</id>
			<field>type_name</field>
			<deleted>type_deleted</deleted>
		</table>
		<list_template>list_type.tpl</list_template>
		<edit_template>edit_type.tpl</edit_template>
	</section> -->
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE PAGE. THIS IS A LISTING AT IT'S BASIC FORM. -->
	<section>
		<showlist>FALSE</showlist>
		<url>page</url>
		<title>Pages</title>
		<type>LISTING</type>
		<type_id>1</type_id>
		<options>
			<field>
				<name>listing_category_id</name>
				<table>tbl_category</table>
				<reference>category_name</reference>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<list_template>list_page.tpl</list_template>
		<edit_template>edit_page.tpl</edit_template>
		<custom_template field="listing_id" value="1">custom_home_edit_page.tpl</custom_template>
		<custom_template field="listing_id" value="3">custom_menu_edit_page.tpl</custom_template>
		<custom_template field="listing_id" value="4">custom_location_edit_page.tpl</custom_template>
		<custom_template field="listing_id" value="31">custom_potm_edit_page.tpl</custom_template>
		<custom_template field="listing_id" value="5">custom_community_edit_page.tpl</custom_template>
	</section>
<!-- THIS SECTION IS USED TO MANAGE THE LISTING CATEGORY TABLE WHICH IS LINKED TO LISTINGS. 
	 THE FORM OF THIS LISTING CATEGORY SHOULD NOT CHANGE. THE ONLY THINGS WHICH SHOULD NEED 
	 TO CHANGE ARE THE 'URL', 'TITLE' AND 'WHERE'. THE 'LISTING_TYPE_ID' FOR 'CATEGORY_LISTING_ID' 
	 SHOULD NOT CHANGE AS ALL CONTENT SHOULD BE COME FROM A PAGE. 'LISTING_TYPE_ID' FOR 
	 'CATEGORY_PARENT_ID' SHOULD REFLECT THE LISTING TYPE THAT THIS CATEGORY IS FOR. -->
	<section>
		<showlist>FALSE</showlist>
		<url>page_category</url>
		<title>Page Categories</title>
		<type>TABLE</type>
		<table>
			<name>tbl_category</name>
			<id>category_id</id>
			<field>category_name</field>
			<deleted>category_deleted</deleted>
			<where>category_type_id = '1'</where>
			<options>
				<field>
					<name>category_listing_id</name>
					<table>tbl_listing</table>
					<reference>listing_name</reference>
					<where>listing_type_id = '1'</where>
				</field>
				<field>
					<name>category_parent_id</name>
					<table>tbl_category</table>
					<reference>category_name</reference>
				</field>
			</options>
		</table>
		<list_template>list_pcategory.tpl</list_template>
		<edit_template>edit_pcategory.tpl</edit_template>
	</section>

<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE PRODUCTS. THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR PRODUCTS.-->
	<section>
		<showlist>FALSE</showlist>
		<url>products</url>
		<title>Products</title>
		<type>LISTING</type>
		<type_id>2</type_id>
		<hierarchy field="category_parent_id" default="1"></hierarchy>
		<options>
			<field>
				<name>listing_category_id</name>
				<table>tbl_category</table>
				<reference>category_name</reference>
				<where>category_type_id = '2'</where>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<extends>
			<table>tbl_product</table>
			<field>product_listing_id</field>
		</extends>
		<list_template>list_product.tpl</list_template>
		<edit_template>edit_product.tpl</edit_template>
	</section>
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTING CATEGORY TABLE WHICH IS LINKED TO LISTINGS. 
	 THE FORM OF THIS LISTING CATEGORY SHOULD NOT CHANGE. THE ONLY THINGS WHICH SHOULD NEED 
	 TO CHANGE ARE THE 'URL', 'TITLE' AND 'WHERE'. THE 'LISTING_TYPE_ID' FOR 'CATEGORY_LISTING_ID' 
	 SHOULD NOT CHANGE AS ALL CONTENT SHOULD BE COME FROM A PAGE. 'LISTING_TYPE_ID' FOR 
	 'CATEGORY_PARENT_ID' SHOULD REFLECT THE LISTING TYPE THAT THIS CATEGORY IS FOR. -->
	<section>
		<showlist>FALSE</showlist>
		<url>product_category</url>
		<title>Product Categories</title>
		<type>TABLE</type>
		<table>
			<name>tbl_category</name>
			<id>category_id</id>
			<field>category_name</field>
			<deleted>category_deleted</deleted>
			<where>category_type_id = '2'</where>
			<hierarchy field="category_parent_id" default="1"></hierarchy>
			<options>
				<field>
					<name>category_listing_id</name>
					<table>tbl_listing</table>
					<reference>listing_name</reference>
					<where>listing_type_id = '1'</where>
				</field>
				<field>
					<name>category_parent_id</name>
					<table>tbl_category</table>
					<reference>category_name</reference>
				</field>
			</options>
		</table>
		<list_template>list_prodcategory.tpl</list_template>
		<edit_template>edit_prodcategory.tpl</edit_template>
	</section>

<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE LOCATION. THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR PRODUCTS.-->
	 <section>
		<showlist>FALSE</showlist>
		<url>locations</url>
		<title>Locations</title>
		<type>LISTING</type>
		<type_id>3</type_id>
		<hierarchy field="category_parent_id" default="2"></hierarchy>
		<options>
			<field>
				<name>listing_category_id</name>
				<table>tbl_category</table>
				<reference>category_name</reference>
				<where>category_type_id = '3'</where>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<extends>
			<table>tbl_location</table>
			<field>location_listing_id</field>
		</extends>
		<list_template>list_location.tpl</list_template>
		<edit_template>edit_location.tpl</edit_template>
	</section>
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTING CATEGORY TABLE WHICH IS LINKED TO LISTINGS. 
	 THE FORM OF THIS LISTING CATEGORY SHOULD NOT CHANGE. THE ONLY THINGS WHICH SHOULD NEED 
	 TO CHANGE ARE THE 'URL', 'TITLE' AND 'WHERE'. THE 'LISTING_TYPE_ID' FOR 'CATEGORY_LISTING_ID' 
	 SHOULD NOT CHANGE AS ALL CONTENT SHOULD BE COME FROM A PAGE. 'LISTING_TYPE_ID' FOR 
	 'CATEGORY_PARENT_ID' SHOULD REFLECT THE LISTING TYPE THAT THIS CATEGORY IS FOR. -->
	<section>
		<staging>TRUE</staging>
		<showlist>FALSE</showlist>
		<url>location_category</url>
		<title>Location Categories</title>
		<type>TABLE</type>
		<table>
			<name>tbl_category</name>
			<id>category_id</id>
			<field>category_name</field>
			<deleted>category_deleted</deleted>
			<where>category_type_id = '3'</where>
			<hierarchy field="category_parent_id" default="2"></hierarchy>
			<options>
				<field>
					<name>category_listing_id</name>
					<table>tbl_listing</table>
					<reference>listing_name</reference>
					<where>listing_type_id = '1'</where>
				</field>
				<field>
					<name>category_parent_id</name>
					<table>tbl_category</table>
					<reference>category_name</reference>
				</field>
			</options>
		</table>
		<list_template>list_category.tpl</list_template>
		<edit_template>edit_loccategory.tpl</edit_template>
	</section>
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE LOCATION. THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR PRODUCTS.-->
	 <section>
		<showlist>FALSE</showlist>
		<url>news-and-media</url>
		<title>News and Media</title>
		<type>LISTING</type>
		<type_id>4</type_id>
		<hierarchy field="category_parent_id" default="3"></hierarchy>
		<options>
			<field>
				<name>listing_category_id</name>
				<table>tbl_category</table>
				<reference>category_name</reference>
				<where>category_type_id = '4'</where>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<list_template>list.tpl</list_template>
		<edit_template>edit_news.tpl</edit_template>
	</section>
	
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>