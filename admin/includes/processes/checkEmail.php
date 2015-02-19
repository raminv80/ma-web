<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include_once 'admin/includes/functions/admin-functions.php';
  global $DBobject;
  
  $duplicated = true;
  if(!empty($_POST['username']) && !empty($_POST['table'])){
    $usr = $_POST['username'];
    $sql = "SELECT {$_POST['table']}_id AS ID from tbl_{$_POST['table']} WHERE {$_POST['table']}_username = :user AND {$_POST['table']}_deleted IS NULL";
    $res = $DBobject->wrappedSqlGet($sql,array("user" => $usr));
    
    $duplicated = false;
    foreach($res as $r){
      if ($r['ID'] != $_POST['id'] && !empty($r['ID'])){
        $duplicated = true;
      }
    }
  }	
  echo json_encode(array("email"=>$duplicated));
  die();
}
echo json_encode(array("email"=>true));
die();

