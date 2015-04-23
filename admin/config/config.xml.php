<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true"> 
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
		<dbname>themso11_db</dbname> 
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
  		<custom field="listing_object_id" value="93">
  			<template>custom_home_edit_page.tpl</template>
  		</custom>
  		<custom field="listing_object_id" value="103">
  			<template>custom_mediacentre_edit_page.tpl</template>
  		</custom>
  	</section>

  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>issue</url>
  		<title>Issues</title>
  		<type>LISTING</type>
  		<type_id>2</type_id>
  		<root_parent_id>101</root_parent_id>
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
  		  <name>updates</name>
  			<table>tbl_issue_update</table>
  			<linkfield>listing_id</linkfield>
  			<field>issue_update_listing_id</field> 
  		</associated>
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_issue.tpl</edit_template>
  	</section> 
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>campaign</url>
  		<title>Campaigns &amp; Petitions</title>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_campaign.tpl</edit_template>
  	</section>  
  </group>
	
	<group name="Media Centre">
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>media-centre</url>
  		<title>Media Centre Groups</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>103</root_parent_id>
  		<where>listing_parent_flag = '1'</where> 
  		<associated>
  			<name>tags</name>
  			<table>tbl_tag</table>
  			<linkfield>listing_id</linkfield>
  			<field>tag_object_id</field>
  			<where>tag_object_table = 'tbl_listing'</where> 
  		</associated>
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list_media_group.tpl</list_template>
  		<edit_template>edit_media_group.tpl</edit_template>
  	</section>
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>media-release</url>
  		<title>Media Releases</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>111</root_parent_id>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_media_mediarelease.tpl</edit_template>
  	</section>
    <section level="1">
  		<showlist>FALSE</showlist>
  		<url>article</url>
  		<title>Articles</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>112</root_parent_id>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_media_article.tpl</edit_template>
  	</section>
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>video</url>
  		<title>Videos</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>120</root_parent_id>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_media_video.tpl</edit_template>
  	</section>
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>speech</url>
  		<title>Speeches</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>121</root_parent_id>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_media_speech.tpl</edit_template>
  	</section>
	  <section level="1">
  		<showlist>FALSE</showlist>
  		<url>transcript</url>
  		<title>Transcripts</title>
  		<type>LISTING</type>
  		<type_id>4</type_id>
  		<root_parent_id>122</root_parent_id>
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
  		<log>
  			<table>tbl_listing</table>
  			<id>listing_id</id>
  			<field>listing_object_id</field>
  		</log>
  		<list_template>list.tpl</list_template>
  		<edit_template>edit_media_transcript.tpl</edit_template>
  	</section>
	</group>	
	<!-- <group name="Communication">
  	<section level="1">
  		<showlist>FALSE</showlist>
  		<url>contactus</url>
  		<title>Contact Us</title>
  		<type>TABLE</type>
  		<table>
  			<name>tbl_email_copy</name>
  			<id>email_id</id>
  			<field>email_from</field>
  			<deleted>email_deleted</deleted>
  		</table>
  		<list_template>list_emails.tpl</list_template>
  		<edit_template>view_email.tpl</edit_template>
  	</section>
	</group> -->
	
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>