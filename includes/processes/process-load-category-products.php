<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  //LOAD COLLECTIONS
  $sql = "SELECT COUNT(product_object_id) AS cnt, listing_name, listing_url, listing_object_id FROM tbl_listing
      LEFT JOIN tbl_productcat ON productcat_listing_id = listing_object_id
      LEFT JOIN tbl_product ON product_id = productcat_product_id
      WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1
      AND listing_deleted IS NULL AND listing_published = 1 AND listing_type_id = 10
      GROUP BY listing_object_id ORDER BY listing_order, listing_name";
  $collections = $DBobject->wrappedSql($sql);
  $SMARTY->assign('collections', $collections);
  

  //LOAD PRODUCT TYPES
  $ptypes = array();
  $sql = "SELECT ptype_id, ptype_name FROM tbl_ptype WHERE ptype_deleted IS NULL";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $ptypes[$r['ptype_id']]['name'] = $r['ptype_name'];
      $ptypes[$r['ptype_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('ptypes', $ptypes);
  
  
  //LOAD PRODUCT MATERIALS
  $pmaterials = array();
  $sql = "SELECT pmaterial_id, pmaterial_name FROM tbl_pmaterial WHERE pmaterial_deleted IS NULL";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $pmaterials[$r['pmaterial_id']]['name'] = $r['pmaterial_name'];
      $pmaterials[$r['pmaterial_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('pmaterials', $pmaterials);
  

  //LOAD PRODUCT ATTRIBUTES
  $attributes = array();
  $sql = "SELECT attribute_id, attribute_name, attr_value_id, attr_value_name FROM tbl_attribute
      LEFT JOIN tbl_attr_value ON attr_value_attribute_id = attribute_id
      WHERE attribute_deleted IS NULL AND attr_value_deleted IS NULL";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $attributes[$r['attribute_id']]['name'] = $r['attribute_name'];
      $attributes[$r['attribute_id']]['values'][$r['attr_value_id']]['name'] = $r['attr_value_name'];
      $attributes[$r['attribute_id']]['values'][$r['attr_value_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('attributes', $attributes);

  
  //FILTERS
  $whereSQL = "";
  $baseParams = array();
  
  //LOAD PRODUCTS
  $prodObj = new ProductClass('', $CONFIG->product_page);
  $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description FROM tbl_product LEFT JOIN tbl_productcat ON productcat_product_id = product_id
  WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1 AND productcat_listing_id = :id {$whereSQL} ORDER BY product_order, product_name";
  $params = array_merge(array('id' => $SMARTY->getTemplateVars('listing_object_id')), $baseParams);
  if($products = $DBobject->wrappedSql($sql, $params)){
    foreach($products as &$p){
      
      $p['general_details'] = $prodObj->GetProductGeneralDetails($p['product_object_id']);
    }
  }
  $SMARTY->assign('products', $products);
  
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
