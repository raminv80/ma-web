<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

include_once 'includes/functions/admin-functions.php';
global $CONFIG, $SMARTY, $DBobject;

// ASSIGN ALL STORED SMARTY VALUES
foreach($_SESSION['smarty'] as $key => $val){
  $SMARTY->assign($key, $val);
}

// ASSIGN ERROR MESSAGES FOR TEMPLATES
$SMARTY->assign('error', $_SESSION['error']);
$SMARTY->assign('notice', $_SESSION['notice']);
$_SESSION['error'] = "";
unset($_SESSION['error']);
$_SESSION['notice'] = "";
unset($_SESSION['notice']);
$_SESSION['smarty'] = "";
unset($_SESSION['smarty']);
$_request = clean($_REQUEST);

// ASSIGN ADMIN USER FOR TEMPLATES
$SMARTY->assign('admin', $_SESSION['user']['admin']);

$token = getToken('admin');
$SMARTY->assign('token', $token);

// HANDLE USER PERMISSSIONS TO VIEW SITE. REDIRECT ALL NON-LOGGED IN USERS TO LOGIN
if($_request['arg1'] == 'logout'){
  logoutAdmin();
}
   
checkAdminLogin($_SESSION['user']['admin'], 1, 6);

if((!isset($_SESSION['user']['admin']) || empty($_SESSION['user']['admin'])) && $_request['arg1'] != 'register' && $_request['arg1'] != 'login' && $_request['arg1'] != 'recover-password'){
  $ignoreArr = array(
      '/admin/logout', 
      '/admin', 
      '/admin/login' 
  );
  $uri = rtrim($_SERVER['REQUEST_URI'], '/');
  $_SESSION['redirect'] = (in_array($uri, $ignoreArr))? $_SESSION['redirect'] : $uri;
  header("Location:/admin/login");
  die();
}

if(!empty($_SESSION['user']['admin']) && $_request['arg1'] == 'login'){
  if(intval($_SESSION['user']['admin']['level']) == 2){
    header("Location:/admin/level2");
    die();
  }
  header("Location:/admin");
  die();
}

if(!isset($_SESSION['user']['admin']) || empty($_SESSION['user']['admin'])){
  $nav = "non-authd-nav.tpl|non-authd-footer.tpl";
} else{
  $nav = "nav.tpl|footer.tpl";
}

while(true){
  /**
   * ***** Goes to login ******
   */
  if($_request['arg1'] == 'login'){
    $SMARTY->assign('redirect', $_SESSION['redirect']);
    $template = "login.tpl";
    break 1;
  }
  
  /**
   * ***** Goes to login ******
   */
  if($_request['arg1'] == 'home' || $_request['arg1'] == ''){
    if(intval($_SESSION['user']['admin']['level']) == 2){
      header("Location:/admin/level2");
      die();
    }
    $template = "home.tpl";
    break 1;
  }
  
  foreach($CONFIG->resource as $sp){
    
    if($sp->url == $_request["arg1"]){
      $template = $sp->template;
      break 2;
    }
  }
  
  /**
   * ***** Listing pages here ******
   */
  $arr = explode("/", $_request["arg1"]);
  /**
   * ***** Goes to login ******
   */
  if($arr[0] == 'list' && $arr[1] != ""){
    /**
     * **** Goes to individual script pages ******
     */
    foreach($CONFIG->group as $gp){
      if((!empty($gp->attributes()->levelonly) && intval($gp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($gp->attributes()->level) && intval($gp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
        continue;
      }
      foreach($gp->section as $sp){
        if((!empty($sp->attributes()->levelonly) && intval($sp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
          continue;
        }
        if($sp->url == $arr[1]){
          foreach($sp->process as $pr){
            $file = $_SERVER['DOCUMENT_ROOT'] . '/' . (string)$pr->file;
            if(file_exists($file)){
              include ($file);
            }
          }
          
          // IF ADMIN HAS STORES
          if(intval($_SESSION['user']['admin']['level']) == 2 && !empty($_SESSION['user']['admin']['franchisee'])){
            // INJECT CONFIG FOR PROMOTIONS
            $a = array();
            foreach($_SESSION['user']['admin']['franchisee'] as $fid){
              if(!empty($sp->franchiseefield)) $a[] = $sp->franchiseefield . ' = ' . $fid;
            }
            $where = implode(' OR ', $a);
            // CREATE WHERE CONFIG STRUCTURE WHICH INCLUDES ALL ADMIN FRANCHISEE AGAINST FRANCHISEEFIELD eg. (FRANCHISEE_ID = 4 OR FRANCHISEE_ID = 5)
            // ADD WHERE CONFIG TO EXISTING WHERE IF ANY
          }
          
          $SMARTY->assign("zone", $sp->title);
          $SMARTY->assign("typeID", intval($sp->type_id));
          $parentID = 0;
          if($sp->root_parent_id){
            $SMARTY->assign("rootParentID", intval($sp->root_parent_id));
            $parentID = intval($sp->root_parent_id);
          }
          $template = "list.tpl";
          if($sp->type == "LISTING"){
            if($sp->category_object_id) $SMARTY->assign("category_object_id", $sp->category_object_id);
            if(!empty($where)){
              if(empty($sp->where)){
                $sp->addChild('where', $where);
              } else{
                $sp->where = $sp->table->where . " AND ( " . $where . " )";
              }
            }
            $record = new Listing($sp);
            $list = $record->getListingList($parentID);
            $SMARTY->assign("list", $list);
            $SMARTY->assign("path", (string)$sp->url);
            $template = $sp->list_template;
            break 3;
          }
          if($sp->type == "TABLE"){
            if(!empty($where)){
              if(empty($sp->table->where)){
                $sp->table->addChild('where', $where);
              } else{
                $sp->table->where = $sp->table->where . " AND ( " . $where . " )";
              }
            }
            $record = new Record($sp);
            $list = $record->getRecordList(0, 1);
            $SMARTY->assign("list", $list);
            $SMARTY->assign("path", (string)$sp->url);
            $template = $sp->list_template;
            break 3;
          }
        }
      }
    }
    break 1;
  }
  
  /**
   * ***** Goes to login ******
   */
  if($arr[0] == 'edit' && $arr[1] != ""){
    /**
     * **** Goes to individual script pages ******
     */
    if(empty($arr[2])){
      foreach($CONFIG->sequence as $sp){
        $sql = "INSERT INTO {$sp->table} () VALUES ()";
        $DBobject->wrappedSql($sql);
        $objID = $DBobject->wrappedSqlIdentity();
      }
    }
    
    foreach($CONFIG->group as $gp){
      if((!empty($gp->attributes()->levelonly) && intval($gp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($gp->attributes()->level) && intval($gp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
        continue;
      }
      foreach($gp->section as $sp){
        if((!empty($sp->attributes()->levelonly) && intval($sp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
          continue;
        }
        if($sp->url == $arr[1]){
          foreach($sp->process as $pr){
            $file = $_SERVER['DOCUMENT_ROOT'] . '/' . (string)$pr->file;
            if(file_exists($file)){
              include ($file);
            }
          }
          $SMARTY->assign("zone", $sp->title);
          $SMARTY->assign("typeID", $sp->type_id);
          $SMARTY->assign("parentID", $sp->parent_id);
          $SMARTY->assign("rootParentID", $sp->root_parent_id);
          $SMARTY->assign("objID", $objID);
          if($sp->type == "LISTING"){
            $record = new Listing($sp);
            $tm = $record->getListing(intval($arr[2]));
            $SMARTY->assign("fields", $tm);
            $template = $sp->edit_template;
            foreach($sp->custom_template as $ct){
              $f = $ct->attributes()->field;
              $v = $ct->attributes()->value;
              if($tm["{$f}"] == $v){
                $template = $ct;
                break;
              }
            }
            foreach($sp->custom as $ct){
              $f = $ct->attributes()->field;
              $v = $ct->attributes()->value;
              if($tm["{$f}"] == $v){
                foreach($ct->associated as $a){
                  $domdict = dom_import_simplexml($sp);
                  $domcat = dom_import_simplexml($a);
                  $domcat = $domdict->ownerDocument->importNode($domcat, TRUE); // Import the <cat> into the dictionary document
                  $domdict->appendChild($domcat); // Append the <cat> to <c> in the dictionary
                }
                foreach($ct->options as $o){
                  $domdict = dom_import_simplexml($sp);
                  $domcat = dom_import_simplexml($o);
                  $domcat = $domdict->ownerDocument->importNode($domcat, TRUE); // Import the <cat> into the dictionary document
                  $domdict->appendChild($domcat); // Append the <cat> to <c> in the dictionary
                }
                foreach($ct->extends as $e){
                  $domdict = dom_import_simplexml($sp);
                  $domcat = dom_import_simplexml($e);
                  $domcat = $domdict->ownerDocument->importNode($domcat, TRUE); // Import the <cat> into the dictionary document
                  $domdict->appendChild($domcat); // Append the <cat> to <c> in the dictionary
                }
                $record = new Listing($sp);
                $tm = $record->getListing(intval($arr[2]));
                $SMARTY->assign("fields", $tm);
                $template = $ct->template;
                break;
              }
            }
            break 3;
          }
          if($sp->type == "TABLE"){
            $record = new Record($sp);
            $tm = $record->getRecord(intval($arr[2]));
            $SMARTY->assign("fields", $tm);
            $SMARTY->assign("type", (string)$sp->slide);
            $template = $sp->edit_template;
            foreach($sp->custom_template as $ct){
              $f = $ct->attributes()->field;
              $v = $ct->attributes()->value;
              if($tm["{$f}"] === $v){
                $template = $ct;
                break;
              }
            }
            break 3;
          }
        }
      }
    }
    break 1;
  }
  
  if($arr[0] == 'delete' && $arr[1] != ""){
    foreach($CONFIG->group as $gp){
      if((!empty($gp->attributes()->levelonly) && intval($gp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($gp->attributes()->level) && intval($gp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
        continue;
      }
      foreach($gp->section as $sp){
        if((!empty($sp->attributes()->levelonly) && intval($sp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
          continue;
        }
        if($sp->url == $arr[1]){
          if($sp->type == "LISTING"){
            $record = new Listing($sp);
            $res = $record->deleteListing($arr[2]);
          }
          if($sp->type == "TABLE"){
            $record = new Record($sp);
            $res = $record->deleteRecord($arr[2]);
          }
          header("Location: {$_SERVER['HTTP_REFERER']}");
          die();
        }
      }
    }
    break 1;
  }
  
  foreach($CONFIG->group as $gp){
    if((!empty($gp->attributes()->levelonly) && intval($gp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($gp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
      continue;
    }
    foreach($gp->section as $sp){
      if((!empty($sp->attributes()->levelonly) && intval($sp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
        continue;
      }
      if((string)$sp->attributes()->subsection == 'true'){
        $needle = str_replace("/", "\/", $sp->url);
        if(preg_match("/^{$needle}/", $arr[0])){
          foreach($sp->subsection as $sb){
            if((!empty($sb->attributes()->levelonly) && intval($sb->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sb->attributes()->level) && intval($sb->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
              continue;
            }
            $needle2 = str_replace("/", "\/", $sb->url);
            if(preg_match("/^{$needle2}/", $arr[1])){
              foreach($sb->process as $pr){
                $file = $_SERVER['DOCUMENT_ROOT'] . '/' . (string)$pr->file;
                if(file_exists($file)){
                  include ($file);
                }
              }
              
              if(intval($_SESSION['user']['admin']['level']) > 1 && !empty($_SESSION['user']['admin']['franchisee'])){
                $franchiseeIdArr = $_SESSION['user']['admin']['franchisee'];
              }
              $SMARTY->assign("zone", $sp->title);
              $template = (string)$sb->template;
              // SET SUBSECTION BASED ON THE REQUEST URI
              if($arr[0] == 'members'){}
              break 4;
            }
          }
        }
      }
    }
  }
  $template = '404.tpl';
  break 1;
}

$menu = array();
foreach($CONFIG->group as $gp){
  if((!empty($gp->attributes()->levelonly) && intval($gp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($gp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
    continue;
  }
  foreach($gp->section as $sp){
    if((!empty($sp->attributes()->levelonly) && intval($sp->attributes()->levelonly) != intval($_SESSION['user']['admin']['level'])) || (!empty($sp->attributes()->level) && intval($sp->attributes()->level) < intval($_SESSION['user']['admin']['level']))){
      continue;
    }
    $list = array();
    if($sp->type == "LISTING" && $sp->showlist != 'FALSE'){
      $record = new Listing($sp);
      $list = $record->getListingList();
    }
    
    if($sp->type == "TABLE" && $sp->showlist != 'FALSE'){
      $record = new Record($sp);
      $list = $record->getRecordList(0, 1);
    }
    $addUrl = "/admin/edit/{$sp->url}";
    if((string)$sp->attributes()->hideadd == 'true'){
      $addUrl = "";
    }
    // CUSTOM SECTIONS
    $customUrls = array();
    if($sp->type == "CUSTOM"){
      if(!empty($sp->file)){
        $class = (string)$sp->file;
        $obj = new $class($sp);
        $list = $obj->getList();
      }
      foreach($sp->subsection as $sub){
        $title = (string)$sub->title;
        $url = (string)$sub->url;
        if(!empty($title) && !empty($url) && empty($sub->attributes()->hidden)){
          $customUrls["/admin/{$sp->url}/{$url}"] = $title;
        }
      }
    }
    $groupName = (string)$gp->attributes()->name;
    $url = ($sp->url->attributes()->notlist)? "/admin/edit/{$sp->url}" : "/admin/list/{$sp->url}";
    if($sp->url->attributes()->append_admin_id) $url .= "/" . $_SESSION['user']['admin']['id'];
    $menu["{$groupName}"]['section'][] = array(
        "title" => $sp->title, 
        "url" => $url, 
        "list" => $list, 
        "addNewUrl" => $addUrl, 
        "customUrls" => $customUrls 
    );
  }
}
$SMARTY->assign("menu", $menu);
$SMARTY->display("extends:page.tpl|$nav|$template");
die();


function loadAdditional($_conf){
  global $CONFIG, $_PUBLISHED, $SMARTY;
  
  foreach($_conf->additional as $ad){
    $tag = (string)$ad->tag;
    $name = (string)$ad->name;
    $data = (string)$ad->data;
    foreach($CONFIG->$tag as $lp){
      if($lp->attributes()->name == $name){
        $tmp_lp = new SimpleXMLElement($lp->asXML());
        foreach($ad->where as $e){
          $tag = (string)$e->attributes()->tag;
          $domdict = dom_import_simplexml($tmp_lp->$tag);
          $domcat = dom_import_simplexml($e);
          $domcat = $domdict->ownerDocument->importNode($domcat, TRUE); // Import the <cat> into the dictionary document
          $domdict->appendChild($domcat); // Append the <cat> to <c> in the dictionary
        }
        $class = (string)$tmp_lp->file;
        $obj = new $class('', $tmp_lp);
        $data2 = $obj->LoadTree($lp->root_parent_id);
        $SMARTY->assign($data, unclean($data2));
        break 1;
      }
    }
  }
  return true;
}
