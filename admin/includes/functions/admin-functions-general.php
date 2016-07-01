<?php
function AdminLogIn($email,$password){
	$DBobject = new DBmanager();
	$encrypt = "new";

  $isValidPassword = isValidPassword($password);

  $temp_str = getPass($email,$password);
  
  $DBobject = new DBmanager();
  $sql = "SELECT * FROM tbl_admin WHERE admin_email = :email AND admin_password = :password AND admin_deleted IS NULL";
  $params = array(
      "email" => $email ,
      "password" => $temp_str
  );
  if( $res = $DBobject->wrappedSql($sql , $params) ){
    $_SESSION['user']['admin']["id"]=$res[0]["admin_id"];
		$_SESSION['user']['admin']["name"]=$res[0]["admin_name"];
		$_SESSION['user']['admin']["surname"]=$res[0]["admin_surname"];
		$_SESSION['user']['admin']["email"]=$res[0]["admin_email"];
		$_SESSION['user']['admin']["level"]=$res[0]["admin_level"];
		saveInLog('Login', 'tbl_admin', $res[0]["admin_id"]);
    $_SESSION['user']['admin']["strong_password"]=$isValidPassword;
    
    /* $sql = "SELECT access_store_id FROM tbl_access WHERE access_admin_id = :id AND access_deleted IS NULL";
    $params = array( "id" => $res[0]["admin_id"]);
    if($res2 = $DBobject->executeSQL($sql , $params)){
    	$tempArr = array();
    	foreach ($res2 as $r2){
    		$tempArr[] = $r2['access_store_id'];
    	}
    }
    if($res[0]["admin_level"] > 1 && empty($tempArr)){
     	return "You do not appear to have correct privileges to access the admin area";
    }
    if($res[0]["admin_level"] > 1){ $_SESSION['user']['admin']['locations'] = $tempArr; } */
    return true;
  } else {
    $temp_str2 = getOldPass($email,$password);
    $sql = "SELECT * FROM tbl_admin WHERE admin_email = :email AND admin_password = :password AND admin_deleted IS NULL AND (admin_encryption IS NULL OR admin_encryption != :encrypt)";
    $params = array(
        "email" => $email ,
        "password" => $temp_str2,
        "encrypt" => $encrypt
    );
    if( $res = $DBobject->wrappedSql($sql , $params) ){
      //Update Password record
      $usql = "UPDATE tbl_admin SET admin_password = :password, admin_encryption = :encrypt WHERE admin_id = :uid";
      $params = array("uid" => $res[0]["admin_id"] ,"password" => $temp_str,"encrypt" => $encrypt);
      $DBobject->wrappedSql($usql , $params);
      
      /* $sql = "SELECT access_store_id FROM tbl_access WHERE access_admin_id = :id AND access_deleted IS NULL";
      $params = array( "id" => $res[0]["admin_id"]);
      if($res2 = $DBobject->executeSQL($sql , $params)){
      	$tempArr = array();
      	foreach ($res2 as $r2){
      		$tempArr[] = $r2['access_store_id'];
      	}
      }
      if($res[0]["admin_level"] > 1 && empty($tempArr)){
      	return "You do not appear to have correct privileges to access the admin area";
      }
      if($res[0]["admin_level"] > 1){ $_SESSION['user']['admin']['locations'] = $tempArr; } */
      
      $_SESSION['user']['admin']["id"]=$res[0]["admin_id"];
		  $_SESSION['user']['admin']["name"]=$res[0]["admin_name"];
		  $_SESSION['user']['admin']["surname"]=$res[0]["admin_surname"];
		  $_SESSION['user']['admin']["email"]=$res[0]["admin_email"];
		  $_SESSION['user']['admin']["level"]=$res[0]["admin_level"];
      $_SESSION['user']['admin']["strong_password"]=$isValidPassword;
      saveInLog('Login', 'tbl_admin', $res[0]["admin_id"]);
      return true;
    }else{
      return false;
    }
  }
  return false;
}

function showVars(){
	foreach($_REQUEST as $key=>$val){
		echo $key . "=" . $val . "<br><br>";
	}
}
function printr($arr,$return = 0){
	if($return == 0 ){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}else{
		$buf = print_r($arr,1);
		return $buf;
	}

}

function load_text_editor(){
// 	$conf =  text_editor();
// 	return "
// 	<script type=\"text/javascript\" src=\"/admin/includes/js/tinymce/jquery.tinymce.min.js\"></script>
//     <script type=\"text/javascript\">
// 	$().ready(function() {
// 		$conf
// 	});
// 	</script>";
}
function reload_text_editor(){
// 	$conf =  text_editor();
// 	return "
// 	<script type=\"text/javascript\" src=\"/admin/includes/js/tinymce/jquery.tinymce.min.js\"></script>
// 	<script type=\"text/javascript\">
// 	function ReloadTiny() {
// 		$conf
// 	}
// 	</script>";
}

function text_editor(){
// 	return "
// 		$('textarea.tinymce').tinymce({
// 			// Location of TinyMCE script
// 			script_url : '/admin/includes/js/tinymce/jquery.tinymce.min.js',

// 			// General options
// 			theme : \"advanced\",
// 			plugins : \"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,class_extension\",

// 			// Theme options
// 			theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\",
// 			theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code\",
// 			theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media\",
// 			theme_advanced_buttons4 : \"\",
// 			theme_advanced_toolbar_location : \"top\",
// 			theme_advanced_toolbar_align : \"left\",
// 			theme_advanced_statusbar_location : \"bottom\",
// 			theme_advanced_resizing : true,
// 			convert_urls : false,
// 			external_image_list_url : \"/uploads/image_list.php\",
// 			content_css : '/includes/css/bootstrap.min.css,/includes/css/custom.css,/admin/includes/css/tinymce.css'
// 		});";
}

function get_type_from_extension($ext) {
	switch(strtolower($ext)) {
		case 'jpg':
		case 'jpeg':
		case 'gif':
		case 'png':
			return 'image';
		case 'pdf':
		case 'xls':
		case 'xlsx':
			return 'pdf';
		case 'doc':
		case 'docx':
		case 'txt':
			return 'doc';
		case 'swf':
		case 'flv':
			return 'video';
		case 'mp3':
			return 'audio';
		case 'csv':
		    return 'csv';
	}
}

