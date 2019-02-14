<?php
session_start();
$res = "";
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include_once 'database/utilities.php';
  
  $usr = $_POST['username'];
  $pwd = $_POST['password'];
  
  if(isValidPassword($pwd)){
    $res = getPass($usr,$pwd);
  }
}
echo json_encode(array("password"=>$res));
die();
