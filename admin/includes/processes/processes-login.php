<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] != $_SERVER['HTTP_HOST']){
  header('HTTP/1.0 403 Forbidden');
  die();
}

set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
global $DBobject;

$error = "Session has expired or token is missing. Please refresh.";
if(checkToken('admin', $_POST["formToken"])){
 
  $sql = "SELECT COUNT(id) AS cnt FROM login_blocked WHERE created > DATE_SUB(NOW(), INTERVAL 1 DAY) AND deleted IS NULL AND ip = :ip";
  $res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
  if($res[0]['cnt'] == 0){
  	if($result = AdminLogIn($_POST['email'],$_POST['password'])){	
  	   $error = ($result===true)?'':$result;
  	   $redirect = empty($_POST['redirect'])?'/admin/home':$_POST['redirect'];
  	   $_SESSION['redirect'] = '';
  	}else{
  	  $error = "Wrong email or password";
  	}
  	$sql = "INSERT INTO login_log (username,token,admin_id,ip,success,user_agent,modified) VALUES (:uname,:token,:admin_id,:ip,:success,:user_agent,now())";
		$res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR'],"uname"=>$_POST['email'],"token"=>$_SESSION['user']['admin']["token"],"admin_id"=>$_SESSION['user']['admin']["id"],"success"=>$result,"user_agent"=>$_SERVER['HTTP_USER_AGENT']));
  	
  	$sql = "SELECT COUNT(id) AS cnt FROM login_log WHERE created > DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND ip = :ip AND success = 0";
  	$res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
  	if($res[0]['cnt'] >= 5){
  	 $sql = "INSERT INTO login_blocked (ip) VALUES (:ip)";
  	 $res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
  	}
  }else{
    $result=false; $error = "You have been blocked for to many incorrect attempts.";
  }
}
echo json_encode(array(
		'error'=>$error,
		'success'=>$result,
		'redirect'=>$redirect
));