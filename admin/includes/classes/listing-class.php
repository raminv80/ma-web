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
							LEFT JOIN tbl_category ON tbl_listing.listing_id = tbl_category.category_listing_id ";
		foreach($_sp->extends as $extend){
			$this->DBTABLE .= " LEFT JOIN {$extend->table} ON {$extend->table}.{$extend->field} = tbl_listing.listing_id ";
			$this->WHERE .= $extend->where !="" ? "  {$extend->where} " :"";
		}
	}

	function updateListing($arr){

	}

	function getListing($id){
		global $SMARTY,$DBobject;
		$listing_f = array();
		$sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' AND listing_deleted IS NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ";
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res as  $row) {
				foreach ($row as $key => $field) {
					if(!is_numeric($key)){
						if( !in_array($field,$listing_f["{$key}"])	&& $listing_f["{$key}"] != $field){
							if(empty($listing_f["{$key}"])){
								$listing_f["{$key}"] = $field;
							}else{
								if(!is_array($listing_f["{$key}"])){
									$temp = $listing_f["{$key}"];
									$listing_f["{$key}"]=array($temp);
								}
								$listing_f["{$key}"][] = $field;
							}

						}
					}
				}
			}
		}
		foreach($this->CONFIG_OBJ->options->field as $f){
			$pre = str_replace("tbl_","",$f->table);
			$sql = "SELECT {$pre}_id,{$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL ".($f->where!=''?"AND {$f->where} ":"")." ";// AND article_deleted IS NULL";
			if($res = $DBobject->wrappedSqlGet($sql)){
				foreach ($res as $key => $row) {
					$listing_f['options']["{$f->name}"][] = array('id'=>$row["{$pre}_id"],'value'=>$row["{$f->reference}"]);
				}
			}
		}

		foreach($this->CONFIG_OBJ->associated as $a){
			$pre = str_replace("tbl_","",$a->table);
			$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$id}' AND {$pre}_deleted IS NULL ";// AND article_deleted IS NULL";
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

		return  $listing_f;
	}

	function getListingBuilder($id){
		global $SMARTY,$DBobject;
		$listing_f = array();
		$sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' AND listing_deleted IS NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ";
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

	function getListingList($category_id=null){
		global $SMARTY,$DBobject;
		$records = array();
	
		//ONLY DO THIS FOR THE TOP LEVEL
		if(empty($category_id)){
			$category_id = 0;
			$hierarchy = $this->CONFIG_OBJ->hierarchy;
			if(!empty($hierarchy) && !empty($hierarchy->attributes()->field) && ((integer)$hierarchy->attributes()->default) >= 0){
				$local_field = $hierarchy->attributes()->field;
				$category_id = ((integer)$hierarchy->attributes()->default);
				$sql = "SELECT tbl_listing.*, tbl_category.category_id FROM {$this->DBTABLE}
				WHERE tbl_category.category_parent_id = :cat_id AND
				tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ORDER BY tbl_listing.listing_order ASC";
			}else{
				$sql = "SELECT tbl_listing.*, tbl_category.category_id FROM {$this->DBTABLE}
				WHERE tbl_listing.listing_category_id = :cat_id AND
				tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ORDER BY tbl_listing.listing_order ASC";
			}
			
			$params = array(":cat_id"=>$category_id);
			if($res = $DBobject->wrappedSqlGet($sql,$params)){
				foreach ($res as $key => $val) {
					if($val['category_id'] != null || $val['category_id'] != ""){
						$subs = $this->getListingList($val['category_id']);
					}
					$records["{$val['listing_id']}"]  = array("title"=>$val['listing_name'],"id"=>$val['listing_id'],"subs"=>$subs);
				}
			}
		}else{
			$sql = "SELECT tbl_listing.*, tbl_category.category_id FROM {$this->DBTABLE}
			WHERE tbl_listing.listing_category_id = :cat_id AND tbl_listing.listing_type_id = :type AND
			tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ORDER BY tbl_listing.listing_order ASC";
			$params = array(":cat_id"=>$category_id,":type"=>$this->TYPE_ID);
			if($res = $DBobject->wrappedSqlGet($sql,$params)){
				foreach ($res as $key => $val) {
					$subs = array();
					if($val['category_id'] != null || $val['category_id'] != ""){
						$subs = $this->getListingList($val['category_id']);
					}
					$records["{$val['listing_id']}"] = array("title"=>$val['listing_name'],"id"=>$val['listing_id'],
						"url"=>"/admin/edit/{$this->CONFIG_OBJ->url}/{$val['listing_id']}",
							"url_delete"=>"/admin/delete/{$this->CONFIG_OBJ->url}/{$val['listing_id']}",
									"subs"=>$subs);
				}
			}
		}
	
		return  $records;
	}
	
	function getParentList(){
		
	}

	function deleteListing($id){
		global $DBobject;
		$sql = "UPDATE {$this->DBTABLE} SET listing_deleted = NOW() WHERE listing_id = '{$id}' ";
		if($DBobject->executeSQL($sql)){
			global $DELETED;
			$_SESSION['notice']= $DELETED;
			return true;
		}
		return false;
	}


}