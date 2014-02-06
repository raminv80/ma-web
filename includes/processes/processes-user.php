<?php

if(checkToken('frontend',$_POST["formToken"], true)){
	
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
				$cart_obj = new cart();
				$cart_obj->SetUserCart($res['id']);
                                $_SESSION['user'] = $res;
				header("Location: " . $_SESSION ['login_referer']);
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
	    		header("Location: " . $_SESSION ['login_referer']);
	    	}
	    	exit;
                
        case 'resetPassword':
	    	$user_obj = new UserClass();
	    	 $res = $user_obj->resetPassword($_POST["email"]); 
	    	 if ( $res['error'] ) {
	    	 	$_SESSION['error']= $res['error'];
                        $_SESSION['post']= $_POST;
	    	 	header("Location: ".$_SERVER['HTTP_REFERER']."#error");
	    	} else {
	    		$_SESSION['error']= $res['success'];  //<<<<<<<<<<<<<<<<<<< CHANGE THIS TO NOTIFICATION VARIABLE, not error!
	    		header("Location: ".$_SERVER['HTTP_REFERER']."#error");//<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>
	    	}
	    	exit;
                
        case 'updatePassword':
	    	$user_obj = new UserClass();
                $data = array_merge($_POST, array('email'=>$_SESSION['user']['email']));
	    	 $res = $user_obj->UpdatePassword($data); 
	    	 if ( $res['error'] ) {
	    	 	$_SESSION['error']= $res['error'];
	    	 	header("Location: ".$_SERVER['HTTP_REFERER']."#error");
	    	} else {
	    		$_SESSION['error']= $res['success'];  //<<<<<<<<<<<<<<<<<<< CHANGE THIS TO NOTIFICATION VARIABLE, not error!
	    		header("Location: ".$_SERVER['HTTP_REFERER']."#error");//<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>
	    	}
	    	exit;
	   
    	case 'FBlogin':
                $facebook = new Facebook(array(
                        'appId'  => '208591239336752',
                        'secret' => '21ffc84783a7275f8cb9a43c49e00d78'
                ));
                $user_id = $facebook->getUser();
                if($user_id) {
                    try {
                        $profile  = $facebook->api('/me','GET');
                        
                        $user_obj = new UserClass();
                        $res = $user_obj->AuthenticateFacebook($profile);
                        if( $res['error'] ) {
							$_SESSION['error']= $res['error'];
							header("Location: /login#error");
						} else {
							$cart_obj = new cart();
                            $cart_obj->SetUserCart($res['id']);
                            $_SESSION['user'] = $res;
							//header("Location: " . $_SESSION ['login_referer']);
							echo json_encode(array(
									"error" => false,
									"login_url" => null,
									"message" => $_SESSION['error']
							));
						}
                    } catch(FacebookApiException $e) {
                        //$_SESSION['error'] = "Error({$e->getType()}): {$e->getMessage()}";
                        //header("Location: /login#error");
                        echo json_encode(array(
                        		"error" => true,
								"login_url" => null,
                        		"message" => "Error({$e->getType()}): {$e->getMessage()}"
                        ));
                    }
                } else {
                    $login_url = $facebook->getLoginUrl(array( 
                    		'display' => 'popup',
                    		'scope' => 'email, user_birthday, user_location' 
                    ));
                    //header("Location: " .$login_url);
                    echo json_encode(array(
                        	"error" => false,
							"login_url" => $login_url,
                        	"message" => null
                    ));
                }			
    		exit;
    		
     
	}
}elseif ($_GET["logout"]) {
                if ($_SESSION ['user']['social_id']) {
                    $facebook = new Facebook(array(
                            'appId'  => '208591239336752',
                            'secret' => '21ffc84783a7275f8cb9a43c49e00d78'
                    ));
                    $user_id = $facebook->getUser();

                    $logout_url = $facebook->getLogoutUrl(array( 'next' => $_SERVER['HTTP_REFERER'] )); 
                } else {
                    $logout_url = $_SERVER['HTTP_REFERER'];
                }
    		$_SESSION ['user'] = "";
		unset ( $_SESSION ['user'] );
                
                session_regenerate_id();
                session_destroy();
                header('Location: ' . $logout_url );
                
		exit;
}elseif ($_GET["code"]) {// FACEBOOK AUTH 2ND PART
		$facebook = new Facebook(array(
                        'appId'  => '208591239336752',
                        'secret' => '21ffc84783a7275f8cb9a43c49e00d78',
                        'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
                ));
                $user_id = $facebook->getUser();
                if($user_id) {
                    try {
                        $profile  = $facebook->api('/me','GET');
                        $user_obj = new UserClass();
                        $res = $user_obj->AuthenticateFacebook($profile);
                        if( $res['error'] ) {
							$_SESSION['error']= $res['error'];
							//header("Location: /login#error");
	                        echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
						} else {
							$cart_obj = new cart();
			                                $cart_obj->SetUserCart($res['id']);
			                                $_SESSION['user'] = $res;
							//header("Location: " . $_SESSION ['login_referer']);
                            echo "<script> window.opener.redirectWin('{$_SESSION ['login_referer']}');  window.close();</script> ";
						}
                        
                    } catch(FacebookApiException $e) {
                        $_SESSION['error'] = "Error({$e->getType()}): {$e->getMessage()}";
                        //header("Location: /login#error");
                        echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
                    }
                } else {
                	$_SESSION['error'] = "Connection to Facebook failed.";
                    //header("Location: /login#error");
                	echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
                }
		exit;
}else{
	die('');
}