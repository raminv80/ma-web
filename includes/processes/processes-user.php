<?php
if(checkToken('frontend',$_POST["formToken"],false)){
  switch($_POST["action"]){
    case 'create':
      $user_obj = new UserClass();
      $_POST['username'] = $_POST['email'];
      $res = $user_obj->Create($_POST);
      if($res['error']){
        echo json_encode(array(
            'error'=>$res['error'],
            'url'=>null
        ));
      }else{
        $cart_obj = new cart();
        $cart_obj->SetUserCart($res['id']);
        $_SESSION['user']['public'] = $res;
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        
        try{
        	// SEND CONFIRMATION EMAIL
        	$SMARTY->assign("domainName",'http://'.$GLOBALS['HTTP_HOST']);
        	$SMARTY->assign("username",$_POST['email']);
        	$SMARTY->assign("password",$_POST['password']);
        	
        	$buffer= $SMARTY->fetch('newmember-email.tpl');
        	$to = $_SESSION['user']['public']['email'];
        	$from = 'Website';
        	$fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
        	$subject = 'Your new account details';
        	$body = $buffer;
        	$mailID = sendMail($to, $from, $fromEmail, $subject, $body);
        	
        }catch(Exception $e){
        	echo json_encode(array(
        			'error'=>null,
        			'emailerror'=>$e
        	));
        
        }
        
        echo json_encode(array(
            'error'=>null,
            'url'=>$url,
            'username'=>$_SESSION['user']['public']['email']
        ));
      }
      die();
    
    case 'login':
	    	$user_obj = new UserClass();	
	    	 $res = $user_obj->Authenticate($_POST["email"], $_POST["pass"]); 
	    	 if ( $res['error'] ) {
	    	 	echo json_encode(array(
	    	 			'error' => $res['error'],
	    	 			'url'=> null
	    	 	));
	    	} else {
	    		$cart_obj = new cart();
	    		$cart_obj->SetUserCart($res['id']);
	    		$_SESSION['user']['public'] = $res;
	    		$url = $_SERVER['HTTP_REFERER'];
	    		if ($_POST['redirect']) {
                	$url = $_POST['redirect'];
                }
				echo json_encode(array(
						'error' => null,
						'url'=> $url
				));
	    	}
	    	die();
    
    case 'resetPassword':
      $user_obj = new UserClass();
      $res = $user_obj->resetPassword($_POST["email"]);
      echo json_encode(array(
          'error'=>$res['error'],
          'success'=>$res['success']
      ));
      die();
    
    case 'updatePassword':
      $user_obj = new UserClass();
      $data = array_merge($_POST,array(
          'email'=>$_SESSION['user']['public']['email']
      ));
      $res = $user_obj->UpdatePassword($data);
      if($res['error']){
        $_SESSION['error'] = $res['error'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
      }else{
        $_SESSION['notice'] = $res['success'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#notice");
      }
      exit();
    
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
    			$_SESSION['notice']= $res['success'];  
    			header("Location: ".$_SERVER['HTTP_REFERER']."#notice");
    		}
    		exit;

    case 'FBlogin':
      $facebook = new Facebook(array(
          'appId'=>'208591239336752',
          'secret'=>'21ffc84783a7275f8cb9a43c49e00d78'
      ));
      $user_id = $facebook->getUser();
      if($user_id){
        try{
          $profile = $facebook->api('/me','GET');
          
          $user_obj = new UserClass();
          $res = $user_obj->AuthenticateFacebook($profile);
          if($res['error']){
            $_SESSION['error'] = $res['error'];
            header("Location: /login#error");
          }else{
            $cart_obj = new cart();
            $cart_obj->SetUserCart($res['id']);
            $_SESSION['user']['public'] = $res;
            echo json_encode(array(
                "error"=>false,
                "login_url"=>null,
                'new_window'=>null,
                "message"=>$_SESSION['error']
            ));
          }
        }catch(FacebookApiException $e){
          echo json_encode(array(
              "error"=>true,
              "login_url"=>null,
              'new_window'=>null,
              "message"=>"Error({$e->getType()}): {$e->getMessage()}"
          ));
        }
      }else{
        if(isMobile()){
          $optionsFB = array(
              'scope'=>'email, user_birthday, user_location'
          );
          $newWindow = false;
        }else{
          $optionsFB = array(
              'display'=>'popup',
              'scope'=>'email, user_birthday, user_location'
          );
          $newWindow = true;
        }
        $login_url = $facebook->getLoginUrl($optionsFB);
        echo json_encode(array(
            "error"=>false,
            "login_url"=>$login_url,
            'new_window'=>$newWindow,
            "message"=>null
        ));
      }
      $_SESSION['fb_referer'] = $_SERVER['HTTP_REFERER'];
      if($_POST['redirect']){
        $_SESSION['fb_referer'] = $_POST['redirect'];
      }
      exit();
  }
}elseif($_GET["logout"]){
  if($_SESSION['user']['public']['social_id']){
    $facebook = new Facebook(array(
        'appId'=>'208591239336752',
        'secret'=>'21ffc84783a7275f8cb9a43c49e00d78'
    ));
    $user_id = $facebook->getUser();
    
    $logout_url = $facebook->getLogoutUrl(array(
        'next'=>$_SERVER['HTTP_REFERER']
    ));
    unset($_SESSION['fb_208591239336752_code']);
    unset($_SESSION['fb_208591239336752_access_token']);
    unset($_SESSION['fb_208591239336752_user_id']);
  }else{
    $logout_url = $_SERVER['HTTP_REFERER'];
  }
  
  unset($_SESSION['user']['public']);
  unset($_SESSION['address']);
  session_regenerate_id();
  header('Location: ' . $logout_url);
  
  exit();
}elseif($_GET["code"]){ // FACEBOOK AUTH 2ND PART
  $facebook = new Facebook(array(
      'appId'=>'208591239336752',
      'secret'=>'21ffc84783a7275f8cb9a43c49e00d78',
      'allowSignedRequest'=>false
  ));
  $user_id = $facebook->getUser();
  
  if(isMobile()){
    $redirectThis = true;
  }else{
    $redirectThis = false;
  }
  if($user_id){
    try{
      $profile = $facebook->api('/me','GET');
      $user_obj = new UserClass();
      $res = $user_obj->AuthenticateFacebook($profile);
      if($res['error']){
        $_SESSION['error'] = $res['error'];
        if($redirectThis){
          header("Location: {$_SESSION['fb_referer']}#error");
        }else{
          echo "<script> window.opener.redirectWin('{$_SESSION['fb_referer']}#error');  window.close();</script> ";
        }
      }else{
        $cart_obj = new cart();
        $cart_obj->SetUserCart($res['id']);
        $_SESSION['user']['public'] = $res;
        if($redirectThis){
          header("Location: " . $_SESSION['fb_referer']);
        }else{
          echo "<script> window.opener.redirectWin('{$_SESSION['fb_referer']}');  window.close();</script> ";
        }
      }
    }catch(FacebookApiException $e){
      $_SESSION['error'] = "Error({$e->getType()}): {$e->getMessage()}";
      if($redirectThis){
        header("Location: {$_SESSION['fb_referer']}#error");
      }else{
        echo "<script> window.opener.redirectWin('{$_SESSION['fb_referer']}#error');  window.close();</script> ";
      }
    }
  }else{
    $_SESSION['error'] = "Connection to Facebook failed.";
    if($redirectThis){
      header("Location: {$_SESSION['fb_referer']}#error");
    }else{
      echo "<script> window.opener.redirectWin('{$_SESSION['fb_referer']}#error');  window.close();</script> ";
    }
  }
  exit();
}else{
  header("Location: /404");
  exit();
}