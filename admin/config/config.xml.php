<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true"> 
	<company></company> 
	<database> 
		<host>n7-mysql5-3.ilisys.com.au</host> 
		<user>themso</user> 
		<password>c@^^3L5tRu7s*n9ub11c</password> 
		<dbname>themso15_db</dbname> 
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
	
	<!-- THIS SECTION IS USED TO MANAGE THE LISTING TYPES TABLE. IT SHOULD NOT BE VISIBLE ON THE LIVE VERSION. --> <!-- <section>
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
		</section> --> <!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE PAGE. THIS IS A LISTING AT IT'S BASIC FORM. -->
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
		
		<custom_template field="listing_id" value="8">custom_home_edit_page.tpl</custom_template>	<!-- Home -->
		<custom_template field="listing_id" value="9">redirect_edit_page.tpl</custom_template> <!-- Punter's Corner  -->
		<custom_template field="listing_id" value="13">redirect_edit_page.tpl</custom_template> <!-- New to Wagering  -->
		<custom_template field="listing_id" value="11">custom_leading_driver_edit_page.tpl</custom_template> <!--  Leading Trainer / Driver Profile -->
		
	</section>

	 <!-- THIS SECTION IS USED TO MANAGE THE LISTINGS OF TYPE "NEWS". THIS IS A LISTING
	 INCLUDES THE GALLERY TABLE AS AN ASSOCIATE. THIS ALLOWS USERS TO SAVE CONTENT INTO
	 THE GALLERY TABLE WITH THE CURRENT ID. IT ALSO INCLUDES AN EXTENDED TABLE WITH 
	 ADDITIONAL FIELDS NEEDED FOR THIS CATEGORY.--> 
	 <section>
		<showlist>FALSE</showlist>
		<url>blog</url>
		<title>Blog Post</title>
		<type>LISTING</type>
		<type_id>4</type_id>
		<parent_id>18</parent_id>
		<extends>
			<table>tbl_news</table>
			<field>news_listing_id</field>
		</extends>
		<list_template>list.tpl</list_template>
		<edit_template>edit_news.tpl</edit_template>
	</section>  
	
	<!-- THIS SECTION IS USED TO MANAGE THE HORSE TABLE. -->
	<section>
		<showlist>FALSE</showlist>
		<url>horse</url>
		<title>Horse</title>
		<type>TABLE</type>
		<table>
			<name>tbl_horse</name>
			<id>horse_id</id>
			<field>horse_name</field>
			<deleted>horse_deleted</deleted>
			<orderby>horse_name ASC</orderby>
		</table>
		<list_template>list.tpl</list_template>
		<edit_template>edit_horse.tpl</edit_template>
	</section>
	
	<!-- THIS SECTION IS USED TO MANAGE THE HORSE TABLE. -->
	<section>
		<showlist>FALSE</showlist>
		<url>meeting</url>
		<title>Meeting</title>
		<type>TABLE</type>
		<table>
			<name>tbl_meeting</name>
			<id>meeting_id</id>
			<field>meeting_title</field>
			<field>DATE_FORMAT(meeting_date,'%d-%b-%Y')</field>
			<deleted>meeting_deleted</deleted>
			<orderby>meeting_date DESC</orderby>
			<associated> 
				<id>race_id</id>
				<name>race</name>
				<table>tbl_race</table>
				<field>race_meeting_id</field> 
				<orderby>race_start_time ASC</orderby>
				<associated> 
					<id>race_id</id>
					<name>entrant</name>
					<table>tbl_entrant</table>
					<field>entrant_race_id</field> 
				</associated>
			</associated>
			<options> 
				<field> 
					<name>entrant_horse_id</name>
					<table>tbl_horse</table>
					<reference>horse_name</reference> 
				</field> 
			</options>
		</table>
		<list_template>list.tpl</list_template>
		<edit_template>edit_meeting.tpl</edit_template>
	</section>

	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>