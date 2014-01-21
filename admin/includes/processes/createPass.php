<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'database/utilities.php';

$usr = $_POST['username'];
$pwd = $_POST['password'];

$res = getPass($usr,$pwd);

echo json_encode(array("password"=>$res));
die();

