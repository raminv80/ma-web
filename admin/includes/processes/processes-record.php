<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
if(checkToken($_POST["formToken"])){
	$tables = $DBobject->ShowTables();
	if($tables){
		
		$stored = array();
		
		$fields = $_POST['field'];
		foreach($fields as $order => $set){
			foreach($set as $tbl => $group){
				if(in_array($tbl, $tables)){
					foreach ( $group as $index => $vals) {
						$id =  $vals['id'];
						$id_check = '';
						$_update_vals = array();
						$_insert_vals = array();
						$_insert_fields = array();
						$_params = array();
						if(is_array($vals)){
							if($tbl == 'tbl_link'){
								if(!empty($vals['link_type_id']) && !empty($vals['link_list_id'])){
									$slq_link = 'UPDATE tbl_link SET link_deleted = NOW() WHERE link_type_id = :link_type_id AND link_list_id = :link_list_id';
									$result = $DBobject->executeSQL($slq_link ,array('link_type_id' => $vals['link_type_id'], 'link_list_id'=>$vals['link_list_id'] ));
								}
								if(is_array($vals['link_parent_id'])){
									foreach ($vals['link_parent_id'] as $cat){
										$slq_link = "INSERT INTO tbl_link ( link_type_id,link_list_id,link_parent_id ) VALUES ( :link_type_id,:link_list_id,:link_parent_id)";
										$result = $DBobject->executeSQL($slq_link ,array('link_type_id'=>$vals['link_type_id'],'link_list_id'=>$vals['link_list_id'],'link_parent_id'=>$cat));
									}
								}else{
									$slq_link = "INSERT INTO tbl_link ( link_type_id,link_list_id,link_parent_id ) VALUES ( :link_type_id,:link_list_id,:link_parent_id)";
									$result = $DBobject->executeSQL($slq_link ,array('link_type_id'=>$vals['link_type_id'],'link_list_id'=>$vals['link_list_id'],'link_parent_id'=>$vals['link_parent_id']));
								}
								continue;
							}
							foreach($vals as $key => $val){
								if($key == 'id'){ continue; }
								if($key == $id){
									$id_check = "{$key} = :{$key}";
								}else{
									if(empty($val) && array_key_exists($key, $_POST['default'])){
										$val = $stored["{$_POST['default']["{$key}"]}"];
									}
									$_update_vals[] = "{$key} = :{$key} ";
									$_insert_vals[] = ":{$key} ";
									$_insert_fields[] = "{$key}";
									$_params["{$key}"] = $val;
								}
							}
						}
						if($vals["{$id}"] == ""){
							$sql = "INSERT INTO {$tbl} (".implode(',',$_insert_fields).") VALUES (".implode(',',$_insert_vals).")";
							$insert_table=$tbl;
						}else{ 
							$_params["{$id}"] = $vals["{$id}"];
							$sql = "UPDATE {$tbl} SET ".implode(',',$_update_vals)." WHERE {$id_check}";
							$update_table=$tbl;
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
							$o = $DBobject->PDO->lastInsertId();
							$stored["{$id}"] = $o;
							if(empty($back_to_id)){
								$back_to_id = '/'.$o;
							}
						}
						
					}
				}
			}
		}
	}
}


global $EDITED;
$_SESSION['notice']= $EDITED;
header("Location: {$_SERVER['HTTP_REFERER']}{$back_to_id}");
die();