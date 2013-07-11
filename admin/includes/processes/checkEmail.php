<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require  '../../includes/functions/admin-functions.php';
$DBobject = new DBmanager();
$usr = $_POST['username'];
$res = checkEmail($usr);
echo json_encode(array("email"=>$res));
die();

