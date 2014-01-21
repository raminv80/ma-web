<?php
class ProductClass extends ListClass {
	private $DBPRODTABLE;
	private $TYPE_ID;
        private $ROOT_PARENT_ID;
	
	
	function __construct($_URL, $_CONFIG_OBJ){
		parent::__construct($_URL, $_CONFIG_OBJ);
		$this->TYPE_ID = $_CONFIG_OBJ->type;
        $this->ROOT_PARENT_ID = $_CONFIG_OBJ->root_parent_id;
		$this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id";
		$this->DBPRODTABLE = " tbl_product
							LEFT JOIN tbl_attribute ON product_id = attribute_product_id
							LEFT JOIN tbl_attr_value ON attr_value_attribute_id = attribute_id";
	}
	
	function getProductList($parent_id='0'){
		global $SMARTY,$DBobject;
		$list = array();
		
		// ------------- Products List ------------------
		$order = " ORDER BY product_order ASC, product_name ASC";
		
		$sql = "SELECT * FROM {$this->DBPRODTABLE}
		WHERE product_listing_id = :pid AND product_listing_id IS NOT NULL AND	product_deleted IS NULL " . $order;
		
		$params = array (
				":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
			foreach ( $res as $key => $val ) {
				$pUrl = $val['product_id'] - $val['product_name'];
				$list ["p{$val['product_id']}"] = array (
						"title" => $val ['product_name'],
						"id" => $val ['product_id'],
						"url" => "/{$val['product_id']}-{$val['product_url']}"
				);
			}
		}
		
		// ------------- Product Categories List ------------------
		$order = " ORDER BY tbl_listing.listing_order ASC";
		
		$sql = "SELECT * FROM {$this->DBTABLE}
				WHERE listing_parent_id = :pid AND tbl_listing.listing_type_id = :type AND
				tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL " . $order;
		
		$params = array (
			":type" => $this->TYPE_ID,
			":pid" => $parent_id
		);
		
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
			foreach ( $res as $key => $val ) {
					$subs = array ();
					$subs = $this->getProductList ( $val ['listing_id']);
					if ($val ['listing_type_id'] == $this->TYPE_ID) {
						$list ["l{$val['listing_id']}"] = array (
												"title" => $val ['listing_name'],
												"id" => $val ['listing_id'],
												"url" => "/{$val ['listing_url']}",
												"subs" => $subs
						);
					}
			}
		}
		return  $list;
	}
	
	
	function Load($_ID = null) {
		global $SMARTY, $DBobject;
		$template = "";
		$data = "";
		// Split the URL
		$_url = ltrim ( rtrim ( $this->URL, '/' ), '/' );
	
		if ($arrChk = $this->ChkCache ( $_url )) {
			$id = $arrChk['id'];
			$data = $this->LoadTree ($id);
			$SMARTY->assign ( 'data', unclean ( $data ) );
		} 
		if(empty($_ID)){
			if(!empty($id) ){
				$_ID = $id;
			}else{
				header ( "Location: /404" );
				die ();
			}
		}
	
       	$bdata = $this->LoadBreadcrumb ( $_ID, $arrChk['type']);
        $SMARTY->assign ( "breadcrumbs", $bdata );
	
		$extends = "";
		foreach ( $this->CONFIG_OBJ->table->extends as $a ) {
			$pre = str_replace ( "tbl_", "", $a->table );
			$extends = " LEFT JOIN {$a->table} ON tbl_listing.listing_id = {$a->field}"; 
		}
		$sql = "SELECT * FROM tbl_listing {$extends} WHERE tbl_listing.listing_id = :id AND tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 ";
		$params = array (
				":id" => $_ID
		);
		if($res = $DBobject->wrappedSqlGet( $sql, $params )) {
			foreach ( $res[0] as $key => $val ) {
				$SMARTY->assign ( $key, unclean ( $val ) );
			}
			$p_data = $this->LoadParents ( $res[0]['listing_parent_id'] );
			$SMARTY->assign ( "listing_parent", $p_data );
		}else{
			header ( "Location: /404" );
			die ();
		}
			
		foreach ( $this->CONFIG_OBJ->table->associated as $a ) {
                        if ($arrChk['type'] == 'product') {
                            $t_data = $this->LoadAssociated($a->associated,$_ID);
                        } else {
                            $t_data = $this->LoadAssociated($a,$_ID);
                        }
                        $SMARTY->assign ( "{$a->name}", $t_data );
			
		}
		
		foreach ( $this->CONFIG_OBJ->table->options->field as $f ) {
			if ($f->attributes()->recursive) {
				$options = $this->getOptionsCatTree($f, $this->ROOT_PARENT_ID );
			} else {
				$pre = str_replace ( "tbl_", "", $f->table );
				$sql = "SELECT {$pre}_id,{$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL " . ($f->where != '' ? "AND {$f->where} " : "") . " " . ($f->orderby != '' ? " ORDER BY {$f->orderby} " : "");
				if ($res = $DBobject->wrappedSqlGet ( $sql )) {
					$options = array();
					foreach ( $res as $key => $row ) {
						$options[] = array (
								'id' => $row ["{$pre}_id"],
								'value' => $row ["{$f->reference}"]
						);
					}
				}
			}
			$SMARTY->assign ( "{$f->name}", $options);
		}
			
		if(!empty($data)){
			$template = $this->CONFIG_OBJ->template;
		}else{
			$template = $this->CONFIG_OBJ->table->template;
		}
	
		return $template;
	}
	function LoadBreadcrumb($_id, $_type) {
		global  $DBobject;
		                
        $data = array();
                
        if ($_type == 'product') {
                
        	$sql = "SELECT * FROM tbl_listing LEFT JOIN tbl_product ON listing_id = product_listing_id 
                                WHERE product_id = :id AND product_deleted IS NULL AND listing_deleted IS NULL";
            $params = array (
            			":id" => $_id
            );
                        
            if ($res = $DBobject->wrappedSql ( $sql, $params )) {
            	$pData = array();
                $pData [$res [0] ['product_id']] ["name"] = ucfirst ( unclean ( $res [0] ['product_name'] ) );
                $pData [$res [0] ['product_id']] ["url"] = $res [0] ['product_url'] .'-'. $res [0] ['product_id'];

                $data [$res [0] ['listing_id']] ["title"] = ucfirst ( unclean ( $res [0] ['listing_title'] ) );
                $data [$res [0] ['listing_id']] ["url"] = $res [0] ['listing_url'];
                $data [$res [0] ['listing_id']] ["subs"] = $pData;
                if (! empty ( $res [0] ['listing_parent_id'] ) && $res [0] ['listing_parent_id'] != 0) {
                	$sql2 = "SELECT * FROM tbl_listing WHERE tbl_listing.listing_id = :cid AND tbl_listing.listing_deleted IS NULL";
                    $params2 = array (
                    	":cid" => $res [0] ['listing_parent_id']
                    );
                    if ($res2 = $DBobject->wrappedSql ( $sql2, $params2 )) { 
                    	$data = parent::LoadBreadcrumb( $res2[0]['listing_id'], $data );
					}
            	}
			}
		} else {
			$data = parent::LoadBreadcrumb($_id);
		}
		return $data;
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
						'subs' => self::getOptionsCatTree($f, $row["{$pre}_id"])
				);
			}
			
		} 
		return $results;
	}
	

	function ChkCache($_url) {
		global $SMARTY, $DBobject;
		$args = explode ( '/', $_url );
		$a = end ($args);
		$sql = "SELECT cache_record_id FROM cache_tbl_product WHERE cache_url = :url"; // Check for PRODUCTS
		$params = array (
				":url" => $_url
		);
                $type = 'product';
		try {
			$row = $DBobject->wrappedSqlGetSingle ( $sql, $params );
                        if (empty ( $row )) {
				$sqlCat = "SELECT cache_record_id FROM cache_tbl_listing WHERE cache_url = :url"; // Check for CATEGORIES
				$row = $DBobject->wrappedSqlGetSingle ( $sqlCat, $params );
				if (empty ( $row )) {
					$sqlp = "SELECT CONCAT(product_url, '-', product_id) FROM tbl_product WHERE product_url = :url";
					$params2 = array (
							":url" => $a
					);
					if ($res = $DBobject->wrappedSqlGet ( $sqlp, $params2 )) { // Build Product cache
						$this->BuildCache();
						$row = $DBobject->wrappedSqlGetSingle ( $sql, $params );
                                                
						
					} else {
                                            $sqlc = "SELECT listing_url FROM tbl_listing WHERE listing_url = :url";
                                            if ($res = $DBobject->wrappedSqlGet ( $sqlc, $params2 )) { // Build Cat - Listing cache
						parent::BuildCache();
						$row = $DBobject->wrappedSqlGetSingle ( $sqlCat, $params );
                                                $type = 'category';
                                            }
					}
				} else {
					$type = 'category';
				}
			}
		} catch ( Exception $e ) {
			$sqlCat = "SELECT cache_record_id FROM cache_tbl_listing WHERE cache_url = :url"; // Check for CATEGORIES
                        $row = $DBobject->wrappedSqlGetSingle ( $sqlCat, $params );
                        if (empty ( $row )) {
                                $sqlp = "SELECT CONCAT(product_url, '-', product_id) FROM tbl_product WHERE product_url = :url";
                                $params2 = array (
                                                ":url" => $a
                                );
                                if ($res = $DBobject->wrappedSqlGet ( $sqlp, $params2 )) { // Build Product cache
                                        $this->BuildCache();
                                        $row = $DBobject->wrappedSqlGetSingle ( $sql, $params );


                                } else {
                                    $sqlc = "SELECT listing_url FROM tbl_listing WHERE listing_url = :url";
                                    if ($res = $DBobject->wrappedSqlGet ( $sqlc, $params2 )) { // Build Cat - Listing cache
                                        parent::BuildCache();
                                        $row = $DBobject->wrappedSqlGetSingle ( $sqlCat, $params );
                                        $type = 'category';
                                    }
                                }
                        } else {
                                $type = 'category';
                        }
		}
		if (! empty ( $row )) {
			return array("id"=>$row ['cache_record_id'],"type"=>$type);
		}
		return false;
	}
	function BuildCache() {
		global $SMARTY, $DBobject;
		$sql [0] = "CREATE TABLE IF NOT EXISTS `cache_tbl_product` (
		`cache_id` INT(11) NOT NULL AUTO_INCREMENT,
		`cache_record_id` INT(11) DEFAULT NULL,
		`cache_url` VARCHAR(255) DEFAULT NULL,
		`cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
		`cache_modified` DATETIME DEFAULT NULL,
		`cache_deleted` DATETIME DEFAULT NULL,
		PRIMARY KEY (`cache_id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;";
		$DBobject->wrappedSql ( $sql [0] );
	
		$sql [3] = "TRUNCATE cache_tbl_product;";
		$sql [2] = "INSERT INTO cache_tbl_product (cache_record_id,cache_url,cache_modified) VALUES  ";
		$params = array ();
		$sql [1] = "SELECT product_id AS id, product_url AS url, product_listing_id AS pid FROM tbl_product WHERE product_published = '1' AND product_deleted IS NULL";
		$res = $DBobject->wrappedSql ( $sql [1] );
		$n = 1;
	
		foreach ( $res as $row ) {
			$id = $row ['id'];
			$url = $row ['url'] . '-' . $row ['id'];
			if (! $this->BuildUrl( $row ['pid'], $url )) {
				continue;
			}
				
			$sql [2] .= " ( :id{$n}, :title{$n}, now() ) ,";
			$params = array_merge ( $params, array (
					":id{$n}" => $id,
					":title{$n}" => $url
			) );
			$n ++;
		}
	
		$sql [2] = trim ( trim ( $sql [2] ), ',' );
		$sql [2] .= ";";
	
		$DBobject->wrappedSql ( $sql [3] );
		$DBobject->wrappedSql ( $sql [2], $params );
	}
	
	
	
}