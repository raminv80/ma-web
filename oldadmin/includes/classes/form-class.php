<?php

/**
 * Enter description here ...
 * @author Nick
 *
 */
class form_class extends misc{

	public $misc;
	public $simple;
	public $redirect;

	/**
	 * Enter description here ...
	 * @param unknown_type $misc
	 * @param unknown_type $id
	 * @param unknown_type $simple
	 * @param unknown_type $limit
	 * @param unknown_type $count
	 * @param unknown_type $where
	 * @param unknown_type $order_by
	 * @param unknown_type $includefiles
	 */
	function __construct($misc,$id=null,$simple,$limit='', $count=0, $where='',  $order_by='',$includefiles=0){
		
		if($simple	!= null){
			$this->simple=$simple;
		}else{
			$this->simple=0;
		}

		parent::__construct($misc);
		
		$this->misc = $misc;
		
		if($id != null){
			$this->id=$id;
			$tmp_arr=$this->get_misc($this->id, $limit='', $count=0, $where='',  $order_by='',$includefiles=0);
			$this->fields	=	$tmp_arr[0];
		}
		
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $action
	 * @return string
	 */
	function create_form($action){

		$this->action=$action;
		
		if($this->simple == 0){
		    if($this->canedit){
			    $form_butf.='<form name="'.$action.'" id="'.$action.'" method="post" action="/admin/includes/processes/admin-processes-general.php" accept-charset="UTF-8">';
		    }
			$div_id='id="div_'.$this->misc.'"';
			$form_butf.=load_text_editor();
		}else{
			$div_id='id="div2"';
		}
		

		$form_butf.='<div '.$div_id.'><table style="width:70%;"  >';
		//get buf for regular fields
		foreach ($this->fields_type as $key => $field) {

			if(!in_array($key,$this->private_fields) && !key_exists($key,$this->foreign_fields) && !in_array($key,$this->linked_foreign_fields)){
				if(strpos($key,'latitude') == true){
					$lat = unclean($this->fields[$key]);
					if(empty($lat)){
						$lat = '-34.9333';
					}
				}
				if(strpos($key,'longitude') == true){
					$lon = unclean($this->fields[$key]);
					if(empty($lon)){
						$lon = '138.5833';
					}
				}

				if(strpos($key, 'password') == false){
					switch ($field) {

						case 'varchar(255)': //text field
							$form_butf.=$this->create_text($key,255);
							break;
						case 'text': //wyswyg editor
							$form_butf.=$this->create_text_area($key);
							break;
						case 'datetime':
 						//case 'timestamp':
						case 'date':		//date!
							$form_butf.=$this->create_date($key);
							break;
						case 'int(1)': //check
							$form_butf.=$this->create_check_box($key);
							break;
						case 'decimal(12,2)':
							$form_butf.=$this->create_number_field($key, 13);
							break;
						case 'decimal(12,0)':
							$form_butf.=$this->create_number_field($key, 12);
							break;
						case 'double':
							$form_butf.=$this->create_number_field($key);
							break;
						default:
						    if(strstr($field,'varchar')){
						        $val = str_replace('varchar(', '', $field);
						        $val = str_replace(')', '', $val);
						        $form_butf.=$this->create_text($key,$val);
							    break;
						    }
						    break;
					}
				}else{
					$form_butf.=$this->create_password_field($key);
				}

				if(!empty($lat) && !empty($lon)){
					$map_butf .= '<br/>
								<table class="GmlTable" onload="GmlLoader();" style="width:100%;">
						        <tbody>
						             <tr>
						                <td colspan="3">
						                    <div class="GmlTitle">
						                        <a title="Google Maps Locator" href="#" >Google Maps Location Finder</a>
						                    </div>
						                </td>
						            </tr>
						            <tr>
						                <td colspan="3">
						                    <div id="GmlMap" class="GmlMap">
						                        Loading Map....
						                    </div>
						                </td>
						            </tr>
						        </tbody>
						    </table>
						    <br/>
						    <div class="clear"></div>
							<script>
								jQuery(document).ready(function() {
									centerOn('.$lat.','.$lon.');
								});
							</script>';
					unset($lat);
					unset($lon);
				}
			}

		}
		
		///get the foreign fields
		foreach ($this->foreign_fields as $link) {
			if(!empty($link["link_tbl"])){
				if($link['field_name'] != 'rep_name'){
				$form_butf .= $this->create_multi_select($link);
				}else{
				$form_butf .=$this->create_single_select($link);
				}
			}
		}
		

		if($_SESSION["Backto"]){
			$back = $this->create_back_button();
		}
		if($this->canedit){
    		$form_butf.='<tr><td>&nbsp;</td><td>
    		<input type="hidden" name="redirect" value="'.$_SERVER['REQUEST_URI'].'" />
    		<input type="submit" value="Submit" />&nbsp;'.$back.'
    		</td></tr>';
		}

		$form_butf.='</table>'.$map_butf.'</div>';
		

		if($this->simple == 0){
			if($this->redirect	!= ''){
				$form_butf.=$this->create_hidden('redirect',$this->redirect);
			}
			$form_butf.=$this->create_hidden('Action',$action);
			$form_butf.=$this->create_hidden('misc_id',$this->id);
			$form_butf.=$this->create_hidden('misc',$this->misc);

			if($this->canedit){
			    $form_butf.=''.insertToken().'</form>';
			}
		}

		if(count($this->fmisc) > 0 &&  $this->simple == 0){

		    foreach ($this->fmisc as $origin => $field) {
		         $form_butf  .=	$this->IncludeForeignTable($origin,$this->id,$field[0],$field[1]);
				 if(empty($this->fields[$field[0]])){
		            $form_butf.='<div id="div2"><!-- Please select a value to continue --></div>';
		        }
		    }
		}

		if($this->linkfiles	== 	'1' && $this->simple==0){
			$form_butf.=LinkedFiles($this->misc, $this->id);
		}

		return $form_butf;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @param unknown_type $field_value
	 * @return string
	 */
	function create_hidden($field_name,$field_value){
		$hidden_field='<input type="hidden" name="'.$field_name.'" value="'.$field_value.'">';
		return $hidden_field;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @param unknown_type $length
	 * @return string
	 */
	function create_text($field_name,$length=''){
	    if($length){
		    $text_field="<input type=text maxlength=\"".$length."\" name=\"$field_name\" id='$field_name' style='width:250px;' value=\"".unclean($this->fields[$field_name])."\">";
	    }else{
	        $text_field="<input type=text name=\"$field_name\" id='$field_name' style='width:250px;' value=\"".unclean($this->fields[$field_name])."\">";
	    }
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$text_field);
		return $created_field;

	}
	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @param unknown_type $length
	 * @return string
	 */
	function create_number_field($field_name, $length=''){
		if($length){
		    $text_field="<input type=text maxlength=\"".$length."\" name=\"$field_name\" id='$field_name' style='width:250px;' onBlur=\"validate_".$field_name."();\" value=\"".unclean($this->fields[$field_name])."\">";
	    }else{
	        $text_field="<input type=text name=\"$field_name\" id='$field_name' style='width:250px;' onBlur=\"validate_".$field_name."();\" value=\"".unclean($this->fields[$field_name])."\">";
	    }
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$text_field.$js);
		return $created_field;

	}

	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @param unknown_type $dynamic
	 * @return string
	 */
	function create_multi_text($field_name,$dynamic){
		if(strpos($field_name, "date")){
			$date="class='datepicker'" ;
			$date2="class=\"datepicker\"";
		}
		$values = unserialize($this->fields[$field_name]);
		if($dynamic){
			$plus="&nbsp;<a href='javascript:void(0);' onclick='Addfield".$field_name."();'><img src='/admin/images/add_icon.png' alt='Add' title='Add' style='width: 20px;border-style: none;' ></a>
				   <a href='javascript:void(0);' onclick='Removefield".$field_name."();'><img src='/admin/images/sub_icon.png' alt='Substract' title='Substract' style='width: 20px;border-style: none;'></a>
				   ";
		}
		if($values){
			foreach ($values as $value) {
				$text_field.="<input type='text' maxlength=\"25\" name=\"".$field_name."[]\" style='width:250px;' value='".unclean($value)."' $date rel='multi'\" style=\"float:left;\">

					";

			}
		}else{
			$text_field.="<input type='text' name=\"".$field_name."[]\" style='width:250px;' value='".(unclean($this->fields[$field_name]) == '0000-00-00' ? '00-00-0000': date('d-m-Y',strtotime(unclean($this->fields[$field_name]))))."' $date  rel='multi' >";
		}
		$text_field.="<script>
		function Addfield".$field_name."(){
		var new_field = '<input type=\"text\"  name=\"".$field_name."-datepicker[]\" style=\"width:250px;\"  class=\"".($date!=''?'datepicker':'')."\"   rel=\"multi\" style=\"float:left;\"> ';
		$('[rel=multi]').last().after(new_field);
		$( '.datepicker' ).datepicker( {changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );
		}
		function Removefield".$field_name."(){
		$('[rel=multi]').last().remove();
		//$('[rel=multi]').last().remove();
		}
		</script>";
		$text_field.=$plus."<script>$(function() {	$( '.datepicker' ).datepicker( { changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );});	</script>";

		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$text_field);
		return $created_field;

	}
	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @return string
	 */
	function create_check_box($field_name){
		$text_field="<input type='checkbox' name=\"$field_name\" id='$field_name'  ".($this->fields[$field_name] == '1'?'checked="checked" value="1"':'value="0"' )." >";
		$text_field.="<script>
		$('#".$field_name."').click(function(){
			if($('#".$field_name."').attr('checked') == 0){
			$('#".$this->action."').append(\"<input type='hidden' name='".$field_name."' value='0' id='temp_".$field_name."'>\");
			$('#".$field_name."').attr('value','0');

			}
			if($('#".$field_name."').attr('checked') == 1){
			$('#temp_".$field_name."').remove();
			$('#".$field_name."').attr('value','1');

			}

		});
		</script>";
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$text_field);
		return $created_field;
	}
	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @return string
	 */
	function create_text_area($field_name){
		$text_area='<textarea id="'.$field_name.'" name="'.$field_name.'" style="width:60%" class="mce">'.unclean($this->fields[$field_name]).'</textarea>';
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$title=str_replace("_", ' ', $title);
		$created_field=$this->create_table_line($title,$text_area);
		return $created_field;

	}
	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @return string
	 */
	function create_date($field_name){
		$date_field="<script>$(function() {	$( '.datepicker' ).datepicker( { changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );});	</script>";
		$date_field.="<input type=text name=\"".$field_name."-datepicker\" class='datepicker'  value='".((unclean($this->fields[$field_name]) == '0000-00-00' || !unclean($this->fields[$field_name]) )? '00-00-0000': date('d-m-Y',strtotime(unclean($this->fields[$field_name]))))."'>";
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$date_field);
		return $created_field;

	}
	/**
	 * Enter description here ...
	 * @param unknown_type $field_name
	 * @return string
	 */
	function create_password_field($field_name){
		$text_field="<input type='password' name=\"$field_name\" id='$field_name' style='width:250px;' >";
		$title=str_replace($this->table_prefix."_", '', $field_name);
		$created_field=$this->create_table_line($title,$text_field);
		return $created_field;

	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $tablename
	 * @param unknown_type $field
	 * @param unknown_type $key
	 * @param unknown_type $title
	 * @param unknown_type $rel
	 * @return string
	 */
	function create_linked_dropdown_field($tablename,$field,$key,$title='',$rel=''){
		$thistable	= substr($tablename,4);//explode('_',$table);
		$table =	$this->DBobject->GetTable($tablename,$thistable."_deleted is null",'',$thistable."_id" );
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
	
		if(!$title){
			$title=str_replace($this->table_prefix."_", '', $field);//or postcode_description
		}
	
		$created_field=$this->create_table_line($title,$drop_down);
		
		$drop_down = '';
		$misc_id = GetAnyCell('cfg_id', 'tbl_cfg', 'cfg_tblname ="'.$tablename.'"');
		$misc = new misc($misc_id);
		$lfield = $this->linked_foreign_fields[$key];
		$ffield = str_replace($this->table_prefix."_", '', $lfield[0]);
		$link_field = $misc->foreign_fields[$ffield];
		if($link_field){
			$f_tablename = $this->GetTableforfield($link_field[0]);
			$f_thistable= substr($f_tablename,4);
			$value = unserialize(GetAnyCell($ffield, $misc->table, $misc->id_name.' ="'.$fmiscvalue.'"'));
			if($value){
			foreach ($value as $fkey) {
				$line = GetRow($f_tablename, $f_thistable.'_id ="'.$fkey.'"');
				if($this->fields[$lfield[0]]==$line[0]){
					$selected='selected="selected"';
				}else{
					$selected='';
				}
				
				$drop_down	.=	'<option value="'.$line[0].'" '.$selected.'>'.unclean($line[$link_field[0]]).'</option>';
			}}
			$drop_down	.=	'</select>';
			$drop_down	=	'<option value="null" >Please select one</option>'.$drop_down;
			
			$drop_down_head	=	'<select name="'.$lfield[0].'" id="'.$link_field[0].'" '.$stg.' >';
			$drop_down = $drop_down_head.$drop_down;
			$js = '<script>
						jQuery("#'.$key.'").change( function(){
							jQuery.post("/admin/includes/processes/processes-ajax.php",{misc_id:'.$misc_id.',ffield:"'.$ffield.'",link_field:"'.$link_field[0].'",id:jQuery(this).attr("value"),Action:"GetFMiscFValues"},function(data){
							var options = \'<option value="null" selected="selected">Please Select One</option>\';
							var str_arr = data.split(":");
							for(str in str_arr){
								if(str_arr[str] != ""){
									var values = str_arr[str].split("&&");
									options += \'<option value="\'+values[0]+\'" >\'+values[1]+\'</option>\';
								}
							}
							jQuery("#'.$link_field[0].'").html(options);
						});
						});
			 	  </script>';
		}
		
		$created_field.=$this->create_table_line($title,$drop_down.$js);
	
		return $created_field;
	
		return $drop_down;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $link
	 * @return string
	 */
	private function create_multi_select($link){
		/*Initialise Link variables based on cfg settings */
		$link_tbl		= new Table($link['link_tbl']);
		$foreign_tbl	= new Table($link['foreign_tbl']);
		$field_name		= $link['field_name'];
		if($link['title'] && $link['title'] != ""){
			$title = $link['title'];
		}else{
			$title = str_replace('_', ' ', $field_name);
		}
		/*END initialise*/
		
		$foreign_link = 'link_2_id';
		$id = 'link_1_id';
		$values = array();
		
		//$tmp = $this->DBobject->GetColumn($foreign_link, $link_tbl->table, "{$id} = '{$this->id}'");
		$sql = "SELECT {$foreign_link} FROM {$link_tbl->table} WHERE {$id} = '{$this->id}' AND link_deleted IS NULL";
		$tmp = $this->DBobject->wrappedsql($sql);
		if(is_array($tmp)){
			foreach($tmp as $val){
				$values[] = $val[$foreign_link];
			}
		}
		
		$table = $this->DBobject->GetTable($foreign_tbl->table, '', '', $foreign_tbl->id_name);

		$v = array();
		$s = array();
		if(!empty($table)){
			foreach($table as $line){
				
				if(in_array($line[$foreign_tbl->id_name], $values)){
					//$selected = 'selected="selected"';
					
					$s[$line[$foreign_tbl->id_name]] = 'selected="selected"';
				}else{
					//$selected = '';
					$s[$line[$foreign_tbl->id_name]] = '';
				}
				$v[$line[$foreign_tbl->id_name]] = unclean($line[$field_name]);
				//$multi_select.= '<option value="'.$line[$foreign_tbl->id_name].'" '.$selected.'>'.unclean($line[$field_name]).'</option>';
			}
		}
		
		asort($v);
		foreach($v as $key=>$val){
			if($link_tbl->table != 'tbl_link_video_user'){
				$multi_select.= '<option value="'.$key.'" '.$s[$key].'>'.$val.'</option>';
			}else{
				$multi_select.= $val!=''?trim($val)."\n":'';
			}
		}
		if($link_tbl->table != 'tbl_link_video_user'){
			$multi_select.= '</select>';
		}else{
			$multi_select.= '</textarea>
							<script>
							$("#user_emails").focus(function() {
						    var $this = $(this);
						    $this.select();
							// Work around Chromes little problem
							$this.mouseup(function() {
							// Prevent further mouseup intervention
							$this.unbind("mouseup");
							 return false;
							    });
							});
							</script>';
									}
		if($link_tbl->table != 'tbl_link_video_user'){
		$multi_select_head = '<select multiple size="10" name="'.$link["link_tbl"].'[]" class="'.$link_tbl->table.'" >
								<option value=""></option>';
		}else{
			$multi_select_head = '<textarea  style="width: 263px; height: 112px;" id="user_emails">';
		}
		$multi_select = $multi_select_head.$multi_select;
		
		$multi_select = $this->create_table_line($title, $multi_select);
		return $multi_select;
	}
	
/**
	 * Enter description here ...
	 * @param unknown_type $link
	 * @return string
	 */
	private function create_single_select($link){
		/*Initialise Link variables based on cfg settings */
		$link_tbl		= new Table($link['link_tbl']);
		$foreign_tbl	= new Table($link['foreign_tbl']);
		$field_name		= $link['field_name'];
		if($link['title'] && $link['title'] != ""){
			$title = $link['title'];
		}else{
			$title = str_replace('_', ' ', $field_name);
		}
		/*END initialise*/
		
		$foreign_link = 'link_2_id';
		$id = 'link_1_id';
		$values = array();
		
		//$tmp = $this->DBobject->GetColumn($foreign_link, $link_tbl->table, "{$id} = '{$this->id}'");
		$sql = "SELECT {$foreign_link} FROM {$link_tbl->table} WHERE {$id} = '{$this->id}' AND link_deleted IS NULL";
		$tmp = $this->DBobject->wrappedsql($sql);
		if(is_array($tmp)){
			foreach($tmp as $val){
				$values[] = $val[$foreign_link];
			}
		}
		
		$table = $this->DBobject->GetTable($foreign_tbl->table, '', '', $foreign_tbl->id_name);

		$v = array();
		$s = array();
		if(!empty($table)){
			foreach($table as $line){
				
				if(in_array($line[$foreign_tbl->id_name], $values)){
					//$selected = 'selected="selected"';
					
					$s[$line[$foreign_tbl->id_name]] = 'selected="selected"';
				}else{
					//$selected = '';
					$s[$line[$foreign_tbl->id_name]] = '';
				}
				$v[$line[$foreign_tbl->id_name]] = unclean($line[$field_name]);
				//$multi_select.= '<option value="'.$line[$foreign_tbl->id_name].'" '.$selected.'>'.unclean($line[$field_name]).'</option>';
			}
		}
		
		asort($v);
		foreach($v as $key=>$val){
			if($link_tbl->table != 'tbl_link_video_user'){
				$multi_select.= '<option value="'.$key.'" '.$s[$key].'>'.$val.'</option>';
			}else{
				$multi_select.= $val!=''?trim($val)."\n":'';
			}
		}
		if($link_tbl->table != 'tbl_link_video_user'){
			$multi_select.= '</select>';
		}else{
			$multi_select.= '</textarea>
							<script>
							$("#user_emails").focus(function() {
						    var $this = $(this);
						    $this.select();
							// Work around Chromes little problem
							$this.mouseup(function() {
							// Prevent further mouseup intervention
							$this.unbind("mouseup");
							 return false;
							    });
							});
							</script>';
									}
		if($link_tbl->table != 'tbl_link_video_user'){
		$multi_select_head = '<select  name="'.$link["link_tbl"].'[]" class="'.$link_tbl->table.'" >
								<option value=""></option>';
		}else{
			$multi_select_head = '<textarea  style="width: 263px; height: 112px;" id="user_emails">';
		}
		$multi_select = $multi_select_head.$multi_select;
		
		$multi_select = $this->create_table_line($title, $multi_select);
		return $multi_select;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $link
	 * @return string
	 */
	function create_linked_mutiple_dropdown_field($link){
		
		$link_tbl = new Table($link["link_tbl"]);
		$foreign_tbl = new Table($link["foreign_tbl"]);
		$field_name = $link["field_name"];
		if($link["title"] && $link["title"] != "")
		{ $title = $link["title"]; }
		else 
		{ $title = str_replace("_", " ", $field_name); }

		$foreign_link = "link_2_id";
		$values = $this->DBobject->GetColumn( $foreign_link , $link_tbl->table , $foreign_tbl->table_prefix."_deleted IS NULL");
		$table = $this->DBobject->GetTable($foreign_tbl->table,$foreign_tbl->table_prefix."_deleted IS NULL",'',$foreign_tbl->id_name);

		if($table){
			foreach($table as $line){
				if(in_array($line[$foreign_tbl->id_name], $values)){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$multi_select.= '<option value="'.$line[$foreign_tbl->id_name].'" '.$selected.'>'.unclean($line[$field_name]).'</option>';
			}
		}
		$multi_select.= '</select>';
		$multi_select_head = '<select multiple size="10" name="'.$link["link_tbl"].'[]" class="'.$link_tbl->table.'" >';
		$multi_select = $multi_select_head.$multi_select;
		$multi_select = $this->create_table_line($title, $multi_select);
		
				
		$drop_down = '';
		$misc_id = GetAnyCell('cfg_id', 'tbl_cfg', 'cfg_tblname ="'.$tablename.'"');
		$misc = new misc($misc_id);
		$lfield = $this->linked_foreign_fields[$key];
		$ffield = str_replace($this->table_prefix."_", '', $lfield[0]);
		$link_field = $misc->foreign_fields[$ffield];
		if($link_field){
			$f_tablename = $this->GetTableforfield($link_field[0]);
			$f_thistable= substr($f_tablename,4);
			
			$first = true;
			$fval = unserialize( $this->fields[$lfield[0]] );
			if($fval){
				foreach($fval as $v){
					if(!$first){
					}else{
						$drop_down .='<br/>';
					}
					$drop_down .= '<select name="'.$lfield[0].'[]" class="'.$link_field[0].'" '.$stg.' rel="multi'.$field.'">';
					$drop_down .= '<option value="null" >Please select one</option>';
					foreach($fmiscvalues as $fmiscvalue){
						$value = unserialize(GetAnyCell($ffield, $misc->table, $misc->id_name.' ="'.$fmiscvalue.'"'));
						if($value){
							foreach ($value as $fkey) {
								$line = GetRow($f_tablename, $f_thistable.'_id ="'.$fkey.'"');
								if( $line[0] == $v ){
									$selected='selected="selected"';
								}else{
									$selected='';
								}
								$drop_down .= '<option value="'.$line[0].'" '.$selected.'>'.unclean($line[$link_field[0]]).'</option>';
							}
						}
					}
					$drop_down .= '</select>';
				}
			}else{
				$drop_down .= '<select name="'.$lfield[0].'[]" class="'.$link_field[0].'" '.$stg.' rel="multi'.$field.'">';
				$drop_down .= '<option value="null" >Please select one</option>';
				foreach($fmiscvalues as $fmiscvalue){
					$value = unserialize(GetAnyCell($ffield, $misc->table, $misc->id_name.' ="'.$fmiscvalue.'"'));
					if($value){
						foreach ($value as $fkey) {
							$line = GetRow($f_tablename, $f_thistable.'_id ="'.$fkey.'"');
							$selected='';
							$drop_down	.=	'<option value="'.$line[0].'" '.$selected.'>'.unclean($line[$link_field[0]]).'</option>';
						}
					}
				}
				$drop_down	.=	'</select>';
			}
			
			$default_dd .= '<select name="'.$lfield[0].'[]" class="'.$link_field[0].'" '.$stg.' rel="multi'.$field.'">';
			
			$js = '<script>
						/*jQuery(".'.$key.'").change( function() { UpdateThis(); alert("hi"); });*/
						/*jQuery(".'.$key.'").select().change( function() { UpdateThis(); alert("hi"); });*/
						
						function UpdateThis(id){
							jQuery.post("/admin/includes/processes/processes-ajax.php",{misc_id:'.$misc_id.',ffield:"'.$ffield.'",link_field:"'.$link_field[0].'",id:ArrayElement("'.$key.'"),Action:"GetFMiscFValues"},function(data){
								var options = \'<option value="null" selected="selected">Please Select One</option>\';
								var str_arr = data.split(":");
								for(str in str_arr){
									if(str_arr[str] != ""){
										var values = str_arr[str].split("&&");
										options += \'<option value="\'+values[0]+\'" >\'+values[1]+\'</option>\';
									}
								}
								jQuery(".'.$link_field[0].'").html(options);
							});
						}
						
						function ArrayElement(id){
							
							var values = {};
							var n = 0;
							jQuery("."+id).each(function(arr){
								values[n] = jQuery(this).val();
								n++;
							});
							return values;
						}

			 	  </script>';
		}

		$created_field.=$this->create_table_line($title,$drop_down.$js.$plus);
		return $created_field;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $title
	 * @param unknown_type $field
	 * @return string
	 */
	private function create_table_line($title,$field){
	
		/**not a nice way to get the field  name **/
		$name = explode('name=', $field);
		$name = substr( $name[1], 1 );
		$arr= explode('"', $name);
		$name	=	$arr[0];

		if($this->addjs[$name]){
			if(key_exists($name, $this->addjs)){
				$js = "<script>".$this->addjs[$name][0]."</script>";
			}
		}

		// Temporay hack for field lables.
		$this_buf = "<tr style=\"\"><td><strong>".strtoupper(str_ireplace("_", " ", $title))."</strong></td><td>".$field." ".$js."</td></tr>";
		if(strtoupper(str_ireplace("_", " ", $title)) == 'LINK'	||	strtoupper(str_ireplace("_", " ", $title)) == 'VIDEO'){
			$this_buf.='<tr><td colspan="2"><small>Please enter YouTube video ID <br/>eg. From the video url http://www.youtube.com/watch?v=[USE THIS PART]</small></td></tr>';	
		}
		if(strstr(strtoupper(str_ireplace("_", " ", $title)),'TAGS') ){
			$this_buf.='<tr><td colspan="2"><small>Please enter TAGS separated by commas<br/>eg. tag1,tag2,other tag3,tag4</small></td></tr>';	
		}
		if(strstr(strtoupper(str_ireplace("_", " ", $title)),'EMAIL') ){
			$this_buf.='<tr><td>&nbsp;</td><td>
						<div id="user_option" style="display:none;">
						Please select users to email:<br/>
						<select multiple="multiple" name="userstomail[]" style="width: 270px;margin: 5px 0 5px 0px; ">';
						$users = $this->DBobject->GetTable('tbl_user',"user_deleted is null AND user_active = '1' ",'',"user_id" );
						if(!empty($users)){
							foreach ($users as $user) {
								$this_buf.='<option value="'.$user['user_id'].'">'.$user['user_business_name'].' - '.$user['user_business_email'].'</option>';
							}
						}
			$this_buf.='</select><br>
						<small>To make multiple selections hold down \'CTRL\' (PC users) or \'COMMAND\' (mac users)</small>
						</td>
						</div>
						<script>
						$("#bulletin_email").click(
							function(){
								$("#user_option").fadeToggle("slow");
						});
						</script>
						</tr>';	
		}
		//die(print_r($this->addphp));
		/****if you know a a safer way to do this , let me know [i dont like this ]********/
		if($this->addphp[$name]){
			if(key_exists($name, $this->addphp)){
				eval($this->addphp[$name][0]);
				
			}else{
		}
		}
		
		/**********************************************************************************/
		return $this_buf;
	}
	
	/**
	 * Enter description here ...
	 * @return string
	 */
	function ListMisc(){

		$buf.="<table width='100%' id='misc_table' class='tablesorter'>";

		$buf .="<thead><tr>";
		foreach ($this->listfields as $field){
			if(key_exists($field,$this->foreign_fields)){
				if($this->foreign_fields[$field][1]){
					$field = str_replace('_',' ',$this->foreign_fields[$field][1]);
				}else{
					$field = str_replace('_',' ',$this->foreign_fields[$field][0]);
				}
				$buf.="<th  bgcolor='#F0F0EE'>".strtoupper($field)."</th>";
			}else{
				$field = str_replace($this->table_prefix.'_','',$field);
				$field = str_replace('_',' ',$field);
				$buf.="<th  bgcolor='#F0F0EE'>".strtoupper($field)."</th>";
			}
		}

		$buf.="<td  bgcolor='#F0F0EE' colspan='4'>ACTIONS&nbsp;";

		if($this->canexport	==	'1'){
			$buf.="&nbsp;<a href='javascript:void(0);' onclick='CreateCSV(".$this->misc.",1);'><img src='images/csv.gif' style='width:22px;float:right;border-style:none;' alt='create CSV' title='create CSV' /></a>
			";
		}
		if($this->email_list	==	'1'){
			$buf.="<a href='javascript:void(0);' onclick='SendEmail();' >
				<img src='images/email_icon.png' s style=\"width: 22px; float: right; border-style: none; padding-left: 10px; padding-right: 10px;\"	float: right;' alt='Send Email' title='Send Email'>
				</a>
			";
		}
		if($this->canadd	==	'1'){
			$buf.="&nbsp;<a href='edit_misc.php?misc=".$this->misc."' >
			<img src='images/add_icon.png' style='width: 22px;float: right; border-style: none;'	float: right;' alt='Add Item' title='Add Item'>
			</a>
			";
		}
		$buf.='</td></tr></thead>';

		$orderby = '';
		if(count($this->orderby) >=1){
		    $orderby = '';
		    foreach($this->orderby as $key => $val){
		        if($key && $key != '' && $val && $val[0] !='' ){
		            $orderby.= ' '.$key.' '.$val[0].',';
		        }
		    }
		    $orderby = trim($orderby,',');
		}
		$filter = '';
		if(count($this->filter) >=1){
			$filter = '';
			foreach($this->filter as $key => $val){
				if($key && $key != '' && $val && $val[0] !='' ){
					$filter.= ' '.$key.' IS NOT NULL,';
				}
			}
			$filter = trim($filter,',');
		}
		if(count($this->listfields) >=1 && $orderby == ''){
		    $orderby = $this->listfields[0];
		}


		$misc_arr	=	$this->get_misc(0,'',0,$filter,$orderby);
		if($misc_arr){
			foreach ($misc_arr as $line) {
				$buf.="<tr>";
				foreach ($this->listfields as $field_name => $field) {
					if(key_exists($field,$line)){
						if(key_exists($field,$this->foreign_fields)){
							$val = $this->GetValueForFF($field,$line[$field]);
							$m_id = $this->GetMiscIDForFF($field,$line[$field]);
							$mr_id = $this->GetMiscRecordIDForFF($field,$line[$field]);
							//die($field);
							if($m_id){
							    $buf.="<td><a href='edit_misc.php?id=".$mr_id."&misc=".$m_id."'>$val</a></td>";
							}else{
							    $buf.="<td>$val</td>";
							}
						}else{
						    if(strpos($field,'date') && $line[$field] != ''){
						        $val = date('d/m/Y   H:m:s',strtotime($line[$field]));
						        $buf.="<td><a href='edit_misc.php?id=".$line[$this->id_name]."&misc=$this->misc'>$val</a></td>";
						    }else{
							    $buf.="<td><a href='edit_misc.php?id=".$line[$this->id_name]."&misc=$this->misc'>$line[$field]</a></td>";
						    }
						}
					}
				}
				$buf.="<td>";
				if($this->candelete == '1'){
					$buf.="<a href='javascript:void(0);' onclick='DE(".$line[$this->id_name].");'>Delete";
				}
				$buf.="</td>";

				$buf.="<td><a href='edit_misc.php?id=".$line[$this->id_name]."&misc=$this->misc'>View/Edit</td>";
				if($this->email_list ==	'1'){
					foreach ($this->fields as $key => $field) {
						if(strstr( $key,'email')){
							$email_field = $key;
						}
					}
					$buf.="<td><input type='checkbox' name=\"email\" value='".$line[$email_field]."'></td>";
				}else{
					$buf.="<td>&nbsp;</td>";
				}
				$buf.="</tr>";
			}
		}
		$buf.='</table>';
		$buf.='<form name="DeleteEntry" method="post" id="DeleteEntry" action="/admin/includes/processes/admin-processes-general.php">
			<input type="hidden" name="redirect" value="'.$_SERVER['REQUEST_URI'].'" />
			<input type="hidden" name="misc" value="'.$this->misc.'" id="misc"	>
			<input type="hidden" name="Action" value="DeleteMiscEntry" >
			<input type="hidden" name="entry" value="" id="entry">
			'.insertToken().'
			</form>';

		$buf.='<script>
				$(document).ready(function()
				    {
				        $("#misc_table").tablesorter();
				    }
				);
				</script>';

		if($this->email_list ==	'1'){
			$buf.='<div id="dialog"  title="" style="display:none;"></div>';
			$text_editor = load_text_editor();
			if(!strstr($buf ,$text_editor )){
				$buf.= reload_text_editor();
			}

			$buf.=$this->SendEmailJS();
		}
		return $buf;
	}

	/**
	 * Enter description here ...
	 * @return string
	 */
	function create_back_button(){
		$button = '<button type="button"  onclick="javascript:window.location=\''.$_SESSION["Backto"].'\';" >Back</button>	';
		$_SESSION["Backto"] ='';
		unset($_SESSION["Backto"]);
		return $button;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $Field
	 * @param unknown_type $parent
	 * @return string
	 */
	function CallForeingMiscJS($Field,$parent){

		$string="<script>
			function  CallFmisc(Mist_ID,List_ID){
							if(Mist_ID != 'null'){
							$('#div2').load('/admin/includes/processes/processes-ajax.php', { Mist_ID:Mist_ID,List_ID:'".$this->id."',Field:'".$Field."',Action:'GetForeingMisc',Parent:'".$parent."'});
							}
	}
			</script>";
		return $string;
	}
	/**
	 * Enter description here ...
	 * @return string
	 */
	function SendEmailJS(){
		$buf = '
		<script>
		function SendEmail(){

			$("#dialog").attr("title","Send Email")
			$("#dialog").html(\'<p><h3>Send email to '.$this->plural.'</h3></p><br><div id="email_res"><table width="100%"><tr><td><strong>Subject</strong></td><td><input type="text" name="email_subject" id="email_subject" value=""></td></tr><tr><td><strong>Email Content</strong></td><td><textarea id="email_content" name="email_content" style="width: 300px; height: 300px;"  class="mce"></textarea></td></tr><td>&nbsp;</td><td><br><input type="button" name="send email"  value="Send Email" onclick="Send();"></td></tr></table></div>\');
			$("#dialog").dialog({
				 width: 800,
				 height: 530,
				 open: function(){Reload();}
			 });
		}
		function Send(){
		var content  =  $("#email_content").html()
		var subject  =  $("#email_subject").html();
		var emaillist	= "@";
			$("[name=email]:checked").each(function() {
				emaillist = emaillist + "," + $(this).attr("value");
			});
		$("#email_res").load("/admin/includes/processes/processes-ajax.php", { content:content,emaillist:emaillist,subject:subject, Action:"SendEmails"});
		}
		</script>
		';
		return $buf;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $this_misc
	 * @param unknown_type $parent_id
	 * @param unknown_type $field_val
	 * @param unknown_type $title
	 * @return Ambigous <string, unknown>
	 */
	function IncludeForeignTable($this_misc,$parent_id,$field_val,$title) {
		if($this_misc	!= ''){
			if($parent_id){
				$misc_obj	=	new misc($this_misc);
				$field = $misc_obj->table_prefix."_".$field_val;
				$list = $misc_obj->get_misc('','','',$field."	='".$parent_id."'");

				if($list){
					foreach($list as $row){

					    $form_str .= '<hr>';
						$myid =reset($row);
						$superform = new form_class($this_misc,$myid,'');
						$form_str .= '<div class="title"><h3>Editing - '.ucwords(str_replace('_', ' ', $title)).'</h3></div>';
						$form_str .= '<hr>';
						$form_str .= $superform->create_form('Edit_Misc2');
						if($myid){
							$form_str .= '<input type="hidden" name="fmisc_id_'.$this_misc.'" value="'.$myid.'">';
						}
					}
				}
			}

		}else{
			//$form_str	= "<tr><td></td><td>Please select a value to continue</td></tr>";
		}
		return $form_str;
	}
}
?>