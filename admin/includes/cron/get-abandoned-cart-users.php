<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
//include_once 'admin/includes/functions/admin-functions.php';
include_once 'admin/includes/functions/admin-functions.php';

/**
 * Gets list of temp users with abandoned carts.
 * EXCLUDES the following products
 * @return boolean[]|multitype:[]
 */
function GetTempUsers(){
  global $DBobject;  
  $result = array();
  try {
    $sql = "SELECT cart_id, usertemp_id FROM tbl_usertemp AS ut
  INNER JOIN tbl_cart AS c ON c.cart_id = ut.usertemp_last_cart_id
  LEFT JOIN tbl_cartitem AS ci ON c.cart_id = ci.cartitem_cart_id
  LEFT JOIN tbl_product AS p ON ci.cartitem_product_id = p.product_object_id  
  WHERE ut.usertemp_deleted IS NULL
  AND c.cart_closed_date IS NULL
  AND c.cart_deleted IS NULL
  AND p.product_deleted IS NULL
  AND p.product_published = 1
  AND p.product_object_id NOT IN (217,213,225,689)
  GROUP BY usertemp_email
  ORDER BY usertemp_created DESC";  
    if($res = $DBobject->wrappedSql($sql)){
      $result = array(
          'success' => true,
          'results' => $res
      );
    } 
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
  
  return $result;  
}

//TODO: EXCLUSIONS - deleted products and membership fee product and reactivation and gift certificate.
/**
 * Gets cart items for each user abandoned cart.
 * @param array $users
 * @return unknown
 */
function GetTempUserList($users = array()){
  global $DBobject;
  $userList = array();
  foreach($users as $key => $user){
      $userList[] = GetDataProductsOnCart($user['cart_id']);
  }
  return $userList;  
}

/**
 * Gets existing users who have an abandoned cart. ** NOTE: It ignores abandoned carts with no cart items. **
 * @return multitype:|boolean
 */
function GetUsers(){
  global $DBobject;
  $result = array();
  try {
    $sql = "SELECT cart_id FROM tbl_cart AS c 
        JOIN tbl_cartitem AS ci 
        ON ci.cartitem_cart_id = c.cart_id
        LEFT JOIN tbl_product AS p ON ci.cartitem_product_id = p.product_object_id
        WHERE c.cart_closed_date IS NULL 
        AND c.cart_deleted IS NULL 
        AND ci.cartitem_deleted IS NULL
        AND p.product_deleted IS NULL
        AND p.product_published = 1
        AND p.product_object_id NOT IN (217,213,225,689)";
    if($res = $DBobject->wrappedSql($sql)){
      foreach($res as $cart){
        $result[] = GetDataProductsOnCart($cart['cart_id']);        
      }
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
  return $result;
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
      WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0'
      AND cartitem_cart_id = :id
      AND product_published = 1
      AND product_deleted IS NULL
      AND variant_deleted IS NULL
      AND product_object_id NOT IN (217,213,225,689)";

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



/**
 * DEVELOPMENT TESTING ONLY
 */ 
echo "<pre>";
echo "<br/>TEMP USERS<br/>";
$res = GetTempUsers();

if($res['success']){
  $res2 = GetTempUserList($res['results']);
  var_dump($res2);
}


echo "<br/><br/>EXISTING USERS<br/>";
echo "<pre>";
$res2 = GetUsers();
var_dump($res2);

die();
/** 
 * END DEVELOPMENT TESTING
 */


