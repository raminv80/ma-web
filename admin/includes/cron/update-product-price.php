<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $limitDate = '2017-01-01';
  
  //UPDATE PRICES - NON-STAINLESS STEEL
  $sql = "UPDATE tbl_variant LEFT JOIN tbl_product ON product_id = variant_product_id
    SET variant_price = ROUND((variant_price * 1.15) / 5) * 5
    WHERE (product_deleted IS NULL OR product_deleted = '0000-00-00') 
    AND product_published = 1 
    AND (variant_deleted IS NULL OR variant_deleted = '0000-00-00') 
    AND product_type_id = 1 AND variant_modified < '{$limitDate}'
    AND NOT EXISTS (
      SELECT pmateriallink_product_id FROM tbl_pmateriallink WHERE (pmateriallink_deleted IS NULL OR pmateriallink_deleted = '0000-00-00') 
      AND pmateriallink_record_id = 1 AND pmateriallink_product_id = product_id
    )";
  if($DBobject->executeSQL($sql)){
    echo "Success<br>";
  }
  echo "Done<br>";
  
}catch(Exception $e){
  $fromEmail = (string)$CONFIG->company->email_from;
  $from = (string)$CONFIG->company->name;
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Update Price', $e->getMessage());
}
die();
