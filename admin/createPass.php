<?php
$usr = $_POST['username'];
$pwd = $_POST['password'];

$res = getPass($usr,$pwd);

echo json_encode(array("password"=>$res));
die();

function getPass($username,$pass){
	return sha1(md5(bin2hex(strrev(stripslashes($username)))) . md5(stripslashes(strtoupper($pass))));
}
