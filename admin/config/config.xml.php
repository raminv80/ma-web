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
				<name>listing_parent_id</name>
				<table>tbl_listing</table>
				<reference>listing_name</reference>
				<where>listing_parent_flag = '1'</where>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<list_template>list.tpl</list_template>
		<edit_template>edit_page.tpl</edit_template>
		<custom_template field="listing_id" value="27">redirect_edit_page.tpl</custom_template> <!-- Prop Mngt  -->
		<custom_template field="listing_id" value="33">redirect_edit_page.tpl</custom_template> <!-- Renting -->
		<custom_template field="listing_id" value="41">redirect_edit_page.tpl</custom_template> <!-- About -->
		<custom_template field="listing_id" value="26">noimage_edit_page.tpl</custom_template> <!-- Home -->
		<custom_template field="listing_id" value="34">noimage_edit_page.tpl</custom_template> <!-- Prop Rent -->
		<custom_template field="listing_id" value="35">noimage_edit_page.tpl</custom_template> <!-- Inspections -->
		<custom_template field="listing_id" value="40">noimage_edit_page.tpl</custom_template> <!-- News -->
		<custom_template field="listing_id" value="42">noimage_edit_page.tpl</custom_template> <!-- Our Team -->
		<custom_template field="listing_id" value="47">noimage_edit_page.tpl</custom_template> <!-- Privacy P -->
		<custom_template field="listing_id" value="44">custom_contactus_edit_page.tpl</custom_template> <!-- Contact -->
	</section>
<!-- THIS SECTION IS USED TO MANAGE THE LISTING CATEGORY TABLE WHICH IS LINKED TO LISTINGS. 
	 THE FORM OF THIS LISTING CATEGORY SHOULD NOT CHANGE. THE ONLY THINGS WHICH SHOULD NEED 
	 TO CHANGE ARE THE 'URL', 'TITLE' AND 'WHERE'. THE 'LISTING_TYPE_ID' FOR 'CATEGORY_LISTING_ID' 
	 SHOULD NOT CHANGE AS ALL CONTENT SHOULD BE COME FROM A PAGE. 'LISTING_TYPE_ID' FOR 
	 'CATEGORY_PARENT_ID' SHOULD REFLECT THE LISTING TYPE THAT THIS CATEGORY IS FOR. -->
<!-- 	<section>
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
	</section> -->

<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE "PROPERTY FOR RENT"- . THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR THIS CATEGORY.-->
	<!-- <section>
		<showlist>FALSE</showlist>
		<url>properties-for-rent</url>
		<title>Properties for Rent</title>
		<type>LISTING</type>
		<type_id>2</type_id>
		<hierarchy field="listing_parent_id" default="22"></hierarchy>
		<options>
			<field>
				<name>listing_category_id</name>
				<table>tbl_category</table>
				<reference>category_name</reference>
				<where>category_type_id = '2'</where>
			</field>
			<field>
				<name>property_suburb</name>
				<table>tbl_postcode</table>
				<reference>postcode_suburb</reference>
				<where>postcode_state = 'SA'</where>
			</field>
			<field>
				<name>property_type_id</name>
				<table>tbl_property_type</table>
				<reference>property_type_title</reference>
			</field>
			<field>
				<name>listing_id</name>
				<table>tbl_listing</table>
				<reference>listing_content1</reference>
				<where>listing_type_id = '4'</where>
			</field>
		</options>
		<associated>
			<name>gallery</name>
			<table>tbl_gallery</table>
			<field>gallery_listing_id</field>
		</associated>
		<associated>
			<name>inspection</name>
			<table>tbl_inspection</table>
			<field>inspection_listing_id</field>
		</associated>
		<extends>
			<table>tbl_property</table>
			<field>property_listing_id</field>
		</extends>
		<list_template>list.tpl</list_template>
		<edit_template>edit_property.tpl</edit_template>
	</section>
	 -->
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE "NEWS". THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR THIS CATEGORY.-->
<!-- 	<section>
		<showlist>FALSE</showlist>
		<url>news</url>
		<title>News</title>
		<type>LISTING</type>
		<type_id>3</type_id>
		<hierarchy field="category_parent_id" default="18"></hierarchy>
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
			<table>tbl_news</table>
			<field>news_listing_id</field>
		</extends>
		<list_template>list.tpl</list_template>
		<edit_template>edit_news.tpl</edit_template>
	</section> -->
	
	
<!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE "OUR TEAM". THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR THIS CATEGORY.-->
<!-- 	 <section>
		<showlist>FALSE</showlist>
		<url>our-team</url>
		<title>Our Team</title>
		<type>LISTING</type>
		<type_id>4</type_id>
		<hierarchy field="category_parent_id" default="21"></hierarchy>
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
		<edit_template>edit_ourteam.tpl</edit_template>
	</section> -->
	
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>