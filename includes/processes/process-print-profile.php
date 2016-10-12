<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if(!empty($_SESSION['user']['public']['maf']['token'])){
      $error = "Error(2): Your session has expired.";
      $template = null;
      $user_obj = new UserClass();
      if($loginCheck = $user_obj->setSessionVars($_SESSION['user']['public']['maf']['token'])){
        $template = $user_obj->printProfile("http://{$_SERVER['HTTP_HOST']}/includes/print", $_SESSION['user']['public']['maf']['main'], $_SESSION['user']['public']['maf']['update']);
        saveInLog('member-print-profile', 'external', $_SESSION['user']['public']['id']);
        echo $template;
        die();
      }else{
        $_SESSION['user']['public'] = null;
      }
}
header('Location: /404' );
die();


