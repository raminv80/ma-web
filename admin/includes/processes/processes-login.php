<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
$error = "Session expired!";
if(checkToken('admin', $_POST["formToken"])){
	$error = "Wrong email or password";
	$result = AdminLogIn($_POST['email'],$_POST['password']);
	$error = ($result)?'':$error;
	$redirect = empty($_POST['redirect'])?'/admin/home':$_POST['redirect'];
	$_SESSION['redirect'] = '';
}

echo json_encode(array(
		'error'=>$error,
		'success'=>$result,
		'redirect'=>$redirect
));

