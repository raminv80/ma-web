<?php
if(!empty($_POST["formToken"]) && checkToken('frontend', $_POST["formToken"], false)){
  
  /**
   * ************ SET CAMPAIGN MONITOR LIST ID WHEN NEEDED ********
   */
  $LIST_ID = '';
  /**
   * ************ **************************************** ********
   */
  
  switch($_POST["action"]){
    case 'createTemporaryMember':
      $error = "Error: Missing parameters.";
      $success = null;
      $url = null;
      $_SESSION['user']['new_user'] = '';
      if(!empty($_POST['gname']) && !empty($_POST['surname']) && !empty($_POST['dob']) && !empty($_POST['gender']) 
          && !empty($_POST['address']) && !empty($_POST['suburb']) && !empty($_POST['state']) && !empty($_POST['postcode']) && !empty($_POST['mobile']) && !empty($_POST['email']) && !empty($_POST['password'])){
        
        $error = "Error: Invalid date of birth (DD/MM/YYYY).";
        if(validateDate($_POST['dob'])){
          $excludeArr = array('formToken', 'action', 'redirect');
          foreach($_POST as $k => $v){
            if(!in_array($k, $excludeArr)){
              $_SESSION['user']['new_user'][$k] = $v;
            }
          }
          $_SESSION['user']['new_user']['db_dob'] = date_format(date_create_from_format('d/m/Y', $_SESSION['user']['new_user']['dob']), 'Y-m-d');
          $error = null;
          $success = true;
          $url = empty($_POST['redirect']) ? '/checkout' : $_POST['redirect'];
        }
      }
      echo json_encode(array(
          'error' => $error,
          'url' => $url,
          'success' => $success
      ));
      die();
      
    case 'login':
      $error = "Error: Missing parameters.";
      $success = null;
      $url = null;
      if(!empty($_POST['username']) && !empty($_POST['pass'])){
        $user_obj = new UserClass();
        if($user_obj->authenticate($_POST['username'], $_POST['pass'])){
          $error = "Error: no data.";
          if($_SESSION['user']['public']['maf'] = $user_obj->getSessionVars()){
            $_SESSION['user']['public']['id'] = $_SESSION['user']['public']['maf']['main']['user_id'];
            $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
            $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
            $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
            $error = null;
            $success = true;
            $url = empty($_POST['redirect']) ? $_SERVER['HTTP_REFERER'] : $_POST['redirect'];
            saveInLog('member-login', 'external', $_SESSION['user']['public']['id']);
          }
        }else{
          $error = $user_obj->getErrorMsg();
        }
      }
      echo json_encode(array(
          'error' => $error,
          'url' => $url,
          'success' => $success
      ));
      die();
      
      
    case 'update-profile':
      $error = "Error(1): Your session has expired.";
      $success = null;
      $url = null;
      $user_obj = new UserClass();
      if($loginCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token'])){
        
        if($user_obj->isLifetimeMember($_SESSION['user']['public']['maf']['main']['user_membershipType']) || 
            (!$user_obj->isPendingMember($_SESSION['user']['public']['maf']['main']['user_status_db']) 
                && $user_obj->isAnnualMember($_SESSION['user']['public']['maf']['main']['user_membershipType']) 
                && ($user_obj->isUnfinancialMember($_SESSION['user']['public']['maf']['main']['user_status_db']) 
                    || $user_obj->isPastRenewalMonth($_SESSION['user']['public']['maf']['main']['user_RenewalDate']) >= 1)) ){
          $_SESSION['user']['public']['pending_update'] = $_POST;
          $url = '/shopping-cart';
          
        }else{
          $_SESSION['user']['public']['pending_update'] = null;
          
          //Pre-process array fields
          if(!empty($_POST['conditions'])){
            $_POST['conditions'] = $user_obj->formatProfileArrayField($_POST['conditions'], $_SESSION['user']['public']['maf']['update']['conditions']);
          }
          if(!empty($_POST['allergies'])){
            $_POST['allergies'] = $user_obj->formatProfileArrayField($_POST['allergies'], $_SESSION['user']['public']['maf']['update']['allergies']);
          }
          if(!empty($_POST['medications'])){
            $_POST['medications'] = $user_obj->formatProfileArrayField($_POST['medications'], $_SESSION['user']['public']['maf']['update']['medications']);
          }
          
          //Update profile
          if($user_obj->processUpdate($_SESSION['user']['public']['maf']['token'], $_POST)){
            saveInLog('member-profile-update', 'external', $_SESSION['user']['public']['id']);
            $error = null;
            $success = 'Your details were successfully submitted.';
            try {
              //Send notification
              $SMARTY->unloadFilter('output', 'trimwhitespace');
              $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
              $subject = 'Update Member Profile';
              $fromEmail = (string) $CONFIG->company->email_from;
              $to = $_POST['user_email'];
              $SMARTY->assign('user_name', $_POST['user_firstname']);
              $COMP = json_encode($CONFIG->company);
              $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
              $from = (string) $CONFIG->company->name;
              $body = $SMARTY->fetch("email/profile-update.tpl");
              $sent = sendMail($to, $from, $fromEmail, $subject, $body);
            }catch(Exception $e){}
            
          }else{
            $error = $user_obj->getErrorMsg();
          }
        }
      }else{
        $_SESSION['user']['public'] = null;
      }
      echo json_encode(array(
          'error' => $error,
          'success' => $success,
          'url' => $url
      ));
      die();
   
      
    case 'print-profile':
      $error = "Error(2): Your session has expired.";
      $template = null;
      $user_obj = new UserClass();
      if($loginCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token'])){
        $template = $user_obj->printProfile("http://{$_SERVER['HTTP_HOST']}/includes/print", $_SESSION['user']['public']['maf']['main'], $_SESSION['user']['public']['maf']['update']);
        saveInLog('member-print-profile', 'external', $_SESSION['user']['public']['id']);
        $error = null;
      }else{
        $_SESSION['user']['public'] = null;
      }
      echo json_encode(array(
          'error' => $error,
          'template' => $template
      ));
      die();
      
    case 'resetPasswordToken':
      $member = new UserClass();
      $success = null;
      $error = 'Undefined error. Please <a href="/contact-us">contact us</a>.';
      try{
        $authenticationRecord = json_decode($member->medicAlertApi->authenticate(medicAlertApi::API_USER, medicAlertApi::API_USER_PASSWORD), true);
      }catch(exceptionMedicAlertApiNotAuthenticated $e){
        $_SESSION['user']['public'] = null;
        $error = "API init error: {$e}";
      }catch(exceptionMedicAlertApiSessionExpired $e){
        $_SESSION['user']['public'] = null;
        $error = "API init error: {$e}";
      }catch(exceptionMedicAlertApi $e){
        $error = "API init error: {$e}";
      }
      $sessionToken = $authenticationRecord['sessionToken'];
      try{
        $results = $member->medicAlertApi->lostPassWord($sessionToken, $_POST["membership_number"], $_POST["email"]);
        $success = "An email has been sent to your";
      }catch(exceptionMedicAlertNotFound $e){
        $error = 'The details you entered do not match any member record.';
      }
      	
      // logout of the API
      try{
        $member->medicAlertApi->logout($sessionToken);
      }catch(exceptionMedicAlertApiNotAuthenticated $e){
        $_SESSION['user']['public'] = null;
        $error = "API error: {$e}";
      }catch(exceptionMedicAlertApiSessionExpired $e){
        $_SESSION['user']['public'] = null;
        $error = "API error: {$e}";
      }catch(exceptionMedicAlertApi $e){
        $error = "API error: {$e}";
      }
      
      echo json_encode(array(
          'error' => $error, 
          'success' => $success
      ));
      die();
    
    case 'passwordreset':
      
      $user_obj = new UserClass();
      $res = $user_obj->ResetPassword($_POST["email"], $_POST['userToken'], $_POST['pass']);
      if(empty($res['error'])){
        $_SESSION['user']['public'] = $res;
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        echo json_encode(array(
            'error' => false, 
            'success' => true, 
            'url' => $url 
        ));
        die();
      }
      echo json_encode(array(
          'error' => $error, 
          'success' => false, 
          'url' => $url 
      ));
      die();
    
    case 'updatePassword':
      $error = "Your session has expired.";
      $success = null;
      $refresh = true;
      if(!empty($_SESSION['user']['public']['maf'])){
        $refresh = null;
        $error = "Error: Missing parameters.";
        if(!empty($_POST['current']) && !empty($_POST['new'])){
          try {
            $error = "Incorrect current password.";
            $user_obj = new UserClass();
            if($user_obj->UpdatePassword($_SESSION['user']['public']['maf']['token'], $_POST['current'], $_POST['new'])){
              //Password updated
              saveInLog('password-update', 'external', $_SESSION['user']['public']['id']);
              $memberId = $_SESSION['user']['public']['id'];
              //Log out
              $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
              $_SESSION['user']['public'] = null;
              //Login with new password
              if($user_obj->authenticate($memberId, $_POST['new'])){
                $error = "Error: no data.";
                if($_SESSION['user']['public']['maf'] = $user_obj->getSessionVars()){
                  $_SESSION['user']['public']['id'] = $_SESSION['user']['public']['maf']['main']['user_id'];
                  $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
                  $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
                  $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
                  $error = null;
                  $success = 'Your password has been updated.';
                }
              }else{
                $error = $user_obj->getErrorMsg();
              }
            }else{
              $error = $user_obj->getErrorMsg();
            }
          }catch(Exception $e){
            $error = $e;
          }
        }
      }
      if(!empty($error) && preg_match('/API error/', $error)){
        $_SESSION['user']['public'] = null;
        $error = 'Your session has expired.<br>This page will be reloaded in 5 secs.';
        $refresh = true;
      }
      echo json_encode(array(
          'error' => $error,
          'refresh' => $refresh,
          'success' => $success
      ));
      die();
    
    case 'unsubscribe':
      if(!empty($_REQUEST['tk']) && !empty($_REQUEST['tl'])){
        $user_obj = new UserClass();
        $email = $user_obj->UnsubscribeUser($_REQUEST['tk'], $_REQUEST['tl']);
        if($email){
          SetMemberCampaignMonitor($LIST_ID, array(
              'email' => $email 
          ), 0);
          $_SESSION['notice'] = 'You have been successfully unsubscribed.';
          header("Location: " . $_SERVER['HTTP_REFERER'] . "#notice");
          die();
        }
      }
      $_SESSION['error'] = 'Something went wrong, please check your email and try again!';
      header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
      die();
    
 
  }
}
$redirect = $_SERVER['HTTP_REFERER'];

if($_GET["logout"]){
  $user_obj = new UserClass();
  saveInLog('member-logout', 'external', $_SESSION['user']['public']['id'], $_SESSION['user']['public']['maf']['token']);
  $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
  $_SESSION['user']['public'] = null;
  session_regenerate_id();
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}

if(!empty($_GET["action"] == 'getfile') && !empty($_GET["fid"]) && is_numeric($_GET["fid"])){
  $user_obj = new UserClass();
  if($fileArr = $user_obj->getMembersFile($_SESSION['user']['public']['maf']['token'], $_GET['fid'])){
    saveInLog('member-file-download', 'external', $_GET['fid'], $_SESSION['user']['public']['id']);
    header('Content-type: '.$fileArr['fileMimeType']);
    header('Content-disposition: attachment; filename="'. str_replace(' ', '_', $fileArr['fileName']) .'"');
    $file = base64_decode($fileArr['base64Data']);
    echo $file;
  }else{
    $_SESSION['error'] = $user_obj->getErrorMsg();
    if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
    header('Location: ' . $redirect . '#error');
  }
  die();
}

$_SESSION['error'] = 'Your session has expired.';
if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
header('Location: ' . $redirect . '#error');
die();


function SetMemberCampaignMonitor($listId, $data, $flag){
  global $CONFIG;
  
  if(empty($data) || empty($listId)){
    return false;
  }
  
  $customFields = array();
  $skipArr = array(
      'email', 
      'gname', 
      'surname' 
  );
  foreach($data as $k => $v){
    if(!in_array($k, $skipArr)){
      $customFields[] = array(
          'Key' => $k, 
          'Value' => $v 
      );
    }
  }
  
  try{
    require_once 'includes/createsend/csrest_subscribers.php';
    $wrap = new CS_REST_Subscribers($listId, '060d24d9003a77b06b95e7c47691975b');
    if(empty($flag)){
      $cs_result = $wrap->unsubscribe($data['email']);
    } else{
      $cs_result = $wrap->add(array(
          'EmailAddress' => $data['email'], 
          'Name' => $data['gname'] . ' ' . $data['surname'], 
          'CustomFields' => $customFields, 
          "Resubscribe" => "true" 
      ));
    }
    if($cs_result->was_successful()){
      return true;
    }
  }
  catch(Exception $e){
    $COMP = json_encode($CONFIG->company);
    $body = "Error: {$e}<br> Session: " . print_r($_SESSION, true);
    $to = 'apolo@them.com.au';
    $from = (string)$CONFIG->company->name;
    $fromEmail = (string)$CONFIG->company->email_from;
    $subject = "{$from} | Campaign monitor error";
    sendMail($to, $from, $fromEmail, $subject, $body);
  }
  return false;
}
