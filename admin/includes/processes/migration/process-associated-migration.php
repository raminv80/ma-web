<?php 
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('memory_limit','750M');
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';

global $CONFIG, $SMARTY, $DBobject;

if($_SERVER['REMOTE_ADDR'] != '150.101.230.130') die('Restricted area');

$sql = "SELECT tbl_product.product_id, AA_products.`Related products` FROM tbl_product 
        LEFT JOIN AA_products ON product_migration_id = `New ID` 
        WHERE product_deleted IS NULL";

if($res = $DBobject->wrappedSql($sql)){
	foreach($res as $r){
	  
	  //Associated products
	  $products = explode(',', $r['Related products']);
	  foreach ($products as $product){
	    $Csql = "SELECT product_object_id FROM tbl_product WHERE product_deleted IS NULL AND product_migration_id = :product_migration_id LIMIT 1";
	    if($Cres = $DBobject->wrappedSql($Csql, array('product_migration_id'=>trim($product)))){
	      
	      $checksql = "SELECT * FROM tbl_productassoc WHERE productassoc_product_id= :productassoc_product_id 
	          AND productassoc_product_object_id = :productassoc_product_object_id AND productassoc_deleted IS NULL";
	      
	      if($checkres = $DBobject->wrappedSql($checksql,array("productassoc_product_id"=>$r['product_id'], 
	          "productassoc_product_object_id"=>$Cres[0]['product_object_id']))){
	      
	      }else{
    	      $collsql = "INSERT INTO tbl_productassoc ( productassoc_product_id, productassoc_product_object_id, 
    	                  productassoc_created, productassoc_modified )
        				  VALUES( :productassoc_product_id, :productassoc_product_object_id, NOW(), NOW() )";
    	       
    	      $DBobject->wrappedSql($collsql, array("productassoc_product_id"=>$r['product_id'], 
    	          "productassoc_product_object_id"=>$Cres[0]['product_object_id']));
    	         
	      }
	    }
	    	
	  }
	  
	}
}


