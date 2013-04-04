{block name=cart}
<div class="cart">
	<script type="text/javascript">
	function UpdateQuantity(self,id){
		var val = $(self).val();
		var q = parseInt(val);
		if( isNaN(q) || q <= 0){
    		alert("Please enter a valid quantity");
		}else{
			$.ajax({ 
		    	type: "POST",
		    	url: "/includes/processes/processes-cart.php",
		    	data: "Action=SetQty4Item&cartitem_id="+id+"&quantity="+val,
		   		dataType: "html",
		    	success: function(res, textStatus) {
			    	try{
		    			data = json_parse(res);
			    	}catch(err){}
		    		// THIS LOOP PUTS ALL THE CONTENT INTO THE RIGHT PLACES
		    		if(data != null){
		    			for (var key in data) {
		    				if(key !== 'title'){
		    					$(key)[0].innerHTML = data[key];
		    				}
		    			}
		    		}
		    		$("body").removeClass("wait");
		    	},
		    	error: function(result) {
		    		$("body").removeClass("wait");
		    	}
		    });
		}
	}
	function DeleteProduct(id){
		$.ajax({ 
	    	type: "POST",
	    	url: "/includes/processes/processes-cart.php",
	    	data: "Action=DeleteItem&cartitem_id="+id,
	   		dataType: "html",
	    	success: function(res, textStatus) {
	    		try{
		    		data = json_parse(res);
			    }catch(err){}
	    		// THIS LOOP PUTS ALL THE CONTENT INTO THE RIGHT PLACES
	    		if(data != null){
	    			for (var key in data) {
	    				if(key !== 'title'){
	    					$(key)[0].innerHTML = data[key];
	    				}
	    			}
	    		}
	    		$("body").removeClass("wait");
	    	},
	    	error: function(result) {
	    		$("body").removeClass("wait");
	    	}
	    });
	}
	</script>
	<div class="cart-content">
<div class="item-header">
	<div class="item-name grid_6"><b>Item</b></div>
	<div class="item-option grid_4"><b>Option</b></div>
	<div class="item-quantity grid_3"><b>Quantity</b></div>
	<div class="item-price grid_2"><b>Price</b></div>
</div>
<div class="clear"></div>
{foreach key=key item=cartitem from=$cart}
<div class="item-row">
	<div class="item-name grid_6">{$cartitem.name}</div>
	<div class="item-option grid_4">{$cartitem.option}</div>
	<div class="item-quantity grid_3" >
		<input id="quantity_{$cartitem.cartitem_id}" name="quantity" value="{$cartitem.quantity}" />
		<a class="update-btn" href="javascript:void(0);" onclick="UpdateQuantity('#quantity_{$cartitem.cartitem_id}','{$cartitem.cartitem_id}');">Update</a> | 
		<a class="delete-btn" href="javascript:void(0);" onclick="DeleteProduct('{$cartitem.cartitem_id}');">Delete</a>
	</div>
	<div class="item-price grid_2">${$cartitem.price}</div>
</div>
<div class="clear"></div>
{/foreach}
</div>
<div class="clear"></div>
<div class="cart-total grid_3"><span class="cart-total-header">Total (Inc GST): </span>${$carttotal}</div>
<div class="cart-notes">
<p>Gluten Free Options are available. Please contact our staff at your preffered store.</p>
<p>A minimum $40.00 order is required for delivery. Orders less than $40.00 can be collected from your nominated store.</p>
</div>
<div class="cart-checkout grid_2"><a class="submit" title="Checkout" href="/checkout">Checkout</a></div>
<div class="clear"></div>
</div>
<div class="clear"></div>
{/block}