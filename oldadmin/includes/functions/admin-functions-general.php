<?php
ini_set('display_errors',1);

function AdminLogIn($email,$password){
	$temp_str = getPass($email,$password);
	$DBobject = new DBmanager();
	$row = $DBobject->GetRow('tbl_admin',"admin_email = '".$email."' AND admin_password = '".$temp_str."'");
	
	if($row){
		$_SESSION["admin"]["id"]=$row["admin_id"];
		$_SESSION["admin"]["name"]=$row["admin_name"];
		$_SESSION["admin"]["surname"]=$row["admin_surname"];
		$_SESSION["admin"]["email"]=$row["admin_email"];
		$_SESSION["admin"]["level"]=$row["admin_level"];
		SaveAdminLogIn($row['admin_id']);
		return true;
	}else{
		return false;
	}
}
function SaveAdminLogIn($admin_id){
	$fields = array('login_admin_id','login_ip');
	$values = array($admin_id,$_SERVER['REMOTE_ADDR']);
	$DBobject = new DBmanager();
	$DBobject->SqlInsert('tbl_login', $fields, $values);
	return true;
	
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
	$conf =  text_editor();
	return "
	<script type=\"text/javascript\" src=\"/admin/includes/js/tinymce/jquery.tinymce.js\"></script>
    <script type=\"text/javascript\">
	$().ready(function() {
		$conf
	});
	</script>";
}
function reload_text_editor(){
	$conf =  text_editor();
	return "
	<script type=\"text/javascript\" src=\"/admin/includes/js/tinymce/jquery.tinymce.js\"></script>
	<script type=\"text/javascript\">
	function ReloadTiny() {
		$conf
	}
	</script>";
}

function text_editor(){
	return "
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/admin/includes/js/tinymce/tiny_mce.js',

			// General options
			theme : \"advanced\",
			plugins : \"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,class_extension\",

			// Theme options
			theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\",
			theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code\",
			theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media\",
			theme_advanced_buttons4 : \"\",
			theme_advanced_toolbar_location : \"top\",
			theme_advanced_toolbar_align : \"left\",
			theme_advanced_statusbar_location : \"bottom\",
			theme_advanced_resizing : true,
			convert_urls : false,
			external_image_list_url : \"/uploads/image_list.php\",
			content_css : '/admin/includes/css/tinyMCE_styles.css'
		});";
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

