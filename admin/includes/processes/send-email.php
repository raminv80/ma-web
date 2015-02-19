<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  global $DBobject, $CONFIG, $SMARTY;
  
  if(checkToken('admin', $_POST["formToken"]) && !empty($_POST['action']) && !empty($_POST['email']) ){
  	$template = '';
  	
  	$to = $_POST['email'];
  	$from = (string) $CONFIG->company->name;
  	$fromEmail = (string) $CONFIG->company->email_from;
  
  	switch ($_POST['action']){
  		case 'UserPassword':
  			$subject = 'Your account details';
  			$SMARTY->assign("name",$_POST['name']);
  			$SMARTY->assign("password",$_POST['password']);
  			$SMARTY->assign("DOMAIN","http://".$_SERVER['HTTP_HOST']);
  			$body= $SMARTY->fetch('email-admin-user.tpl');
  			break;
  	}
  	if(!empty($to) && !empty($subject) && !empty($body)){
  		$response = sendMail($to, $from, $fromEmail, $subject, $body);
  		echo json_encode(array(
  				"response" => $response
  		));
  		die ();
  	}
  }
  echo json_encode(array(
  		"response" => null
  ));
  die ();
}
echo json_encode(array(
  		"response" => null
));
die ();