<?php

Class Record{

	protected $CONFIG_OBJ;
	protected $DBTABLE;
	//URL value passed into constructor
	private $URL;
	private $TABLE;
	private $ID;
	private $FIELD;
	private $DELETED;
	private $CONFIG;
	//SQL ELEMENTS
	protected $SELECT = "*";
	protected $TABLES = "";
	protected $WHERE = "";
	protected $ORDERBY = "";
	protected $GROUPBY = "";
	protected $LIMIT = "";
	//SET OF DATA LOADED
	protected $DATA;

	function __construct($_config){
		global $SMARTY,$DBobject;
		$this->URL = $_config->url;
		$this->TABLE = $_config->table->name;
		$this->ID = $_config->table->id;
		$this->FIELD = $_config->table->field;
		$this->WHERE = $_config->table->where?$_config->table->where:'';
		$this->ORDERBY = $_config->table->orderby?$_config->table->orderby:'';
		$this->GROUPBY = $_config->table->groupby?$_config->table->groupby:'';
		$this->LIMIT = $_config->table->limit?$_config->table->limit:'';
		$this->DELETED = $_config->table->deleted;
		$this->CONFIG = $_config;
	}

	function updateRecord($arr){

	}

	function getRecord($id){
		global $SMARTY,$DBobject;
		$article_f = array();
		$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->ID} = '{$id}' AND {$this->DELETED} IS NULL".($this->WHERE!=""?" AND {$this->WHERE}":"");// AND article_deleted IS NULL";
		//echo $sql.'<br/>';
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res[0] as $key => $field) {
				if(!is_numeric($key)){
					$article_f[$key] = $field;
				}
			}
		}

		foreach($this->CONFIG->table->options->field as $f){

			$pre = str_replace("tbl_","",$f->table);
			$sql = "SELECT {$pre}_id,{$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL ".($f->where!=''?"AND {$f->where} ":"")." ";// AND article_deleted IS NULL";
			if($res = $DBobject->wrappedSqlGet($sql)){
				foreach ($res as $key => $row) {
					$article_f['options']["{$f->name}"][] = array('id'=>$row["{$pre}_id"],'value'=>$row["{$f->reference}"]);
				}
			}
		}

		foreach($this->CONFIG_OBJ->associated as $a){
			$pre = str_replace("tbl_","",$a->table);
			$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$id}' AND {$pre}_deleted IS NULL ";
			if($res = $DBobject->wrappedSqlGet($sql)){
				foreach ($res as $row) {
					$r_array = array();
					foreach($row as $key =>$field){
						$r_array["{$key}"] = $field;
					}
					$listing_f["{$a->name}"][] = $r_array;
				}
			}
		}

		return  $article_f;
	}
	function getRecordBuilder($id){
		global $SMARTY,$DBobject;
		$article_f = array();
		$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->ID} = '{$id}' AND {$this->DELETED} IS NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res[0] as $key => $field) {
				if(!is_numeric($key)){
					$article_f[$key] = array("title"=>$key,"value"=>$field);
				}
			}
		}

		$record = array("{$this->TABLE}"=>$article_f);
		return  $record;
	}

	function getRecordList($hierarchy_id=null){
		global $SMARTY,$DBobject;
		$records = array();
		
		$hierarchy = $this->CONFIG->table->hierarchy;
		if(!empty($hierarchy) && !empty($hierarchy->attributes()->field) && ((integer)$hierarchy->attributes()->default) >= 0){
			$local_field = $hierarchy->attributes()->field;
			if(empty($hierarchy_id)){
				$hierarchy_id = ((integer)$hierarchy->attributes()->default);
			}
			
			$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->DELETED} IS NULL AND {$local_field} = {$hierarchy_id} ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ";
			$params = array(":def" =>$hierarchy_id);
			if($res = $DBobject->wrappedSqlGet($sql,$params)){
				foreach ($res as $key => $val) {
					$subs = $this->getRecordList($val["{$this->ID}"]);
					$records[$val["{$this->ID}"]] = array("title"=>$val["{$this->FIELD}"],"id"=>$val["{$this->ID}"],"url"=>"/admin/edit/{$this->URL}/{$val["{$this->ID}"]}","url_delete"=>"/admin/delete/{$this->URL}/{$val["{$this->ID}"]}","subs"=>$subs);
				}
			}
		}else{
			$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->DELETED}  IS NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ";
			if($res = $DBobject->wrappedSqlGet($sql)){
				foreach ($res as $key => $val) {
					$records[$val["{$this->ID}"]] = array("title"=>$val["{$this->FIELD}"],"id"=>$val["{$this->ID}"],"url"=>"/admin/edit/{$this->URL}/{$val["{$this->ID}"]}","url_delete"=>"/admin/delete/{$this->URL}/{$val["{$this->ID}"]}");
				}
			}
		}
		
		
		return  $records;
	}
	function deleteRecord($id){
		global $DBobject;
		$sql = "UPDATE {$this->TABLE} SET {$this->DELETED} = NOW() WHERE {$this->ID} = '{$id}' ";
		if($DBobject->executeSQL($sql)){
			global $DELETED;
			$_SESSION['notice']= $DELETED;
			return true;
		}
		return false;

	}

}