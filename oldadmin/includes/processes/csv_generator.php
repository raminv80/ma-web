<?php
ini_set('display_errors',1);
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'oldadmin/includes/functions/admin-functions.php';
//die($_SESSION["formToken"]."   ####  ".$_REQUEST['id']);
if(checkToken($_REQUEST['id'])	==	 true){
	
	if($_REQUEST['misc']) {
		
		$misc	=	intval(($_REQUEST['misc']));
		$misc_obj	=	new misc($misc);
		if($_REQUEST['s']==1){
			foreach ($misc_obj->listfields as $field) {
				$field = str_replace($misc_obj->table_prefix.'_','',$field);
				$head.="\"".strtoupper($field)."\",";	
			}
			$fields_arr = $misc_obj->listfields;
		}
		if($_REQUEST['s']==0){
			foreach ($misc_obj->fields as $field) {
				$field = str_replace($misc_obj->table_prefix.'_','',$field);
				$head.="\"".strtoupper($field)."\",";	
			}
			$fields_arr = $misc_obj->fields;			
		}
		$buf.="\r\n";
		$misc_arr	=	$misc_obj->get_misc();
		
		foreach ($misc_arr as $line) {
			foreach ($fields_arr as $field) {
				if(key_exists($field,$line)){
						$buf.="\"$line[$field]\",";	
						}
			}	
			$buf.="\r\n";			
		}
		
		$head=rtrim($head,',');
		$main_buf=$head.$buf;
		$filename=$misc_obj->plural.'.csv';
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($main_buf));
	    // Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
   		//header("Content-type: text/csv");
    	//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$filename);
    	echo $main_buf;

	}
	
}else{
	header("Location:/index.php");
}