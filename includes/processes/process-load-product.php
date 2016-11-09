<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

$impressionList = '';
if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] . '/products/')){
  $referer = $_SERVER['HTTP_REFERER'];
  
  if(strpos($referer, "?")){
    $referer = substr($referer, 0, strpos($referer, "?"));
  }
  
  $arr = explode('/', $referer);
  
  $url = '';
  for($i = 4; $i < count($arr); $i++){
    $url .= "/" . $arr[$i];
  }
  
  $sql = 'SELECT listing_name FROM tbl_listing WHERE listing_url = :listing_url AND listing_deleted IS NULL AND listing_type_id = 10 AND listing_published = 1';
  if($res = $DBobject->executeSQL($sql, array(
      "listing_url" => $arr[count($arr) - 1] 
  ))){
    $impressionList = unclean($res[0]['listing_name']);
    $SMARTY->assign('impressionList', $impressionList);
    $SMARTY->assign('backcollectionURL', $url);
    $SMARTY->assign('backcollection', strtolower($impressionList));
  }
}

if(!empty($GA_ID)){
  $prodArr = array();
  $poid = $SMARTY->getTemplateVars('product_object_id');
  $pname = $SMARTY->getTemplateVars('product_name');
  $variantsArr = $SMARTY->getTemplateVars('variants');
  $detailsArr = $SMARTY->getTemplateVars('general_details');
  foreach($variantsArr as $v){
    $vAttrArr = array();
    foreach($detailsArr['has_attributes'] as $attr){
      foreach($attr as $val){
        if(in_array($v['variant_id'], $val['variants'])){
          $vAttrArr[] = $val['values']['attr_value_name'];
        }
      } 
    }
    $prodArr[] = array(
        'id' => (empty($v['variant_uid']) ? "{$poid}-{$v['variant_id']}" : $v['variant_uid']),
        'name' => $pname,
        'category' => 'products',
        'brand' => '',
        'variant' => implode('/', $vAttrArr),
        'position' => 0
    );
  }
  sendGAEnEcImpressionAction($GA_ID, 'detail', $prodArr, $impressionList);
}