<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "includes/functions/functions.php";
include_once 'includes/social/facebook/facebook.php';

$facebookParams = array(
		'appId'=>'208591239336752',
		'secret'=>'21ffc84783a7275f8cb9a43c49e00d78',
		'allowSignedRequest'=>false
);

if($_GET["code"]){ // FACEBOOK AUTH 2ND PART  -- NOTE: DUE TO A FACEBOOK USER LOGIN ISSUE, THIS SECTION BYPASSED INDEX.PHP FILE
	$facebook = new Facebook($facebookParams);
	if($facebook->getUser()){
		try{
			$profile = $facebook->api('/me','GET');
			$user_obj = new UserClass();
			$res = $user_obj->AuthenticateFacebook($profile);
			if($res['error']){
				setRedirection($res['error'], $_SESSION['fb_referer'].'#error', isMobile());
			}else{
				$cart_obj = new cart($_SESSION['user']['public']['id']);
				$cart_obj->SetUserCart($res['id']);
				$_SESSION['user']['public'] = $res;
				setRedirection('', $_SESSION['fb_referer'], isMobile());
			}
		}catch(FacebookApiException $e){
			setRedirection("Error({$e->getType()}): {$e->getMessage()}", $_SESSION['fb_referer'].'#error', isMobile());
		}
  }else{
		setRedirection("Connection to Facebook failed.", $_SESSION['fb_referer'].'#error', isMobile());
	}
	die();
}

if(checkToken('frontend',$_POST["formToken"],false)){
  switch($_POST["action"]){
    
    case 'FBlogin':
    	$facebook = new Facebook($facebookParams);
    	
    	$_SESSION['fb_referer'] = $_SERVER['HTTP_REFERER'];
    	if($_POST['redirect']){
    		$_SESSION['fb_referer'] = $_POST['redirect'];
    	}
    	$error = false;
    	$login_url = null;
    	$new_window = null;
    	$message = null;
    	
			if($facebook->getUser()){
        try{
          $profile = $facebook->api('/me','GET');
          $user_obj = new UserClass();
          $res = $user_obj->AuthenticateFacebook($profile);
          if($res['error']){
            $error = true;
            $message = $res['error'];
          }else{
            $cart_obj = new cart($_SESSION['user']['public']['id']);
            $cart_obj->SetUserCart($res['id']);
            $_SESSION['user']['public'] = $res;
            $login_url = $_SESSION['fb_referer'];
          }
        }catch(FacebookApiException $e){
        	$error = true;
        	$message = "Error({$e->getType()}): {$e->getMessage()}";
        }
      }else{
      	$new_window = false;
      	$optionsFB = array(
      			'scope'=>'email, user_birthday, user_location',
      			'redirect_uri' => 'http://'.$GLOBALS['HTTP_HOST'].'/includes/processes/processes-facebook.php'
      	);
        if(!isMobile()){
        	$new_window = true;
          $optionsFB = array_merge($optionsFB, array('display'=>'popup'));
        }
        $login_url = $facebook->getLoginUrl($optionsFB);
      }
      
      echo json_encode(array(
      		"error"=>$error,
      		"login_url"=>$login_url,
      		'new_window'=>$new_window,
      		"message"=>$message
      ));
      die();
  }
}
	
header("Location: /404");
die();
  

function setRedirection($_error, $_redirectUrl, $_redirectFlag = false){
	$_SESSION['error'] = $_error;
	if($_redirectFlag){
		header("Location: {$_redirectUrl}");
	}else{
		echo "<script> window.opener.redirectWin('{$_redirectUrl}');  window.close();</script> ";
	}
	return null;
}
  