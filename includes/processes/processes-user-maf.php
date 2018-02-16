<?php
if(!empty($_POST["formToken"]) && checkToken('frontend', $_POST["formToken"], false)){
  
  switch($_POST["action"]){
    case 'activateOnlineAccount':
      $error = "Error: Missing parameters.";
      $success = null;

      if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['membership_no']) && is_numeric($_POST['phone']) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
        try{
          $error = null;
          $SMARTY->unloadFilter('output', 'trimwhitespace');
          $banned = array(
              'formToken',
              'action',
              'additional',
              'wantpromo',
              'enqsub',
              'Hp',
              'timestamp',
              'honeypot'
          );
          $content = serialize($_POST);
          $buf .= '<h2>Website ' . $_POST['form_name'] . '</h2>';
          foreach($_POST as $name => $var){
            if(!in_array($name, $banned)){
              $buf .= '<br/><b>' . ucwords(str_replace('_', ' ', $name)) . ': </b> <br/> ' . $var . '<br/>';
            }
          }
          $body = $buf;
          $subject = 'Website ' . $_POST['form_name'];
          $fromEmail = (string)$CONFIG->company->email_from;
          $to = (string)$CONFIG->company->email_contact;
          $COMP = json_encode($CONFIG->company);
          $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
          $from = (string)$CONFIG->company->name;
          $bcc = null;
          $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
          
        }
        catch(Exception $e){
          $error = 'There was an error sending your request.';
        }
        if(empty($error)){
          try {
            $sql = "INSERT INTO tbl_contact (contact_site,contact_form_name,contact_reference_id,contact_reference_name,contact_name,contact_email,
    		    contact_phone,contact_postcode,contact_file,contact_enquiry,contact_content1,contact_content2,contact_flag1,contact_flag2,contact_ip,
    		    contact_email_id,contact_created)
              VALUES (:contact_site,:contact_form_name,:contact_reference_id,:contact_reference_name,:contact_name,:contact_email,:contact_phone,
    		    :contact_postcode,:contact_file,:contact_enquiry,:contact_content1,:contact_content2,:contact_flag1,:contact_flag2,:contact_ip,
    		    :contact_email_id,now() )";
            $params = array(
                ":contact_name" => $_POST['name'],
                ":contact_site" => $SITE,
                ":contact_form_name" => $_POST['form_name'],
                ":contact_reference_id" => 0,
                ":contact_reference_name" => '',
                ":contact_email" => $_POST['email'],
                ":contact_phone" => $_POST['phone'],
                ":contact_postcode" => '',
                ":contact_file" => '',
                ":contact_enquiry" => '',
                ":contact_content1" => $_POST['membership_no'],
                ":contact_content2" => '',
                ":contact_flag1" => '',
                ":contact_flag2" => '',
                ":contact_ip" => $_SERVER['REMOTE_ADDR'],
                ":contact_email_id" => $sent
            );
            $DBobject->wrappedSql($sql, $params);
          }catch(Exception $e){
            $error = 'There was an unexpected error saving your request.';
          }
          if(empty($error)){
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
            }
            $success = 'Your online account activation request was successfully sent.<br>You will be contacted shortly by our Membership Services team.';
          }
        }
        
      }
      echo json_encode(array(
          'error' => $error,
          'success' => $success
      ));
      die();
      
    case 'createTemporaryMember':
      $error = "Error: Missing parameters.";
      $success = null;
      $url = null;
      $_SESSION['user']['new_user'] = '';
      if(!empty($_POST['gname']) && !empty($_POST['surname']) && !empty($_POST['dob']) && !empty($_POST['gender']) 
          && !empty($_POST['address']) && !empty($_POST['suburb']) && !empty($_POST['state']) && !empty($_POST['postcode']) && !empty($_POST['mobile']) && !empty($_POST['email']) && !empty($_POST['password'])){
        
        $user_obj = new UserClass();
        $error = "Error: Invalid date of birth (DD/MM/YYYY).";
        if(validateDate($_POST['dob'])){
          $dobDB = date_format(date_create_from_format('d/m/Y', $_POST['dob']), 'Y-m-d');
          if($memberId = $user_obj->profileMatch($_POST['gname'], $_POST['surname'], $dobDB)){
            //Already has an account
            $error = 'Our records indicate that you may already be a member of MedicAlert Foundation.<br>Please <a href="/contact-us">contact us</a> for more information.';
            try{
              //Send notification
              $subject = 'Notice - Member registration match';
              $fromEmail = (string) $CONFIG->company->email_from;
              $to = (string) $CONFIG->company->email_notice;
              $from = (string) $CONFIG->company->name;
              $body = "A member match was found when <b>{$_POST['gname']} {$_POST['surname']}</b> attempted to register on the website.<br/>";
              $body .= "Member record <b>{$memberId}</b> was found as the matching result. <br/>";
              $body .= "DOB: {$dobDB}<br/>Phone: {$_POST['mobile']}<br/>Email: {$_POST['email']}";
              $sent = sendMail($to, $from, $fromEmail, $subject, $body);
            }catch (Exception $e){}
            
          }else{
            //New member
            $excludeArr = array('formToken', 'action', 'redirect');
            foreach($_POST as $k => $v){
              if(!in_array($k, $excludeArr)){
                $_SESSION['user']['new_user'][$k] = $v;
              }
            }
            $_SESSION['user']['new_user']['db_dob'] = $dobDB;
            
            try{
              $cart_obj = new cart($_SESSION['user']['public']['id']);
              if($user_obj->CreateUserTemp($_SESSION['user']['new_user'], $cart_obj->cart_id)){
                $error = null;
                $success = true;
                $url = empty($_POST['redirect']) ? '/checkout' : $_POST['redirect'];
                if(!empty($GA_ID)){
                  sendGAEvent($GA_ID, 'user', 'pre-register', '0');
                  
                  $productsGA = $cart_obj->getCartitemsByCartId_GA();
                  sendGAEnEcCheckoutStep($GA_ID, '3', 'Billing and shipping', $productsGA);
                }
              }
            }catch (Exception $e){
              $error = 'Database error. Please try again';
            }
          }
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
            $_SESSION['user']['new_user'] = null;
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'user', 'login', $_SESSION['user']['public']['id']);
              $cart_obj = new cart($_SESSION['user']['public']['id']);
              $productsGA = $cart_obj->getCartitemsByCartId_GA();
              sendGAEnEcCheckoutStep($GA_ID, '3', 'Billing and shipping', $productsGA);
            }
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
          $url = '/quick-checkout';
          
        }else{
          $_SESSION['user']['public']['pending_update'] = null;
          $detailsArr = $_POST;
          //Pre-process array fields
          if(!empty($detailsArr['conditions'])){
            $detailsArr['conditions'] = $user_obj->formatProfileArrayField($detailsArr['conditions'], $_SESSION['user']['public']['maf']['update']['conditions']);
          }
          if(!empty($detailsArr['allergies'])){
            $detailsArr['allergies'] = $user_obj->formatProfileArrayField($detailsArr['allergies'], $_SESSION['user']['public']['maf']['update']['allergies']);
          }
          if(!empty($detailsArr['medications'])){
            $detailsArr['medications'] = $user_obj->formatProfileArrayField($detailsArr['medications'], $_SESSION['user']['public']['maf']['update']['medications']);
          }
          
          //Update profile
          if($user_obj->processUpdate($_SESSION['user']['public']['maf']['token'], $detailsArr)){
            saveInLog('member-profile-update', 'external', $_SESSION['user']['public']['id']);
            $error = null;
            $success = 'Your details were successfully submitted.';
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'user', 'profile-update', $_SESSION['user']['public']['id']);
            }
            try{
              //Send notification
              $SMARTY->unloadFilter('output', 'trimwhitespace');
              $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
              $subject = 'Update Member Profile';
              $fromEmail = (string) $CONFIG->company->email_from;
              $to = $detailsArr['user_email'];
              $SMARTY->assign('user_name', $detailsArr['user_firstname']);
              $COMP = json_encode($CONFIG->company);
              $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
              $from = (string) $CONFIG->company->name;
              $body = $SMARTY->fetch("email/profile-update.tpl");
              $sent = sendMail($to, $from, $fromEmail, $subject, $body);
            }catch(Exception $e){}
            
            try{
              //Create survey
              require_once 'includes/classes/survey-class.php';
              $surveyObj = new Survey();
              $surveyObj->CreateSurvey($_SESSION['user']['public']['id'], $_SESSION['user']['public']['email'], 2);
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
   
      
    case 'forgotPassword':
      $user_obj = new UserClass();
      $success = null;
      $error = 'Undefined error. Please <a href="/contact-us">contact us</a>.';
      if(!empty($_POST['username']) && !empty($_POST['email'])){
        $error = 'Undefined error2';
        try{
          if($user_obj->ForgotPassword($_POST['username'], $_POST['email'])){
            $success = 'An email to reset your password has successfully been sent.';
            $error = null;
            saveInLog('forgot-password', 'external', $_POST['username'], $_POST['email']);
            if(!empty($GA_ID)){
              sendGAEvent($GA_ID, 'user', 'forgot-password', $_POST['username'], $_POST['email']);
            }
          }else{
            $error = $user_obj->getErrorMsg();
          }
        }catch(Exception $e){
          $error = $e;
        }
      }
      echo json_encode(array(
          'error' => $error, 
          'success' => $success
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
              if(!empty($GA_ID)){
                sendGAEvent($GA_ID, 'user', 'password-update', $_SESSION['user']['public']['id']);
              }
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
    
    
 
  }
}
$redirect = $_SERVER['HTTP_REFERER'];

if($_GET["logout"]){
  $user_obj = new UserClass();
  saveInLog('member-logout', 'external', $_SESSION['user']['public']['id'], $_SESSION['user']['public']['maf']['token']);
  if(!empty($GA_ID)){
    sendGAEvent($GA_ID, 'user', 'logout', $_SESSION['user']['public']['id']);
  }
  $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
  $_SESSION['user']['public'] = null;
  $_SESSION['user']['new_user'] = null;
  $_SESSION['address'] = null;
  session_regenerate_id();
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}

if(!empty($_GET["action"] == 'getfile') && !empty($_GET["fid"]) && is_numeric($_GET["fid"])){
  $user_obj = new UserClass();
  if($fileArr = $user_obj->getMembersFile($_SESSION['user']['public']['maf']['token'], $_GET['fid'])){
    saveInLog('member-file-download', 'external', $_GET['fid'], $_SESSION['user']['public']['id']);
    if(!empty($GA_ID)){
      sendGAEvent($GA_ID, 'user', 'member-file-download', $_SESSION['user']['public']['id']);
    }
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
