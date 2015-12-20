<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include_once 'database/utilities.php';
  
  $usr = $_POST['username'];
  $pwd = $_POST['password'];
  
  $res = getPass($usr,$pwd);
  
  echo json_encode(array("password"=>$res));
  die();
}
echo json_encode(array("password"=>""));
die();
