<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'includes/functions/functions.php';
global $SOCIAL,$DBobject;
$SMARTY->assign('admin',$_SESSION['admin']['email']) ;
switch ($_REQUEST['action']) {
	case 'update':
		header('Data-g0:'.$_POST['limit']);
		$res = $SOCIAL->GetResults('','',20,$_POST['limit'],$_POST['type']);
		echo $res;
		break;
	case 'load':
		$_SESSION['control'] = array();
		$res = $SOCIAL->GetResults();
		echo $res;
	break;
	case 'rollback':
		if($_SESSION['type'] != $_POST['type']){
			$_SESSION['control'] = array();
			$_SESSION['control'] = $_SESSION['type'];
		}
		$res = $SOCIAL->GetResults('','',20,$_POST['limit'],$_POST['type']);
		echo $res;
	break;
	case 'getitem':
		echo SocialWall::FormatSingleResult($_REQUEST['itemid']);
	break;
	case 'delete':
		$res = $SOCIAL->DeleteId($_POST['item']);
		echo $res;
	break;
}
