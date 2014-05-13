<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="false">
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
			<template>landingpage.tpl</template>
		</table>
		<template>landingpage.tpl</template>
	</page_strut>
	<index_page>
		<template>landingpage.tpl</template>
		<pageID>343</pageID>
	</index_page>
	<error404>
	  <header>HTTP/1.0 404 Not Found</header>
		<template>landingpage.tpl</template>
		<pageID>343</pageID>
	</error404>
	<error403>
	  <header>HTTP/1.0 403 Forbidden</header>
		<template>landingpage.tpl</template>
		<pageID>343</pageID>
	</error403>
	<error503>
	  <header>HTTP/1.0 503 Service Temporarily Unavailable</header>
		<template>landingpage.tpl</template>
		<pageID>343</pageID>
	</error503>
	<subsite>
	  <url>diy</url>
	  <config>/config/diy.config.xml.php</config>
	</subsite>
	<subsite>
	  <url>trade</url>
	  <config>/config/trade.config.xml.php</config>
	</subsite>
	<smartytemplate_config>
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>