<?php
class exceptionCart extends Exception{}
class cart{
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
  protected $validateMsg = array();


  /**
   *
   * @param int $userId
   */
  function __construct($userId = null){
    $userId = !is_numeric($userId)? 0 : $userId;
    $this->cart_user_id = empty($userId)? 0 : $userId;
    if($this->VerifySessionCart(session_id())){
      if($this->cart_user_id != $this->cart_db_user_id && !empty($this->cart_db_user_id)){
        // create new cart because the user ids don't match or user is not logged in
        session_regenerate_id();
        $this->CreateCart();
      }
      // do nothing since session cart exists
      $this->cart_id = $this->ses_cart_id;
    } else{
      // create new cart because it's a brand new session
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
  function VerifySessionCart($ses_val){
    global $DBobject, $SITE;

    $sql = "SELECT cart_id, cart_user_id FROM tbl_cart
				WHERE cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_session = :id AND cart_site = :site
				ORDER BY cart_id DESC";

    if($res = $DBobject->wrappedSql($sql, array(
        ":id" => $ses_val,
        ":site" => $SITE
    ))){
      $this->ses_cart_id = $res[0]['cart_id'];
      $this->cart_db_user_id = $res[0]['cart_user_id'];
      return true;
    } else{
      return false;
    }
  }


  /**
   * If there is opened session with userid
   * if userid-cart has items then : OPEN new-cart / MERGE both cart to new-cart / SET old sessionID /
   * if userid-cart has NO items then : CLOSE userid-cart / UPDATE current-cart with userid
   * If there is NO opened session with userid: UPDATE current-cart with userid
   *
   * @return boolean
   */
  private function SetUserCart(){
    global $DBobject, $SITE;

    if(!empty($this->cart_user_id)){
      $sql = "SELECT * FROM tbl_cart WHERE cart_user_id = :uid AND cart_site = :site AND cart_closed_date IS NULL AND cart_deleted IS NULL AND cart_id <> '0' ORDER BY cart_id DESC";
      if($res = $DBobject->wrappedSql($sql, array(
          ":uid" => $this->cart_user_id,
          ":site" => $SITE
      ))){
        if($this->NumberOfProductsOnCart($res[0]['cart_id']) && empty($this->NumberOfProductsOnCart())){
          $old_cart_id = $this->cart_id;
          $this->ResetSession($res[0]['cart_session']);
          $this->CreateCart();
          $this->MergeCarts(array(
              $res[0]['cart_id'],
              $old_cart_id
          ), $this->cart_id);
          return $message;
        } else{
          foreach($res as $r){
            if($r['cart_id'] != $this->cart_id){
              $this->DeleteCart($r['cart_id']);
            }
          }
          $this->UpdateUserIdCart();
          return true;
        }
      } else{
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
  function ResetSession($id = null){
    $sessionBackup = $_SESSION;
    session_destroy();
    session_id($id);
    session_start();
    $_SESSION = $sessionBackup;
    return true;
  }


  /**
   * Create new cart, set user_id when given
   */
  function CreateCart(){
    global $DBobject, $SITE;

    //Google Analytics Client ID
    $gaClient = '';
    try{
      $gaClient = gaParseCookie();
    }catch(Exception $e){}

    $this->cart_session = session_id();
    $sql = " INSERT INTO tbl_cart ( cart_created, cart_session, cart_user_id, cart_site, cart_ga_clientid )
        VALUES ( now(), :sid, :uid, :site, :gaid )";
    $params = array(
        ":sid" => $this->cart_session,
        ":uid" => $this->cart_user_id,
        ":gaid" => $gaClient,
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
  function MergeCarts($originArr, $destination){
    global $DBobject;

    $firstCreated = date("Y-m-d H:i:s");
    $code = null;

    try{
      foreach($originArr as $origin){
        $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted is null AND cartitem_cart_id = :id AND cartitem_product_id != 225";
        $orig_items = $DBobject->wrappedSql($sql, array(
            ":id" => $origin
        ));

        if($orig_items){
          foreach($orig_items as $item){
            $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
            $this->validateMsg[] = $this->AddToCart($item['cartitem_product_id'], $attrs, $item['cartitem_product_price'], $item['cartitem_quantity'], $destination, $item['cartitem_variant_id'], $item['cartitem_listname']);
          }
        }

        $sql = "SELECT cart_discount_code, cart_created, cart_modified FROM tbl_cart WHERE cart_closed_date is null AND cart_deleted is null AND cart_id = :id";
        $orig_cart = $DBobject->wrappedSql($sql, array(
            ":id" => $origin
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
          ":firstCreated" => $firstCreated,
          ":code" => $code,
          ":id" => $destination
      );
      $res = $DBobject->wrappedSql($sql, $params);
      $this->ValidateCart();
      return true;
    }
    catch(Exception $e){}
    return false;
  }


  /**
   * Returns an array with cartitem_attr_attribute_id as key and cartitem_attr_attr_value_id a values
   * given the cartitem_attr_cartitem_id from tbl_cartitem_attr.
   *
   * @param int $cartItemId
   * @return array
   */
  function GetAttributesIdsOnCartitem($cartItemId){
    global $DBobject;

    $attrArr = array();
    $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL";
    if($cart_arr = $DBobject->wrappedSql($sql, array(
        ":id" => $cartItemId
    ))){
      foreach($cart_arr as $a){
        $attrArr[$a['cartitem_attr_attribute_id']]['id'] = $a['cartitem_attr_attr_value_id'];
        $attrArr[$a['cartitem_attr_attribute_id']]['additional'] = (empty($a['cartitem_attr_attr_value_additional'])? '' : json_decode($a['cartitem_attr_attr_value_additional']));
      }
    }
    return $attrArr;
  }


  /**
   * Set the cart_user_id field in tbl_cart with given userid
   *
   * @return boolean
   */
  function UpdateUserIdCart(){
    global $DBobject;

    $params = array(
        ":uid" => $this->cart_user_id,
        ":cid" => $this->cart_id
    );
    $sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_id = :cid";
    if($DBobject->wrappedSql($sql, $params)){
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
  function UpdateUserIdOfClosedCart($cartId, $userId){
    global $DBobject;

    if(!empty($cartId) && !empty($userId)){
      $sql = "SELECT cart_id FROM tbl_cart WHERE cart_closed_date IS NOT NULL AND cart_id = :cid";
      if($res = $DBobject->wrappedSql($sql, array(
          ":cid" => $cartId
      ))){
        $sql = "UPDATE tbl_cart SET cart_user_id = :uid WHERE cart_closed_date IS NOT NULL AND cart_id = :cid";
        if($DBobject->wrappedSql($sql, array(
            ":uid" => $userId,
            ":cid" => $cartId
        ))){
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
  function CloseCart($cart_id = null){
    global $DBobject;

    if(is_null($cart_id)){
      $cart_id = $this->cart_id;
    }
    $sql = "UPDATE tbl_cart SET cart_closed_date = now() WHERE cart_id = :id";
    return $DBobject->wrappedSql($sql, array(
        ":id" => $cart_id
    ));
  }


  /**
   * Delete current cart (or given cart_id) by seeting current datetime in cart_deleted field
   *
   * @param unknown $cart_id
   * @return Ambigous <multitype:, boolean, void, resource, unknown, multitype:>
   */
  function DeleteCart($cart_id = null){
    global $DBobject;

    if(is_null($cart_id)){
      $cart_id = $this->cart_id;
    }
    $sql = "UPDATE tbl_cart SET cart_deleted = now() WHERE cart_id = :id";
    return $DBobject->wrappedSql($sql, array(
        ":id" => $cart_id
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
  function ProductOnCart($productObjId, $variantId, $attributesArray){
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

        if(count(array_diff_assoc($feAttr, $dbAttr)) === 0 && count(array_diff_assoc($dbAttr, $feAttr)) === 0){
          // Item found
          return $item;
        }
      }
    }
    return array(
        'cartitem_id' => 0
    );
  }


  /**
   * Return number of items on cart
   *
   * @return int
   */
  function NumberOfProductsOnCart($cid = null){
    global $DBobject;

    if(is_null($cid)){
      $cid = $this->cart_id;
    }
    if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0'){
      $sql = "SELECT SUM(cartitem_quantity) AS SUM FROM tbl_cartitem
					WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";

      $cart_arr = $DBobject->wrappedSql($sql, array(
          ":id" => $cid
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
  function ShippableCartitems($cid = null){
    global $DBobject;

    if(empty($cid)){
      $cid = $this->cart_id;
    }
    $cart_arr = array();
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND (cartitem_type_id = 1 OR cartitem_type_id = 4) AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, array(
        ":id" => $cid
    ))){
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
  function GetDataProductsOnCart($cartId = null, $disableUnclean = false){
    global $DBobject;

    if(empty($cartId)){
      $cartId = $this->cart_id;
    }

    $cart_arr = array();

    $sql = "SELECT * FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
        LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id
      WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id AND product_published = 1 AND product_deleted IS NULL AND variant_deleted IS NULL";
    $res = $DBobject->wrappedSql($sql, array(
        ":id" => $cartId
    ));
    if($res) foreach($res as $p){

      $cart_arr[$p['cartitem_id']] = $p;

      // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
      $sql = "SELECT * FROM tbl_cartitem_attr WHERE cartitem_attr_cartitem_id = :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0' ORDER BY cartitem_attr_order";
      $params = array(":id" => $p['cartitem_id']);
      $cart_arr[$p['cartitem_id']]['attributes'] = $DBobject->wrappedSql($sql, $params);

      // ---------------- PRODUCT CATEGORY ----------------
      $cart_arr[$p['cartitem_id']]['category'] = $this->getFullCategoryName($p['cartitem_product_id']);

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

    $this->cartProducts = $disableUnclean ? $cart_arr : unclean($cart_arr);
    return $this->cartProducts;
  }


  /**
   * Return a recordset array of the current cart (or given cartId).
   * Only tbl_cart.
   *
   * @return array
   */
  function GetDataCart($cartId = null){
    global $DBobject;

    if(empty($cartId) && !empty($this->cartRecord)){
      return $this->cartRecord;
    }
    $sql = "SELECT * FROM tbl_cart WHERE cart_id = :id AND cart_deleted IS NULL AND cart_id <> 0";
    if($res = $DBobject->wrappedSql($sql, array(
        ":id" => $cartId
    ))){
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
  function GetOrderHistoryByUser($userId){
    global $DBobject, $SITE;

    $cart_arr = array();

    $sql = "SELECT tbl_cart.*, tbl_payment.*, status_id, status_order, status_name FROM tbl_cart LEFT JOIN tbl_payment ON cart_id = payment_cart_id LEFT JOIN tbl_order ON order_payment_id = payment_id LEFT JOIN tbl_status ON order_status_id = status_id
    			WHERE cart_user_id = :uid AND cart_site = :site  AND payment_status != 'F' AND cart_deleted IS NULL AND cart_closed_date IS NOT NULL AND cart_id <> '0' AND order_deleted IS NULL ORDER BY cart_closed_date DESC";

    if($res = $DBobject->wrappedSql($sql, array(
        ":uid" => $userId,
        ":site" => $SITE
    ))){
      foreach($res as $order){
        $cart_arr[$order['cart_id']] = $order;

        // Get cartitem details
        $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0' ";
        $cartitems = $DBobject->wrappedSql($sql, array(
            ":id" => $order['cart_id']
        ));
        foreach($cartitems as $p){
          // ---------------- ATTRIBUTES SAVED IN tbl_cartitem_attr ----------------
          $sql = "SELECT cartitem_attr_id, cartitem_attr_cartitem_id, cartitem_attr_attribute_id, cartitem_attr_attr_value_id, cartitem_attr_attribute_name, cartitem_attr_attr_value_name
					FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL AND cartitem_attr_cartitem_id <> '0'";
          $res2 = $DBobject->wrappedSql($sql, array(
              ":id" => $p['cartitem_id']
          ));
          $p['attributes'] = $res2;

          $sql = "SELECT product_id FROM tbl_product WHERE product_deleted IS NULL AND product_published = 1 AND product_object_id = :pid ";
          if($product = $DBobject->wrappedSql($sql, array(
              ":pid" => $p['cartitem_product_id']
          ))){

            // ---------------- BUILD URL ----------------
            $p['url'] = '';
            if(!empty($p['attributes']) && !empty($p['url'])){
              foreach($p['attributes'] as $k => $a){
                if($k == 0){
                  $p['url'] .= '?' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
                } else{
                  $p['url'] .= '&' . strtolower($a['cartitem_attr_attribute_name']) . '=' . strtolower($a['cartitem_attr_attr_value_name']);
                }
              }
            }
            // ---------------- PRODUCTS DETAILS FROM tbl_gallery ----------------
            $sql = "SELECT gallery_title, gallery_link, gallery_alt_tag FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL ORDER BY gallery_ishero DESC";
            $p['gallery'] = $DBobject->wrappedSql($sql, array(
                ":id" => $p['product_id']
            ));
          }
          $cart_arr[$order['cart_id']]['items'][$p['cartitem_id']] = $p;
        }

        $sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
        $res = $DBobject->wrappedSql($sql, array(
            ':id' => $order['payment_billing_address_id']
        ));
        $cart_arr[$order['cart_id']]['billing'] = $res[0];
        $res = $DBobject->wrappedSql($sql, array(
            ':id' => $order['payment_shipping_address_id']
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
  function CalculateTotal(){
    global $DBobject, $CONFIG_VARS;

    $auto_discount_msg = '';
    // check if valid for SENIOR or STAINLESS-STEEL offer and auto apply the best offer
    if(empty($_SESSION['autoApplydiscount']) && $this->getApplyBestDiscount()){
        $auto_discount_msg = 'Best discount code is automatically applied to cart';
    }
    $shippingFee = 0;
    $tax = 0;
    if(!empty($CONFIG_VARS['postage'])){
      $shippingFee = floatval(str_replace('$', '', trim($CONFIG_VARS['postage'])));
    }
    if(!empty($CONFIG_VARS['gst'])){
      $tax = floatval(trim($CONFIG_VARS['gst']));
    }

    $discount = 0;
    $discount_error = '';

    $cart = $this->GetDataCart($this->cart_id);

    $hasBupaDiscount = false;
    $hasAutismDiscount = false;
    $hasSeniorsDiscount = false;
    $hasBenevolentDiscount = false;

    if(!empty($cart['cart_discount_code'])){
      $discountData = $this->GetDiscountData($cart['cart_discount_code']);
      $discArr = $this->ApplyDiscountCode($cart['cart_discount_code'], $shippingFee);
      $discount = $discArr['discount'];
      $discount_error = $discArr['error'];

      switch($cart['cart_discount_code']){
        case 'BUPA17':
        case 'BUPA18':
        case 'BUPA'.date('y'):{
          //ONLY FOR MAF - BUPA OFFER
          if(empty($this->cart_user_id)){
            $discount = $this->GetBupaDiscount($shippingFee);
            $hasBupaDiscount = true;
            $discount_error = '';
            if(empty($discount)){
              $discount_error = 'Please add a valid product for this offer.';
              $hasBupaDiscount = false;
              $this->ApplyDiscountCode('');
            }
          }else{
            $discount_error = 'This offer is for new members only.';
            $this->ApplyDiscountCode('');
          }
          break;
        }

        case 'AUTISM16':
        case 'AUTISM18':
        case 'AUTISM'.date('y'):{
          //ONLY FOR MAF - AUTISM16 OFFER
          if(empty($this->cart_user_id)){
            $discount = $this->GetAutismDiscount($shippingFee);
            $hasAutismDiscount = true;
            $discount_error = '';
            if(empty($discount)){
              $discount_error = 'Please add a valid product for this offer.';
              $hasAutismDiscount = false;
            }
          }else{
            $discount_error = 'This offer is for new members only.';
          }
          break;
        }

        case 'SENIORS':{
          //ONLY FOR MAF - SENIORS OFFER - SENIORS
          //2nd part - 20% off membership
          $discount += $this->GetMSFDiscount(20);
          $hasSeniorsDiscount = true;
          if($discount > 0) $discount_error = '';
          break;
        }

        case 'BENEVOLENT-CPSC':
        case 'BENEVOLENT-HAE':
        case 'STJOHNS':{
          //ONLY FOR MAF - BENEVOLENT PROGRAM OFFER
          if(empty($this->cart_user_id)){
            $discount = $this->GetBenevolentDiscount($shippingFee);
            $hasBenevolentDiscount = true;
            $discount_error = '';
            if(empty($discount)){
              $discount_error = 'Please add a valid product for this offer.';
              $hasBenevolentDiscount = false;
            }
          }else{
            $discount_error = 'This offer is for new members only.';
          }
          break;
        }

        case 'STAINLESS-STEEL':{
          $discount = $this->GetStainlessSteelDiscount();
          break;
        }
      }
    }

    // For MAF only
    if(!$hasBupaDiscount){

      $removeFutureYear = false;
      // check if new member
      if(empty($this->cart_user_id) ){
        $removeFutureYear = true;
      }
      else{
        // check if the serverdate year is less than renewal date year
        // and the days diff between serverdate and renewal date is less than 45 days
        // then skip the part of removing the next year membership fee from cart
        try{
          $serverDate = date_create(date('Y-m-d'));
          $memberRenewalDate = date_create($_SESSION['user']['public']['maf']['main']['user_RenewalDate']);
          $serverDateYear = date('Y');
          $renewalDateYear = date('Y', strtotime($_SESSION['user']['public']['maf']['main']['user_RenewalDate']));
          //difference between two dates
          $diff = date_diff($serverDate,$memberRenewalDate);
          //count days
          $daysDiff = $diff->format("%a");
          if($serverDateYear >= $renewalDateYear || $daysDiff > 45){
          	$removeFutureYear = true;
          }
        }catch (Exception $e){

        }

      }
      if($removeFutureYear) {
        //remove MAF membership fee - next year
        $msfArr = $this->GetCurrentMAF_MSF(225, date('Y', strtotime('+1 year')));
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        $this->RemoveFromCart($membershipFeeCartitemId);
      }
    }

    $subtotal = $this->GetSubtotal();
    $gst_taxable = $this->GetGSTSubtotal();
    $gst = round($gst_taxable * (1 - 100 / (100 + $tax)) , 2);
    $discount_code_msg = '';
    if($auto_discount_msg != ''){
      $discount_code_msg = "'".$discountData['discount_name']."' has been successfully applied.<br/>".$auto_discount_msg;
    }

    return array(
        'subtotal' => $subtotal,
        'discount' => $discount,
        'discount_code' => $cart['cart_discount_code'],
        'discount_code_msg' => $discount_code_msg,
        'discount_error' => $discount_error,
        'GST_Taxable' => $gst_taxable,
        'GST' => $gst,
        'total' => $subtotal - $discount
    );
  }


  /**
   * Return subtotal amount of items which incl-GST a given cart_id
   *
   * @return array
   */
  function GetGSTSubtotal($cartId = null){
    global $DBobject;

    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }

    $sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_product_gst = 1 AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
    $res = $DBobject->wrappedSql($sql, array(
        ":id" => $cartId
    ));
    return (empty($res[0]['SUM'])? 0 : $res[0]['SUM']);
  }


  /**
   * Return subtotal of a given cart_id
   *
   * @return array
   */
  function GetSubtotal($cartId = null){
    global $DBobject;

    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }

    $sql = "SELECT SUM(cartitem_subtotal) AS SUM FROM tbl_cartitem
    			WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
    $res = $DBobject->wrappedSql($sql, array(
        ":id" => $cartId
    ));
    return (empty($res[0]['SUM'])? 0 : $res[0]['SUM']);
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
  function AddToCart($productId, $attributesArr, $price, $quantity = 1, $cartId = null, $variantId = 0, $listname = null){
    global $DBobject;

    if($this->cart_id == '' || $this->cart_id == '0'){
      $this->__construct();
    }

    if(empty($cartId)){
      $cartId = $this->cart_id;
    }

    $quantity = empty($quantity)? 1 : intval($quantity);
    $price = floatval($price);
    $message = '';

    $product = $this->GetProductCalculation($productId, $attributesArr, $quantity, $price, $variantId);
    // It will return exception if product is not available

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
          ":uid" => (empty($product['variant_uid'])? $product['product_uid'] : $product['variant_uid']),
          ":product_name" => $product['product_name'],
          ":variant_name" => $product['variant_name'],
          ":product_price" => $product['product_price'],
          ":qty" => $quantity,
          ":subtotal" => $subtotal,
          ":product_gst" => $product['product_gst'],
          ":listname" => $listname,
          ":ip" => $_SERVER['REMOTE_ADDR'],
          ":browser" => $_SERVER['HTTP_USER_AGENT']
      );
      $sql = "INSERT INTO tbl_cartitem ( cartitem_cart_id, cartitem_product_id, cartitem_variant_id, cartitem_type_id, cartitem_product_uid, cartitem_product_name, cartitem_variant_name, cartitem_product_price, cartitem_quantity, cartitem_subtotal, cartitem_product_gst, cartitem_listname, cartitem_user_ip, cartitem_user_browser, cartitem_created )
        values( :cid, :product_id, :variant_id, :type_id, :uid, :product_name, :variant_name, :product_price, :qty, :subtotal, :product_gst, :listname, :ip, :browser, now() )";
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
            $errorCnt++;
          }
        }
        if($errorCnt == 0){
          return "'{$product ['product_name']}' was added. {$message}";
        }
      }
    } else{

      $quantity = intval($cartItem['cartitem_quantity']) + $quantity;
      $params = array(
          ":id" => $cartItem['cartitem_id'],
          ":qty" => $quantity,
          ":price" => $product['product_price'],
          ":subtotal" => $quantity * $product['product_price']
      );
      $sql = "UPDATE tbl_cartitem SET cartitem_quantity = :qty, cartitem_product_price = :price, cartitem_subtotal = :subtotal, cartitem_modified = now()
        WHERE cartitem_id = :id";
      if($DBobject->wrappedSql($sql, $params)){
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
  function GetProductCalculation($product_id, $attributesArray = array(), $qty = 0, $frontEndPrice = 0, $variant_id = 0){
    global $DBobject;

    $params = array(
        ':oid' => $product_id
    );

    // --------------- GET PRODUCT INFO --------------------
    $sql = "SELECT product_name FROM tbl_product WHERE product_object_id = :oid ORDER BY product_published = 1 DESC, product_modified DESC";
    $res = $DBobject->wrappedSql($sql, $params);
    $productName = $res[0]['product_name'];

    $params[":variant_id"] = $variant_id;

    // --------------- GET BASE PRODUCT INFO --------------------
    $sql = "SELECT tbl_product.*, tbl_variant.* FROM tbl_product LEFT JOIN tbl_variant ON variant_product_id = product_id
        WHERE product_deleted IS NULL AND product_published = 1 AND variant_deleted IS NULL AND product_object_id = :oid AND variant_id = :variant_id GROUP BY product_object_id, variant_id";
    if($res = $DBobject->wrappedSql($sql, $params)){
      $prod = $res[0];

      // Check variant and attributes
      $totalAttr = count($attributesArray);
      $attrCnt = 0;
      // expected array to get "array(array('attribute_id' => 'attr_value_id'))"
      if($totalAttr && $totalAttr>0) foreach($attributesArray as $attrId => $valId){
        $params2 = array(
            ':variant_id' => $variant_id,
            ':attr' => $attrId,
            ':val' => $valId['id']
        );
        $sql = "SELECT productattr_id FROM tbl_productattr WHERE productattr_deleted IS NULL AND productattr_variant_id = :variant_id AND productattr_attribute_id = :attr AND productattr_attr_value_id = :val";
        if($DBobject->wrappedSql($sql, $params2)){
          $attrCnt++;
        }
      }

      if($attrCnt != $attrCnt){
        throw new exceptionCart("<b>{$productName}</b> cannot be found.");
        return false;
      }

      if(empty($prod['variant_published'])){
        throw new exceptionCart("<b>{$productName}</b> is no longer available.");
        return false;
      }

      if(empty($prod['variant_instock'])){
        throw new exceptionCart("<b>{$productName}</b> is out of stock.");
        return false;
      }

      // Set initial product price
      if($prod['variant_editableprice'] == 1){
        $productPrice = ($frontEndPrice > 1000)? 1000 : round($frontEndPrice, 0);
      } else{
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
        if($attr = $DBobject->wrappedSql($sql, array(
            ":id" => $attrId
        ))){
          $productAttr[$cnt]['attribute_id'] = $attr[0]['attribute_id'];
          $productAttr[$cnt]['attribute_name'] = $attr[0]['attribute_name'];
          $productAttr[$cnt]['attribute_order'] = $attr[0]['attribute_order'];
        }

        // --------------- GET ATTRIBUTE-VALUES INFO --------------------
        $productAttr[$cnt]['attr_value_id'] = 0;
        $productAttr[$cnt]['attr_value_name'] = '';
        $sql = "SELECT attr_value_id, attr_value_name FROM tbl_attr_value WHERE attr_value_id = :id AND attr_value_deleted IS NULL";
        if($attr = $DBobject->wrappedSql($sql, array(
            ":id" => $valId['id']
        ))){
          $productAttr[$cnt]['attr_value_id'] = $attr[0]['attr_value_id'];
          $productAttr[$cnt]['attr_value_name'] = $attr[0]['attr_value_name'];
          $productAttr[$cnt]['attr_value_additional'] = (empty($valId['additional'])? '' : json_encode($valId['additional']));
        }

        $cnt++;
      }

      // Set product price with bulk discount
      $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
      $params2 = array(
          ":qty" => $qty,
          ":pid" => $prod['variant_id']
      );
      if($mod = $DBobject->wrappedSql($sql, $params2)){
        if(intval($mod[0]['productqty_percentmodifier']) == 1){
          $productPrice = $productPrice - ($productPrice * ($mod[0]['productqty_modifier'] / 100));
        } else{
          $productPrice = $productPrice - ($mod[0]['productqty_modifier']);
        }
      }

      $prod['product_price'] = round($productPrice, 2);
      $prod['attributes'] = $productAttr;
      $this->dbProducts[$product_id] = $prod;
      return $prod;
    }
    throw new exceptionCart("<b>{$productName}</b> is no longer available.");
  }


  /**
   * Delete product item on cart
   *
   * @param int $cartitem_id
   * @return boolean
   */
  function RemoveFromCart($cartitem_id){
    global $DBobject;

    $params = array(
        ":id" => $cartitem_id
    );
    $sql = "UPDATE tbl_cartitem SET cartitem_deleted = now() WHERE cartitem_id = :id";
    $res = $DBobject->wrappedSql($sql, $params);

    $sql = "UPDATE tbl_cartitem_attr SET cartitem_attr_deleted = now() WHERE cartitem_attr_cartitem_id = :id";
    $res2 = $DBobject->wrappedSql($sql, $params);

    if($res && $res2){
      return true;
    } else{
      return false;
    }
  }


  /**
   * Update product items quantities on cart
   *
   * @param array $qtys
   * @return array
   */
  function UpdateQtyCart($qtys){
    global $DBobject;

    $result = array();
    foreach($qtys as $id => $qty){
      $sql = "SELECT cartitem_quantity, cartitem_product_price, cartitem_product_id, product_id, variant_editableprice, cartitem_variant_id
      		FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
            LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id
      		WHERE cartitem_id = :id AND cartitem_deleted IS NULL AND product_deleted IS NULL AND variant_deleted IS NULL AND product_published = '1'";

      if($res = $DBobject->wrappedSql($sql, array(
          ":id" => $id
      ))){

        if($qty != $res[0]['cartitem_quantity']){
          $attrs = $this->GetAttributesIdsOnCartitem($id);
          $DBproduct = $this->GetProductCalculation($res[0]['cartitem_product_id'], $attrs, $qty, $res[0]['cartitem_product_price'], $res[0]['cartitem_variant_id']);
          $price = ($res[0]['variant_editableprice'] == 1)? $res[0]['cartitem_product_price'] : $DBproduct['product_price'];
          $subtotal = $price * $qty;
          $pricemodifier = "";

          // Set product bulk discount
          $sql = "SELECT * FROM tbl_productqty WHERE productqty_variant_id = :pid AND productqty_qty <= :qty AND productqty_deleted IS NULL ORDER BY productqty_qty DESC ";
          $params = array(
              ":qty" => $qty,
              ":pid" => $res[0]['product_id']
          );
          if($mod = $DBobject->wrappedSql($sql, $params)){
            if(intval($mod[0]['productqty_percentmodifier']) == 1){
              $pricemodifier = intval($mod[0]['productqty_modifier']) . "%";
            } else{
              $pricemodifier = "$" . intval($mod[0]['productqty_modifier']);
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
          if($DBobject->wrappedSql($sql, $params)){
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
  function ValidateCart($_memberArr = array(), $isquick = false){
    global $DBobject;

    //VALIDATE MAF MEMBERS
    $addMSF = false;
    $addReactivationFee = false;
    $hasMAFProd = $this->HasMAFProducts();
    //MAF membership fee
    $year = '';
    $isNextYearRenewal= false;
    $isLastYearRenewal= false;

    if(!empty($_memberArr['maf']['main']['reactivation']) && !empty($_memberArr['maf']['main']['user_RenewalDate']) && $_memberArr['maf']['main']['reactivation'] == 'f'){
      $renewalYear = date('Y', strtotime($_memberArr['maf']['main']['user_RenewalDate']));
      $renewalDate = date_create_from_format('Y-m-d', $_memberArr['maf']['main']['user_RenewalDate']);
      $today = new DateTime();
      $interval = $renewalDate->diff($today);
      $year_diff = floatval($interval->format('%R%y.%m%d'));
      if(!empty($renewalYear) && $year_diff < 1){
        if(date('Y') == $renewalYear + 1){
          $isLastYearRenewal= true;
          $year = $renewalYear;
        }elseif(date('Y') == $renewalYear - 1){
          $isNextYearRenewal= true;
          $year = $renewalYear;
        }
      }
    }
    $msfArr = $this->GetCurrentMAF_MSF(225, $year);
    $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
    if(empty($_memberArr['id']) && $hasMAFProd && ($this->NumberOfProductsOnCart() > 1 || empty($membershipFeeCartitemId))){
      //Add "member service fee - current_year" when member is not logged in
      //Prevent from having a MAF membership fee without a product
      $addMSF = true;
    } elseif(!empty($_memberArr['maf']) && empty($_memberArr['maf']['main']['lifetime'])){
      //Existing member

      //Avoid duplicate payments - member service fee

      $addMSF = (($_memberArr['maf']['main']['renew'] == 't' || $_memberArr['maf']['main']['renew_option'] == 't') && !$this->HasPaidMSF($_memberArr['id'], $msfArr['product_object_id'], $msfArr['variant_id']))? true : false;

      //Avoid duplicate payments - reactivation fee
      $addReactivationFee = ($_memberArr['maf']['main']['reactivation'] == 't' && !$this->HasPaidMSF($_memberArr['id'], 225, 16))? true : false;
    }

    if(!$isquick && ($_memberArr['maf']['main']['renew_option'] === 't' )){
      $addMSF = false;
    }

    //Add/remove MAF membership fee
    if($addMSF && empty($membershipFeeCartitemId)){
      $this->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
    } elseif(!$addMSF && !empty($membershipFeeCartitemId)){
      $this->RemoveFromCart($membershipFeeCartitemId);
    }

    //To remove MSF of previous year
    if(empty($year)){
      $msfArrOld = $this->GetCurrentMAF_MSF(225, date('Y') - 1);
      if($membershipFeeCartitemIdOld = $this->hasProductInCart($msfArrOld['product_object_id'], $msfArrOld['variant_id'])){
        $this->RemoveFromCart($membershipFeeCartitemIdOld);
      }
    }

    //Make sure last year MAF membership fee is not added
    if($isNextYearRenewal || $isLastYearRenewal){
      $msfArr = $this->GetCurrentMAF_MSF(225);
      $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
      if(!empty($membershipFeeCartitemId)){
        $this->RemoveFromCart($membershipFeeCartitemId);
      }
    }

    //Add/remove MAF reactivation fee
    $reactivationCartitemId = $this->hasProductInCart(225, 16);
    if($addReactivationFee && empty($reactivationCartitemId)){
      $this->AddToCart(225, array(), 0, 1, null, 16);
    } elseif(!$addReactivationFee && !empty($reactivationCartitemId)){
      $this->RemoveFromCart($reactivationCartitemId);
    }

    //----------------LIFETIME MEMBER - PENDING UPDATES-----------------------------
    $lifeCartitemId = $this->hasProductInCart(225, 876);
    if(empty($_memberArr['maf']['main']['lifetime'])){
      if(!empty($lifeCartitemId)){
        $this->RemoveFromCart($lifeCartitemId);
      }
    }else{
      if(!empty($_memberArr['pending_update']) ){
        // Add MAF lifetime member details update
        if(empty($lifeCartitemId)){
          $this->AddToCart(225, array(), 0, 1, null, 876);
        }
      }
    }

    //check if cart has membership card, if yes then fix the quantity to one only
    $lifeCartCardId = $this->hasProductInCart($GLOBALS['CONFIG_VARS']['membership_card_product_id'], getenv('membership_card_variant_id'));
    if(!empty($lifeCartCardId)){
      $this->UpdateQtyCart(array($lifeCartCardId => '1'));
    }

    //END OF VALIDATE MAF MEMBERS

    $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, array(
        ":id" => $this->cart_id
    ))){
      foreach($res as $item){
        $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
        $DBproduct = $this->GetProductCalculation($item['cartitem_product_id'], $attrs, $item['cartitem_quantity'], $item['cartitem_product_price'], $item['cartitem_variant_id']);

        if($DBproduct['error']){
          $this->validateMsg[] = $DBproduct['error_message'];
          $this->RemoveFromCart($item['cartitem_id']);
        } else{
          if($DBproduct['product_price'] != $item['cartitem_product_price'] || $DBproduct['variant_id'] != $item['cartitem_variant_id'] || $DBproduct['product_name'] != $item['cartitem_product_name'] || $DBproduct['product_gst'] != $item['cartitem_product_gst']){
            $sql = "UPDATE tbl_cartitem SET cartitem_variant_id = :variant_id, cartitem_product_price = :price, cartitem_product_name = :product_name, cartitem_variant_name = :variant_name, cartitem_product_gst = :product_gst, cartitem_subtotal = :subtotal  WHERE cartitem_id = :id";
            $DBobject->wrappedSql($sql, array(
                ":id" => $item['cartitem_id'],
                ":price" => $DBproduct['product_price'],
                ":product_name" => $DBproduct['product_name'],
                ":variant_name" => $DBproduct['variant_name'],
                ":variant_id" => $DBproduct['variant_id'],
                ":product_gst" => $DBproduct['product_gst'],
                ":subtotal" => floatval($DBproduct['product_price']) * $item['cartitem_quantity']
            ));
            if($DBproduct['product_price'] != $item['cartitem_product_price']){
              $this->validateMsg[] = "The price of '{$DBproduct['product_name']}' has been updated. ";
            }
          }
        }
      }
    }
    return $this->validateMsg;
  }


  /**
   * Validate the given discount code and calculate the amount according to items on current cart (or given cartId).
   *
   * Limit the discount amount to subtotal value.
   * Returns array['discount']:float or array['error']:string
   *
   * @param string $code
   * @param string $cartId
   * @return array
   */
  function ApplyDiscountCode($code, $shippingFee = 0, $calculateOnly = false){
    global $DBobject, $GA_ID;

    $result = array();
    $code = strtoupper(trim($code));
    $denyHigherSubtotal = true; // CHANGE THIS ACCORDINGLY

    $subtotal = floatval($this->GetSubtotal());

    $discount = 0;

    $res = $this->GetDiscountData($code);

    if($res){
      $useValid = true;
      if($res['discount_unlimited_use'] == '0' && $res['discount_used'] >= $res['discount_fixed_time']){
        $useValid = false;
      }

      // Check discount restriction per user or usergroup
      $userRestricted = false;
      $reApplyAfterLogin = false;
      if($res['discount_usergroup_id'] > 0){

        $cartInfo = $this->GetDataCart();
        $usrLoggedIn = empty($cartInfo['cart_user_id']) ? false : true;

        //Check user group
        switch($res['discount_usergroup_id']){

          //Members only/logged in
          case 1:{
            if($usrLoggedIn){
              //Validate for user_id
              if($res['discount_user_id'] > 0 && $cartInfo['cart_user_id'] != $res['discount_user_id']){
                $userRestricted = true;
                $userRestrictionError = "Sorry but this code is non-transferable.<br>Please <a href='/contact-us' title='Contact us'>contact us</a> for more information.";
                $reApplyAfterLogin = true;
              }
            }else{
              $userRestricted = true;
              $userRestrictionError = "Authentication is required for this code.<br><a href='/login' title='Click here to log in'>Please click here to log in.</a>";
              $reApplyAfterLogin = true;
            }
            break;
          }

          //Non-members only/not logged in
          case 2:{
            if($usrLoggedIn){
              $userRestricted = true;
              $userRestrictionError = "Sorry but this offer applies to new members only";
              $reApplyAfterLogin = true;
            }
            break;
          }
          default:{
            $userRestricted = true;
            $userRestrictionError = "Undefined error, please <a href='/login' title='Contact us'>contact us</a> for more information.";
          }
        }
      }

      $today = strtotime(date("Y-m-d"));
      if($useValid && !$userRestricted && $res['discount_published'] == '1' && ((strtotime($res['discount_start_date']) <= $today && $today <= strtotime($res['discount_end_date'])) || (strtotime($res['discount_start_date']) <= $today && ($res['discount_end_date'] == '0000-00-00' || empty($res['discount_end_date']))))){
        $matchCategory = false;
        $matchProduct = false;
        // Valid code by date
        if($res['discount_listing_id'] == 0 && $res['discount_product_id'] == 0){
          $matchCategory = true;
          $matchProduct = true;
          // No filter or special code for particular category/product
          if($res['discount_amount_percentage'] == '1'){
            $discount = $subtotal * floatval($res['discount_amount']) / 100;
          } else{
            // Discount must not be higher than subtotal
            if(floatval($res['discount_amount']) > $subtotal + $shippingFee && $denyHigherSubtotal){
              $discount = $subtotal;
            } else{
              $discount = $res['discount_amount'];
            }
          }
        } else{ // With filter or special code for a category/product
          $sql = "SELECT cartitem_product_id, cartitem_quantity, cartitem_subtotal FROM tbl_cartitem
            WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0'";
          if($cartItems = $DBobject->wrappedSql($sql, array(':id' => $this->cart_id))){
            $listingMatchSubtotal = 0;
            $discount_amount_total = 0; // Added by Nijesh 1/04/2016 - to apply discount on item quantity
            foreach($cartItems as $item){
              if($res['discount_listing_id']){
                // Special code for category only
                $listingArr = $this->getProductCategoriesArr($item['cartitem_product_id']);

                $voucher_category_matches = in_array_r($res['discount_listing_id'], $listingArr);

                #include products from accessory category if this is a birthday gift voucher
                if(trim($res['discount_name'])=='Birthday voucher'){
                    $accessory_cat = '251';
                    $voucher_category_matches = $voucher_category_matches || in_array_r($accessory_cat, $listingArr);
                }
                if($voucher_category_matches){
                  $matchCategory = true;
                  if($res['discount_amount_percentage'] == '1'){
                    $discount += floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
                  } else{
                    $listingMatchSubtotal += floatval($item['cartitem_subtotal']);
                    $discount_amount_total += floatval($item['cartitem_quantity'] * $res['discount_amount']);
                  }
                }

              } elseif($res['discount_product_id'] == $item['cartitem_product_id']){
                $matchProduct = true;
                // Special code for product only
                if($res['discount_amount_percentage'] == '1'){
                  $discount = floatval($item['cartitem_subtotal']) * floatval($res['discount_amount']) / 100;
                } else{
                  // Discount must not be higher than subtotal
                  if(($res['discount_amount'] * $item['cartitem_quantity']) > $item['cartitem_subtotal']){
                    $discount = $item['cartitem_subtotal'];
                  } else{
                    $discount = $res['discount_amount'] * $item['cartitem_quantity'];
                  }
                }
              }
            }

            if($listingMatchSubtotal > 0){
              //Discount amount for entire shopping cart
              if($res['discount_amount_percentage'] == '-1'){
                $discount_amount_total = (floatval($res['discount_amount']) < $listingMatchSubtotal) ? floatval($res['discount_amount']) : $listingMatchSubtotal;
              }

              if($discount_amount_total > $listingMatchSubtotal + $shippingFee){
                $discount = $listingMatchSubtotal + $shippingFee;
              } else{
                $discount = $discount_amount_total;
              }
            }
            if(empty($discount) && (empty($res['discount_special']) || (!empty($res['discount_special']) && !$matchCategory && !$matchProduct)) && (empty($res['discount_shipping']) || (!empty($res['discount_shipping']) && !$matchCategory && !$matchProduct))){
              $result['error'] = "This code '" . $code . "' doesn't apply for the item(s) on your cart";
              if(!empty($res['discount_shipping']) || !empty($res['discount_special'])){
                $code = null;
              }
            }
          }
        }
      } else{
        $result['error'] = ($userRestricted)? $userRestrictionError : ("Sorry, this code '" . $code . "' is no longer valid. " . ($useValid? '' : 'Maximum claims reached.'));
        if($reApplyAfterLogin){
          $result['reApplyAfterLogin'] = true;
        }
        $code = null;
      }
    } else{
      $result['error'] = 'Invalid Code';
      $code = null;
    }

    $result['discount'] = round($discount, 2);

    if($calculateOnly){
      return $result;
    } else{
      $params = array(
          ":id" => $this->cart_id,
          ":discount_code" => $code,
          ":description" => (empty($code))? '' : $res['discount_description']
      );
      $sql = "UPDATE tbl_cart SET cart_discount_code = :discount_code, cart_discount_description = :description WHERE cart_id = :id";
      if($res = $DBobject->wrappedSql($sql, $params)){
        if(!empty($GA_ID)){
          sendGAEvent($GA_ID, 'discount-code', 'apply', $code);
        }
        return $result;
      }
    }

    return array(
        'error' => 'Undefined error'
    );
  }


  /**
   * function to clear applied discount code if the discount is not there
   */
  function clearDiscountCode($cartId = ''){
    global $DBobject;

    if($cartId == '')
    {
      $cartId = $this->cart_id;
    }
    $params = array(
        ":id" => $cartId,
        ":discount_code" => null,
        ":description" => null
    );
    $sql = "UPDATE tbl_cart SET cart_discount_code = :discount_code, cart_discount_description = :description WHERE cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, $params)){
      return $result;
    }
  }


  /**
   * Delete discount code
   *
   * @return boolean
   */
  function RemoveDiscountCode(){
    global $DBobject;

    $sql = "UPDATE tbl_cart SET cart_discount_code = NULL, cart_discount_description = NULL WHERE cart_discount_code IS NOT NULL AND cart_id = :id";
    return $DBobject->wrappedSql($sql, array(':id' => $this->cart_id));
  }


  /**
   * Update 'discount_used' field (and unpublish the discount when 'discount_used' value is greater or equal than 'discount_fixed_time' value)
   * given a discount code with 'discount_unlimited_use' equal zero
   * Return true if the update was made, otherwise false.
   *
   * @param string $code
   * @return boolean
   */
  function SetUsedDiscountCode($code){
    global $DBobject;

    $sql = "SELECT * FROM tbl_discount
	    			WHERE discount_code = :id AND discount_published = 1 AND discount_deleted IS NULL";
    $params = array(
        ":id" => strtoupper($code)
    );
    if($res = $DBobject->wrappedSql($sql, $params)){
      if($res[0]['discount_unlimited_use'] == '0' && $res[0]['discount_used'] >= $res[0]['discount_fixed_time']){
        $sql = "UPDATE tbl_discount SET discount_published = 0, discount_modified = now() WHERE discount_code = :id";
      } else{
        $newUsed = $res[0]['discount_used'] + 1;
        $unpublish = "";
        if($res[0]['discount_unlimited_use'] == '0' && $newUsed >= $res[0]['discount_fixed_time']){
          $unpublish = ", discount_published = 0";
        }
        $sql = "UPDATE tbl_discount SET discount_used = :used, discount_modified = now(){$unpublish} WHERE discount_code = :id";
        $params = array_merge($params, array(
            ':used' => $newUsed
        ));
      }
      if($res = $DBobject->wrappedSql($sql, $params)){
        return true;
      }
    }
    return false;
  }


  /**
   * Return a record give a code.
   *
   * @return array
   */
  function GetCurrentFreeShippingDiscountName(){
    global $DBobject;

    $sql = "SELECT discount_shipping FROM tbl_discount
	    	WHERE discount_deleted IS NULL AND discount_published = 1 AND ((discount_start_date <= CURDATE() AND discount_end_date IS NULL)  OR (CURDATE() BETWEEN discount_start_date AND discount_end_date)) AND discount_code = :id ";
    if($res = $DBobject->wrappedSql($sql, array(
        ":id" => $this->cartRecord['cart_discount_code']
    ))){
      return $res[0]['discount_shipping'];
    }
    return '';
  }


  /**
   * Return a record give a code.
   *
   * @return array
   */
  function GetDiscountData($code){
    global $DBobject;

    if(empty($code)){
      return array();
    }
    $sql = "SELECT *  FROM tbl_discount WHERE discount_code = :id AND discount_deleted IS NULL LIMIT 1";
    $res = $DBobject->wrappedSql($sql, array(
        ":id" => $code
    ));
    return $res[0];
  }


  /**
   * Insert a new discount code
   *
   * @param array $_data
   * @return int
   */
  function CreateDiscountCode($_data){
    global $DBobject;

    if(empty($_data['code']) || empty($_data['name']) || empty($_data['start_date'])){
      return 0;
    }
    $params = array(
        ":discount_code" => $_data['code'],
        ":discount_name" => $_data['name'],
        ":discount_description" => $_data['description'],
        ":discount_amount" => (empty($_data['amount'])? 0 : $_data['amount']),
        ":discount_amount_percentage" => (empty($_data['isPercentage'])? 0 : $_data['isPercentage']),
        ":discount_listing_id" => (empty($_data['listing_id'])? 0 : $_data['listing_id']),
        ":discount_product_id" => (empty($_data['product_id'])? 0 : $_data['product_id']),
        ":discount_usergroup_id" => (empty($_data['usergroup_id'])? 0 : $_data['usergroup_id']),
        ":discount_user_id" => (empty($_data['user_id'])? 0 : $_data['user_id']),
        ":discount_shipping" => $_data['shipping'],
        ":discount_start_date" => $_data['start_date'],
        ":discount_end_date" => (empty($_data['end_date'])? null : $_data['end_date']),
        ":discount_unlimited_use" => (empty($_data['isUnlimited'])? 0 : 1),
        ":discount_fixed_time" => (empty($_data['isUnlimited'] && !empty($_data['fixed_time']))? $_data['fixed_time'] : 0),
        ":discount_published" => (empty($_data['isPublished'])? 0 : 1),
        ":discount_membership_system_mapped_code" => (empty($_data['membership_system_mapped_code'])? '' : $_data['membership_system_mapped_code'])
    );

    $sql = "INSERT INTO tbl_discount (discount_code, discount_name, discount_description, discount_amount, discount_amount_percentage, discount_listing_id,
	      discount_product_id, discount_usergroup_id, discount_user_id, discount_shipping, discount_start_date, discount_end_date, discount_unlimited_use, discount_fixed_time, discount_published, discount_created, discount_membership_system_mapped_code)
			values (:discount_code, :discount_name, :discount_description, :discount_amount, :discount_amount_percentage, :discount_listing_id,
	      :discount_product_id, :discount_usergroup_id, :discount_user_id, :discount_shipping, :discount_start_date, :discount_end_date, :discount_unlimited_use, :discount_fixed_time, :discount_published, :discount_membership_system_mapped_code, NOW())";
    if($DBobject->wrappedSql($sql, $params)){
      return $DBobject->wrappedSqlIdentity();
    }
    return 0;
  }


  /**
   * Calculate and return shipping fee
   *
   * @param int $cartId
   * @return float
   */
  function CalculateShippingFee($method = null, $cartId = null){
    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    // DEPENDS ON CLIENT
    return 15;
  }


  /**
   *
   * Return a string with listing_obj_ids(categories) parents' name from a given product_object_id
   *
   * @param int $productObjId
   * @return array
   */
  function getProductCategoriesArr($productObjId){
    global $DBobject;

    $result = array();

    $sql = "SELECT productcat_listing_id FROM tbl_product
      LEFT JOIN tbl_productcat ON product_id = productcat_product_id
      LEFT JOIN tbl_listing ON productcat_listing_id = listing_object_id
      WHERE product_deleted IS NULL AND productcat_deleted IS NULL AND product_published = 1
      AND listing_deleted IS NULL AND listing_published = 1 AND listing_type_id = 10
      AND product_object_id = :id
      GROUP BY listing_object_id";
    if($res = $DBobject->wrappedSql($sql, array(':id' => $productObjId))){
      foreach($res as $r){
        $result[] = $r['productcat_listing_id'];
      }
    }
    return $result;
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
  function getProductInfo_GA($productId, $AttributesArr, $quantity = 0, $parentId = 0, $coupon = null, $position = 0, $variantId = 0){
    global $DBobject;

    $result = array();
    $product = $this->GetProductCalculation($productId, $AttributesArr, $quantity, 0, $variantId);

    if(!$product['error']){

      $variant = array();
      foreach($product['attributes'] as $a){
        $variant[] = $a['attr_value_name'];
      }

      $result = array(
          'id' => (empty($product['variant_uid']) ? "{$product['product_object_id']}-{$product['variant_id']}" : $product['variant_uid']),
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
   * This return empty at the moment because a product could be in multiple categories
   *
   * @param int $productObjId
   * @return string
   */
  function getFullCategoryName($productObjId){
    return 'products';
  }


  /**
   * Return array with product details given a cartitem_id for Google Analytics - Enhanced ecommerce.
   *
   * @param int $cartItemId
   * @return array
   */
  function getProductInfoByCartItem_GA($cartItemId){
    global $DBobject;

    $result = array();
    $param = array(
        ":id" => $cartItemId
    );
    $sql = "SELECT tbl_cartitem.*, product_brand, variant_id, variant_uid
        FROM tbl_cartitem LEFT JOIN tbl_product ON product_object_id = cartitem_product_id
        LEFT JOIN tbl_variant ON variant_id = cartitem_variant_id
  			WHERE product_deleted IS NULL AND product_published = 1 AND variant_deleted IS NULL AND cartitem_id = :id";
    if($res = $DBobject->wrappedSql($sql, $param)){

      $variant = array();
      $sql = "SELECT cartitem_attr_attr_value_name FROM tbl_cartitem_attr
					WHERE cartitem_attr_cartitem_id	= :id AND cartitem_attr_deleted IS NULL";

      if($res2 = $DBobject->wrappedSql($sql, $param)){
        foreach($res2 as $a){
          $variant[] = $a['cartitem_attr_attr_value_name'];
        }
      }

      $result = array(
          'id' => (empty($res[0]['variant_uid']) ? "{$res[0]['cartitem_product_id']}-{$res[0]['variant_uid']}" : $res[0]['variant_uid']),
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
   *
   * @param int $cartId
   * @return string
   */
  function getJSCartitemsByCartId_GA($cartId = null){
    global $DBobject;

    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    $result = '';
    $param = array(
        ":id" => $cartId
    );
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, $param)){
      foreach($res as $r){
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
   *
   * @param int $cartId
   * @return string
   */
  function getCartitemsByCartId_GA($cartId = null){
    global $DBobject;

    if(is_null($cartId)){
      $cartId = $this->cart_id;
    }
    $result = array();
    $param = array(
        ":id" => $cartId
    );
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, $param)){
      foreach($res as $r){
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
  function GetCartWeight($cartId = null){
    global $DBobject;

    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    $result = 0;
    $sql = "SELECT * FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id";
    $params = array(
        ":id" => $cartId
    );
    if($res = $DBobject->wrappedSql($sql, $params)){
      foreach($res as $item){
        $attrs = $this->GetAttributesIdsOnCartitem($item['cartitem_id']);
        $DBproduct = $this->GetProductCalculation($item['cartitem_product_id'], $attrs, $item['cartitem_quantity'], $item['cartitem_product_price'], $item['cartitem_variant_id']);
        if(!empty($DBproduct['product_weight'])){
          $result += floatval($DBproduct['product_weight']) * $item['cartitem_quantity'];
        }
      }
    }
    return $result;
  }


  /**
   * Checks whether or not the cart has a given product_id and or variant_id
   *
   * @param int $_productId
   * @param int $_variant_id
   * @return boolean
   */
  function hasProductInCart($_productId, $_variant_id = 0){
    global $DBobject;

    $params = array(
        ':id' => $this->cart_id,
        ':pid' => $_productId
    );
    $vstr = '';
    if(!empty($_variant_id)){
      $params[':vid'] = $_variant_id;
      $vstr = 'AND cartitem_variant_id = :vid';
    }
    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id != 0 AND cartitem_cart_id = :id AND cartitem_product_id = :pid {$vstr}";
    if($res = $DBobject->wrappedSql($sql, $params)){
      return $res[0]['cartitem_id'];
    }
    return false;
  }


  /**
   * Checks whether or not the cart has a given category (listing_object_id)
   *
   * @param int $oid
   * @return boolean
   */
  function HasCategory($oid){
    global $DBobject;

    $params = array(
        ':cid' => $this->cart_id,
        ':oid' => $oid
    );

    $sql = "SELECT cartitem_id FROM tbl_cartitem
        LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
        LEFT JOIN tbl_productcat ON productcat_product_id = product_id
        WHERE product_deleted IS NULL AND product_published = 1 AND productcat_deleted IS NULL
        AND cartitem_deleted IS NULL AND cartitem_cart_id = :cid AND productcat_listing_id = :oid";
    if($res = $DBobject->wrappedSql($sql, $params)){
      return true;
    }
    return false;
  }


  /**
   * Return wish list - array with product_object_id
   *
   * @return array
   */
  function GetWishList(){
    global $DBobject;

    $resArr = array();
    $sql = "SELECT wishlist_product_object_id FROM tbl_wishlist WHERE wishlist_deleted IS NULL AND wishlist_user_id = :wishlist_user_id ORDER BY wishlist_modified DESC";
    if($res = $DBobject->wrappedSql($sql, array(
        ':wishlist_user_id' => $this->cart_user_id
    ))){
      foreach($res as $r){
        $resArr[] = $r['wishlist_product_object_id'];
      }
    }
    return $resArr;
  }


  /**
   * Return wish list with product details
   *
   * @return array
   */
  function GetWishListWithProds(){
    global $DBobject;

    $sql = "SELECT tbl_product.*, tbl_gallery.*, wishlist_modified FROM tbl_wishlist
        LEFT JOIN tbl_product ON product_object_id = wishlist_product_object_id
        LEFT JOIN tbl_gallery ON gallery_product_id = product_id
        WHERE wishlist_deleted IS NULL AND product_deleted IS NULL AND product_published = 1
        AND gallery_deleted IS NULL AND wishlist_user_id = :wishlist_user_id
        GROUP BY wishlist_product_object_id
        ORDER BY wishlist_modified DESC";
    return $DBobject->wrappedSql($sql, array(':wishlist_user_id' => $this->cart_user_id));
  }


  /**
   * Add product to wish list
   *
   * @param int $_productObjId
   * @param string $_cookie
   *          [optional]
   * @return int
   */
  function AddProductWishList($_productObjId, $_cookie = ''){
    global $DBobject;

    if((!empty($this->cart_user_id) || !empty($_cookie)) && !empty($_productObjId)){
      $params1 = array(
          ":kid" => $this->cart_user_id,
          ":pid" => $_productObjId
      );
      $whereSql = 'AND wishlist_user_id = :kid';
      if(empty($this->cart_user_id)){
        $params1[':kid'] = $_cookie;
        $whereSql = 'AND wishlist_cookie = :kid';
      }

      $sql = "SELECT wishlist_id FROM tbl_wishlist WHERE wishlist_deleted IS NULL AND wishlist_product_object_id = :pid {$whereSql}";
      if($res = $DBobject->wrappedSql($sql, $params1)){
        return $res[0]['wishlist_id'];
      }

      $params2 = array(
          ":uid" => $this->cart_user_id,
          ":pid" => $_productObjId,
          ":cid" => $_cookie
      );
      $sql = "INSERT INTO tbl_wishlist (wishlist_user_id, wishlist_product_object_id, wishlist_cookie, wishlist_created)
							VALUES (:uid, :pid, :cid, NOW())";
      if($DBobject->wrappedSql($sql, $params2)){
        return $DBobject->wrappedSqlIdentity();
      }
    }
    throw new exceptionCart("You cannot add this product to your wish list.");
    return 0;
  }


  /**
   * Delete a product from wishlist
   *
   * @param int $_productObjId
   * @param string $_cookie
   *          [optional]
   * @return boo
   */
  function DeleteProductWishList($_productObjId, $_cookie = ''){
    global $DBobject;

    if((!empty($this->cart_user_id) || !empty($_cookie)) && !empty($_productObjId)){
      $params = array(
          ":kid" => $this->cart_user_id,
          ":pid" => $_productObjId
      );
      $whereSql = 'AND wishlist_user_id = :kid';
      if(empty($this->cart_user_id)){
        $params[':kid'] = $_cookie;
        $whereSql = 'AND wishlist_cookie = :kid';
      }

      $sql = "UPDATE tbl_wishlist SET wishlist_deleted = NOW()
      WHERE wishlist_deleted IS NULL AND wishlist_product_object_id = :pid {$whereSql}";
      if($res = $DBobject->wrappedSql($sql, $params)){
        return true;
      }
    }
    throw new exceptionCart("You cannot remove this product from your wish list.");
    return false;
  }


  /**
   * Get the number of items in the current cart based on product type id
   *
   * @return int
   */
  function GetProductTypeCountInCart($typeId){
    global $DBobject;

    $params = array(
        ':id' => $this->cart_id,
        ':pid' => $typeId
    );

    $sql = "SELECT SUM(cartitem_quantity) AS 'QTY' FROM tbl_cartitem
        LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
        WHERE product_deleted IS NULL AND product_published = 1 AND product_type_id = :pid
        AND cartitem_deleted IS NULL AND cartitem_cart_id = :id";

    $res = $DBobject->wrappedSql($sql, $params);
    return $res[0]['QTY'];
  }

  /**
   * ONLY FOR MAF
   * Return true when the cart has MAF product, in other words not only donations and/or gift certificates
   *
   * @return boolean
   */
  function HasMAFProducts($cartId = null){
    global $DBobject;

    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    $params = array(
        ":id" => $cartId
    );

    // gift certificates and/or donations product_object_id
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

  /**
   * check if cart only has the membership fee in the cart
   *
   * @return array of cart item ids or FALSE if no result
   */
  function HasMSFProduct($cartId = null){
    global $DBobject;

    if(empty($cartId)){
      $cartId = $this->cart_id;
    }
    $params = array(
        ":id" => $cartId
    );

    $sql = "SELECT cartitem_id FROM tbl_cartitem WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> 0 AND cartitem_product_id = 225 AND cartitem_cart_id = :id";
    if($res = $DBobject->wrappedSql($sql, $params)){
      return $res;
    }
    return false;
  }

  /**
   * ONLY FOR MAF
   * Return current year "Member Service Fee" record given the product_object_id (defaul value: 225)
   *
   * @return boolean
   */
  function GetCurrentMAF_MSF($_productId = 225, $_year = ''){
    global $DBobject;

    $curYear = empty($_year) ? date('Y') : $_year;

    $sql = "SELECT * FROM tbl_product LEFT JOIN tbl_variant ON variant_product_id = product_id
        WHERE product_deleted IS NULL AND product_published = 1 AND variant_deleted IS NULL AND variant_published = 1 AND variant_name LIKE :thisyear AND product_object_id = :id";
    if($res = $DBobject->wrappedSql($sql, array(
        ':id' => $_productId,
        ':thisyear' => '%' . $curYear . '%'
    ))){
      return $res[0];
    }
    return false;
  }


  /**
   * ONLY FOR MAF
   * Remove all non membership fee service cartitems from the current cart
   *
   * @return boolean
   */
  function RemoveNonMembershipFeeCartitems(){
    global $DBobject;

    $params = array(
        ":id" => $this->cart_id
    );

    // Member Service Fee - product object id
    $whereSQL = "AND cartitem_product_id != 225";

    $sql = "UPDATE tbl_cartitem SET cartitem_deleted = NOW() WHERE cartitem_deleted IS NULL AND cartitem_cart_id <> '0' AND cartitem_cart_id = :id {$whereSQL}";
    if($res = $DBobject->wrappedSql($sql, $params)){
      return true;
    }
    return false;
  }


  /**
   * ONLY FOR MAF
   * Check if cart has any stainless steel product
   *
   * @return float
   */
  function HasStainlessSteel(){
    global $DBobject;

    // Stainless steel - tbl_pmaterial - pmateriallink_record_id = pmaterial_id = 1

    $sql = "SELECT cartitem_id FROM tbl_cartitem
        LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
        LEFT JOIN tbl_pmateriallink ON pmateriallink_product_id = product_id
        WHERE product_deleted IS NULL AND product_published = 1 AND pmateriallink_deleted IS NULL AND pmateriallink_record_id = 1
        AND cartitem_deleted IS NULL  AND cartitem_cart_id = :id ";
    if($res = $DBobject->wrappedSql($sql, array(
        ':id' => $this->cart_id
    ))){
      return true;
    }
    return false;
  }


  /**
   * ONLY FOR MAF
   * Discount amount - Get a second stainless steel product for $35
   *
   * @return float
   */
  function GetStainlessSteelDiscount(){
    global $DBobject;

    $amount = 0;
    $params = array(
        ':id' => $this->cart_id
    );


      // Stainless steel - tbl_pmaterial - pmateriallink_record_id = pmaterial_id = 1
      // updated on 10/04/2018
      // check if there are two items for value more than 35
      $sql = "SELECT SUM(cartitem_quantity) AS 'QTY' FROM tbl_cartitem
        LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
        WHERE product_deleted IS NULL AND product_published = 1 AND product_type_id = :pid
        AND cartitem_deleted IS NULL AND cartitem_cart_id = :id AND cartitem_product_price >= 35";
      $chk_params = array(
          ':id' => $this->cart_id,
          ':pid' => '1'
      );
      $chk_qty = $DBobject->wrappedSql($sql, $chk_params);

      if((int)$chk_qty[0]['QTY'] > 1){
        $sql = "SELECT MIN(cartitem_product_price) AS 'AMOUNT' FROM tbl_cartitem
        LEFT JOIN tbl_product ON cartitem_product_id = product_object_id
        LEFT JOIN tbl_pmateriallink ON pmateriallink_product_id = product_id
        WHERE product_deleted IS NULL AND cartitem_product_price >= 35 AND product_published = 1 AND pmateriallink_deleted IS NULL AND pmateriallink_record_id = 1
        AND cartitem_deleted IS NULL AND cartitem_cart_id = :id";

        if($res = $DBobject->wrappedSql($sql, $params)){
          $amount = round(floatval($res[0]['AMOUNT']) - 35, 2);
          $amount = ($amount > 0) ? $amount : 0;
        }
      }
    return $amount;
  }


  /**
   * ONLY FOR MAF
   * Discount amount - BUPA 2 years membership + selected product for $100
   *
   * @return float
   */
  function GetBupaDiscount($shippingFee = 0){
    global $DBobject;

    $discount = 0;
    $prodAmount = 0;
    $amount = 100;
    //Update time 01/01/2017, 12:00:00 AM (1483191000)
    if(time() > 1483191000){
      $amount = 125;
    }
    if(empty($this->cart_user_id)){

      //Check for valid product - Bupa member collection - listing_object_id = 667
      $sql = "SELECT cartitem_product_id, cartitem_quantity, cartitem_subtotal, cartitem_product_price FROM tbl_cartitem
            WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0' ORDER BY cartitem_product_price";
      if($cartItems = $DBobject->wrappedSql($sql, array(':id' => $this->cart_id))){
        foreach($cartItems as $item){
          $collectionArr = $this->getProductCategoriesArr($item['cartitem_product_id']);
          if(in_array(667, $collectionArr)){
            $prodAmount = floatval($item['cartitem_product_price']);
            break;
          }
        }
      }
      if($prodAmount > 0){
        //Shipping fee
        $prodAmount += $shippingFee;

        // Add MAF membership fee - current year
        $msfArr = $this->GetCurrentMAF_MSF(225);
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        if(empty($membershipFeeCartitemId)){
          $this->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
        }
        $prodAmount += floatval($msfArr['variant_price']);

        // Add MAF membership fee - next year
        $msfArr = $this->GetCurrentMAF_MSF(225, date('Y', strtotime('+1 year')));
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        if(empty($membershipFeeCartitemId)){
          $this->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
        }
        $prodAmount += floatval($msfArr['variant_price']);

        $discount = $prodAmount - $amount;

      }else{
        //remove MAF membership fee - next year
        $msfArr = $this->GetCurrentMAF_MSF(225, date('Y', strtotime('+1 year')));
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        $this->RemoveFromCart($membershipFeeCartitemId);
      }
    }
    $discount = ($discount > 0) ? $discount : 0;
    return round($discount, 2);
  }


  /**
   * ONLY FOR MAF
   * Discount amount - AUTISM 1 year membership + selected product for $80
   *
   * @return float
   */
  function GetAutismDiscount($shippingFee = 0){
    global $DBobject;

    $discount = 0;
    $prodAmount = 0;
    if(empty($this->cart_user_id)){

      //Check for valid product - Exclusive Autism collection - listing_object_id = 820
      $sql = "SELECT cartitem_product_id, cartitem_quantity, cartitem_subtotal, cartitem_product_price FROM tbl_cartitem
            WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0' ORDER BY cartitem_product_price";
      if($cartItems = $DBobject->wrappedSql($sql, array(':id' => $this->cart_id))){
        foreach($cartItems as $item){
          $collectionArr = $this->getProductCategoriesArr($item['cartitem_product_id']);
          if(in_array(820, $collectionArr)){
            $prodAmount = floatval($item['cartitem_product_price']);
            break;
          }
        }
      }
      if($prodAmount > 0){
        //Shipping fee
        $prodAmount += $shippingFee;

        // Add MAF membership fee - current year
        $msfArr = $this->GetCurrentMAF_MSF(225);
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        if(empty($membershipFeeCartitemId)){
          $this->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
        }
        $prodAmount += floatval($msfArr['variant_price']);

        $discount = $prodAmount - 80;
      }
    }
    $discount = ($discount > 0) ? $discount : 0;
    return round($discount, 2);
  }


  /**
   * ONLY FOR MAF
   * Discount amount - BENEVOLENT 1 year membership + selected product + shipping FOR FREE
   *
   * @return float
   */
  function GetBenevolentDiscount($shippingFee = 0){
    global $DBobject;

    $discount = 0;
    $prodAmount = 0;
    if(empty($this->cart_user_id)){

      //Check for valid product - Exclusive Benevolent collection - listing_object_id = 974 (LIVE) - 788 (DEV)
      $validCatArr = array(788, 974);

      $sql = "SELECT cartitem_product_id, cartitem_quantity, cartitem_subtotal, cartitem_product_price FROM tbl_cartitem
            WHERE cartitem_cart_id = :id AND cartitem_deleted IS NULL AND cartitem_cart_id <> '0' ORDER BY cartitem_product_price";
      if($cartItems = $DBobject->wrappedSql($sql, array(':id' => $this->cart_id))){
        foreach($cartItems as $item){
          $collectionArr = $this->getProductCategoriesArr($item['cartitem_product_id']);
          if(count(array_intersect ($validCatArr, $collectionArr)) > 0){
            $prodAmount = floatval($item['cartitem_product_price']);
            break;
          }
        }
      }
      if($prodAmount > 0){
        //Shipping fee
        //$prodAmount += $shippingFee; //Already set via CMS

        // Add MAF membership fee - current year
        $msfArr = $this->GetCurrentMAF_MSF(225);
        $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
        if(empty($membershipFeeCartitemId)){
          $this->AddToCart($msfArr['product_object_id'], array(), 0, 1, null, $msfArr['variant_id']);
        }
        $prodAmount += floatval($msfArr['variant_price']);

        $discount = $prodAmount;
      }
    }
    $discount = ($discount > 0) ? $discount : 0;
    return round($discount, 2);
  }


  /**
   * ONLY FOR MAF
   * Discount amount - Annual membership discount for the current & previous year
   *
   * @param float $percentage
   * @return float
   */
  function GetMSFDiscount($percentage = 0){
    global $DBobject;

    $discount = 0;

    //MAF membership fee - last year
    $msfArr = $this->GetCurrentMAF_MSF(225, date('Y', strtotime('-1 year')));

    $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
    if(!empty($membershipFeeCartitemId)){
      $discount +=  floatval($msfArr['variant_price']) * $percentage / 100;
    }

    //MAF membership fee - current year
    $msfArr = $this->GetCurrentMAF_MSF(225, date('Y'));

    $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
    if(!empty($membershipFeeCartitemId)){
      $discount +=  floatval($msfArr['variant_price']) * $percentage / 100;
    }

    //MAF membership fee - next year
    $msfArr = $this->GetCurrentMAF_MSF(225, date('Y', strtotime('+1 year')));

    $membershipFeeCartitemId = $this->hasProductInCart($msfArr['product_object_id'], $msfArr['variant_id']);
    if(!empty($membershipFeeCartitemId)){
      $discount +=  floatval($msfArr['variant_price']) * $percentage / 100;
    }

    return round($discount, 2);
  }


  /**
   * ONLY FOR MAF
   * Check if the member has already paid the membership fee for the last 18 months
   *
   * @param integer $membershipId
   * @return boolean
   */
  protected function HasPaidMSF($membershipId, $productId, $variantId){
    global $DBobject;

    if(!empty($membershipId)){
      $params = array(
          ':uid' => $membershipId,
          ':pid' => $productId,
          ':vid' => $variantId
      );
      $sql = "SELECT cartitem_id FROM tbl_payment LEFT JOIN tbl_cartitem ON payment_cart_id = cartitem_cart_id
          WHERE payment_deleted IS NULL AND payment_status = 'A' AND payment_user_id = :uid
          AND cartitem_deleted IS NULL AND cartitem_product_id = :pid AND cartitem_variant_id = :vid AND payment_created > (NOW() - INTERVAL 18 MONTH)";
      if($res = $DBobject->wrappedSql($sql, $params)){
        return true;
      }
    }
    return false;
  }

  /**
   * return the variant name of the cart item in the cart
   *
   * @param integer $cartItemId
   * @return string variant name
   */
  function getCartItemVariantName($cartItemId = null){
      global $DBobject;

      if(empty($cartItemId)){
        return false;
      }

      $params = array(
          ':cid' => $cartItemId
      );
      $sql = "SELECT cartitem_variant_name FROM tbl_cartitem WHERE cartitem_id = :cid AND cartitem_deleted IS NULL";
      if($res = $DBobject->wrappedSql($sql, $params)){
        return $res[0]['cartitem_variant_name'];
      }
      return false;
  }

  function getApplyBestDiscount() {
    $validForSeniors = '';
    $validForStainless = '';

    // check if cart is valid for seniors discount
    $dob = '';
    $seniorsCard = '';
    $age = 0;

    //New member
    if(!empty($_SESSION['user']['new_user']) && empty($_SESSION['user']['public']['id'])){
      $dob = $_SESSION['user']['new_user']['db_dob'];
      $seniorsCard = $_SESSION['user']['new_user']['seniorscard'];
    }

    //Existing member - approved details
    if(!empty($_SESSION['user']['public']['maf']['update'])){
      $dob = $_SESSION['user']['public']['maf']['update']['user_dob'];
      $seniorsCard = $_SESSION['user']['public']['maf']['update']['attributes'][18];
    }

    //Existing member - pending details
    if(!empty($_SESSION['user']['public']['maf']['pending'])){
      $dob = $_SESSION['user']['public']['maf']['pending']['user_dob'];
      $seniorsCard = $_SESSION['user']['public']['maf']['pending']['attributes'][18];
    }

    if(!empty($dob)){
      //Calculate date difference between renewal date and today
      $dob = date_create_from_format('Y-m-d', $dob);
      $today = new DateTime();
      $interval = $dob->diff($today);
      $age = floatval($interval->y);
    }

    if($age >= 60 && !empty($seniorsCard)){
      $validForSeniors = true;
      $chkSeniorsDiscount = $this->ApplyDiscountCode('SENIORS', 0, true);
    }

    $chkStainlessDiscount = $this->GetStainlessSteelDiscount();
    if((float)$chkStainlessDiscount > 0){
      $validForStainless = true;
    }

    if($validForSeniors && $validForStainless){
      //echo $chkSeniorsDiscount['discount'].'----'.$chkStainlessDiscount;
      if((float)$chkSeniorsDiscount['discount'] > (float)$chkStainlessDiscount){
        $this->ApplyDiscountCode('SENIORS');
        return true;
      } else{
        $this->ApplyDiscountCode('STAINLESS-STEEL');
        return true;
      }
    } elseif($validForSeniors){
      $this->ApplyDiscountCode('SENIORS');
      return true;
    } elseif($validForStainless){
      $this->ApplyDiscountCode('STAINLESS-STEEL');
      return true;
    }
    $cart = $this->GetDataCart($this->cart_id);
    if(!empty($cart['cart_discount_code'])){
      $this->ApplyDiscountCode('');
    }
    return false;

  }

}
