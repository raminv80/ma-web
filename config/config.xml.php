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
		<renting>rentingstandardpage.tpl</renting>
	</page_strut>
	<index_page>
		<template>home.tpl</template>
		<pageID>26</pageID>
	</index_page>
	<error404>
		<template>404.tpl</template>
		<pageID>46</pageID>
	</error404>
	<inspections>
		<template>inspections.tpl</template>
		<pageID>35</pageID>
	</inspections>
 	<static_page>
		<url>property-management/appraisal-request</url>
		<template>appraisal-request.tpl</template>
		<pageID>31</pageID>
	</static_page>
	<static_page>
		<url>renting/tenancy-application</url>
		<template>tenancy-application.tpl</template>
		<pageID>36</pageID>
	</static_page>
	<static_page>
		<url>about-us/testimonials</url>
		<template>testimonials.tpl</template>
		<pageID>43</pageID>
	</static_page>
	<static_page>
		<url>contact-us</url>
		<template>contact-us.tpl</template>
		<pageID>44</pageID>
	</static_page>

	
 	<listing_page name="properties-for-rent">
		<url>renting/properties-for-rent</url>
		<pageID>34</pageID>
		<type>2</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<options>
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
			<associated listing="true">
				<name>inspection</name>
				<table>tbl_inspection</table>
				<field>inspection_listing_id</field>
			</associated>
			<extends>
				<table>tbl_property</table>
				<field>property_listing_id</field>
			</extends>
			<template>property.tpl</template>
		</table>
		<template>properties-for-rent.tpl</template><!-- The template used if the field is matched -->
 	</listing_page>
 <!--	<listing_page name="inspections">
		<url>renting/inspections</url>
		<pageID>35</pageID>
		<type>2</type>
		<file>ListClass</file>
		<table>
			<name>tbl_listing</name>
			<field>listing_url</field>
			<options>
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
			<template>property.tpl</template>
		</table>
		<template>inspections.tpl</template>
 	</listing_page>-->
 	<listing_page name="news">
		<url>news</url>
		<pageID>40</pageID>
		<type>3</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<orderby>news_start_date DESC</orderby>
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<field>gallery_listing_id</field>
			</associated>
			<extends>
				<table>tbl_news</table>
				<field>news_listing_id</field>
			</extends>
			<template>news-article.tpl</template>
		</table>
		<template>news.tpl</template><!-- The template used if the field is matched -->
 	</listing_page>
 	<listing_page name="our-team">
		<url>about-us/our-team</url>
		<pageID>42</pageID>
		<type>4</type>
		<file>ListClass</file>
		<table><!-- This table will be the details table -->
			<name>tbl_listing</name>
			<field>listing_url</field><!-- The field used to match the URL -->
			<associated>
				<name>gallery</name>
				<table>tbl_gallery</table>
				<field>gallery_listing_id</field>
			</associated>
			<template>team-member.tpl</template>
		</table>
		<template>our-team.tpl</template><!-- The template used if the field is matched -->
 	</listing_page>

	<smartytemplate_config>
		<templates>/templates</templates>
		<templates_c>/templates_c</templates_c>
		<cache>/cache</cache>
		<configs>/configs</configs>
		<plugins>/plugins</plugins>
	</smartytemplate_config>
</config>