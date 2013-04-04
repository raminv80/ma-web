<?php

class filelink_class {


	var $misc;
	var $id;
	var $files_arr;

	public function __construct($misc=null, $id=null) {
		$db = new DBmanager();
		if($misc && $id){
		$this->id = $id;
		$this->misc = $misc;
		$this->files_arr = $this->GetFiles();
		//print_r($this->files_arr);
		}
	}

	private function GetFiles(){
	$misc_obj	=	new misc(4);
	$misc_arr=$misc_obj->get_misc($id=0, $limit='', $count=0, $where="file_misc_id='".$this->misc."' AND file_entry_id='".$this->id."' ",  $order_by='file_order',$includefiles=0);
	return $misc_arr;
	}

	public function DisplayFiles(){
		$buf='<div id="actions"></div>';
		$buf.='<div id="actions2"></div>';
		$buf.='<div id="actions3" ></div>';
		$buf.="<script>

				function filedetails(fileid){
						$('#actions3').dialog({ modal: true ,resizable: false, draggable: false, title: 'File Details', width:600,
						open: function(event, ui) {
					        $(this).css({'height': 520, 'width': 600}); 
					    }
						} );
						$('#actions3').html('<div id=\"result\"><h3>Please Wait..</h3><img src=\"images/loading.gif\" alt=\"loading...\"></div>');
						$('#result').load('/admin/includes/processes/processes-ajax.php', { id:fileid ,Action:'GetFileDetails'});
				}
				function DeleteLink(fileid){
				var check=confirm('Do you want to delete this file');
					if(check == true){
						$('#actions').dialog({ modal: true ,resizable: false, draggable: false,title: 'Delete File', width:600,
						open: function(event, ui) {
					        $(this).css({'height': 520, 'width': 600}); 
					    }
						} );
						$('#actions').html('<div id=\"result\"><h3>Please Wait..</h3><img src=\"images/loading.gif\" alt=\"loading...\"></div>');
						$('#result').load('/admin/includes/processes/processes-ajax.php', { id:fileid , Action:'DeleteFileLink'});
					}
				}
				function EditLink(fileid, miscid){
					$('#actions3').dialog({ modal: true ,resizable: false, draggable: false,title: 'File Details', width:462,
						open: function(event, ui) {
					        $(this).css({'height': 400, 'width': 462}); 
					    }
						} );
					$('#actions3').html('<div id=\"result\"><h3>Please Wait..</h3><img src=\"images/loading.gif\" alt=\"loading...\"></div>');
					$('#result').load('/admin/includes/processes/processes-ajax.php', { id:fileid , redirect:\"".$_SERVER['REQUEST_URI']."\", misc:miscid ,Action:'EditFileDetails'});
				}
				function Upload( ){
				$('#actions2').dialog({ modal: true ,resizable: false, draggable: false,title: 'File Upload', width:600,
										open: function(event, ui) {
									        $(this).css({'height': 500, 'width': 600}); 
									    },
										 buttons: {
										 	 'Cancel': function() { $(this).dialog('close')}
										 	,'Upload': function() {
										 							if($('#file_web_filename').val()	!= ''	){
										 								if($('#file').val()	!= ''){
										 									$('input[name=file_description]').attr('disabled', false);
										 									$('#FileUploadForm').submit();
										 								}else{
										 								$('#error').html('<strong>Please enter a file name</strong>');
										 								}
										 							}else{
										 								$('#error').html('<strong>Please select a file</strong>');
										 							}
																  }
										}
									} );

				var string ='<div id=\"result\"><form method=\"post\" action=\"/admin/includes/processes/admin-processes-general.php\" name=\"FileUploadForm\" id=\"FileUploadForm\" enctype=\"multipart/form-data\"><p id=\"error\"><br></p><table>'+
							'<tr><td>File Name</td><td><input type=\"text\" name=\"file_web_filename\" id=\"file_web_filename\"></td></tr>' +
							'<tr><td>File Description</td><td><input type=\"text\" name=\"file_description\" id=\"file_description\"></td></tr>'	+
							'<tr><td>File Order</td><td><input type=\"text\" name=\"file_order\" id=\"file_description\"></td></tr>'	+
							'<tr><td>File</td><td><input type=\"file\" name=\"ufile\" id=\"ufile\" ></td></tr>'	+
							'<tr><td><input type=\"hidden\" name=\"redirect\" value=\"".$_SERVER['REQUEST_URI']."\" />' +
							'<input type=\"hidden\" name=\"id\" value=\"".$_REQUEST['id']."\" />' +
							'<input type=\"hidden\" name=\"misc\" value=\"".$_REQUEST['misc']."\" />' +
							'<input type=\"hidden\" name=\"Action\" value=\"FileUpload\" />".insertToken()."</td></tr>'+
							'</table></form></div>';

					$('#actions2').html(string);
					$('#is_main_image').click(function(){
								if($('input[name=file_description]').attr('disabled') == false){
						            $('input[name=file_description]').attr('disabled', true);
						            $('input[name=file_description]').attr('value','main');
						        }else{
						            $('input[name=file_description]').attr('disabled', false);
						            $('input[name=file_description]').attr('value','');
						        }
							});
				}

			   </script>";
		$buf.='<br><table style="width:60%;float:right;"> <tr><td colspan="3"> <strong style="float:left;" >UPLOADS</strong></td></tr>';
		$buf.='<tr><td bgcolor="#F0F0EE">File Name</td><td bgcolor="#F0F0EE">File Description</td><td bgcolor="#F0F0EE">List on Page</td><td bgcolor="#F0F0EE">Action</td></tr>';
		if(!empty($this->files_arr)){
				foreach ($this->files_arr as $file){
					$buf.='<tr><td><a href="javascript:void(0);" onclick="filedetails('.$file['file_id'].')">'.$file['file_web_filename'].'</td><td>'.$file['file_description'].'</td><td>'.($file['file_list']=='1'?'YES':'NO').'</td><td><a  href="javascript:void(0);" onclick="DeleteLink('.$file['file_id'].')">Delete</a>';
				}
		}
		$buf.='<tr><td bgcolor="#F0F0EE" colspan="4"><a href="javascript:void(0);" onclick="Upload();" >Add a File</a></td></tr>';
		$buf.='</table>';
		return $buf;
	}
	public function GetFileDetails($id){
		
		$db = new DBmanager();
		$fileDetails_arr = $db->GetRow('tbl_file', "file_id='".$id."'") ;
		return $fileDetails_arr;
	}
	public function StoreFileDetails($misc,$entry_id,$list,$file_name,$file_type,$file_web_name,$file_size,$file_path,$file_order='',$file_description){
		$db = new DBmanager();
		$fileDetails = $db->GetRow('tbl_file', "file_path='".($file_path)."' and file_deleted <> null ") ;
		if($fileDetails == false ){
			$SQL	= "INSERT INTO tbl_file
						(file_id,file_misc_id,file_entry_id,file_list,file_name,file_type,file_web_filename,file_uploadedate,file_size,file_path,file_order,file_description,file_deleted)
					   VALUES
					   ('','".($misc)."','".($entry_id)."','".($list)."','".($file_name)."','".($file_type)."','".($file_web_name)."',now(),'".($file_size)."','".($file_path)."','".($file_order)."','".($file_description)."',null)";
			$sql_res=$db->executeSQL($SQL);
		}else{
		//file already exist
		}
	}

	public function UpdateFileDetails($misc,$file_id, $list,$file_web_name,$file_order='',$file_description){

		$db = new DBmanager();
		$fileDetails = $db->GetRow('tbl_file', "file_id='".$file_id."' and file_deleted is null ") ;
		if($fileDetails == false ){
			//file doesn't exist
		}else{
			$SQL = "UPDATE tbl_file SET file_list='".($list)."',file_web_filename='".($file_web_name)."',file_order='".($file_order)."',file_description='".($file_description)."'
						WHERE file_id='".($file_id)."'";
			$sql_res=$db->executeSQL($SQL);
		}
	}

}