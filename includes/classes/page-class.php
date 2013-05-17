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
			$this->LoadMenu($_CONFIG_OBJ->pageID);
		}
	}

	function GetPageInfo($_CONFIG_OBJ){
		global  $SMARTY, $CONFIG;
		
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
			foreach ($arr[0] as $key =>  $row) {
				$page_fields[$key]=$row['link_content'];
				
			}
		}
		return  $page_fields;
	}

	function LoadMenu($_id){
		global  $CONFIG,$SMARTY,$DBobject;
		$submenu = array();
		
		$cat_id = "";
		//GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		$sql = "SELECT tbl_category.* FROM tbl_listing LEFT JOIN tbl_category ON tbl_listing.listing_category_id = tbl_category.category_id WHERE tbl_listing.listing_id = '{$_id}' AND tbl_listing.listing_deleted IS NULL AND tbl_category.category_deleted IS NULL";
		if($cat = $this->DBobject->wrappedSql($sql)){
			if(!empty($cat['0']) && !empty($cat['0']['category_id'])){
				$cats = $this->get_top_category($cat['0']['category_id']);
				$cat_id = $categories['id'];
			}
		}
		
		//This section of code gets all the items which are considered menu items.
		//The top level menu items are determined by tbl_menu. This is because top level menu items are part of site design and not part of category structure. 
		//Because listing objects can be categories themselves, menus may have sub menus
		$sql = "SELECT tbl_menu.* FROM tbl_menu WHERE tbl_menu.menu_deleted IS NULL ORDER BY menu_order";
		if($res = $this->DBobject->wrappedSql($sql)){
			if(!empty($res['0']) && !empty($res['0']['menu_id'])){
				foreach ($res as $row) {
					
					$menu[$row['menu_id']]['title']=ucfirst(unclean($row['menu_title']));
					$menu[$row['menu_id']]['url']=unclean($row['menu_url']);
					
					$menu[$row['menu_id']]['selected'] = false;
					if($row['menu_category_id'] == $cat_id ){
						$menu[$row['menu_id']]['selected'] = true;
					}
					$sql = "SELECT tbl_listing.* FROM tbl_listing WHERE tbl_listing.listing_category_id = '{$row['menu_category_id']}' AND tbl_listing.listing_deleted IS NULL ORDER BY listing_order ASC";
					if($subpages = $this->DBobject->wrappedSql($sql)){
						if(!empty($subpages['0']) && !empty($subpages['0']['listing_id'])){
							$menu[$row['menu_id']]['rel']=(count($subpages)>0?'rel="ddsubmenu'.$row['menu_id'].'"':'');
							foreach($subpages as $row2){
								$submenu[$row['menu_id']][$row2['listing_id']] = $this->sub_menu($row, 1);
							}
						}
					}
				}
			}
		}
		$SMARTY->assign('menuitems',$menu);
		$SMARTY->assign('submenuitems',$submenu);
	}
	
	function sub_menu($row, $_lvl){
		$return = array();
		$return['title'] = ucfirst($row['listing_title']);
		$return['url']  = $row['listing_url'];
		$sql = "SELECT tbl_listing.* FROM tbl_category LEFT JOIN tbl_listing ON tbl_category.category_id = tbl_listing.listing_category_id WHERE tbl_category.category_listing_id = '{$row['listing_id']}' AND tbl_category.category_deleted IS NULL AND tbl_listing.listing_deleted IS NULL";
		if($res = $this->DBobject->wrappedSql($sql)){
			if(!empty($res['0']) && !empty($res['0']['listing_id'])){
				$return['rel']=(count($subpages)>0?'rel="ddsubmenu'.$row['menu_id'].'"':'');
			}
			foreach($res as $row2){
				$return['submenu'][] = $this->sub_menu($row2, (intval($_lvl)+1));
			}
		}
		return $return;
	}

	function get_top_category($_id,$_child=null){
		$C = $_child;
		//GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
		$sql = "SELECT tbl_category.* FROM tbl_listing LEFT JOIN tbl_category ON tbl_listing.listing_category_id = tbl_category.category_id WHERE tbl_listing.listing_id = {$_id} AND tbl_listing.listing_deleted IS NULL AND tbl_category.category_deleted IS NULL";
		die($sql);
		if($obj = $this->DBobject->wrappedSql($sql)){
			if(!empty($obj['0'])){
				$C = array();
				$C['id'] = $obj['0']['category_id'];
				$C['name'] = $obj['0']['category_name'];
				$C['child'] = $_child;
				return $this->get_top_category($C['id'],$C);
			}
		}
		return $C;
	}
	
}