<?php
die('disabled');
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('memory_limit','750M');
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';

global $CONFIG, $SMARTY, $DBobject;

if($_SERVER['REMOTE_ADDR'] != '150.101.230.130') die('Restricted area');

$sql = "SELECT tbl_variant.variant_id, tbl_variant.variant_product_id, tbl_product.product_name, tbl_product.product_url, tbl_product_live.product_listing_image,
        tbl_product_live.product_image_1, tbl_product_live.product_image_2, tbl_product_live.product_image_3 
        FROM tbl_variant 
        LEFT JOIN AA_variants ON variant_migration_id = id 
        LEFT JOIN tbl_product ON tbl_product.product_id = tbl_variant.variant_product_id
        LEFT JOIN tbl_product_live ON tbl_product_live.product_id = AA_variants.`Old ID`
        WHERE (tbl_product_live.product_deleted IS NULL OR tbl_product_live.product_deleted = '0000-00-00') 
        AND (tbl_product.product_deleted IS NULL OR tbl_product.product_deleted = '0000-00-00') 
        AND (tbl_variant.variant_deleted IS NULL OR tbl_variant.variant_deleted = '0000-00-00')";

$ins = 0;
$updates = 0;
if($res = $DBobject->wrappedSql($sql)){

	foreach($res as $r){
	  

	  $sourceArr = array();
	  
	  if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url'])){
	    mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url']);
	  }
	  /*
	  if(!empty($r['product_listing_image'])){
        $sourceFile = $r['product_listing_image'];
  	    $updates = $updates + grab_image($sourceFile, $_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url']."/".end(explode('/', $sourceFile)));
  	    array_push($sourceArr, $sourceFile);
	  }
	  */
	  if(!empty($r['product_image_1'])){
        $sourceFile1 = $r['product_image_1'];
  	    $updates = $updates + grab_image($sourceFile1, $_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url']."/".end(explode('/', $sourceFile1)));
  	    array_push($sourceArr, $sourceFile1);
	  }
      
	  if(!empty($r['product_image_2'])){
	    $sourceFile2 = $r['product_image_2'];
	    $updates = $updates + grab_image($sourceFile2, $_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url']."/".end(explode('/', $sourceFile2)));
  	    array_push($sourceArr, $sourceFile2);
	  }
      
	  if(!empty($r['product_image_3'])){
    	$sourceFile2 = $r['product_image_3'];
    	$updates = $updates + grab_image($sourceFile3, $_SERVER['DOCUMENT_ROOT']."/uploads/products/".$r['product_url']."/".end(explode('/', $sourceFile3)));
  	    array_push($sourceArr, $sourceFile3);
	  }
	  
	  if(!empty($sourceArr)){
	    
	    foreach ($sourceArr as $source){
	      
	      $checksql = "SELECT * FROM tbl_gallery WHERE gallery_link= :gallery_link";
	       
	      if($checkres = $DBobject->wrappedSql($checksql,array("gallery_link"=>"/uploads/products/".$r['product_url']."/".end(explode('/', $source))))){
	         
	      }else{
	      
    	    $Gsql = "INSERT INTO tbl_gallery ( gallery_product_id, gallery_variant_id, gallery_title, gallery_link, gallery_file, 
    	             gallery_alt_tag, gallery_modified )
    		  VALUES( :gallery_product_id, :gallery_variant_id, :gallery_title, :gallery_link, :gallery_file, :gallery_alt_tag, NOW() )";
    	    
    	    $DBobject->wrappedSql($Gsql, array("gallery_product_id"=>$r['variant_product_id'],
    	        "gallery_variant_id"=>$r['variant_id'],
    	        "gallery_title"=>$r['product_name'],
    	        "gallery_link"=>"/uploads/products/".$r['product_url']."/".end(explode('/', $source)),
    	        "gallery_file"=>end(explode('/', $source)),
    	        "gallery_alt_tag"=>$r['product_name']." (image)"
    	    ));
    	    
    	    $ins++;
	      }
	    }
	  }	  
	}
}

echo "\n\n".$ins." images inserted.";
echo "\n\n".$updates." images updated.";

function grab_image($url,$saveto){
  $url = "https://www.medicalert.org.au/uploads/Products/images/".end(explode('/', $url));
  if(file_exists($saveto)){
    //unlink($saveto);
    
    if(filesize($saveto) == 0){
      
      $ch = curl_init ($url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
      
      $raw=curl_exec($ch);
      
      if($raw === false)
      {
        
        //echo 'Curl error: ' . curl_error($ch);
      }
      else
      {
        $fp = fopen($saveto,'w+');
        fwrite($fp, $raw);
        fclose($fp);
      }
      curl_close ($ch);
      
      return 1;
    }
  }else{
      
    
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    
    $raw=curl_exec($ch);
    
    if($raw === false)
    {
      //echo 'Curl error: ' . curl_error($ch);
    }
    else
    {
      $fp = fopen($saveto,'w+');
      fwrite($fp, $raw);
      fclose($fp);

      //var_dump(curl_getinfo($ch)); exit;
    }
    curl_close ($ch);
  }
  
  return 0;
}

