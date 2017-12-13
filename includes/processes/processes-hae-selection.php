<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;
$referer = parse_url($_SERVER['HTTP_REFERER']);
$response = '';
if($referer['host'] == $GLOBALS['HTTP_HOST'] && !empty($_POST['agreed'])){
  $_SESSION['hae_flag'] = ($_POST['agreed'] == 'yes') ? 1 : 0;
  $response = $_SESSION['hae_flag'];
  echo json_encode(array(
      'response' => $response
  ));
}
die();