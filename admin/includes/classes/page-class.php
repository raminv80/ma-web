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

	/*********************** back end ****************************************/

	/*function ListPages(){
		$table  =	$this->DBobject->GetTable('tbl_page',"page_deleted is null ");
		$buf='<table class="page-listing">
			  <tr><th>PAGE NAME</th><th>OPTIONS</th><th><a href="/admin/edit_misc.php?misc=1"><img style=";border-style: none;width: 20px;" title="Add Page" alt="Add" src="/admin/images/add_icon.png" class="add-btn"></a></th></tr>
			'; 
		foreach ($table as $page) {
			$buf.='<tr><td class="td-page-listin">'.$page["page_name"].'</td><td><a href="/admin/edit_page.php?id='.$page["page_id"].'">Edit</a></td><td><a href="#" onclick="DE('.$page["page_id"].');" >Delete</a></td>';
		}
		$buf.='</table>';
		echo $buf;
		//printr($table);
	}*/
	
	//MORE ADVANCED PAGE LISTING
	function ListPages(){

		
		$buf.="<table width='100%' id='page_table' class='tablesorter'>";

		$buf .="<thead><tr>";
		$buf.="<th  bgcolor='#F0F0EE'> Page Name </th>";
		$buf.="<td  bgcolor='#F0F0EE' colspan='4'>ACTIONS&nbsp;";

    	$buf.="&nbsp;<a href='/admin/includes/processes/page-constructor.php' >
    				<img src='images/add_icon.png' style='width: 22px;float: right; border-style: none;'	float: right;' alt='Add Item' title='Add Item'>
    				</a>";

		$buf.='</td></tr></thead>';
		
		$page_arr  =	$this->DBobject->GetTable('tbl_page',"page_deleted IS NULL ",'',"page_name ASC");

		if($page_arr){
			foreach ($page_arr as $line) {
				$buf.="<tr>";
				$buf.="<td><a href='edit_page.php?id={$line['page_id']}'>{$line['page_name']}</a></td>";
				$buf.="<td><a href='edit_page.php?id={$line['page_id']}'>View/Edit</td>";
				$buf.="</tr>";
			}
		}
		$buf.='</table>';

		$buf.='<script>$(document).ready(function() {  $("#page_table").tablesorter();  } ); </script>';

		return $buf;
	}

	function SetUpdatePage($arr){
		foreach ($arr as $key => $value){
			if(is_array($value)){
				$value=serialize(clean($value));
			}else{
				$value=clean($value);
			}
			
			if(strstr($key,'||')){
					$new_id = str_replace('||', '', $key);
					$sql="INSERT INTO  tbl_link_page_field (link_field_id,link_page_id,link_content) VALUES ('".$new_id."','".$arr['id']."','".$value."') ";
					$this->DBobject->wrappedSqlInsert($sql);
			} 
			if(is_numeric($key)){
				if($res = $this->DBobject->wrappedSql("SELECT link_id FROM tbl_link_page_field WHERE link_field_id='{$key}' AND link_page_id='{$arr['id']}' AND link_deleted is NULL")){
					if($res['0']['link_id']  != '' ){
						$sql = "UPDATE tbl_link_page_field SET link_content = '{$value}' WHERE link_field_id='{$key}' AND link_page_id='{$arr['id']}' AND link_deleted is NULL";
						$this->DBobject->executeSQL($sql);
					}
				}
			}
		}
		//die(print_r($arr));

		/*$mtitle = $this->DBobject->GetAnyCell('menu_title', 'tbl_menu', ' menu_id = "'.($arr['page_menu_id']).'" ');
		if($mtitle){
			$url = (ucwords(urlSafeString($mtitle)).'/'.ucwords(urlSafeString($arr['page_name'])));
		}else{
			$url = (ucwords(urlSafeString($arr['page_name'])));
		}*/
		$url = urlSafeString($arr['page_url']);
		$fields =  array('page_seo_title','page_metawords','page_metadescription','page_menu_id','page_order','page_url','page_name','page_modified');
		$values =  array($arr['page_seo_title'],$arr['page_metawords'],$arr['page_metadescription'],$arr['page_menu_id'],$arr['page_order'],$url,$arr['page_name'],'NOW()');
		$this->DBobject->wrappedSqlUpdate('tbl_page',$fields,$values ,  " page_id = '".$arr['id']."' ");
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
		tbl_link_field_type.link_type_id,
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
				$this->page_dynamic[$row['field_name']]=$row;
			}
		}
		return  $page_fields;
	}

	function CreateEditForm( $id , $action ){
		$this->GetPageInfo($id);
		//$this->LoadTemplate();
		$this->action=$action;
		$form_butf.='<form name="'.$action.'" id="'.$action.'" method="post" action="/admin/includes/processes/admin-processes-general.php" accept-charset="UTF-8">';
		$div_id='id="div_'.$this->page_name.'"';
		$form_butf.=load_text_editor();
		$form_butf.='<div '.$div_id.'><table class="page-form-table"  >';
		///get buf for regular fields
		//page_seo_title
		$text_field="<input type=text name=\"page_seo_title\" id=\"title\" class=\"form-textfield\" value=\"".unclean($this->page_seo_title)."\">";
		$form_butf .=$this->create_table_line('page_seo_title',$text_field);
		//page_metawords
		$text_field="<input type=text name=\"page_metawords\" id=\"metawords\" class=\"form-textfield\" value=\"".unclean($this->page_metawords)."\">";
		$form_butf .=$this->create_table_line('metawords',$text_field);
		//page_metadescription
		$text_field="<input type=text name=\"page_metadescription\" id=\"metadescription\" class=\"form-textfield\" value=\"".unclean($this->page_metadescription)."\">";
		$form_butf .=$this->create_table_line('metadescription',$text_field);
		//page_menu_id
		$form_butf	.=	$this->create_dropdown_field('tbl_menu','menu_title','page_menu_id',$this->page_menu_id, 'parent_menu');
		//page_order
		$text_field="<input type=text name=\"page_order\" id=\"order\" class=\"form-textfield\" value=\"".unclean($this->page_order)."\">";
		$form_butf .=$this->create_table_line('order',$text_field);
		//page_url
		$text_field="<input type=text name=\"page_url\" id=\"url\" class=\"form-textfield\" value=\"".unclean($this->page_url)."\">";
		$form_butf .=$this->create_table_line('url',$text_field);
		//page_url
		$text_field="<input type=text name=\"page_name\" id=\"page_name\" class=\"form-textfield\" value=\"".unclean($this->page_name)."\">";
		$form_butf .=$this->create_table_line('page_name',$text_field);
		///END STATIC fields
		if($this->page_dynamic){
			foreach ($this->page_dynamic as $field) {

				switch ($field['link_type_id']) {
					case '1'://'text': //text field
					case '7'://'meta':
					case '8'://'video':
					case '6'://'embedded':
						$form_butf.=$this->create_text($field);
						break;
					case '2': //'paragraph': //wyswyg editor
					case '14':
						$form_butf.=$this->create_text_area($field);
						break;
					case '3'://'numeric':
					case '4'://'decimal':
						$form_butf.=$this->create_number_field($field);
						break;
					case '10'://'datetime':
					case '5'://'date':		//date!
						$form_butf.=$this->create_date($field);
						break;
					case '11'://'check': //check
						$form_butf.=$this->create_check_box($field);
						break;
					case '9'://'password':
						$form_butf.=$this->create_password_field($field);
						break;
					default:
						if(strpos($field,'varchar') >= 0){
							$form_butf.=$this->create_text($field);
							break;
						}
						break;
				}
			}
		}
		$form_butf.='
						<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Submit" /></td></tr>
						</table>
						</div>
		';

		if($this->redirect	!= ''){
			$form_butf.=$this->create_hidden('redirect',$this->redirect);
		}
		$form_butf.=$this->create_hidden('Action',$action);
		$form_butf.=$this->create_hidden('id',$this->page_id);
		$form_butf.=insertToken().'</form>';
		/*** check with jeff ****/
			 $form_butf.=LinkedFiles('1', $this->page_id);
	    /***********/
		return $form_butf;

	}

	/** Region has the create methods to create the form elements **/

	function create_hidden($action, $value){
		$hidden_field='<input type="hidden" name="'.$action.'" value="'.$value.'">';
		return $hidden_field;
	}

	function create_text($field){
		$text_field="<input type=text name=\"".unclean($field['link_field_id'])."\" id=".unclean($field['link_field_id'])." class=\"form-textfield\" value=\"".unclean($field['link_content'])."\">";
		$created_field=$this->create_table_line(unclean($field['field_name']),$text_field);
		return $created_field;
	}

	function create_number_field($field){
		$text_field="<input type=text name=\"".unclean($field['link_field_id'])."\" id=".unclean($field['link_field_id'])." class=\"form-textfield\" value=\"".unclean($field['link_content'])."\">";
		$js="<script>
		 $('#".unclean($field['field_name'])."').keydown(function(e)
        {


          		 var key = e.charCode || e.keyCode || 0;
			  // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
                key == 8 ||
                key == 9 ||
                key == 46 ||
                key == 110 ||
                (key >= 37 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));

        });

		</script>";
		$created_field=$this->create_table_line(unclean($field['field_name']),$text_field.$js);
		return $created_field;

	}

	function create_check_box($field){
		$text_field="<input type='checkbox' name=\"".unclean($field['link_field_id'])."\" id=".unclean($field['link_field_id'])."  ".(unclean($field['link_content']) == '1'?'checked="checked" value="1"':'value="0"' )." >";
		$text_field.="<script>
		$('#".unclean($field['field_name'])."').click(function(){
			if($('#".unclean($field['field_name'])."').attr('checked') == 0){
			$('#".$this->action."').append(\"<input type='hidden' name='".unclean($field['field_name'])."' value='0' id='temp_".unclean($field['field_name'])."'>\");
			$('#".unclean($field['field_name'])."').attr('value','0');

			}
			if($('#".unclean($field['field_name'])."').attr('checked') == 1){
			$('#temp_".unclean($field['field_name'])."').remove();
			$('#".unclean($field['field_name'])."').attr('value','1');

			}

		});
		</script>";
		$created_field=$this->create_table_line(unclean($field['field_name']),$text_field);
		return $created_field;
	}
	function create_text_area($field){
		if($field['link_field_id'] == '' ){
			$field['link_field_id']='||'.$field['link_field_id'];
			
		}
		$text_area='<textarea id="'.unclean($field['link_field_id']).'" name="'.unclean($field['link_field_id']).'" style="width:60%" class="tinymce">'.unclean($field['link_content']).'</textarea>';
		$created_field=$this->create_table_line(unclean($field['field_name']),$text_area);
		return $created_field;

	}
	function create_date($field){
		$date_field="<script>$(function() {	$( '.datepicker' ).datepicker( { changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );});	</script>";
		$date_field.="<input type=text name=\"".unclean($field['link_field_id'])."-datepicker\" class='datepicker' value='".unclean($field['link_content'])."'>";
		$created_field=$this->create_table_line(unclean($field['field_name']),$date_field);
		return $created_field;
	}

	function create_password_field($field){
		$text_field="<input type='password' name=\"".unclean($field['link_field_id'])."\" id='".unclean($field['field_name'])."' class=\"form-textfield\" >";
		$created_field=$this->create_table_line(unclean($field['field_name']),$text_field);
		return $created_field;
	}

	function create_dropdown_field($table,$field,$key,$value=0,$name){
		$thistable	= substr($table,4);//explode('_',$table);
		/***hack for dependent dd*******/
		if($field == 'state_state'){
			$js="
			<script>
			$('#location_state_id').change(function () {
				var state = $('#location_state_id option:selected').text();
				if(state != null){
					$('#location_postcode_id').load('/admin/includes/processes/processes-ajax.php', { state:state, Action:'GetStates'});
				}
			});
			</script>
			";
		}

		/*********************************/
		$table  =	$this->DBobject->GetTable($table,$thistable."_deleted is null");
		$fmiscvalue = 'null';
		if($table){
			foreach ($table as $line) {
				if($value==$line[0]){
					$selected='selected="selected"';
					$fmiscvalue=$line[0];
				}else{
					$selected='';
				}
				$drop_down	.=	'<option value="'.$line[0].'" '.$selected.'>'.unclean($line[$field]).'</option>';
			}
		}
		$drop_down	.=	'</select>';

		$drop_down	=	'<option value="0" >Please select one</option>'.$drop_down;

		if($this->fmisc[0] != ''	&&  $this->simple == 0){
			$stg='onchange=\'CallFmisc($("#'.$key.' option:selected").val(),'.$fmiscvalue.')\'';
		}

		$drop_down_head	=	'<select name="'.$key.'" id="'.$key.'" '.$stg.' >';

		$drop_down = $drop_down_head.$drop_down;
		//die($drop_down);
		$thistitle	= explode('_',$field);
		if($thistitle[0] ==	$thistitle[1] ){
			//when field names are stupid like postcode_postcode
			$title=$thistitle[0];
		}else{
			$title=$field;
		}

		/*******hack for times ****************/
		if(strstr($title, 'time')){
			$time_title	= explode('_',$key);
			$title = $title." ".$time_title[1];
		}
		/****************************************/

		$title	=	str_replace("description", '', $title);//or postcode_description

		if($name){
			$created_field=$this->create_table_line($name,$drop_down.$js);
		}else{
			$created_field=$this->create_table_line($title,$drop_down.$js);
		}

		return $created_field;

		return $drop_down;
	}
	private function create_dropdown($table,$field,$key, $rel){
		$thistable	= substr($table,4);//explode('_',$table);
		$table =	$this->DBobject->GetTable($table,$thistable."_deleted is null");
		$fmiscvalue = 'null';
		if($table){
			foreach ($table as $line) {
				if($this->fields[$key]==$line[0]){
					$selected='selected="selected"';
					$fmiscvalue=$line[0];
				}else{
					$selected='';
				}
				$drop_down	.=	'<option value="'.$line[0].'" '.$selected.'>'.unclean($line[$field]).'</option>';
			}
		}
		$drop_down	.=	'</select>';

		$drop_down	=	'<option value="null" >Please select one</option>'.$drop_down;

		if($this->fmisc[0] != ''	&&  $this->simple == 0){
			$stg='onchange=\'CallFmisc($("#'.$key.' option:selected").val(),'.$fmiscvalue.')\'';
		}

		$drop_down_head	=	'<select name="'.$key.'" id="'.$key.'" '.$stg.' '.$rel.' >';

		$drop_down = $drop_down_head.$drop_down;
		$thistitle	= explode('_',$field);
		if($thistitle[0] ==	$thistitle[1] ){
			$title=$thistitle[0];
		}else{
			$title=$field;
		}

		$title	=	str_replace("description", '', $title);
		$created_field=$this->create_table_line($title,$drop_down.$js);
		return $created_field;
	}
	function create_mutiple_dropdown_field($table,$field,$key){

		$class="class='dropdown'" ;

		$values = unserialize($this->fields[$field_name]);
		$plus="&nbsp;<a href='javascript:void(0);' onclick='Addfield();'><img src='/admin/images/add_icon.png' alt='Add' title='Add' style='width: 20px;border-style: none;' ></a>
					   <a href='javascript:void(0);' onclick='Removefield();'><img src='/admin/images/sub_icon.png' alt='Substract' title='Substract' style='width: 20px;border-style: none;'></a>
					   ";
		if($values){
			foreach ($values as $value) {
				$dropdown_fields.= $this->create_dropdown($table, $field, $key, "rel='multi-dropdown'");
			}
		}else{
			$dropdown_fields.=$this->create_dropdown($table, $field, $key, "rel='multi-dropdown'");
		}
		$dropdown_fields.="<script>
				function Addfield(){
				var new_field = ".$this->create_dropdown($table, $field, $key, "rel='multi-dropdown'").";
				$('[rel=multi]').last().after(new_field);
				//$( '.datepicker' ).datepicker( { changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );
				}
				function Removefield(){
				$('[rel=multi]').last().remove();
				//$('[rel=multi]').last().remove();
				}
				</script>";
		$dropdown_fields.=$plus;//."<script>$(function() {	$( '.datepicker' ).datepicker( { dateFormat: 'yy-mm-dd' } );});	</script>";

		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$dropdown_fields);
		return $created_field;

	}

	function create_table_line($title,$field){

		/**not a nice way to get the field  name **/
		$name = explode('name=', $field);
		$name = substr( $name[1], 1 );
		$arr= explode('"', $name);
		$name	=	$arr[0];

		$this_buf = "<tr style=\"\"><td><span class='title'>".(str_ireplace("_", " ", $title))."</span></td><td>".$field."</td></tr>";

		/**********************************************************************************/
		return $this_buf;
	}
	/** End Form Create Region **/
}