<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  global $DBobject;
  
  // $name = $_POST ['value'];
  $url = urlSafeString( htmlspecialchars_decode($_POST ['value'], ENT_QUOTES));
  $duplicated = null;
  
  if (!empty($_POST ['id']) && !empty($_POST ['idfield']) && !empty($_POST ['table']) && !empty($_POST ['field'])) {
  	$duplicated = false;
  	$pre = str_replace ( "tbl_", "", $_POST ['table'] );
  	$params = array(':url'=>$url);
  	$where = '';
  	if(!empty($_POST['field2']) && !empty($_POST['value2'])){
  		$where = "AND {$_POST['field2']} = :pid ";
  		$params = array_merge($params,array(':pid'=>$_POST ['value2']));
  	}
  	$sql = "SELECT {$_POST ['idfield']} AS ID FROM {$_POST ['table']} WHERE {$_POST ['field']} = :url {$where} AND {$pre}_deleted IS NULL";
  	if($res = $DBobject->wrappedSql($sql,$params)){
  		foreach ($res as $r){
  			if ($r['ID'] != $_POST ['id']){
  				echo json_encode ( array ("url" => $url, "duplicated" => true  ) );
  				die ();
  			}
  		}
  	}
  }
  echo json_encode ( array ("url" => $url, "duplicated" => $duplicated  ) );
  die ();
}
