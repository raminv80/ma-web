<?php

class misc extends Table
{

	function  __construct($id){
		parent::__construct($id);
	}

	function set_update_misc($arr){
		$foreign_vals = array();
		
		foreach ($arr as $key => $value){
			if(
			array_key_exists($key,$this->fields) 
			&& $key != $this->id_name 
			&& strpos($key, 'password') == false			       
			&& strpos($key, 'datepicker') == false 
			&& !in_array($key,$this->foreign_fields) 
			&& !in_array($key,$this->linked_foreign_fields)){
				$num_of_fields++;
				if(is_array($value)){ // if it's an array it's a multi select and selections need to be stored in a linking table instead of being serialized.
					$value=serialize(clean($value));
					$clean_arr[$key]=$value;
				}else{
					$clean_arr[$key]=clean($value);
				}
			}
			
			/** Link Table handling init */
			if(array_key_exists($key, $this->foreign_fields)){
				$foreign_vals[] = array("field"=>$this->foreign_fields[$key], "values"=>$value);
			}
			/** END link table handling */

			if(strpos($key, 'datepicker') != false){
			    $num_of_fields++;
			    $key = str_replace('-datepicker','',$key);
			    if(is_array($value)){
			        foreach($value as $k => $d){
			            if($d == '0000-00-00' || $d == '00-00-0000' || empty($d)){
			                $value[$k] = '(NULL)';
			            }else{
			                $value[$k] = date('Y-m-d',strtotime($d));
			            }
			        }
					$value=serialize(($value));
					$clean_arr[$key]=$value;
				}else{
				    if($value == '0000-00-00' || $value == '00-00-0000' || empty($value)){
				        $value = '(NULL)';
				    }else{
				        $value = date('Y-m-d',strtotime($value));
				    }
					$clean_arr[$key]=($value);
				}
			}


			if((strpos($key, 'email') != false) && !in_array($key,$this->foreign_fields) && !in_array($key,$this->linked_foreign_fields)	&& empty($username)){
			    $username = $value;
			}

			if((strpos($key, 'password') != false) && !in_array($key,$this->foreign_fields) && !in_array($key,$this->linked_foreign_fields)){
			    $password = $value;
			    $pas_key = $key;
			}
			

			if(!empty($username) && !empty($password) && !in_array($pas_key, $clean_arr[$pas_key])){
				 $new_pass = getPass($username, $password);			//generate password
			    if($value	!= ''){
			        $clean_arr[$pas_key]=$new_pass;					//add to array
			        $num_of_fields++;
			    }
			}
		}
		if($clean_arr){
			$parent_id	=	$this->insert_update_DB($this->table,$this->id_name,$clean_arr,$num_of_fields,$arr['misc_id']);
		}
		
		/** Foreign value insert */
		foreach($foreign_vals as $val){
			//Initialise Link variables based on cfg settings//
			$link			= $val['field'];
			$link_tbl		= new Table($link['link_tbl']);
			$foreign_tbl	= new Table($link['foreign_tbl']);
			$field_name		= $link['field_name'];
			if($link['title'] && $link['title'] != ""){
				$title = $link['title'];
			}else{
				$title = str_replace('_', ' ', $field_name);
			}
			//END initialise//
			
			if(!empty($arr['misc_id'])){
				$parent_id = $arr['misc_id'];
			}
			
			// Delete from link table for this item
			if(!empty($parent_id)){
				$this->DeleteLinked($link_tbl, 'link_1_id', $parent_id);
			}
			foreach($val['values'] as $v){
				if(!empty($v)){
					
					$this->InsertLinked($link_tbl, $v, $parent_id, $foreign_tbl);
				}
			}
		}
		/** END Foreign value insert */

		if(count($this->fmisc[0]) > 0){
		    foreach ($this->fmisc as $fmsic_arr){
    		    foreach ($fmsic_arr as $table_id => $val) {
    				$clean_arr2	= array();
    				$other_misc = new misc($table_id);
    				foreach ($arr as $key => $entry) {
    					if(array_key_exists($key,$other_misc->fields) && !array_key_exists($key, $clean_arr2)){
    						$clean_arr2[$key] = clean($entry);
    					}
    				}
    				$field = $other_misc->table_prefix."_".$val[0];
    				if(!array_key_exists($field,$clean_arr2)){
    					$clean_arr2[$field]	=	$parent_id;
    				}
    				$ffields	=	$this->insert_update_DB($other_misc->table,$other_misc->id_name,$clean_arr2,count($clean_arr2),$arr['fmisc_id_'.$table_id]);
    			}
		    }
		}
		return  $parent_id;
	}
	
	function DeleteLinked($table, $field, $id){
	
		$sql = "UPDATE ".$table->table."
				SET link_deleted = now()
				WHERE ".$field." = '".$id."'";
	
		if($this->DBobject->wrappedSqlInsert($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	function InsertLinked($table, $v, $id,$foreign_tbl){
	
		$sql = "INSERT INTO {$table->table} (link_1_id, link_2_id)
			    VALUES ({$id}, {$v})";
		if($this->DBobject->wrappedSqlInsert($sql)){
			return true;
		}else{
			return false;
		}
	}

	private  function  insert_update_DB($table,$id_name,$this_arr,$num_of_fields,$id){
		if($id){
			//update regular  fields
			$sql  = "UPDATE ".$table." SET ";
			foreach ($this_arr as $key => $value){
				if($value != '(NULL)'){
					$sql.=" ".$key." = '".$value."' ";
				}else{
					$sql.=" ".$key." = ".$value." ";
				}
				if(sizeof($this_arr)-$count != '1' ){
					$sql.=",";
					$count++;
				}
			}
			if(in_array("{$this->table_prefix}_modified", $this->fields)){
				$sql .= ",".$this->table_prefix.'_modified = NOW()';
			}
			$sql .=" WHERE ".$id_name." = '".$id."' ";
			if($this->DBobject->wrappedSql($sql)){
				return 	$id;
			}else{
				return false;
			}
		}else{
			$sql = "INSERT INTO ".$table."  ( ";
			foreach ($this_arr as  $key => $field){
				$sql.=" ".($key)."  ";
				$sql2.=" '".$field."' ";
				if($num_of_fields-$count != '1' ){
					$sql.=",";
					$sql2.=",";
					$count++;
				}
			}
			$sql = trim($sql,',');
			$sql2 = trim($sql2,',');
			if(in_array("{$this->table_prefix}_modified", $this->fields)){
				$sql.= ",{$this->table_prefix}_modified";
				$sql2.= ", NOW()";
			}
			$sql.=") VALUES (".$sql2.")";
			if($this->DBobject->wrappedSql($sql)){
				return 	$this->DBobject->id;
			}else{
				return false;
			}
		}
	}

	function get_misc($id=0, $limit='', $count=0, $where='',  $order_by='',$includefiles=0){
		
		if($count) {
			$e_sql = "SELECT count(*) FROM ".$this->table." ".($where ? '  WHERE  '.$where .' AND '.$this->table_prefix.'_deleted IS NULL ' : 'WHERE '.$this->table_prefix.'_deleted IS NULL ').($order_by ? ' ORDER BY '.$order_by : ' ') ;
			return $this->DBobject->wrappedSqlGetSingle($e_sql);
		}
		if( $includefiles == '1') {
			$selectfile = ", tbl_file.* ";
			$joinfile = " LEFT JOIN
                        tbl_file  ON ".$this->table.".".$this->id_name." = tbl_file.file_entry_id
								 AND ".$this->misc_id." = 	tbl_file.file_misc_id
                        ";
			$orderfile = " order";

			if($where == '') {
				$a ='' ;
			}else{
				$a = 'and ';
			}
			$where.=$a.'  '.$this->table.'.'.$this->table_prefix.'_deleted is null';
		}else{
			$selectfile  = '';
			$joinfile = '';
		}
		$sql = "SELECT ".$this->table.".*	$selectfile
                         FROM
						 ".$this->table."$joinfile
						WHERE
					    (".$this->table_prefix."_deleted is null )
                        ".($where ? ' AND '.$where : '')
		.($id != 0 ? " AND ".$this->id_name."= '".$id."' " : "")
		.(($order_by && $order_by != '') ? " ORDER BY ".$order_by." " : "")
		.($limit ? " LIMIT ".$limit : "");
		$sql_res = $this->DBobject->executeSQL($sql);
		if($sql_res) {
			while($sql_arr = mysql_fetch_assoc($sql_res)) {
			foreach ($sql_arr as $key	=> $value) {
					$sql_arr[$key]=unclean($value);
				}
				$responce[]= $sql_arr;
			}
			return $responce;
		}else{

			return false;
		}
	}

	function Delete($id){

		$sql	=	"UPDATE ".$this->table." SET ".$this->table_prefix."_deleted = 'now()'
      				 WHERE  ".$this->id_name." = '".$id."'
      				";

		if($this->DBobject->wrappedSqlInsert($sql)){
			return true;
		}else{
			return false;
		}
	}

}