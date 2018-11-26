<?php
die('disabled');
ini_set('display_errors', 1);

ini_set('error_reporting', E_ALL);

ini_set('memory_limit', '750M');

set_include_path($_SERVER['DOCUMENT_ROOT']);

include 'admin/includes/functions/admin-functions.php';

global $CONFIG, $SMARTY, $DBobject;

if($_SERVER['REMOTE_ADDR'] != '150.101.230.130') die('Restricted area');

$sql = "SELECT * FROM tbl_product LEFT JOIN `AA_products` ON `New ID`= product_migration_id 
WHERE (product_deleted IS NULL OR product_deleted = '0000-00-00')";

if($res = $DBobject->wrappedSql($sql)){
  
  foreach($res as $r){    
    
    // Collections
    
    $collections = explode(',', $r['Collections']);
    
    foreach($collections as $collect){
    
      $Csql = "SELECT `listing_object_id` FROM `tbl_listing` WHERE `listing_type_id` =10 
      AND listing_name LIKE :listing_name AND (listing_deleted is NULL OR listing_deleted = '0000-00-00') LIMIT 1";
    
      if($Cres = $DBobject->wrappedSql($Csql, array('listing_name' => trim($collect)))){
    
        $collectListingObjID = $Cres[0]['listing_object_id'];
        
        $cGetSQL = "SELECT productcat_id FROM tbl_productcat WHERE productcat_listing_id = :productcat_listing_id 
            AND productcat_product_id = :productcat_product_id 
            AND (productcat_deleted IS NULL OR productcat_deleted = '0000-00-00')";
        
        if($cGetSQLres = $DBobject->wrappedSql($cGetSQL, array("productcat_listing_id" => $collectListingObjID, "productcat_product_id" => $r['product_id']))){
            print_r($cGetSQLres);
        }else{
          echo "product id: ".$r['product_id']."/n";
          echo "listing id: ".$collectListingObjID."/n";
          $collsql = "INSERT INTO tbl_productcat ( productcat_listing_id, productcat_product_id, productcat_created, productcat_modified )
      				  VALUES( :productcat_listing_id, :productcat_product_id, NOW(), NOW() )";
      
          $DBobject->wrappedSql($collsql, array(
              "productcat_listing_id" => $collectListingObjID,
              "productcat_product_id" => $r['product_id']
          ));
        }
      }
    }
      
      // Variants
      
      //update_variants($productID, $r['New ID'], $r['Types']);
    }
  
}


function update_variants($product_id, $linking_id, $product_type){
  global $CONFIG, $SMARTY, $DBobject;
  
  $sql = "SELECT * FROM AA_variants LEFT JOIN tbl_variant ON variant_migration_id = id WHERE `New ID`= :linking_id 
  AND (variant_deleted IS NULL OR variant_deleted = '0000-00-00')";
  
  if($res = $DBobject->wrappedSql($sql, array(
      "linking_id" => $linking_id 
  ))){
    
    foreach($res as $r){
      
        
        $variantID = $r['variant_id'];
        
        // Colour
        $Csql = "SELECT  productattr_id
                FROM  tbl_productattr WHERE (productattr_deleted IS NULL OR productattr_deleted = '0000-00-00') AND productattr_attribute_id = 2
                AND  `productattr_variant_id` =  :productattr_variant_id";
        
        if($Cres = $DBobject->wrappedSql($Csql, array('productattr_variant_id' => $variantID ))){
          
            $Cgetsql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  (`attr_value_deleted` IS NULL OR attr_value_deleted = '0000-00-00')
                AND (attribute_deleted IS NULL OR attribute_deleted = '0000-00-00')
                AND attribute_name LIKE  'Colour'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
          
            if($Cgetres = $DBobject->wrappedSql($Cgetsql, array('attr_value_name' => trim($r['Colour'])))){

                $collsql = "UPDATE tbl_productattr SET productattr_attr_value_id = :productattr_attr_value_id, productattr_modified = NOW()
                        WHERE productattr_id = :productattr_id AND (productattr_deleted IS NULL OR productattr_deleted = '0000-00-00')";
                
                $DBobject->wrappedSql($collsql, array(
                    "productattr_id" => $Cres['productattr_id'],
                    "productattr_attr_value_id"=> $Cgetres[0]['attr_value_id']
                ));
            }
        }else{
          
          $Cgetsql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  (`attr_value_deleted` IS NULL OR attr_value_deleted = '0000-00-00')
                AND (attribute_deleted IS NULL OR attribute_deleted = '0000-00-00')
                AND attribute_name LIKE  'Colour'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
          
          if($Cgetres = $DBobject->wrappedSql($Cgetsql, array('attr_value_name' => trim($r['Colour'])))){
          
              $collsql = "INSERT INTO tbl_productattr ( productattr_variant_id, productattr_attribute_id, productattr_attr_value_id,
    
                            productattr_created, productattr_modified )
    
          			  VALUES( :productattr_variant_id, :productattr_attribute_id, :productattr_attr_value_id, NOW(), NOW() )";
              
              $DBobject->wrappedSql($collsql, array(
                  "productattr_variant_id" => $variantID, 
                  
                  "productattr_attribute_id" => $Cgetres[0]['attr_value_attribute_id'], 
                  
                  "productattr_attr_value_id" => $Cgetres[0]['attr_value_id'] 
              ));
          }
        }
        
        // Medical ID size
        
        echo $product_type;
        if(trim($r['Medical ID size']) == "Dog tags"){
          echo $medicalIDSize = trim($r['Medical ID size']);
        }elseif (trim($r['Medical ID size']) == "Large Pendants"){
          $medicalIDSize = "Large (P)"; 
        }elseif (trim($r['Medical ID size']) == "Small Pendants"){
          $medicalIDSize = "Small (P)"; 
        }elseif (trim($r['Medical ID size']) == "Large Square"){
          $medicalIDSize = "Large Square (B)"; 
        }elseif (trim($r['Medical ID size']) == "Large Wristbands"){
          $medicalIDSize = "Large (W)"; 
        }elseif (trim($r['Medical ID size']) == "Small Wristbands"){
          $medicalIDSize = "Small (W)"; 
        }elseif (trim($r['Medical ID size']) == "Rectangle"){
          $medicalIDSize = "Standard (B)"; 
        }else {
          echo $medicalIDSize = trim($r['Medical ID size'])." (" . ucfirst(substr($product_type, 0, 1)) . ")"; 
        }
        
        
        echo $Msql = "SELECT  productattr_id
                FROM  tbl_productattr WHERE (productattr_deleted IS NULL OR productattr_deleted = '0000-00-00') 
                AND productattr_attribute_id = 4
                AND  `productattr_variant_id` =  :productattr_variant_id";
        echo $variantID;
        echo "<br>";
        
        if($Mres = $DBobject->wrappedSql($Msql, array('productattr_variant_id' => $variantID ))){
          
            $Mgetsql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  (`attr_value_deleted` IS NULL OR `attr_value_deleted` = '0000-00-00')
                AND (attribute_deleted IS NULL OR attribute_deleted = '0000-00-00')
                AND attribute_name LIKE  'Medical ID size'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
          
            if($Mgetres = $DBobject->wrappedSql($Mgetsql, array('attr_value_name' => $medicalIDSize))){

                echo $collsql = "UPDATE tbl_productattr SET productattr_attr_value_id = :productattr_attr_value_id, productattr_modified = NOW()
                        WHERE productattr_id = :productattr_id 
                        AND (productattr_deleted IS NULL OR productattr_deleted = '0000-00-00')";
                echo "<br>";
                $DBobject->wrappedSql($collsql, array(
                    "productattr_id" => $Mres['productattr_id'],
                    "productattr_attr_value_id"=> $Mgetres[0]['attr_value_id']
                ));
            }
        }else{
          
          $Mgetsql = "SELECT  `attr_value_id` ,  `attr_value_attribute_id`
                FROM  `tbl_attr_value`
                LEFT JOIN tbl_attribute ON  `attr_value_attribute_id` = attribute_id
                WHERE  (`attr_value_deleted` IS NULL OR attr_value_deleted = '0000-00-00')
                AND (attribute_deleted IS NULL OR attribute_deleted = '0000-00-00')
                AND attribute_name LIKE  'Medical ID size'
                AND  `attr_value_name` LIKE  :attr_value_name LIMIT 1";
          
          echo $medicalIDSize;echo "<br>";
          
          if($Mgetres = $DBobject->wrappedSql($Mgetsql, array('attr_value_name' => $medicalIDSize))){
          
              echo $collsql = "INSERT INTO tbl_productattr ( productattr_variant_id, productattr_attribute_id, productattr_attr_value_id,
                            productattr_created, productattr_modified )
          			  VALUES( :productattr_variant_id, :productattr_attribute_id, :productattr_attr_value_id, NOW(), NOW() )";
              
              $DBobject->wrappedSql($collsql, array(
                  "productattr_variant_id" => $variantID, 
                  
                  "productattr_attribute_id" => $Mgetres[0]['attr_value_attribute_id'], 
                  
                  "productattr_attr_value_id" => $Mgetres[0]['attr_value_id'] 
              ));
          }
        }
    }
  }
}

