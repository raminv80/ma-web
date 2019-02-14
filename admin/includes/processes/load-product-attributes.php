<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  global $SMARTY, $DBobject;
  
  $result = array();
  $sql = "SELECT producttype_id FROM tbl_producttype WHERE producttype_deleted IS NULL ORDER BY producttype_id";
  if($res = $DBobject->wrappedSql($sql)){
    foreach($res as $r){
      $sql = "SELECT attribute_id, attribute_name FROM tbl_attribute LEFT JOIN tbl_productschema ON productschema_attribute_id = attribute_id
          WHERE attribute_deleted IS NULL AND productschema_deleted IS NULL AND productschema_type_id = :type_id ORDER BY attribute_order, attribute_name";
      if($res2 = $DBobject->wrappedSql($sql, array('type_id'=>$r['producttype_id']))){
        foreach($res2 as $r2){
          $result["{$r['producttype_id']}"]["{$r2['attribute_id']}"]['id'] = $r2['attribute_id'];
          $result["{$r['producttype_id']}"]["{$r2['attribute_id']}"]['name'] = $r2['attribute_name'];
          
          $sql = "SELECT attr_value_id, attr_value_name FROM tbl_attr_value WHERE attr_value_deleted IS NULL AND attr_value_attribute_id = :id ORDER BY attr_value_order, attr_value_name";
          $result["{$r['producttype_id']}"]["{$r2['attribute_id']}"]['values'] = $DBobject->wrappedSql($sql, array('id'=>$r2['attribute_id']));
        }
      }
    }
  }
  $SMARTY->assign("attributes", $result);
}