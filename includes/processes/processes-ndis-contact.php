<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['name']) && !empty($_POST['plan_type'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
    $error = '';
    $sent = 0;
    $contact_email = "";
   
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
        
        $body = $buf;
        $subject = 'Website ' . $_POST['form_name'];
        $fromEmail = (string)$CONFIG->company->email_from;
        if($contact_email){
          $to = $contact_email;
        }else{
          $to = (string)$CONFIG->company->email_contact;
        }
        $COMP = json_encode($CONFIG->company);
        $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
        $from = (string)$CONFIG->company->name;
        $bcc = null;       
        $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);       
      }
      catch(Exception $e){
        $error = 'There was an error sending your enquiry.';
      }
    }
    
    // SAVE IN DATABASE
    if(empty($error)){

      try{
        $sql = "INSERT INTO tbl_ndis (
            ndis_name, 
            ndis_email, 
            ndis_phone, 
            ndis_form_name,
            ndis_plan_no, 
            ndis_plan_type, 
            ndis_plan_manager, 
            ndis_maf_member, 
            ndis_maf_no,              
            ndis_ip, 
            ndis_browser, 
            ndis_ga_clientid, 
            ndis_email_id, 
            ndis_created
            )
            VALUES (
            :ndis_name, 
            :ndis_email, 
            :ndis_phone, 
            :ndis_form_name,
            :ndis_plan_no, 
            :ndis_plan_type, 
            :ndis_plan_manager, 
            :ndis_maf_member, 
            :ndis_maf_no,             
            :ndis_ip, 
            :ndis_browser, 
            :ndis_ga_clientid, 
            :ndis_email_id,
            now()
            )";

        $params = array(
            ":ndis_name" => $_POST['name'], 
            ":ndis_email" => $_POST['email'], 
            ":ndis_phone" => $_POST['phone'], 
            ":ndis_form_name" => $_POST['form_name'],             
            ":ndis_plan_no" => $_POST['plan_no'], 
            ":ndis_plan_type" => $_POST['plan_type'],             
            ":ndis_plan_manager" => $_POST['plan_manager'],
            ":ndis_maf_member" => $_POST['maf_member'],
            ":ndis_maf_no" => $_POST['maf_no'],
            ":ndis_ip" => $_SERVER['REMOTE_ADDR'], 
            ":ndis_browser" => $_SERVER['HTTP_USER_AGENT'], 
            ":ndis_ga_clientid" => gaParseCookie(),
            ":ndis_email_id" => $sent 
        );
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        $error = 'There was an unexpected error saving your enquiry.' . $e;
      }
    }
    
    if(empty($error)){      
       try{
         $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
         $body = $SMARTY->fetch("email-thank-you.tpl");
         $subject = 'Thank you for your enquiry';
         $fromEmail = (string) $CONFIG->company->email_from;
         $to = $_POST['email'];
         $COMP = json_encode($CONFIG->company);
         $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
         $from = (string) $CONFIG->company->name;
         $sent = sendMail($to, $from, $fromEmail, $subject, $body);
       }catch (Exception $e){
        $error = 'There was an error sending the contact email.';
       }      
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
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