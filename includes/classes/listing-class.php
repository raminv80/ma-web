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
	
	function __construct($_URL,$_CONFIG_OBJ){
		global $SMARTY,$DBobject;	
		$this->URL = $_URL;
		$this->CONFIG_OBJ = $_CONFIG_OBJ;
		$this->DBTABLE = new Table($this->CONFIG_OBJ->ID);
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
			$this->WHERE = " {$deleted_name} IS NULL AND {$publish_name} = 1 AND {$type} = '{$this->CONFIG_OBJ->type}'";
			foreach($t->extra as $et){
				$f_id = str_replace("tbl_", "", $t->name);
				$s_id = str_replace("tbl_", "", $et);
				$_tables .= " LEFT JOIN {$et} ON {$t->name}.{$f_id}_id = {$et}.{$s_id}_{$f_id}_id";
				
				$deleted_name = $s_id."_deleted";
				$this->WHERE .= " AND {$deleted_name} IS NULL ";
			}
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
	
	
	function Load(){
		global $SMARTY;	
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
			if(empty($_url)){
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
				if(count($this->CONFIG_OBJ->xpath('menu')) > 0){
					foreach($this->CONFIG_OBJ->menu as $m){
						$menus[] = $this->LoadMenu($m);
					}
				}
				
				$SMARTY->assign('menus',$menus);
				if(!empty($data)){
					break 1;
				}
			}
			//Check if matches first table
			$tbl_name = $this->CONFIG_OBJ->table->name;
			$chk_field = $this->CONFIG_OBJ->table->field;
			foreach($args as $arg){
				if($e_id = $this->ChkCache($tbl_name,$chk_field,$arg)){
					$data = $this->GetDataSingleSet($e_id);
					$SMARTY->assign('data', unclean($data));
					$template = $this->CONFIG_OBJ->table->template;
					if(count($this->CONFIG_OBJ->xpath('menu')) > 0){
						foreach($this->CONFIG_OBJ->menu as $m){
							$menus[] = $this->LoadMenu($m);
						}
					}
					$SMARTY->assign('menus',$menus);
					if(!empty($data)){
						break 2;
					}
				}
			}
			
			if(count($this->CONFIG_OBJ->table->xpath('table')) > 0){
				$template = $this->LoadTemplate($this->CONFIG_OBJ->table, args);
				if(!empty($template)){
					break 1;
				}
			}
			if(count($this->CONFIG_OBJ->xpath('filter')) > 0){
				$field = $this->CONFIG_OBJ->filter->field;
				$val = clean($args[0]);
				if(count($this->CONFIG_OBJ->xpath('groupby')) > 0){
					$groupby = $this->CONFIG_OBJ->groupby;
				}
				if(count($this->CONFIG_OBJ->xpath('orderby')) > 0){
					$orderby = $this->CONFIG_OBJ->orderby;
				}
				$data = $this->GetData("*"," {$field} = '{$val}'",$groupby,$orderby);
				$SMARTY->assign('data', unclean($data));
				$template = $this->CONFIG_OBJ->template;
				if(count($this->CONFIG_OBJ->xpath('menu')) > 0){
					foreach($this->CONFIG_OBJ->menu as $m){
						$menus[] = $this->LoadMenu($m);
					}
				}
				$SMARTY->assign('menus',$menus);
				
				if(count($this->CONFIG_OBJ->filter->xpath('title')) > 0){
					$d2 = $this->GetData($this->CONFIG_OBJ->filter->title." AS title"," {$field} = '{$val}'","","");
					foreach($d2 as $row){
						$SMARTY->assign('title', $row['title']);
						break;
					}
				}
				
				if(!empty($data)){
					break 1;
				}
			}
			header("Location: /404");
			die('here');
		}
		
		$this->GenerateSEO();
		//$this->LoadPageData();
		
		return $template;
	}
	
	private function ChkCache($tbl_name, $chk_field, $arg){
		global $SMARTY,$DBobject;
		try{
			$element_id = $DBobject->GetAnyCell('cache_record_id', "cache_".$tbl_name, 'cache_url = "'.$arg.'"','1');
			if(empty($this->element_id)){
				if($res = $DBobject->GetAnyCell("*", $tbl_name, $chk_field.' LIKE "%'.str_replace('-','%',$arg).'%"') ){
					$this->DBTABLE->BuildCache($chk_field);	
					
				}
				$element_id = $DBobject->GetAnyCell('cache_record_id', "cache_".$tbl_name, 'cache_url = "'.$arg.'"','1');
			}
		}catch(Exception $e){
			if($res = $DBobject->GetAnyCell("*", $tbl_name, $chk_field.' LIKE "%'.str_replace('-','%',$arg).'%"')){
				$this->DBTABLE->BuildCache($chk_field);	
			}
			$element_id = $DBobject->GetAnyCell('cache_record_id', "cache_".$tbl_name, 'cache_url = "'.$arg.'"','1');
		}
		if(!empty($element_id)){
			return $element_id;
		}
		return false;
	}
	
	private function LoadTemplate($table, $url_args){
		global $SMARTY;	
		foreach($table->table as $t)
		{
			//Check if matches first table
			$tbl_name = $t->name;
			$chk_field = $t->field;
			foreach($args as $arg){
				if($e_id = $this->ChkCache($tbl_name,$chk_field,$arg)){
					$data = $this->GetDataSubSet("{$chk_field} = {$arg}");
					$SMARTY->assign('data', unclean($data));
					$template = $t->template;
					if(count($t->xpath('menu')) > 0){
						foreach($t->menu as $m){
							$menus[] = $this->LoadMenu($m);
						}
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
				if(count($table->xpath('menu')) > 0){
					foreach($table->menu as $m){
						$menus[] = $this->LoadMenu($m);
					}
				}
				$SMARTY->assign('menus',$menus);
				if(!empty($data)){
					break 1;
				}
			}
		}
		$SMARTY->assign('menus',$menus);
		if(!empty($data)){
			return $template;
		}else{
			return "";
		}
	}
	
	private function LoadMenu($menu){
		global $SMARTY;	
		
		$field = $menu->field;
		if(count($menu->xpath('groupby')) > 0){
			$groupby = $menu->groupby;
		}
		if(count($menu->xpath('orderby')) > 0){
			$orderby = $menu->orderby;
		}
		
		$data = $this->GetData($field,"",$groupby,$orderby);
		$SMARTY->assign('menuitem', unclean($data));
		
		$template = $menu->template;
		$res =  $SMARTY->fetch($template);
		return $res;
	}
	
	/**
	 * This function retrieves all the Field values linked to this PAGEID 
	 */
	function LoadPageData(){
		/*global  $DBobject,$SMARTY;		
		$sql = "SELECT tbl_link_page_field.link_page_field_content, tbl_field.field_name
				FROM tbl_link_page_field LEFT JOIN tbl_field ON tbl_link_page_field.link_field_id = tbl_field.field_id
				WHERE tbl_link_page_field.link_page_id = '{$this->CONFIG_OBJ->pageID}'
				AND tbl_link_page_field.link_deleted IS NULL 
				AND tbl_field.field_deleted IS NULL";
		$data = array();
		if($data = $DBobject->wrappedSqlGet($sql)){
			foreach($data as $key=>$value){
				$SMARTY->assign($value['field_name'],$value['link_page_field_content']);
			}
		}*/
	}
	
	/**
	 * This function retrieves the SEO values from the page table for this PAGEID 
	 */
	function GenerateSEO(){
		/*global  $DBobject,$SMARTY;	
		
		$sql = "SELECT * FROM tbl_page WHERE page_id = '{$this->CONFIG_OBJ->pageID}'";
		if($data = $DBobject->wrappedSqlGet($sql)){
			foreach($data as $row){
				foreach($row as $key=>$value){
					$SMARTY->assign($key,$value);
				}
			}
		}*/
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
		
		$data = $DBobject->wrappedSql($sql);
		return $data;
	}
	
}




