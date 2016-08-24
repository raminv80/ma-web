<!-- <?php die(); ?> -->
<!-- THEM CMS configuration file -->
<config debug="true" staging="true">
  <domain></domain>
  <google_analytics>
    <id>UA-</id>
    <old_id></old_id>
  </google_analytics>
  <company>
    <name>MedicAlert Foundation</name>
    <address>
      <street>Level 1, 210 Greenhill Road</street>
        <suburb>Eastwood</suburb>
		<state>SA</state>
		<postcode>5063</postcode>
    </address>
    <phone>08 82738401</phone>
    <toll_free>1800 88 22 22</toll_free>
    <fax>1800 64 32 59</fax>
    <email>noreply@themserver.com.au</email>
    <email_from>noreply@themserver.com.au</email_from>
    <email_contact>enquiry@medicalert.org.au</email_contact>
    <email_orders>apolo@them.com.au</email_orders>
    <logo>logo.png</logo>
  </company> 
  <database> 
    <host>122.201.97.172</host> 
    <user>them_usr01</user> 
    <password>OTwFwL?pSnR+</password> 
    <dbname>them_db01</dbname> 
  </database> 
  <page_strut>
    <type>1</type>
    <depth>2</depth>
    <file>ListClass</file>
    <table>
      <name>tbl_listing</name>
	<field>listing_url</field>
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
    </table>
    <template>standardpage.tpl</template>
    <process>
      <file>includes/processes/load-products.php</file>
    </process>
  </page_strut>
  <index_page>
    <template>home.tpl</template>
    <pageID>1</pageID>
  </index_page>
  <error404>
    <header>HTTP/1.0 404 Not Found</header>
    <template>standardpage.tpl</template>
    <pageID>5</pageID>
  </error404>
  <error403>
  <header>HTTP/1.0 403 Forbidden</header>
    <template>standardpage.tpl</template>
    <pageID>15</pageID>
  </error403>
  <error503>
  <header>HTTP/1.0 503 Service Temporarily Unavailable</header>
    <template>503.tpl</template>
  </error503>
  <search>
    <template>search.tpl</template>
    <pageID>16</pageID>
  </search>
  <static_page>
    <url>about-medicalert-foundation</url>
    <template>about.tpl</template>
    <pageID>2</pageID>
  </static_page>
  <static_page>
    <url>contact-us</url>
    <template>contact-us.tpl</template>
    <pageID>3</pageID>
  </static_page>
  <static_page>
    <url>feedback</url>
    <template>feedback.tpl</template>
    <pageID>108</pageID>
  </static_page>
  <static_page>
    <url>emergency-personnel</url>
    <template>emergency-personnel.tpl</template>
    <pageID>106</pageID>
  </static_page>
  <static_page>
    <url>accessibility</url>
    <template>accessibility.tpl</template>
    <pageID>111</pageID>
  </static_page>
  <static_page>
    <url>password-recovery</url>
    <template>password-recovery.tpl</template>
    <pageID>14</pageID>
  </static_page>
  <static_page>
    <url>thank-you</url>
    <template>thank-you.tpl</template>
    <pageID>4</pageID>
  </static_page>
  <static_page>
    <url>thank-you-for-purchasing</url>
    <template>checkout-complete.tpl</template>
    <pageID>7</pageID>
  </static_page>
  <static_page>
    <url>benefits-of-membership</url>
    <template>benefits.tpl</template>
    <pageID>99</pageID>
  </static_page>
  <static_page>
    <url>cost</url>
    <template>cost.tpl</template>
    <pageID>114</pageID>
  </static_page>
  <static_page>
    <url>who-needs-membership</url>
    <template>who-needs-membership.tpl</template>
    <pageID>95</pageID>
    <process>
      <file>includes/processes/process-load-testimonials.php</file>
    </process>
  </static_page>
  <static_page>
    <url>how-to-become-a-member</url>
    <template>how-to-become-a-member.tpl</template>
    <pageID>96</pageID>
  </static_page>
  <static_page>
    <url>faqs</url>
    <template>faqs.tpl</template>
    <pageID>103</pageID>
  </static_page>
  <static_page>
    <url>corporate-partners</url>
    <template>corporate-partners.tpl</template>
    <pageID>107</pageID>
  </static_page>

  <listing_page name="news-and-resources">
    <url>news-and-resources</url>
    <root_parent_id>0</root_parent_id> 
    <type>2</type><!-- articles --> 
    <type>3</type><!-- newsletter -->
    <file>ListClass</file>
    <orderby>listing_schedule_start DESC</orderby>
    <where><![CDATA[(listing_schedule_start < NOW() OR listing_schedule_start IS NULL)]]></where>
    <limit level="1">20</limit>
    <table> 
      <name>tbl_listing</name>
      <field>listing_url</field>  
      <extends>
        <table>tbl_news</table>
        <linkfield>listing_id</linkfield>
        <field>news_listing_id</field>
      </extends>    
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
      <template typeid="2">news-article.tpl</template> 
      <template typeid="3">newsletter.tpl</template> 
    </table>
    <template>news-resources.tpl</template>
    <loadmoretemplate>news-resources-structure.tpl</loadmoretemplate>  
  </listing_page>
  
  <listing_page name="testimonials">
    <url>testimonials</url>
    <root_parent_id>0</root_parent_id> 
    <type>4</type>
    <file>ListClass</file>
    <orderby>listing_order</orderby>
    <limit level="1">200</limit>
    <table> 
      <name>tbl_listing</name>
      <field>listing_url</field>  
      <template typeid="4">testimonial-details.tpl</template> 
    </table>
    <template>testimonials.tpl</template>
  </listing_page>
  
  <listing_page name="products">
    <url>products</url>
    <root_parent_id>6</root_parent_id> 
    <type>10</type>
    <file>ListClass</file>
    <limit level="1">100</limit>
    <table> 
      <name>tbl_listing</name>
      <field>listing_url</field>  
      <associated listing="false">
        <name>gallery</name>
        <table>tbl_gallery</table>
        <linkfield>listing_id</linkfield>
        <field>gallery_listing_id</field>
      </associated>
      <template>ec_category.tpl</template> 
    </table>
    <template typeid="10">ec_category-details.tpl</template>
    <template typeid="1">ec_category.tpl</template>
    <loadmoretemplate>ec_category-loadmore.tpl</loadmoretemplate>  
    <process>
      <file>includes/processes/process-load-viewed-products.php</file>
    </process>
    <process>
      <file>includes/processes/process-load-popular-products.php</file>
    </process>
  </listing_page>
  	
  <login>
    <url>login-register</url>
    <template>login-register.tpl</template>
    <pageID>8</pageID>
    <fallback_redirect>my-account</fallback_redirect>
  </login>
  <account restricted="true">
    <url>my-account</url>
    <template>account.tpl</template>
    <pageID>9</pageID>
    <fallback_redirect>login-register</fallback_redirect>
  </account>
  <cart>
    <url>shopping-cart</url>
    <pageID>12</pageID>
    <type>1</type>
    <file>ListClass</file>
    <table>
      <name>tbl_cart</name>
      <field>listing_url</field>
    </table>
    <template>shopping-cart.tpl</template>
  </cart>
  <checkout guest="false">
    <url>checkout</url>
    <pageID>13</pageID>
    <type>1</type>
    <file>ListClass</file>
    <table>
      <name>tbl_listing</name>
      <field>listing_url</field>
    </table>
    <template>checkout.tpl</template>
  </checkout>
  
  <product_page>
    <file>ProductClass</file>
    <table>
      <name>tbl_product</name>
      <id>product_object_id</id>
      <field>product_url</field>
      <associated>
        <name>gallery</name>
        <table>tbl_gallery</table>
        <linkfield>product_id</linkfield>
        <field>gallery_product_id</field>
        <orderby>gallery_order</orderby>
      </associated>
      <associated> 
        <name>attributes</name>
        <table>tbl_productschema</table>
        <linkfield>product_type_id</linkfield>
        <field>productschema_type_id</field> 
        <orderby>attribute_order</orderby>
        <extends>
          <table>tbl_attribute</table>
          <linkfield>productschema_attribute_id</linkfield>
          <field>attribute_id</field>
        </extends> 
        <associated>
          <name>values</name>
          <table>tbl_attr_value</table>
          <linkfield>attribute_id</linkfield>
          <field>attr_value_attribute_id</field>
          <orderby>attr_value_order</orderby>
        </associated>
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
      </associated>
      <associated>
        <name>productassoc</name>
        <table>tbl_productassoc</table>
        <linkfield>product_id</linkfield>
        <field>productassoc_product_id</field>
      </associated>
    </table>
    <template>ec_product.tpl</template>
  </product_page>
  
  <global_process>
    <file>includes/processes/global-source-referer.php</file>
  </global_process>
  <global_process>
    <file>includes/processes/global-shopping-cart.php</file>
  </global_process>
  <process>
    <url>process/cart</url>
    <file>includes/processes/processes-cart.php</file>
    <return_url></return_url>
  </process>  
  <process>
    <url>process/contact-us</url>
    <file>includes/processes/processes-contactus.php</file>
    <return_url></return_url>
  </process>
  <process>
    <url>process/load-more</url>
    <file>includes/processes/load-more.php</file>
    <return_url></return_url>
  </process>	 	

  <smartytemplate_config>
    <templates>/templates</templates>
    <templates_c>/templates_c</templates_c>
    <cache>/cache</cache>
    <configs>/configs</configs>
    <plugins>/plugins</plugins>
  </smartytemplate_config>
</config>