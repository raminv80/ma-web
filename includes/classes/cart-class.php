<?php
class exceptionCart extends Exception{}

class cart {
  public $cart_id;
  public $created_date;
  public $closed_date;
  public $cart_session;
  private $ses_cart_id;
  protected $cart_user_id = null;
  protected $cart_db_user_id = null;
  protected $cartRecord = array();
  protected $dbProducts = array();
  protected $cartProducts = array();
  protected $enableMerging = false;

  /**
   * 
   * @param int $userId
   * @param string $enableMerging
   */
  function __construct($userId = null, $enableMerging = false) {
    $this->cart_user_id = empty($userId) ? 0 : $userId;
    $this->enableMerging = $enableMerging;
    if($this->VerifySessionCart(session_id())){
      if($this->cart_user_id != $this->cart_db_user_id && !empty($this->cart_db_user_id)){
        //create new cart because the user ids don't match or user is not logged in
        session_regenerate_id();
        $this->CreateCart();
      }
      //do nothing since session cart exists
      $this->cart_id = $this->ses_cart_id; 
      
    }else{
      //create new cart because it's a brand new session
      $this->CreateCart();
    }
    $this->SetUserCart();
    $this->GetDataCart($this->cart_id);
  }

  /**
   * Takes a Session_id value and checks if a cart exists in the database for this session.
   * Returns True if exists, else returns false.
   *
   * @param string $ses_val          
   * @return boolean
   */
  function VerifySessionCart($ses_val) {
    global $DBobject,$SITE;
    
    $sql = "SELECT cart_id, cart_user_id FROM tbl_cart
				WHERE cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_session = :id AND cart_site = :site
				ORDER BY cart_id DESC";
    
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$ses_val,
        ":site"=>$SITE
    ))){
      $this->ses_cart_id = $res[0]['cart_id'];
      $this->cart_db_user_id = $res[0]['cart_user_id'];
      return true;
    }else{
      return false;
    }
  }

  /**
   * If there is opened session with userid
   * if userid-cart has items then :	OPEN new-cart / MERGE both cart to new-cart / SET old sessionID /
   * if userid-cart has NO items then : CLOSE userid-cart / UPDATE current-cart with userid
   * If there is NO opened session with userid: UPDATE current-cart with userid
   *
   * @return boolean
   */
  private function SetUserCart() {
    global $DBobject,$SITE;
    
    if(!empty($this->cart_user_id)){
      $sql = "SELECT * FROM tbl_cart WHERE cart_user_id = :uid AND cart_site = :site AND cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_id <> '0' ORDER BY cart_id DESC";
      if($res = $DBobject->wrappedSql($sql, array(":uid" => $this->cart_user_id, ":site" => $SITE))){
        if($this->NumberOfProductsOnCart($res[0]['cart_id']) && $this->enableMerging){
          $old_cart_id = $this->cart_id;
          $this->ResetSession($res[0]['cart_session']);
          $this->CreateCart();
          $message = $this->MergeCarts(array($res[0]['cart_id'], $old_cart_id), $this->cart_id);
          return $message;
        }else{
          foreach($res as $r){
            if($r['cart_id'] != $this->cart_id){
              $this->DeleteCart($r['cart_id']);
            }
          }
          $this->UpdateUserIdCart();
          return true;
        }
      }else{
        $this->UpdateUserIdCart();
        return true;
      }
    }
    return false;
  }

  /**
   * Set manually a session id keeping its content
   *
   * @param string $sessionId          
   * @return boolean
   */
  function ResetSession($id = null) {
    $sessionBackup = $_SESSION;
    session_destroy();
    session_id($id);
    session_start();
    $_SESSION = $sessionBackup;
    return true;
  }

  /**
   * Create new cart, set user_id when given
   * 
   */
  function CreateCart() {
    global $DBobject,$SITE;
    
    $this->cart_session = session_id();
    $sql = " INSERT INTO tbl_cart ( cart_created, cart_session, cart_user_id, cart_site )
        VALUES ( now(), :sid, :uid, :site )";
    $params = array(
        ":sid" => $this->cart_session, 
        ":uid" => $this->cart_user_id, 
        ":site" => $SITE
    );
    $res = $DBobject->wrappedSql($sql, $params);
    $this->cart_id = $DBobject->wrappedSqlIdentity();
    return true;
  }

  /**
   * Merge a set of carts with items into one cart
   * 
   * @param array $originArr          
   * @param int $destination          
   * @return array
   */
  function MergeCarts($originArr, $destination) {
    global $DBobject;
    
    $firstCreated = date("Y-m-d H:i:s");
    $code = null;
    
    $message = array();
    foreach($originArr as $origin){
      $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted is null AND cartitem_cart_id = :id";
      $orig_items = $DBobject->wrappedSql($sql,array(
          ":id"=>$origin
      ));
      
      if($orig_items){
        foreach($orig_items as $item){
          $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
          $message[] = $this->AddToCart($item['cartitem_product_id'], $attrs, $item['cartitem_product_price'], $item['cartitem_quantity'], $destination, $item['cartitem_variant_id']);
        }
      }
      
      $sql = "SELECT cart_discount_code, cart_created, cart_modified FROM tbl_cart WHERE cart_closed_date is null AND cart_deleted is null AND cart_id = :id";
      $orig_cart = $DBobject->wrappedSql($sql,array(
          ":id"=>$origin
      ));
      if(strtotime($orig_cart[0]['cart_created']) < strtotime($firstCreated)){
        $firstCreated = $orig_cart[0]['cart_created'];
      }
      if($orig_cart[0]['cart_discount_code']){
        $code = $orig_cart[0]['cart_discount_code'];
      }
      $this->DeleteCart($origin); // CLOSE THIS OPENED CART
    }
    
    $sql = "UPDATE tbl_cart SET cart_created = :firstCreated , cart_discount_code = :code WHERE cart_id = :id ";
    $params = array(
        ":firstCreated"=>$firstCreated,
        ":code"=>$code,
        ":id"=>$destination
    );
    $res = $DBobject->wrappedSql($sql,$params);
    $this->ValidateCart();
    
    return $message;
  }

  /**
   * Returns an array with cartitem_attr_attribute_id as key and cartitem_attr_attr_value_id a values
   * given the cartitem_attr_cartitem_id from tbl_cartitem_attr.
   * 
   * @param int $cartItemId          
   * @return array
   */
  function GetAttributesIdsOnCartitem($cartItemId) {
    global $DBobject;
    
    $attrArr = array();
    $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL";
    if($cart_arr = $DBobject->wrappedSql($sql, array(":id"=>$cartItemId))){
      foreach($cart_arr as $a){
        $attrArr[$a['cartitem_attr_attribute_id']]['id'] = $a['cartitem_attr_attr_value_id'];
        $attrArr[$a['cartitem_attr_attribute_id']]['additional'] = (empty($a['cartitem_attr_attr_value_additional']) ? '' : json_decode($a['cartitem_attr_attr_value_additional']));
      }
    }
    return $attrArr;
  }

  /**
   * Set the cart_user_id field in tbl_cart with given userid
   * 
   * @return boolean
   */
  function UpdateUserIdCart() {
    global $DBobject;
    
    $params = array(
        ":uid"=>$this->cart_user_id,
        ":cid"=>$this->cart_id
    );
    $sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_id = :cid";
    if($DBobject->wrappedSql($sql,$params)){
      $this->cart_db_user_id = $this->cart_user_id;
      return $userId;
    }
    return 0;
  }
  
  /**
   * NORMALLY USED AFTER A SUCCESSFUL PAYMENT AND USER WAS CREATED 
   * Set the cart_user_id on a closed cart
   * 
   * @param int $cartId
   * @param int $userId
   * @return boolean
   */
  function UpdateUserIdOfClosedCart($cartId, $userId) {
    global $DBobject;
  
    if(!empty($cartId) && !empty($userId)){
      $sql = "SELECT cart_id FROM tbl_cart WHERE cart_closed_date IS NOT NULL AND cart_id = :cid";
      if($res = $DBobject->wrappedSql($sql, array(":cid" => $cartId))){
        $sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_closed_date IS NOT NULL AND cart_id = :cid";
        if($DBobject->wrappedSql($sql, array(":uid" => $userId, ":cid" => $cartId))){
          return true;
        }
      }
    }
    return false;
  }

  /**
   * Close current cart (or given cart_id) by setting current datetime in cart_closed_date field
   * ALSO save store_id 
   * 
   * @param int $cart_id          
   * @return boolean
   */
	function CloseCart($cart_id = null) {
		global $DBobject;
		
		if (is_null($cart_id)){
			$cart_id = $this->cart_id;
		}
		$sql = "UPDATE tbl_cart SET cart_closed_date = now() WHERE cart_id = :id";
		return $DBobject->wrappedSql ( $sql, array (
				":id" => $cart_id 
		) );
	}
	

  /**
   * Delete current cart (or given cart_id) by seeting current datetime in cart_deleted field
   * 
   * @param unknown $cart_id          
   * @return Ambigous <multitype:, boolean, void, resource, unknown, multitype:>
   */
  function DeleteCart($cart_id = null) {
    global $DBobject;
    
    if(is_null($cart_id)){
      $cart_id = $this->cart_id;
    }
    $sql = "UPDATE tbl_cart SET cart_deleted = now() WHERE cart_id = :id";
    return $DBobject->wrappedSql($sql,array(
        ":id"=>$cart_id
    ));
  }

  /**
   * Find the cartitem_id and quantity when the same product is on the cart, else returns 0
   *
   * @param integer $productObjId
   * @param integer $variantId          
   * @param array $attributesArray          
   * @return array
   */
  function ProductOnCart($productObjId, $variantId, $attributesArray) {
    global $DBobject;
    
    if(empty($this->cartProducts)){
      $this->GetDataProductsOnCart();
    }
    
    foreach($this->cartProducts as $item){
      if($item['cartitem_product_id'] == $productObjId && $item['cartitem_variant_id'] == $variantId){
        $dbAttr = array();
        foreach($item['attributes'] as $attr){
          $dbAttr[$attr['cartitem_attr_attribute_name']] = $attr['cartitem_attr_attr_value_name'] . $attr['cartitem_attr_attr_value_additional'];
        }
        $feAttr = array();
        foreach($attributesArray as $v){
          $feAttr[$v['attribute_name']] = $v['attr_value_name'] . $v['attr_value_additional'];
        }

        if(count(array_diff_assoc($feAttr,$dbAttr)) === 0 && count(array_diff_assoc($dbAttr,$feAttr)) === 0){
          // Item found
          return $item; 
        }
      }
    }
    return array('cartitem_id' => 0);
  }

  /**
   * Return number of items on cart
   *
   * @return int
   */
  function NumberOfProductsOnCart($cid = null) {
    global $DBobject;
  
    if(is_null($cid)){
      $cid = $this->cart_id;
    }
    if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0'){
      $sql = "SELECT SUM(cartitem_quantity) AS SUM FROM tbl_cartitem
					WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
  
      $cart_arr = $DBobject->wrappedSql($sql,array(
          ":id"=>$cid
      ));
      if($cart_arr[0]['SUM']){
        return $cart_arr[0]['SUM'];
      }
    }
    return 0;
  }
  
  
  /**
   * Return array with cartitem IDs of products that require shipping, normally cartitems with cartitem_type_id = 1
   *
   * @return array
   */
  function ShippableCartitems($cid = null) {
    global $DBobject;
    
    if(empty($cid)){
      $cid = $this->cart_id;
    }
    $cart_arr = array();
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_type_id = 1 AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, array(":id"=>$cid))){
      foreach($res as $r){
        $cart_arr[] = $r['cartitem_id'];
      }
    }
    return $cart_arr;
  }
  

  /**
   * Return array with product details on current cart (or given cartId)
   * Include tbl_cartitem, tbl_cartitem_attr and product image gallery
   *
   * @return array
   */
  function GetDataProductsOnCart($cartId = null) {
    global $DBobject;
    
    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    
    $cart_arr = array();
    
    $sql = "SELECT * FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
      WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id AND product_published = 1 AND product_deleted IS NULL";
    $res = $DBobject->wrappedSql($sql, array(":id" => $cartId));
    foreach($res as $p){
      
      $cart_arr[$p['cartitem_id']] = $p;
      
      // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
      $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
      $cart_arr[$p['cartitem_id']]['attributes'] = $DBobject->wrappedSql($sql, array(":id" => $p['cartitem_id']));
      
      // ---------------- PRODUCT CATEGORY ----------------
      $cart_arr[$p['cartitem_id']]['category'] = $this->getFullCategoryName($p['cartitem_product_id']);
      
      // ---------------- PRODUCTS GALLERY ----------------
      $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_variant_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
      if($gal1 = $DBobject->wrappedSql($sql, array(":id" => $p['cartitem_variant_id']))){
        $cart_arr[$p['cartitem_id']]['gallery'] = $gal1;
      }else{
        $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_order LIMIT 1";
        $cart_arr[$p['cartitem_id']]['gallery'] = $DBobject->wrappedSql($sql, array(":id" => $p['product_id']));
      }
      
      // ---------------- PRODUCT PRICE MODIFIER ----------------
      $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
      $params = array(
          ":qty"=>$p['cartitem_quantity'],
          ":pid"=>$p['variant_id']
      );
      if($mod = $DBobject->wrappedSql($sql, $params)){
        $cart_arr[$p['cartitem_id']]['productqty_modifier'] = $mod[0];
      }
    }
    
    $this->cartProducts = unclean($cart_arr);
    return $this->cartProducts;
  }

  /**
   * Return a recordset array of the current cart (or given cartId).
   * Only tbl_cart.
   * 
   * @return array
   */
  function GetDataCart($cartId = null) {
    global $DBobject;
    
    if(empty($cartId) && !empty($this->cartRecord)){
      return $this->cartRecord;
    }
    $sql = "SELECT * FROM tbl_cart WHERE cart_id = :id AND cart_deleted IS NULL AND cart_id <> 0";
    if($res = $DBobject->wrappedSql($sql, array(":id"=>$cartId))){
      $this->cartRecord = $res[0];
      return $res[0];
    }
    throw new exceptionCart("Cart not found.");
  }

  /**
   * Return array with closed carts and products details of a given user_id
   *
   * @param int $userId          
   * @return array
   */
  function GetOrderHistoryByUser($userId) {
    global $DBobject,$SITE;
    
    $cart_arr = array();
    
   $sql = "SELECT tbl_cart.*, tbl_payment.*, status_id, status_order, status_name FROM tbl_cart LEFT JOIN tbl_payment ON cart_id = payment_cart_id LEFT JOIN tbl_order ON order_payment_id = payment_id LEFT JOIN tbl_status ON order_status_id = status_id 
    			WHERE cart_user_id = :uid AND cart_site = :site  AND payment_status != 'F' AND cart_deleted IS NULL AND cart_closed_date IS NOT NULL AND cart_id <> '0' AND order_deleted IS NULL ORDER BY cart_closed_date DESC";
     
    if($res = $DBobject->wrappedSql($sql,array(
        ":uid"=>$userId,
        ":site"=>$SITE
    ))){
      foreach($res as $order){
        $cart_arr[$order['cart_id']] = $order;
        
        // Get cartitem details
        $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0' ";
        $cartitems = $DBobject->wrappedSql($sql,array(
        		":id"=>$order['cart_id']
        ));
        foreach($cartitems as $p){
        	// ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
        	$sql = "SELECT cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
					FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
        	$res2 = $DBobject->wrappedSql($sql, array(":id"=>$p['cartitem_id']));
        	$p['attributes'] = $res2;
        	 
        	 
        	$sql = "SELECT product_id FROM tbl_product WHERE product_deleted IS NULL AND product_published = 1 AND product_object_id = :pid ";
        	if($product = $DBobject->wrappedSql($sql, array(":pid"=>$p['cartitem_product_id']))){
        
        		// ---------------- BUILD URL ----------------
        		$p['url'] = '';
        		$sql = "SELECT cache_url FROM cache_tbl_product WHERE cache_published = '1' AND cache_record_id = :id ";
        		if($res2 = $DBobject->wrappedSql($sql, array(":id"=>$p['cartitem_product_id']))){
        			$p['url'] = '/' . $res2[0]['cache_url'];
        		}
        		if(!empty($p['attributes']) && ! empty($p['url'])){
        			foreach($p['attributes'] as $k=>$a){
        				if($k == 0){
        					$p['url'] .= '?' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
        				}else{
        					$p['url'] .= '&' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
        				}
        			}
        		}
        		// ---------------- PRODUCTS DETAILS FROM tbl_gallery ----------------
        		$sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_ishero DESC";
        		$p['gallery'] = $DBobject->wrappedSql($sql,array(":id"=>$p['product_id']));
        	}
        	$cart_arr[$order['cart_id']]['items'][$p['cartitem_id']] = $p;
        }
        
        $sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
        $res = $DBobject->wrappedSql($sql,array(
            ':id'=>$order['payment_billing_address_id']
        ));
        $cart_arr[$order['cart_id']]['billing'] = $res[0];
        $res = $DBobject->wrappedSql($sql,array(
            ':id'=>$order['payment_shipping_address_id']
        ));
        $cart_arr[$order['cart_id']]['shipping'] = $res[0];
      }
    }
    return $cart_arr;
  }

  /**
   * Calculate total and return all amounts of the current cart
   * 
   * @return array
   */
  function CalculateTotal() {
    global $DBobject;
    
    $subtotal = $this->GetSubtotal();
    $gst_taxable = $this->GetGSTSubtotal();
    $discount = 0;
    $discount_error = '';
    
    $cart = $this->GetDataCart();
    if($cart['cart_discount_code']){
      $discArr = $this->ApplyDiscountCode($cart['cart_discount_code']);
      $discount = $discArr['discount'];
      $discount_error = $discArr['error'];
    }
    return array(
        'subtotal' => $subtotal,
        'discount' => $discount,
    	'discount_error' => $discount_error,
        'GST_Taxable' => $gst_taxable,
        'total' => $subtotal - $discount
    );
  }

  /**
   * Return subtotal amount of items which incl-GST a given cart_id
   * 
   * @return array
   */
  function GetGSTSubtotal($cartId = null) {
    global $DBobject;
    
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    
    $sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_product_gst = 1 AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
    $res = $DBobject->wrappedSql($sql,array(
        ":id"=>$cartId
    ));
    return (empty($res[0]['SUM']) ? 0 : $res[0]['SUM']);
  }

  /**
   * Return subtotal of a given cart_id
   * 
   * @return array
   */
  function GetSubtotal($cartId = null) {
    global $DBobject;
    
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    
    $sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
    $res = $DBobject->wrappedSql($sql,array(
        ":id"=>$cartId
    ));
    return (empty($res[0]['SUM']) ? 0 : $res[0]['SUM']);
  }

  /**
   * Add a single product with attributes to cart, checking whether it already exists, price difference and availability
   *
   * @param int $productId          
   * @param array $attributesArr      
   * @param float $price               
   * @param int $quantity         
   * @param int $cartId  
   * @param int $variantId   
   * @param int $type      
   * @param boolean $getMemberPrice    
   * @return string
   */
  function AddToCart($productId, $attributesArr, $price, $quantity = 1, $cartId = null, $variantId = 0) {
    global $DBobject;
    
   
    if($this->cart_id == '' || $this->cart_id == '0'){
      $this->__construct();
    }
    
    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    
    $quantity = empty($quantity) ? 1 : intval($quantity);
    $price = floatval($price);
    $message = '';
   
    $product = $this->GetProductCalculation($productId, $attributesArr, $quantity, $price);
    //It will return exception if product is not available    

    
    if($product['product_price'] != $price){
      $message = "The price of '{$product['product_name']}' has been updated. ";
    }
    
    $cartItem = $this->ProductOnCart($product['product_object_id'], $product['variant_id'], $product['attributes']);
    
    if($cartItem['cartitem_id'] == 0){
      $subtotal = floatval($quantity) * floatval($product['product_price']);
      $params = array(
          ":cid" => $cartId, 
          ":product_id" => $product['product_object_id'], 
          ":variant_id" => $product['variant_id'], 
          ":type_id" => $product['product_type_id'], 
          ":uid" => (empty($product['variant_uid']) ? $product['product_uid'] : $product['variant_uid']), 
          ":product_name" => $product['product_name'], 
          ":product_price" => $product['product_price'], 
          ":qty" => $quantity, 
          ":subtotal" => $subtotal, 
          ":product_gst" => $product['product_gst'], 
          ":ip" => $_SERVER['REMOTE_ADDR'], 
          ":browser" => $_SERVER['HTTP_USER_AGENT']
      );
      $sql = "INSERT INTO tbl_cartitem ( cartitem_cart_id, cartitem_product_id, cartitem_variant_id, cartitem_type_id, cartitem_product_uid, cartitem_product_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst, cartitem_user_ip, cartitem_user_browser, cartitem_created )
        values( :cid, :product_id, :variant_id, :type_id, :uid, :product_name, :product_price, :qty, :subtotal, :product_gst, :ip, :browser, now() )";
      if($res = $DBobject->wrappedSql($sql, $params)){
        $errorCnt = 0;
        $cartitem_id = $DBobject->wrappedSqlIdentity();
        foreach($product['attributes'] as $attr){
          $params = array(
              ":cid" => $cartitem_id, 
              ":attribute_id" => $attr['attribute_id'], 
              ":attr_value_id" => $attr['attr_value_id'], 
              ":attribute_name" => $attr['attribute_name'], 
              ":attr_value_name" => $attr['attr_value_name'],
              ":attr_value_additional" => $attr['attr_value_additional'], 
              ":attribute_order" => $attr['attribute_order'] 
          );
          $sql = "INSERT INTO tbl_cartitem_attr ( cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name, cartitem_attr_attr_value_additional, cartitem_attr_order, cartitem_attr_created )
            values( :cid, :attribute_id, :attr_value_id, :attribute_name, :attr_value_name, :attr_value_additional, :attribute_order, now() )";
          $res2 = $DBobject->wrappedSql($sql, $params);
          if(!$res2){
            $errorCnt ++;
          }
        }
        if($errorCnt == 0){
          return "'{$product ['product_name']}' was added. {$message}";
        }
      }
    }else{
      
      $quantity = intval($cartItem['cartitem_quantity']) + $quantity;
      $params = array(
          ":id" => $cartItem['cartitem_id'], 
          ":qty" => $quantity, 
          ":price" => $product['product_price'], 
          ":subtotal" => $quantity * $product['product_price']
      );
      $sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_product_price = :price, cartitem_subtotal = :subtotal, cartitem_modified = now()  
        WHERE cartitem_id = :id";
      if($DBobject->wrappedSql($sql,$params)){
        return "'{$product ['product_name']}' was added. {$message}";
      }
    }
    return 'Connection Error.';
  }

  /**
   * Return array with product details from DB, calculate final price/weight/width,height,length based on attribute values.
   * If the product is out of stock, deleted, unpublished or not found then returns array with error flag and message.
   *
   * @param int $product_id          
   * @param array $attributesArray          
   * @return array
   */
  function GetProductCalculation($product_id, $attributesArray = array(), $qty=0, $frontEndPrice = 0) {
    global $DBobject;

    $params = array(':oid' => $product_id);
    
    // --------------- GET PRODUCT INFO --------------------
    $sql = "SELECT product_name FROM tbl_product WHERE product_object_id = :oid ORDER BY product_published = 1 DESC, product_modified DESC";
    $res = $DBobject->wrappedSql($sql, $params);
    $productName = $res[0]['product_name'];
    
    $sqlAttrArr = array();
    $sqlAttrStr = '';
    $cnt = 0;
    // expected array to get "array(array('attribute_id' => 'attr_value_id'))"
    foreach($attributesArray as $attrId => $valId){ 
      $sqlAttrArr[] = "( productattr_attribute_id = :attr{$cnt} AND productattr_attr_value_id = :val{$cnt} )"; 
      $params[":attr{$cnt}"] = $attrId;
      $params[":val{$cnt}"] = $valId['id'];
      $cnt++;
    }
    if(!empty($sqlAttrArr)){
      $sqlAttrStr = 'AND (' . implode(' OR ', $sqlAttrArr) . ' )';
    }
    
    // --------------- GET BASE PRODUCT INFO --------------------
    $sql = "SELECT COUNT(product_id) AS CNT, tbl_product.*, tbl_variant.* FROM tbl_product LEFT JOIN tbl_variant ON variant_product_id = product_id LEFT JOIN tbl_productattr ON productattr_variant_id = variant_id 
        WHERE product_object_id = :oid AND product_deleted IS NULL AND product_published = 1 AND variant_deleted IS NULL AND variant_published = 1 AND productattr_deleted IS NULL {$sqlAttrStr} GROUP BY product_object_id, variant_id";
    $res = $DBobject->wrappedSql($sql, $params);
    if(!empty($res) && $cnt == $res[0]['CNT']){
      $prod = $res[0];
      if(empty($prod['variant_instock'])){
        throw new exceptionCart("<b>{$productName}</b> is out of stock.");
        return false;
      }
      //Set initial product price
      if($prod['variant_editableprice'] == 1){
        $productPrice = ($frontEndPrice > 1000) ? 1000 : round($frontEndPrice, 0);
      }else{
        $productPrice = $prod['variant_price'];
        if($prod['variant_specialprice'] > 0){
          $productPrice = $prod['variant_specialprice'];
        }
        if(!empty($this->cart_user_id) && $prod['variant_membersprice'] > 0){
          $productPrice = $prod['variant_membersprice'];
        } 
      }
      $productAttr = array();
      $cnt = 0;
      foreach($attributesArray as $attrId => $valId){
        
        // --------------- GET ATTRIBUTES INFO --------------------
        $productAttr[$cnt]['attribute_id'] = 0;
        $productAttr[$cnt]['attribute_name'] = '';
        $productAttr[$cnt]['attribute_order'] = 0;
        $sql = "SELECT attribute_id, attribute_name, attribute_order FROM tbl_attribute WHERE attribute_id = :id AND attribute_deleted IS NULL";
        if($attr = $DBobject->wrappedSql($sql, array(":id" => $attrId))){
          $productAttr[$cnt]['attribute_id'] = $attr[0]['attribute_id'];
          $productAttr[$cnt]['attribute_name'] = $attr[0]['attribute_name'];
          $productAttr[$cnt]['attribute_order'] = $attr[0]['attribute_order'];
        }
        
        // --------------- GET ATTRIBUTE-VALUES INFO --------------------
        $productAttr[$cnt]['attr_value_id'] = 0;
        $productAttr[$cnt]['attr_value_name'] = '';
        $sql = "SELECT attr_value_id, attr_value_name FROM tbl_attr_value WHERE attr_value_id = :id AND attr_value_deleted IS NULL";
        if($attr = $DBobject->wrappedSql($sql, array(":id" => $valId['id']))){
          $productAttr[$cnt]['attr_value_id'] = $attr[0]['attr_value_id'];
          $productAttr[$cnt]['attr_value_name'] = $attr[0]['attr_value_name'];
          $productAttr[$cnt]['attr_value_additional'] = (empty($valId['additional'])? '' : json_encode($valId['additional']));
        }
        
        $cnt++;
      }
      
      //Set product price with bulk discount
      $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
      $params2 = array(":qty" => $qty, ":pid" => $prod['variant_id']);
      if($mod = $DBobject->wrappedSql($sql,$params2)){
        if(intval($mod[0]['productqty_percentmodifier']) == 1){
          $productPrice = $productPrice - ($productPrice*($mod[0]['productqty_modifier']/100));
        }else{
          $productPrice = $productPrice - ($mod[0]['productqty_modifier']);
        }
      }
      
      $prod['product_price'] = round($productPrice, 2);
      $prod['attributes'] = $productAttr;
      $this->dbProducts[$product_id] = $prod;
      return $prod;
    }
    throw new exceptionCart("<b>{$productName}</b> is not longer available.");
  }

  /**
   * Delete product item on cart
   *
   * @param int $cartitem_id          
   * @return boolean
   */
  function RemoveFromCart($cartitem_id) {
    global $DBobject;
    
    $params = array(
        ":id"=>$cartitem_id
    );
    $sql = "UPDATE tbl_cartitem SET cartitem_deleted = now() WHERE cartitem_id = :id";
    $res = $DBobject->wrappedSql($sql,$params);
    
    $sql = "UPDATE tbl_cartitem_attr SET cartitem_attr_deleted = now() WHERE cartitem_attr_cartitem_id = :id";
    $res2 = $DBobject->wrappedSql($sql,$params);
    
    if($res && $res2){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Update product items quantities on cart
   *
   * @param array $qtys          
   * @return array
   */
  function UpdateQtyCart($qtys) {
    global $DBobject;
    
    $result = array();
    foreach($qtys as $id=>$qty){
      $sql = "SELECT cartitem_quantity, cartitem_product_price, cartitem_product_id, product_id, variant_editableprice
      		FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id 
            LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id 
      		WHERE cartitem_id = :id AND cartitem_deleted IS NULL AND product_deleted IS NULL AND variant_deleted IS NULL AND product_published = '1'";
      
      if($res = $DBobject->wrappedSql($sql,array(
          ":id"=>$id
      ))){
        
        if($qty != $res[0]['cartitem_quantity']){
          $attrs = $this->GetAttributesIdsOnCartitem($id);
          $DBproduct = $this->GetProductCalculation($res[0]['cartitem_product_id'], $attrs, $qty);
          $price = ($res[0]['variant_editableprice'] == 1) ? $res[0]['cartitem_product_price'] : $DBproduct['product_price'];
          $subtotal = $price * $qty;
          $pricemodifier = "";
          
          //Set product bulk discount
          $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
          $params = array(
              ":qty"=>$qty,
              ":pid"=>$res[0]['product_id']
          );
          if($mod = $DBobject->wrappedSql($sql,$params)){
            if(intval($mod[0]['productqty_percentmodifier']) == 1){
              $pricemodifier = intval($mod[0]['productqty_modifier'])."%";
            }else{
              $pricemodifier = "$".intval($mod[0]['productqty_modifier']);
            }
          }
          $params = array(
              ":id" => $id,
              ":qty" => $qty,
              ":subtotal" => $subtotal,
              ":price" => $price
          );
          $sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_subtotal = :subtotal, cartitem_modified = now(), cartitem_product_price = :price
	                		WHERE cartitem_id = :id";
          if($DBobject->wrappedSql($sql,$params)){
            $result['subtotals'][$id] = $subtotal;
            $result['pricemodifier'][$id] = $pricemodifier;
            $result['priceunits'][$id] = $price;
          }
        }
      }
    }
    return $result;
  }

  /**
   * Validate all items on current cart and totals and return messages in array.
   *
   * @return array
   */
  function ValidateCart() {
    global $DBobject;
    
    $message = array();
    
    $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, array(":id" => $this->cart_id))){
      foreach($res as $item){
        $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
        $DBproduct = $this->GetProductCalculation($item['cartitem_product_id'], $attrs, $item['cartitem_quantity'], $item['cartitem_product_price']);
        
        if($DBproduct['error']){
          $message[] = $DBproduct['error_message'];
          $this->RemoveFromCart($item['cartitem_id']);
        }else{
          if($DBproduct['product_price'] != $item['cartitem_product_price'] || $DBproduct['variant_id'] != $item['cartitem_variant_id'] || $DBproduct['product_name'] != $item['cartitem_product_name'] || $DBproduct['product_gst'] != $item['cartitem_product_gst']){
            $sql = "UPDATE tbl_cartitem SET cartitem_variant_id = :variant_id, cartitem_product_price = :price, cartitem_product_name = :product_name, cartitem_product_gst = :product_gst, cartitem_subtotal = :subtotal  WHERE cartitem_id = :id";
            $DBobject->wrappedSql($sql,array(
                ":id" => $item['cartitem_id'], 
                ":price" => $DBproduct['product_price'], 
                ":product_name" => $DBproduct['product_name'], 
                ":variant_id" => $DBproduct['variant_id'], 
                ":product_gst" => $DBproduct['product_gst'], 
                ":subtotal" => floatval($DBproduct['product_price']) * $item['cartitem_quantity']
            ));
            if($DBproduct['product_price'] != $item['cartitem_product_price']){
              $message[] = "The price of '{$DBproduct['product_name']}' has been updated. ";
            }
          }
        }
      }
    }
    
    return $message;
  }





	/**
	 * Validate the given discount code and calculate the amount according to items on current cart (or given cartId). 
	 * Limit the discount amount to subtotal value. 
	 * Returns array['discount']:float or array['error']:string
	 * 
	 * @param string $code
	 * @param string $cartId
	 * @return array
	 */
function ApplyDiscountCode($code, $cartId = null) { 
		global $DBobject;
	
		if (is_null($cartId)) {
			$cartId = $this->cart_id;
		}
		$result = array();
		$code = strtoupper($code);
		$denyHigherSubtotal = true;		//CHANGE THIS ACCORDINGLY
		
		$subtotal = floatval( $this->GetSubtotal() );
		
		$discount = 0;
		
		$res = $this->GetDiscountData($code);
	
		if ($res) {
			$useValid = true;
			if($res['discount_unlimited_use'] == '0' && $res['discount_used'] >= $res['discount_fixed_time']){
				$useValid = false;
			}
			
			//Check discount restriction per user or usergroup
			$userRestricted = false;
			$reApplyAfterLogin = false;
			if( $res['discount_user_id'] > 0 || $res['discount_usergroup_id'] > 0 ){
				$userRestricted = true;
				$cartInfo = $this->GetDataCart();
				if(empty($cartInfo['cart_user_id']) || $cartInfo['cart_user_id'] == 0){
					$userRestrictionError = "You must be logged in to use this code '".$code. "'. <a href='/login-register' title='Click here to log in'>Click here to log in.</a>";
					$reApplyAfterLogin = true;
				}else{
					$sql = "SELECT user_group FROM tbl_user WHERE user_id = :id AND user_deleted IS NULL";
					$userInfo  = $DBobject->wrappedSql($sql, array( ":id" => $cartInfo['cart_user_id'] ));
					if($res['discount_user_id'] == $cartInfo['cart_user_id'] || $res['discount_usergroup_id'] == $userInfo[0]['user_group']){
						$userRestricted = false;
					}
					$userRestrictionError = "This code '".$code. "' does not match user's details.";
						
				}
			}
			
			$today = strtotime ( date ( "Y-m-d" ) );
			if ($useValid && !$userRestricted && $res['discount_published'] == '1' && ((strtotime($res['discount_start_date']) <= $today && $today <= strtotime($res['discount_end_date']) ) 
					|| ( strtotime($res['discount_start_date']) <= $today && ($res['discount_end_date'] == '0000-00-00' || empty($res['discount_end_date']) ))) ) {
				
				// Valid code by date
				if ( $res['discount_listing_id'] == 0 && $res['discount_product_id'] == 0 ){
					// No filter or special code for particular category/product 
                	if ($res['discount_amount_percentage']) {
                    	$discount = $subtotal * floatval($res['discount_amount']) / 100;
                    } else {
                    	// Discount must not be higher than subtotal
                    	if (floatval($res['discount_amount']) > $subtotal && $denyHigherSubtotal){
                    		$discount = $subtotal;
                    	} else {
                    		$discount = $res['discount_amount'];
                    	}
                    }
				} else { // With filter or special code for a category/product
                	$sql = "SELECT 	cartitem_product_id, cartitem_quantity, cartitem_subtotal FROM tbl_cartitem
                    		WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
					if ( $cartItems = $DBobject->wrappedSql ( $sql, array ( ":id" => $cartId ) )) {
                    	$listingMatchSubtotal = 0;
                    	$discount_amount_total = 0; //Added by Nijesh 1/04/2016 - to apply discount on item quantity
                        foreach ($cartItems as $item){
                        	if ($res['discount_listing_id']){
                        		// Special code for category only
                            	$listingArr = $this->getProductCatParentList($item['cartitem_product_id']);
                                if (in_array_r($res['discount_listing_id'], $listingArr)){
                                	if ($res['discount_amount_percentage']) {
                                    	$discount += floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
                                    } else {
                                    	$listingMatchSubtotal += floatval($item['cartitem_subtotal']);
                                    	$discount_amount_total += floatval($item['cartitem_quantity']*$res['discount_amount']);
          									        }
          								        }
            							} elseif ($res['discount_product_id'] == $item['cartitem_product_id']){
  								          // Special code for product only
                              	if ($res['discount_amount_percentage']) {
                                  	$discount = floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
              								  } else {
              									// Discount must not be higher than subtotal
                                              	if (($res['discount_amount']*$item['cartitem_quantity']) > $item['cartitem_subtotal']){
                                                  	$discount = $item['cartitem_subtotal']; 
              									                } else {
                                                  	$discount = $res['discount_amount']*$item['cartitem_quantity'];
                                                }
              								}
            							}
          						}
						if ($listingMatchSubtotal > 0) {
							if ($discount_amount_total > $listingMatchSubtotal){
                            	$discount = $listingMatchSubtotal; 
							} else {
                            	$discount = $discount_amount_total;
							}
						}
						if(empty($discount)){
							$result['error'] = "This code '".$code. "' doesn't apply for the item(s) on your cart";
							$code = null;
						} 
					}
				}
			} else {
				$result ['error'] = "Sorry, this code '".$code. "' is no longer valid. " . ($useValid?'':'Maximum claims reached.');
				$result ['error'] = ($userRestricted)?$userRestrictionError:'';
				if($reApplyAfterLogin){
					$result ['reApplyAfterLogin'] = true;
				}
				$code = null;
			}
		} else {
			$result ['error'] = 'Invalid Code';
			$code = null;
		}
		
		$result ['discount'] = round($discount, 2);
		
		
		$params = array (
				":id" => $cartId,
				":discount_code" => $code,
				":description"=>(empty($code))?'':$res['discount_description']
		);
		$sql = "UPDATE tbl_cart SET cart_discount_code = :discount_code, cart_discount_description = :description, cart_modified = now() WHERE cart_id = :id";
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			return $result;
		}
		
		return array( 'error' => 'Undefined error');
	}							
	
	/**
	 * Update 'discount_used' field (and unpublish the discount when 'discount_used' value is greater or equal than 'discount_fixed_time' value) 
	 * given a discount code with 'discount_unlimited_use' equal zero
	 * Return true if the update was made, otherwise false.
	 * 
	 * @param string $code
	 * @return boolean
	 */
	function SetUsedDiscountCode($code) {
		global $DBobject;
	
		$sql = "SELECT * FROM tbl_discount
	    			WHERE discount_code = :id AND discount_published = 1 AND discount_deleted IS NULL";
		$params = array ( ":id" => strtoupper($code) );
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			if ($res[0]['discount_unlimited_use'] == '0' && $res[0]['discount_used'] >= $res[0]['discount_fixed_time']) {
				$sql = "UPDATE tbl_discount SET discount_published = 0, discount_modified = now() WHERE discount_code = :id";
			}else{
				$newUsed = $res[0]['discount_used'] + 1;
				$unpublish = "";
				if($res[0]['discount_unlimited_use'] == '0' && $newUsed >= $res[0]['discount_fixed_time']){
					$unpublish = ", discount_published = 0";
				}
				$sql = "UPDATE tbl_discount SET discount_used = :used, discount_modified = now(){$unpublish} WHERE discount_code = :id";
				$params = array_merge($params,array(':used'=> $newUsed ));
			}
			if ($res = $DBobject->wrappedSql ( $sql, $params )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Return a record give a code.
	 * @return array
	 */
	function GetCurrentFreeShippingDiscountName() {
	  global $DBobject;
	
	  $sql = "SELECT discount_shipping FROM tbl_discount
	    	WHERE discount_deleted IS NULL AND discount_published = 1 AND (CURDATE() BETWEEN discount_start_date AND discount_END_date) AND discount_code = :id ";
	  if($res = $DBobject->wrappedSql($sql, array(":id" => $this->cartRecord['cart_discount_code']))){
	    return $res[0]['discount_shipping'];
	  }
	  return '';
	}
	
	/**
	 * Return a record give a code.
	 * @return array
	 */
	function GetDiscountData($code) {
		global $DBobject;
	
		$sql = "SELECT *  FROM tbl_discount
	    			WHERE discount_code = :id AND discount_deleted IS NULL";
	
		$res = $DBobject->wrappedSql ( $sql, array (
				":id" => $code
		) );
	
		return $res[0];
	}

  /**
   * Calculate and return shipping fee
   *
   * @param int $cartId          
   * @return float
   */
  function CalculateShippingFee($method = null, $cartId = null) {
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    // DEPENDS ON CLIENT
    return 15;
  }


  /**
   * ---------------- PENDING TO DO
   * 
   * Return a string with listings(categories) parents' name from a given listing_id
   *
   * @param int $productId
   * @return string
   */
  function getFullCategoryName($productId){
  	global $DBobject;
  
  	return null;
  	
  	/* $result = array();
  	foreach($parentList as $pl){
  		$sql = "SELECT listing_name FROM tbl_listing WHERE listing_deleted IS NULL AND listing_published = 1 AND listing_object_id = :id";
  		if($res = $DBobject->wrappedSql($sql,array(":id" => $pl))){
  				$result[] = $res[0]['listing_name'];
  		}
  	}
  	if(empty($result)){
  		return null;
  	}
  	return implode('/', $result); */
  }
  
   /**
   * Return array with product details for Google Analytics - Enhanced ecommerce.
   * Required: $product_id and $AttributesArr
   *
   * @param int $product_id          
   * @param array $AttributesArr
   * @param int $quantity  
   * @param int $parentId  
   * @param string $coupon 
   * @param int $position          
   * @return array
   */
  function getProductInfo_GA($productId, $AttributesArr, $quantity = 0, $parentId = 0, $coupon = null, $position = 0) {
  	global $DBobject;
  
  	$result = array();
  	$product = $this->GetProductCalculation($productId,$AttributesArr,$quantity);
  	
  	if(!$product['error']){
  		
  		$variant = array();
  		foreach ($product['attributes'] as $a){
  			$variant[] = $a['attr_value_name'] ;
  		}
  		
  		$result = array(
  				'id' => $product['product_object_id'],
  				'name' => $product['product_name'],
  				'category' => $this->getFullCategoryName($productId),
  				'brand' => $product['product_brand'],
  				'variant' => implode('/', $variant),
  				'price' => $product['product_price'],
  				'quantity' => $quantity,
  				'coupon' => $coupon,
  				'position' => $position
  		);
  		
  	}
  	
  	return $result;
  }
  
  /**
   * Return array with product details given a cartitem_id for Google Analytics - Enhanced ecommerce.
   * @param int $cartItemId          
   * @return array
   */
  function getProductInfoByCartItem_GA($cartItemId) {
  	global $DBobject;
  
  	$result = array();
  	$param = array(":id"=>$cartItemId);
  	$sql = "SELECT * FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id 
  			WHERE product_deleted IS NULL AND product_published = 1 AND cartitem_id = :id";
  	if($res = $DBobject->wrappedSql($sql,$param)){
  		
  		$variant = array();
  		$sql = "SELECT cartitem_attr_attr_value_name FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL";
  		
  		if($res2 = $DBobject->wrappedSql($sql,$param)){
  			foreach($res2 as $a){
  				$variant[] = $a['cartitem_attr_attr_value_name'];
  			}
  		}
  		
  		$result = array(
  				'id' => $res[0]['cartitem_product_id'],
  				'name' => $res[0]['cartitem_product_name'],
  				'category' => $this->getFullCategoryName($res[0]['cartitem_product_id']),
  				'brand' => $res[0]['product_brand'],
  				'variant' => implode('/', $variant),
  				'price' => $res[0]['cartitem_product_price'],
  				'quantity' => $res[0]['cartitem_quantity'],
  				'coupon' => '',
  				'position' => ''
  		);
  	}
  
  	return $result;
  }
  
  
  /**
   * Return javascript string with products details given a cart_id for Google Analytics - Enhanced ecommerce.
   * @param int $cartId
   * @return string
   */
  function getJSCartitemsByCartId_GA($cartId = null) {
  	global $DBobject;
  
  	if(is_null($cartId)){
  		$cartId = $this->cart_id;
  	}
  	$result = '';
  	$param = array(":id"=>$cartId);
  	$sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
  	if($res = $DBobject->wrappedSql($sql,$param)){
  		foreach( $res as $r){
  			$product = $this->getProductInfoByCartItem_GA($r['cartitem_id']);
  			$result .= "ga('ec:addProduct', {
  			'id': '{$product['id']}',
  			'name': '{$product['name']}',
  			'category': '{$product['category']}',
  			'brand': '{$product['brand']}',
  			'variant': '{$product['variant']}',
  			'price': '{$product['price']}',
  			'quantity': {$product['quantity']}
  			}); 
  			";
  		}
  	}
  
  	return $result;
  }
  


  /**
   * Return array with products details given a cart_id for Google Analytics - Enhanced ecommerce.
   * @param int $cartId
   * @return string
   */
  function getCartitemsByCartId_GA($cartId = null) {
  	global $DBobject;
  
  	if(is_null($cartId)){
  		$cartId = $this->cart_id;
  	}
  	$result = array();
  	$param = array(":id"=>$cartId);
  	$sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
  	if($res = $DBobject->wrappedSql($sql,$param)){
  		foreach( $res as $r){
  			$result[] = $this->getProductInfoByCartItem_GA($r['cartitem_id']);
  		
  		}
  	}
  	return $result;
  }
  
  
  /**
   * Return the total weight of products on cart
   *
   * @param int $cartId
   * @return float
   */
  function GetCartWeight($cartId = null) {
  	global $DBobject;
  
  	if(empty($cartId)){
  		$cartId = $this->cart_id;
  	}
  	$result = 0;
  	$sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id";
  	$params = array(":id"=>$cartId);
  	if($res = $DBobject->wrappedSql($sql, $params)){
  		foreach($res as $item){
  			$attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
  			$DBproduct = $this->GetProductCalculation($item['cartitem_product_id'],$attrs,$item['cartitem_quantity']);
  			if(!empty($DBproduct['product_weight'])){
  				$result +=  floatval($DBproduct['product_weight']) * $item['cartitem_quantity'];
  			}
  		}
  	}
  	return $result;
  }
  
  
  /**
   * ONLY FOR MAF
   * Return true when the cart has MAF product, in other words not only donations and/or gift certificates 
   * @return boolean
   */
  function HasMAFProducts($cartId = null){
    global $DBobject;
    
    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    $params = array(":id" => $cartId);
    
    //gift certificates and/or donations product_object_id
    $notDonationGift = "AND cartitem_product_id != 213 AND cartitem_product_id != 217";
    
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, $params)){
      $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' {$notDonationGift} AND cartitem_cart_id = :id";
      if($res2 = $DBobject->wrappedSql($sql, $params)){
        return true;
      }
    }
    return false;  
  }
  
  
  
//   /**
//    * Return array with Favourite products given the user_id
//    *
//    * @param int $userId          
//    * @return array
//    */
//   function GetFavouritesByUser($userId) {
//     global $DBobject;
    
//     $cart_arr = array();
    
//     $sql = "SELECT * FROM tbl_favourite LEFT JOIN tbl_product ON product_object_id = favourite_product_object_id 
//     			WHERE product_deleted IS NULL AND product_published = 1 AND favourite_deleted IS NULL AND favourite_user_id = :uid";
    
//     if($res = $DBobject->wrappedSql($sql,array(
//         ":uid"=>$userId
//     ))){
//       foreach($res as $item){
//         $cart_arr[$item['product_id']] = $item;
//         $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery 
// 					WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_ishero DESC";
//         $cart_arr[$item['product_id']]['gallery'] = $DBobject->wrappedSql($sql,array(
//             ':id'=>$item['product_id']
//         ));
        
//         $sql = "SELECT cache_url FROM cache_tbl_product WHERE cache_published = 1 AND cache_deleted IS NULL AND cache_record_id = :id";
//         $res2 = $DBobject->wrappedSql($sql,array(
//             ':id'=>$item['product_object_id']
//         ));
//         $cart_arr[$item['product_id']]['cache_url'] = $res2[0]['cache_url'];
//       }
//     }
//     return $cart_arr;
//   }

//   /**
//    * Return array with product_object_id associated to an user_id
//    *
//    * @param int $userId
//    * @return array
//    */
//   function GetFavouritesObjIdsByUser($userId) {
//   	global $DBobject;
  
//   	$cart_arr = array();
//  	if(!empty($userId)){
//   	$sql ="SELECT favourite_product_object_id FROM tbl_favourite WHERE favourite_deleted IS NULL AND favourite_user_id = :favourite_user_id";
//   	if($res = $DBobject->wrappedSql($sql, array(':favourite_user_id'=>$userId))){
//   		foreach($res as $r){
//   			$cart_arr[] = $r['favourite_product_object_id'];
//   		}
//   	}
//		}
//   	return $cart_arr;
//   }
  
//   /**
//    * Add Favourite products for user, given the user_id and product_object_id
//    *
//    * @param int $userId          
//    * @param int $productObjId          
//    * @return int / boolean
//    */
//   function AddFavourite($userId, $productObjId) {
//     global $DBobject;
    
//     $params = array(
//         ":uid"=>$userId,
//         ":pid"=>$productObjId
//     );
//     $sql = "SELECT favourite_id FROM tbl_favourite WHERE favourite_user_id = :uid AND favourite_product_object_id = :pid AND favourite_deleted IS NULL";
//     if($res = $DBobject->wrappedSql($sql,$params)){
//       return $res[0]['favourite_id'];
//     }
//     $sql = " INSERT INTO tbl_favourite (
// 								favourite_user_id,
//     						favourite_product_object_id,
//         				favourite_created
// 								)
// 							VALUES (
// 								:uid,
// 								:pid,
//         				now()
// 							)";
//     return $DBobject->wrappedSql($sql,$params);
//   }

//   /**
//    * Delete a favourite product given the user_id and product_object_id
//    *
//    * @param unknown $data          
//    * @return array
//    */
//   function DeleteFavourite($userId, $productObjId) {
//     global $DBobject;
    
//     $params = array(
//         ":uid"=>$userId,
//         ":pid"=>$productObjId
//     );
//     $sql = "UPDATE tbl_favourite SET favourite_deleted = now()
//                     WHERE favourite_user_id = :uid AND favourite_product_object_id = :pid";
    
//     if($DBobject->wrappedSql($sql,$params)){
//       return array(
//           'success'=>'The product was removed from your favourite list.'
//       );
//     }
//     return array(
//         'error'=>'There was a connection problem. Please, try again!'
//     );
//   }
}