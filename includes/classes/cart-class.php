<?php
class cart{

	protected $DBobject;
    protected $cart_id;
    protected $created_date;
    protected $closed_date;
    protected $cart_session;

    protected $postage = 0;

    private $ses_cart_id;
    private $user_cart_id;

    function __construct(){
    	$this->DBobject = new DBmanager();
        if($this->VerifySessionCart(session_id())){
            if(isset($_SESSION['user_params'])){
                if($this->VerifyUserCart($_SESSION['user_params']['user_id'])){
                    if($this->ses_cart_id != $this->user_cart_id){
                        $this->CreateCart();//create new cart
                        $this->CopyCart2Cart($this->user_cart_id, $this->cart_id);
                        $this->DeleteCart($this->user_cart_id);
                        $this->CopyCart2Cart($this->ses_cart_id, $this->cart_id);
                        $this->DeleteCart($this->ses_cart_id);
                    }else{
                        $this->cart_id = $this->ses_cart_id;
                        //do nothing since the session cart exists and is the current user cart too.
                    }
                }else{
                    $this->CreateCart();//create new cart
                    $this->CopyCart2Cart($this->ses_cart_id, $this->cart_id);
                    $this->DeleteCart($this->ses_cart_id);
                }
            }else{
                $this->cart_id = $this->ses_cart_id;
                //do nothing since session cart exists and user is not logged in
            }
        }else{
            if(isset($_SESSION['user_params'])){
                if($this->VerifyUserCart($_SESSION['user_params']['user_id'])){
                    $this->CreateCart();//create new cart
                    $this->CopyCart2Cart($this->user_cart_id, $this->cart_id); //copy cart contents to new cart
                    $this->DeleteCart($this->user_cart_id); //close old user cart;
                }else{
                    $this->CreateCart();//create new cart
                }
            }else{
                $this->CreateCart();//create new cart
            }
        }

    }
    
    function LoadStore($id){
    	$sql[0] = "SELECT * FROM tbl_store WHERE store_id = '{$id}'";
    	if($res = $this->DBobject->wrappedSqlGet($sql[0])){
    		foreach($res as $value){
    			return $value;
    		}
    	}
    }

    function LoadCart(){
    	global  $CONFIG,$SMARTY;
    	$total_price = 0;
    	$cart = array();
    	if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
    		$sql[0] = "SELECT * FROM tbl_cartitem WHERE cartitem_cart_id = {$this->cart_id} AND cartitem_deleted IS NULL";
    		if($res = $this->DBobject->wrappedSqlGet($sql[0])){
				foreach($res as $value){
					$id = $value['cartitem_id'];
					$name = $value['cartitem_product_name'];
					$attr_id = $value['cartitem_attribute_id'];
					$sql[2] = "SELECT attribute_name FROM tbl_attribute WHERE attribute_id = {$attr_id} AND attribute_deleted IS NULL";
    				$attr = $this->DBobject->wrappedSqlGetSingle($sql[2]);
    				if(empty($attr)){
    					$attr = "&nbsp;";
    				}
					$quantity = $value['cartitem_product_quantity'];
					$price = $value['cartitem_product_price'];
					$t_price = doubleval($price) * doubleval($quantity);
					$total_price = $total_price + $t_price;
					$cart[] = array('cartitem_id'=>$id,'name'=>$name,'option'=>$attr,'quantity'=>$quantity,'price'=>money_format("%.2n", $t_price));
				}    			
    		}
    	}
    	$SMARTY->assign('cart',$cart);
    	$SMARTY->assign('carttotal',money_format("%.2n", $total_price));
    	
    	$stores = $this->LoadStores();
    	$SMARTY->assign('stores',$stores);
    	
    	if($total_price >= 40){
    		$SMARTY->assign('delivery',true);
    	}else{
    		$SMARTY->assign('delivery',false);
    	}
    }
    
    function LoadStores(){
    	$stores = array();
    	$sql[2] = "SELECT * FROM tbl_store WHERE store_deleted IS NULL";
    	$res = $this->DBobject->wrappedSqlGet($sql[2]);
    	if($res){
    		foreach($res as $row){
    			$stores[] = array('id'=>$row['store_id'],'name'=>$row['store_name']);
    		}
    	}
    	return $stores;
    }
    
    /**
     * Return Postage value as stored in $this->postage
     */
    function GetPostage(){
        if($this->CartContainsProducts()){
            return $this->postage;
        }else{
            return 0;
        }
    }

    /**
     * Takes a Session_id value and checks if a cart exists in the database for this session.
     * Returns True if exists, else returns false.
     * @param unknown_type $ses_val
     * @return boolean
     */
    function VerifySessionCart($ses_val){
        $this->ses_cart_id = $this->DBobject->wrappedSqlGetSingle("SELECT cart_id FROM tbl_cart
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
     * Takes a User_id value and checks if a cart exists in the database for this User.
     * Returns True if exists, else returns false.
     * @param unknown_type $user_val
     * @return boolean
     */
    function VerifyUserCart($user_val){
        $this->user_cart_id = $this->DBobject->wrappedSqlGetSingle("SELECT cart_id FROM tbl_cart
											WHERE cart_closed_date is null
											AND cart_deleted is null
											AND cart_user_id = '".clean($user_val)."'
											ORDER BY cart_id DESC");
        if($this->user_cart_id != 0){
            return true;
        }else{
            return false;
        }
    }

    function  CreateCart(){
        $this->cart_session = session_id();						//current session id
        if(isset($_SESSION['user_params'])){
        	$sql = "INSERT INTO tbl_cart (cart_created_date,cart_session,cart_user_id)
							VALUES (now(),'".clean($this->cart_session)."',".clean($_SESSION['user_params']['user_id']).")";
            $res = $this->DBobject->wrappedSqlInsert($sql);
            $this->cart_id = $this->DBobject->wrappedSqlIdentity();
        }else{
        	$sql = "INSERT INTO tbl_cart (cart_created_date,cart_session)
							VALUES (now(),'".clean($this->cart_session)."')";
            $res = $this->DBobject->wrappedSqlInsert($sql);
            $this->cart_id = $this->DBobject->wrappedSqlIdentity();
        }
    }

    function CopyCart2Cart($origin, $destination){
        if($origin != 0 && $destination != 0){
            $orig_cart = GetTable('tbl_cart', 'cart_id = "'.$origin.'"');
            if($orig_cart[0]){
            	$sql = "UPDATE tbl_cart SET 
            		cart_created_date = '".$orig_cart[0]['cart_created_date']."',
            		cart_promotion_code = '".$orig_cart[0]['cart_promotion_code']."',
            		cart_notes = '".$orig_cart[0]['cart_notes']."',
            		WHERE cart_id = '".$destination."'";
                $this->DBobject->wrappedSqlInsert($sql);
            }

            $orig_items = GetTable('tbl_cartitem', 'cartitem_cart_id = "'.$origin.'" AND cartitem_deleted  IS NULL');
            if($orig_items){
                foreach($orig_items as $item){
                	$sql = "INSERT INTO tbl_cartitem (
    									cartitem_cart_id,
    									cartitem_product_id,
    									cartitem_product_name,
    									cartitem_product_category_id,
    									cartitem_product_price,
    									cartitem_product_quantity,
    									cartitem_added,
    									cartitem_trackcode
    								)
    								values(
    									'".($destination)."',
    									'".($item['cartitem_product_id'])."',
    									'".($item['cartitem_product_name'])."',
    									'".($item['cartitem_product_category_id'])."',
    									'".($item['cartitem_product_price'])."',
    									'".($item['cartitem_product_quantity'])."',
    									'".($item['cartitem_added'])."',
    									'".($item['cartitem_trackcode'])."'
    									)";
                    $this->DBobject->wrappedSqlInsert($sql);
                }
            }
        }
    }

    function CloseCart($cart_id){
        $res = $this->DBobject->UpdateField('tbl_cart','cart_closed_date','now()',"cart_id = '".$cart_id."'");
    }

    function DeleteCart($cart_id){
        $res = $this->DBobject->UpdateField('tbl_cart','cart_deleted','now()',"cart_id = '".$cart_id."'");
    }
    
    function UpdateCartField($field, $value){
    	$res = $this->DBobject->UpdateField('tbl_cart',$field,$value,"cart_id = '".$this->cart_id."'");
    }

    function ProductOnCart($product_id,$attribute_id,$product_category_id){
    	$sql = "SELECT cartitem_id
							FROM tbl_cartitem
							WHERE
							cartitem_cart_id = '".clean($this->cart_id)."'
							AND cartitem_product_id = '".clean($product_id)."'
							AND cartitem_attribute_id = '".clean($attribute_id)."'
							AND cartitem_product_category_id = '".clean($product_category_id)."'
							AND cartitem_deleted is null
							AND cartitem_cart_id <> '0'";
        $res = $this->DBobject->wrappedSqlGetSingle($sql); //This can allow multiple seperate donations
        
        if($res	!=	''	|| $res	!= null){
            return true;
        }else{
            return false;
        }
    }
    function CartItemOnCart($cartitem_id){
        $res = $this->DBobject->wrappedSqlGetSingle("
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
        $res = $this->DBobject->wrappedSqlGetSingle("
        							SELECT cartitem_id
        							FROM tbl_cartitem
        							WHERE
        							cartitem_cart_id	=	'".clean($this->cart_id)."'
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
    function AddToCart($product_id,$attribute_id,$product_name,$product_category,$product_price,$product_quantity,$trackcode){

        if($this->cart_id == '' || $this->cart_id == '0'){
            $this->__construct();
        }

        if($this->ProductOnCart($product_id,$attribute_id,$product_category) == false  ){
            $res = $this->DBobject->wrappedSqlInsert("
					INSERT INTO tbl_cartitem (
						cartitem_cart_id,
						cartitem_product_id,
						cartitem_product_name,
						cartitem_attribute_id,
						cartitem_product_category_id,
						cartitem_product_price,
						cartitem_product_quantity,
						cartitem_added,
						cartitem_trackcode
					)
					values(
						'".($this->cart_id)."',
						'".($product_id)."',
						'".($product_name)."',
						'".($attribute_id)."',
						'".($product_category)."',
						'".($product_price)."',
						'".($product_quantity)."',
						now(),
						'".$trackcode."'
						)");
            if($res){
                return true;
            }
        }
        else{
        	$cartitem_id = $this->DBobject->GetAnyCell('cartitem_id', 'tbl_cartitem', 'cartitem_cart_id = "'.($this->cart_id).'" AND cartitem_attribute_id="'.$attribute_id.'" AND cartitem_product_id="'.$product_id.'" AND cartitem_deleted IS NULL');
        	$quant = $this->DBobject->GetAnyCell('cartitem_product_quantity', 'tbl_cartitem', 'cartitem_id = "'.($cartitem_id).'"');
            $n_q = intval($quant) + intval($product_quantity);
            $sql = "UPDATE tbl_cartitem SET cartitem_product_quantity = '{$n_q}' WHERE cartitem_id = '{$cartitem_id}'";
        	$res = $this->DBobject->wrappedSql($sql);
        	if($res){
                return true;
            }
            
            /*if($this->RemoveFromCart($cartitem_id)){
                if($this->AddToCart($product_id,$attribute_id,$product_name,$product_category,$product_price,$product_quantity)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }*/
        }
        return true;
    }

    function RemoveFromCart($cartitem_id){

        $res = $this->DBobject->UpdateField('tbl_cartitem','cartitem_deleted','now()',"
															     cartitem_cart_id	= '".clean($this->cart_id)."'
																 AND cartitem_id = '".clean($cartitem_id)."'
																 AND cartitem_deleted is null
																  ");
        return $res;
    }

    function UpdateQuantity4Item($cartitem_id,$quantity){
        if($this->CartItemOnCart($cartitem_id) == true){
        	$quantity = intval($quantity);
        	$sql = "UPDATE tbl_cartitem SET cartitem_product_quantity = '{$quantity}' 
        				WHERE cartitem_cart_id	= '".clean($this->cart_id)."'
						AND cartitem_id = '".clean($cartitem_id)."'
						AND cartitem_deleted is null";
            $res = $this->DBobject->wrappedSql($sql);
            return $res;
        }else{
            return false;
        }
    }

    function AddPromoCode($promocode){
        $row = GetRow('tbl_promotion', 'promotion_code ="'.$promocode.'"');
        if($row){
            $res = $this->DBobject->UpdateField('tbl_cart','cart_promotion_code',$promocode,'cart_id ="'.$this->cart_id.'"');
            return $res;
        }else{
            $_SESSION['promo_error'] = 'Invalid Code';
            return false;
        }
    }

    function ListCart(){
        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cart_arr  = $this->DBobject->GetTable('tbl_cartitem', "cartitem_cart_id='".$this->cart_id."' AND cartitem_deleted is null AND cartitem_cart_id <> '0'");
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
    
    function CartDetails(){
    	if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cart_arr  = $this->DBobject->GetTable('tbl_cart', "cart_id='".$this->cart_id."'");
            return $cart_arr[0];
        }else{
            $arr = array();
            return $arr;
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
        $promo = GetAnyCell('cart_promotion_code', 'tbl_cart', 'cart_id = "'.$this->cart_id.'"');

        if($promo){
            $tbl = GetTable('tbl_promotion', 'promotion_code LIKE BINARY "'.$promo.'" AND promotion_active = "1"');
        }else{
            return 0;
        }
        $discounted = 0 ;
        $promo_cat_price = 0;
        $promo_cat_special_price = 0;

        if($this->VerifySessionCart(session_id()) == true && $this->cart_id != '0' ){
            $cartitems = $this->ListCart();
            foreach ($cartitems as $item) {
                if($item['cartitem_product_name'] == 'Donation'){
                    continue;
                }
                foreach($tbl as $row){
                    $promo_cat = $row['promotion_product_category_id'];
                    $promo_prod = $row['promotion_product_id'];
                    //Validate that either no promo category and product are set, or category matches and no product set, or product matches and no category set, or both category and product are set and match
                    if( ($promo_cat == '0' && $promo_prod == '0') || ($item['cartitem_product_category_id'] == $promo_cat && $promo_prod == '0')
                        || ($item['cartitem_product_id'] == $promo_prod && $promo_cat == '0') || ($item['cartitem_product_id'] == $promo_prod && $item['cartitem_product_category_id'] == $promo_cat) ){

                        $dtype = $this->DBobject->GetAnyCell('dtype_description', 'tbl_dtype', 'dtype_id = "'.$row['promotion_dtype_id'].'"');
                        $promo_n = $row['promotion_amount'];
                        $promo_s = $row['promotion_special_amount'];

                        if($item['cartitem_product_special']){
                            $discounted = $discounted + ($this->DiscountValue($item['cartitem_product_price'],$promo_s,$dtype)* $item['cartitem_product_quantity']) ;
                        }else{
                            $discounted = $discounted + ($this->DiscountValue($item['cartitem_product_price'],$promo_n,$dtype)* $item['cartitem_product_quantity']) ;
                        }

                    }
                }
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

    function GetProduct($product_id,$product_category){
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

    function CloseThisCart(){
        $res = $this->DBobject->UpdateField('tbl_cart','cart_closed_date','now()'," cart_id	= '".clean($this->cart_id)."' AND cart_deleted is null ");
        $res = $this->DBobject->UpdateField('tbl_cart','cart_closed_date','now()'," cart_id	= '".clean($_COOKIE["cart_id"])."' AND cart_deleted is null ");
        return $res;
    }

    function GetCartID(){
        $res  = $this->DBobject->wrappedSqlGetSingle("SELECT cart_id
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

    /**************
     * NEEDS WORK *
     * ************/
    function ValidateCartItems(){
        return '';//Temporary skip

        $buf = '';
        $cart_items = $this->ListCart();
        if(!$cart_items){
            return '';
        }
        foreach($cart_items as $item){
            $product_id = $item['cartitem_product_id'];
            $price = $item['cartitem_product_price'];
            $name = $item['cartitem_product_name'];
            $special = $item['cartitem_product_special'];
            if($emblem_id){
                $pprice = '0';
                if($special){
                    $pprice = $this->DBobject->GetAnyCell('product_specialprice', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                }else{
                    $pprice = $this->DBobject->GetAnyCell('product_price', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                }
                if($pprice){
                    if($pprice != $price){
                        $pname = $this->DBobject->GetAnyCell('product_name', 'tbl_product', 'product_id = "'.$emblem_id.'"');
                        $this->DBobject->UpdateField('tbl_cartitem','cartitem_product_price',$pprice,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        $buf.= 'The price for '.$name.' - '.$pname.' has changed. Your shopping cart has been updated.<br />';
                    }
                }else{
                    //update cart item
                    $this->DBobject->UpdateField('tbl_cartitem','cartitem_deleted','now()','cartitem_id = "'.$item['cartitem_id'].'"');
                    $buf.= $name.' - '.$pname.' is no longer available. Your shopping cart has been updated.<br />';
                }
            }else{
                $pprice = '0';
                if($special){
                    $pprice = $this->DBobject->GetAnyCell('product_specialprice', 'tbl_product', 'product_id = "'.$product_id.'"');
                }else{
                    $pprice = $this->DBobject->GetAnyCell('product_price', 'tbl_product', 'product_id = "'.$product_id.'"');
                }
                if($pprice){
                    if($pprice != $price){
                        //update cart item
                        $this->DBobject->UpdateField('tbl_cartitem','cartitem_product_price',$pprice,'cartitem_id = "'.$item['cartitem_id'].'"');//update cart item
                        $buf.= 'The price for '.$name.' has changed. Your shopping cart has been updated.<br />';
                    }
                }else{
                    //update cart item
                    $this->DBobject->UpdateField('tbl_cartitem','cartitem_deleted','now()','cartitem_id = "'.$item['cartitem_id'].'"');
                    $buf.= $name.' is no longer available. Your shopping cart has been updated.<br />';
                }
            }
        }
        return $buf;
    }
}