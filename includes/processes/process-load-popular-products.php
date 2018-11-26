<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  //Members only
  $whereSQL = empty($_SESSION['user']['public']['id']) ? 'AND (product_membersonly IS NULL OR product_membersonly = 0)' : '';
  
  //LOAD PRODUCTS
  $prodObj = new ProductClass('', $CONFIG->product_page);
  $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description FROM tbl_product 
  WHERE (product_deleted IS NULL OR product_deleted = '0000-00-00') 
  AND product_published = 1 AND product_featured = 1 {$whereSQL} ORDER BY product_order, product_name LIMIT 8";
  if($products = $DBobject->wrappedSql($sql)){
    foreach($products as &$p){
      $p['general_details'] = $prodObj->GetProductGeneralDetails($p['product_object_id']);
    }
  }
  $SMARTY->assign('popular_products', $products);

}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}


