<?php
class ProductClass extends ListClass {
	private $DBPRODTABLE;
	private $TYPE_ID;
    private $ROOT_PARENT_ID;
    private $IS_PRODUCT;
	
	
	function __construct($_URL, $_CONFIG_OBJ){
		parent::__construct($_URL, $_CONFIG_OBJ);
		$this->TYPE_ID = $_CONFIG_OBJ->type;
        $this->ROOT_PARENT_ID = $_CONFIG_OBJ->root_parent_id;
		$this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id";
		$this->DBPRODTABLE = $_CONFIG_OBJ->producttable->table;
	}
	
	
	function Load($_ID = null, $_PUBLISHED = 1) {
		global $SMARTY, $DBobject;
		$template = "";
		$data = "";
		// Split the URL
		$_url = ltrim ( rtrim ( $this->URL, '/' ), '/' );
	
		if ($arrChk = $this->ChkCache ( $_url )) {
			$this->ID = $arrChk['id'];
			$this->IS_PRODUCT = $arrChk['isProduct'];
			if (!$arrChk['isProduct']) {
				$data = $this->LoadTree ($this->ID);
				$SMARTY->assign ( 'data', unclean ( $data ) );
			} 
		} 
		if(empty($_ID)){
			if(!empty($this->ID) ){
				$_ID = $this->ID;
			}else{
				header ( "Location: /404" );
				die ();
			}
		}
	
	
        //------------ LOAD PRODUCT/CATEGORY DATA, PARENT AND EXTENDS TABLE ----------
        $LOCALCONF = $this->CONFIG_OBJ->table;
        if ($arrChk['isProduct']) {
        	$tableName = 'tbl_product';
        	$parentField = 'product_listing_id';
        	$LOCALCONF = $this->CONFIG_OBJ->producttable;
        } else {
        	$tableName = 'tbl_listing';
        	$parentField = 'listing_parent_id';
        }
        $pre = str_replace ( "tbl_", "", $tableName );
		$extends = "";
		foreach ( $LOCALCONF->extends as $a ) {
			$extends .= " LEFT JOIN {$a->table} ON {$pre}_id = {$a->field}"; 
		}
		$sql = "SELECT * FROM {$tableName} {$extends} WHERE {$pre}_object_id = :id AND {$pre}_deleted IS NULL ORDER BY {$pre}_published = :published DESC ";
		$params = array (
			":id" => $_ID,
			":published" => $_PUBLISHED
		);
		if($res = $DBobject->wrappedSqlGet( $sql, $params )) {
			foreach ( $res[0] as $key => $val ) {
				$SMARTY->assign ( $key, unclean ( $val ) );
			}

			$bdata = $this->LoadBreadcrumb (  $res[0]["{$pre}_object_id"], $arrChk['isProduct'], 1);
			$SMARTY->assign ( "breadcrumbs", $bdata );
			
			$p_data = $this->LoadParents ( $res[0][$parentField], 1);
			$SMARTY->assign ( "listing_parent", $p_data );
			
			//------------- LOAD ASSOCIATED TABLES --------------
			foreach ( $LOCALCONF->associated as $a ) {
				$t_data = $this->LoadAssociated($a, $res[0]["{$a->linkfield}"]);
				$SMARTY->assign ( "{$a->name}", $t_data );	
			}
		}else{
			header ( "Location: /404" );
			die ();
		}
		
		
		//------------- LOAD OPTIONS FOR SELECT INPUTS --------------
		foreach ( $LOCALCONF->options->field as $f ) {
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
			
		 if ($arrChk['isProduct']) {
		 	$template = $this->CONFIG_OBJ->producttable->template;
		}else{
			$template = $this->CONFIG_OBJ->template;
		}
	
		return $template;
	}
	function LoadBreadcrumb($_id, $_isProduct, $_PUBLISHED = 1) {
		global  $DBobject;
		                
        $data = array();
                
        if ($_isProduct) {
                
        	$sql = "SELECT * FROM tbl_listing LEFT JOIN tbl_product ON listing_id = product_listing_id 
                    WHERE product_id = :id AND product_deleted IS NULL AND listing_deleted IS NULL";
            $params = array (
            	":id" => $_id 
            );
                        
            if ($res = $DBobject->wrappedSql ( $sql, $params )) {
            	$pData = array();
                $pData [$res [0] ['product_id']] ["title"] = ucfirst ( unclean ( $res [0] ['product_name'] ) );
                $pData [$res [0] ['product_id']] ["url"] = $res [0] ['product_url'] .'-'. $res [0] ['product_id'];

                $data [$res [0] ['listing_id']] ["title"] = ucfirst ( unclean ( $res [0] ['listing_name'] ) );
                $data [$res [0] ['listing_id']] ["url"] = $res [0] ['listing_url'];
                $data [$res [0] ['listing_id']] ["subs"] = $pData;
                if (! empty ( $res [0] ['listing_parent_id'] ) && $res [0] ['listing_parent_id'] != 0) {
                	$sql2 = "SELECT * FROM tbl_listing WHERE listing_object_id = :cid AND listing_deleted IS NULL ORDER BY listing_published = :published DESC";
                    $params2 = array (
                    	":cid" => $res [0] ['listing_parent_id'],
		        		":published" => $_PUBLISHED
                    );
                    if ($res2 = $DBobject->wrappedSql ( $sql2, $params2 )) { 
                    	$data = parent::LoadBreadcrumb( $res2[0]['listing_id'], $data, $_PUBLISHED );
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
	
	function LoadTree($_cid = 0, $level = 0, $count = 0, $_PUBLISHED = 1) {
		global $CONFIG, $SMARTY, $DBobject;
		$data = array ();
	
		if (! empty ( $this->CONFIG_OBJ->depth ) && intval ( $this->CONFIG_OBJ->depth ) > 0 && intval ( $this->CONFIG_OBJ->depth ) <= $level) {
			return $data;
		}
	
		// GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		$filter = "";
		$filter_params = array();
		$f_count = 1;
		if (!empty($this->CONFIG_OBJ->filter)) {
			foreach($this->CONFIG_OBJ->filter as $f) {
				if (!empty($f->field) && !empty($f->value)) {
					$filter .= " AND ".$f->field." = :filter{$f_count}";
					$filter_params[":filter{$f_count}"] = $f->value;
					$f_count++;
				}
			}
		}
		if(!empty($filter)){
			$this->CONFIG_OBJ->limit = 0;
		}
	
	
		$order = " ORDER BY tbl_listing.listing_order ASC";
		if (! empty ( $this->CONFIG_OBJ->orderby )) {
			$order = " ORDER BY " . $this->CONFIG_OBJ->orderby;
		}
		$extends = "";
		foreach ( $this->CONFIG_OBJ->table->extends as $a ) {
			$pre = str_replace ( "tbl_", "", $a->table );
			$extends = " LEFT JOIN {$a->table} ON tbl_listing.listing_id = {$a->field}"; 
		}
		
		
		//---------------------- GET PRODUCTS CHILDREN
		$prod_extends = "";
		foreach ( $this->CONFIG_OBJ->producttable->extends as $a ) {	// extends product table
			$pre = str_replace ( "tbl_", "", $a->table );
			$prod_extends = " LEFT JOIN {$a->table} ON product_id = {$a->field}";
		}
		
		$prod_order = "";
		if (! empty ( $this->CONFIG_OBJ->producttable->orderby )) { 
			$prod_order = " ORDER BY " . $this->CONFIG_OBJ->producttable->orderby;
		}
		
		$sql = "SELECT * FROM tbl_product {$prod_extends} WHERE product_listing_id = :cid AND product_deleted IS NULL AND product_published = :published" . $prod_order;
		$params = array (
				":cid" => $_cid,
		    	":published" => $_PUBLISHED  
		);
		
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			foreach ( $res as $row ) {
				$data ['products']["{$row['product_id']}"] = unclean ( $row );
				foreach ( $this->CONFIG_OBJ->producttable->associated as $a ) {
					$data ['products']["{$row['product_id']}"] ["{$a->name}"] = $this->LoadAssociated($a,$row["{$a->linkfield}"]);
				}
			}
		}
		//-------------------------------------------------
		
		$sql = "SELECT * FROM tbl_listing {$extends} WHERE tbl_listing.listing_parent_id = :cid AND tbl_listing.listing_type_id = :type AND tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1" . $filter . $order;
		$params = array (
				":cid" => $_cid,
				":type" => $this->CONFIG_OBJ->type
		);
		$params = array_merge($params,$filter_params);
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			foreach ( $res as $row ) {
				if($this->CONFIG_OBJ->limit && $count >= intval($this->CONFIG_OBJ->limit)){
					return $data;
				}
				$count += 1;
	
				$data ['listings']["{$row['listing_object_id']}"] = unclean ( $row );
				foreach ( $this->CONFIG_OBJ->table->associated as $a ) {
					if ($a->attributes ()->listing) {
						$data ["{$row['listing_object_id']}"] ["{$a->name}"] = $this->LoadAssociated($a,$row["{$a->linkfield}"]);
					}
				}
				$subs = self::LoadTree ( $row ['listing_object_id'], $level ++ ,$count, $_PUBLISHED);
				$data ["{$row['listing_object_id']}"] ['listings'] = $subs;
			}
		}
	
		return $data;
	}
	
	function ChkCache($_url) {
		global $SMARTY, $DBobject;
		$args = explode ( '/', $_url );
		$a = end ($args);
		
		$sql = "SELECT cache_record_id FROM cache_tbl_product WHERE cache_url = :url"; // Check for PRODUCTS
		$params = array (
				":url" => $_url
		);
        $isProduct = true;
		try {
			$row = $DBobject->wrappedSql ( $sql, $params );
            if (empty ( $row )) {
				$sqlCat = "SELECT cache_record_id FROM cache_tbl_listing WHERE cache_url = :url"; // Check for CATEGORIES
				$row = $DBobject->wrappedSql ( $sqlCat, $params );
				if (empty ( $row )) {
					$sqlp = "SELECT product_object_id FROM tbl_product WHERE CONCAT(product_url, '-', product_object_id) = :url";
					$params2 = array (
							":url" => $a
					);
					if ($res = $DBobject->wrappedSql ( $sqlp, $params2 )) {// Build Product cache 
						$this->BuildCache();
						$row = $DBobject->wrappedSql ( $sql, $params );
                                                
						
					} else {
						$sqlc = "SELECT listing_url FROM tbl_listing WHERE listing_url = :url";
						if ($res = $DBobject->wrappedSql ( $sqlc, $params2 )) { // Build Cat - Listing cache
							parent::BuildCache();
							$row = $DBobject->wrappedSql ( $sqlCat, $params );
							$isProduct = false;
						}
					}
				} else {
					$isProduct = false;
				}
			}
		} catch ( Exception $e ) {
			$sqlCat = "SELECT cache_record_id FROM cache_tbl_listing WHERE cache_url = :url"; // Check for CATEGORIES
                        $row = $DBobject->wrappedSql ( $sqlCat, $params );
                        if (empty ( $row )) {
                                $sqlp = "SELECT product_object_id FROM tbl_product WHERE CONCAT(product_url, '-', product_object_id) = :url";
								$params2 = array (
                                                ":url" => $a
                                );
                                if ($res = $DBobject->wrappedSql ( $sqlp, $params2 )) { // Build Product cache
                                        $this->BuildCache();
                                        $row = $DBobject->wrappedSql ( $sql, $params );


                                } else {
                                    $sqlc = "SELECT listing_url FROM tbl_listing WHERE listing_url = :url";
                                    if ($res = $DBobject->wrappedSql ( $sqlc, $params2 )) { // Build Cat - Listing cache
                                        parent::BuildCache();
                                        $row = $DBobject->wrappedSql ( $sqlCat, $params );
                                        $isProduct = false;
                                    }
                                }
                        } else {
                                $isProduct = false;
                        }
		}
		if (! empty ( $row )) {
			return array("id"=>$row[0]['cache_record_id'],"isProduct"=>$isProduct);
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
		$sql [2] = "INSERT INTO cache_tbl_product (cache_record_id,cache_published,cache_url,cache_modified) VALUES  ";
		$params = array ();
		$sql [1] = "SELECT product_object_id AS id, product_url AS url, product_listing_id, product_published, AS pid FROM tbl_product WHERE product_deleted IS NULL";
		$res = $DBobject->wrappedSql ( $sql [1] );
		$n = 1;
	
		foreach ( $res as $row ) {
			$id = $row ['id'];
			$url = $row ['url'] . '-' . $row ['id'];
			$published = $row ['product_published'];
			if (! $this->BuildUrl( $row ['pid'], $url )) { 
				continue;
			}
				
			$sql [2] .= " ( :id{$n}, :title{$n}, now() ) ,";
			$params = array_merge ( $params, array (
					":id{$n}" => $id,
					":published{$n}" => $published, 
					":title{$n}" => $url
			) );
			$n ++;
		}
	
		$sql [2] = trim ( trim ( $sql [2] ), ',' );
		$sql [2] .= ";";
	
		$DBobject->wrappedSql ( $sql [3] );
		$DBobject->wrappedSql ( $sql [2], $params );
	}
	

	function LoadAssociatedByTag($cfg) {
		global $CONFIG, $SMARTY, $DBobject;
		
		if (!$this->IS_PRODUCT) {
			parent::LoadAssociatedByTag($cfg);
		} else {
			foreach ( $cfg->producttable->tags as $a ) {
				$preObjTbl = str_replace ( "tbl_", "", $a->object_table );
				$sql = "SELECT  {$a->object_value} AS VALUE  FROM {$a->object_table} WHERE {$preObjTbl}_id = :id AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 ";
				$params = array (
						":id" => $this->ID 
				);
				if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
					
					$group = "";
					$order = "";
					$limit = "";
					$where = "";
					if (! empty ( $a->groupby )) {
						$group = " GROUP BY " . $a->groupby;
					}
					if (! empty ( $a->orderby )) {
						$order = " ORDER BY " . $a->orderby;
					}
					if (! empty ( $a->limit )) {
						$limit = " LIMIT " . $a->limit;
					}
					if (! empty ( $a->where )) {
						$where = " AND " . $a->where;
					}
					
					$pre = str_replace ( "tbl_", "", $a->table );
					$sql = "SELECT {$a->object_table}.* FROM {$a->table} LEFT JOIN {$a->object_table} ON  {$preObjTbl}_id = {$pre}_object_id
					WHERE {$pre}_object_table = :objTable AND {$pre}_value = :objValue  AND {$pre}_deleted IS NULL
					AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 " . $where . " " . $group . " " . $order . " " . $limit;
					$params = array (
							':objTable' => $a->object_table,
							':objValue' => $res [0] ["VALUE"] 
					);
					
					$data = array ();
					if ($objs = $DBobject->wrappedSqlGet ( $sql, $params )) {
						foreach ( $objs as $o ) {
							$data ["{$o["{$preObjTbl}_id"]}"] = $o;
							$url = $o ["{$preObjTbl}_url"] . '-' . $o ["{$preObjTbl}_id"];
							$this->BuildUrl ( $o ["{$preObjTbl}_listing_id"], $url );
							$data ["{$o["{$preObjTbl}_id"]}"]["absolute_url"] = $url;
							
							foreach ( $cfg->producttable->associated as $b ) {
								$data ["{$o["{$preObjTbl}_id"]}"] ["{$b->name}"] = $this->LoadAssociated ( $b, $o["{$b->linkfield}"] );
							}
						}
					}
					$SMARTY->assign ( "{$a->name}", $data );
				}
			}
		}
	}
	
	
	/**
	 * Return array with products and categories starting by a given parent_id (product_listing_id as product category)
	 * 
	 * @param int $parent_id
	 * @param boolean $firstTime
	 * @return array
	 */
	function getProductList($parent_id='0', $firstTime = false){
		global $SMARTY,$DBobject;
	
		$list = array();
		$rootUrl = '';
		$params = array (
				":pid" => $parent_id
		);
	
		if ($parent_id <> '0' && $firstTime) {
			$sql = "SELECT listing_url FROM tbl_listing WHERE listing_id = :pid AND listing_deleted IS NULL ";
			$res = $DBobject->wrappedSqlGetSingle ( $sql, $params );
			$rootUrl = '/' . $res['listing_url'];
		}
	
		// ------------- Products List ------------------
		$order = " ORDER BY product_order ASC, product_name ASC";
	
		$sql = "SELECT * FROM {$this->DBPRODTABLE}
		WHERE product_listing_id = :pid AND product_listing_id IS NOT NULL AND product_deleted IS NULL AND product_published = 1 " . $order;
	
	
	
		if ($res = $DBobject->wrappedSqlGet ( $sql, $params )) {
		foreach ( $res as $key => $val ) {
		$list ["p{$val['product_id']}"] = array (
		"title" => $val ['product_name'],
		"id" => $val ['product_id'],
		"url" => "{$rootUrl}/{$val['product_url']}-{$val['product_id']}"
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
								"url" => "{$rootUrl}/{$val ['listing_url']}",
								"subs" => $subs
						);
				}
			}
		}
		return  $list;
	}
	
	
}