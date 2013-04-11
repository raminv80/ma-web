<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
if(empty($_SESSION[admin]) && $_POST["Action"] != 'AdminLogIn'){ die();}
$Action = strip_tags($_POST["Action"]);
if(checkToken($_POST["token"])){
	switch ($Action) {
		case 'AdminLogIn':
			$result = AdminLogIn($_POST['email'],$_POST['password']);
			if( $result == true ){
				die("<script>document.location.href='/admin/main.php'</script>");	
			}else{
				echo "wrong email or password<br><br><br><button onclick='$(\"#log\").dialog(\"close\")'>try again</button>";	
			}
			break;
			
			case	'GetFileDetails':
			$linkfiles_obj = new filelink_class();
			$arr	= $linkfiles_obj->GetFileDetails($_POST['id']);
			$buf = "
			<table>
			<tr>
				<td>File Name</td>
				<td>".$arr['file_name']."</td>
			</tr>
			<tr>
				<td>File Type</td>
				<td>".$arr['file_type']."</td>
			</tr>
			<tr>
				<td>File Web Name</td>
				<td>".$arr['file_web_filename']."</td>
			</tr>
			<tr>
				<td>Uploaded Date</td>
				<td>".$arr['file_uploadedate']."</td>
			</tr>	
			<tr>
				<td>File Size</td>
				<td>".$arr['file_size']."</td>
			</tr>
			<tr>
				<td>File Order</td>
				<td>".$arr['file_order']."</td>
			</tr>
			<tr>
				<td>File Description</td>
				<td>".$arr['file_description']."</td>
			</tr>
			<tr>
				<td>File Path</td>
				<td>/".$arr['file_path']."</td>
			</tr>
			";
			
			if($arr['file_type']   ==	'image'){
				
				$arr2 = explode('thumb_', $arr['file_name']);
			
				if($arr2[0]	== ''){
					
				$file_path=$arr['file_path'];
					
				}else{
					
				$file_path	=	str_ireplace($arr['file_name'],'thumb_'.$arr['file_name'],$arr['file_path']);			
					
				}
				
				$buf.="
						<tr>
							<td colspan='2'>
								<img src='/".$file_path."' alt='".$arr['file_description']."' >
							</td>
						</tr>
				";
			}
			
			echo $buf;
			break;
		////////////////////////
		case 'DeleteFileLink':
			$db = new DBmanager();
			$var=$db->UpdateField('tbl_file', 'file_deleted', 'now()', "file_id='".$_POST[id]."'");
			$fieldpath=$db->GetAnyCell('file_path', 'tbl_file', 'file_id = "'.$_POST[id].'"');
			$file_root=$_SERVER['DOCUMENT_ROOT']."/";
			rename($file_root.$fieldpath,$file_root.$fieldpath.'deleted');
			if($var == true){
			die("<script>location.reload(true);</script>");
			}else{
				echo "error";
			}
		break;
		
		///////////////////////
		case	'EditFileDetails':
			$linkfiles_obj = new filelink_class();
			$arr	= $linkfiles_obj->GetFileDetails($_POST['id']);

			$is_main = '';
			if($arr['file_description'] === 'main'){
				$is_main = 'checked="checked"';
			}
			$is_file_list = '';
			if($arr['file_list'])
			{
				$is_file_list = 'checked="checked"';
			}
			$buf = "<form method=\"post\" action=\"/admin/includes/processes/admin-processes-general.php\" name=\"FileUpdateForm\" id=\"FileUpdateForm\"><p id=\"error\"><br></p>
				<table>
				<tr><td>File Web Name</td><td><input type=\"text\" name=\"file_web_filename\" id=\"file_web_filename\" value=\"".$arr['file_web_filename']."\" /></td></tr>
				<tr><td>Main Image</td><td><input type=\"checkbox\" name=\"is_main_image\" id=\"is_main_image\" ".$is_main." /></td></tr>
				<tr><td>List on Page</td><td><input type=\"checkbox\" name=\"list\" id=\"list\" ".$is_file_list." value=\"1\" /></td></tr>
				<tr><td>File Description</td><td><input type=\"text\" name=\"file_description\" id=\"file_description\" value=\"".$arr['file_description']."\" /></td></tr>																					
				<tr><td>File Order</td><td><input type=\"text\" name=\"file_order\" id=\"file_order\" value=\"".$arr['file_order']."\" /></td></tr>		
				<tr><td>
					<input type=\"hidden\" name=\"redirect\" value=\"".$_POST['redirect']."\" />
					<input type=\"hidden\" name=\"file_id\" value=\"".$arr['file_id']."\" />
					<input type=\"hidden\" name=\"id\" value=\"".$_REQUEST['id']."\" />
					<input type=\"hidden\" name=\"misc\" value=\"".$_REQUEST['misc']."\" />
					<input type=\"hidden\" name=\"Action\" value=\"FileUpdate\" />".insertToken()."
					</td>
				</tr>
				<tr><td></td><td><input type=\"button\" name\"submitBtn\" id=\"submitBtn\" value=\"Submit\" /></td></tr>	
				</table></form></div>
				<script type=\"text/javascript\">
				$('#is_main_image').click(function(){
								if($('input[name=file_description]').attr('disabled') == false){
						            $('input[name=file_description]').attr('disabled', true);
						            $('input[name=file_description]').attr('value','main');
						        }else{
						            $('input[name=file_description]').attr('disabled', false);
						            $('input[name=file_description]').attr('value','');
						        }
							});
							
				$('#submitBtn').click(function(){
								if($('#file_web_filename').val() != '' ){
	 									$('input[name=file_description]').attr('disabled', false);
	 									$('#FileUpdateForm').submit();
	 							}else{
	 								$('#error').html('<strong>Please select a File Web Name</strong>');
	 							}
 							});
							</script>";

			echo $buf;
			break;
			////////////////////////
		case 'GetFMiscFValues':
			$buf = '';
			$misc_id = $_POST['misc_id'];
			$id = $_POST['id'];
			$link_field = $_POST['link_field'];
			$ffield = $_POST['ffield'];
			
			$misc = new misc($misc_id);
			$pos = strrpos($link_field, '_');
			$thistable = substr($link_field, 0, $pos);
			$f_tablename = "tbl_".$thistable;
			$f_thistable= substr($f_tablename,4);
			foreach($id as $id_val){
				$value = unserialize(GetAnyCell($ffield, $misc->table, $misc->id_name.' ="'.$id_val.'"'));
				if($value){
					foreach ($value as $fkey) {
						$line = GetRow($f_tablename, $f_thistable.'_id ="'.$fkey.'"');
						$buf .= $line[0].'&&'.$line[$link_field].':';
					}
				}
				}
			$buf = trim($buf,':');
			echo $buf;
			break;
		case 'SendEmails':
			//print_r($_POST);
			$mail_content	= "<html><head></head><body>".$_POST["content"]."</body></html>";
			//substr($_POST["emaillist"], 0)="";
			$emails=str_ireplace("@,", "", $_POST["emaillist"]);
			$sendEmail = new email();
			//$sendEmail->to = $emails;
			$sendEmail->subject = "Payment : Website";
			$sendEmail->body = $mail_content;
			//$result = $sendEmail->sendEmail();
			echo "<strong>Email sent to:</strong><br><p>".$emails."</p>";
			echo "<strong>Email Content:</strong><br><div>".$mail_content."</div>";
			echo"Email Sent!!!";
			break;
			
	}
}else{
	switch ($Action) {
		/////////////////////////
		case 'GetFileDetails':
			$linkfiles_obj = new filelink_class();
			$arr	= $linkfiles_obj->GetFileDetails($_POST['id']);
			$buf = "<table>
				<tr>
					<td>File Name</td>
					<td>".$arr['file_name']."</td>
				</tr>
				<tr>
					<td>File Type</td>
					<td>".$arr['file_type']."</td>
				</tr>
				<tr>
					<td>File Web Name</td>
					<td>".$arr['file_web_filename']."</td>
				</tr>
				<tr>
					<td>Uploaded Date</td>
					<td>".$arr['file_uploadedate']."</td>
				</tr>	
				<tr>
					<td>File Size</td>
					<td>".$arr['file_size']."</td>
				</tr>
				<tr>
					<td>File Order</td>
					<td>".$arr['file_order']."</td>
				</tr>
				<tr>
					<td>File Description</td>
					<td>".$arr['file_description']."</td>
				</tr>
				<tr>
					<td>File Path</td>
					<td>/".$arr['file_path']."</td>
				</tr>
				";

			if($arr['file_type']   ==	'image'){
				$arr2 = explode('thumb_', $arr['file_name']);
				if($arr2[0]	== ''){
					$file_path=$arr['file_path'];
				}else{
					$file_path	=	str_ireplace($arr['file_name'],'thumb_'.$arr['file_name'],$arr['file_path']);
				}
					
				$buf.="
					<tr>
						<td colspan='2'>
							<img src='/".$file_path."' alt='".$arr['file_description']."' >
						</td>
					</tr>";
			}
			echo $buf;
			break;
	case	'EditFileDetails':
			$linkfiles_obj = new filelink_class();
			$arr	= $linkfiles_obj->GetFileDetails($_POST['id']);

			$is_main = '';
			if($arr['file_description'] === 'main'){
				$is_main = 'checked="checked"';
			}
			$is_file_list = '';
			if($arr['file_list'])
			{
				$is_file_list = 'checked="checked"';
			}
			$buf = "<form method=\"post\" action=\"/admin/includes/processes/admin-processes-general.php\" name=\"FileUpdateForm\" id=\"FileUpdateForm\"><p id=\"error\"><br></p>
				<table>
				<tr><td>File Web Name</td><td><input type=\"text\" name=\"file_web_filename\" id=\"file_web_filename\" value=\"".$arr['file_web_filename']."\" /></td></tr>
				<tr><td>Main Image</td><td><input type=\"checkbox\" name=\"is_main_image\" id=\"is_main_image\" ".$is_main." /></td></tr>
				<tr><td>List on Page</td><td><input type=\"checkbox\" name=\"list\" id=\"list\" ".$is_file_list." value=\"1\" /></td></tr>
				<tr><td>File Description</td><td><input type=\"text\" name=\"file_description\" id=\"file_description\" value=\"".$arr['file_description']."\" /></td></tr>																					
				<tr><td>File Order</td><td><input type=\"text\" name=\"file_order\" id=\"file_order\" value=\"".$arr['file_order']."\" /></td></tr>		
				<tr><td>
					<input type=\"hidden\" name=\"redirect\" value=\"".$_POST['redirect']."\" />
					<input type=\"hidden\" name=\"file_id\" value=\"".$arr['file_id']."\" />
					<input type=\"hidden\" name=\"id\" value=\"".$_REQUEST['id']."\" />
					<input type=\"hidden\" name=\"misc\" value=\"".$_REQUEST['misc']."\" />
					<input type=\"hidden\" name=\"Action\" value=\"FileUpdate\" />".insertToken()."
					</td></tr>
				<tr><td></td><td><input type=\"button\" name\"submitBtn\" id=\"submitBtn\" value=\"Submit\" /></td></tr>	
				</table></form></div>
				<script type=\"text/javascript\">
				$('#is_main_image').click(function(){
								if($('input[name=file_description]').attr('disabled') == false){
						            $('input[name=file_description]').attr('disabled', true);
						            $('input[name=file_description]').attr('value','main');
						        }else{
						            $('input[name=file_description]').attr('disabled', false);
						            $('input[name=file_description]').attr('value','');
						        }
							});
							
				$('#submitBtn').click(function(){
								if($('#file_web_filename').val() != '' ){
	 									$('input[name=file_description]').attr('disabled', false);
	 									$('#FileUpdateForm').submit();
	 							}else{
	 								$('#error').html('<strong>Please select a File Web Name</strong>');
	 							}
 				});
							</script>";

			echo $buf;
			break;
			////////////////////////
		case 'DeleteFileLink':
			$db = new DBmanager();
			$var=$db->UpdateField('tbl_file', 'file_deleted', 'now()', "file_id='".$_POST[id]."'");
			$fieldpath=$db->GetAnyCell('file_path', 'tbl_file', 'file_id = "'.$_POST[id].'"');
			$file_root=$_SERVER['DOCUMENT_ROOT']."/";
			rename($file_root.$fieldpath,$file_root.$fieldpath.'deleted');
			if($var == true){
				die("<script>location.reload(true);</script>");
			}else{
				echo "error";
			}
			break;		
	}
//echo "<script>document.location.href='/index.php'</script>";	
}