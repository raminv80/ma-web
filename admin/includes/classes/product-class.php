<?php

Class Product extends Record{
	
	function __construct($_config){
		parent::__construct($_config);
	}
	
	function getRecordList($parent_id='0'){
		global $SMARTY,$DBobject;
		$records = array();
		
		$sql = "SELECT * FROM tbl_listing
		WHERE listing_parent_id = :pid AND tbl_listing.listing_type_id = :type AND
		tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL ORDER BY tbl_listing.listing_order ASC"; 
		$params = array (
				":type" => $this->CONFIG->type_id,
				":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet($sql,$params)) {
			foreach ( $res as $key => $val ) {
				
				$subs = array ();
				$subs = $this->subList( $val ['listing_id']);
				$subs = array_merge_recursive($subs,$this->getRecordList( $val ['listing_id']));
				if ($val ['listing_type_id'] == $this->CONFIG->type_id) {
					$records ["l{$val['listing_id']}"] = array (
							"title" => $val ['listing_name'],
							"id" => $val ['listing_id'],
							"subs" => $subs
					);
				}
			}
		}
		return  $records;
	}
	
	private function subList($parent_id='0'){
		global $SMARTY,$DBobject;
		$records = array();
		$tfields="";
		foreach($this->FIELD as $f){
			$tfields .= "{$f},";
		}
		$sql = "SELECT {$tfields} {$this->TABLE}.* FROM {$this->TABLE} WHERE {$this->DELETED} IS NULL  AND product_listing_id = :pid ".($this->WHERE!=''?"AND {$this->WHERE} ":" ")." ".($this->ORDERBY!=''?" ORDER BY {$this->ORDERBY} ":" ");
		$params = array (
				":pid" => $parent_id
		);
		if($res = $DBobject->wrappedSqlGet($sql,$params)){
			foreach ($res as $key => $val) {
				$n = 0;
				$title = "";
				foreach($this->FIELD as $f){
					$title .= ($n>0?", ":"").$val["{$f}"];
					$n++;
				}
				$records[$val["{$this->ID}"]] = array("title"=>$title,"id"=>$val["{$this->ID}"],"url"=>"/admin/edit/{$this->URL}/{$val["{$this->ID}"]}","url_delete"=>"/admin/delete/{$this->URL}/{$val["{$this->ID}"]}");
			}
		}
		return  $records;
	}
}