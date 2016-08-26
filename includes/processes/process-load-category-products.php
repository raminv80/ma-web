<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $products = array();
  $prodObj = new ProductClass('', $CONFIG->product_page);

  $sql = "SELECT product_object_id FROM tbl_product LEFT JOIN tbl_productcat ON productcat_product_id = product_id 
      WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1 AND productcat_listing_id = :id";
  if($prods = $DBobject->wrappedSql($sql, array('id' => $SMARTY->getTemplateVars('listing_object_id')))){
    foreach($prods as $p){
      $products[] = $prodObj->GetProductGeneralDetails($p['product_object_id']);
    }
  }
  $SMARTY->assign('products', $products);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
