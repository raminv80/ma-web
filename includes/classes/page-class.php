<?php

Class Page{


	function __construct(){
		$this->DBobject = new DBmanager();

	}
	/*********************** front end ****************************************/
	function LoadPage($_CONFIG_OBJ){
		global  $SMARTY, $CONFIG;
		if(empty($_CONFIG_OBJ)){
			throw(new Exception("An error occured because there was no configuration information for the page you are trying to load"));
		}else{
			$this->page_fields = $this->GetPageInfo($_CONFIG_OBJ);
			if($this->page_fields){
				foreach ($this->page_fields as $fieldname => $fieldvalue) {
					$SMARTY->assign($fieldname,unclean($fieldvalue));
				}
			}
			$menu = $this->LoadMenu($_CONFIG_OBJ->pageID);
			$SMARTY->assign('menuitems',$menu);
		}
	}

	function GetPageInfo($_CONFIG_OBJ){
		global  $SMARTY, $CONFIG   ,$obj;
		foreach($CONFIG->page_strut->table as $t){
			$_tables = $t->name;
			$_where = "";
			$tbl_comp = str_replace("tbl_","",$t->name,$count);
			$deleted_name = $tbl_comp."_deleted";
			$publish_name = $tbl_comp."_published";
			$type = $tbl_comp."_type_id";
			
			$_where = " {$t->name}.{$tbl_comp}_id = '{$_CONFIG_OBJ->pageID}' AND {$deleted_name} IS NULL AND {$publish_name} = 1 AND {$type} = '{$CONFIG->page_strut->type}'";
			foreach($t->extra as $et){
				$f_id = str_replace("tbl_", "", $t->name);
				$s_id = str_replace("tbl_", "", $et);
				$_tables .= " LEFT JOIN {$et} ON {$t->name}.{$f_id}_id = {$et}.{$s_id}_{$f_id}_id";
				$deleted_name = $s_id."_deleted";
				$_where .= " AND {$deleted_name} IS NULL ";
			}

		}
		$sql = "SELECT * FROM {$_tables} WHERE {$_where}";
		$arr = $this->DBobject->wrappedSqlGet($sql);
		if(!empty($arr[0])){
			$this->CONFIG_OBJ = $_CONFIG_OBJ;
			foreach ($arr[0] as $key =>  $row) {
				$page_fields[$key]=$row;
			}
		}
		return  $page_fields;
	}

	function LoadMenu($_pid, $_cid="0",$_parentURL=""){
		global  $CONFIG,$SMARTY,$DBobject;
		$data = array();
		
		//GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		$sql = "SELECT tbl_category.*,tbl_listing.listing_title, tbl_listing.listing_url FROM tbl_category LEFT JOIN tbl_listing ON tbl_category.category_listing_id = tbl_listing.listing_id WHERE tbl_category.category_parent_id = :cid AND tbl_category.category_deleted IS NULL AND tbl_listing.listing_deleted IS NULL";
		$params = array(":cid"=>$_cid);
		if($res = $DBobject->wrappedSql($sql,$params)){
			foreach ($res as $row) {
				$subs = $this->LoadMenu($_pid,$row['category_id']);
				$data["{$row['category_id']}"]["title"]=ucfirst(unclean($row['listing_title']));
				$data["{$row['category_id']}"]["url"]=ucfirst(unclean($row['listing_url']));
				$data["{$row['category_id']}"]["subs"]=$subs;
				$data["{$row['category_id']}"]["selected"] = 0;
				
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

// 		$SMARTY->assign('menuitems',$menu);
// 		$SMARTY->assign('submenuitems',$submenu);
	}
	

}