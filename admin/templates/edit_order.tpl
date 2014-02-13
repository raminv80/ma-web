{block name=body}

<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-offset-1">
				Order No: <b> {$fields.cart_id}</b>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1">
				Order placed: <b> {$fields.cart_closed_date|date_format:"%e %B %Y"}</b>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1">
				User's Detail: <b>{$fields.user.0.user_gname} {$fields.user.0.user_surname} / {$fields.user.0.user_email}</b>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1">
				Billing Address: <b>{$fields.payment.0.billing_address.0.address_name}, 
									{$fields.payment.0.billing_address.0.address_line1} 
									{$fields.payment.0.billing_address.0.address_line2} 
									{$fields.payment.0.billing_address.0.address_suburb}, 
									{$fields.payment.0.billing_address.0.address_state}, 
									{$fields.payment.0.billing_address.0.address_country} 
									{$fields.payment.0.billing_address.0.address_postcode}. 
									{if $fields.payment.0.billing_address.0.address_telephone} {$fields.payment.0.billing_address.0.address_telephone}{/if}
									{if $fields.payment.0.billing_address.0.address_telephone && $fields.payment.0.billing_address.0.address_telephone} / {/if} 
									{if $fields.payment.0.billing_address.0.address_mobile} {$fields.payment.0.billing_address.0.address_mobile} {/if}</b>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1">
				Shipping Address: <b>{$fields.payment.0.shipping_address.0.address_name}, 
									{$fields.payment.0.shipping_address.0.address_line1} 
									{$fields.payment.0.shipping_address.0.address_line2} 
									{$fields.payment.0.shipping_address.0.address_suburb}, 
									{$fields.payment.0.shipping_address.0.address_state}, 
									{$fields.payment.0.shipping_address.0.address_country} 
									{$fields.payment.0.shipping_address.0.address_postcode}. 
									{if $fields.payment.0.shipping_address.0.address_telephone} {$fields.payment.0.shipping_address.0.address_telephone}{/if}
									{if $fields.payment.0.shipping_address.0.address_telephone && $fields.payment.0.shipping_address.0.address_telephone} / {/if} 
									{if $fields.payment.0.shipping_address.0.address_mobile} {$fields.payment.0.shipping_address.0.address_mobile} {/if}</b>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-offset-1">
				Payment Status: <b>{if $fields.payment.0.payment_status eq 'P'}PAID{else}{$fields.payment.0.payment_status}{/if}</b>
			</div>
		</div>	
		
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
					<td style="text-align: right;">${$fields.cart_discount|number_format:2:".":","}</td>
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






<script type="text/javascript">


$(document).ready(function(){
	
});


</script>
{/block}
