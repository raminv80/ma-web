<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
if(checkToken($_POST["formToken"])){
	$tables = $DBobject->ShowTables();
	if($tables){
		// $_POST['field'] This is an array of all fields. The structure of the array is field['table name']['field name'] == value
		// field['table name']['id'] == id field
		$fields = $_POST['field'];
		foreach($fields as $tbl => $group){
			if(in_array($tbl, $tables)){
				foreach ( $group as $index => $vals) {
					$id =  $vals['id'];
					$id_check = '';
					$_update_vals = array();	
					$_insert_vals = array();
					$_insert_fields = array();
					$_params = array();
					if(is_array($vals)){
						foreach($vals as $key => $val){
							if($key == 'id'){ continue; }
							if($key == $id){
								$id_check = "{$key} = :{$key}";
							}else{
								$_update_vals[] = "{$key} = :{$key} ";	
								$_insert_vals[] = ":{$key} ";	
								$_insert_fields[] = "{$key}";	
								$_params[$key] = $val;
							}
						}
					}
					if($vals[$id] == ""){
						$sql = "INSERT INTO {$tbl} (".implode(',',$_insert_fields).") VALUES (".implode(',',$_insert_vals).")";
					}else{
						$_params[$id] = $vals[$id];
						$sql = "UPDATE {$tbl} SET ".implode(',',$_update_vals)." WHERE {$id_check}";
					}
					

					$result = $DBobject->executeSQL($sql , $_params );
				}	
				
			}
		}
	}
}
$_SESSION['alert']='<p><b>Record edited</b>'.$att.'</p>';
header("Location: {$_SERVER['HTTP_REFERER']}");
die();