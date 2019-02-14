<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['loop-email']) && !empty($_POST['loop-fname']) && !empty($_POST['loop-lname']) && !empty($_POST['form_name'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
  
    /**
     * ************ SET CAMPAIGN MONITOR LIST ID WHEN NEEDED ********
     */
    if($_POST['form_name'] == 'Newsletter subscription - emergency personal'){
      $LIST_ID = '421adf5731c3286e3968c2dc28b39463';
    } elseif($_POST['form_name'] == 'Newsletter subscription - refer your patient'){
      $LIST_ID = 'b9d5f5c42a8ece869c63b64124680a53';
    }
    /**
     * ************ **************************************** ********
     */
    
    $error = '';
    //set data to be posted to campaign monitor
    $data = array(
      'email' => $_POST['loop-email'],
      'fname' => $_POST['loop-fname'],
      'lname' => $_POST['loop-lname']
    );
    
    //set the custom fields to be posted to campaign monitor
    $customFields = array(
      'Position' => $_POST['loop-position'],
      'Firstname1' => $_POST['loop-fname'],
      'Lastname1' => $_POST['loop-lname']
    );
    
    $data['customFields'] = $customFields;
    
    //call to function to add member to campaign monitor
    $result = SetMemberCampaignMonitor($LIST_ID, $data);
     
    //check result and return message
    if($result){
      $success = 'Successfully subscribed';
    } else{
      $error = 'Error: could not subscribe. Please refresh page and try again.';
    }
    if(empty($error)){
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
      }
    }
  } else{
    $error = 'Please provide the required information.';
  }
} else{
  $error = 'Your session has expired.<br>Please try again.';
}

echo json_encode(array(
    'error' => $error,
    'success' => $success
));
die();


function SetMemberCampaignMonitor($listId, $data, $flag = 1){
  global $CONFIG;
  
  if(empty($data) || empty($listId)){
    return false;
  }
  
  $customFields = array();
  if(key_exists('customFields', $data)){
    foreach($data['customFields'] as $k => $v){
      if(!in_array($k, $skipArr)){
        $customFields[] = array(
            'Key' => $k,
            'Value' => $v
        );
      }
    }
    unset($data['customFields']);
  }
  
  try{
    require_once 'includes/createsend/csrest_subscribers.php';
    $wrap = new CS_REST_Subscribers($listId, '060d24d9003a77b06b95e7c47691975b');
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
    //die(var_dump($cs_result));
    if($cs_result->was_successful()){
      return true;
    }
  }
  catch(Exception $e){
    $COMP = json_encode($CONFIG->company);
    $body = "Error: {$e}<br> Session: " . print_r($_SESSION, true);
    $to = 'bikram@them.com.au';
    $from = (string)$CONFIG->company->name;
    $fromEmail = (string)$CONFIG->company->email_from;
    $subject = "{$from} | Campaign monitor error";
    sendMail($to, $from, $fromEmail, $subject, $body);
  }
  return false;
}
