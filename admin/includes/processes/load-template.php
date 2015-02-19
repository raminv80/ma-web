<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  global $SMARTY;
  
  $tpl = $_POST['template'];
  if(!file_exists("{$SMARTY->template_dir[0]}".$tpl)){
  	die();
  }
  foreach($_POST as $key => $var){
  	$SMARTY->assign("{$key}",$var);
  }
  $SMARTY->display("{$tpl}");
}