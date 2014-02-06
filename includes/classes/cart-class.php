<?php
class cart {
	public $SHIPPING_FEE = 15; // <<<<<<<======== REMEMBER TO CHANGE THIS ====================
	public $cart_id;
	public $cart_user_id = null;
	public $created_date;
	public $closed_date;
	public $cart_session;
	private $ses_cart_id;
	// private $user_cart_id;
	function __construct() {
		if ($this->VerifySessionCart ( session_id () )) {
			if (!isset($_SESSION['user']) && $this->cart_user_id) {
				session_regenerate_id();
				$this->CreateCart ();
				$this->cart_user_id = null;
			}
			$this->cart_id = $this->ses_cart_id; // do nothing since session cart exists and user is not logged in
		} else {
			$this->CreateCart ();
		}
	}
	
	/**
	 *
	 *
	 * Takes a Session_id value and checks if a cart exists in the database for this session.
	 * Returns True if exists, else returns false.
	 * 
	 * @param unknown_type $ses_val        	
	 * @return boolean
	 */
	function VerifySessionCart($ses_val) {
		global $DBobject;
		
		$sql = "SELECT cart_id, cart_user_id FROM tbl_cart
				WHERE cart_closed_date is null AND cart_deleted is null AND cart_session = :id
				ORDER BY cart_id DESC";
		
		if ($res = $DBobject->wrappedSql ( $sql, array (
				":id" => $ses_val 
		) )) {
			$this->ses_cart_id = $res [0] ['cart_id'];
			$this->cart_user_id = $res [0] ['cart_user_id'];
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * If there is opened session with userid
	 * 		if userid-cart has items then :	OPEN new-cart / MERGE both cart to new-cart / SET old sessionID /
	 * 		if userid-cart has NO items then : CLOSE userid-cart / UPDATE current-cart with userid
	 * If there is NO opened session with userid: UPDATE current-cart with userid
	 *
	 * @param int $userId        	
	 * @return boolean
	 */
	function SetUserCart($userId) {
		global $DBobject;
		
		$sql = "SELECT * FROM tbl_cart
    			WHERE cart_user_id = :id AND cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_id <> '0'";
		
		if ($res = $DBobject->wrappedSql ( $sql, array ( ":id" => $userId ))) {
			if ($this->NumberOfProductsOnCart ( $res [0] ['cart_id'] )) {
				$old_cart_id = $this->cart_id;
				$this->ResetSession ( $res [0] ['cart_session'] );
				$this->CreateCart ( $userId );
				$message = $this->MergeCarts ( array (
						$res [0] ['cart_id'],
						$old_cart_id 
				), $this->cart_id );
				return $message;
			} else {
				$this->DeleteCart ( $res [0] ['cart_id'] );
				$this->UpdateUserIdCart ( $userId );
				return true;
			}
		} else {
			$this->UpdateUserIdCart ( $userId );
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
		session_destroy ();
		session_id ( $id );
		session_start ();
		$_SESSION = $sessionBackup;
		return true;
	}
	
	/**
	 * Create new cart, set user_id when given
	 * @param string $userId
	 */
	function CreateCart($userId = null) {
		global $DBobject;
		
		$this->cart_session = session_id ();
		$sql = " INSERT INTO tbl_cart (
        						cart_created, 
        						cart_session, 
        						cart_user_id
								)
							VALUES (
        						now(), 
        						:sid,
       							:uid
							)";
		$params = array (
				":sid" => $this->cart_session,
				":uid" => $userId 
		);
		$res = $DBobject->wrappedSqlInsert ( $sql, $params );
		$this->cart_id = $DBobject->wrappedSqlIdentity ();
	}
	
	
	/**
	 * Merge a set of carts with items into one cart 
	 * @param array $originArr
	 * @param int $destination
	 * @return array
	 */
	function MergeCarts($originArr, $destination) {
		global $DBobject;
		
		$firstCreated = date ( "Y-m-d H:i:s" );
		$code = null;
		
		$message = array();
		foreach ( $originArr as $origin ) {
			$sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted is null AND cartitem_cart_id = :id";
			$orig_items = $DBobject->wrappedSql ( $sql, array( ":id" => $origin ) );
			
			if ($orig_items) {
				foreach ( $orig_items as $item ) {
					$attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
					$message[] = $this->AddToCart($item['cartitem_product_id'], $attrs, $item['cartitem_quantity'], $item['cartitem_product_price'], $destination);
				}
			}
			
			$sql = "SELECT cart_discount_code, cart_created, cart_modified FROM tbl_cart WHERE cart_closed_date is null AND cart_deleted is null AND cart_id = :id";
			$orig_cart = $DBobject->wrappedSql ( $sql, array (
					":id" => $origin 
			) );
			if (strtotime ( $orig_cart [0] ['cart_created'] ) < strtotime ( $firstCreated )) {
				$firstCreated = $orig_cart [0] ['cart_created'];
			}
			if ($orig_cart [0] ['cart_discount_code']) {
				$code = $orig_cart [0] ['cart_discount_code'];
			}
			$this->DeleteCart ( $origin ); // CLOSE THIS OPENED CART
		}
		
		$sql = "UPDATE tbl_cart SET cart_created = :firstCreated , cart_discount_code = :code WHERE cart_id = :id ";
		$params = array (
				":firstCreated" => $firstCreated,
				":code" => $code,
				":id" => $destination 
		);
		$res = $DBobject->wrappedSql ( $sql, $params );
		
		return $message;
	}
	
	
	/**
	 * Returns an array with cartitem_attr_attribute_id as key and cartitem_attr_attr_value_id a values
	 * given the cartitem_attr_cartitem_id from tbl_cartitem_attr.
	 * @param int $cartItemId
	 * @return array
	 */
	function GetAttributesIdsOnCartitem($cartItemId) {
		global $DBobject;
	
		$attrArr = array();
		$sql = "SELECT * FROM tbl_cartitem_attr
				WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL";
			
		if ( $cart_arr = $DBobject->wrappedSql ( $sql, array ( ":id" => $cartItemId	))) {
			foreach ($cart_arr as $a) {
				$attrArr[$a['cartitem_attr_attribute_id']] = $a['cartitem_attr_attr_value_id'];
			}
		}
		return $attrArr;
	}
	
	
	/**
	 * Set the cart_user_id field in tbl_cart with given userid
	 * @param int $userId
	 * @return boolean
	 */
	function UpdateUserIdCart($userId) {
		global $DBobject;
		
		$params = array (
				":uid" => $userId,
				":cid" => $this->cart_id 
		);
		$sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_id = :cid";
		return $DBobject->wrappedSql ( $sql, $params );
	}
	
	
	/**
	 * Close cart by setting current datetime in cart_closed_date field
	 * @param int $cart_id
	 * @return boolean
	 */
	function CloseCart($cart_id) {
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
	 * Delete cart by seeting current datetime in cart_deleted field
	 * @param unknown $cart_id
	 * @return Ambigous <multitype:, boolean, void, resource, unknown, multitype:>
	 */
	function DeleteCart($cart_id) {
		global $DBobject;
		
		if (is_null($cart_id)){
			$cart_id = $this->cart_id;
		}
		$sql = "UPDATE tbl_cart SET cart_deleted = now() WHERE cart_id = :id";
		return $DBobject->wrappedSql ( $sql, array (
				":id" => $cart_id 
		) );
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
	
		$params = array (
				":cid" => $this->cart_id,
				":pid" => $product_id
		);
	
		$sql = "SELECT cartitem_id, cartitem_quantity FROM tbl_cartitem
				WHERE cartitem_cart_id = :cid AND cartitem_product_id = :pid AND cartitem_deleted is null AND cartitem_cart_id <> '0'";
	
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			foreach ( $res as $item ) {
				$sql = "SELECT cartitem_attr_attribute_id, cartitem_attr_attr_value_id FROM tbl_cartitem_attr
                		WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted is null";
	
				$dbAttr = array ();
				if ($res2 = $DBobject->wrappedSql ( $sql, array (
						":id" => $item ['cartitem_id']
				) )) {
					foreach ( $res2 as $attr ) {
						$dbAttr [$attr ['cartitem_attr_attribute_id']] = $attr ['cartitem_attr_attr_value_id'];
					}
				}
	
				$feAttr = array ();
				foreach ( $attributesArray as $v ) {
					$feAttr [$v ['attribute_id']] = $v ['attr_value_id'];
				}
				if (count ( array_diff_assoc ( $feAttr, $dbAttr ) ) === 0) {
					return $res [0]; // Item found
				}
			}
		}
		return array (
				'cartitem_id' => 0
		);
	}
	
	/**
	 * Return number of items on cart
	 * 
	 * @return int
	 */
	function NumberOfProductsOnCart($cid = null) {
		global $DBobject;
		
		if (is_null ( $cid )) {
			$cid = $this->cart_id;
		}
		if ($this->VerifySessionCart ( session_id () ) == true && $this->cart_id != '0') {
			$sql = "SELECT SUM(cartitem_quantity) AS SUM FROM tbl_cartitem
					WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
			
			$cart_arr = $DBobject->wrappedSql ( $sql, array (
					":id" => $cid 
			) );
			return $cart_arr [0] ['SUM'];
		} else {
			return 0;
		}
	}
	
	/**
	 * Return array with saved product details on cart (tbl_cartitem AND tbl_cartitem_attr) and product image gallery
	 * 
	 * @return array
	 */
	function GetDataProductsOnCart() {
		global $DBobject;
		
		$cart_arr = array ();
		
		$sql = "SELECT 	cartitem_id, cartitem_cart_id, cartitem_product_id, cartitem_product_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst 
				FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
		
		$res = $DBobject->wrappedSql ( $sql, array (
				":id" => $this->cart_id 
		) );
		
		foreach ( $res as $p ) {
			$cart_arr [$p ['cartitem_id']] = $p;
			// ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
			$sql = "SELECT 	cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
					FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
			
			$res2 = $DBobject->wrappedSql ( $sql, array (
					":id" => $p ['cartitem_id'] 
			) );
			$cart_arr [$p ['cartitem_id']] ['attributes'] = $res2;
			
			// ---------------- PRODUCTS DETAILS FROM tbl_gallery ----------------
			$sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery 
					WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
			
			$res2 = $DBobject->wrappedSql ( $sql, array (
					":id" => $p ['cartitem_product_id'] 
			) );
			$cart_arr [$p ['cartitem_id']] ['gallery'] = $res2;
			
			/*
			 * //---------------- PRODUCTS DETAILS FROM tbl_product ---------------- $sql = "SELECT product_weight, product_width, product_height, product_length FROM tbl_product WHERE product_id = :id AND product_deleted IS NULL AND product_instock = 1 AND product_published = 1"; $res2 = $DBobject->wrappedSql($sql, array( ":id" => $p['cartitem_product_id'] )); $cart_arr[$p['cartitem_id']]['details']= $res2;
			 */
		}
		return $cart_arr;
	}
	
	/**
	 * Return the recordset of the current cart
	 * @return array
	 */
	function GetDataCart($cartId = null) {
		global $DBobject;
		
                if (is_null($cartId)){
                    $cartId = $this->cart_id;
                }
                
		$sql = "SELECT * FROM tbl_cart
    			WHERE cart_id = :id AND cart_deleted IS NULL AND cart_id <> '0'";
		
		$res = $DBobject->wrappedSql ( $sql, array (
				":id" => $cartId 
		) );
		
		return $res [0];
	}
	
	/**
	 * Calculate total and return all amounts of the current cart 
	 * @return array
	 */
	function CalculateTotal() {
		global $DBobject;
		
		$subtotal = 0;
		$discount = 0;
		$shipping = 0;
		$total = 0;
		// --------------- SUBTOTAL ------------
		$sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
		$res = $DBobject->wrappedSql ( $sql, array (
				":id" => $this->cart_id 
		) );
		if ($res [0] ['SUM']) {
			$subtotal = $res [0] ['SUM'];
			
			// --------------- DISCOUNT AMOUNT ------------
			$discount = 0;
			
			$cart = $this->GetDataCart();
			if ($cart['cart_discount_code']) {
				$discArr = $this->ApplyDiscountCode($cart['cart_discount_code']);
				$discount = $discArr['discount'];
			}
			
			// --------------- SHIPPING FEE ------------
			$shipping = $this->SHIPPING_FEE; // <<<<===== CHANGE THIS
			/*
			 * $sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'"; if ( $res = $DBobject->wrappedSql($sql, array( ":id" => $this->cart_id )) ) { $subtotal = $res[0]['SUM']; }
			 */
			
			$total = $subtotal + $shipping - $discount;
		}
		$params = array (
				":id" => $this->cart_id,
				":subtotal" => $subtotal,
				":fee" => $shipping,
				":total" => $total 
		);
		$sql = "UPDATE tbl_cart SET cart_subtotal = :subtotal, cart_shipping_fee = :fee, cart_total = :total, cart_modified = now() WHERE cart_id = :id";
		
		$sum = array ();
		if ($res = $DBobject->wrappedSql ( $sql, $params )) {
			$sum ['subtotal'] = $subtotal;
			$sum ['shipping'] = $shipping;
			$sum ['discount'] = $discount;
			$sum ['total'] = $total;
		}
		
		return $sum;
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
		
		if ($this->cart_id == '' || $this->cart_id == '0') {
			$this->__construct ();
		}
		
		if ( is_null($cartId) ) {
			$cartId = $this->cart_id;
		}
		
		$quantity = intval($quantity);
		$price = floatval($price);
		$message = '';
		
		$product = $this->GetProductCalculation($productId, $AttributesArr);
		
		if ($product['error']) {
			return $product['error_message'];
		}

		if ($product['product_price'] <> $price ) {
			$message = "The price of '{$product['product_name']}' has been updated. ";
		}
		
		$cartItem = $this->ProductOnCart ( $product ['product_id'], $product ['attributes'] );
		
		if ($cartItem ['cartitem_id'] == 0) {
			$subtotal = floatval ( $quantity ) * floatval ( $product ['product_price'] );
			$params = array (
					":cid" => $cartId,
					":product_id" => $product ['product_id'],
					":product_name" => $product ['product_name'],
					":product_price" => $product ['product_price'],
					":qty" => $quantity,
					":subtotal" => $subtotal,
					":product_gst" => $product ['product_gst'],
					":ip" => $_SERVER ['REMOTE_ADDR'],
					":browser" => $_SERVER ['HTTP_USER_AGENT'] 
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
			
			if ($res = $DBobject->wrappedSql ( $sql, $params )) {
				$errorCnt = 0;
				$cartitem_id = $DBobject->wrappedSqlIdentity ();
				foreach ( $product ['attributes'] as $attr ) {
					
					$params = array (
							":cid" => $cartitem_id,
							":attribute_id" => $attr ['attribute_id'],
							":attr_value_id" => $attr ['attr_value_id'],
							":attribute_name" => $attr ['attribute_name'],
							":attr_value_name" => $attr ['attr_value_name'] 
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
					
					$res2 = $DBobject->wrappedSql ( $sql, $params );
					if (! $res2) {
						$errorCnt ++;
					}
				}
				if ($errorCnt == 0) {
					$this->CalculateTotal ();
					return "'{$product ['product_name']}' was added. {$message}";
				}
			}
		} else {
			
			$quantity = intval ( $cartItem ['cartitem_quantity'] ) + $quantity;
			$params = array (
					":id" => $cartItem ['cartitem_id'],
					":qty" => $quantity,
					":price" => $product ['product_price'],
					":subtotal" => $quantity * $product ['product_price'] 
			);
			$sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_product_price = :price, cartitem_subtotal = :subtotal, cartitem_modified = now()  
	                WHERE cartitem_id = :id";
			
			if ($DBobject->wrappedSql ( $sql, $params )) {
				$this->CalculateTotal ();
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
	function GetProductCalculation($product_id, $attributesArray = array()) {
		global $DBobject;
		
		// --------------- GET PRODUCT INFO --------------------
		
		$params = array (
				":id" => $product_id 
		);
		$sql = "SELECT product_name FROM tbl_product WHERE product_id = :id ";
		$res = $DBobject->wrappedSql ( $sql, $params );
		$productName = $res[0]['product_name'];
		
		$sql = "SELECT product_id, product_name, product_price, product_specialprice, product_gst, product_weight, product_width, product_height, product_length FROM tbl_product
				WHERE product_id = :id AND product_deleted is null AND product_instock = 1 AND product_published = 1";
		$res = $DBobject->wrappedSql ( $sql, $params );
		if ($res) {
			$prod = $res [0];
			if ($prod ['product_specialprice'] > 0) {
				$prod ['product_price'] = $prod ['product_specialprice'];
			}
			
			$productAttr = array ();
			foreach ( $attributesArray as $attrId => $attrValId ) { // expected array to get "array(array('attribute_id' => 'attr_value_id'))"
			                                                      
				// --------------- GET PRODUCT ATTRIBUTES INFO --------------------
				$params = array (
						":vid" => $attrValId,
						":pid" => $product_id 
				);
				$sql = "SELECT * FROM tbl_attr_value
							LEFT JOIN tbl_attribute ON attr_value_attribute_id = attribute_id
    						WHERE attr_value_id = :vid AND attribute_product_id = :pid AND attribute_deleted is null AND attr_value_deleted is null";
				$attr = $DBobject->wrappedSql ( $sql, $params );
				if ($attr [0] ['attr_value_instock'] === 0) {
					return array (
							"error" => true,
							"error_message" => "'Sorry, {$prod ['product_name']}' is out of Stock" 
					);
				}
				if ($prod ['product_specialprice'] > 0) {
					$prod ['product_price'] = $prod ['product_price'] + $attr [0] ['attr_value_specialprice'];
				} else {
					$prod ['product_price'] = $prod ['product_price'] + $attr [0] ['attr_value_price'];
				}
				$prod ['product_weight'] = $prod ['product_weight'] + $attr [0] ['attr_value_weight'];
				$prod ['product_width'] = $prod ['product_width'] + $attr [0] ['attr_value_width'];
				$prod ['product_height'] = $prod ['product_height'] + $attr [0] ['attr_value_height'];
				$prod ['product_length'] = $prod ['product_length'] + $attr [0] ['attr_value_length'];
				
				$productAttr [$attrValId] ['attribute_id'] = $attr [0] ['attribute_id'];
				$productAttr [$attrValId] ['attr_value_id'] = $attr [0] ['attr_value_id'];
				$productAttr [$attrValId] ['attribute_name'] = $attr [0] ['attribute_name'];
				$productAttr [$attrValId] ['attr_value_name'] = $attr [0] ['attr_value_name'];
			}
			
			return ($product = array (
					"error" => false,
					"product_id" => $prod ['product_id'],
					"product_name" => $prod ['product_name'],
					"product_price" => $prod ['product_price'],
					"product_gst" => $prod ['product_gst'],
					"product_weight" => $prod ['product_weight'],
					"product_width" => $prod ['product_width'],
					"product_height" => $prod ['product_height'],
					"product_length" => $prod ['product_length'],
					"attributes" => $productAttr 
			));
		}
		return array (
				"error" => true,
				"error_message" => "Sorry, '{$productName}' is no longer available." 
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
		
		$params = array (
				":id" => $cartitem_id 
		);
		$sql = "UPDATE tbl_cartitem SET cartitem_deleted = now() WHERE cartitem_id = :id";
		$res = $DBobject->wrappedSql ( $sql, $params );
		
		$sql = "UPDATE tbl_cartitem_attr SET cartitem_attr_deleted = now() WHERE cartitem_attr_cartitem_id = :id";
		$res2 = $DBobject->wrappedSql ( $sql, $params );
		
		if ($res && $res2) {
			return true;
		} else {
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
		
		$result = array ();
		foreach ( $qtys as $id => $qty ) {
			$sql = "SELECT cartitem_quantity, cartitem_product_price FROM tbl_cartitem WHERE cartitem_id = :id AND cartitem_deleted IS NULL";
			
			if ($res = $DBobject->wrappedSql ( $sql, array (
					":id" => $id 
			) )) {
				
				if ($qty != $res [0] ['cartitem_quantity']) {
					$subtotal = $res [0] ['cartitem_product_price'] * $qty;
					$params = array (
							":id" => $id,
							":qty" => $qty,
							":subtotal" => $subtotal 
					);
					$sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_subtotal = :subtotal, cartitem_modified = now()
	                		WHERE cartitem_id = :id";
					if ($DBobject->wrappedSql ( $sql, $params )) {
						$result [$id] = $subtotal;
					}
				}
			}
		}
		return $result;
	}

	
	/**
	 * Validate all items on current cart and totals and return messages in array.  
	 * @return array
	 */
	function ValidateCart() {
		global $DBobject;
	
		$message = array();
		
		$sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted is null AND cartitem_cart_id = :id";
		if ($res = $DBobject->wrappedSql ( $sql, array( ":id" => $this->cart_id ) )) {
			foreach ( $res as $item ) {
				$attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
				$DBproduct = $this->GetProductCalculation($item['cartitem_product_id'], $attrs);
				
				if ($DBproduct['error']) {
					$message[] = $DBproduct['error_message'];
					$this->RemoveFromCart($item['cartitem_id']);
				} else {
					if ($DBproduct['product_price'] <> $item['cartitem_product_price'] ) {
						$sql = "UPDATE tbl_cartitem SET cartitem_product_price = :price WHERE cartitem_id = :id";
						$DBobject->wrappedSql ( $sql, array ( 
													":id" => $item['cartitem_id'],
													":price" => $DBproduct['product_price']
						));
						$message[] = "The price of '{$DBproduct['product_name']}' has been updated. ";
					}
				}
			}
		}
		
		$this->CalculateTotal();
		
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
		if ($res = $DBobject->wrappedSql ( $sql, array( ":id" => $parentId ) )) {
			if ($res[0]['listing_parent_id'] == $root ) {
                            return array_merge(array($parentId), $list);
                        } else {
                            return $this->getParentList($res[0]['listing_parent_id'], $root, array_merge(array($parentId), $list));
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
		if ($res = $DBobject->wrappedSql ( $sql, array( ":id" => $productId ) )) {
			return $this->getParentList($res[0]['product_listing_id'], $root);
		} 
		return array();
	}

	/**
	 * Validate the given discount code and calculate the amount according to items on cart. 
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
		
		$cart = $this->GetDataCart();
		
		$discount = 0;
		
		$sql = "SELECT *  FROM tbl_discount
	    			WHERE discount_code = :id AND discount_published = 1 AND discount_deleted IS NULL";
	
		if ($res = $DBobject->wrappedSql ( $sql, array ( ":id" => $code	) )) {
			$today = strtotime ( date ( "Y-m-d H:i:s" ) );
			if (strtotime ( $res[0]['discount_start_date'] ) <= $today && $today <= strtotime ( $res[0]['discount_end_date'] )) {
				// Valid code by date
				if ( $res[0]['discount_listing_id'] == 0 && $res[0]['discount_product_id'] == 0 ){
					// No filter or special code for particular category/product 
                	if ($res[0]['discount_amount_percentage']) {
                    	$discount = floatval($cart['cart_subtotal']) * floatval($res[0]['discount_amount']) / 100;
                    } else {
                    	// Discount must not be higher than subtotal
                    	if (floatval($res[0]['discount_amount']) > floatval($cart['cart_subtotal']) ){
                    		$discount = $cart['cart_subtotal'];
                    	} else {
                    		$discount = $res[0]['discount_amount'];
                    	}
                    }
				} else { // With filter or special code for a category/product
                	$sql = "SELECT 	cartitem_product_id, cartitem_subtotal FROM tbl_cartitem
                    		WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
					if ( $cartItems = $DBobject->wrappedSql ( $sql, array ( ":id" => $cartId ) )) {
                    	$listingMatchSubtotal = 0;
                        foreach ($cartItems as $item){
                        	if ($res[0]['discount_listing_id']){
                        		// Special code for category only
                            	$listingArr = $this->getProductCatParentList($item['cartitem_product_id']);
                                if (in_array_r($res[0]['discount_listing_id'], $listingArr)){
                                	if ($res[0]['discount_amount_percentage']) {
                                    	$discount += floatval($item['cartitem_subtotal']) * floatval($res[0]['discount_amount']) / 100;
                                    } else {
                                    	$listingMatchSubtotal += floatval($item['cartitem_subtotal']);
									}
								}
							} elseif ($res[0]['discount_product_id'] == $item['cartitem_product_id']){
								// Special code for product only
                            	if ($res[0]['discount_amount_percentage']) {
                                	$discount = floatval($item['cartitem_subtotal']) * floatval($res[0]['discount_amount']) / 100;
								} else {
									// Discount must not be higher than subtotal
                                	if ($res[0]['discount_amount'] > $item['cartitem_subtotal']){
                                    	$discount = $item['cartitem_subtotal']; 
									} else {
                                    	$discount = $res[0]['discount_amount'];
                                    }
								}
							}
						}
						if ($listingMatchSubtotal > 0) {
							if ($res[0]['discount_amount'] > $listingMatchSubtotal){
                            	$discount = $listingMatchSubtotal; 
							} else {
                            	$discount = $res[0]['discount_amount'];
							}
						} 
					}
				}
			} else {
				$result ['error'] = 'This code has expired';
				$code = null;
			}
		} else {
			$result ['error'] = 'Invalid Code';
			$code = null;
		}
		
		$result ['discount'] = $discount;
		$total = floatval($cart['cart_subtotal']) + floatval($cart['cart_shipping_fee']) - $discount;
		
		$params = array (
				":id" => $cartId,
				":discount" => $discount,
				":discount_code" => $code,
				":total" => $total
		);
		$sql = "UPDATE tbl_cart SET cart_discount_code = :discount_code, cart_discount = :discount, cart_total = :total, cart_modified = now() WHERE cart_id = :id";
		$res = $DBobject->wrappedSql ( $sql, $params );
		
		return $result;
	}						
	
	
/* 	function PlaceOrder($paymentArr, $billingAddressArr, $shippingAddressArr = array() ) {
		global $DBobject;
	
		$dbTotal = $this->CalculateTotal();
		return array( 'error' => 'Connection problem - Billing Address was not created. Please, try again');
		if (!empty($dbTotal) && $this->cart_id == $paymentArr['payment_cart_id']
		&& $dbTotal['subtotal'] == $paymentArr['payment_subtotal']
		&& $dbTotal['shipping'] == $paymentArr['payment_shipping_fee']
		&& floatval($dbTotal['total']) == floatval($paymentArr['payment_charged_amount']) ) {
				
				
			if ($res = $this->InsertNewAddress($billingAddressArr)) {
				$billingAddressId = $res;
			} else {
				return array( 'error' => 'Connection problem - Billing Address was not created. Please, try again');
			}
				
			if (empty($shippingAddressArr)) {
				$shippingAdddressId = $billingAddressId;
			} else {
				if ($res = $this->InsertNewAddress($shippingAddressArr)) {
					$shippingAdddressId = $res;
				} else {
					return array( 'error' => 'Connection problem - Shipping Address was not created. Please, try again');
				}
			}
				
			$sql = " INSERT INTO tbl_payment (
        						payment_cart_id, payment_user_id, payment_billing_address_id, payment_shipping_address_id,
								payment_status, payment_subtotal, payment_shipping_fee, payment_charged_amount,
        						payment_user_ip,
								payment_created
								)
							VALUES (
								:payment_cart_id, :payment_user_id, :payment_billing_address_id, :payment_shipping_address_id,
								:payment_status, :payment_subtotal, :payment_shipping_fee, :payment_charged_amount,
        						:payment_user_ip,
        						now()
							)";
			$params = array (
					":payment_cart_id" => $paymentArr['payment_cart_id'],
					":payment_user_id" => $paymentArr['payment_user_id'],
					":payment_billing_address_id" => $billingAddressId,
					":payment_shipping_address_id" => $shippingAdddressId,
					":payment_status" => 'P',
					":payment_subtotal" => $paymentArr['payment_subtotal'],
					":payment_shipping_fee" => $paymentArr['payment_shipping_fee'],
					":payment_charged_amount" => $paymentArr['payment_charged_amount'],
					":payment_user_ip" => $_SERVER ['REMOTE_ADDR']
			);
				
			if ( $DBobject->wrappedSqlInsert ( $sql, $params ) ) {
				$this->CloseCart($this->cart_id);
				$this->CreateCart($paymentArr['payment_user_id']);
				return array( 'success' => 'Thank you for Buying!');
			}
				
		}
		return array( 'error' => 'Connection problem or Values do not match. Please, try again');
	} */

	
	


}