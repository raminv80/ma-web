<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

$baseURL = 'https://www.medicalert.org.au/';
$brandName = "MedicAlert";
$googleProductCategory = "Health & Beauty > Health Care > Medical Identification Tags & Jewelry";

//LOAD PRODUCT TYPES
$ptypes = array();
$sql = "SELECT ptype_id, ptype_name FROM tbl_ptype WHERE ptype_deleted IS NULL ORDER BY ptype_order, ptype_name";
if($res = $DBobject->wrappedSql($sql)){
  foreach($res as $r){
    $ptypes[$r['ptype_id']] = $r['ptype_name'];
  }
}

//LOAD PRICE FILTERS
$prices = array(
    array('name' => '$0 - $50', 'cnt' => 0, 'value' => '0-50', 'min' => 0, 'max' => 50),
    array('name' => '$51 - $100', 'cnt' => 0, 'value' => '51-100', 'min' => 51, 'max' => 100),
    array('name' => '$101 - $200', 'cnt' => 0, 'value' => '101-200', 'min' => 101, 'max' => 200),
    array('name' => '$201 - $400', 'cnt' => 0, 'value' => '201-400', 'min' => 201, 'max' => 400),
    array('name' => '$401 plus', 'cnt' => 0, 'value' => '401-99999', 'min' => 401, 'max' => 99999)
);


$sql = "SELECT  variant_id AS id, 
                    `product_id` AS item_group_id, 
                    `product_name` AS title, 
                    CONCAT('{$baseURL}','',`product_url`) AS link, 
        `product_meta_description` AS description, 
        CONCAT(`variant_price`, ' AUD') AS price, 
        IF(variant_instock='1','in stock','preorder') AS availability, 
        IF(product_id,'new','') AS `condition`, 
        IF(product_id,'{$brandName}','') AS `brand`, 
        IF(product_id,'{$googleProductCategory}','') AS `google_product_category`,
        IF(product_uid, product_uid, CONCAT(variant_id,product_object_id)) AS 'mpn',
        product_associate1 AS `product_type`,
        variant_price AS `custom_label_0`
        
        FROM `tbl_product` 
        LEFT JOIN tbl_variant ON variant_product_id = product_id
        WHERE `product_deleted` IS NULL AND `product_published` = 1 AND (product_type_id =1 OR product_type_id =4) 
        AND (product_membersonly IS NULL OR product_membersonly = 0)
        AND variant_published =1 AND variant_deleted IS NULL 
        GROUP BY id
        ORDER BY item_group_id, id";

if($res = $DBobject->wrappedSql($sql, array())){
  
  foreach($res as $key => $r){
    $res[$key]['color'] = '';
    $res[$key]['size'] = '';
    $res[$key]['image_link'] = '';
    $res[$key]['shipping'] = "AU:::9.50 AUD";
    
    $flag = 0;
    
    $colsql = "SELECT attr_value_name AS color FROM tbl_productattr 
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 2";
    
    if($colres = $DBobject->wrappedSql($colsql, array(
        'variant_id' => $r['id'] 
    ))){
      $res[$key]['color'] = $colres[0]['color'];
      
      $res[$key]['description'] = $res[$key]['description'] . " Colour: " . $res[$key]['color'];
      
      $flag = 1;
      
      $psql = "SELECT variant_id, gallery_link, attr_value_name FROM tbl_product
                    RIGHT JOIN tbl_variant ON variant_product_id = product_id
                    RIGHT JOIN tbl_productattr ON productattr_variant_id = variant_id AND `productattr_attribute_id` = 2
                    RIGHT JOIN tbl_attr_value ON attr_value_id = `productattr_attr_value_id`
                    RIGHT JOIN tbl_gallery ON gallery_variant_id = variant_id
                    WHERE variant_deleted IS NULL AND product_deleted is NULL AND `productattr_deleted` IS NULL AND gallery_deleted IS NULL AND attr_value_deleted IS NULL
                    AND variant_product_id = :pid AND attr_value_name = :color";
      
      if($pres = $DBobject->wrappedSql($psql, array(
          'pid' => $r['item_group_id'], 
          'color' => $colres[0]['color'] 
      ))){
        $res[$key]['image_link'] = 'https://www.medicalert.org.au' . $pres[0]['gallery_link'];
      } else{
        $psql = "SELECT gallery_link FROM tbl_product
                    RIGHT JOIN tbl_gallery ON gallery_product_id = product_id
                    WHERE product_deleted is NULL AND gallery_deleted IS NULL 
                    AND product_id = :pid";
        if($pres = $DBobject->wrappedSql($psql, array(
            'pid' => $r['item_group_id'] 
        ))){
          $res[$key]['image_link'] = 'https://www.medicalert.org.au' . $pres[0]['gallery_link'];
        }
      }
    }
    
    $sizesql = "SELECT attr_value_name AS custom_label_1 FROM tbl_productattr
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 4";
    
    if($sizeres = $DBobject->wrappedSql($sizesql, array(
        'variant_id' => $r['id'] 
    ))){
      $res[$key]['size'] = $sizeres[0]['custom_label_1'];
      
      if($flag == 1){
        $res[$key]['description'] = $res[$key]['description'] . ",";
      }
      $res[$key]['description'] = $res[$key]['description'] . " Size: " . $res[$key]['size'];
    }
    
    $lensql = "SELECT attr_value_name AS custom_label_0 FROM tbl_productattr
    	        LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id AND attr_value_attribute_id = productattr_attribute_id
    	        WHERE productattr_variant_id = :variant_id AND productattr_attribute_id = 1";
    
    if($lenres = $DBobject->wrappedSql($lensql, array(
        'variant_id' => $r['id'] 
    ))){
      $res[$key]['size'] .= ", Length: " . $lenres[0]['custom_label_0'];
      
      if($flag == 1){
        $res[$key]['description'] = $res[$key]['description'] . ",";
      }
      $res[$key]['description'] = $res[$key]['description'] . " Length: " . $res[$key]['length'];
      
      $flag = 1;
    }
    
    //Set product type
    $res[$key]['product_type'] = empty($res[$key]['product_type']) ? '' : $ptypes[$res[$key]['product_type']];
    
    //Set price range
    foreach($prices as $pr){
      if(floatval($res[$key]['custom_label_0']) >= $pr['min'] && floatval($res[$key]['custom_label_0']) <= $pr['max']){
        $res[$key]['custom_label_0'] = $pr['value'];
        break;
      }
    }
  }
}

$csv = AssociativeArrayToTXT($res);

$filename = 'product_feed_' . date('Y-m-d') . '.txt';
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;


