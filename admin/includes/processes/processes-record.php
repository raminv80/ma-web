<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] != $_SERVER['HTTP_HOST']){
  header('HTTP/1.0 403 Forbidden');
  die();
}

session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include 'admin/includes/functions/admin-functions.php';
  if(checkToken('admin', $_POST["formToken"])){
  	$isInsert = false;
  	$additional = array();
  	$tables = $DBobject->ShowTables();
  	if($tables){
  		$returnIDs = array();
  		$stored = array();
  		$fields = $_POST['field'];
  		foreach($fields as $order => $set){
  			foreach($set as $tbl => $group){
  				if(in_array($tbl, $tables)){
  					foreach ( $group as $index => $vals) {
  					  $insert_table ="";$update_table="";
  					  
  						$id =  $vals['id'];
  						if(!is_array($id)){
  							$id = array($id);
  						}
  						$id_check = array();
  						$_update_vals = array();
  						$_insert_vals = array();
  						$_insert_fields = array();
  						$_params = array();
  						$additionalAction = 'Edit';
  						$additionalIDs = array();
  						if(is_array($vals)){
  							foreach($vals as $key => $val){
  								if($key == 'id'){ continue; }
  								if(in_array($key, $id)){ 
  									$id_check[] = "{$key} = :{$key}";
  									$additionalIDs[] = $val;
  								}else{
  									if(empty($val) && array_key_exists($key, $_POST['default'])){
  										$val = $stored["{$_POST['default']["{$key}"]}"];
  									}
  								  $_inp = ":{$key}";
  									if(empty($val) && $val !== "0" && !preg_match("/_url/", $key)){ $_inp="(NULL)"; }
  									else{	$_params["{$_inp}"] = $val; }
  									$_update_vals[] = "{$key} = {$_inp} ";
  									$_insert_vals[] = "{$_inp} ";
  									$_insert_fields[] = "{$key}";
  									if(preg_match("/deleted/", $key)){
  										$additionalAction = 'Delete';
  									}
  								}
  							}
  						}
  						$nvalues=0;
  						foreach ($id as $i){
  							if($vals["{$i}"] != ""){
  								$nvalues++; 
  							}
  						}
  						if(count($id) > $nvalues){ 
  							$sql = "INSERT INTO {$tbl} (".implode(',',$_insert_fields).") VALUES (".implode(',',$_insert_vals).")";
  							$insert_table=$tbl;
  						}else{ 
  							foreach ($id as $i){
  								$_params["{$i}"] = $vals["{$i}"];
  								if($_POST['primary_id'] == $i){
  									$primaryID = $vals["{$i}"];
  									$primaryTable = $tbl;
  									$primaryAction = $additionalAction;
  								}
  							}
  							$sql = "UPDATE {$tbl} SET ".implode(',',$_update_vals)." WHERE ".implode(' AND ',$id_check);
  							$update_table=$tbl;
  							
  							if($primaryTable != $tbl){
  								$additional[] = "$additionalAction|$tbl|". implode(':',$additionalIDs);
  							}
  						}
  	
  						$result = $DBobject->executeSQL($sql , $_params );
  						if(empty($stored)){ 
  							$stored = $_params; 
  						}else{
  							if(!empty($_params)){
  								$stored = array_merge($stored,$_params);
  							}
  						}
  						
  						if($insert_table != ''){
  							$o = $DBobject->wrappedSqlIdentity();
  							foreach ($id as $i){
  								$key = "field[{$order}][{$tbl}][{$index}][{$i}]";
  								$returnIDs[$key] = $o;
  							
  								$stored["{$i}"] = $o;
  								if($_POST['primary_id'] == $i){
  									$isInsert = true;
  									$primaryID = $o;
  									$primaryTable = $tbl;
  									$primaryAction = 'Add';
  								}else{
  									$additional[] = "Add|$tbl|$o";
  								}
  							}
  						}
  						
  					}
  				}
  			}
  		}
  	}
  }
  
  global $EDITED;
  
  if($isInsert){
  	saveInLog($primaryAction, $primaryTable, $primaryID, implode(',',$additional));
  	echo json_encode(array(
  			"notice" => $EDITED,
  			"IDs" => $returnIDs,
  			"primaryID" => $primaryID
  	));
  }else{
  	saveInLog($primaryAction, $primaryTable, $primaryID, implode(',',$additional));
  	echo json_encode(array(
  			"notice" => $EDITED,
  			"IDs" => $returnIDs
  	));
  }
  
  die();
}
echo json_encode(array(
    "notice" => "",
    "IDs" => array()
));
die();