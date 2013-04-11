<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include 'oldadmin/includes/functions/admin-functions.php';
$Action = strip_tags($_POST["Action"]);
if(checkToken($_POST["formToken"]) == true){
	switch ($Action) {
		case 'Edit':
			
			break;
		case 'Edit_Page':
			$page_obj = new Page();
			$page_obj->SetUpdatePage($_POST);
			
			break;
		case 'DeleteMiscEntry':
			$db = new DBmanager();
			$misc_obj	=	new misc($_POST['misc']);
			$db->UpdateField($misc_obj->table,$misc_obj->table_prefix.'_deleted', 'now()',  $misc_obj->id_name.' = "'.$_POST["entry"].'" ');
			$redirect	=	'/admin/list_misc.php?misc='.$_POST['misc'];
			$_SESSION['alert']='<p><b>Data successfully deleted.!</b></p>';
			break;
		case 'DeletePageEntry':
		  	$db = new DBmanager();
			$db->UpdateField('tbl_page', 'page_deleted', 'now()', 'page_id = "'.$_POST["entry"].'" ');
			$redirect	=	'/admin/list_page.php';
			$_SESSION['alert']='<p><b>Page successfully deleted.!</b></p>';
		    break;
	}
}