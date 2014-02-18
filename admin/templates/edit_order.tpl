{block name=body}

<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover" style="margin-top:40px;">
			<tbody>
				<tr>
					<td><b>Order No:</b></td>
					<td style="text-align: center;">{$fields.cart_id}</td>
					<td><b>Order placed:</b></td>
					<td style="text-align: center;">{$fields.cart_closed_date|date_format:"%e %B %Y"}</td>
				</tr>
				<tr>
					<td><b>User's Detail:</b></td>
					<td style="text-align: center;">{$fields.user.0.user_gname} {$fields.user.0.user_surname} / {$fields.user.0.user_email}</td>
					<td><b>Payment Status:</b></td>
					<td style="text-align: center;"> <b>{if $fields.payment.0.payment_status eq 'P'}PAID{else}{$fields.payment.0.payment_status}{/if}</b></td>
				</tr>
				<tr>
					<td><b>Billing Address:</b></td>
					<td style="text-align: center;">{$fields.payment.0.billing_address.0.address_name}</td>
					<td style="text-align: center;" colspan="2">
						{$fields.payment.0.billing_address.0.address_line1} 
						{$fields.payment.0.billing_address.0.address_line2} 
						{$fields.payment.0.billing_address.0.address_suburb}, 
						{$fields.payment.0.billing_address.0.address_state}, 
						{$fields.payment.0.billing_address.0.address_country} 
						{$fields.payment.0.billing_address.0.address_postcode}. 
						{if $fields.payment.0.billing_address.0.address_telephone} {$fields.payment.0.billing_address.0.address_telephone}{/if}
						{if $fields.payment.0.billing_address.0.address_telephone && $fields.payment.0.billing_address.0.address_telephone} / {/if} 
						{if $fields.payment.0.billing_address.0.address_mobile} {$fields.payment.0.billing_address.0.address_mobile} {/if}
					</td>
				</tr>
				<tr>
					<td><b>Shipping Address:</b></td>
					<td style="text-align: center;">{$fields.payment.0.shipping_address.0.address_name}</td>
					<td style="text-align: center;" colspan="2">
						{$fields.payment.0.shipping_address.0.address_line1} 
						{$fields.payment.0.shipping_address.0.address_line2} 
						{$fields.payment.0.shipping_address.0.address_suburb}, 
						{$fields.payment.0.shipping_address.0.address_state}, 
						{$fields.payment.0.shipping_address.0.address_country} 
						{$fields.payment.0.shipping_address.0.address_postcode}. 
						{if $fields.payment.0.shipping_address.0.address_telephone} {$fields.payment.0.shipping_address.0.address_telephone}{/if}
						{if $fields.payment.0.shipping_address.0.address_telephone && $fields.payment.0.shipping_address.0.address_telephone} / {/if} 
						{if $fields.payment.0.shipping_address.0.address_mobile} {$fields.payment.0.shipping_address.0.address_mobile} {/if}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover" style="margin-top:15px;">
			<thead>
				<tr>
					<th>Description</th>
					<th style="text-align: right;">Qty</th>
					<th style="text-align: right;">Unit Price</th>
					<th style="text-align: right;">Subtotal</th>
				</tr>
			</thead>
			<tbody>
				{foreach $fields.items as $item}
				<tr>
					<td>{$item.cartitem_product_name} 
						{if $item.attributes} 
			  				{foreach $item.attributes as $attr}
			    				<small>/ {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</small>
		   					{/foreach}
		  				{/if}
		  			</td>
					<td style="text-align: right;">{$item.cartitem_quantity}</td>
					<td style="text-align: right;">${$item.cartitem_product_price|number_format:2:".":","}</td>
					<td style="text-align: right;">${$item.cartitem_subtotal|number_format:2:".":","}</td>
				</tr>
				{/foreach} 
				<tr>
					<td style="text-align: right;" colspan="3">Subtotal</td>
					<td style="text-align: right;">${$fields.cart_subtotal|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td style="text-align: right;" colspan="3">Discount {if $fields.cart_discount_code}<small>[Code: {$fields.cart_discount_code}]</small>{/if}</td>
					<td style="text-align: right;">-${$fields.cart_discount|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td style="text-align: right;" colspan="3">Postage & Handling</td>
					<td style="text-align: right;">${$fields.cart_shipping_fee|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td style="text-align: right;" colspan="3"><b>Total</b></td>
					<td style="text-align: right;"><b> ${$fields.cart_total|number_format:2:".":","}</b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row">
	<form class="well form-horizontal" id="" accept-charset="UTF-8" method="post">
		{if $cnt eq ""}{assign var=cnt value=0}{/if} 
		<input type="hidden" value="{$fields.cart_id}" name="field[1][tbl_cart][{$cnt}][cart_id]" id="cart_id"> 
					
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="id_cart_order_status">Order Status</label>
			<div class="col-sm-5">
				<select class="form-control" name="field[1][tbl_cart][{$cnt}][cart_order_status]" id="id_cart_order_status">
					<option value="0">Unverified</option> 
					<option value="1">Cancelled</option> 
					<option value="2">Verified</option> 
					<option value="3">Shipped</option> 
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right" style="margin-top: 50px;"> Save</a>
			</div>
		</div>
	</form>
</div>




<script type="text/javascript">


$(document).ready(function(){
	
});


</script>
{/block}
