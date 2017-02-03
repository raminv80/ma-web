<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;


	
    $sql = "SELECT  variant_id AS id, `product_id` AS item_group_id, `product_name` AS title, CONCAT('https://www.medicalert.org.au/','',`product_url`) AS link, 
        `product_meta_description` AS description, `variant_price` AS price, IF(variant_instock='1','in stock','preorder') AS availability, 
        IF(product_id,'new','') AS `condition`, IF(product_id,'MedicAlert','') AS `brand`, 
        IF(product_id,'Health & Beauty > Health Care > Medical Identification Tags & Jewelry','') AS `google_product_category`
        FROM `tbl_product` 
        LEFT JOIN tbl_variant ON variant_product_id = product_id
        WHERE `product_deleted` IS NULL AND `product_published` = 1 AND (product_type_id =1 OR product_type_id =4) 
        AND (product_membersonly IS NULL OR product_membersonly = 0)
        AND variant_published =1 AND variant_deleted IS NULL 
        GROUP BY id
        ORDER BY item_group_id, id";
    
	if($res = $DBobject->wrappedSql($sql, array())){
	  
	  
	  foreach ($res as $key=>$r){
	        $res[$key]['color'] = '';
	        $res[$key]['length'] = '';
	        $res[$key]['size'] = '';
	        $res[$key]['image_link'] = '';
	        
    	    $colsql = "SELECT attr_value_name AS color FROM tbl_productattr 
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 2";
    	    
    	    if($colres = $DBobject->wrappedSql($colsql, array('variant_id'=>$r['id']))){
    	      $res[$key]['color'] = $colres[0]['color'];
    	      
    	      $psql = "SELECT variant_id, gallery_link, attr_value_name FROM tbl_product
                    RIGHT JOIN tbl_variant ON variant_product_id = product_id
                    RIGHT JOIN tbl_productattr ON productattr_variant_id = variant_id AND `productattr_attribute_id` = 2
                    RIGHT JOIN tbl_attr_value ON attr_value_id = `productattr_attr_value_id`
                    RIGHT JOIN tbl_gallery ON gallery_variant_id = variant_id
                    WHERE variant_deleted IS NULL AND product_deleted is NULL AND `productattr_deleted` IS NULL AND gallery_deleted IS NULL AND attr_value_deleted IS NULL
                    AND variant_product_id = :pid AND attr_value_name = :color";
    	      
    	      if($pres = $DBobject->wrappedSql($psql, array('pid'=>$r['item_group_id'],'color'=>$colres[0]['color']))){
    	        $res[$key]['image_link'] = 'https://www.medicalert.org.au'.$pres[0]['gallery_link'];
    	      }else{
    	        $psql = "SELECT gallery_link FROM tbl_product
                    RIGHT JOIN tbl_gallery ON gallery_product_id = product_id
                    WHERE product_deleted is NULL AND gallery_deleted IS NULL 
                    AND product_id = :pid";
    	        if($pres = $DBobject->wrappedSql($psql, array('pid'=>$r['item_group_id']))){
    	          $res[$key]['image_link'] = 'https://www.medicalert.org.au'.$pres[0]['gallery_link'];
    	        }
    	      }
    	    }
    	    
    	    $lensql = "SELECT attr_value_name AS custom_label_0 FROM tbl_productattr
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 1";
    	    	
    	    if($lenres = $DBobject->wrappedSql($lensql, array('variant_id'=>$r['id']))){
    	      $res[$key]['length'] = $lenres[0]['custom_label_0'];
    	    }
    	    
    	    $sizesql = "SELECT attr_value_name AS custom_label_1 FROM tbl_productattr
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 4";
    	    	
    	    if($sizeres = $DBobject->wrappedSql($sizesql, array('variant_id'=>$r['id']))){
    	      $res[$key]['size'] = $sizeres[0]['custom_label_1'];
    	    }
	  }
	}
	
	

	$csv = AssociativeArrayToCSV($res);
				
	$filename='product_feed_'.date('Y-m-d') .'.csv';
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Length: " . strlen($csv));
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$filename);
    echo $csv;


