<?php

Class Listing{
	
	protected $CONFIG_OBJ;
	private $DBTABLE;
	//URL value passed into constructor
	private $TYPE_ID;
	//SQL ELEMENTS
	protected $SELECT = "*";
	protected $TABLES = "";
	protected $WHERE = "";
	protected $ORDERBY = "";
	protected $GROUPBY = "";
	protected $LIMIT = "";
	//SET OF DATA LOADED
	protected $DATA;
	
	function __construct($_sp){
		global $SMARTY,$DBobject;
		$this->CONFIG_OBJ = $_sp;	
		$this->TYPE_ID = $_sp->type_id;
		
		$this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id
							LEFT JOIN tbl_category ON tbl_listing.listing_category_id = tbl_category.category_id ";
		foreach($_sp->extends as $extend){
			$this->DBTABLE .= " LEFT JOIN {$extend->table} ON {$extend->table}.{$extend->field} = tbl_listing.listing_id ";
		}
	}
	
	function updateListing($arr){
		
	}
	
	function getListing($id){
		global $SMARTY,$DBobject;	
		$listing_f = array();
		$sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' AND listing_deleted IS NULL";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res[0] as $key => $field) {
				if(!is_numeric($key)){
					$listing_f[$key] = $field;
				}
			}
		}
		
		return  $listing_f;
	}
	
	function getListingBuilder($id){
		global $SMARTY,$DBobject;	
		$listing_f = array();
		$sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' AND listing_deleted IS NULL";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res[0] as $key => $field) {
				if(!is_numeric($key)){
					$listing_f[$key] = array("title"=>$key,"value"=>$field);
				}
			}
		}
		
		$record = array("{$this->TABLE}"=>$listing_f);
		return  $record;
	}
	
	function getListingList(){
		global $SMARTY,$DBobject;	
		$records = array();
		$sql = "SELECT tbl_listing.listing_name, tbl_listing.listing_id FROM {$this->DBTABLE} WHERE tbl_listing.listing_type_id = '{$this->TYPE_ID}' AND tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res as $key => $val) {
				$records[$key] = array("title"=>$val['listing_name'],"id"=>$val['listing_id'],"url"=>"/admin/edit/{$this->CONFIG_OBJ->url}/{$val['listing_id']}");
			}
		}
		return  $records;
	}
	
}