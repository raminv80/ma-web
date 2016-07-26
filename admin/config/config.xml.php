<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true"> 
  <domain></domain>
  <google_analytics>
  	<id>UA-</id>
  	<old_id></old_id>
  </google_analytics>
  <company>
  	<name>Them</name>
  	<address>
  		<street>1/26 The Parade West</street>
  		<suburb>Kent Town</suburb>
  		<state>SA</state>
  		<postcode>5067</postcode>
  	</address>
  	<phone>08 8363 2717</phone>
  	<email>online@them.com.au</email>
  	<email_from>noreply@themserver.com.au</email_from>
  	<email_contact>apolo@them.com.au</email_contact>
  	<email_orders>apolo@them.com.au</email_orders>
  	<logo>logo.png</logo>
  </company>  
  <database> 
  	<host>m4-mysql1-1.ilisys.com.au</host> 
  	<user>themso</user> 
  	<password>c@^^3L5tRu7s*n9ub11c</password> 
  	<dbname>themso3_db</dbname> 
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
      <options> 
      	<field>
          <name>listing_parent_id</name>
          <table>tbl_listing</table>
          <id>listing_object_id</id>
          <reference>listing_name</reference> 
          <where>listing_parent_flag = 1 AND listing_type_id = 1 AND listing_published = 1</where> 
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
      <log>
      	<table>tbl_listing</table>
      	<id>listing_id</id>
      	<field>listing_object_id</field>
      </log>
      <list_template>list.tpl</list_template>
      <edit_template>edit_news.tpl</edit_template>
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
      <edit_template>edit_news.tpl</edit_template>
    </section> 
  </group>
  
  <group name="E-commerce">
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
      <url>product-types</url>
      <title>Product types</title>
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
      <url>prodcat</url>
      <title>Categories</title>
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
      	<associated> 
          <name>schemas</name>
          <table>tbl_productschema</table>
          <linkfield>product_type_id</linkfield>
          <field>productschema_type_id</field> 
        </associated>
        <associated> 
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
      	<options> 
          <field recursive="true"> 
          	<name>categories</name>
          	<table>tbl_listing</table>
          	<id>listing_object_id</id>
          	<reference>listing_name</reference> 
            <parent_id>6</parent_id>
          	<where>listing_parent_flag = 1 AND listing_type_id = 10 AND listing_published = 1</where> 
          </field>  
          <field> 
          	<name>product_types</name>
          	<table>tbl_producttype</table>
          	<id>producttype_id</id>
          	<reference>producttype_name</reference> 
          	<orderby>producttype_id</orderby> 
          </field>  
          <field> 
            <name>products</name>
            <table>tbl_product</table>
            <id>product_object_id</id>
            <reference>product_name</reference> 
            <where>product_published = '1'</where> 
            <orderby>product_name</orderby> 
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
      <title>Discount Codes</title>
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
          	<reference>listing_name</reference> 
          	<where>listing_parent_flag = 1 AND listing_type_id = 10 AND listing_published = 1</where> 
          </field> 
          <field> 
          	<name>products</name>
          	<table>tbl_product</table>
          	<id>product_object_id</id>
          	<reference>product_name</reference> 
          	<orderby>product_name AND product_published = 1</orderby>
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
          <associated> 
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
  </group>

  <smartytemplate_config><!-- This element contains the smarty template values -->
  	<templates>/templates</templates>
  	<templates_c>/templates_c</templates_c>
  	<cache>/cache</cache>
  	<configs>/configs</configs>
  	<plugins>/plugins</plugins>
  </smartytemplate_config>
</config>