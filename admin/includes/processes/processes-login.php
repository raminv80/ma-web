<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
$error = "Wrong email or password";
if(checkToken('admin', $_POST["formToken"])){
	$result = AdminLogIn($_POST['email'],$_POST['password']);
	$error = ($result)?'':$error;
}

echo json_encode(array(
		'error'=>$error,
		'success'=>$result
));

