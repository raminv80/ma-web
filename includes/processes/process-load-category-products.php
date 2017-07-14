<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{

  //LOAD PRODUCT TYPES
  $ptypes = array();
  $sql = "SELECT ptype_id, ptype_name FROM tbl_ptype WHERE ptype_deleted IS NULL ORDER BY ptype_order, ptype_name";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $ptypes[$r['ptype_id']]['name'] = $r['ptype_name'];
      $ptypes[$r['ptype_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('ptypes', $ptypes);
  
  
  //LOAD PRODUCT MATERIALS
  $pmaterials = array();
  $sql = "SELECT pmaterial_id, pmaterial_name FROM tbl_pmaterial WHERE pmaterial_deleted IS NULL ORDER BY pmaterial_order, pmaterial_name";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $pmaterials[$r['pmaterial_id']]['name'] = $r['pmaterial_name'];
      $pmaterials[$r['pmaterial_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('pmaterials', $pmaterials);
  

  //LOAD PRODUCT ATTRIBUTES >>>>> ONLY COLOURS <<<<<<
  $attributes = array();
  $sql = "SELECT attribute_id, attribute_name, attr_value_id, attr_value_name FROM tbl_attribute
      LEFT JOIN tbl_attr_value ON attr_value_attribute_id = attribute_id
      WHERE attribute_deleted IS NULL AND attribute_type = 1 AND attr_value_deleted IS NULL AND attr_value_flag1 = 1 ORDER BY attr_value_order, attr_value_name";
  if($res = $DBobject->wrappedSql($sql, $paramsType)){
    foreach($res as $r){
      $attributes[$r['attribute_id']]['name'] = $r['attribute_name'];
      $attributes[$r['attribute_id']]['values'][$r['attr_value_id']]['name'] = $r['attr_value_name'];
      $attributes[$r['attribute_id']]['values'][$r['attr_value_id']]['cnt'] = 0;
    }
  }
  $SMARTY->assign('attributes', $attributes);
  
  //LOAD PRICE FILTERS
  $prices = array(
      array('name' => 'Sale items only', 'cnt' => 0, 'value' => 'sale', 'min' => 0, 'max' => 0),
      array('name' => '$0 - $50', 'cnt' => 0, 'value' => '0-50', 'min' => 0, 'max' => 50),
      array('name' => '$51 - $100', 'cnt' => 0, 'value' => '51-100', 'min' => 51, 'max' => 100),
      array('name' => '$101 - $200', 'cnt' => 0, 'value' => '101-200', 'min' => 101, 'max' => 200),
      array('name' => '$201 - $400', 'cnt' => 0, 'value' => '201-400', 'min' => 201, 'max' => 400),
      array('name' => '$401 plus', 'cnt' => 0, 'value' => '401-99999', 'min' => 401, 'max' => 99999)
  );
  $SMARTY->assign('prices', $prices);

  
  //FILTERS
  //Members only
  $whereSQL = empty($_SESSION['user']['public']['id']) ? 'AND (product_membersonly IS NULL OR product_membersonly = 0)' : '';
  $baseParams = array();
  $params = array();
    
  
  //LOAD PRODUCTS
  $prodObj = new ProductClass('', $CONFIG->product_page);
  $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description, product_associate1 FROM tbl_product LEFT JOIN tbl_productcat ON productcat_product_id = product_id
  WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1 AND productcat_listing_id = :id {$whereSQL} GROUP BY product_object_id ORDER BY product_order, product_name";
  $params = array_merge(array('id' => $SMARTY->getTemplateVars('listing_object_id')), $baseParams);
  if($products = $DBobject->wrappedSql($sql, $params)){
    foreach($products as &$p){
      
      $p['general_details'] = $prodObj->GetProductGeneralDetails($p['product_object_id']);
      
      //Set price range
      foreach($prices as $pr){
        if($p['general_details']['price']['min'] >= $pr['min'] && $p['general_details']['price']['min'] <= $pr['max']){
          $p['price_range'] = $pr['value'];
          break;
        }
      }
    }
  }
  $SMARTY->assign('products', $products);
  
  $recentProducts = $prodObj->GetRecentViewProduct();
  $SMARTY->assign('recent_products', (count($recentProducts) > 4) ? $recentProducts : '');
  

  //LOAD COLLECTIONS
  $sql = "SELECT listing_name, listing_url, listing_object_id FROM tbl_listing
      WHERE listing_deleted IS NULL AND listing_published = 1 AND listing_type_id = 10 AND listing_flag1 = '1'
      GROUP BY listing_object_id ORDER BY listing_order, listing_name";
  if($collections = $DBobject->wrappedSql($sql)){
    foreach($collections as &$c){
      $sql = "SELECT COUNT(product_object_id) as CNT FROM tbl_product LEFT JOIN tbl_productcat ON productcat_product_id = product_id
        WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1 AND productcat_listing_id = :id {$whereSQL} GROUP BY product_object_id";
      $params['id'] = $c['listing_object_id'];
      if($products = $DBobject->wrappedSql($sql, $params)){
        $c['cnt'] = count($products);
      }
    }
    
  }
  $SMARTY->assign('collections', $collections);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
