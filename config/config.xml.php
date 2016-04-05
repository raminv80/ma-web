<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true">
	<domain></domain>
	<google_analytics>
		<id>UA-</id>
	</google_analytics>
	<company>
		<name></name>
		<address>
			<street></street>
			<suburb></suburb>
			<state></state>
			<postcode></postcode>
		</address>
		<phone></phone>
		<email_from>noreply@them.com.au</email_from>
		<email_contact>apolo@them.com.au</email_contact>
		<email_orders>apolo@them.com.au</email_orders>
		<logo>logo.png</logo>
	</company> 
	<database> 
		<host>n7-mysql5-3.ilisys.com.au</host> 
		<user>themso</user> 
		<password>c@^^3L5tRu7s*n9ub11c</password> 
		<dbname>themso5_db</dbname> 
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
		<pageID>1</pageID>
	</index_page>
	<error404>
		<header>HTTP/1.0 404 Not Found</header>
		<template>standardpage.tpl</template>
		<pageID>32</pageID>
	</error404>
	<error403>
	  <header>HTTP/1.0 403 Forbidden</header>
		<!-- <template>403.tpl</template> -->
		<template>standardpage.tpl</template>
		<pageID>32</pageID>
	</error403>
	<error503>
	  <header>HTTP/1.0 503 Service Temporarily Unavailable</header>
		<template>503.tpl</template>
	</error503>
		<search>
		<template>search.tpl</template>
		<pageID>28</pageID>
	</search>
	<static_page>
		<url>contact-us</url>
		<template>contact-us.tpl</template>
		<pageID>6</pageID>
	</static_page>
	
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
		<url>process/load-more</url>
		<file>includes/processes/load-more.php</file>
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