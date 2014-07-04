<?php
class cart {
  public $cart_id;
  public $cart_user_id = null;
  public $created_date;
  public $closed_date;
  public $cart_session;
  private $ses_cart_id;
  // private $user_cart_id;
  function __construct() {
    if($this->VerifySessionCart(session_id())){
      if(! isset($_SESSION['user']['public']) && $this->cart_user_id){
        session_regenerate_id();
        $this->CreateCart();
        $this->cart_user_id = null;
      }
      $this->cart_id = $this->ses_cart_id; // do nothing since session cart exists and user is not logged in
    }else{
      $this->CreateCart();
    }
  }

  /**
   *
   *
   *
   * Takes a Session_id value and checks if a cart exists in the database for this session.
   * Returns True if exists, else returns false.
   *
   * @param unknown_type $ses_val          
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
      $this->cart_user_id = $res[0]['cart_user_id'];
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
   * @param int $userId          
   * @return boolean
   */
  function SetUserCart($userId) {
    global $DBobject,$SITE;
    
    $sql = "SELECT * FROM tbl_cart
    			WHERE cart_user_id = :id AND cart_site = :site AND cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_id <> '0' ORDER BY cart_id DESC";
    
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$userId,
        ":site"=>$SITE
    ))){
      if($this->NumberOfProductsOnCart($res[0]['cart_id'])){
        $old_cart_id = $this->cart_id;
        $this->ResetSession($res[0]['cart_session']);
        $this->CreateCart($userId);
        $message = $this->MergeCarts(array(
            $res[0]['cart_id'],
            $old_cart_id
        ),$this->cart_id);
        return $message;
      }else{
        $this->DeleteCart($res[0]['cart_id']);
        $this->UpdateUserIdCart($userId);
        return true;
      }
    }else{
      $this->UpdateUserIdCart($userId);
      return true;
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
   * @param string $userId          
   */
  function CreateCart($userId = null) {
    global $DBobject,$SITE;
    
    $this->cart_session = session_id();
    $sql = " INSERT INTO tbl_cart (
        						cart_created, 
        						cart_session, 
        						cart_user_id,
		                cart_site
								)
							VALUES (
        						now(), 
        						:sid,
       							:uid,
       							:site
							)";
    $params = array(
        ":sid"=>$this->cart_session,
        ":uid"=>$userId,
        ":site"=>$SITE
    );
    $res = $DBobject->wrappedSql($sql,$params);
    $this->cart_id = $DBobject->wrappedSqlIdentity();
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
          $message[] = $this->AddToCart($item['cartitem_product_id'],$attrs,$item['cartitem_quantity'],$item['cartitem_product_price'],$destination);
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
    $sql = "SELECT * FROM tbl_cartitem_attr
				WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL";
    
    if($cart_arr = $DBobject->wrappedSql($sql,array(
        ":id"=>$cartItemId
    ))){
      foreach($cart_arr as $a){
        if($a['cartitem_attr_attribute_id']>0){
          $attrArr[$a['cartitem_attr_attribute_id']] = $a['cartitem_attr_attr_value_id'];
        }else{
          $attrArr[$a['cartitem_attr_attribute_name']] = $a['cartitem_attr_attr_value_name'];
        }
      }
    }
    return $attrArr;
  }

  /**
   * Set the cart_user_id field in tbl_cart with given userid
   * 
   * @param int $userId          
   * @return boolean
   */
  function UpdateUserIdCart($userId) {
    global $DBobject;
    
    $params = array(
        ":uid"=>$userId,
        ":cid"=>$this->cart_id
    );
    $sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_id = :cid";
    return $DBobject->wrappedSql($sql,$params);
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
   * @param integer $product_id          
   * @param array $attributesArray          
   * @return array
   */
  function ProductOnCart($product_id, $attributesArray) {
    global $DBobject;
    
    $params = array(
        ":cid"=>$this->cart_id,
        ":pid"=>$product_id
    );
    
    $sql = "SELECT cartitem_id, cartitem_quantity FROM tbl_cartitem
				WHERE cartitem_cart_id = :cid AND cartitem_product_id = :pid AND cartitem_deleted is null AND cartitem_cart_id <> '0'";
    
    if($res = $DBobject->wrappedSql($sql,$params)){
      foreach($res as $item){
        $sql = "SELECT cartitem_attr_attribute_id, cartitem_attr_attr_value_id,cartitem_attr_attribute_name,cartitem_attr_attr_value_name FROM tbl_cartitem_attr
                		WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted is null";
        
        $dbAttr = array();
        if($res2 = $DBobject->wrappedSql($sql,array(
            ":id"=>$item['cartitem_id']
        ))){
          foreach($res2 as $attr){
            if($attr['cartitem_attr_attribute_id'] > 0){
              $dbAttr[$attr['cartitem_attr_attribute_id']] = $attr['cartitem_attr_attr_value_id'];
            }else{
              $dbAttr[$attr['cartitem_attr_attribute_name']] = $attr['cartitem_attr_attr_value_name'];
            }
          }
        }
        
        $feAttr = array();
        foreach($attributesArray as $v){
          if($v['attribute_id'] > 0){
            $feAttr[$v['attribute_id']] = $v['attr_value_id'];
          }else{
            $feAttr[$v['attribute_name']] = $v['attr_value_name'];
          }
        }
        if(count(array_diff_assoc($feAttr,$dbAttr)) === 0 && count(array_diff_assoc($dbAttr,$feAttr)) === 0){
          return $item; // Item found
        }
      }
    }
    return array(
        'cartitem_id'=>0
    );
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
   * Return array with product details on current cart (or given cartId)
   * Include tbl_cartitem, tbl_cartitem_attr and product image gallery
   *
   * @return array
   */
  function GetDataProductsOnCart($cartId = null) {
    global $DBobject;
    
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    
    $cart_arr = array();
    
    $sql = "SELECT 	cartitem_id, cartitem_cart_id, cartitem_product_id, cartitem_product_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst 
				FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
    
    $res = $DBobject->wrappedSql($sql,array(
        ":id"=>$cartId
    ));
    
    foreach($res as $p){
	  $sql = "SELECT * FROM tbl_productqty WHERE productqty_product_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
      $params = array(
          ":qty"=>$p['cartitem_quantity'],
          ":pid"=>$p['cartitem_product_id']
      );
      if($mod = $DBobject->wrappedSql($sql,$params)){
        $p['productqty_modifier'] = $mod[0];
      }
      
      $cart_arr[$p['cartitem_id']] = $p;
      // ---------------- BUILD URL -Part:1/2----------------
      $url = '';
      $sql = "SELECT cache_url FROM tbl_product LEFT JOIN cache_tbl_product ON product_object_id = cache_record_id WHERE product_id = :id AND cache_published = '1'";
      if($res2 = $DBobject->wrappedSql($sql,array(
          ":id"=>$p['cartitem_product_id']
      ))){
        $url = '/' . $res2[0]['cache_url'];
      }
      
      // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
      $sql = "SELECT 	cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
					FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
      
      $res2 = $DBobject->wrappedSql($sql,array(
          ":id"=>$p['cartitem_id']
      ));
      $cart_arr[$p['cartitem_id']]['attributes'] = $res2;
      
      // ---------------- BUILD URL -Part:2/2----------------
      if($res2 && ! empty($url)){
        foreach($res2 as $k=>$a){
          if($k == 0){
            $url .= '?' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
          }else{
            $url .= '&' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
          }
        }
      }
      $cart_arr[$p['cartitem_id']]['url'] = $url;
      
      // ---------------- PRODUCTS DETAILS FROM tbl_gallery ----------------
      $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery 
					WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_ishero DESC";
      
      $res2 = $DBobject->wrappedSql($sql,array(
          ":id"=>$p['cartitem_product_id']
      ));
      $cart_arr[$p['cartitem_id']]['gallery'] = $res2;
      
      /*
       * //---------------- PRODUCTS DETAILS FROM tbl_product ---------------- $sql = "SELECT product_weight, product_width, product_height, product_length FROM tbl_product WHERE product_id = :id AND product_deleted IS NULL AND product_instock = 1 AND product_published = 1"; $res2 = $DBobject->wrappedSql($sql, array( ":id" => $p['cartitem_product_id'] )); $cart_arr[$p['cartitem_id']]['details']= $res2;
       */
    }
    return $cart_arr;
  }

  /**
   * Return a recordset array of the current cart (or given cartId).
   * Only tbl_cart.
   * 
   * @return array
   */
  function GetDataCart($cartId = null) {
    global $DBobject;
    
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    
    $sql = "SELECT * FROM tbl_cart
    			WHERE cart_id = :id AND cart_deleted IS NULL AND cart_id <> '0'";
    
    $res = $DBobject->wrappedSql($sql,array(
        ":id"=>$cartId
    ));
    
    return $res[0];
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
    
    $sql = "SELECT * FROM tbl_cart LEFT JOIN tbl_payment ON cart_id = payment_cart_id
    			WHERE cart_user_id = :uid AND cart_site = :site AND cart_deleted IS NULL AND cart_closed_date IS NOT NULL AND cart_id <> '0' ORDER BY cart_closed_date DESC";
    
    if($res = $DBobject->wrappedSql($sql,array(
        ":uid"=>$userId,
        ":site"=>$SITE
    ))){
      foreach($res as $order){
        $cart_arr[$order['cart_id']] = $order;
        $cart_arr[$order['cart_id']]['items'] = $this->GetDataProductsOnCart($order['cart_id']);
        
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
        'subtotal'=>$subtotal,
        'discount'=>$discount,
    		'discount_error'=>$discount_error,
        'GST_Taxable'=>$gst_taxable - $discount,
        'total'=>$subtotal - $discount
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
    return $res[0]['SUM'];
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
    return $res[0]['SUM'];
  }

  /**
   * Add a single product with attributes to cart, checking whether it already exists, price difference and availability
   *
   * @param int $productId          
   * @param array $AttributesArr          
   * @param int $quantity          
   * @param float $price          
   * @param int $cartId          
   * @return string
   */
  function AddToCart($productId, $AttributesArr, $quantity, $price, $cartId = null) {
    global $DBobject;
    
    if($this->cart_id == '' || $this->cart_id == '0'){
      $this->__construct();
    }
    
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    
    $quantity = intval($quantity);
    $price = floatval($price);
    $message = '';
    
    $product = $this->GetProductCalculation($productId,$AttributesArr,$quantity);
    
    if($product['error']){
      return $product['error_message'];
    }
    
    if($product['product_price'] != $price){
      $message = "The price of '{$product['product_name']}' has been updated. ";
    }
    
    $cartItem = $this->ProductOnCart($product['product_id'],$product['attributes']);
    
    if($cartItem['cartitem_id'] == 0){
      $subtotal = floatval($quantity) * floatval($product['product_price']);
      $params = array(
          ":cid"=>$cartId,
          ":product_id"=>$product['product_id'],
          ":product_name"=>$product['product_name'],
          ":product_price"=>$product['product_price'],
          ":qty"=>$quantity,
          ":subtotal"=>$subtotal,
          ":product_gst"=>$product['product_gst'],
          ":ip"=>$_SERVER['REMOTE_ADDR'],
          ":browser"=>$_SERVER['HTTP_USER_AGENT']
      );
      $sql = "INSERT INTO tbl_cartitem (
									cartitem_cart_id,
									cartitem_product_id,
									cartitem_product_name,
									cartitem_product_price,
									cartitem_quantity,
									cartitem_subtotal,
									cartitem_product_gst,
									cartitem_user_ip,
									cartitem_user_browser,
									cartitem_created
								)
								values(
									:cid,
									:product_id,
									:product_name,
									:product_price,
									:qty,
									:subtotal,
									:product_gst,
									:ip,
									:browser,
									now()
									)";
      
      if($res = $DBobject->wrappedSql($sql,$params)){
        $errorCnt = 0;
        $cartitem_id = $DBobject->wrappedSqlIdentity();
        foreach($product['attributes'] as $attr){
          $params = array(
              ":cid"=>$cartitem_id,
              ":attribute_id"=>$attr['attribute_id'],
              ":attr_value_id"=>$attr['attr_value_id'],
              ":attribute_name"=>$attr['attribute_name'],
              ":attr_value_name"=>$attr['attr_value_name']
          );
          $sql = "INSERT INTO tbl_cartitem_attr (
							    						cartitem_attr_cartitem_id,
                              cartitem_attr_attribute_id,
	    												cartitem_attr_attr_value_id,
							    						cartitem_attr_attribute_name,
							    						cartitem_attr_attr_value_name,
	    												cartitem_attr_created
			    								)
			    								values(
			    									:cid,
			    									:attribute_id,
			    									:attr_value_id,
			    									:attribute_name,
			    									:attr_value_name,
			    									now()
			    									)";
          
          $res2 = $DBobject->wrappedSql($sql,$params);
          if(! $res2){
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
          ":id"=>$cartItem['cartitem_id'],
          ":qty"=>$quantity,
          ":price"=>$product['product_price'],
          ":subtotal"=>$quantity * $product['product_price']
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
  function GetProductCalculation($product_id, $attributesArray = array(), $qty=0) {
    global $DBobject;
    $cnt = 1;
    
    // --------------- GET PRODUCT INFO --------------------
    
    $sql = "SELECT product_name, product_object_id FROM tbl_product WHERE product_id = :id ";
    $res = $DBobject->wrappedSql($sql,array(
        ":id"=>$product_id
    ));
    $productName = $res[0]['product_name'];
    $OBJID = $res[0]['product_object_id'];
    
    $sql = "SELECT product_id, product_name, product_price, product_specialprice, product_gst, product_weight, product_width, product_height, product_length FROM tbl_product
				WHERE product_object_id = :oid AND product_deleted is null AND product_instock = 1 AND product_published = 1";
    $res = $DBobject->wrappedSql($sql,array(
        ":oid"=>$OBJID
    ));
    if($res){
      $prod = $res[0];
      if($prod['product_specialprice'] > 0){
        $prod['product_price'] = $prod['product_specialprice'];
      }
      
      $productAttr = array();
      foreach($attributesArray as $attrId=>$attrValId){ // expected array to get "array(array('attribute_id' => 'attr_value_id'))"
        // --------------- GET PRODUCT ATTRIBUTES INFO --------------------
        $params = array(
            ":vid"=>$attrValId,
            ":pid"=>$prod['product_id']
        );
        $sql = "SELECT * FROM tbl_attr_value
							LEFT JOIN tbl_attribute ON attr_value_attribute_id = attribute_id
    						WHERE attr_value_id = :vid AND attribute_product_id = :pid AND attribute_deleted is null AND attr_value_deleted is null";
        if($attr = $DBobject->wrappedSql($sql,$params)){
          if($attr[0]['attr_value_instock'] === 0){
            return array(
                "error"=>true,
                "error_message"=>"'Sorry, {$prod ['product_name']}' is out of Stock"
            );
          }
          if($prod['product_specialprice'] > 0){
            $prod['product_price'] = $prod['product_price'] + $attr[0]['attr_value_specialprice'];
          }else{
            $prod['product_price'] = $prod['product_price'] + $attr[0]['attr_value_price'];
          }
          $prod['product_weight'] = $prod['product_weight'] + $attr[0]['attr_value_weight'];
          $prod['product_width'] = $prod['product_width'] + $attr[0]['attr_value_width'];
          $prod['product_height'] = $prod['product_height'] + $attr[0]['attr_value_height'];
          $prod['product_length'] = $prod['product_length'] + $attr[0]['attr_value_length'];
          
          $productAttr[$attrValId]['attribute_id'] = $attr[0]['attribute_id'];
          $productAttr[$attrValId]['attr_value_id'] = $attr[0]['attr_value_id'];
          $productAttr[$attrValId]['attribute_name'] = $attr[0]['attribute_name'];
          $productAttr[$attrValId]['attr_value_name'] = $attr[0]['attr_value_name'];
        }else{
          $sql = "SELECT * FROM tbl_attr_value
							LEFT JOIN tbl_attribute ON attr_value_attribute_id = attribute_id
    						WHERE attr_value_id = :vid AND attribute_product_id = :pid";
          if($res = $DBobject->wrappedSql($sql,$params)){
            $attrName = ' - ' . $res[0]['attribute_name'] . ': ' . $res[0]['attr_value_name'];
            return array(
                "error"=>true,
                "error_message"=>"Sorry, '{$productName} {$attrName}' is no longer available."
            );
          }else{
            //$attrId=>$attrValId
            if(!empty($attrValId)){
              $productAttr[$attrValId]['attribute_id'] = -$cnt;
              $productAttr[$attrValId]['attr_value_id'] = -$cnt;
              $productAttr[$attrValId]['attribute_name'] = $attrId;
              $productAttr[$attrValId]['attr_value_name'] = $attrValId;
              $cnt++;
            }
          }
        }
      }
      
      $sql = "SELECT * FROM tbl_productqty WHERE productqty_product_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
      $params = array(
          ":qty"=>$qty,
          ":pid"=>$prod['product_id']
      );
      if($mod = $DBobject->wrappedSql($sql,$params)){
        
        if(intval($mod[0]['productqty_percentmodifier']) == 1){
          $prod['product_price'] = $prod['product_price'] - ($prod['product_price']*($mod[0]['productqty_modifier']/100));
        }else{
          $prod['product_price'] = $prod['product_price'] - ($mod[0]['productqty_modifier']);
        }
      }
      $prod['product_price'] = round($prod['product_price'],2);
      
      return ($product = array(
          "error"=>false,
          "product_id"=>$prod['product_id'],
          "product_name"=>$prod['product_name'],
          "product_price"=>$prod['product_price'],
          "product_gst"=>$prod['product_gst'],
          "product_weight"=>$prod['product_weight'],
          "product_width"=>$prod['product_width'],
          "product_height"=>$prod['product_height'],
          "product_length"=>$prod['product_length'],
          "attributes"=>$productAttr
      ));
    }
    return array(
        "error"=>true,
        "error_message"=>"Sorry, '{$productName}' is no longer available."
    );
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
      $sql = "SELECT cartitem_quantity, cartitem_product_price,cartitem_product_id FROM tbl_cartitem WHERE cartitem_id = :id AND cartitem_deleted IS NULL";
      
      if($res = $DBobject->wrappedSql($sql,array(
          ":id"=>$id
      ))){
        
        if($qty != $res[0]['cartitem_quantity']){
          $attrs = $this->GetAttributesIdsOnCartitem($id);
          $DBproduct = $this->GetProductCalculation($res[0]['cartitem_product_id'],$attrs,$qty);
          $subtotal = $DBproduct['product_price'] * $qty;
          $pricemodifier = "";
          $sql = "SELECT * FROM tbl_productqty WHERE productqty_product_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
          $params = array(
              ":qty"=>$qty,
              ":pid"=>$res[0]['cartitem_product_id']
          );
          if($mod = $DBobject->wrappedSql($sql,$params)){
            if(intval($mod[0]['productqty_percentmodifier']) == 1){
              $pricemodifier = intval($mod[0]['productqty_modifier'])."%";
            }else{
              $pricemodifier = "$".intval($mod[0]['productqty_modifier']);
            }
          }
		  
          $params = array(
              ":id"=>$id,
              ":qty"=>$qty,
              ":subtotal"=>$subtotal,
              ":price"=>$DBproduct['product_price']
          );
          $sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_subtotal = :subtotal, cartitem_modified = now(), cartitem_product_price = :price
	                		WHERE cartitem_id = :id";
          if($DBobject->wrappedSql($sql,$params)){
            $result['subtotals'][$id] = $subtotal;
            $result['pricemodifier'][$id] = $pricemodifier;
            $result['priceunits'][$id] = $DBproduct['product_price'];
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
    
    $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted is null AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$this->cart_id
    ))){
      foreach($res as $item){
        $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
        $DBproduct = $this->GetProductCalculation($item['cartitem_product_id'],$attrs,$item['cartitem_quantity']);
        
        if($DBproduct['error']){
          $message[] = $DBproduct['error_message'];
          $this->RemoveFromCart($item['cartitem_id']);
        }else{
          if($DBproduct['product_price'] != $item['cartitem_product_price'] || $DBproduct['product_id'] != $item['cartitem_product_id'] || $DBproduct['product_name'] != $item['cartitem_product_name'] || $DBproduct['product_gst'] != $item['cartitem_product_gst']){
            $sql = "UPDATE tbl_cartitem SET cartitem_product_price = :price, cartitem_product_id = :product_id, cartitem_product_name = :product_name, cartitem_product_gst = :product_gst, cartitem_subtotal = :subtotal  WHERE cartitem_id = :id";
            $DBobject->wrappedSql($sql,array(
                ":id"=>$item['cartitem_id'],
                ":price"=>$DBproduct['product_price'],
                ":product_id"=>$DBproduct['product_id'],
                ":product_name"=>$DBproduct['product_name'],
                ":product_gst"=>$DBproduct['product_gst'],
                ":subtotal"=>floatval($DBproduct['product_price']) * $item['cartitem_quantity']
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
   * Recursive function which returns listings(categories) parents' id from a given listing_id
   *
   * @param int $parentId          
   * @param int $root          
   * @param array $list          
   * @return array
   */
  private function getParentList($parentId, $root = 0, $list = array()) {
    global $DBobject;
    
    $sql = "SELECT listing_parent_id FROM tbl_listing WHERE listing_id = :id";
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$parentId
    ))){
      if($res[0]['listing_parent_id'] == $root){
        return array_merge(array(
            $parentId
        ),$list);
      }else{
        return $this->getParentList($res[0]['listing_parent_id'],$root,array_merge(array(
            $parentId
        ),$list));
      }
    }
    return array();
  }

  /**
   * Return a multidimensional array with the product parents' id from a given product_id
   *
   * @param int $productId          
   * @param int $root          
   * @return array
   */
  private function getProductCatParentList($productId, $root = 0) {
    global $DBobject;
    
    $sql = "SELECT product_listing_id FROM tbl_product WHERE product_id = :id";
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$productId
    ))){
      return $this->getParentList($res[0]['product_listing_id'],$root);
    }
    return array();
  }

  /**
   * Return the product object id from a given product_id
   *
   * @param int $productId          
   * @param int $root          
   * @return int
   */
  private function getProductObjectId($productId) {
    global $DBobject;
    
    $sql = "SELECT product_object_id FROM tbl_product WHERE product_id = :id";
    if($res = $DBobject->wrappedSql($sql,array(
        ":id"=>$productId
    ))){
      return $res[0]['product_object_id'];
    }
    return - 1;
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
			
			$today = strtotime ( date ( "Y-m-d" ) );
			if ($useValid && $res['discount_published'] == '1' && ((strtotime($res['discount_start_date']) <= $today && $today <= strtotime($res['discount_end_date']) ) 
					|| ( strtotime($res['discount_start_date']) <= $today && $res['discount_end_date'] == '0000-00-00' )) ) {
				
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
                	$sql = "SELECT 	cartitem_product_id, cartitem_subtotal FROM tbl_cartitem
                    		WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
					if ( $cartItems = $DBobject->wrappedSql ( $sql, array ( ":id" => $cartId ) )) {
                    	$listingMatchSubtotal = 0;
                        foreach ($cartItems as $item){
                        	if ($res['discount_listing_id']){
                        		// Special code for category only
                            	$listingArr = $this->getProductCatParentList($item['cartitem_product_id']);
                                if (in_array_r($res['discount_listing_id'], $listingArr)){
                                	if ($res['discount_amount_percentage']) {
                                    	$discount += floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
                                    } else {
                                    	$listingMatchSubtotal += floatval($item['cartitem_subtotal']);
									}
								}
							} elseif ($res['discount_product_id'] == $this->getProductObjectId($item['cartitem_product_id'])){
								// Special code for product only
                            	if ($res['discount_amount_percentage']) {
                                	$discount = floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
								} else {
									// Discount must not be higher than subtotal
                                	if ($res['discount_amount'] > $item['cartitem_subtotal']){
                                    	$discount = $item['cartitem_subtotal']; 
									} else {
                                    	$discount = $res['discount_amount'];
                                    }
								}
							}
						}
						if ($listingMatchSubtotal > 0) {
							if ($res['discount_amount'] > $listingMatchSubtotal){
                            	$discount = $listingMatchSubtotal; 
							} else {
                            	$discount = $res['discount_amount'];
							}
						} 
					}
				}
			} else {
				$result ['error'] = "Sorry, this code '".$code. "' is no longer valid. " . ($useValid?'':'Maximum claims reached.');
				$code = null;
			}
		} else {
			$result ['error'] = 'Invalid Code';
			$code = null;
		}
		
		$result ['discount'] = $discount;
		
		
		$params = array (
				":id" => $cartId,
				":discount_code" => $code,
				":description"=>$res[0]['discount_description']
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