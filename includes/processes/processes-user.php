<?php

if(checkToken('frontend',$_POST["formToken"], true)){
	
	switch ($_POST["action"]) {
		
		case 'create':
			$user_obj = new UserClass();
			$_POST['username'] = $_POST['email'];
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
                                $_SESSION['user']['public'] = $res;
				header("Location: " . $_SESSION ['login_referer']);
			}
			exit;

		case 'guest':
			$user_obj = new UserClass();
			$values = array();
			$values = $_POST;
			$values['username'] = $_POST['email'] . '#' . strtotime("now");
			$values['password'] = session_id ();
			$values['gname'] = 'Guest';
			$values['surname'] = '';
			$res = $user_obj->Create($values);
				
			if( $res['error'] ) {
				$_SESSION['error']= $res['error'];
				$_SESSION['post']= $_POST;
				header("Location: ".$_SERVER['HTTP_REFERER']."#error");
			} else {
				$cart_obj = new cart();
				$cart_obj->SetUserCart($res['id']);
				$_SESSION['user']['public'] = $res;
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
	    		$_SESSION['user']['public'] = $res;
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
                $data = array_merge($_POST, array('email'=>$_SESSION['user']['public']['email']));
	    	 $res = $user_obj->UpdatePassword($data); 
	    	 if ( $res['error'] ) {
	    	 	$_SESSION['error']= $res['error'];
	    	 	header("Location: ".$_SERVER['HTTP_REFERER']."#error");
	    	} else {
	    		$_SESSION['error']= $res['success'];  //<<<<<<<<<<<<<<<<<<< CHANGE THIS TO NOTIFICATION VARIABLE, not error!
	    		header("Location: ".$_SERVER['HTTP_REFERER']."#error");//<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>
	    	}
	    	exit;

    	case 'updateDetails':
    		$user_obj = new UserClass();
    		$data = array_merge($_POST, array('email'=>$_SESSION['user']['public']['email']));
    		$res = $user_obj->UpdateDetails($data);
    		if ( $res['error'] ) {
    			$_SESSION['error']= $res['error'];
    			header("Location: ".$_SERVER['HTTP_REFERER']."#error");
    		} else {
    			$_SESSION['user']['public']['gname']= $_POST["gname"];
    			$_SESSION['user']['public']['surname']= $_POST["surname"];
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
                            $_SESSION['user']['public'] = $res;
							//header("Location: " . $_SESSION ['login_referer']);
							echo json_encode(array(
									"error" => false,
									"login_url" => null,
									'new_window' => null,
									"message" => $_SESSION['error']
							));
						}
                    } catch(FacebookApiException $e) {
                        //$_SESSION['error'] = "Error({$e->getType()}): {$e->getMessage()}";
                        //header("Location: /login#error");
                        echo json_encode(array(
                        		"error" => true,
								"login_url" => null,
                        		'new_window' => null,
                        		"message" => "Error({$e->getType()}): {$e->getMessage()}"
                        ));
                    }
                } else {
                	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4))){
                		$optionsFB = array( 
                    			'scope' => 'email, user_birthday, user_location' 
                    	);
                		$newWindow = false;
                	}else{
                		$optionsFB = array(
                				'display' => 'popup',
                				'scope' => 'email, user_birthday, user_location'
                		);
                		$newWindow = true;
                	}
                    $login_url = $facebook->getLoginUrl($optionsFB);
                    //header("Location: " .$login_url);
                    echo json_encode(array(
                        	"error" => false,
							"login_url" => $login_url,
                    		'new_window' => $newWindow,
                        	"message" => null
                    ));
                }			
    		exit;
    		
     
	}
}elseif ($_GET["logout"]) {
                if ($_SESSION['user']['public']['social_id']) {
                    $facebook = new Facebook(array(
                            'appId'  => '208591239336752',
                            'secret' => '21ffc84783a7275f8cb9a43c49e00d78'
                    ));
                    $user_id = $facebook->getUser();

                    $logout_url = $facebook->getLogoutUrl(array( 'next' => $_SERVER['HTTP_REFERER'] )); 
                    unset ( $_SESSION['fb_208591239336752_code'] );
                    unset ( $_SESSION['fb_208591239336752_access_token'] );
                    unset ( $_SESSION['fb_208591239336752_user_id'] );
                    
                } else {
                    $logout_url = $_SERVER['HTTP_REFERER'];
                }
    	
				unset ( $_SESSION['user']['public'] );
                session_regenerate_id();
                header('Location: ' . $logout_url );
                
		exit;
}elseif ($_GET["code"]) {// FACEBOOK AUTH 2ND PART
		$facebook = new Facebook(array(
                        'appId'  => '208591239336752',
                        'secret' => '21ffc84783a7275f8cb9a43c49e00d78',
                        'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
                ));
                $user_id = $facebook->getUser();
                
                if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4))){
                	$redirectThis = true;
                }else{
                	$redirectThis = false;
                }
                if($user_id) {
                    try {
                        $profile  = $facebook->api('/me','GET');
                        $user_obj = new UserClass();
                        $res = $user_obj->AuthenticateFacebook($profile);
                        if( $res['error'] ) {
							$_SESSION['error']= $res['error'];
							if ($redirectThis) {
								header("Location: /login#error");
							} else {
	                        	echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
							}
						} else {
							$cart_obj = new cart();
			                                $cart_obj->SetUserCart($res['id']);
			                                $_SESSION['user']['public'] = $res;
			                if ($redirectThis) {
			                	header("Location: " . $_SESSION ['login_referer']);
			                } else {
								echo "<script> window.opener.redirectWin('{$_SESSION ['login_referer']}');  window.close();</script> ";
			                }
						}
                        
                    } catch(FacebookApiException $e) {
                        $_SESSION['error'] = "Error({$e->getType()}): {$e->getMessage()}";
                        if ($redirectThis) {
                        	header("Location: /login#error");
                        } else {
                        	echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
                        }
                    }
                } else {
                	$_SESSION['error'] = "Connection to Facebook failed.";
                	if ($redirectThis) {
                		header("Location: /login#error");
                	} else {
                		echo "<script> window.opener.redirectWin('http://{$_SERVER['HTTP_HOST']}/login#error');  window.close();</script> ";
                	}
                }
		exit;
}else{
	header("Location: /404");
	exit;
}