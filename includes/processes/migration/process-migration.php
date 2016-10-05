<?php
ini_set('display_errors', 1);

ini_set('error_reporting', E_ALL);

ini_set('memory_limit', '750M');

set_include_path($_SERVER['DOCUMENT_ROOT']);

include 'admin/includes/functions/admin-functions.php';

global $CONFIG, $SMARTY, $DBobject;

if($_SERVER['REMOTE_ADDR'] != '150.101.230.130') die('Restricted area');

$sql = "SELECT * FROM AA_products WHERE 1";

if($res = $DBobject->wrappedSql($sql)){
  
  foreach($res as $r){
    
    $checksql = "SELECT * FROM tbl_product WHERE product_migration_id= :id";
    
    if($checkres = $DBobject->wrappedSql($checksql, array(
        "id" => $r['New ID'] 
    ))){} else{
      
      $params = array();
      
      // Product new ID
      
      $params['product_migration_id'] = $r['New ID'];
      
      // Old product ID
      
      $params['product_old_id'] = $r['Old ID'];
      
      // Get product_object_id
      
      $Osql = "INSERT INTO tbl_sequence VALUES ()";
      
      $DBobject->wrappedSql($Osql);
      
      $objID = $DBobject->wrappedSqlIdentity();
      
      $params['product_object_id'] = $objID;
      
      // Get Schema ID - product_type_id
      
      $params['product_type_id'] = 1;
      
      // Get product_name
      
      $params['product_name'] = clean($r['Name']);
      
      // Get product_url
      
      $url = urlSafeString($r['Name']);
      
      /*
       *
       * $tempVal = false;
       *
       * $tempVal= validate_URL($url);
       *
       *
       *
       * while ($tempVal){
       *
       * $url.=$r['Types'];
       *
       * $tempVal= validate_URL($url);
       *
       * }
       *
       */
      
      $params['product_url'] = $url;
      
      // Get product_description
      
      $params['product_description'] = clean($r['Description']);
      
      // Get TYPE ID - product_associate1
      
      $Tsql = "SELECT `ptype_id` FROM `tbl_ptype` WHERE `ptype_name` LIKE :ptype_name AND ptype_deleted IS NULL LIMIT 1";
      
      if($Tres = $DBobject->wrappedSql($Tsql, array(
          'ptype_name' => $r['Types'] 
      ))){
        
        $typeID = $Tres[0]['ptype_id'];
      }
      
      $params['product_associate1'] = $typeID;
      
      // Get Delivery & returns - product_associate2
      
      $params['product_associate2'] = $r['Delivery & returns'];
      
      // Get Warranty - product_associate3
      
      $params['product_associate3'] = $r['Warranty'];
      
      // Get product_seo_title
      
      $params['product_seo_title'] = clean($r['SEO title']);
      
      // Get product_meta_description
      
      $params['product_meta_description'] = clean($r['SEO meta description']);
      
      // Get Popular (Y/N) - product_featured
      
      if($r['Popular (Y/N)'] == 'Y'){
        
        $params['product_featured'] = 1;
      } else{
        
        $params['product_featured'] = 0;
      }
      
      // Get Wear (O/E) - product_flag1
      
      if($r['Wear (O/E)'] == 'O'){
        
        $params['product_flag1'] = 0;
      } else{
        
        $params['product_flag1'] = 1;
      }
      
      // product_order
      
      $params['product_order'] = 999;
      
      // product_published
      
      $params['product_published'] = 1;
      
      $INSsql = "INSERT INTO tbl_product ( product_migration_id, product_old_id, product_object_id, product_type_id, product_name, product_url, product_description,
    	             product_associate1, product_associate2, product_associate3, product_seo_title, product_meta_description, product_featured, 
    	             product_flag1, product_order, product_published, product_created, product_modified )
    				  
    	      VALUES( :product_migration_id, :product_old_id, :product_object_id, :product_type_id, :product_name, :product_url, :product_description,
    	             :product_associate1, :product_associate2, :product_associate3, :product_seo_title, :product_meta_description, :product_featured, 
    	             :product_flag1, :product_order, :product_published, NOW(), NOW() )";
      
      $DBobject->wrappedSql($INSsql, $params);
      
      $productID = $DBobject->wrappedSqlIdentity();
      
      // Collections
      
      $collections = explode(',', $r['Collections']);
      
      foreach($collections as $collect){
        
        $Csql = "SELECT `listing_object_id` FROM `tbl_listing` WHERE `listing_type_id` =10 AND 	listing_name LIKE :listing_name AND listing_deleted is NULL LIMIT 1";
        
        if($Cres = $DBobject->wrappedSql($Csql, array(
            'listing_name' => trim($collect) 
        ))){
          
          $collectListingObjID = $Cres[0]['listing_object_id'];
          
          $collsql = "INSERT INTO tbl_productcat ( productcat_listing_id, productcat_product_id, productcat_created, productcat_modified )
    				  VALUES( :productcat_listing_id, :productcat_product_id, NOW(), NOW() )";
          
          $DBobject->wrappedSql($collsql, array(
              "productcat_listing_id" => $collectListingObjID, 
              "productcat_product_id" => $productID 
          ));
        }
      }
      
      // Material
      
      $materials = explode(',', $r['Materials']);
      
      foreach($materials as $material){
        
        $Msql = "SELECT `pmaterial_id` FROM `tbl_pmaterial` WHERE pmaterial_name LIKE :pmaterial_name AND pmaterial_deleted IS NULL LIMIT 1";
        
        if($Mres = $DBobject->wrappedSql($Msql, array(
            'pmaterial_name' => trim($material) 
        ))){
          
          $pmaterial_id = $Mres[0]['pmaterial_id'];
          
          $matsql = "INSERT INTO tbl_pmateriallink ( pmateriallink_record_id, pmateriallink_product_id, pmateriallink_created, pmateriallink_modified )
    				  VALUES( :pmateriallink_record_id, :pmateriallink_product_id, NOW(), NOW() )";
          
          $DBobject->wrappedSql($matsql, array(
              "pmateriallink_record_id" => $pmaterial_id, 
              "pmateriallink_product_id" => $productID 
          ));
        }
      }
      
      // Product care
      
      $cares = explode(',', $r['Care']);
      
      foreach($cares as $care){
        
        $caresql = "INSERT INTO tbl_pcarelink ( pcarelink_record_id, pcarelink_product_id, pcarelink_created, pcarelink_modified )
    				  VALUES( :pcarelink_record_id, :pcarelink_product_id, NOW(), NOW() )";
        
        $DBobject->wrappedSql($caresql, array(
            "pcarelink_record_id" => trim(str_replace('C-', '', $care)), 
            "pcarelink_product_id" => $productID 
        ));
      }
      
      // Variants
      
      set_variants($productID, $r['New ID'], $r['Types']);
    }
  }
}


function set_variants($product_id, $linking_id, $product_type){
  global $CONFIG, $SMARTY, $DBobject;
  
  $sql = "SELECT * FROM AA_variants WHERE `New ID`= :linking_id";
  
  if($res = $DBobject->wrappedSql($sql, array(
      "linking_id" => $linking_id 
  ))){
    
    foreach($res as $r){
      
      $checksql = "SELECT * FROM tbl_variant WHERE variant_migration_id= :id";
      
      if($checkres = $DBobject->wrappedSql($checksql, array(
          "id" => $r['id'] 
      ))){} else{
        
        $params = array();
        
        // variant id
        
        $params['variant_migration_id'] = $r['id'];
        
        // Product ID
        
        $params['variant_product_id'] = $product_id;
        
        // Variant UID
        
        $params['variant_uid'] = $r['Code'];
        
        // Get variant_price
        
        $params['variant_price'] = $r['Price'];
        
        // Get Limited stock (Y/N) - variant_limitedstock
        
        if($r['Limited stock (Y/N)'] == 'Y'){
          
          $params['variant_limitedstock'] = 1;
        } else{
          
          $params['variant_limitedstock'] = 0;
        }
        
        // Get New (Y/N) - variant_new
        
        if($r['New (Y/N)'] == 'Y'){
          
          $params['variant_new'] = 1;
        } else{
          
          $params['variant_new'] = 0;
        }
        
        // Get Hide from website - variant_published
        
        if($r['Hide from website'] == 'Y'){
          
          $params['variant_published'] = 0;
        } else{
          
          $params['variant_published'] = 1;
        }
        
        // variant_order
        
        $params['variant_order'] = 999;
        
        $INSsql = "INSERT INTO tbl_variant ( variant_migration_id, variant_product_id, variant_uid, variant_price, variant_limitedstock, variant_new, 
                 variant_published, variant_order, variant_created, variant_modified )
    
  	      VALUES( :variant_migration_id, :variant_product_id, :variant_uid, :variant_price, :variant_limitedstock, :variant_new, :variant_published,
  	             :variant_order, NOW(), NOW() )";
        
        $DBobject->wrappedSql($INSsql, $params);
        
        $variantID = $DBobject->wrappedSqlIdentity();
        
        // Colour
        
        $Csql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  `attr_value_deleted` IS NULL
                AND attribute_deleted IS NULL
                AND attribute_name LIKE  'Colour'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
        
        if($Cres = $DBobject->wrappedSql($Csql, array(
            'attr_value_name' => trim($r['Colour']) 
        ))){
          
          $collsql = "INSERT INTO tbl_productattr ( productattr_variant_id, productattr_attribute_id, productattr_attr_value_id,
                        productattr_created, productattr_modified )
      			  VALUES( :productattr_variant_id, :productattr_attribute_id, :productattr_attr_value_id, NOW(), NOW() )";
          
          $DBobject->wrappedSql($collsql, array(
              "productattr_variant_id" => $variantID, 
              
              "productattr_attribute_id" => $Cres[0]['attr_value_attribute_id'], 
              
              "productattr_attr_value_id" => $Cres[0]['attr_value_id'] 
          ));
        }
        
        // Medical ID size
        
        $Msql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  `attr_value_deleted` IS NULL
                AND attribute_deleted IS NULL
                AND attribute_name LIKE  'Medical ID size'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
        
        $size = (($product_type == "Dog tags")? trim($r['Medical ID size']) : trim($r['Medical ID size']) . " (" . ucfirst(substr($product_type, 0, 1)) . ")");
        
        if($Mres = $DBobject->wrappedSql($Msql, array(
            'attr_value_name' => $size 
        ))){
          
          $collsql = "INSERT INTO tbl_productattr ( productattr_variant_id, productattr_attribute_id, productattr_attr_value_id,
                        productattr_created, productattr_modified )
      			  VALUES( :productattr_variant_id, :productattr_attribute_id, :productattr_attr_value_id, NOW(), NOW() )";
          
          $DBobject->wrappedSql($collsql, array(
              "productattr_variant_id" => $variantID, 
              
              "productattr_attribute_id" => $Mres[0]['attr_value_attribute_id'], 
              
              "productattr_attr_value_id" => $Mres[0]['attr_value_id'] 
          ));
        }
        
        // Length
        
        $Lsql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  `attr_value_deleted` IS NULL
                AND attribute_deleted IS NULL
                AND attribute_name LIKE  'Length'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
        
        if($Lres = $DBobject->wrappedSql($Lsql, array(
            'attr_value_name' => trim($r['Length']) 
        ))){
          
          $collsql = "INSERT INTO tbl_productattr ( productattr_variant_id, productattr_attribute_id, productattr_attr_value_id,
                        productattr_created, productattr_modified )
      			  VALUES( :productattr_variant_id, :productattr_attribute_id, :productattr_attr_value_id, NOW(), NOW() )";
          
          $DBobject->wrappedSql($collsql, array(
              "productattr_variant_id" => $variantID, 
              
              "productattr_attribute_id" => $Lres[0]['attr_value_attribute_id'], 
              
              "productattr_attr_value_id" => $Lres[0]['attr_value_id'] 
          ));
        }
      }
    }
  }
}
