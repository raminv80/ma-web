<?php 
set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'includes/functions/functions.php';

global $SOCIAL;
$res = $SOCIAL->UpdateResultDatabase();
echo $res;


