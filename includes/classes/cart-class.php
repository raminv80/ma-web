<?php
class cart{

    public $cart_id;
    public $created_date;
    public $closed_date;
    public $cart_session;

    public $postage;

    private $ses_cart_id;
    private $user_cart_id;

    function __construct(){

    	
    	if($this->VerifySessionCart(session_id())){	
    		$this->cart_id = $this->ses_cart_id;	//do nothing since session cart exists and user is not logged in
    	}else{										
    		$this->CreateCart();
    	}	
    		
    		
        /* $this->postage = 6; 

        if($this->VerifySessionCart(session_id())){	// OPENED SESSION-CART FOUND
            if(isset($_SESSION['user'])){	// USER LOGGED
                if($this->VerifyUserCart($_SESSION['user']['user_id'])){ 	// OPENED USER-CART FOUND
                    if($this->ses_cart_id != $this->user_cart_id){ 
                        $this->CreateCart();
                        $this->CopyCart2Cart([$this->user_cart_id, $this->ses_cart_id ], $this->cart_id); 
                        $this->DeleteCart($this->user_cart_id);
                        $this->CopyCart2Cart($this->ses_cart_id, $this->cart_id);
                        $this->DeleteCart($this->ses_cart_id);
                        // merge opened user-cart and current-session-cart to new cart
                    }else{
                        $this->cart_id = $this->ses_cart_id;
                        //do nothing since the session cart exists and is the current user cart too.
                    }
                }else{	// NO OPENED USER-CART FOUND
                    $this->CreateCart();
                    $this->CopyCart2Cart($this->ses_cart_id, $this->cart_id);
                    $this->DeleteCart($this->ses_cart_id);
                }
            }else{	// USER NOT LOGGED
                $this->cart_id = $this->ses_cart_id;
                //do nothing since session cart exists and user is not logged in
            }
        }else{	// NO OPENED SESSION-CART FOUND
            if(isset($_SESSION['user'])){	// USER LOGGED
                if($this->VerifyUserCart($_SESSION['user']['user_id'])){ // OPENED USER-CART FOUND
                    $this->CreateCart();
                    $this->CopyCart2Cart($this->user_cart_id, $this->cart_id); 
                    $this->DeleteCart($this->user_cart_id); 
                }else{ // NO OPENED USER-CART FOUND
                    $this->CreateCart();
                }
            }else{	// USER NOT LOGGED
                $this->CreateCart();
            }
        }*/

    }

    
 /*    function GetPostage(){
        if($this->CartContainsProducts()){
            return $this->postage;
        }else{
            return 0;
        }
    } */

    /**
     *
     * Takes a Session_id value and checks if a cart exists in the database for this session.
     * Returns True if exists, else returns false.
     * @param unknown_type $ses_val
     * @return boolean
     */
    function VerifySessionCart($ses_val){
        global $DBobject;
    	
    	$this->ses_cart_id = $DBobject->wrappedSqlGetSingle("SELECT cart_id FROM tbl_cart
											WHERE cart_closed_date is null
											AND cart_deleted is null
											AND cart_session = '".clean($ses_val)."'
											ORDER BY cart_id DESC");
        if($this->ses_cart_id	!= 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     *
     * Takes a User_id value and checks if a cart exists in the database for this User.
     * Returns True if exists, else returns false.
     * @param unknown_type $user_val
     * @return boolean
     */
    function VerifyUserCart($user_val){
        global $DBobject;
    	
    	$res = $DBobject->wrappedSql("SELECT cart_id, cart_session FROM tbl_cart
											WHERE cart_closed_date is null
											AND cart_deleted is null
											AND cart_user_id = '".clean($user_val)."'
											ORDER BY cart_id DESC");
        if($res){
        	$this->cart_session = $res[0]['cart_session'];
        	session_id($this->cart_session);
        	$this->user_cart_id = $res[0]['cart_id'];
            return true;
        }else{
            return false;
        }
    }

    function  CreateCart(){
        global $DBobject;
    	
    	$this->cart_session = session_id();						//current session id
    	if(isset($_SESSION['user'])){
        	$sql = " INSERT INTO tbl_cart
						(cart_created,cart_session,cart_user_id)
						VALUES (now(),'".clean($this->cart_session)."','".clean($_SESSION['user']['user_id'])."')";
            $res = $DBobject->wrappedSqlInsert($sql);
            $this->cart_id = $DBobject->wrappedSqlIdentity();
        }else{
        	$sql = " INSERT INTO tbl_cart
						(cart_created,cart_session)
						VALUES (now(),'".clean($this->cart_session)."')";
            $res = $DBobject->wrappedSqlInsert($sql);
            $this->cart_id = $DBobject->wrappedSqlIdentity();
        }
    }

    function CopyCart2Cart($origin, $destination){
        global $DBobject;
    	
    	if($origin != 0 && $destination != 0){
            $orig_cart = $DBobject->wrappedSql("SELECT * FROM tbl_cart
							WHERE cart_closed_date is null AND cart_deleted is null	AND cart_id = " . $origin);
            if($orig_cart[0]){
                $DBobject->wrappedSql("UPDATE tbl_cart SET cart_created = '".$orig_cart[0]['cart_created']."',cart_discount_code = '".$orig_cart[0]['cart_discount_code']."' WHERE cart_id = '".$destination."'");
            }

            $orig_items = $DBobject->wrappedSql("SELECT * FROM tbl_cartitem
							WHERE cartitem_deleted is null	AND cartitem_cart_id = " . $origin);
            
            if($orig_items){
                foreach($orig_items as $item){
                    $DBobject->wrappedSql("INSERT INTO tbl_cartitem (
    									cartitem_cart_id,
    									cartitem_product_id,
    									cartitem_product_name,
    									cartitem_product_price,
						    			cartitem_product_gst,
    									cartitem_quantity,
										cartitem_user_ip,
										cartitem_user_browser,
    									cartitem_created
    								)
    								values(
    									'".($destination)."',
    									'".($item['cartitem_product_id'])."',
    									'".($item['cartitem_product_name'])."',
    									'".($item['cartitem_product_price'])."',
    									'".($item['cartitem_product_gst'])."',
    									'".($item['cartitem_quantity'])."',
    									'".($item['cartitem_user_ip'])."',
    									'".($item['cartitem_user_browser'])."',
    									'".($item['cartitem_created'])."',
    									)");
                    
                    $dest_cartitemId = $DBobject->wrappedSqlIdentity();
                    
            		$orig_itemsAttr = $DBobject->wrappedSql("SELECT * FROM tbl_cartitem_attr
							WHERE cartitem_attr_deleted is null	AND cartitem_attr_cartitem_id = " . $item['cartitem_id']);
                    
            		if ($orig_itemsAttr) {
	                    foreach ($orig_itemsAttr as $itemAttr){
	                    	$DBobject->wrappedSql("INSERT INTO tbl_cartitem_attr (
							    						cartitem_attr_cartitem_id,
	                    								cartitem_attr_attribute_id,
	    												cartitem_attr_attr_value_id,
							    						cartitem_attr_attribute_name,
							    						cartitem_attr_attr_value_name,
	    												cartitem_attr_created
			    								)
			    								values(
			    									'".$dest_cartitemId."',
			    									'".($item['cartitem_attr_attribute_id'])."',
			    									'".($item['cartitem_attr_attr_value_id'])."',
			    									'".($item['cartitem_attr_attribute_name'])."',
			    									'".($item['cartitem_attr_attr_value_name'])."',
			    									'".($item['cartitem_attr_created'])."',
			    									)");
	                    }
            		} 
                }
            }
        }
    }

    function CloseCart($cart_id){
        global $DBobject;
    	
        return $DBobject->wrappedSql("UPDATE tbl_cart SET cart_closed_date = now() WHERE cart_id = '".$cart_id."'");
    }

    function DeleteCart($cart_id){
        global $DBobject;
    	
        return $DBobject->wrappedSql("UPDATE tbl_cart SET cart_deleted = now() WHERE cart_id = '".$cart_id."'");
    }
    
    
	/**
	 * Find the cartitem_id whether there is a product on the cart, when there isn't returns 0  
	 * @param integer $product_id
	 * @param array $attributesArray
	 * @return integer
	 */
    function ProductOnCart($product_id,$attributesArray){
        global $DBobject;
    	
    	$res = $DBobject->wrappedSql("SELECT cartitem_id FROM tbl_cartitem
							WHERE cartitem_cart_id = '".clean($this->cart_id)."'
							AND	cartitem_product_id = '".clean($product_id)."'
							AND cartitem_deleted is null
							AND cartitem_cart_id <> '0'"); 
    	
        if($res){
        	
        	$res2 = $DBobject->wrappedSql("SELECT cartitem_attr_attribute_id, cartitem_attr_attr_value_id FROM tbl_cartitem_attr
							WHERE cartitem_attr_cartitem_id = '".clean($res[0]['cartitem_id'])."'
							AND cartitem_attr_deleted is null");
        	if ($res2) {
        		$dbAttr = array();
        		foreach ($res2 as $attr){
        			$dbAttr[][$attr['cartitem_attr_attribute_id']] = $attr['cartitem_attr_attr_value_id'];
        		}
        		if (count(array_diff($attributesArray, $dbAttr)) === 0) {
        			return $res[0]['cartitem_id']; // Item found 
        		}
        	} 
        }
        return 0;
    }
    function StyleOnCart($product_style_id){
    	global $DBobject;
    	
    	$res = $DBobject->wrappedSqlGetSingle("
							SELECT tbl_cartitem.cartitem_id
								FROM tbl_cartitem
								  LEFT JOIN tbl_product
								    ON tbl_cartitem.cartitem_product_id = tbl_product.product_id
								WHERE cartitem_cart_id = '".clean($this->cart_id)."' 
								AND tbl_product.product_style_id LIKE '%".$product_style_id."%' 
								AND cartitem_deleted IS NULL 
								AND cartitem_cart_id <> '0'"); //This can allow multiple seperate donations
    	if($res	!=	''	|| $res	!= null){
    		return $res;
    	}else{
    		return false;
    	}
    }
    function CartItemOnCart($cartitem_id){
        global $DBobject;
    	
    	$res = $DBobject->wrappedSqlGetSingle("
							SELECT cartitem_id
							FROM tbl_cartitem
							WHERE
							cartitem_cart_id	=	'".clean($this->cart_id)."'
							AND	cartitem_id = '".clean($cartitem_id)."'
							AND cartitem_deleted is null
							AND cartitem_cart_id <> '0'");

        if($res	!=	''	|| $res	!= null){
            return true;
        }else{
            return false;
        }


    }


    function CartContainsProducts(){
        global $DBobject;
    	
    	$res = $DBobject->wrappedSqlGetSingle("
        							SELECT cartitem_id
        							FROM tbl_cartitem
        							WHERE
        							cartitem_cart_id	=	'".clean($this->cart_id)."'
        							AND cartitem_product_name != 'Donation'
        							AND cartitem_product_category_id != '26'
        							AND cartitem_deleted is null
        							AND cartitem_cart_id <> '0'");
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     *
     * This function takes a range of variables and inserts or updates a item in the cartitem table
     * @param int $product_id
     * @param string $product_name
     * @param string $product_category
     * @param int $product_price
     * @param int $product_quantity
     * @param boolean $product_special
     * @param serialized_array $attributes
     * @param boolean $help
     * @param int $wristsize
     * @return boolean
     */
    function AddToCart($product, $quantity){
    	global $DBobject;
    	 
        if($this->cart_id == '' || $this->cart_id == '0'){
            $this->__construct();
        }

	    $cartitem_id = $this->ProductOnCart($product['product_id'],$product['attributes']);
	        
        if($cartitem_id == 0){
            $res = $DBobject->wrappedSql("
					INSERT INTO tbl_cartitem (
						cartitem_cart_id,
						cartitem_product_id,
						cartitem_product_name,
						cartitem_product_price,
						cartitem_quantity,
						cartitem_product_gst,
						cartitem_user_ip,
						cartitem_user_browser,
						cartitem_created,
					)
					values(
						'".$this->cart_id."',
						'".clean($product['product_id'])."',
						'".clean($product['product_name'])."',
						'".clean($product['product_price'])."',
						'".clean($quantity)."',
						'".clean($$product['product_gst'])."',
						'".clean($_SERVER['REMOTE_ADDR'])."',
						'".clean($_SERVER['HTTP_USER_AGENT'])."',
						now()
						)");
            if($res){
            	$error = false; 
            	$cartitem_id = $$DBobject->wrappedSqlIdentity();
            	foreach ($product['attributes'] as $attr) {
            		 $res2 = $DBobject->wrappedSql("INSERT INTO tbl_cartitem_attr (
							    						cartitem_attr_cartitem_id,
	                    								cartitem_attr_attribute_id,
	    												cartitem_attr_attr_value_id,
							    						cartitem_attr_attribute_name,
							    						cartitem_attr_attr_value_name,
	    												cartitem_attr_created
			    								)
			    								values(
			    									'".$cartitem_id."',
			    									'".clean($attr['attribute_id'])."',
			    									'".clean($attr['attr_value_attribute_id'])."',
			    									'".clean($attr['attribute_name'])."',
			    									'".clean($attr['attr_value_name'])."',
			    									now()
			    									)");
            		 if (!$res2) {
            		 	$error = true;
            		 }
            	}
            	if (!$error) {
            		return true;
            	} 
            	
            }
        }
        else{
			
        	$DBobject->wrappedSql("UPDATE tbl_cartitem SET cartitem_quantity = '".$orig_cart[0]['cart_created']."',cart_discount_code = '".$orig_cart[0]['cart_discount_code']."' WHERE cart_id = '".$destination."'");
        	
	        
        }
        return false;
    }
    /**
     * Return array with product details from DB, calculate final price/weight/width,height,length based on attribute values. 
     * If the product is out of stock, deleted, unpublished or not found then returns array with error flag and message.
     * @param int $product_id
     * @param array $attributesArray
     * @return array 
     */
    function GetProductCalculation($product_id, $attributesArray = array()){
    	global $DBobject;
    
    	// --------------- GET PRODUCT INFO --------------------
    	
    	
    	$params = array (
    			":id" => $product_id
    	);

    	$res = $DBobject->wrappedSql("SELECT product_id, product_name, product_price, product_specialprice, product_gst, product_weight, product_width, product_height, product_length FROM tbl_product
									    			WHERE product_id = :id
									    			AND product_deleted is null
    												AND product_instock = 1
	    											AND product_published = 1",
    												$params
    	);
    	if ($res) {
    		$prod = $res[0];
    		if ($prod['product_specialprice'] > 0) {
    			$prod['product_price'] = $prod['product_specialprice'];
    		} 
    		
	    	$productAttr = array();
	    	foreach ($attributesArray as $attr) {	// expected array to get "array(array('attribute_id' => 'attr_value_id'))" 
	    		foreach ($attr as $attrId => $attrValId) {
		    		// --------------- GET PRODUCT ATTRIBUTES INFO --------------------
		    		
		    		$params = array (
			    			":vid" => $attrValId, 
			    			":pid" => $product_id
			    	);
			    	$attr = $DBobject->wrappedSql("SELECT * FROM tbl_attr_value
														LEFT JOIN tbl_attribute ON attr_value_attribute_id = attribute_id
			    										WHERE attr_value_id = :vid
													    AND attribute_product_id = :pid
													    AND attribute_deleted is null
													    AND attr_value_deleted is null",
			    										$params
			    	);
			    	if ($attr[0]['attr_value_instock'] === 0) {
			    		return array(
			    				"error" => true,
			    				"error_message" => "Product Out of Stock"
			    		);
			    	}
			    	if ($prod['product_specialprice'] > 0) {
		    			$prod['product_price'] = $prod['product_price'] + $attr[0]['attr_value_specialprice'];
		    		} else {
		    			$prod['product_price'] = $prod['product_price'] + $attr[0]['attr_value_price'];
		    		}
		    		$prod['product_weight'] = $prod['product_weight'] + $attr[0]['attr_value_weight'];
		    		$prod['product_width'] = $prod['product_width'] + $attr[0]['attr_value_width'];
		    		$prod['product_height'] = $prod['product_height'] + $attr[0]['attr_value_height'];
		    		$prod['product_length'] = $prod['product_length'] + $attr[0]['attr_value_length'];
		    		
		    		$productAttr[]['attribute_id'] = $attr[0]['attribute_id'];
		    		$productAttr[]['attr_value_attribute_id'] = $attr[0]['attr_value_attribute_id'];
		    		$productAttr[]['attribute_name'] = $attr[0]['attribute_name'];
		    		$productAttr[]['attr_value_name'] = $attr[0]['attr_value_name'];
		    	}
	    	}
	    	
	    	return ( $product = array(
	    				"error" => false,
	    				"product_id" => $prod['product_id'],
	    				"product_price" => $prod['product_price'],
	    				"product_gst" => $prod['product_gst'],
	    				"product_weight" => $prod['product_weight'],
	    				"product_width" => $prod['product_width'],
	    				"product_height" => $prod['product_height'],
	    				"product_length" => $prod['product_length'],
	    				"attributes" => $productAttr
	    	));
	    	 
    	}
    	return array(
    			"error" => true,
    			"error_message" => "Product Not Available"
    	);
    	 
    }

    function RemoveFromCart($cartitem_id){
        global $DBobject;
    	
    	$res = UpdateField('tbl_cartitem','cartitem_deleted','now()',"
															     cartitem_cart_id	= '".clean($this->cart_id)."'
																 AND cartitem_id = '".clean($cartitem_id)."'
																 AND cartitem_deleted is null
																  ");
        return $res;
    }
    
	/*function RemoveFromCart($product_id){

        $res = UpdateField('tbl_cartitem','cartitem_deleted','now()',"
															     cartitem_cart_id	= '".clean($this->cart_id)."'
																 AND cartitem_id = '".clean($product_id)."'
																 AND cartitem_deleted is null
																  ");
        return $res;
    }*/

    function UpdateQuantity4Item($cartitem_id,$quantity){
        global $DBobject;
    	
    	if($this->CartItemOnCart($cartitem_id) == true){
            $res = UpdateField('tbl_cartitem','cartitem_product_quantity',clean($quantity),"
																	     cartitem_cart_id	= '".clean($this->cart_id)."'
																		 AND cartitem_id = '".clean($cartitem_id)."'
																		 AND cartitem_deleted is null
																		  ");
            return $res;
        }else{
            return false;
        }
    }

    function AddPromoCode($promocode){
    	global $DBobject;
    	 
        $row = GetRow('tbl_promotion', 'promotion_code ="'.$promocode.'"');
        if($row){
			if($row['promotion_id'] == 27){
				if(!empty($_SESSION['user']['user_id']) && $_SESSION['user']['has_purchased']){
					return false;
				}
				if(!$this->StyleOnCart('3')){
					return false;
				}
				$prod = GetRow('tbl_product', 'product_id ="470"');
				$this->AddToCart($prod['product_id'],$prod['product_name'],'30',$$prod['product_price'],1,$prod['product_special'],serialize(array()),0,0,0,0,0);
			}
            $res = UpdateField('tbl_cart','cart_promotion_code',$promocode,'cart_id ="'.$this->cart_id.'"');
            return $res;
        }else{
            $_SESSION['promo_error'] = 'Invalid Code';
            return false;
        }
    }

    function ListCart(){
        global $DBobject;
    	
    	if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cart_arr  = $DBobject->wrappedSql("
        							SELECT cartitem_id
        							FROM tbl_cartitem
        							WHERE
        							cartitem_cart_id	=	'".clean($this->cart_id)."'
        							AND cartitem_deleted IS NULL
        							AND cartitem_cart_id <> '0'");
            
            return $cart_arr;
        }else{
            $arr = array();
            return $arr;
        }
    }
    function NumberOfProductsOnCart(){
        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cartitems = $this->ListCart();
            return count($cartitems);
        }else{
            return 0;
        }
    }

    function CartValue(){
        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cartitems = $this->ListCart();
            if($cartitems){
                foreach ($cartitems as $item) {
                    $price = $price + ($item[cartitem_product_price]* $item[cartitem_product_quantity] );
                }
            }
        }
        return $price;
    }

    function CartDiscountedValue(){
        return $this->CartValue() - $this->DiscountedAmount();
    }

    function DiscountedAmount(){
    	global $DBobject;
    	 
    	$discounted = 0 ;
    	$discounted = $this->StainlessSteelDiscount();
    	if($discounted != 0){
    		$ss_discount = true;
    	}
    	
        $promo = GetAnyCell('cart_promotion_code', 'tbl_cart', 'cart_id = "'.$this->cart_id.'"');
        if($promo){
            $tbl_promo = GetTable('tbl_promotion', 'promotion_code LIKE BINARY "'.$promo.'" AND promotion_active = "1"');
        }else{
            return $discounted;
        }
        
        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cartitems = $this->ListCart();

            if($cartitems && count($cartitems) > 0 && $tbl_promo && count($tbl_promo) > 0){
            	
                foreach($cartitems as $item){
                    if($item['cartitem_product_name'] == 'Donation' || $item['cartitem_product_special']){
                        continue;
                    }
                    
                    $cats = $this->GetCategories($item['cartitem_product_id']);
                    $mats = $this->GetMaterials($item['cartitem_product_id']);
                    $sty = $this->GetStyles($item['cartitem_product_id']);
                    
                    if($ss_discount && in_array('1', $mats)){
                    	$ss_discount = false;
                    	continue;
                    }
                    
                    foreach($tbl_promo as $row){
                        $dtype = 'dollar';
                        $promo_n = 0;
                        $promo_s = 0;
                        $item_c_check = true;
                        $item_p_check = false;
                        $item_m_check = false;
                        $item_s_check = false;
                        
                        $promo_cat = unserialize($row['promotion_product_category_id']);
		                $promo_style = unserialize($row['promotion_style_id']);
		                $promo_material = unserialize($row['promotion_material_id']);
		                $promo_prod = unserialize($row['promotion_product_id']);
                        /*if($promo_cat && count($promo_cat) > 0){
                            foreach($promo_cat as $cat){
                                if(isset($cats[$cat]) || (count($promo_cat) == 1 && $cat == 0)){
                                    $item_c_check = true;
                                    break;
                                }
                            }
                        }*/
                    	if($promo_style && count($promo_style) > 0){
                            foreach($promo_style as $s){
                                if(isset($sty[$s]) || (count($promo_style) == 1 && $s == 0)){
                                    $item_s_check = true;
                                    break;
                                }
                            }
                        }
                    	if($promo_material && count($promo_material) > 0){
                            foreach($promo_material as $ma){
                                if(isset($mats[$ma]) || (count($promo_material) == 1 && $ma == 0)){
                                    $item_m_check = true;
                                    break;
                                }
                            }
                        }
                        
                        if($promo_prod && count($promo_prod) > 0){
                            foreach($promo_prod as $prod){
                                if($item['cartitem_product_id'] == $prod || (count($promo_prod) == 1 && $prod == 0)){
                                    $item_p_check = true;
                                    break;
                                }
                            }
                        }
                        if($item_c_check && $item_p_check && $item_m_check && $item_s_check){
                            $dtype = GetAnyCell('dtype_description', 'tbl_dtype', 'dtype_id = "'.$row['promotion_dtype_id'].'"');
                            $promo_n = $row['promotion_amount'];
                            $promo_s = $row['promotion_special_amount'];
                            break;
                        }
                    }
                    if($item_c_check && $item_p_check && $item_m_check && $item_s_check){
                        if($item['cartitem_product_special']){
                            $discounted = $discounted + ($this->DiscountValue($item['cartitem_product_price'],$promo_s,$dtype)* $item['cartitem_product_quantity']) ;
                            continue;
                        }else{
                            $discounted = $discounted + ($this->DiscountValue($item['cartitem_product_price'],$promo_n,$dtype)* $item['cartitem_product_quantity']) ;
                            continue;
                        }
                    }
                }
            }
        }

        return $discounted;
    }
/* 
	function StainlessSteelDiscount(){
		global $DBobject;
		 
		if(!empty($_SESSION['user']['user_id']) && $_SESSION['user']['has_purchased']){
			return 0;
		}
    	//////
        //Stainless Steel check
        //////
        $ss_check = false;
        $pcount = 0;
        $discounted = 0 ;
        $pprice = 0;
        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cartitems = $this->ListCart();
            if($cartitems && count($cartitems) > 0){
                foreach($cartitems as $item){
                    //if($item['cartitem_product_special'] || $item['cartitem_product_name'] == 'Donation' || $item['cartitem_product_category_id'] == '26' || $item['cartitem_product_category_id'] == '27'){
                	 if( $item['cartitem_product_name'] == 'Donation' || $item['cartitem_product_category_id'] == '26' || $item['cartitem_product_category_id'] == '30' || $item['cartitem_product_category_id'] == '27'){
                        continue;
                    }
                    
                    $pcount= $pcount + intval($item['cartitem_product_quantity']); //Count number of actual products on the cart
	                ////////////////////////////
	            	//Stainless Steel Discount//
	            	////////////////////////////
	            	$pid = $item['cartitem_product_id'];
	            	$res = GetAnyCell('product_material_id', 'tbl_product', "product_id = '{$pid}'");
	            	if(!empty($res)){
		            	$mats = unserialize($res);
		            	if(in_array('1', $mats)){
		            		if($item['cartitem_product_price'] > 30){
		            			$pprice = $item['cartitem_product_price'];
		            		}
		            		$ss_check = true;
		            	}
	            	}
                }
            }
        }
        if($ss_check && $pcount >= 2 ){
        	$discounted = $pprice - 30; //Discount by 25 Dollars to reduce a stainless steel items to 30 dollars
        	if($discounted < 0)
        	{
        		$discounted = 0;
        	}
        }
        return $discounted;
    }
    
    function DiscountPrice($price,$discount_amount,$discount_type){
        if($discount_type == 'dollar'){
            return $price - $discount_amount;
        }
        if($discount_type == 'percentage'){
            return $price * ((100-$discount_amount)/100);
        }
    }
    function DiscountValue($price,$discount_amount,$discount_type){
        if($discount_type == 'dollar'){
            return $discount_amount;
        }
        if($discount_type == 'percentage'){
            return $price * (($discount_amount)/100);
        }
    }

    function GetCategories($category_id){
        global $DBobject;
    	
    	$cats = array();
        $cat_id = $category_id;
        $cats[$cat_id] = $cat_id;
        while(true){
            $res = GetAnyCell('product_category_product_category_id', 'tbl_product_category', 'product_category_id = "'.$cat_id.'"');
            if($res && $res != 0){
                $cat_id = $res;
                $cats[$cat_id] = $cat_id;
            }else{
                break;
            }
        }
        return $cats;
    }
    
	function GetMaterials($product_id){
        global $DBobject;
    	
    	$mats = array();
        $prod_id = $product_id;
        $res = GetAnyCell('product_material_id', 'tbl_product', 'product_id = "'.$prod_id.'"');
        $arr_res = unserialize($res);
	 	foreach($arr_res as $va){
            $mats[$va] = $va;
        }
        return $mats;
    }
    
	function GetStyles($product_id){
        global $DBobject;
    	
    	$sty = array();
        $prod_id = $product_id;
        $res = GetAnyCell('product_style_id', 'tbl_product', 'product_id = "'.$prod_id.'"');
		$arr_res = unserialize($res);
	 	foreach($arr_res as $va){
            $sty[$va] = $va;
        }
        return $sty;
    }
    
    function GetProduct($product_id,$product_category){
        global $DBobject;
    	
    	$res = GetRow('tbl_cartitem', "cartitem_cart_id	= '".clean($this->cart_id)."'
				 AND cartitem_product_id = '".clean($product_id)."'
				 AND cartitem_product_category_id = '".clean($product_category)."'
				 AND cartitem_deleted is null");
        if($res){
            return $res;
        }else{
            return false;
        }
    }
 */
    function CloseThisCart(){
        global $DBobject;
    	
    	$res = UpdateField('tbl_cart','cart_closed_date','now()'," cart_id	= '".clean($this->cart_id)."' AND cart_deleted is null ");
        $res = UpdateField('tbl_cart','cart_closed_date','now()'," cart_id	= '".clean($_COOKIE["cart_id"])."' AND cart_deleted is null ");
        return $res;
    }

    function GetCartID(){
        global $DBobject;
    	
    	$res  = $DBobject->wrappedSqlGetSingle("SELECT cart_id
    									FROM tbl_cart
    									WHERE
    									cart_closed_date is null
    									AND cart_deleted is null
    									AND cart_session = '".session_id()."'
    									");
        if($res	!= 0){
            return $res;
        }else{
            return  false;
        }
    }

/*     function GetPromotionCode(){
        global $DBobject;
    	
    	$res = GetAnyCell('cart_promotion_code', 'tbl_cart', 'cart_id = "'.$this->cart_id.'"');
        if($res){
            return $res;
        }else{
            return  false;
        }
    } */

    function ValidateCartItems(){
        global $DBobject;
    	
    	$buf = '';
        $cart_items = $this->ListCart();
        if(!$cart_items){
            return '';
        }
        
        foreach($cart_items as $item){
        	if($item['cartitem_product_id'] == 286){
        		if(!($_SESSION['pending_update'])){
        			//$this->RemoveFromCart($item['cartitem_id']);
        		}
        	}
        	
        	
        	//SPECIAL PROMOTION PRODUCT ADDED 10 MAY 2013
        	if($item['cartitem_product_id'] == '470'){
        		
        		$promo = GetAnyCell('cart_promotion_code', 'tbl_cart', 'cart_id = "'.$this->cart_id.'"');
        		if($promo != 'SPORTS13'){
        			$this->RemoveFromCart($item['cartitem_id']);
        			continue;
        		}
        		if(!empty($_SESSION['user']['user_id']) && $_SESSION['user']['has_purchased']){
        			$this->RemoveFromCart($item['cartitem_id']);
        			continue;
        		}
        		if(!$this->StyleOnCart('3')){
        			$this->RemoveFromCart($item['cartitem_id']);
        			continue;
        		}
        	}
        	
            $product_id = $item['cartitem_product_id'];
            $emblem_id = $item['cartitem_emblem_id'];
            $length_id = $item['cartitem_product_length_id'];
            $price = $item['cartitem_product_price'];
            $name = $item['cartitem_product_name'];
            $special = $item['cartitem_product_special'];
            if($emblem_id){
            	$pspecial = GetAnyCell('product_special', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                $pprice = GetAnyCell('product_price', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                $spprice = GetAnyCell('product_specialprice', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                $lspecial = GetAnyCell('product_length_special', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
                $lprice = GetAnyCell('product_length_price', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
                $lsprice = GetAnyCell('product_length_specialprice', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
                if($pspecial && $spprice && $spprice < $pprice && $spprice != 0){
                	$pprice = $spprice;
                	$special = 1;
                }
            	if($lprice && $lprice != 0){
                	$pprice = $lprice;
                	$special = 0;
                }
            	if($lspecial && $lsprice && $lsprice < $pprice && $lsprice != 0){
                	$pprice = $lsprice;
                	$special = 1;
                }
                
                if(!empty($_SESSION['user']['user_id']) && $_SESSION['user']['has_purchased']){
	                $mpprice = GetAnyCell('product_member_price', 'tbl_product', 'product_id = "'.$emblem_id.'"');
	                $mspprice = GetAnyCell('product_member_specialprice', 'tbl_product', 'product_id = "'.$emblem_id.'"');
	                $mlprice = GetAnyCell('product_length_member_price', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
	                $mlsprice = GetAnyCell('product_length_member_specialprice', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
	                if($mpprice && $mpprice < $pprice && $mpprice != 0){
	                	$pprice = $mpprice;
	                	$special = 0;
	                }
                	if($pspecial && $mspprice && $mspprice < $pprice && $mspprice != 0){
	                	$pprice = $mspprice;
	                	$special = 1;
	                }
	            	if($mlprice && $mlprice != 0){
	                	$pprice = $mlprice;
	                	$special = 0;
	                }
	            	if($lspecial && $mlsprice && $mlsprice < $pprice && $mlsprice != 0){
	                	$pprice = $mlsprice;
	                	$special = 1;
	                } 	
                }
                    
                if($pprice){
                    if($pprice && $pprice != $price){
                        $pname = GetAnyCell('product_name', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                        UpdateField('tbl_cartitem','cartitem_product_price',$pprice,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        UpdateField('tbl_cartitem','cartitem_product_special',$special,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        $buf.= 'The price for '.$name.' - '.$pname.' has changed. Your shopping cart has been updated.<br />';
                    }
                }else{
                    //update cart item
                    UpdateField('tbl_cartitem','cartitem_deleted','now()','cartitem_id = "'.$item['cartitem_id'].'"');
                    $buf.= $name.' - '.$pname.' is no longer available. Your shopping cart has been updated.<br />';
                }
            }else{
            	$pspecial = GetAnyCell('product_special', 'tbl_product', 'product_id = "'.$product_id.'"');
            	$pprice = GetAnyCell('product_price', 'tbl_product', 'product_id = "'.$product_id.'"');
                $spprice = GetAnyCell('product_specialprice', 'tbl_product', 'product_id = "'.$product_id.'"');
                $lspecial = GetAnyCell('product_length_special', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
                $lprice = GetAnyCell('product_length_price', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
                $lsprice = GetAnyCell('product_length_specialprice', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
            	if($pspecial && $spprice && $spprice < $pprice && $spprice != 0){
                	$pprice = $spprice;
                	$special = 1;
                }
            	if($lprice && $lprice != 0){
                	$pprice = $lprice;
                	$special = 0;
                }
            	if($lspecial && $lsprice && $lsprice < $pprice && $lsprice != 0){
                	$pprice = $lsprice;
                	$special = 1;
                }
                
                if(!empty($_SESSION['user']['user_id']) && $_SESSION['user']['has_purchased']){
	                $mpprice = GetAnyCell('product_member_price', 'tbl_product', 'product_id = "'.$product_id.'"');
	                $mspprice = GetAnyCell('product_member_specialprice', 'tbl_product', 'product_id = "'.$product_id.'"');
	                $mlprice = GetAnyCell('product_length_member_price', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
	                $mlsprice = GetAnyCell('product_length_member_specialprice', 'tbl_product_length', 'product_length_id = "'.$length_id.'"');
	                if($mpprice && $mpprice < $pprice && $mpprice != 0){
	                	$pprice = $mpprice;
	                	$special = 0;
	                }
                	if($pspecial && $mspprice && $mspprice < $pprice && $mspprice != 0){
	                	$pprice = $mspprice;
	                	$special = 1;
	                }
	            	if($mlprice && $mlprice != 0){
	                	$pprice = $mlprice;
	                	$special = 0;
	                }
	            	if($lspecial && $mlsprice && $mlsprice < $pprice && $mlsprice != 0){
	                	$pprice = $mlsprice;
	                	$special = 1;
	                } 	
                }
                
                if($pprice){
                    if($pprice != $price){
                        //update cart item
                        UpdateField('tbl_cartitem','cartitem_product_price',$pprice,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        UpdateField('tbl_cartitem','cartitem_product_special',$special,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        $buf.= 'The price for '.$name.' has changed. Your shopping cart has been updated.<br />';
                    }
                }else{
                    //update cart item
                    UpdateField('tbl_cartitem','cartitem_deleted','now()','cartitem_id = "'.$item['cartitem_id'].'"');
                    $buf.= $name.' is no longer available. Your shopping cart has been updated.<br />';
                }
            }
        }
        return $buf;
    }
}