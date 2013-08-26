<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true">
	<company></company>
	<database>
		<host>n7-mysql5-3.ilisys.com.au</host>
		<user>themso</user>
		<password>c@^^3L5tRu7s*n9ub11c</password>
		<dbname>themso5_db</dbname>
	</database>
	<page_strut>
		<type>1</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<field>gallery_listing_id</field>
			</associated>
		</table>
		<template>standardpage.tpl</template><!-- The template used if the field is matched -->
	</page_strut>
	<index_page>
		<template>home.tpl</template>
		<pageID>1</pageID>
	</index_page>
	<error404>
		<template>404.tpl</template>
		<pageID>26</pageID>
	</error404>
	<search>
		<template>search.tpl</template>
		<pageID>2</pageID>
	</search>
	<socialwall name="community" ads="false" instagram="true" facebook="false" twitter="false" youtube="false">
		<url>community</url>
		<pageID>5</pageID>
		<template>community.tpl</template>
		<tag>cocolat</tag>
		<table>ntbl_social</table>
 	</socialwall>
 	<static_page>
		<url>franchise-opportunities</url>
		<template>franchise-opportunities.tpl</template>
		<pageID>6</pageID>
	</static_page>
	<listing_page name="our-menu">
		<url>our-menu</url>
		<pageID>3</pageID>
		<type>2</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<field>gallery_listing_id</field>
			</associated>
			<template>product.tpl</template>
		</table>
		<template>our-menu.tpl</template><!-- The template used if the field is matched -->
 	</listing_page>
 	

	<!-- <listing_page name="contact-us">
		<url>contact-us</url>
		<type>1</type>
		<pageID>5</pageID>
		<ID>72</ID>
		<file>ListClass</file>
		<template>contactus.tpl</template>
		<table>
			<name>tbl_listing</name>
			<where>(listing_id = '7' OR listing_id = '8' )</where>
		</table>
 	</listing_page>
	<listing_page name="news">
		<url>news</url>
		<type>2</type>
		<pageID>1</pageID>
		<ID>72</ID>
		<file>ListClass</file>
		<template>news-listing.tpl</template>
		<limit>5</limit>
		<orderby>news_start_date DESC</orderby>
		<table>
			<name>tbl_listing</name>
			<field>listing_url</field>
			<extra>tbl_news</extra>
			<template>news-item.tpl</template>
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
	<listing_page name="product-care">
		<url>product-care</url>
		<type>6</type>
		<pageID>23</pageID>
		<ID>72</ID>
		<file>ListClass</file>
		<template>product-care.tpl</template>
		<limit>50</limit>
		<orderby>listing_order DESC</orderby>
		<table>
			<name>tbl_listing</name>
			<field>listing_url</field>
			<template>product-care-item.tpl</template>
		</table>
 	</listing_page>
 	<listing_page name="specials">
		<url>specials</url>
		<type>5</type>
		<pageID>22</pageID>
		<ID>72</ID>
		<file>ListClass</file>
		<template>specials.tpl</template>
		<limit>50</limit>
		<orderby>listing_order </orderby>
		<table>
			<name>tbl_listing</name>
			<field>listing_url</field>
			<template>specials.tpl</template>
			<orderby>listing_order </orderby>
		</table>
 	</listing_page>
	<listing_page name="products">
		<file>ProductListing</file>
		<type>4</type>
		<url>products</url>
		<pageID>30</pageID>
		<item_template>product-category-template.tpl</item_template>
		<category_template>product-category-template.tpl</category_template>
	</listing_page>-->
	<smartytemplate_config>
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>