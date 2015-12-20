<?php
/**
 * This file allows you to "load more" from any of the listing_page templates in the 
 * config file. To do this, it takes 3 parameters. The name of the section to be loaded,
 * the quantity for each section to load the offset to begin the load from.
 * 
 * This file uses the same quantity and offset for ever subsection of the listing_page, so there 
 * may be some sections which load no new data.
 *  
 */
global $CONFIG,$SMARTY,$_PUBLISHED;

$html = "";
if(!empty($_POST['data-section']) && (!empty($_POST['data-qty']) && intval($_POST['data-qty']) > 0) && !empty($_POST['data-uri'])){
  $_section = $_POST['data-section'];
  $_uri = $_POST['data-uri'];
  $_qty = intval($_POST['data-qty']);
  $_offset = intval($_POST['data-offset']);
  $limit = "{$_qty}".($_offset>0?" OFFSET {$_offset}":"");
  
  foreach($CONFIG->listing_page as $lp){
    if($lp->attributes()->name == $_section){
      if(empty($lp->loadmoretemplate)){ break; } //Escape if no template to support this.
      if($lp->limit){
        $lp->limit = $limit;
      }else{
        $lp->addChild('limit',$limit);
      }
      $_nurl = $_uri;
      $class = (string)$lp->file;
      $obj = new $class($_nurl,$lp);
      $obj->Load((!empty($lp->root_parent_id)?$lp->root_parent_id:null),$_PUBLISHED);
      $html = $SMARTY->fetch($lp->loadmoretemplate);
      break;
    }
  }

}

echo json_encode(array("html"=>$html));
die();