<?php

Class Page{

	public $css=array();
	public $js=array();
	public $inside_header=array();
	public $short_js=array();
	public $after_heads=array();

	function __construct(){
		$this->DBobject = new DBmanager();

	}
	/*********************** front end ****************************************/
	function LoadPage($id='0'){
		if($id == 0 ){

		}else{
			$this->page_fields = $this->GetPageInfo($id);
			$this->LoadTemplate($id);
		}
	}

	function GetPageInfo($id){
		$tbl_fields = $this->DBobject->GetRow('tbl_page', 'page_id = "'.$id.'" ');
		if($tbl_fields){
			$page_fields=array();
			foreach ($tbl_fields as $key => $field) {
				if($key != 'page_modified' && $key != 'page_deleted' && $key != 'page_fields'	&& !is_numeric($key)){
					$page_fields[$key] = $field;
					$this->$key = $field;
				}
			}
		}
		
		$sql = "
		SELECT
		tbl_field.field_name,
		tbl_link_field_type.link_type_id AS field_type_id,
		tbl_link_page_field.link_id,
		tbl_link_page_field.link_content,
		tbl_link_page_field.link_field_id	
 		FROM tbl_link_page_field
  		LEFT JOIN tbl_field
   		  ON tbl_link_page_field.link_field_id = tbl_field.field_id
   		LEFT JOIN tbl_link_field_type
   			ON tbl_field.field_id = tbl_link_field_type.link_field_id
		WHERE tbl_link_page_field.link_page_id  = '".$id."'
		AND tbl_link_page_field.link_deleted IS NULL
		AND tbl_link_field_type.link_deleted IS NULL
		";
		$arr = $this->DBobject->wrappedSql($sql);
		if(!empty($arr[0])){
			foreach ($arr as $key =>  $row) {
				$page_fields[$row['field_name']]=$row['link_content'];
			}
		}
		return  $page_fields;
	}

	private function LoadTemplate($id){
		global  $SMARTY;

		if($this->page_fields){
			foreach ($this->page_fields as $fieldname => $fieldvalue) {
				$this->$fieldname = $fieldvalue;
				$SMARTY->assign($fieldname,unclean($fieldvalue));
			}
		}
		if(!empty($this->short_js)){
			$SMARTY->assign('short_js', $this->short_js);
		}
		if(!empty($this->js)){
			$SMARTY->assign('js', $this->short_js);
		}
		if(!empty($this->css)){
			$SMARTY->assign('css', $this->css);
		}

		$this->LoadMenu($id);
	}
	
	function LoadMenu($id){
		
		global  $CONFIG,$SMARTY,$DBobject;
		
		$page = $DBobject->GetRow("tbl_page", "page_id = '$id'");
		
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