<?php
class Table
{

	public $table;
	public $id;
	public $fields;
	public $foreign_fields;
	public $fmisc;
	public $private_fields;
	public $fields_default_value;
	public $fields_type;
	public $table_prefix;
	public $id_name;
	public $sname;
	public $plural;
	public $linkfiles;
	public $listfields;
	public $canadd;
	public $canexport;
	public $misc_id;
	public $addjs;
	public $addphp;
	public $email_list;
	public $candelete;
	public $canedit;
	public $orderby;
	public $filter;
	//Cache table
	public $cache_table;

	function  __construct($id){
		//echo $id;
		//die('');
		$this->misc_id = $id;
		$this->DBobject = new DBmanager();
		$sql = "SELECT * from tbl_cfg WHERE cfg_id = {$id} ";
		$table_data = $this->DBobject->wrappedSqlGetSingle($sql);
		//$table_data = $this->DBobject->GetRow('tbl_cfg',"cfg_id ='$id'");  					 
		$this->private_fields = explode(',', $table_data['cfg_private']);				 
		$this->listfields = explode(',', $table_data['cfg_listfields']);				 
		
		$this->table           = 	$table_data['cfg_tblname'];	
		$this->table_prefix    =    str_replace("tbl_","",$this->table);						 
		$this->id_name         = 	$this->table_prefix."_id";  						 
		$this->sname	       =	$table_data['cfg_sname'];
		$this->plural	       =	$table_data['cfg_plural'];
		$this->linkfiles       =	$table_data['cfg_linkfiles'];					 
		$this->canadd	       =	$table_data['cfg_canadd'];					 
		$this->candelete	   =	$table_data['cfg_candelete'];			 
		$this->canexport	   =	$table_data['cfg_canexport'];				 
		$this->canedit	       =	$table_data['cfg_canedit'];				 


		$this->foreign_fields			=	array();								 
		$this->linked_foreign_fields	=	array();
		$this->fmisc					=	array();
		$this->addjs					=	array();
		$this->addphp					=	array();


		$this->foreign_fields			=    $this->UnserializeForeign($table_data['cfg_foreign']);
		$this->linked_foreign_fields	=    $this->UnserializeLinkForeign($table_data['cfg_linked_foreign']);
		$this->addjs	    			=    $this->UnserializeArray($table_data['cfg_addjs'],"~"); 
		$this->addphp	  				=    $this->UnserializeArray($table_data['cfg_addphp'],"~"); 
		$this->fmisc        			=    $this->UnserializeArray($table_data['cfg_fmisc']);
		$this->orderby      			=     $this->UnserializeArray($table_data['cfg_orderby']);
		$this->filter  				    =     $this->UnserializeArray($table_data['cfg_filter']);
		$this->email_list				=	  $table_data['cfg_emaillist'];//for emails

		$tablefields = $this->DBobject->ShowColumns($this->table);
		if($tablefields){
				foreach ($tablefields as $tablefield){	
						$this->fields[ $tablefield['Field']]=null;
						$this->fields_default_value[ $tablefield['Field']]= $tablefield['Default'];
						$this->fields_type[ $tablefield['Field']]= $tablefield['Type'];
				}
			}
		$this->private_fields[]=$this->table_prefix."_deleted";
		$this->private_fields[]=$this->table_prefix."_updated";
		$this->private_fields[]=$this->table_prefix."_modified";
		$this->private_fields[]=$this->table_prefix."_id";
		
		//Cache table
		$this->cache_table = "cache_{$this->table}";
		
	}

	function UnserializeArray($str,$separator=','){
		$return_arr =	array();
		$str = preg_replace("/\n|\r/", "", $str);
		$temp_arr = explode($separator,$str);
		foreach ($temp_arr as $line) {
			$temp_arr_2 = explode('=>',$line);
			$return_arr[$temp_arr_2[0]] = explode(':',$temp_arr_2[1]);
		}
		return $return_arr;
	}
	
	function UnserializeForeign($str,$separator=','){
		$return_arr = array();
		$str = preg_replace("/\n|\r/", "", $str);
		$temp_arr = explode($separator,$str);
		foreach ($temp_arr as $line) {
			$temp_arr_4 = explode('=>',$line);
			$return_arr[$temp_arr_4[0]] = array("link_tbl"=>$temp_arr_4[0],"foreign_tbl"=>$temp_arr_4[1],"field_name"=>$temp_arr_4[2],"title"=>$temp_arr_4[3]);
		}
		return $return_arr;
	}
	
	function UnserializeLinkForeign($str,$separator=','){
		$return_arr = array();
		$str = preg_replace("/\n|\r/", "", $str);
		$temp_arr = explode($separator,$str);
		foreach ($temp_arr as $line) {
			$temp_arr_2 = explode('->',$line);
			$temp_arr_4 = explode('=>',$temp_arr_2[1]);
			$return_arr[$temp_arr_2[0]][] = array("link_tbl"=>$temp_arr_4[0],"foreign_tbl"=>$temp_arr_4[1],"field_name"=>$temp_arr_4[2],"title"=>$temp_arr_4[3]);
		}
		return $return_arr;
	}
	
	function GetTableforfield($field){
		if($field){
			$pos = strrpos($field, '_');
			$thistable = substr($field, 0, $pos);
			$this_table ="tbl_".$thistable;
			return $this_table;
		}else{
			return false;
		}
	}
	function GetIDforTable($field){
		if($field){
			$pos = explode("_", $field);
			$thisid = $pos[1].'_id';
			return $thisid;
		}else{
			return false;
		}
	}
	function GetIDname($field,$table=1){
		if($field){
			if($table == 1){
				$pos = strrpos($field, '_');
				$thistable = substr($field, 0, $pos);
				$this_id = $thistable."_id";
			}else{
				$thistable = substr($field, 4);
				$this_id = $thistable[1]."_id";
			}
			return $this_id;
		}else{
			return false;
		}
	}
	
	function GetValueForFF($field_name,$field_value){
		$ffkey = trim(preg_replace('/'.$this->table_prefix.'/','',$field_name,1),'_');
		$result = substr($ffkey, 0, strrpos($ffkey, '_'));
		$table='tbl_'.$result;
		$field_on_f_table = $this->foreign_fields[$field_name][0];
		$val = $this->DBobject->GetAnyCell($field_on_f_table, $table, $ffkey.' = \''.$field_value.'\' ');
		if($val)
			return  $val;
		else
			return $field_value;
	}
	
	function GetMiscIDForFF($field_name,$field_value){
		$ffkey = trim(preg_replace('/'.$this->table_prefix.'/','',$field_name,1),'_');
		$result = substr($ffkey, 0, strrpos($ffkey, '_'));
		$table='tbl_'.$result;
		$val = $this->DBobject->GetAnyCell('cfg_id', 'tbl_cfg', 'cfg_tblname = "'.$table.'" ');
		return  $val;
	}
	
	function GetMiscRecordIDForFF($field_name,$field_value){
		$ffkey = trim(preg_replace('/'.$this->table_prefix.'/','',$field_name,1),'_');
		$result = substr($ffkey, 0, strrpos($ffkey, '_'));
		$table='tbl_'.$result;
		$f_id = $result.'_id';
		$field_on_f_table = $this->foreign_fields[$field_name][0];
		$val = $this->DBobject->GetAnyCell($f_id, $table, $ffkey.' = \''.$field_value.'\' ');
		return  $val;
	}

	function BuildCache($field){
		if(key_exists($field, $this->fields)){
			$sql[0] = "CREATE TABLE IF NOT EXISTS `cache_{$this->table}` (
						  `cache_id` INT(11) NOT NULL AUTO_INCREMENT,
						  `cache_record_id` INT(11) DEFAULT NULL,
						  `cache_url` VARCHAR(255) DEFAULT NULL,
						  `cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
						  `cache_modified` DATETIME DEFAULT NULL,
						  `cache_deleted` DATETIME DEFAULT NULL,
						  PRIMARY KEY (`cache_id`)
						) ENGINE=MYISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;";
			$this->DBobject->wrappedSql($sql[0]);
			
			$sql[3] = "TRUNCATE cache_{$this->table};";
			$sql[2] = " INSERT INTO cache_{$this->table} (cache_record_id,cache_url,cache_modified) VALUES ";
			
			$sql[1] = "SELECT {$this->table_prefix}_id AS id, {$this->table_prefix}_url AS title FROM {$this->table} WHERE {$this->table_prefix}_deleted IS NULL";
			$res = $this->DBobject->wrappedSql($sql[1]);
			foreach($res as $row){
				$id = $row['id'];
				$title = unclean($row[$field]);
				$url = urlSafeString($title);
				$sql[2].= " ('{$id}', '{$url}', now()),";
			}
			$sql[2] = trim(trim($sql[2]), ',');
			$sql[2].= ";";
			$this->DBobject->wrappedSql($sql[3]);
			$this->DBobject->wrappedSql($sql[2]);
		}else if(key_exists($this->table_prefix."_title", $this->fields)){
			$sql[0] = "CREATE TABLE IF NOT EXISTS `cache_{$this->table}` (
						  `cache_id` INT(11) NOT NULL AUTO_INCREMENT,
						  `cache_record_id` INT(11) DEFAULT NULL,
						  `cache_url` VARCHAR(255) DEFAULT NULL,
						  `cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
						  `cache_modified` DATETIME DEFAULT NULL,
						  `cache_deleted` DATETIME DEFAULT NULL,
						  PRIMARY KEY (`cache_id`)
						) ENGINE=MYISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;";
			$this->DBobject->wrappedSql($sql[0]);
			
			$sql[3] = "TRUNCATE cache_{$this->table};";
			$sql[2] = " INSERT INTO cache_{$this->table} (cache_record_id,cache_url,cache_modified) VALUES ";
			
			$sql[1] = "SELECT {$this->table_prefix}_id AS id, {$this->table_prefix}_title AS title FROM {$this->table} WHERE {$this->table_prefix}_deleted IS NULL";
			$res = $this->DBobject->wrappedSql($sql[1]);
			foreach($res as $row){
				$id = $row['id'];
				$title = unclean($row['title']);
				$url = urlSafeString($title);
				$sql[2].= " ('{$id}', '{$url}', now()),";
			}
			$sql[2] = trim(trim($sql[2]), ',');
			$sql[2].= ";";
			$this->DBobject->wrappedSql($sql[3]);
			$this->DBobject->wrappedSql($sql[2]);
		}
	}
	
	
}