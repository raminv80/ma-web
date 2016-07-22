<?php
if(!empty($_REQUEST['gclid'])){
  $_SESSION['utm_source'] = "google ads";
  $_SESSION['utm_medium']="";
  $_SESSION['utm_content']="";
  $_SESSION['utm_campaign']="";
}else{
  if(!empty($_REQUEST['utm_source'])){
    $_SESSION['utm_source'] = $_REQUEST['utm_source'];
  }
  if(!empty($_REQUEST['utm_medium'])){
    $_SESSION['utm_medium'] = $_REQUEST['utm_medium'];
  }
  if(!empty($_REQUEST['utm_content'])){
    $_SESSION['utm_content'] = $_REQUEST['utm_content'];
  }
  if(!empty($_REQUEST['utm_campaign'])){
    $_SESSION['utm_campaign'] = $_REQUEST['utm_campaign'];
  }
}
if(empty($_SESSION['source_referer'])){
  $_SESSION['source_referer'] = empty($_SERVER['HTTP_REFERER'])?'direct':$_SERVER['HTTP_REFERER'];
}
  
