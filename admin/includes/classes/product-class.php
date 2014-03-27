<?php

Class Product extends Record{
	private $DBPRODTABLE;
	private $TYPE_ID;
	
	function __construct($_config){
		parent::__construct($_config);
		$this->TYPE_ID = $_config->type_id;
		$this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id";
		$this->DBPRODTABLE = " tbl_product
							LEFT JOIN tbl_attribute ON product_id = attribute_product_id 
							LEFT JOIN tbl_attr_value ON attr_value_attribute_id = attribute_id";
	}
	
	function getRecordList($parent_id='0'){
		global $SMARTY,$DBobject;
		$records = array();
		
		// ------------- Products List ------------------
		$order = " ORDER BY product_order, product_name";
		if (! empty ( $this->CONFIG->orderby )) {
			$order = " ORDER BY " . $this->CONFIG->orderby;
		}
		
		$sql = "SELECT * FROM {$this->DBPRODTABLE}
		WHERE product_listing_id = :pid AND product_listing_id IS NOT NULL AND	product_deleted IS NULL " . ($this->WHERE != '' ? "AND {$this->WHERE} " : " ") .$order;
		
		$params = array (
				":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
			foreach ( $res as $key => $val ) {
				$p_url = '/';
				if($val['product_published'] == 0){
					$p_url .= 'draft/';
				}
				$records ["p{$val['product_id']}"] = array (
						"title" => $val ['product_name'],
						"id" => $val ['product_id'],
						"published" => $val ['product_published'],
						"preview_url" => $p_url . $this->getCachedUrl($val['product_object_id']),
						"url" => "/admin/edit/{$this->CONFIG->url}/{$val['product_id']}",
						"url_delete" => "/admin/delete/{$this->CONFIG->url}/{$val['product_id']}"
				);
			}
		}
		
		// ------------- Product Categories List ------------------
		$order = " ORDER BY tbl_listing.listing_order ASC";
		if (! empty ( $this->CONFIG->orderby )) {
				$order = " ORDER BY " . $this->CONFIG->orderby;
		}
		
		$sql = "SELECT * FROM {$this->DBTABLE}
				WHERE listing_parent_id = :pid AND tbl_listing.listing_type_id = :type AND
				tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL " . ($this->WHERE != '' ? "AND {$this->WHERE} " : " ") .$order;
		
		$params = array (
			":type" => $this->TYPE_ID,
			":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
			foreach ( $res as $key => $val ) {
					$subs = array ();
					$subs = $this->getRecordList ( $val ['listing_object_id']);
					if ($val ['listing_type_id'] == $this->TYPE_ID) {
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
	
	function getCachedUrl($id) {
		global $DBobject;
		$sql = "SELECT cache_url FROM cache_tbl_product WHERE cache_record_id = :id";
		if ($res = $DBobject->executeSQL ($sql,array(':id'=>$id))) {
			return $res[0]['cache_url'];
		}
		return '';
	}

}