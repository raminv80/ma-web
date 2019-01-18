<?php
ini_set('display_errors', 1);

ini_set('error_reporting', E_ALL);

ini_set('memory_limit', '750M');

set_include_path($_SERVER['DOCUMENT_ROOT']);

include 'admin/includes/functions/admin-functions.php';

global $CONFIG, $SMARTY, $DBobject;

if($_SERVER['REMOTE_ADDR'] != '45.124.202.249') die('Restricted area');

//echo 'hello';

$csv_content_full = '';
    $file = 'usages.csv';
    $csv_content = '';
    $preprocess_result = '';
    if(file_exists($file)){
      $fp = fopen($file, "r");
      $rows   = array_map('str_getcsv', file($file));
      //echo '<pre>';
      //print_r($rows);exit;
      $header = array_shift($rows);
      $csv_data    = array();
      foreach($rows as $row) {
        //print_r($row);
        $csv_data[] = array_combine($header, $row);
      }
      $csv_data = array_change_key_case($csv_data, CASE_LOWER);
    }
    //echo '<pre>';
    //print_r($csv_data);
    foreach($csv_data as $row){
      // echo "<pre>";
      // print_r($row);
      // echo "</pre>";
      $sql = "SELECT * FROM tbl_product WHERE product_object_id = ".$row['obj_id']." AND product_deleted IS NULL";

      if($res = $DBobject->wrappedSql($sql)){
        for($i = 1; $i <= 7; $i++){
          if($row[$i] == 'Yes'){
            echo $sql_insert = "INSERT INTO tbl_pusagelink (pusagelink_product_id, pusagelink_record_id, pusagelink_created) VALUES (".$res[0]['product_id'].", ".$i.", now())";
            $DBobject->wrappedSql($sql_insert);
            echo '<br>';
          }
        }
      } else{
        echo 'not found';
      }
      
    }
    echo 'done';
