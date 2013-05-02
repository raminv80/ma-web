<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false">
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
	</static_page>
	<listing_page name="news">
		<url>news</url><!-- This element name is the base URL which will be used as part of the matching. Tables can have any number of sub tables which will be linked using a linking table (name convention) -->
		<pageID>9</pageID>
		<ID>69</ID>
		<file>ListClass</file>
		<template>news-listing.tpl</template>
		<limit>5</limit>
		<orderby>news_date DESC</orderby>
		<table><!-- This table will be the details table -->
			<name>tbl_article</name>
			<field>article_title</field><!-- The field used to match the URL -->
			<template>news-item.tpl</template><!-- The template used if the field is matched -->
		</table>
		<menu>
			<field>COUNT(article_date) AS num, DATE_FORMAT(article_date,'%M') AS month, DATE_FORMAT(article_date,'%Y') AS year</field>
			<groupby>DATE_FORMAT(article_date,'%Y'),DATE_FORMAT(article_date,'%M')</groupby>
			<orderby>DATE_FORMAT(article_date,'%Y') DESC</orderby>
			<template>news_month_archive.tpl</template>
		</menu>
		<filter>
			<field>CONCAT(DATE_FORMAT(article_date,'%Y'),"-",DATE_FORMAT(article_date,'%M'))</field>
			<title>CONCAT(" - ",DATE_FORMAT(article_date,'%M'),"(",COUNT(article_date),")")</title>
		</filter>
	</listing_page>
	<listing_page name="faqs">
		<url>faqs</url><!-- This element name is the base URL which will be used as part of the matching. Tables can have any number of sub tables which will be linked using a linking table (name convention) -->
		<pageID>4</pageID>
		<ID>70</ID>
		<file>ListClass</file>
		<template>faq.tpl</template>
		<admin_template></admin_template>
		<table><!-- This table will be the details table -->
			<name>tbl_faqs</name>
			<field></field><!-- The field used to match the URL -->
			<template></template><!-- The template used if the field is matched -->
		</table>
	</listing_page>
	<listing_page name="videos">
		<url>videos</url><!-- This element name is the base URL which will be used as part of the matching. Tables can have any number of sub tables which will be linked using a linking table (name convention) -->
		<pageID>6</pageID>
		<ID>71</ID>
		<file>ListClass</file>
		<template>videos.tpl</template>
		<admin_template></admin_template>
		<orderby>video_date DESC</orderby>
		<table><!-- This table will be the details table -->
			<name>tbl_video</name>
			<field>video_title</field><!-- The field used to match the URL -->
			<template></template><!-- The template used if the field is matched -->
		</table>
	</listing_page>
	<listing_page name="bulletin">
		<url>bulletin</url><!-- This element name is the base URL which will be used as part of the matching. Tables can have any number of sub tables which will be linked using a linking table (name convention) -->
		<pageID>4</pageID>
		<ID>69</ID>
		<file>ListClass</file>
		<template>bulletins.tpl</template>
		<admin_template></admin_template>
		<orderby>bulletin_date DESC</orderby>
		<table><!-- This table will be the details table -->
			<name>tbl_bulletin</name>
			<field></field><!-- The field used to match the URL -->
			<template>bulletins.tpl</template><!-- The template used if the field is matched -->
		</table>
		<menu>
			<field>COUNT(bulletin_date) AS num, DATE_FORMAT(bulletin_date,'%M') AS month, DATE_FORMAT(bulletin_date,'%Y') AS year</field>
			<groupby>DATE_FORMAT(bulletin_date,'%Y'),DATE_FORMAT(bulletin_date,'%M')</groupby>
			<orderby>DATE_FORMAT(bulletin_date,'%Y') DESC</orderby>
			<template>bulletin_month_archive.tpl</template>
		</menu>
		<filter>
			<field>CONCAT(DATE_FORMAT(bulletin_date,'%Y'),"-",DATE_FORMAT(bulletin_date,'%M'))</field>
			<title>CONCAT(" - ",DATE_FORMAT(bulletin_date,'%M'),"(",COUNT(bulletin_date),")")</title>
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