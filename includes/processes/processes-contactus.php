<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['name']) && !empty($_POST['enquiry']) && !empty($_POST['postcode']) && is_numeric($_POST['postcode'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
    
    $error = '';
    $sent = 0;
    $file_name = '';
    
    $contact_email = "";
    if($_POST['nature_enquiry']){
      $sql = "SELECT `reason_name`,`reason_email` FROM `tbl_reason` WHERE `reason_id`= :reason_id AND `reason_deleted` IS NULL";
      $res = $DBobject->wrappedSql($sql, array("reason_id"=>$_POST['nature_enquiry']));
      $nature_enquiry_id = $_POST['nature_enquiry'];
      $nature_enquiry = $res[0]['reason_name'];
      $contact_email = $res[0]['reason_email'];
    }
    
    // SEND EMAIL TO ADMIN
    if(empty($error)){
      try{
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
        if(isset($_POST['nature_enquiry'])){
          $_POST['nature_enquiry'] = $nature_enquiry;
        }
        foreach($_POST as $name => $var){
          if(!in_array($name, $banned)){
            $name = (strtolower($_POST['form_name']) == 'share your story' && $name == 'enquiry') ? 'Story' : $name;
            $buf .= '<br/><b>' . ucwords(str_replace('_', ' ', $name)) . ': </b> <br/> ' . $var . '<br/>';
          }
        }
        $body = $buf;
        $subject = 'Website ' . $_POST['form_name'];
        $fromEmail = (string)$CONFIG->company->email_from;
        if($contact_email){
          $to = $contact_email;
        }else{
          $to = (string)$CONFIG->company->email_contact;
        }

        if(APP_ENV==='development') $to=getenv('EMAIL_APP_SUPPORT');

        $COMP = json_encode($CONFIG->company);
        $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
        $from = (string)$CONFIG->company->name;
        $bcc = null;
        if(!empty($file_name)){
          if(sendAttachMail($to, $from, $fromEmail, $subject, $body, $bcc, $file_name)){
            $sent = 1;
          }
        } else{
          $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
        }
      }
      catch(Exception $e){
        $error = 'There was an error sending your enquiry.';
      }
    }
    
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_contact (contact_site,contact_form_name,contact_reference_id,contact_reference_name,contact_name,contact_email,
    		    contact_phone,contact_postcode,contact_file,contact_enquiry,contact_content1,contact_content2,contact_flag1,contact_flag2,contact_ip,
    		    contact_email_id, contact_browser, contact_ga_clientid, contact_created)
              VALUES (:contact_site,:contact_form_name,:contact_reference_id,:contact_reference_name,:contact_name,:contact_email,:contact_phone,
    		    :contact_postcode,:contact_file,:contact_enquiry,:contact_content1,:contact_content2,:contact_flag1,:contact_flag2,:contact_ip,
    		    :contact_email_id, :contact_browser, :contact_ga_clientid, now() )";
        $params = array(
            ":contact_name" => $_POST['name'], 
            ":contact_site" => $SITE, 
            ":contact_form_name" => $_POST['form_name'], 
            ":contact_reference_id" => ($nature_enquiry)?$nature_enquiry_id:0, 
            ":contact_reference_name" => ($nature_enquiry)?$nature_enquiry:$_POST['nature_enquiry'], 
            ":contact_name" => $_POST['name'], 
            ":contact_email" => $_POST['email'], 
            ":contact_phone" => $_POST['phone'], 
            ":contact_postcode" => $_POST['postcode'], 
            ":contact_file" => empty($file_short)? "" : "/uploads_contact/" . $file_short, 
            ":contact_enquiry" => $_POST['enquiry'], 
            ":contact_content1" => $_POST['membership_no'], 
            ":contact_content2" => '', 
            ":contact_flag1" => '', 
            ":contact_flag2" => '', 
            ":contact_ip" => $_SERVER['REMOTE_ADDR'], 
            ":contact_browser" => $_SERVER['HTTP_USER_AGENT'], 
            ":contact_ga_clientid" => gaParseCookie(),
            ":contact_email_id" => $sent 
        );
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        $error = 'There was an unexpected error saving your enquiry.';
      }
    }
    
    if(empty($error)){
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
      }
      
      if(strtolower($_POST['form_name']) == "help"){
        header("Location: /thank-you-for-your-enquiry");
        die();
      }
      
      if(strtolower($_POST['form_name']) == "share your story"){
        header("Location: /thank-you-for-sharing-your-story");
        die();
      }
      
      header("Location: /thank-you");
      die();
    }
  } else{
    $error = 'Please provide the required information.';
  }
} else{
  $error = 'Your session has expired.<br>Please try again.';
}

$_SESSION['post'] = $_POST;
$_SESSION['error'] = $error;
header("Location: {$_SERVER['HTTP_REFERER']}#form-error");
die();
