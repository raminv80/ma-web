<?php
include "../../includes/functions/functions.php";
$Action = strip_tags($_POST["Action"]);
if($Action){
	switch ($Action) {
		case 'Redirect' :
			if($_POST['redirect']){
				header('Location:'.$_POST['redirect']);
			}else{
				header('Location:/Home');
			}
			exit;
		case 'Search' :
		    if($_POST['search_text']){
		        header('Location:/Search?search='.$_POST['search_text']);
		    }
		    exit;
	}
}