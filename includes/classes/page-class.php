<?php

Class Page{

	
	function __construct(){
		$this->DBobject = new DBmanager();

	}
	/*********************** front end ****************************************/
	function LoadPage($_CONFIG_OBJ){
		global  $SMARTY;
		if(empty($_CONFIG_OBJ)){
			throw(new Exception("An error occured because there was no configuration information for the page you are trying to load"));
		}else{
			$this->page_fields = $this->GetPageInfo($_CONFIG_OBJ);

			if($this->page_fields){
				foreach ($this->page_fields as $fieldname => $fieldvalue) {
					$SMARTY->assign($fieldname,unclean($fieldvalue));
				}
			}
			$this->LoadMenu($id);
		}
	}

	function GetPageInfo($_CONFIG_OBJ){
		
		$_tables = "";		
		foreach($_CONFIG_OBJ->table as $t){
			$table = $t->name;
			$relID = $t->relID;
			$_tables .= " LEFT JOIN {$table} ON tbl_listing.listing_id = {$relID} ";
		}
		
		$sql = "SELECT * FROM tbl_listing {$_tables} WHERE tbl_listing.listing_id = {$_CONFIG_OBJ->pageID} AND tbl_listing.listing_deleted IS NULL";
		$arr = $this->DBobject->wrappedSql($sql);
		if(!empty($arr[0])){
			foreach ($arr as $key =>  $row) {
				$page_fields[$row['field_name']]=$row['link_content'];
			}
		}
		return  $page_fields;
	}

	function LoadMenu($id){
		
		global  $CONFIG,$SMARTY,$DBobject;
		
		$sql = "SELECT * FROM tbl_listing WHERE tbl_listing.listing_id = {$_CONFIG_OBJ->pageID} AND tbl_listing.listing_deleted IS NULL";
		$page = $this->DBobject->wrappedSql($sql);
		$page = $page['0'];
		
		$sql = "SELECT * FROM tbl_menu WHERE tbl_menu.listing_deleted IS NULL";
		
		$res = $DBobject->GetTable('tbl_menu','','','menu_order ASC');
		if($res){
			foreach ($res as $row) {
				$subpages = $DBobject->GetTable('tbl_page','page_menu_id = "'.$row['menu_id'].'" AND page_order >= 0 ','','page_order ASC');
				$menu[$row['menu_id']]['title']=ucfirst(unclean($row['menu_title']));
				$menu[$row['menu_id']]['url']=unclean($row['menu_url']);
				$menu[$row['menu_id']]['rel']=(count($subpages)>0?'rel="ddsubmenu'.$row['menu_id'].'"':'').unclean($row['menu_url']);
				$menu[$row['menu_id']]['selected'] = false;
				if(unclean($row['menu_url']) == $page['page_url'] || $row['menu_id'] == $page['page_menu_id'] ){
					$menu[$row['menu_id']]['selected'] = true;
				}
				if(count($subpages) > 0){
					foreach($subpages as $row2){
						$submenu[$row['menu_id']][$row2['page_id']]['title'] =  ucfirst($row2['page_name']);
						$submenu[$row['menu_id']][$row2['page_id']]['url']  =  $row2['page_url'];
					}
				}
			}
		}
		$SMARTY->assign('menuitems',$menu);
		$SMARTY->assign('submenuitems',$submenu);
	}

}