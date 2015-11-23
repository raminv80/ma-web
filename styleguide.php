<?php
include "includes/functions/functions.php";
global $CONFIG,$SMARTY,$DBobject;

$SMARTY->debugging = true;
$SMARTY->force_compile = true;
$SMARTY->caching = false;

$template_folder = $_SERVER['DOCUMENT_ROOT']. $CONFIG->smartytemplate_config->templates."/styleObjects";
$res = directoryToArray($template_folder,1);
$dir = array();
foreach($res as $r){
  if(!in_array($r['dir'], $dir)){
    $dir[] = $r['dir'];
  }
}

$SMARTY->assign("dir",$dir);
$SMARTY->assign("templates",$res);

$page_tpl = "styleguide.tpl";
$SMARTY->display("extends:$page_tpl");
die();


function directoryToArray($directory, $recursive, $dir_name = null) {
  $array_items = array();
  if ($handle = scandir($directory)) {
    foreach ($handle as $file) {
      if ($file != "." && $file != "..") {
        if (is_dir($directory. "/" . $file)) {
          if($recursive) {
            $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive,$file));
          }
//           $file = $directory . "/" . $file;
//           $array_items[] = preg_replace("/\/\//si", "/", $file);
        } else {
          $ext = strtolower(pathinfo($directory. "/" . $file, PATHINFO_EXTENSION));
          if($ext == "tpl"){
            $file = $directory . "/" . $file;
            $array_items[] = array("dir"=>$dir_name,"template"=>preg_replace("/\/\//si", "/", $file));
          }
        }
      }
    }
    closedir($handle);
  }

  return $array_items;
}