{block name=body}

	<div class="row">
	
		<div style="display:inline;" class="col-md-6">{cartitem_product_name}</div>
		<div style="display:inline;" class="col-md-2">${$cartitem_product_price}</div>
		<div style="display:inline;" class="col-md-1">{$cartitem_quantity}</div>
		<div style="display:inline;" class="col-md-2">***</div>
		<div style="display:inline;" class="col-md-1"><a class="btn-primary btn" onclick="remove();">Remove</a></div>
			
	</div>

{/block}
