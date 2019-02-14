<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  global $DBobject;
  
  $response = false;
  $url = false;
  if (!empty($_POST['table']) && !empty($_POST['objid']) && !empty($_POST['parentfield'])) {
  	$pre = str_replace("tbl_","",$_POST['table']);
  	$sql = "SELECT * FROM {$_POST['table']} WHERE {$pre}_object_id = :id AND {$pre}_published = '0' AND {$pre}_deleted IS NULL";
  	
  	if($res = $DBobject->wrappedSql($sql,array(':id'=>$_POST['objid']))){
  		$response = true;
  		$url = '/draft/' . getUrl($res[0]["{$_POST['parentfield']}"],1, $res[0]["{$pre}_url"]) ;
  	}
  }
  
  echo json_encode(array("response"=>$response,"url"=>$url));
  die();
}



function getUrl($id, $published = 1, $url = '') {
  global $DBobject;
  
  $data = $url;
  $sql = "SELECT listing_url, listing_parent_id FROM tbl_listing WHERE listing_object_id = :id AND listing_deleted IS NULL ORDER BY listing_published = :published";
  if($res = $DBobject->executeSQL($sql,array(':id'=>$id,':published'=>$published))){
  	if(!empty($res[0]['listing_parent_id']) && intval($res[0]['listing_parent_id'])>0 && !empty($res[0]['listing_url'])){
  		$data = getUrl($res[0]['listing_parent_id'],1,$res[0]['listing_url']. '/' . $url);
  	}else{
    	$data = $res[0]['listing_url'] . '/' . $url;
  	}
  }
  return $data;
}
