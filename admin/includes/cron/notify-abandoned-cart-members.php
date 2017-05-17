<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'includes/functions/functions.php';
global $DBobject, $SMARTY;

$subject = "Don't leave your favourites behind";

$from = (string) $CONFIG->company->name;
$fromEmail = (string) $CONFIG->company->email_from;
$COMP = json_encode($CONFIG->company);
$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
$SMARTY->assign('DOMAIN', "http://" . $GLOBALS['HTTP_HOST']);

$day = date('Y-m-d', strtotime('-7 days'));

//Only at THEM
if($_SERVER['REMOTE_ADDR']=='150.101.230.130' && !empty($_REQUEST['date'])){
  $day = date('Y-m-d', strtotime($_REQUEST['date']));
}

$params = array(
    'day' => $day
);

try {
  //Build list of temp members with abandoned carts.
  $tempMembers = array();
  
  $sql = "SELECT cart_id, usertemp_id, usertemp_email AS 'email', usertemp_gname AS 'name' FROM tbl_usertemp
    INNER JOIN tbl_cart ON cart_id = usertemp_cart_id
    LEFT JOIN tbl_cartitem ON cart_id = cartitem_cart_id
    LEFT JOIN tbl_product ON cartitem_product_id = product_object_id  
    WHERE usertemp_deleted IS NULL AND usertemp_payment_id = 0
    AND cartitem_deleted IS NULL AND cart_closed_date IS NULL
    AND cart_deleted IS NULL AND product_deleted IS NULL
    AND product_published = 1 AND product_object_id NOT IN (217,213,225)
    AND DATE(usertemp_created) = :day 
    GROUP BY cart_id
    ORDER BY usertemp_id DESC, cart_id DESC";
  
  if($res = $DBobject->wrappedSql($sql, $params)){
    foreach($res as $r){
     if(!array_key_exists($r['email'], $tempMembers)){
       $tempMembers[$r['email']]['cart_id'] = $r['cart_id'];
       $tempMembers[$r['email']]['name'] = $r['name'];
       $tempMembers[$r['email']]['user_id'] = $r['usertemp_id'];
     }
    }
  } 
  
    
  //Build list of existing members who have an abandoned cart.
  $existingMembers = array();
  
  $sql = "SELECT cart_id, cart_user_id FROM tbl_cartitem
  LEFT JOIN tbl_cart ON cart_id = cartitem_cart_id
  LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
  WHERE cartitem_deleted IS NULL AND cart_closed_date IS NULL
  AND cart_deleted IS NULL AND product_deleted IS NULL
  AND product_published = 1 AND product_object_id NOT IN (217,213,225)
  AND DATE(cartitem_modified) = :day
  GROUP BY cart_id
  ORDER BY cart_id DESC";
  
  if($res = $DBobject->wrappedSql($sql, $params)){
    $user_obj = new UserClass();
    foreach($res as $r){
      try{
        if($member = $user_obj->GetMemberBasicDetails($r['cart_user_id'])){
          if(!array_key_exists($member['member_details_email'], $existingMembers)){
            $existingMembers[$member['member_details_email']]['cart_id'] = $r['cart_id'];
            $existingMembers[$member['member_details_email']]['name'] = $member['member_details_firstname'];
            $existingMembers[$member['member_details_email']]['user_id'] = $r['cart_user_id'];
          }
        }
      }catch(Exception $e){
        $error = $e;
      }
    }
  }
  
  
  
  //Create and send emails
  foreach($tempMembers as $email => $d){
    $token = 'TM-' . dechex($d['cart_id']) . '-'. dechex(time()) . '-' . dechex($d['user_id']);
    $SMARTY->assign('token', $token);
    $SMARTY->assign('name', $d['name']);
    $SMARTY->assign('unsubscribe_token', 'tm-' . dechex($d['user_id']) . '-' . dechex(time()));
    $SMARTY->assign('productsOnCart', GetDataProductsOnCart($d['cart_id']));
    $body = $SMARTY->fetch('email/abandoned-cart.tpl'); //FRONT-END templates
    if(!IsUnsubscribed($email)){
      createBulkMail(array($email), $from, $fromEmail, $subject, $body, 0, array($d['user_id'] * -1));
    }
  }
  
  foreach($existingMembers as $email => $d){
    $token = ''; //Existing members don't need tokens - The shopping cart will retrieve their cart-items after login
    $SMARTY->assign('token', $token);
    $SMARTY->assign('name', $d['name']);
    $SMARTY->assign('unsubscribe_token', 'em-' . dechex($d['user_id']) . '-' . dechex(time()));
    $SMARTY->assign('productsOnCart', GetDataProductsOnCart($d['cart_id']));
    $body = $SMARTY->fetch('email/abandoned-cart.tpl'); //FRONT-END templates
    if(!IsUnsubscribed($email)){
      createBulkMail(array($email), $from, $fromEmail, $subject, $body, 0, array($d['user_id']));
    }
  }
  
} catch (Exception $e) {
  sendErrorMail('weberrors@them.com.au', $from, $fromEmail, 'Abandoned cart - cronjob',  $e->getMessage());
}

echo "Cronjob ended";
die();






function IsUnsubscribed($email){
  global $DBobject;

  $params = array(
      ":unsubscribe_email" => $email
  );
  $sql = "SELECT unsubscribe_id FROM tbl_unsubscribe WHERE unsubscribe_deleted IS NULL AND unsubscribe_email = :unsubscribe_email";
  if($DBobject->wrappedSql($sql, $params)){
    return true;
  }
  return false;
}







/**
 * Return array with product details on current cart (or given cartId)
 * Include tbl_cartitem, tbl_cartitem_attr and product image gallery
 *
 * @return array
 */
function GetDataProductsOnCart($cartId = null){
  global $DBobject;
  $cart_arr = array();

  $sql = "SELECT * FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
        LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id
      WHERE cartitem_deleted IS NULL AND cartitem_cart_id > 0
      AND cartitem_cart_id = :id
      AND product_published = 1
      AND product_deleted IS NULL
      AND variant_deleted IS NULL
      AND product_object_id NOT IN (217,213,225)";

  $res = $DBobject->wrappedSql($sql, array(
      ":id" => $cartId
  ));
  foreach($res as $p){
    $cart_arr[$p['cartitem_id']] = $p;

    // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
    $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0' ORDER BY cartitem_attr_order";
    $params = array(":id" => $p['cartitem_id']);
    $cart_arr[$p['cartitem_id']]['attributes'] = $DBobject->wrappedSql($sql, $params);
    // ---------------- PRODUCT CATEGORY ----------------
    //$cart_arr[$p['cartitem_id']]['category'] = $this->getFullCategoryName($p['cartitem_product_id']);
    // ---------------- PRODUCTS GALLERY ----------------
    $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_variant_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
    $params = array(":id" => $p['cartitem_variant_id']);
    $galArr = $DBobject->wrappedSql($sql, $params);
    if(empty($galArr) && !empty($cart_arr[$p['cartitem_id']]['attributes'])){
      //Get similar variant based on attribute
      $params = array(":id" => $p['product_id']);
      $whereStr = '';
      $paramsArr = array();
      foreach($cart_arr[$p['cartitem_id']]['attributes'] as $k => $attr){
        if(!empty($attr['cartitem_attr_attr_value_id'])){
          $params[":attr{$k}"] = $attr['cartitem_attr_attr_value_id'];
          $whereStr .= " AND productattr_attr_value_id = :attr{$k}";
          $paramsArr[$k]['params'] = $params;
          $paramsArr[$k]['where'] = $whereStr;
        }
      }
      $reversedArr = array_reverse($paramsArr);
      foreach($reversedArr as $v){
        $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_variant LEFT JOIN tbl_gallery ON gallery_variant_id = variant_id
        LEFT JOIN tbl_productattr ON productattr_variant_id = variant_id
        WHERE gallery_deleted IS NULL AND productattr_deleted IS NULL AND gallery_link IS NOT NULL AND variant_deleted IS NULL AND variant_product_id = :id {$v['where']} ORDER BY gallery_order LIMIT 1";
        if($galArr = $DBobject->wrappedSql($sql, $v['params'])){
          break;
        }
      }
    }

    if(empty($galArr)){
      //Get base product image
      $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
      $params = array(":id" => $p['product_id']);
      $galArr = $DBobject->wrappedSql($sql, $params);
    }
    $cart_arr[$p['cartitem_id']]['gallery'] = $galArr;
    // ---------------- PRODUCT PRICE MODIFIER ----------------
    $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
    $params = array(
        ":qty" => $p['cartitem_quantity'],
        ":pid" => $p['variant_id']
    );
    if($mod = $DBobject->wrappedSql($sql, $params)){
      $cart_arr[$p['cartitem_id']]['productqty_modifier'] = $mod[0];
    }
  }
  return unclean($cart_arr);
}




