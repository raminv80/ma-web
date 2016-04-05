<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true"> 
	<domain></domain>
	<company>
		<name>Them</name>
		<address>
			<street>1/26 The Parade West</street>
			<suburb>Kent Town</suburb>
			<state>SA</state>
			<postcode>5067</postcode>
		</address>
		<phone>08 8363 2717</phone>
		<email>hello@them.com.au</email>
		<email>hello@them.com.au</email>
		<email_from>noreply@them.com.au</email_from>
		<email_contact>apolo@them.com.au</email_contact>
		<email_orders>apolo@them.com.au</email_orders>
		<logo>themlogo.png</logo>
	</company>  
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
	<sequence> 
		<table>tbl_sequence</table> 
	</sequence>
	<them_news> 
		<domain>http://www.them.com.au</domain>
		<page></page> 
	</them_news> 
	
	<group name="System">
	<!-- THIS SECTION IS USED TO MANAGE THE ADMINISTRATOR TABLE. ADMINISTRATORS AND USERS FOR THE CMS ARE MANAGED HERE. -->
	<section level="1">
		<showlist>FALSE</showlist>
		<url>administrator</url>
		<title>Administrators</title>
		<type>TABLE</type>
		<table>
			<name>tbl_admin</name>
			<id>admin_id</id>
			<field>admin_name</field>
			<deleted>admin_deleted</deleted>
			<log>
				<table>tbl_admin</table>
				<id>admin_id</id>
				<field>admin_id</field>
			</log>
		</table>
		<list_template>list.tpl</list_template>
		<edit_template>edit_admin.tpl</edit_template>
	</section>
	</group>
	
	<group name="Content">
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
  			<name>tags</name>
  			<table>tbl_tag</table>
  			<linkfield>listing_id</linkfield>
  			<field>tag_object_id</field>
  			<where>tag_object_table = 'tbl_listing'</where> 
  		</associated>
  		<associated> 
  			<name>gallery</name>
  			<table>tbl_gallery</table>
  			<linkfield>listing_id</linkfield>
  			<field>gallery_listing_id</field> 
  			<orderby>gallery_order ASC</orderby>
  		</associated>
  		<associated> 
  			<name>files</name>
  			<table>tbl_files</table>
  			<linkfield>listing_id</linkfield>
  			<field>files_listing_id</field> 
  		</associated>
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_page.tpl</edit_template>
  	</section>

  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>news</url>
  		<title>News</title>
  		<type>LISTING</type>
  		<type_id>2</type_id>
  		<root_parent_id>8</root_parent_id>
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
  		<associated> 
  			<name>gallery</name>
  			<table>tbl_gallery</table>
  			<linkfield>listing_id</linkfield>
  			<field>gallery_listing_id</field> 
  		</associated>
  		<associated> 
  			<name>files</name>
  			<table>tbl_files</table>
  			<linkfield>listing_id</linkfield>
  			<field>files_listing_id</field> 
  		</associated>
  		<associated> 
  			<name>testimonials</name>
  			<table>tbl_testimonial</table>
  			<linkfield>testimonial_id</linkfield>
  			<field>testimonial_object_id</field> 
  		</associated>
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_news.tpl</edit_template>
  	</section> 
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>faqs</url>
  		<title>FAQS</title>
  		<type>LISTING</type>
  		<type_id>3</type_id>
  		<root_parent_id>102</root_parent_id>
  		<options> 
  			<field recursive="true"> 
  				<name>listing_parent_id</name>
  				<table>tbl_listing</table>
  				<id>listing_object_id</id>
  				<reference>listing_name</reference> 
  				<where>listing_parent_flag = '1' AND listing_type_id = '3' AND listing_published = '1'</where> 
  			</field> 
  		</options>
  		<associated>
  			<name>tags</name>
  			<table>tbl_tag</table>
  			<linkfield>listing_id</linkfield>
  			<field>tag_object_id</field>
  			<where>tag_object_table = 'tbl_listing'</where> 
  		</associated>
  		<associated> 
  			<name>gallery</name>
  			<table>tbl_gallery</table>
  			<linkfield>listing_id</linkfield>
  			<field>gallery_listing_id</field> 
  		</associated>
  		<associated> 
  			<name>files</name>
  			<table>tbl_files</table>
  			<linkfield>listing_id</linkfield>
  			<field>files_listing_id</field> 
  		</associated>
  		<associated> 
  			<name>testimonials</name>
  			<table>tbl_testimonial</table>
  			<linkfield>testimonial_id</linkfield>
  			<field>testimonial_object_id</field> 
  		</associated>
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_faqs.tpl</edit_template>
  	</section>  
  </group>
	
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>