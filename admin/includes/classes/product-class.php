<?php
class Product {
	protected $CONFIG_OBJ;
	private $DBTABLE;
	private $DBPRODTABLE;
	// URL value passed into constructor
	private $TYPE_ID;
	// SQL ELEMENTS
	protected $SELECT = "*";
	protected $TABLES = "";
	protected $WHERE = "";
	protected $ORDERBY = "";
	protected $GROUPBY = "";
	protected $LIMIT = "";
	// SET OF DATA LOADED
	protected $DATA;
	function __construct($_sp) {
		global $SMARTY, $DBobject;
		$this->CONFIG_OBJ = $_sp;
		$this->TYPE_ID = $_sp->type_id;
		
		$this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id";
		
		$this->DBPRODTABLE = " tbl_product
							LEFT JOIN tbl_attribute ON product_id = attribute_product_id 
							LEFT JOIN tbl_attr_value ON attr_value_attribute_id = attribute_id";
		foreach ( $_sp->extends as $extend ) {
			$this->DBTABLE .= " LEFT JOIN {$extend->table} ON {$extend->table}.{$extend->field} = tbl_listing.listing_id ";
			$this->DBPRODTABLE .= " LEFT JOIN {$extend->table} ON {$extend->field} = product_id ";
			$this->WHERE .= $extend->where != "" ? "  {$extend->where} " : "";
		}
	}
	function getProduct($id) {
		global $SMARTY, $DBobject;
		$listing_f = array ();
		$sql = "SELECT * FROM {$this->DBPRODTABLE} WHERE product_id = '{$id}' AND product_deleted IS NULL " . ($this->WHERE != '' ? "AND {$this->WHERE} " : " ") . " ";
		if ($res = $DBobject->wrappedSqlGet ( $sql )) {
			foreach ( $res as $row ) {
				foreach ( $row as $key => $field ) {
					if (! is_numeric ( $key )) {
						if (! in_array ( $field, $listing_f ["{$key}"] ) && $listing_f ["{$key}"] != $field) {
							if (empty ( $listing_f ["{$key}"] )) {
								$listing_f ["{$key}"] = $field;
							} else {
								if (! is_array ( $listing_f ["{$key}"] )) {
									$temp = $listing_f ["{$key}"];
									$listing_f ["{$key}"] = array (
											$temp 
									);
								}
								$listing_f ["{$key}"] [] = $field;
							}
						}
					}
				}
			}
		}
		foreach ( $this->CONFIG_OBJ->options->field as $f ) {
			if ($f->attributes()->recursive) { 
				$listing_f ['options'] ["{$f->name}"] = $this->getOptionsCatTree($f, 0);
			} else {
				$pre = str_replace ( "tbl_", "", $f->table );
				$sql = "SELECT {$pre}_id,{$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL " . ($f->where != '' ? "AND {$f->where} " : "") . " " . ($f->orderby != '' ? " ORDER BY {$f->orderby} " : ""); 
				if ($res = $DBobject->wrappedSqlGet ( $sql )) {
					foreach ( $res as $key => $row ) {
						$listing_f ['options'] ["{$f->name}"] ["{$row ["{$pre}_id"]}"] = array (
								'id' => $row ["{$pre}_id"],
								'value' => $row ["{$f->reference}"] 
						);
					}
				}
			}
		}
		foreach ( $this->CONFIG_OBJ->associated as $a ) {
			$listing_f ["{$a->name}"] = $this->getAssociated($a, $id);
		}
		foreach ( $this->CONFIG_OBJ->extends as $a ) {
			$pre = str_replace ( "tbl_", "", $a->table );
			$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$id}' AND {$pre}_deleted IS NULL "; // AND article_deleted IS NULL";
			if ($res = $DBobject->wrappedSqlGet ( $sql )) {
				foreach ( $res [0] as $key => $field ) {
					$r_array ["{$key}"] = $field;
				}
				$listing_f ["{$a->name}"] [] = $r_array;
			}
		}
		
		return $listing_f;
	}
	function getOptionsCatTree($f, $pid){
		global $SMARTY,$DBobject;
		$results = array();
	
		$pre = str_replace("tbl_","",$f->table);
		$sql = "SELECT {$pre}_id, {$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL AND {$pre}_parent_id = {$pid} " . ($f->where != '' ? "AND {$f->where} " : "") . ($f->orderby != '' ? " ORDER BY {$f->orderby} " : "");
	
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res as $row) {
				$results[] = array (
						'id' => $row ["{$pre}_id"],
						'value' => $row ["{$f->reference}"],
						'subs' => $this->getOptionsCatTree($f, $row["{$pre}_id"])
				);
			}
				
		}
		return $results;
	}
	function getAssociated($a,$id){
		global $SMARTY,$DBobject;
		$results = array();
	
		$order = "";
		if (! empty ( $a->orderby )) {
			$order = " ORDER BY " . $a->orderby;
		}
	
		$pre = str_replace("tbl_","",$a->table);
		$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$id}' AND {$pre}_deleted IS NULL ".$order;
		if($res = $DBobject->wrappedSqlGet($sql)){
			foreach ($res as $row) {
				$r_array = array();
				foreach($row as $key =>$field){
					$r_array["{$key}"] = $field;
				}
	
				foreach($a->associated as $as){
					$r_array["{$as->name}"] = $this->getAssociated($as, $row["{$a->id}"]);
				}
				$results[] = $r_array;
			}
		}
		return $results;
	}
	function getListingProductList($parent_id='0') {
		global $SMARTY, $DBobject;
		$records = array ();

		// ------------- Products List ------------------
		$order = " ORDER BY product_order ASC, product_name ASC";
		if (! empty ( $this->CONFIG_OBJ->orderby )) {
			$order = " ORDER BY " . $this->CONFIG_OBJ->orderby;
		}
		
		$sql = "SELECT * FROM {$this->DBPRODTABLE}
		WHERE product_listing_id = :pid AND product_listing_id IS NOT NULL AND	product_deleted IS NULL " . ($this->WHERE != '' ? "AND {$this->WHERE} " : " ") .$order;
		
		$params = array (
				":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
			foreach ( $res as $key => $val ) {
				$records ["p{$val['product_id']}"] = array (
					"title" => $val ['product_name'],
					"id" => $val ['product_id'],
					"url" => "/admin/edit/{$this->CONFIG_OBJ->url}/{$val['product_id']}",
					"url_delete" => "/admin/delete/{$this->CONFIG_OBJ->url}/{$val['product_id']}"
					);
			}
		}
		
		// ------------- Product Categories List ------------------
		$order = " ORDER BY tbl_listing.listing_order ASC";
		if (! empty ( $this->CONFIG_OBJ->orderby )) {
			$order = " ORDER BY " . $this->CONFIG_OBJ->orderby;
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
				$subs = $this->getListingProductList ( $val ['listing_id']);
				if ($val ['listing_type_id'] == $this->TYPE_ID) {
					$records ["l{$val['listing_id']}"] = array (
							"title" => $val ['listing_name'],
							"id" => $val ['listing_id'],
							"subs" => $subs 
					);
				}
			}
		}
		return $records;
	}
	function deleteProduct($id) {
		global $DBobject;
		$sql = "UPDATE tbl_product SET product_deleted = NOW() WHERE product_id = '{$id}' ";
		if ($DBobject->executeSQL ( $sql )) {
			global $DELETED;
			$_SESSION ['notice'] = $DELETED;
			return true;
		}
		return false;
	}
}