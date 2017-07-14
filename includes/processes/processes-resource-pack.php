<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['postcode']) && is_numeric($_POST['postcode'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
  
    /**
     * ************ SET CAMPAIGN MONITOR LIST ID WHEN NEEDED ********
     */
    $LIST_ID = '0119c2290ed3c55b2efebe830398a56a';
    /**
     * ************ **************************************** ********
     */
    
    $error = '';
    $sent = 0;
    $file_name = '';
    
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
        /**
         * COMMENTED OUT - 04/11/2016 - REQUESTED BY MAF
         */
        /* if(!empty($file_name)){
          if(sendAttachMail($to, $from, $fromEmail, $subject, $body, $bcc, $file_name)){
            $sent = 1;
          }
        } else{
          $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
        } */
      }
      catch(Exception $e){
        $error = 'There was an error sending your enquiry.';
      }
    }
      
      $data = array_merge($_POST, array(
          'user_id' => $_SESSION['user']['public']['id']
      ));
      $promo = 0;
      $data['user_want_promo'] = empty($_POST['user_want_promo'])? 0 : 1;
      $result = SetMemberCampaignMonitor($LIST_ID, $data, $data['user_want_promo']);
       
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_orderresource (orderresource_form_name,
                orderresource_fname,orderresource_lname,orderresource_job_title,orderresource_company,orderresource_department,
                orderresource_address,orderresource_suburb,orderresource_state,orderresource_postcode,orderresource_email,
    		    orderresource_phone,orderresource_category,orderresource_pack_required,orderresource_catalogue,orderresource_a3poster,
                orderresource_stand, orderresource_ip,orderresource_email_id,orderresource_created)
            
              VALUES (:orderresource_form_name,
                :orderresource_fname,:orderresource_lname,:orderresource_job_title,:orderresource_company,:orderresource_department,
                :orderresource_address,:orderresource_suburb,:orderresource_state,:orderresource_postcode,:orderresource_email,
    		    :orderresource_phone,:orderresource_category,:orderresource_pack_required,:orderresource_catalogue,:orderresource_a3poster,
                :orderresource_stand, :orderresource_ip,:orderresource_email_id,now() )";
        
        $resource_pack = 0;
        if($_POST['resource_pack']){
          $resource_pack = 1;
        }
        $params = array(
            ":orderresource_form_name" => $_POST['form_name'], 
            ":orderresource_fname" => $_POST['fname'], 
            ":orderresource_lname" => $_POST['lname'], 
            ":orderresource_job_title" => $_POST['jobtitle'], 
            ":orderresource_company" => $_POST['company'], 
            ":orderresource_department" => $_POST['department'], 
            ":orderresource_address" => $_POST['address'], 
            ":orderresource_suburb" => $_POST['suburb'], 
            ":orderresource_state" => $_POST['state'], 
            ":orderresource_postcode" => $_POST['postcode'],
            ":orderresource_email" => $_POST['email'], 
            ":orderresource_phone" => $_POST['phone'],  
            ":orderresource_category" => $_POST['category'], 
            ":orderresource_pack_required" => $resource_pack, 
            ":orderresource_catalogue" => $_POST['membership_catalogues'],  
            ":orderresource_a3poster" => $_POST['a3_posters'],
            ":orderresource_stand" => $_POST['stands'],
            ":orderresource_ip" => $_SERVER['REMOTE_ADDR'], 
            ":orderresource_email_id" => $sent 
        );
        
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        $error = 'There was an unexpected error saving your enquiry.';
      }
    }
    
    if(empty($error)){
      /*
       * try{
       * $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
       * $body = $SMARTY->fetch("email-thank-you.tpl");
       * $subject = 'Thank you for your enquiry';
       * $fromEmail = (string) $CONFIG->company->email_from;
       * $to = $_POST['email'];
       * $COMP = json_encode($CONFIG->company);
       * $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
       * $from = (string) $CONFIG->company->name;
       * $sent = sendMail($to, $from, $fromEmail, $subject, $body);
       * }catch (Exception $e){
       * $error = 'There was an error sending the orderresource email.';
       * }
       */
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
      }
      
      header("Location: /thank-you-for-requesting-a-resource-pack");
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
    //die(var_dump($data));
    if(empty($flag)){
      $cs_result = $wrap->unsubscribe($data['email']);
    } else{      
      $cs_result = $wrap->add(array(
          'EmailAddress' => $data['email'],
          'Name' => $data['fname'] . ' ' . $data['lname'],
          'CustomFields' => $customFields,
          "Resubscribe" => "true"
      ));
    }
//    die(var_dump($cs_result));
    if($cs_result->was_successful()){
      return true;
    }
  }
  catch(Exception $e){
    $COMP = json_encode($CONFIG->company);
    $body = "Error: {$e}<br> Session: " . print_r($_SESSION, true);
    $to = 'shaun@them.com.au';
    $from = (string)$CONFIG->company->name;
    $fromEmail = (string)$CONFIG->company->email_from;
    $subject = "{$from} | Campaign monitor error";
    sendMail($to, $from, $fromEmail, $subject, $body);
  }
  return false;
}
