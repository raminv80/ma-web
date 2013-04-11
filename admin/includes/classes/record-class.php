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
		$this->DELETED = $_config->table->deleted;
	}
	
	function updateRecord($arr){
		
	}
	
	function getRecord($id){
		global $SMARTY,$DBobject;	
		$article_f = array();
		$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->ID} = '{$id}' AND {$this->DELETED} IS NULL";// AND article_deleted IS NULL";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res[0] as $key => $field) {
				if(!is_numeric($key)){
					$article_f[$key] = $field;
				}
			}
		}
		
		return  $article_f;
	}
	function getRecordBuilder($id){
		global $SMARTY,$DBobject;	
		$article_f = array();
		$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->ID} = '{$id}' AND {$this->DELETED} IS NULL";// AND article_deleted IS NULL";
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
	
	function getRecordList(){
		global $SMARTY,$DBobject;
		$records = array();
		$sql = "SELECT * FROM {$this->TABLE} WHERE {$this->DELETED} IS NULL";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res as $key => $val) {
				$records[$key] = array("title"=>$val["{$this->FIELD}"],"id"=>$val["{$this->ID}"],"url"=>"/admin/edit/{$this->URL}/{$val["{$this->ID}"]}");
			}
		}
		return  $records;
	}
	
}