<?php

if($_POST["action"]){
	
	switch ($_POST["action"]) {
		
		case 'create':
			$user_obj = new UserClass();
			$res = $user_obj->Create($_POST);
			
			if( $res['error'] ) {
				$_SESSION['error']= $res['error'];
				$_POST["password"] = '';
				$_POST["confirm_password"] = '';
				$_SESSION['post']= $_POST;
				header("Location: ".$_SERVER['HTTP_REFERER']."#error");
			} else {
				$_SESSION['user'] = $res;
				$cart_obj = new cart();
				$cart_obj->SetUserCart($_SESSION['user']['id']);
				header("Location: /store");
			}
			exit;
	    
	    case 'login':
	    	$user_obj = new UserClass();
	    	 $res = $user_obj->Authenticate($_POST["email"], $_POST["pass"]); 
	    	 if ( $res['error'] ) {
	    	 	$_SESSION['error']= $res['error'];
	    	 	$_POST["pass"] = '';
	    	 	$_SESSION['post']= $_POST;
	    	 	header("Location: ".$_SERVER['HTTP_REFERER']."#error");
	    	} else {
	    		$cart_obj = new cart();
	    		$cart_obj->SetUserCart($res['id']);
	    		$_SESSION['user'] = $res;
	    		header("Location: /store");
	    	}
	    	exit;
	   
    	case 'FBlogin':
    		$user_obj = new UserClass();
    		$fbData = json_decode(html_entity_decode($_POST['fbdata']));
			$userArr = (array) $fbData;
    		$res = $user_obj->AuthenticateFacebook($userArr);
    		if ($res['error']) {
    		
    		} else {
    			$cart_obj = new cart();
    			$cart_obj->SetUserCart($res['id']);
    			$_SESSION['user'] = $res;
    			echo json_encode(array("url" => 'http://' . $_SERVER['HTTP_HOST']. '/store'));
			} 
				
    		exit;
    		
		case 'FBlogout':
    		$_SESSION ['user'] = "";
			unset ( $_SESSION ['user'] );
			session_regenerate_id();
    		echo json_encode(array("response" => 'FB logout'));
    		exit;

	}
}elseif ($_GET["logout"]) {
		$_SESSION ['user'] = "";
		unset ( $_SESSION ['user'] );
		session_regenerate_id();
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
}else{
	die('');
}