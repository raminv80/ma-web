<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $SMARTY, $CONFIG;

$error = "Missing fields or session expired.";
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] == $GLOBALS['HTTP_HOST'] && !empty($_SESSION['user']['admin']["id"])){
	if($_GET['action'] == 'ViewEmail' && !empty($_GET['id']) ){
		$sql = "SELECT * FROM tbl_email_queue WHERE email_id = :id ";
		if($res = $DBobject->wrappedSql( $sql, array(':id' => $_GET["id"]))){
			$SMARTY->assign('content', unclean($res[0]['email_content']));
			$template = $SMARTY->fetch('view-email-content.tpl');
			$SMARTY->display('view-email-content.tpl');
			die();
		}
	}
	if($_POST['action'] == 'SendEmailtoClasses' ){
		$COMP = json_encode($CONFIG->company);
		$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
		$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);
		$SMARTY->assign("content",unclean($_POST['content']));
		$_SESSION['previewEmail'] = $SMARTY->fetch('email-custom.tpl');
		echo json_encode(array(
				"error" => null,
				"success" => true
		));
		die ();
	}
	if($_GET['action'] == 'PreviewEmail' ){
		$SMARTY->assign('content', $_SESSION['previewEmail']);
		$template = $SMARTY->fetch('view-email-content.tpl');
		$SMARTY->display('view-email-content.tpl');
		die();
	}

}





