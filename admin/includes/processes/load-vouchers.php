<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  if(!empty($_POST['search'])){
    $sql = "SELECT * FROM tbl_discount WHERE discount_deleted IS NULL AND discount_name = 'Birthday voucher' AND (discount_user_id = :uid OR discount_code LIKE :code)";
    $params = array(
        'uid' => $_POST['search'],
        'code' => "%{$_POST['search']}%"
    );
    if($res = $DBobject->wrappedSql($sql, $params)){
      $SMARTY->assign("list", $res);
    }
    $template= $SMARTY->fetch('ec_vouchers.tpl');
    echo json_encode(array(
        'body' =>  $template
    ));
  }
}