<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $popular = array();
  $sql = "SELECT product_object_id, product_url, product_name FROM tbl_product WHERE product_deleted IS NULL AND product_published = 1 ORDER BY product_order";
  $res = $DBobject->wrappedSql($sql);
  $SMARTY->assign('popular_products', $popular);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
