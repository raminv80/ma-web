<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

//LOAD BUPA PRODUCTS - 667
if(!empty($SMARTY->getTemplateVars('listing_type_id')) && !empty($SMARTY->getTemplateVars('listing_associate1')) && $SMARTY->getTemplateVars('listing_type_id') == 6){
  try{
    $discountCode = $SMARTY->getTemplateVars('listing_content6');
    $collectionID = $SMARTY->getTemplateVars('listing_associate1');	
    $prodObj = new ProductClass('', $CONFIG->product_page);
    $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description, product_associate1 
        FROM tbl_product 
        LEFT JOIN tbl_productcat ON productcat_product_id = product_id
    WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1 AND productcat_listing_id = :id ORDER BY product_order, product_name";
    $params = array('id' => $collectionID);
    if($products = $DBobject->wrappedSql($sql, $params)){
      
      foreach($products as &$p){
        $p['product_url'] .= '?setdc=' . $discountCode; 
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
    $SMARTY->assign('discount_code', $discountCode);
    $SMARTY->assign('products', $products);
    
  }catch(exceptionCart $e) {
    $SMARTY->assign('error', $e->getMessage());
  }
}
  
