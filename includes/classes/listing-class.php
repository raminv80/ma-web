<?php
class ListClass{

	protected $CONFIG_OBJ;
	protected $DBTABLE;
	//URL value passed into constructor
	private $URL;
	//SQL ELEMENTS
	protected $SELECT = "*";
	protected $TABLES = "";
	protected $WHERE = "";
	protected $ORDERBY = "";
	protected $GROUPBY = "";
	protected $LIMIT = "";
	//SET OF DATA LOADED
	protected $DATA;
	
	protected $ID;

	function __construct($_URL,$_CONFIG_OBJ){
		global $SMARTY,$DBobject;
		$this->URL = $_URL;
		$this->CONFIG_OBJ = $_CONFIG_OBJ;
		//$this->DBTABLE = new Table($this->CONFIG_OBJ->ID);
		$this->init();
	}

	/**
	 * Initialise values based on the CONFIG. This function will initialise the TABLE joins
	 */
	protected function init(){
		foreach($this->CONFIG_OBJ->table as $t){
			$_tables = $t->name;
			$tbl_comp = str_replace("tbl_","",$t->name,$count);
			$deleted_name = $tbl_comp."_deleted";
			$publish_name = $tbl_comp."_published";
			$type = $tbl_comp."_type_id";

			$this->WHERE = " {$deleted_name} IS NULL AND {$publish_name} = 1 AND {$type} = '{$this->CONFIG_OBJ->type}'  ";
			foreach($t->extra as $et){
				$f_id = str_replace("tbl_", "", $t->name);
				$s_id = str_replace("tbl_", "", $et);
				$_tables .= " LEFT JOIN {$et} ON {$t->name}.{$f_id}_id = {$et}.{$s_id}_{$f_id}_id";

				$deleted_name = $s_id."_deleted";
				$this->WHERE .= " AND {$deleted_name} IS NULL ";
			}
			$this->WHERE .= $t->where!=''?' AND '.$t->where:'';
			$this->TABLES = $_tables;
			$count = 1;

			if(count($t->xpath('table')) > 0){
				$this->joinTable($t);
			}
		}
	}
	/**
	 * This function takes the CONFIG structure for a table which has
	 * joins. It can be called recursively. Linked tables must have a
	 * linking table named tbl_link_(parent)_(child).
	 * @param Object $table
	 */
	private function joinTable($table){
		$tbl_name = $table->name;
		$count = 1;
		$tbl_comp = str_replace("tbl_","",$tbl_name,$count);
		$id_name = $tbl_comp."_id";
		foreach($table->table as $t){
			$tbl2_name = $t->name;
			$count = 1;
			$tbl2_comp = str_replace("tbl_","",$tbl2_name,$count);
			$deleted_name = $tbl_comp."_deleted";
			$publish_name = $tbl_comp."_published";
			$id2_name = $tbl2_comp."_id";
			$this->TABLES = " LEFT JOIN tbl_link_{$tbl_comp}_{$tbl2_comp} ON $tbl_name.{$id_name} = tbl_link_{$tbl_comp}_{$tbl2_comp}.link_1_id
				LEFT JOIN $tbl2_name ON $tbl2_name.{$id2_name} = tbl_link_{$tbl_comp}_{$tbl2_comp}.link_2_id ";
			$this->WHERE = " AND {$deleted_name} IS NULL AND {$publish_name} = 1";
			if(count($t->xpath('table')) > 0){
				$this->joinTable($t);
			}
		}
	}


	function Load($_ID=null){
		global $SMARTY,$DBobject;
		$template = "";
		$data = "";
		//Split the URL
		$_url= ltrim(rtrim($this->URL,'/'),'/');
		$array = explode('/', $_url);
		foreach($array as $val){
			if($val != ''){
			$args[] = urldecode($val);
			}
		}

		while(true){
			if(empty($_url) && empty($_ID)){
				if(count($this->CONFIG_OBJ->xpath('limit')) > 0){
					$this->LIMIT = intval($this->CONFIG_OBJ->limit);
				}
				if(count($this->CONFIG_OBJ->xpath('groupby')) > 0){
					$groupby = $this->CONFIG_OBJ->groupby;
				}
				if(count($this->CONFIG_OBJ->xpath('orderby')) > 0){
					$orderby = $this->CONFIG_OBJ->orderby;
				}
				$data = $this->GetData("*","",$groupby,$orderby);
				$this->LIMIT = "";
				$SMARTY->assign('data', unclean($data));
				$template = $this->CONFIG_OBJ->template;
				
				$menu = $this->LoadMenu($_CONFIG_OBJ->pageID);
				$SMARTY->assign('menuitems',$menu);
				
				if(!empty($data)){
					break 1;
				}
			}else if(!empty($_ID)){
				
				$data = $this->GetDataSingleSet($_ID);
				foreach($data[0] as $key => $val){
					$SMARTY->assign($key, unclean($val));
				}
				$template = $t->template;
					
				$menu = $this->LoadMenu($_CONFIG_OBJ->pageID);
				$SMARTY->assign('menuitems',$menu);
				
				foreach($this->CONFIG_OBJ->table->associated as $a){
					$t_data = array();
					$pre = str_replace("tbl_","",$a->table);
					$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$_ID}' AND {$pre}_deleted IS NULL ";// AND article_deleted IS NULL";
					if($res = $DBobject->wrappedSqlGet($sql)){
						foreach ($res as $row) {
							$t_data[] = $row;
						}
					}
					$SMARTY->assign("{$a->name}",$t_data);
				}
				
				if(!empty($data)){
					break 1;
				}
			}else{
				$template = $this->LoadTemplate($this->CONFIG_OBJ, $args);
				if(!empty($template)){
					break 1;
				}
			}
			header("Location: /404");
		}

		return $template;
	}

	private function LoadTemplate($table, $args){
		global $SMARTY,$DBobject;
		foreach($table->table as $t)
		{
			//Check if matches first table
			$tbl_name = $t->name;
			$chk_field = $t->field;
			foreach($args as $arg){
				if($e_id = $this->ChkCache($tbl_name,$chk_field,$arg)){
					$prefix = str_replace("tbl_", "", $tbl_name);
					$data = $this->GetDataSubSet("{$prefix}_id = '{$e_id}'");
					$SMARTY->assign('data', unclean($data));
					$template = $t->template;
					
					$menu = $this->LoadMenu($_CONFIG_OBJ->pageID);
					$SMARTY->assign('menuitems',$menu);
					
					foreach($t->associated as $a){
						$t_data = array();
						$pre = str_replace("tbl_","",$a->table);
						$sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '{$e_id}' AND {$pre}_deleted IS NULL ";// AND article_deleted IS NULL";
						if($res = $DBobject->wrappedSqlGet($sql)){
							foreach ($res as $row) {
								$t_data[] = $row;
							}
						}
						$SMARTY->assign("{$a->name}",$t_data);
					}
					
					if($this->CONFIG_OBJ->pageID){
						$this->Load($this->CONFIG_OBJ->pageID);
					}
					
					if(!empty($data)){
						break 2;
					}
				}
			}

			if(count($t->table->xpath('table')) > 0){
				$template = $this->LoadTemplate($t->table, args);
				if(!empty($template)){
					break;
				}
			}

			if(count($table->table->xpath('filter')) > 0){
				$field = $table->table->filter->field;
				$val = clean($args[0]);
				if(count($this->CONFIG_OBJ->xpath('groupby')) > 0){
					$groupby = $this->CONFIG_OBJ->groupby;
				}
				if(count($this->CONFIG_OBJ->xpath('orderby')) > 0){
					$orderby = $this->CONFIG_OBJ->orderby;
				}
				$data = $this->GetData("*"," {$field} = '{$val}'",$groupby,$orderby);
				$SMARTY->assign('data', unclean($data));
				$template = $table->template;
				
				$menu = $this->LoadMenu($_CONFIG_OBJ->pageID);
				$SMARTY->assign('menuitems',$menu);
				
				if($this->CONFIG_OBJ->pageID){
					$this->Load($this->CONFIG_OBJ->pageID);
				}
				
				if(!empty($data)){
					break 1;
				}
			}
		}
		if(!empty($data)){
			return $template;
		}else{
			return "";
		}
	}
	

	private function ChkCache($tbl_name, $chk_field, $arg){
		global $SMARTY,$DBobject;
		$sql = "SELECT cache_record_id FROM cache_{$tbl_name} WHERE cache_url = :url";
		try{
			$params = array(":url"=>$arg);
			$row = $DBobject->wrappedSqlGetSingle($sql,$params);
			if(empty($row)){
				$sql2 = "SELECT {$chk_field} FROM {$tbl_name} WHERE {$chk_field} LIKE :url";
				$params2 = array(":url"=>'%'.str_replace('-','%',$arg).'%');
				if($res = $DBobject->wrappedSqlGet($sql2,$params2) ){
					$this->BuildCache($tbl_name,$chk_field);
				}
				$row = $DBobject->wrappedSqlGetSingle($sql,$params);
			}
		}catch(Exception $e){
			$sql2 = "SELECT {$chk_field} FROM {$tbl_name} WHERE {$chk_field} LIKE :url";
			$params2 = array(":url"=>'%'.str_replace('-','%',$arg).'%');
			if($res = $DBobject->wrappedSqlGet($sql2,$params2) ){
				$this->BuildCache($tbl_name,$chk_field);
			}
			$row = $DBobject->wrappedSqlGetSingle($sql,$params);
		}
		if(!empty($row)){
			return $row['cache_record_id'];
		}
		return false;
	}
	
	function BuildCache($tbl_name,$field){
		global $SMARTY,$DBobject;
		$table_prefix = str_replace("tbl_", "", $tbl_name);
		$sql[0] = "CREATE TABLE IF NOT EXISTS `cache_{$tbl_name}` (
		`cache_id` INT(11) NOT NULL AUTO_INCREMENT,
		`cache_record_id` INT(11) DEFAULT NULL,
		`cache_url` VARCHAR(255) DEFAULT NULL,
		`cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
		`cache_modified` DATETIME DEFAULT NULL,
		`cache_deleted` DATETIME DEFAULT NULL,
		PRIMARY KEY (`cache_id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;";
		$DBobject->wrappedSql($sql[0]);
			
		$sql[3] = "TRUNCATE cache_{$tbl_name};";
		$sql[2] = "INSERT INTO cache_{$tbl_name} (cache_record_id,cache_url,cache_modified) VALUES  ";
		$params = array();
		$sql[1] = "SELECT {$table_prefix}_id AS id, {$field} AS title FROM {$tbl_name} WHERE {$table_prefix}_deleted IS NULL";
		$res = $DBobject->wrappedSql($sql[1]);
		$n = 1;
		foreach($res as $row){
			
		$id = $row['id'];
			$title = unclean($row['title']);
			$url = urlSafeString($title);
				
			$sql[2].= " ( :id{$n}, :title{$n}, now() ) ,";
			$params = array_merge($params , array(":id{$n}"=>$id,":title{$n}"=>$url) );
			$n++;
			}
			$sql[2] = trim(trim($sql[2]), ',');
			$sql[2].= ";";
	
					$DBobject->wrappedSql($sql[3]);
					$DBobject->wrappedSql($sql[2],$params);
	}

	function LoadMenu($_pid, $_cid="0",$_parentURL=""){
		global  $CONFIG,$SMARTY,$DBobject;
		$data = array();
	
		//GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		//$sql = "SELECT tbl_category.*,tbl_listing.listing_title, tbl_listing.listing_url FROM tbl_category LEFT JOIN tbl_listing ON tbl_category.category_listing_id = tbl_listing.listing_id WHERE tbl_category.category_parent_id = :cid AND tbl_category.category_deleted IS NULL AND tbl_listing.listing_deleted IS NULL ORDER BY tbl_listing.listing_order ASC";
		$sql = "SELECT tbl_listing.listing_id, tbl_listing.listing_title, tbl_listing.listing_url FROM tbl_listing WHERE tbl_listing.listing_category_id = :cid AND tbl_listing.listing_deleted IS NULL AND EXISTS(SELECT * FROM tbl_category WHERE tbl_category.category_listing_id = tbl_listing.listing_id) ORDER BY tbl_listing.listing_order ASC";
		$params = array(":cid"=>$_cid);
		if($res = $DBobject->wrappedSql($sql,$params)){
			foreach ($res as $row) {
				//SELECT CATEGORY IF ONE EXISTS FOR ATTACHED TO THIS LISTING
				$sql = "SELECT tbl_category.* FROM tbl_category LEFT JOIN tbl_listing ON tbl_category.category_listing_id = tbl_listing.listing_id WHERE tbl_category.category_listing_id = :id AND tbl_category.category_parent_id = :cid AND tbl_category.category_deleted IS NULL ORDER BY tbl_listing.listing_order ASC";
				$params = array(":cid"=>$_cid,":id"=>$row['listing_id']);
				if($res2 = $DBobject->wrappedSql($sql,$params)){
					foreach ($res2 as $row2) {
						$subs = $this->LoadSubMenu($_pid,$row2['category_id']);
					}
				}
				$data[$row['listing_id']]["title"]=ucfirst(unclean($row['listing_title']));
				$data[$row['listing_id']]["url"]=ucfirst(unclean($row['listing_url']));
				$data[$row['listing_id']]["subs"]=$subs;
				$data[$row['listing_id']]["selected"] = 0;
	
				if($subs['selected']){
					$data["selected"] = 1;
				}else{
					$sql = "SELECT * FROM tbl_listing WHERE listing_id = :pid";
					$params = array(":pid"=>$_pid);
					if($res = $DBobject->wrappedSql($sql,$params)){
						if($res[0]!=null){
							$data["{$row['category_id']}"]["selected"] = 1;
							$data["selected"] = 1;
						}
					}
				}
			}
		}
		return $data;
	}
	
	function LoadSubMenu($_pid, $_cid,$_parentURL=""){
		global  $CONFIG,$SMARTY,$DBobject;
		$data = array();
	
		//GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		$sql = "SELECT tbl_listing.listing_id, tbl_listing.listing_title, tbl_listing.listing_url FROM tbl_listing WHERE tbl_listing.listing_category_id = :cid AND tbl_listing.listing_deleted IS NULL ORDER BY tbl_listing.listing_order ASC";
		$params = array(":cid"=>$_cid);
		if($res = $DBobject->wrappedSql($sql,$params)){
			foreach ($res as $row) {
				
				//SELECT CATEGORY IF ONE EXISTS FOR ATTACHED TO THIS LISTING
				$sql = "SELECT tbl_category.* FROM tbl_category LEFT JOIN tbl_listing ON tbl_category.category_listing_id = tbl_listing.listing_id WHERE tbl_category.category_listing_id = :id AND tbl_category.category_parent_id = :cid AND tbl_category.category_deleted IS NULL ORDER BY tbl_listing.listing_order ASC";
				$params = array(":cid"=>$_cid,":id"=>$row['listing_id']);
				if($res2 = $DBobject->wrappedSql($sql,$params)){
					foreach ($res2 as $row2) {
						$subs = $this->LoadSubMenu($_pid,$row2['category_id']);
					}
				}

				$data[$row['listing_id']]["title"]=ucfirst(unclean($row['listing_title']));
				$data[$row['listing_id']]["url"]=ucfirst(unclean($row['listing_url']));
				$data[$row['listing_id']]["subs"]=$subs;
				$data[$row['listing_id']]["selected"] = 0;
	
				if($subs['selected']){
					$data["selected"] = 1;
				}else{
					$sql = "SELECT * FROM tbl_listing WHERE listing_id = :pid";
					$params = array(":pid"=>$_pid);
					if($res = $DBobject->wrappedSql($sql,$params)){
						if($res[0]!=null){
							$data["{$row['category_id']}"]["selected"] = 1;
							$data["selected"] = 1;
						}
					}
				}
			}
		}
		return $data;
	}

	/**
	 * This function retieves a set of raw data for use in templates other than the
	 * listing templates.
	 *
	 * $_WHERE should be formatted as " field = value AND field2 = value2 ".
	 * $_GROUPBY should be formatted as " field, field2 ".
	 * $_ORDERBY should be formatted as " field ASC, field2 DESC ".
	 * @param string $_WHERE
	 * @param unknown_type $_GROUPBY
	 * @param unknown_type $_ORDERBY
	 */
	function GetRawData($_WHERE="",$_GROUPBY="",$_ORDERBY=""){
		return $this->GetData("*",$_WHERE,$_GROUPBY,$_ORDERBY);
	}

	/**
	 * This function retrieves all the distinct values from $_FIELD in the database.
	 * @param string $_FIELD
	 */
	protected function GetDataField($_FIELD){
		return $this->GetData($_FIELD,"",$_FIELD,"");
	}

	/**
	 * This function retieves a SubSet of all values in the database where the $_WHERE
	 * conditions are satisfied.
	 *
	 * $_WHERE should be formatted as " field = value AND field2 = value2 ".
	 * @param string $_WHERE
	 */
	protected function GetDataSubSet($_WHERE){
		return $this->GetData("*",$_WHERE,"","");
	}

	/**
	 * This function retrieves all values in the database. The result set will be returned
	 * as ARRAY[][row1]
	 * 			 [row2]
	 * 			 etc.
	 *
	 * $GB_FIELDS can be used to define the field used to group the data at the top level
	 * in the result set.
	 * eg.	GetDataSet("month_field");
	 * ARRAY[April][row5]
	 * 			   [row6]
	 * 		[May][row7]
	 * 			 [row8]
	 * 		etc.
	 * @param string $GB_FIELD
	 */
	protected function GetDataSet($GB_FIELD=""){
		$_resSet = array();
		$res = $this->GetData("*","","","");
		if(!empty($GB_FIELD)){
			foreach($res as $row){
				$_resSet[$row[$GB_FIELD]][] = $row;
			}
			return $_resSet;
		}
		return $res;
	}

	/**
	 * This function retrieves a single value from the database based on the ID matching against
	 * the primary table.
	 * @param string $ID
	 */
	protected function GetDataSingleSet($ID){
		$count = 1;
		$id_name = str_replace("tbl_","",$this->CONFIG_OBJ->table->name,$count)."_id";
		return $this->GetData("*"," {$id_name} = {$ID} ","","");
	}

	/**
	 * This is the top level class for retrieving data from the database. Classes such
	 * as GetDataSubSet() or GetDataSet() should be used instead.
	 * @param unknown_type $_SELECT
	 * @param unknown_type $_WHERE
	 * @param unknown_type $_GROUPBY
	 * @param unknown_type $_ORDERBY
	 */
	protected function GetData($_SELECT="*",$_WHERE="",$_GROUPBY="",$_ORDERBY=""){
		global  $DBobject,$SMARTY;
		$sql = "SELECT {$_SELECT}
				FROM {$this->TABLES}
				".(!empty($_WHERE)?" WHERE {$this->WHERE} AND {$_WHERE} ":" WHERE {$this->WHERE} ")."
				".(!empty($_GROUPBY)?" GROUP BY {$_GROUPBY}":"")."
				".(!empty($_ORDERBY)?" ORDER BY {$_ORDERBY}":"")."
				".(!empty($this->LIMIT)?" LIMIT {$this->LIMIT}":"");
		//echo $sql;
		$data = $DBobject->wrappedSql($sql);
		return $data;
	}

}




