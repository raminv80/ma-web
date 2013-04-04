<?php
ini_set('display_errors',1);
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'admin/includes/functions/admin-functions.php';
$Action = strip_tags($_POST["Action"]);
if(true){ //checkToken($_POST["formToken"]) == true){
	$db = new DBmanager();
	switch ($Action) {
		case 'Edit_Misc':
			
			if($_POST['misc'] == '70' &&  $_POST['user_active'] == '1'		&& !empty($_POST['misc_id'])){
				$user= $db->GetRow('tbl_user',"  user_id = '".$_POST['misc_id']."' ");
				
				if($user['user_active'] == 0 ){
					$from = 'All Fresh website';
					$fromEmail = 'noreply@allfresh.com.au';
					$subject = 'Your account is now active';
					$SMARTY->assign('user_name',$user['user_name']);
				 	$SMARTY->assign('link',$_SERVER['HTTP_HOST']);
					$body = $SMARTY->fetch('extends:email.tpl|email-new-user.tpl');					
					$res = sendMail($user['user_business_email'], $from, $fromEmail, $subject, $body);
					
				}
			}
			
			$misc_obj	= new misc($_POST['misc']);
			if( $_POST['bulletin_email'] == 1 ){
				 $_POST['bulletin_email'] = 0 ;
				 $bull = '1';	
			}else{
				 $bull = 0;
			}	
			  
			$result	=	$misc_obj->set_update_misc($_POST);
			
			if($_POST['misc'] == '69' && $bull == '1'){
				if(!empty($_POST['userstomail'])){
					$num=0;
					foreach ($_POST['userstomail'] as $user_id) {
						$user= $db->GetRow('tbl_user',"  user_id = '".$user_id."' ");
						if($user['user_business_email'] != ''){
							$from = 'All Fresh website';
							$fromEmail = 'noreply@allfresh.com.au';
							$subject = 'New Bulletion on All fresh';
						 //	global  $SMARTY;
							$link =  $_SERVER['HTTP_HOST'].'/bulletin#bulletin-'.($misc_obj->DBobject->id != '0' ?  $misc_obj->DBobject->id : $_POST['misc_id']);
							$SMARTY->assign('link_home',$_SERVER['HTTP_HOST']);
							$SMARTY->assign('bulletin_title',unclean($_POST['bulletin_title']));
							$SMARTY->assign('bulletin_content',unclean($_POST['bulletin_content']));
							$SMARTY->assign('link_bulletin',$link);
							$body = $SMARTY->fetch('extends:email.tpl|email-new-bulletin.tpl');					
							sendMail($user['user_business_email'], $from, $fromEmail, $subject, $body);
							$num++;
							$att ='<br/>And update email has been send to '.$num.' users';
						}
					}
				}else{
					$att ='<br/>No users selected';
				}
				$_POST['bulletin_email'] = '';
				unset($_POST['bulletin_email']);
			}
			
			
			
			
			if($_POST['misc_id']	&&	!empty($_POST['misc'])){
					$redirect	=	'/admin/edit_misc.php?id='.$_POST['misc_id'].'&misc='.$_POST['misc'];
			}else{
					$redirect	=	'/admin/edit_misc.php?id='.$misc_obj->DBobject->id.'&misc='.$_POST['misc'];
				
			}
			
			$_SESSION['alert']='<p><b>Item edited</b>'.$att.'</p>';
			break;
		case 'Edit_Page':
			$page_obj = new Page();
			$page_obj->SetUpdatePage($_POST);
			$redirect = '/admin/edit_page.php?id='.$_POST["id"];
			$_SESSION['alert']='<p><b>Page edited</b></p>';
			break;
		case 'DeleteMiscEntry':
			
			$db = new DBmanager();
			$misc_obj	=	new misc($_POST['misc']);
			$db->UpdateField($misc_obj->table,$misc_obj->table_prefix.'_deleted', 'now()',  $misc_obj->id_name.' = "'.$_POST["entry"].'" ');
			$redirect	=	'/admin/list_misc.php?misc='.$_POST['misc'];
			$_SESSION['alert']='<p><b>Item deleted</b></p>';

			break;
		case 'DeletePageEntry':
		  	$db = new DBmanager();
			$db->UpdateField('tbl_page', 'page_deleted', 'now()', 'page_id = "'.$_POST["entry"].'" ');
			$redirect	=	'/admin/list_page.php';
			$_SESSION['alert']='<p><b>Page deleted</b></p>';
		    break;

		case 'FileUpload':
			$file_root=$_SERVER['DOCUMENT_ROOT'];
			$db->GetAnyCell('cfg_plural','tbl_cfg','cfg_id = "'.$_POST['misc'].'" ');
			$fu_obj	=	new uploader();
			$uploadresult = $fu_obj->upload('ufile');
			if($uploadresult == FALSE){
				die("Error:".$fu_obj->error);
			}

			$file_type=get_type_from_extension(str_ireplace('.', '', $fu_obj->file['extention']));
			switch ($file_type) {
				case 'image':
					$folder='uploads/images/';
					$thumb = '1';
					$fu_obj->max_image_size(200, 200);
					break;

				case 'pdf':
					$folder='uploads/pdf/';
					$thumb=null;
					break;

				case 'doc':
					$thumb=null;
					$file_type="doc";
					$folder='uploads/other/';
				$thumb=null;
				break;
			}

			$path=$file_root.$folder;

			if($fu_obj->save_file($path,1,$thumb)){
				if($_POST['list'] != '1'){
					$_POST['list'] = 0;
				}
				$save	=	filelink_class::StoreFileDetails($_POST['misc'],$_POST['id'],$_POST['list'],$fu_obj->file["name"],$file_type,$_POST['file_web_filename'],$fu_obj->file['size'],$folder.$fu_obj->file["name"],$_POST['file_order'],$_POST['file_description']);

				if($thumb	==	'1'){

					$save2	=	filelink_class::StoreFileDetails($_POST['misc'],$_POST['id'],'0','thumb_'.$fu_obj->file["name"],$file_type,'thumbnail_'.$_POST['file_web_filename'],$fu_obj->file['size'],$folder.'thumb_'.$fu_obj->file["name"],$_POST['file_order'],'thumb_'.$_POST['file_description']);

				}

			}else{
				die($fu_obj->error);
			}
			if($_REQUEST['redirect']	!= ''  ){
				$redirect = $_REQUEST['redirect'];
			}
			else
			{
				$redirect = '/admin/edit_misc.php?id='.$_POST['id'].'&misc='.$_POST['misc'];
			}
			break;

		case 'FileUpdate':
			$file_root=$_SERVER['DOCUMENT_ROOT'];

			if($_POST['list'] != '1'){
				$_POST['list'] = 0;
			}
			$save	=	filelink_class::UpdateFileDetails($_POST['misc'],$_POST['file_id'],$_POST['list'],$_POST['file_web_filename'],$_POST['file_order'],$_POST['file_description']);

			if($_REQUEST['redirect']	!= ''  ){
				$redirect = $_REQUEST['redirect'];
			}else{
				$redirect = '/admin/edit_misc.php?id='.$_POST['id'].'&misc='.$_POST['misc'];
			}

			break;
	}
	
	header("Location:".str_ireplace('&amp;', '&', $redirect)); /* Redirect browser */
	
	
}else{
	header("Location:/admin/index.php"); /* Redirect browser */
}