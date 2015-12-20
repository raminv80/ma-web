{block name=body}

<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover" style="margin-top:40px;">
			<tbody>
				<tr>
					<td><b>Order No:</b></td>
					<td class="text-center">{$fields.payment.0.payment_transaction_no}</td>
					<td><b>Order placed:</b></td>
					<td class="text-center">{$fields.cart_closed_date|date_format:"%e %B %Y"}</td>
				</tr>
				<tr>
					<td><b>User's detail:</b></td>
					<td class="text-center">{$fields.user.0.user_gname} {$fields.user.0.user_surname} / {$fields.user.0.user_email}</td>
					<td><b>Payment status:</b></td>
					<td class="text-center {if $fields.payment.0.payment_status eq 'A'}text-success{else}text-danger{/if}"> 
						<b>{if $fields.payment.0.payment_status eq 'P'}PENDING{elseif $fields.payment.0.payment_status eq 'A'}APPROVED{elseif $fields.payment.0.payment_status eq 'R'}REFUNDED{elseif $fields.payment.0.payment_status eq 'C'}CANCELLED{else}{$fields.payment.0.payment_status}{/if}</b>
					</td>
				</tr>
				<tr>
					<td><b>Shipping method:</b></td>
					<td class="text-center">{$fields.payment.0.payment_shipping_method}</td>
					<td><b>Card:</b></td>
					<td class="text-center">{$fields.payment.0.payment_response_cardscheme}</td>
				</tr>
				<tr>
					<td><b>Billing address:</b></td>
					<td class="text-center">{$fields.payment.0.billing_address.0.address_name}</td>
					<td class="text-center" colspan="2">
						{$fields.payment.0.billing_address.0.address_line1} 
						{$fields.payment.0.billing_address.0.address_line2} 
						{$fields.payment.0.billing_address.0.address_suburb}, 
						{$fields.payment.0.billing_address.0.address_state}, 
						{$fields.payment.0.billing_address.0.address_country} 
						{$fields.payment.0.billing_address.0.address_postcode}. 
						{if $fields.payment.0.billing_address.0.address_telephone} {$fields.payment.0.billing_address.0.address_telephone}{/if}
						{if $fields.payment.0.billing_address.0.address_telephone && $fields.payment.0.billing_address.0.address_telephone}{/if} 
						{if $fields.payment.0.billing_address.0.address_mobile} / {$fields.payment.0.billing_address.0.address_mobile} {/if}
					</td>
				</tr>
				<tr>
					<td><b>Shipping address:</b></td>
					<td class="text-center"><strong>{$fields.payment.0.shipping_address.0.address_name}</strong></td>
					<td class="text-center" colspan="2">
						<strong>
						{$fields.payment.0.shipping_address.0.address_line1} 
						{$fields.payment.0.shipping_address.0.address_line2} 
						{$fields.payment.0.shipping_address.0.address_suburb}, 
						{$fields.payment.0.shipping_address.0.address_state}, 
						{$fields.payment.0.shipping_address.0.address_country} 
						{$fields.payment.0.shipping_address.0.address_postcode}. 
						{if $fields.payment.0.shipping_address.0.address_telephone} {$fields.payment.0.shipping_address.0.address_telephone}{/if}
						{if $fields.payment.0.shipping_address.0.address_telephone && $fields.payment.0.shipping_address.0.address_telephone}{/if} 
						{if $fields.payment.0.shipping_address.0.address_mobile} / {$fields.payment.0.shipping_address.0.address_mobile} {/if}
						</strong>
					</td>
				</tr>
				<tr>
					<td><b>Comments:</b></td>
					<td class="text-center" colspan="3">
						{$fields.payment.0.payment_shipping_comments} 
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover" style="margin-top:15px;">
			<thead>
				<tr>
					<th>Item</th>
					<th class="text-right">Qty</th>
					<th class="text-right">Unit price</th>
					<th class="text-right">Subtotal</th>
				</tr>
			</thead>
			<tbody>
				{foreach $fields.items as $item}
				<tr>
					<td>{if $item.cartitem_product_gst eq '0'}*{/if}{$item.cartitem_product_name} 
						{if $item.attributes} 
			  				{foreach $item.attributes as $attr}
			    				<small>/ {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</small>
		   					{/foreach}
		  				{/if}
		  			</td>
					<td class="text-right">{$item.cartitem_quantity}</td>
					<td class="text-right">${$item.cartitem_product_price|number_format:2:".":","}</td>
					<td class="text-right">${$item.cartitem_subtotal|number_format:2:".":","}</td>
				</tr>
				{/foreach} 
				<tr>
					<td class="text-right" colspan="3">Subtotal</td>
					<td class="text-right">${$fields.payment.0.payment_subtotal|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td class="text-right" colspan="3">Discount {if $fields.cart_discount_code}<small>[Code: {$fields.cart_discount_code}]</small>{/if}</td>
					<td class="text-right">-${$fields.payment.0.payment_discount|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td class="text-right" colspan="3">Shipping</td>
					<td class="text-right">${$fields.payment.0.payment_shipping_fee|number_format:2:".":","}</td>
				</tr>
				<tr>
					<td class="text-right" colspan="3">Total (excl. GST)</td>
					{assign var='totalExclGST' value=$fields.payment.0.payment_charged_amount - $fields.payment.0.payment_gst}
					<td class="text-right">(${$totalExclGST|number_format:2:".":","})</td>
				</tr>
				<tr>
					<td colspan="2"><small>(*) GST Free item.</small></td>
					<td class="text-right">Incl. GST</td>
					<td class="text-right">(${$fields.payment.0.payment_gst|number_format:2:".":","})</td>
				</tr>
				<tr>
					<td class="text-right" colspan="3"><b>Total</b></td>
					<td class="text-right"><b> ${$fields.payment.0.payment_charged_amount|number_format:2:".":","}</b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<form class="well form-horizontal" id="send_invoice_email" accept-charset="UTF-8" method="post">
<!--		<input type="hidden" value="{$fields.payment.0.payment_id}" name="payment_id" /> 
 		<input type="hidden" value="{$fields.payment.0.billing_address.0.address_id}" name="bill_ID" /> 
		<input type="hidden" value="{$fields.payment.0.shipping_address.0.address_id}" name="ship_ID" /> 
		<input type="hidden" value="{$fields.user.0.user_gname}" name="user[gname]" />
		<input type="hidden" value="{$fields.cart_id}" name="cart_id" />   -->
		<input type="hidden" value="{$fields.user.0.user_email}" name="email" id="email" /> 
		<input type="hidden" value="{$fields.payment.0.payment_invoice_email_id}" name="email_id" /> 
		<input type="hidden" name="formToken" id="formToken" value="{$token}" />
					
		<div class="row">
			<div class="col-sm-offset-3 col-sm-9">
				<a href="javascript:void(0);" onClick="sendInvoiceEmail();" id="send-btn" class="btn btn-info pull-right"><span class="glyphicon glyphicon-envelope"></span> Re-send Invoice</a>
			</div>
		</div>
	</form>
</div>

<div class="row">
	<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
		{if $cnt eq ""}{assign var=cnt value=0}{/if} 
		<input type="hidden" value="order_id" name="field[1][tbl_order][{$cnt}][id]" id="id" /> 
		<input type="hidden" value="{$fields.payment.0.order.0.order_payment_id}" name="field[1][tbl_order][{$cnt}][order_payment_id]" id="order_payment_id">
		<input type="hidden" value="{$admin.id}" name="field[1][tbl_order][{$cnt}][order_admin_id]" id="order_admin_id">
		
		<input type="hidden" value="payment_id" name="field[2][tbl_payment][{$cnt}][id]" id="id_payment" /> 
		<input type="hidden" value="{$fields.payment.0.order.0.order_payment_id}" name="field[2][tbl_payment][{$cnt}][payment_id]" id="payment_id">
		
		<input type="hidden" name="formToken" id="formToken" value="{$token}" />
		
<!-- 		<div class="row form-group">
			<label class="col-sm-3 control-label" for="payment_status">Payment Status</label>
			<div class="col-sm-5">
				<select class="form-control" name="field[2][tbl_payment][{$cnt}][payment_status]" id="payment_status">
						<option value="P" {if $fields.payment.0.payment_status eq 'P'}selected="selected"{/if}>Pending</option>
						<option value="A" {if $fields.payment.0.payment_status eq 'A'}selected="selected"{/if}>Approved</option>
						<option value="C" {if $fields.payment.0.payment_status eq 'C'}selected="selected"{/if}>Cancelled</option>
						<option value="R" {if $fields.payment.0.payment_status eq 'R'}selected="selected"{/if}>Refunded</option>
				</select>
			</div>
		</div>	 -->		
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="payment_shipping_tracking">Shipping Tracking</label>
			<div class="col-sm-5">
				<input class="form-control" value="{$fields.payment.0.payment_shipping_tracking}" name="field[2][tbl_payment][{$cnt}][payment_shipping_tracking]" id="payment_shipping_tracking">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="id_cart_order_status">Order Status</label>
			<div class="col-sm-5">
				<select class="form-control" name="field[1][tbl_order][{$cnt}][order_status_id]" id="order_status_id">
					{foreach $fields.options.status as $opt}
							<option value="{$opt.id}" {if $fields.payment.0.order.0.order_status_id eq $opt.id}selected="selected"{/if}>{$opt.value}</option>
					{/foreach} 
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
				<a href="javascript:void(0);" onClick="RunCallback()" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-envelope"></span> Save and Notify</a>
			</div>
		</div>
	</form>
</div>


{include file='jquery-validation.tpl'}

<script type="text/javascript">

$(document).ready(function(){

	$('#Edit_Record').validate();
	
});

function RunCallback(){
	if($('#Edit_Record').valid()){
		$('#Edit_Record').attr('data-callback','sendStatusEmail').submit();
	}else{
		alert('You need to fill out the form.');
	}
}

function sendInvoiceEmail(){
	var datastring = $("#send_invoice_email").serialize();
	$('body').css('cursor','wait');
	$('#send-btn').addClass('disabled');
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/send-invoice-email.php",
		cache: false,
		data: datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
	    		$('.notification').hide();
			 	if (obj.response) {
			 		$('#email-sent').slideDown();
					setTimeout(function(){
						$('#email-sent').slideUp();
			    	},10000);
				} else {
					$('#error').slideDown();
					setTimeout(function(){
						$('#error').slideUp();
			    	},10000);
				}
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default');
			$('#send-btn').removeClass('disabled');
	    },
		error: function(){
			$('body').css('cursor','default');
			$('#send-btn').removeClass('disabled');
			console.log('AJAX error');
      	}
	});
	
}

function sendStatusEmail(TO, PAYMENTID, STATUS){
	if(!TO) TO = 'email';
	if(!PAYMENTID) PAYMENTID = 'order_payment_id';
	if(!STATUS) STATUS = 'order_status_id';

	var email_to = encodeURIComponent( $('#'+ TO).val() );
	var content = encodeURIComponent( $('#'+ PAYMENTID).val() );
	var subject = encodeURIComponent( $('#'+ STATUS).val() );
	var datastring = 'action=OrderStatus&email='+email_to+'&payment_id='+content+'&status='+subject+'&formToken='+$('#formToken').val();
	$('body').css('cursor','wait');
	$('#send-btn').addClass('disabled');
	$.ajax({
		type: "POST",
	    url: "/admin/includes/processes/send-email.php",
		cache: false,
		async: false,
		data: datastring,
		dataType: "html",
	    success: function(data) {
	    	try{
	    		var obj = $.parseJSON(data);
			 	if (obj.response) {
				 	$('.notification').hide();
			 		$('#email-sent').slideDown();
					setTimeout(function(){
						$('#email-sent').slideUp();
			    	},10000);
				} else {
					$('#error').slideDown();
					setTimeout(function(){
						$('#error').slideUp();
			    	},10000);
				}
			 	
			}catch(err){
				console.log('TRY-CATCH error');
			}
			$('body').css('cursor','default');
			$('#send-btn').removeClass('disabled');
	    },
		error: function(){
			$('body').css('cursor','default');
			$('#send-btn').removeClass('disabled');
			console.log('AJAX error');
      	}
	});
}

</script>
{/block}
