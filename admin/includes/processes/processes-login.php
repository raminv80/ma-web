<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
if(checkToken($_POST["token"])){
	$result = AdminLogIn($_POST['email'],$_POST['password']);
	if( $result == true ){
		die("<script>document.location.href='/admin/home'</script>");	
	}else{
		echo "wrong email or password<br><br><br><button onclick='$(\"#log\").dialog(\"close\")'>try again</button>";	
	}
}
