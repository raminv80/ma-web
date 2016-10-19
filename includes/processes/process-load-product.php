<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'].'/products/')){
  $referer = $_SERVER['HTTP_REFERER'];
  
  if(strpos($referer, "?")){
    $referer = substr($referer, 0, strpos($referer, "?"));
  }
  
  $arr = explode('/', $referer);
  
  $url = '';
  for ($i=4;$i<count($arr);$i++){
    $url.="/".$arr[$i];
  }
  
  $sql = 'SELECT listing_name FROM tbl_listing WHERE listing_url = :listing_url AND listing_deleted IS NULL AND listing_type_id = 10 AND listing_published = 1';
  if($res = $DBobject->executeSQL($sql,array("listing_url"=>$arr[count($arr)-1]))){

    $SMARTY->assign('backcollectionURL', $url);
    $SMARTY->assign('backcollection', strtolower($res[0]['listing_name']));
  }
}