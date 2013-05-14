<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true">
	<company></company>
	<database>
		<host>m4-mysql1-1.ilisys.com.au</host>
		<user>themso</user>
		<password>c@^^3L5tRu7s*n9ub11c</password>
		<dbname>themso3_db</dbname>
	</database>
	<index_page>
		<template>home.tpl</template>
		<pageID>1</pageID>
	</index_page>
	<search>
		<template>search.tpl</template>
		<pageID>1</pageID>
	</search>
	<static_page>
		<url>contact-us</url>
		<template>contactus.tpl</template>
		<pageID>2</pageID>
		<!-- <table>
			<name>tbl_extra</name>
			<relID>news_listing_id</relID>
		</table> -->
	</static_page>
	<listing_page name="news">
		<url>news</url><!-- This element name is the base URL which will be used as part of the matching. Tables can have any number of sub tables which will be linked using a linking table (name convention) -->
		<pageID>9</pageID>
		<ID>69</ID>
		<file>ListClass</file>
		<template>news-listing.tpl</template>
		<limit>5</limit>
		<orderby>news_start_date DESC</orderby>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<template>news-item.tpl</template><!-- The template used if the field is matched -->
			<table><!-- This table will be the details table -->
				<name>tbl_news</name>
			</table>
		</table>
		<menu>
			<field>COUNT(news_start_date) AS num, DATE_FORMAT(news_start_date,'%M') AS month, DATE_FORMAT(news_start_date,'%Y') AS year</field>
			<groupby>DATE_FORMAT(news_start_date,'%Y'),DATE_FORMAT(news_start_date,'%M')</groupby>
			<orderby>DATE_FORMAT(news_start_date,'%Y') DESC</orderby>
			<template>news_month_archive.tpl</template>
		</menu>
		<filter>
			<field>CONCAT(DATE_FORMAT(news_start_date,'%Y'),"-",DATE_FORMAT(news_start_date,'%M'))</field>
			<title>CONCAT(" - ",DATE_FORMAT(news_start_date,'%M'),"(",COUNT(news_start_date),")")</title>
		</filter>
	</listing_page>
	<smartytemplate_config><!-- This element contains the smarty template values -->
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
	<payment_gateway><!-- This element contains BANK details -->
		<bank name="NAB" live="false">
			<file></file>
			<test_url></test_url>
			<live_url></live_url>
			<merchant_id></merchant_id>
			<merchant_password></merchant_password>
		</bank>
	</payment_gateway>
</config>