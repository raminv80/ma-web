<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "includes/functions/functions.php";
$Action = strip_tags($_POST["Action"]);
$Error_check = false;
$Errors = '';
if($Action	|| checkToken($_POST["formToken"])){
	switch ($Action) {
		case 'Redirect' :
			if($_POST['redirect']){
				header('Location:'.$_POST['redirect']);
			}else{
				header('Location:/Home');
			}
		break;
		case 'RegisterUser':
			
			$res = ProcessSaveRegistration($_POST);
			if($res == 1){
				$redirect='/login';
				$_SESSION['notice'][]='Your account has been requested, please wait for a confirmation email';
			}else{
				$redirect ='/register';
				$_SESSION['error'][]='Email already on system';
				foreach($_POST as $key => $value){
					$_SESSION['smarty'][$key] = $value;
				}
			}
		break;
		case 'UserLogin':
			$res = ProcessUserLogin($_POST);
			if($res != 1){
				$redirect='/login';
				
			}else{
				$redirect ='/';				
			}
		break;
		case 'UserAsk':
			$res = ProcessUserAsk($_POST);
			if($res != 1){
				$redirect ='/ask-a-question';		
			}else{
				$redirect ='/thank-you';				
			}
		break;
		case 'UserRecover':
			$res = ProcessUserRecover($_POST);
			$redirect ='/login';	
		break;			
	}
	

	//echo "<script>document.location.href='".$redirect."'</script>";
	header("Location:".$redirect); /* Redirect browser */
	die();
}else{
	//echo "<script>document.location.href='/index.php'</script>";
	header("Location:/index.php"); /* Redirect browser */
	die();
}