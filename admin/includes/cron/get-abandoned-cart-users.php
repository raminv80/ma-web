<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
//include_once 'admin/includes/functions/admin-functions.php';
include_once 'includes/functions/functions.php';

/**
 * Gets list of temp users with abandoned carts.
 * @return boolean[]|multitype:[]
 */
function GetTempUsers(){
  global $DBobject;
  $result = array();
  
  try {
    $sql = "SELECT cart_id, usertemp_gname, usertemp_surname FROM tbl_usertemp AS ut
  INNER JOIN tbl_cart AS c ON c.cart_id = ut.usertemp_last_cart_id
  WHERE ut.usertemp_deleted IS NULL
  AND c.cart_closed_date IS NULL
  AND c.cart_deleted IS NULL";  
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
  foreach($users as $key => $user){
    try {
      $sql = "SELECT GROUP_CONCAT(cartitem_product_id) AS product_ids FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :cart_id GROUP BY cartitem_cart_id";      
      if($res = $DBobject->wrappedSqlGet($sql, array(':cart_id' => $user['cart_id']))){
        $users[$key]['product_ids'].= $res[0]['product_ids'];        
      }
    } catch (Exception $e) {
     echo "Error: " . $e->getMessage(); 
    }    
  }  
  return $users;
}

/**
 * Gets existing users who have an abandoned cart. ** NOTE: It ignores abandoned carts with no cart items. **
 * @return multitype:|boolean
 */
function GetUsers(){
  global $DBobject;
  $result = array();
  try {
    $sql = "SELECT cart_id, cart_session, GROUP_CONCAT(cartitem_product_id) AS product_ids FROM tbl_cart AS c JOIN tbl_cartitem AS ci ON ci.cartitem_cart_id = c.cart_id WHERE c.cart_closed_date IS NULL AND c.cart_deleted IS NULL AND ci.cartitem_deleted IS NULL GROUP BY cart_id";
    if($res = $DBobject->wrappedSql($sql)){
      $result = $res;
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
  return $result;
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