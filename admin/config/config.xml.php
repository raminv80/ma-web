<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="false" staging="true"> 
  <domain></domain>
  <google_analytics>
  	<id>UA-</id>
  	<old_id></old_id>
  </google_analytics>
  <company>
    <name>Australia Medic Alert Foundation</name>
    <address>
      <street>Level 1, 210 Greenhill Road</street>
        <suburb>Eastwood</suburb>
		<state>SA</state>
		<postcode>5063</postcode>
    </address>
    <phone>(+61) 882 738 401</phone>
    <toll_free>1800 88 22 22</toll_free>
    <fax>1800 64 32 59</fax>
    <email>enquiry@medicalert.org.au</email>
    <email_from>noreply@themdigital.com.au</email_from>
    <email_contact>apolo@them.com.au</email_contact>
    <email_orders>apolo@them.com.au</email_orders>
    <logo>logo.png</logo>
  </company> 
  <global_variable>
    <name>gst</name>
    <value>10</value>
  </global_variable> 
  <global_variable>
    <name>membership_fee</name>
    <value>$32</value>
  </global_variable> 
  <database> 
  	<host>122.201.97.172</host> 
    <user>them_usr01</user> 
    <password>OTwFwL?pSnR+</password> 
    <dbname>them_db01</dbname> 
  </database> 
  <resource> 
  	<url>file-manager</url> 
  	<template>filemanager.tpl</template> 
  </resource>
  <sequence> 
  	<table>tbl_sequence</table> 
  </sequence>
	
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
      	<name>additionals</name>
      	<table>tbl_additional</table>
      	<linkfield>listing_id</linkfield>
      	<field>additional_listing_id</field> 
        <orderby>additional_category, additional_order, additional_name</orderby>
      </associated>
      <associated> 
        <name>files</name>
        <table>tbl_files</table>
        <linkfield>listing_id</linkfield>
        <field>files_listing_id</field> 
      </associated>
      <options> 
        <field>
          <name>listing_parent_id</name>
          <table>tbl_listing</table>
          <id>listing_object_id</id>
          <reference>listing_name</reference> 
          <where>listing_parent_flag = 1 AND listing_type_id = 1 AND listing_published = 1</where> 
        </field> 
        <field> 
          <name>banners</name>
          <table>tbl_banner</table>
          <id>banner_id</id>
          <reference>banner_name</reference>
          <extra>banner_flag1</extra> 
          <orderby>banner_name</orderby> 
        </field> 
      </options>
      <log>
      	<table>tbl_listing</table>
      	<id>listing_id</id>
      	<field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>edit_page.tpl</edit_template>
      <custom_template field="listing_object_id" value="1">edit_page_home.tpl</custom_template>
      <custom_template field="listing_object_id" value="2">edit_page_about.tpl</custom_template>
      <custom_template field="listing_object_id" value="3">edit_page_contact-us.tpl</custom_template>
      <custom_template field="listing_object_id" value="102">edit_page_contact-us.tpl</custom_template>
      <custom_template field="listing_object_id" value="108">edit_page_contact-us.tpl</custom_template>
      <custom_template field="listing_object_id" value="101">edit_page_refer-a-friend.tpl</custom_template>
      <custom_template field="listing_object_id" value="94">edit_page_benefits-of-membership.tpl</custom_template>
      <custom_template field="listing_object_id" value="95">edit_page_who-needs-membership.tpl</custom_template>
      <custom_template field="listing_object_id" value="103">edit_page_faqs.tpl</custom_template>
      <custom_template field="listing_object_id" value="107">edit_page_corporate-partners.tpl</custom_template>
      <custom_template field="listing_object_id" value="9">ec_edit_page_my-account.tpl</custom_template>
      <custom_template field="listing_object_id" value="12">ec_edit_page_shopping-cart.tpl</custom_template>
      <custom_template field="listing_object_id" value="13">ec_edit_page_checkout.tpl</custom_template>
      <custom_template field="listing_object_id" value="222">ec_edit_page_autorenewal.tpl</custom_template><!-- Quick renew -->
      <custom_template field="listing_object_id" value="650">ec_edit_page_autorenewal.tpl</custom_template><!-- Auto-renewal -->
      <custom_template field="listing_object_id" value="690">ec_edit_page_autorenewal.tpl</custom_template><!-- Quick checkout -->
      <custom_template field="listing_object_id" value="219">ec_edit_page_update-my-profile.tpl</custom_template>
      <custom_template field="listing_object_id" value="239">ec_edit_page_my-account.tpl</custom_template><!-- wish list -->
      <custom_template field="listing_object_id" value="109">edit_page_careers.tpl</custom_template><!-- Careers -->
  	</section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>landing-page</url>
      <title>Landing pages</title>
      <type>LISTING</type>
      <type_id>6</type_id>
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
      <log>
        <table>tbl_listing</table>
        <id>listing_id</id>
        <field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>edit_landing_page.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>menu</url>
      <title>Menu</title>
      <type>TABLE</type>
      <table recursive="true">
        <name>tbl_menu</name>
        <id>menu_id</id>
        <field>menu_name</field>
        <parent_field>menu_parent_id</parent_field>
        <deleted>menu_deleted</deleted>
        <orderby>menu_location DESC, menu_order, menu_name</orderby>
        <options> 
          <field recursive="true">
            <name>pages</name>
            <table>tbl_listing</table>
            <id>listing_object_id</id>
            <reference>listing_name</reference> 
            <orderby>listing_name</orderby>
            <where>listing_type_id = 1 AND listing_published = 1 AND listing_display_menu = 1</where> 
          </field> 
          <field recursive="true">
            <name>menus</name>
            <table>tbl_menu</table>
            <id>menu_id</id>
            <reference>menu_name</reference> 
            <extra>menu_location</extra>
            <where>menu_listing_id IS NOT NULL AND menu_listing_id != 0</where> 
          </field> 
        </options>
        <log>
          <table>tbl_menu</table>
          <id>menu_id</id>
          <field>menu_id</field>
        </log>
      </table>
      <list_template>list_menu.tpl</list_template>
      <edit_template>edit_menu.tpl</edit_template>
    </section>
    
  	<section level="1">
      <showlist>FALSE</showlist>
      <url>news-article</url>
      <title>News article</title>
      <type>LISTING</type>
      <type_id>2</type_id>
      <root_parent_id>17</root_parent_id>
      <extends>
        <table>tbl_news</table>
        <linkfield>listing_id</linkfield>
        <field>news_listing_id</field>
      </extends>
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
      <log>
      	<table>tbl_listing</table>
      	<id>listing_id</id>
      	<field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>edit_news_article.tpl</edit_template>
  	</section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>newsletter</url>
      <title>Newsletter</title>
      <type>LISTING</type>
      <type_id>3</type_id>
      <root_parent_id>17</root_parent_id>
      <extends>
        <table>tbl_news</table>
        <linkfield>listing_id</linkfield>
        <field>news_listing_id</field>
      </extends>
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
      <list_template>list.tpl</list_template>
      <edit_template>edit_newsletter.tpl</edit_template>
    </section> 
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>videos</url>
      <title>Videos</title>
      <type>LISTING</type>
      <type_id>5</type_id>
      <root_parent_id>17</root_parent_id>
      <extends>
        <table>tbl_news</table>
        <linkfield>listing_id</linkfield>
        <field>news_listing_id</field>
      </extends>
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
      <list_template>list.tpl</list_template>
      <edit_template>edit_video.tpl</edit_template>
    </section> 
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>testimonials</url>
      <title>Testimonials</title>
      <type>LISTING</type>
      <type_id>4</type_id>
      <root_parent_id>104</root_parent_id>
      <log>
        <table>tbl_listing</table>
        <id>listing_id</id>
        <field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>edit_testimonial.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>banner-ads</url>
      <title>Banner ads</title>
      <type>TABLE</type>
      <table recursive="true">
        <name>tbl_banner</name>
        <id>banner_id</id>
        <field>banner_name</field>
        <deleted>banner_deleted</deleted>
        <orderby>banner_order, banner_name</orderby>
        <log>
          <table>tbl_banner</table>
          <id>banner_id</id>
          <field>banner_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>edit_banner.tpl</edit_template>
    </section>
    
  </group>
  
  <group name="Settings">
    <section level="1">
      <showlist>FALSE</showlist>
      <url>attributes</url>
      <title>Attributes</title>
      <type>TABLE</type>
      <table>
        <name>tbl_attribute</name>
        <id>attribute_id</id>
        <field>attribute_name</field>
        <deleted>attribute_deleted</deleted>
        <orderby>attribute_order, attribute_name</orderby>
        <associated> 
          <name>attr_values</name>
          <table>tbl_attr_value</table>
          <linkfield>attribute_id</linkfield>
          <field>attr_value_attribute_id</field> 
          <orderby>attr_value_order</orderby>
        </associated>
        <log>
          <table>tbl_attribute</table>
          <id>attribute_id</id>
          <field>attribute_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_attribute.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>schemas</url>
      <title>Schemas</title>
      <type>TABLE</type>
      <table>
        <name>tbl_producttype</name>
        <id>producttype_id</id>
        <field>producttype_name</field>
        <deleted>producttype_deleted</deleted>
        <orderby>producttype_name</orderby>
        <associated> 
          <name>productschemas</name>
          <table>tbl_productschema</table>
          <linkfield>producttype_id</linkfield>
          <field>productschema_type_id</field> 
        </associated>
        <options> 
          <field> 
            <name>attributes</name>
            <table>tbl_attribute</table>
            <id>attribute_id</id>
            <reference>attribute_name</reference> 
            <orderby>attribute_name</orderby>
          </field> 
        </options>
        <log>
          <table>tbl_producttype</table>
          <id>producttype_id</id>
          <field>producttype_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_producttype.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>product-types</url>
      <title>Product types</title>
      <type>TABLE</type>
      <table>
        <name>tbl_ptype</name>
        <id>ptype_id</id>
        <field>ptype_name</field>
        <deleted>ptype_deleted</deleted>
        <orderby>ptype_order, ptype_name</orderby>
        <log>
          <table>tbl_ptype</table>
          <id>ptype_id</id>
          <field>ptype_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_ptype.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>product-materials</url>
      <title>Product materials</title>
      <type>TABLE</type>
      <table>
        <name>tbl_pmaterial</name>
        <id>pmaterial_id</id>
        <field>pmaterial_name</field>
        <deleted>pmaterial_deleted</deleted>
        <orderby>pmaterial_order, pmaterial_name</orderby>
        <log>
          <table>tbl_pmaterial</table>
          <id>pmaterial_id</id>
          <field>pmaterial_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_pmaterial.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>product-deliveries</url>
      <title>Product deliveries</title>
      <type>TABLE</type>
      <table>
        <name>tbl_pdelivery</name>
        <id>pdelivery_id</id>
        <field>pdelivery_name</field>
        <deleted>pdelivery_deleted</deleted>
        <orderby>pdelivery_order, pdelivery_name</orderby>
        <log>
          <table>tbl_pdelivery</table>
          <id>pdelivery_id</id>
          <field>pdelivery_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_pdelivery.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>product-warranties</url>
      <title>Product warranties</title>
      <type>TABLE</type>
      <table>
        <name>tbl_pwarranty</name>
        <id>pwarranty_id</id>
        <field>pwarranty_name</field>
        <deleted>pwarranty_deleted</deleted>
        <orderby>pwarranty_order, pwarranty_name</orderby>
        <log>
          <table>tbl_pwarranty</table>
          <id>pwarranty_id</id>
          <field>pwarranty_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_pwarranty.tpl</edit_template>
    </section>
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>product-cares</url>
      <title>Product cares</title>
      <type>TABLE</type>
      <table>
        <name>tbl_pcare</name>
        <id>pcare_id</id>
        <field>pcare_name</field>
        <deleted>pcare_deleted</deleted>
        <orderby>pcare_order, pcare_name</orderby>
        <log>
          <table>tbl_pcare</table>
          <id>pcare_id</id>
          <field>pcare_id</field>
        </log>
      </table>
      <list_template>list_noviewbtn.tpl</list_template>
      <edit_template>ec_edit_pcare.tpl</edit_template>
    </section>
    
    
  </group>
  
  <group name="E-commerce">
    <section level="1">
      <showlist>FALSE</showlist>
      <url>prodcat</url>
      <title>Collections</title>
      <type>LISTING</type>
      <type_id>10</type_id>
      <root_parent_id>6</root_parent_id>
      <options> 
      	<field recursive="true"> 
          <name>listing_parent_id</name>
          <table>tbl_listing</table>
          <id>listing_object_id</id>
          <reference>listing_name</reference> 
          <where>listing_parent_flag = 1 AND listing_type_id = 10 AND listing_published = 1</where> 
      	</field>
        <field> 
          <name>banners</name>
          <table>tbl_banner</table>
          <id>banner_id</id>
          <reference>banner_name</reference> 
          <orderby>banner_name</orderby> 
        </field> 
      </options>
      <log>
      	<table>tbl_listing</table>
      	<id>listing_id</id>
      	<field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>ec_edit_prodcategory.tpl</edit_template>
    </section>  
	
    <section level="1">
      <showlist>FALSE</showlist>
      <url>products</url>
      <title>Products</title>
      <type>TABLE</type>
      <root_parent_id>0</root_parent_id>
      <table>
      	<name>tbl_product</name>
      	<id>product_id</id>
      	<field>product_name</field>
      	<deleted>product_deleted</deleted>
        <orderby>product_order, product_name</orderby>
        <associated inlist="true"> 
          <name>variants</name>
          <table>tbl_variant</table>
          <linkfield>product_id</linkfield>
          <field>variant_product_id</field> 
           <associated> 
            <name>productattributes</name>
            <table>tbl_productattr</table>
            <linkfield>variant_id</linkfield>
            <field>productattr_variant_id</field> 
          </associated>
          <associated> 
            <name>qty_modifier</name>
            <table>tbl_productqty</table>
            <linkfield>variant_id</linkfield>
            <field>productqty_variant_id</field> 
            <orderby>productqty_qty ASC</orderby>
          </associated>
        </associated>
      	<associated> 
          <name>gallery</name>
          <table>tbl_gallery</table>
          <linkfield>product_id</linkfield>
          <field>gallery_product_id</field> 
      	</associated>
      	<associated>
          <name>productcats</name>
          <table>tbl_productcat</table>
          <linkfield>product_id</linkfield>
          <field>productcat_product_id</field>
      	</associated>
      	<associated>
          <name>productassoc</name>
          <table>tbl_productassoc</table>
          <linkfield>product_id</linkfield>
          <field>productassoc_product_id</field>
      	</associated>
        <associated>
          <name>pmateriallinks</name>
          <table>tbl_pmateriallink</table>
          <linkfield>product_id</linkfield>
          <field>pmateriallink_product_id</field>
        </associated>
        <associated>
          <name>pcarelinks</name>
          <table>tbl_pcarelink</table>
          <linkfield>product_id</linkfield>
          <field>pcarelink_product_id</field>
        </associated>
      	<options> 
          <field recursive="true"> 
          	<name>categories</name>
          	<table>tbl_listing</table>
          	<id>listing_object_id</id>
          	<reference>listing_name</reference> 
            <parent_id>6</parent_id>
          	<where>listing_parent_flag = 1 AND listing_type_id = 10 AND listing_published = 1</where> 
            <orderby>listing_name</orderby>
          </field>  
          <field> 
          	<name>product_types</name>
          	<table>tbl_producttype</table>
          	<id>producttype_id</id>
          	<reference>producttype_name</reference> 
          	<orderby>producttype_name = 'Standard' DESC</orderby> 
          </field>  
          <field> 
            <name>products</name>
            <table>tbl_product</table>
            <id>product_object_id</id>
            <reference>product_name</reference> 
            <where>product_published = '1'</where> 
            <orderby>product_name</orderby> 
          </field>
          <field> 
            <name>ptypes</name>
            <table>tbl_ptype</table>
            <id>ptype_id</id>
            <reference>ptype_name</reference> 
            <orderby>ptype_name</orderby> 
          </field>
          <field> 
            <name>pcares</name>
            <table>tbl_pcare</table>
            <id>pcare_id</id>
            <reference>pcare_name</reference> 
            <orderby>pcare_name</orderby> 
          </field>
          <field> 
            <name>pdeliveries</name>
            <table>tbl_pdelivery</table>
            <id>pdelivery_id</id>
            <reference>pdelivery_name</reference> 
            <orderby>pdelivery_name</orderby> 
          </field>
          <field> 
            <name>pmaterials</name>
            <table>tbl_pmaterial</table>
            <id>pmaterial_id</id>
            <reference>pmaterial_name</reference> 
            <orderby>pmaterial_name</orderby> 
          </field>
          <field> 
            <name>pwarranties</name>
            <table>tbl_pwarranty</table>
            <id>pwarranty_id</id>
            <reference>pwarranty_name</reference> 
            <orderby>pwarranty_name</orderby> 
          </field>  
      	</options>
      	<log>
          <table>tbl_product</table>
          <id>product_id</id>
          <field>product_object_id</field>
      	</log>
      </table>
      <list_template>ec_list_product.tpl</list_template>
      <edit_template>ec_edit_product.tpl</edit_template>
      <process>
        <file>admin/includes/processes/load-product-attributes.php</file>
      </process>
    </section> 
    
    <section level="1">
      <showlist>FALSE</showlist>
      <url>discounts</url>
      <title>Discount codes</title>
      <type>TABLE</type>
      <table>
        <name>tbl_discount</name>
        <id>discount_id</id>
        <field>discount_name</field>
        <deleted>discount_deleted</deleted>
        <orderby>discount_id DESC</orderby>
        <published>discount_published</published>
        <options> 
          <field recursive="true"> 
            <name>categories</name>
            <table>tbl_listing</table>
            <id>listing_object_id</id>
            <parent_id>6</parent_id>
            <reference>listing_name</reference> 
            <where>listing_parent_flag = 1 AND listing_type_id = 10 AND listing_published = 1</where> 
            <orderby>listing_name</orderby>
          </field> 
          <field> 
            <name>products</name>
            <table>tbl_product</table>
            <id>product_object_id</id>
            <reference>product_name</reference>
            <where>product_published = 1</where>
            <orderby>product_name</orderby>
          </field>
          <field> 
            <name>usergroups</name>
            <table>tbl_usergroup</table>
            <id>usergroup_id</id>
            <reference>usergroup_name</reference>
            <orderby>usergroup_name</orderby>
          </field> 
        </options>
        <log>
          <table>tbl_discount</table>
          <id>discount_id</id>
          <field>discount_id</field>
        </log>
      </table>
      <list_template>ec_list_discount.tpl</list_template>
      <edit_template>ec_edit_discount.tpl</edit_template>
    </section>
  </group>
  
  <group name="Conversions"> 
    <section level="1">
      <showlist>FALSE</showlist>
      <url>orders</url>
      <title>Orders</title>
      <type>TABLE</type>
      <type_id>2</type_id>
      <table>
        <name>tbl_cart</name>
        <id>cart_id</id>
        <field>cart_closed_date</field>
        <deleted>cart_deleted</deleted>
        <where notedit="true">DATE(cart_closed_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE() </where> 
        <orderby>cart_closed_date DESC</orderby>
        <associated> 
          <id>cartitem_id</id>
          <name>items</name>
          <table>tbl_cartitem</table>
          <linkfield>cart_id</linkfield>
          <field>cartitem_cart_id</field> 
          <associated> 
            <id>cartitem_attr_id</id>
            <name>attributes</name>
            <table>tbl_cartitem_attr</table>
            <linkfield>cartitem_id</linkfield>
            <field>cartitem_attr_cartitem_id</field> 
          </associated>
        </associated>
        <associated inlist="true"> 
          <id>user_id</id>
          <name>user</name>
          <table>tbl_user</table>
          <linkfield>cart_user_id</linkfield>
          <field>user_id</field> 
        </associated>
        <associated inlist="true"> 
          <id>payment_id</id>
          <name>payment</name>
          <table>tbl_payment</table>
          <linkfield>cart_id</linkfield>
          <field>payment_cart_id</field> 
          <where>payment_status = 'A'</where>
          <associated inlist="true"> 
            <id>order_id</id>
            <name>order</name>
            <table>tbl_order</table>
            <linkfield>payment_id</linkfield>
            <field>order_payment_id</field> 
            <orderby>order_modified DESC</orderby> 
          </associated>
          <associated> 
            <id>address_id</id>
            <name>billing_address</name>
            <table>tbl_address</table>
            <linkfield>payment_billing_address_id</linkfield>
            <field>address_id</field> 
          </associated>
          <associated> 
            <id>address_id</id>
            <name>shipping_address</name>
            <table>tbl_address</table>
            <linkfield>payment_shipping_address_id</linkfield>
            <field>address_id</field> 
          </associated>
        </associated>
        <options> 
          <field inlist="true"> 
            <name>status</name>
            <table>tbl_status</table>
            <id>status_id</id>
            <reference>status_name</reference> 
            <orderby>status_order</orderby> 
          </field> 
        </options>
      </table>
      <list_template>ec_list_order.tpl</list_template>
      <edit_template>ec_edit_order.tpl</edit_template>
    </section>  
    <section level="1">
      <showlist>FALSE</showlist>
      <url>enquiries</url>
      <title>Enquiries</title>
      <type>TABLE</type>
      <table>
      	<name>tbl_contact</name>
      	<id>contact_id</id>
      	<field>contact_email</field>
      	<deleted>contact_deleted</deleted>
      	<orderby>contact_created DESC</orderby>
      </table>
      <list_template>list_contact.tpl</list_template>
      <edit_template>edit_contact.tpl</edit_template>
    </section> 
    <section level="1" subsection="true">
      <showlist>FALSE</showlist>
      <url>wish-list</url>
      <title>Wish list</title>
      <type>CUSTOM</type>
      <subsection>
        <url>members</url>
        <title>Wish list</title>
        <template>ec_wish-list-members.tpl</template>
        <process>
          <file>admin/includes/processes/load-wish-list-members.php</file>
        </process>
      </subsection>
      <subsection hidden="true">
        <url>products</url>
        <title>Wish list</title>
        <template>ec_wish-list-products.tpl</template>
        <process>
          <file>admin/includes/processes/load-wish-list-products.php</file>
        </process>
      </subsection>
    </section>
    <section level="1" subsection="true">
      <showlist>FALSE</showlist>
      <url>download</url>
      <title>Download</title>
      <type>CUSTOM</type>
      <subsection>
        <url>reports</url>
        <title>Reports</title>
        <template>reports.tpl</template>
      </subsection>
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