<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true">
	<domain></domain>
	<google_analytics>
		<id>UA-33575281-1</id>
	</google_analytics>
	<company>
		<name>Christoper Pyne MP</name>
		<address>
			<street>429 Magill Road</street>
			<suburb>St Morris</suburb>
			<state>SA</state>
			<postcode>5068</postcode>
		</address>
		<phone>(08) 8431 2277</phone>
		<email>info@pyneonline.com.au</email>
		<email_from>noreply@pyneonline.com.au</email_from>
		<email_contact>sarah@them.com.au</email_contact>
		<logo>logo.png</logo>
	</company> 
	<database> 
		<host>n7-mysql5-3.ilisys.com.au</host> 
		<user>themso</user> 
		<password>c@^^3L5tRu7s*n9ub11c</password> 
		<dbname>themso11_db</dbname> 
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
				<orderby>gallery_order ASC</orderby>
			</associated>
		</table>
		<template>standardpage.tpl</template><!-- The template used if the field is matched -->
	</page_strut>
	<index_page>
		<template>home.tpl</template>
		<pageID>93</pageID>
		<additional>
		  <tag>listing_page</tag>
		  <name>homemediacentre</name>
		  <data>news</data>
		</additional>
	</index_page>
	<error404>
		<header>HTTP/1.0 404 Not Found</header>
		<template>standardpage.tpl</template>
		<pageID>135</pageID>
	</error404>
	<error403>
	  <header>HTTP/1.0 403 Forbidden</header>
		<template>403.tpl</template>
	</error403>
	<error503>
	  <header>HTTP/1.0 503 Service Temporarily Unavailable</header>
		<template>503.tpl</template>
	</error503>
	<search>
		<template>search.tpl</template>
		<pageID>136</pageID>
	</search>
	<static_page>
		<url>have-your-say</url>
		<template>contact-us.tpl</template>
		<pageID>104</pageID>
	</static_page>

	<listing_page name="successful-campaigns">
	  <url>issues/successful-campaigns-and-petitions</url>
	  <pageID>327</pageID>
 	  <root_parent_id>102</root_parent_id> 
		<type>3</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_start DESC</orderby>
		<where><![CDATA[(listing_schedule_end < NOW() OR listing_schedule_end IS NULL)]]></where>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>
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
			<template>campaign.tpl</template> 
		</table>
		<template>campaign-list.tpl</template> 
	</listing_page>
	<listing_page name="campaigns">
	  <url>issues/campaigns-and-petitions</url>
	  <pageID>102</pageID>
 	  <root_parent_id>102</root_parent_id> 
		<type>3</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_start DESC</orderby>
		<where><![CDATA[(listing_schedule_start < NOW() OR listing_schedule_start IS NULL) AND (listing_schedule_end > NOW() OR listing_schedule_end IS NULL)]]></where>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>
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
			<template>campaign.tpl</template> 
		</table>
		<template>campaign-list.tpl</template> 
	</listing_page>
	
	<listing_page name="successful-issues">
	  <url>issues/successful-issues</url>
	  <pageID>326</pageID>
 	  <root_parent_id>101</root_parent_id> 
		<type>2</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_start DESC</orderby>
		<where><![CDATA[(listing_schedule_end < NOW() OR listing_schedule_end IS NULL)]]></where>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>
			<associated> 
				<id>issue_update_id</id>
				<name>issue_updates</name>
				<table>tbl_issue_update</table>
				<linkfield>listing_id</linkfield>
				<field>issue_update_listing_id</field> 
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
			<template>issue.tpl</template> 
		</table>
		<template>issue-list.tpl</template> 
	</listing_page>
	<listing_page name="issues">
	  <url>issues</url>
	  <pageID>101</pageID>
 	  <root_parent_id>101</root_parent_id> 
		<type>2</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_start DESC</orderby>
		<where><![CDATA[(listing_schedule_start < NOW() OR listing_schedule_start IS NULL) AND (listing_schedule_end > NOW() OR listing_schedule_end IS NULL)]]></where>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>
			<associated> 
				<id>issue_update_id</id>
				<name>issue_updates</name>
				<table>tbl_issue_update</table>
				<linkfield>listing_id</linkfield>
				<field>issue_update_listing_id</field> 
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
			<template>issue.tpl</template> 
		</table>
		<template>issue-list.tpl</template> 
	</listing_page>
	
<!-- <listing_page name="mediacentrearchive">
	  <url>media-centre-archive</url>
 	  <root_parent_id>103</root_parent_id> 
		<type>4</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_end DESC</orderby>
		<where><![CDATA[(listing_schedule_end < NOW() OR listing_schedule_end IS NULL)]]></where>
		<limit level="1">20</limit>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>			
			<associated listing="false">
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<associated listing="true">
				<name>tags</name>
				<table>tbl_tag</table>
				<linkfield>listing_id</linkfield>
				<field>tag_object_id</field>
			</associated>
			<associated listing="false">
				<name>files</name>
				<table>tbl_files</table>
				<linkfield>listing_id</linkfield>
				<field>files_listing_id</field>
			</associated>
			<options> 
  			<field> 
  				<name>listing_counts</name>
  				<table>tbl_listing</table>
  				<id>listing_parent_id</id>
  				<reference>COUNT(listing_id)</reference> 
  				<where>listing_type_id = 4 AND listing_parent_id != '103' AND listing_deleted IS NULL AND listing_published = 1 GROUP BY listing_parent_id</where> 
  			</field> 
  		</options>
			<template>media-item.tpl</template> 
		</table>
		<template>mediacentre-list.tpl</template> 
		<loadmoretemplate>mediacentre-structure.tpl</loadmoretemplate>  
	</listing_page> -->	
	
	<listing_page name="mediacentre">
	  <url>media-centre</url>
 	  <root_parent_id>103</root_parent_id> 
		<type>4</type> 
		<file>ListClass</file>
		<orderby>listing_schedule_start DESC</orderby>
		<where><![CDATA[(listing_schedule_start < NOW() OR listing_schedule_start IS NULL)]]></where>
		<limit level="1">20</limit>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>			
			<associated listing="false">
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<associated listing="true">
				<name>tags</name>
				<table>tbl_tag</table>
				<linkfield>listing_id</linkfield>
				<field>tag_object_id</field>
			</associated>
			<associated listing="false">
				<name>files</name>
				<table>tbl_files</table>
				<linkfield>listing_id</linkfield>
				<field>files_listing_id</field>
			</associated>
			<options> 
  			<field> 
  				<name>listing_counts</name>
  				<table>tbl_listing</table>
  				<id>listing_parent_id</id>
  				<reference>COUNT(listing_id)</reference> 
  				<where>listing_type_id = 4 AND listing_parent_id != '103' AND listing_deleted IS NULL AND listing_published = 1 GROUP BY listing_parent_id</where> 
  			</field> 
  		</options>
			<template>media-item.tpl</template> 
		</table>
		<template>mediacentre-list.tpl</template>
		<loadmoretemplate>mediacentre-structure.tpl</loadmoretemplate>  
	</listing_page>
	
	<listing_page name="homemediacentre" ignoreparent="1">
	  <url>home-media-centre</url>
		<type>4</type> 
		<file>ListClass</file>
		<where><![CDATA[(listing_schedule_start < NOW() OR listing_schedule_start IS NULL) AND (listing_schedule_end > NOW() OR listing_schedule_end IS NULL) AND listing_parent_flag = 0 AND listing_parent_id != '120']]></where>
		<orderby>listing_schedule_start DESC</orderby>
		<limit>2</limit>
		<table>	
			<name>tbl_listing</name>
			<field>listing_url</field>			
			<associated listing="true">
				<name>gallery</name>
				<table>tbl_gallery</table>
				<linkfield>listing_id</linkfield>
				<field>gallery_listing_id</field>
			</associated>
			<associated listing="true">
				<name>tags</name>
				<table>tbl_tag</table>
				<linkfield>listing_id</linkfield>
				<field>tag_object_id</field>
			</associated>
			<associated listing="true">
				<name>files</name>
				<table>tbl_files</table>
				<linkfield>listing_id</linkfield>
				<field>files_listing_id</field>
			</associated>
			<extends>
  			<table>cache_tbl_listing</table>
  			<linkfield>listing_object_id</linkfield>
  			<field>cache_record_id</field>
  		</extends>
		</table>
	</listing_page>
	
	<process>
		<url>process/contact-us</url>
		<file>includes/processes/processes-contactus.php</file>
		<return_url></return_url>
	</process>
	<process>
		<url>process/e-newsletter</url>
		<file>includes/processes/process-ajax-campaignmonitor.php</file>
		<return_url></return_url>
	</process>	
	<process>
		<url>process/load-more</url>
		<file>includes/processes/load-more.php</file>
		<return_url></return_url>
	</process>	 	
	<!-- <process>
		<url>process/news</url>
		<file>includes/processes/news_v2.php</file>
		<return_url></return_url>
	</process>	 -->
	
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