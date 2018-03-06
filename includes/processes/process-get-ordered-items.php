<?php
global $CONFIG, $SMARTY, $DBobject, $GA_ID, $REQUEST_URI;
try{
  $products = array();
  $cart_obj = new cart($_SESSION['user']['public']['id']);
  
    //LOAD PRODUCTS
    $prodObj = new ProductClass('', $CONFIG->product_page);
    $sql = "SELECT cartitem_id, cartitem_cart_id, cartitem_product_id, cartitem_variant_id, cartitem_product_uid, cartitem_product_name,
            cartitem_type_id, tbl_variant.*, tbl_product.*, payment_cart_id, payment_user_id 
            FROM tbl_cartitem LEFT JOIN `tbl_payment` ON cartitem_cart_id = payment_cart_id
            LEFT JOIN tbl_product ON cartitem_product_id = product_object_id 
            LEFT JOIN tbl_variant ON cartitem_variant_id = variant_id 
            WHERE payment_user_id = :user_id AND payment_response_code = 'approved' AND (cartitem_type_id = 1 OR cartitem_type_id = 4) AND product_published = 1 AND product_deleted IS NULL AND variant_deleted IS NULL GROUP BY cartitem_variant_id ORDER BY cartitem_modified DESC";
    if($products = $DBobject->wrappedSql($sql, array(":user_id" => $_SESSION['user']['public']['id']))){

      foreach($products as $p){
        
        $cart_arr[$p['cartitem_id']] = $p;
        $cart_arr[$p['cartitem_id']]['general_details'] = $prodObj->GetProductGeneralDetails($p['cartitem_product_id']);
        // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
        $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0' ORDER BY cartitem_attr_order";
        $params = array(":id" => $p['cartitem_id']);
        $cart_arr[$p['cartitem_id']]['attributes'] = $DBobject->wrappedSql($sql, $params);
        foreach($cart_arr[$p['cartitem_id']]['attributes'] as $k => $attr){
          $cart_arr[$p['cartitem_id']]['attributes'][$k]['additionals'] = json_decode($cart_arr[$p['cartitem_id']]['attributes'][$k]['cartitem_attr_attr_value_additional']);
        }
        
        // ---------------- PRODUCT CATEGORY ----------------
        $cart_arr[$p['cartitem_id']]['category'] = $cart_obj->getFullCategoryName($p['cartitem_product_id']);
        
        // ---------------- PRODUCTS GALLERY ----------------
        $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_variant_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
        $params = array(":id" => $p['cartitem_variant_id']);
        $galArr = $DBobject->wrappedSql($sql, $params);
        
        if(empty($galArr) && !empty($cart_arr[$p['cartitem_id']]['attributes'])){
          //Get similar variant based on attribute
          $params = array(":id" => $p['product_id']);
          $whereStr = '';
          $paramsArr = array();
          foreach($cart_arr[$p['cartitem_id']]['attributes'] as $k => $attr){
            if(!empty($attr['cartitem_attr_attr_value_id'])){
              $params[":attr{$k}"] = $attr['cartitem_attr_attr_value_id'];
              $whereStr .= " AND productattr_attr_value_id = :attr{$k}";
              $paramsArr[$k]['params'] = $params;
              $paramsArr[$k]['where'] = $whereStr;
            }
          }
          $reversedArr = array_reverse($paramsArr);
          foreach($reversedArr as $v){
            $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_variant LEFT JOIN tbl_gallery ON gallery_variant_id = variant_id
            LEFT JOIN tbl_productattr ON productattr_variant_id = variant_id
            WHERE gallery_deleted IS NULL AND productattr_deleted IS NULL AND gallery_link IS NOT NULL AND variant_deleted IS NULL AND variant_product_id = :id {$v['where']} ORDER BY gallery_order LIMIT 1";
            if($galArr = $DBobject->wrappedSql($sql, $v['params'])){
              break;
            }
          }
        }
        
        if(empty($galArr)){
          //Get base product image
          $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
          $params = array(":id" => $p['product_id']);
          $galArr = $DBobject->wrappedSql($sql, $params);
        }
        $cart_arr[$p['cartitem_id']]['gallery'] = $galArr;
        
        // ---------------- PRODUCT PRICE MODIFIER ----------------
        $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
        $params = array(
            ":qty" => $p['cartitem_quantity'],
            ":pid" => $p['variant_id']
        );
        if($mod = $DBobject->wrappedSql($sql, $params)){
          $cart_arr[$p['cartitem_id']]['productqty_modifier'] = $mod[0];
        }
      }
    }
  
  //echo '<pre>';print_r($cart_arr);
  $SMARTY->assign('products', $cart_arr);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  
