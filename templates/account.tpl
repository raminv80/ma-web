{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="maincont">
	<div class="container">
		<div class="row">
			<div class="col-sm-12" id="breadcrumbs">{include file='breadcrumbs.tpl'}</div>
			<div class="col-sm-12">
				<h3>{$listing_name}</h3>
			</div>
			<div class="col-sm-12">
				{if $error}
				<div class="alert alert-danger fade in">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
					<strong>{$error}</strong>
				</div>
				{/if} {if $notice}
				<div class="alert alert-success fade in">
					<button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
					<strong>{$notice}</strong>
				</div>
				{/if}
				<h4>Welcome back {$user.gname}</h4>
				<div id="tab-container">
					<ul class="nav nav-tabs" id="account-tab">
						<li class="active"><a href="#orders" data-toggle="tab">My Order History</a></li> {if !$user.social_id}
						<!-- <li><a href="#details" data-toggle="tab">Update My Details</a></li> -->
						<li><a href="#password" data-toggle="tab">Change My Password</a></li> {/if}
					</ul>

					<div class="tab-content">
						<!--===+++===+++===+++===+++===+++ ORDER HISTORY TAB +++===+++===+++===+++===+++====-->
						<div class="tab-pane active" id="orders">
							<div class="tab-content-open">

								{if $orders }
								<div id="orders-list">
									<div class="panel-group" id="accordion">
										{foreach $orders as $order}
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#{$order.payment_transaction_no}"> <b>Order No.</b> {$order.payment_transaction_no} | <b>Placed date:</b> {$order.cart_closed_date|date_format:"%d %b %Y"}
													</a>
												</h4>
											</div>
											<div id="{$order.payment_transaction_no}" class="panel-collapse collapse">
												<div class="panel-body">
													<table cellspacing="0" width="100%" cellpadding="0" border="0">
														<tr align="left">
															<th>Items</th>
															<th class="text-right">Qty</th>
															<th style="min-width:65px;" class="text-right">Unit Price</th>
															<th style="min-width:70px;" class="text-right">Total Price</th>
														</tr>
														{foreach $order.items as $item}
														<tr valign="top" aling="left">
															{assign var=free value=0}
															<td>{if $item.cartitem_product_gst eq '0'} {assign var=free value=1} *{/if}{$item.cartitem_product_name} {if $item.attributes} {foreach $item.attributes as $attr} <small>/ {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}</small> {/foreach} {/if}
															</td>
															<td width="10%" class="text-right">{$item.cartitem_quantity}</td>
															<td width="20%" class="text-right">$ {$item.cartitem_product_price|number_format:2:".":","}</td>
															<td width="20%" class="text-right">$ {$item.cartitem_subtotal|number_format:2:".":","}</td>
														</tr>
														{/foreach}
														<tr valign="top" align="left">
															<td colspan="4"><hr></td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="3" class="text-right"><strong>Sub Total</strong></td>
															<td style="text-align: right">$ {$order.payment_subtotal|number_format:2:".":","}</td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="3" class="text-right"><strong>Discount</strong></td>
															<td class="text-right">$ -{$order.payment_discount|number_format:2:".":","}</td>
														</tr>
														<tr valign="top" align="left">
															<td>{if $free}(*)GST free item.{/if}</td>
															<td colspan="2" class="text-right"><strong>Shipping</strong></td>
															<td style="text-align: right">$ {$order.payment_shipping_fee|number_format:2:".":","}</td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="4"><hr></td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="3" class="text-right">Total (excl. GST)</td>
															{assign var='totalExclGST' value=$order.payment_charged_amount - $order.payment_gst}
															<td style="text-align: right">($ {$totalExclGST|number_format:2:".":","})</td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="3" class="text-right">GST</td>
															<td style="text-align: right">($ {$order.payment_gst|number_format:2:".":","})</td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="3" class="text-right"><strong>TOTAL</strong></td>
															<td style="text-align: right"><strong>$ {$order.payment_charged_amount|number_format:2:".":","}</strong></td>
														</tr>
														<tr valign="top" align="left">
															<td colspan="4"><hr></td>
														</tr>
													</table>
													<div class="col-sm-12">
														<div class="col-sm-6">
															<div>
																<strong>Billing Address</strong>
															</div>
															<div>{$order.billing.address_name}</div>
															<div>{$order.billing.address_line1} {$order.billing.address_line2}</div>
															<div>{$order.billing.address_suburb}, {$order.billing.address_state}</div>
															<div>{$order.billing.address_country} {$order.billing.address_postcode}</div>
															<div>{$order.billing.address_telephone}</div>
															<div>{$order.billing.address_mobile}</div>
														</div>
														<div class="col-sm-6">
															<div>
																<strong>Shipping Address</strong>
															</div>
															<div>{$order.shipping.address_name}</div>
															<div>{$order.shipping.address_line1} {$order.shipping.address_line2}</div>
															<div>{$order.shipping.address_suburb}, {$order.shipping.address_state}</div>
															<div>{$order.shipping.address_country} {$order.shipping.address_postcode}</div>
															<div>{$order.shipping.address_telephone}</div>
															<div>{$order.shipping.address_mobile}</div>
															<strong>Shipping Method:</strong> {$order.payment_shipping_method}
														</div>
													</div>
												</div>
											</div>
										</div>
										{/foreach}
									</div>
								</div>
								{else} No orders found. {/if}


							</div>
						</div>

						{if !$user.social_id}
						<!--===+++===+++===+++===+++===+++ UPDATE DETAILS TAB +++===+++===+++===+++===+++====-->
						<!-- <div class="tab-pane" id="details">
              <div class="tab-content-open">
            <form class="form-horizontal" id="update-details-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
              <input type="hidden" value="updateDetails" name="action" id="action" /> 
              <input type="hidden" name="formToken" id="formToken" value="{$token}" />
              <div class="form-group">
                  <label for="gname" class="col-sm-3 control-label">Given Name</label>
                  <div class="col-sm-4">
                      <input type="text" value="{if $post}{$post.gname}{else}{$user.gname}{/if}" class="form-control" id="gname" name="gname" required>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group">
                  <label for="surname" class="col-sm-3 control-label">Surname</label>
                  <div class="col-sm-4">
                      <input type="text" value="{if $post}{$post.surname}{else}{$user.surname}{/if}" class="form-control" id="surname" name="surname" required>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                      <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
            </form>
          </div>
        </div> -->

						<!--===+++===+++===+++===+++===+++ CHANGE PASSWORD TAB +++===+++===+++===+++===+++====-->
						<div class="tab-pane" id="password">
							<div class="tab-content-open">
								<form class="form-horizontal" id="update-pass-form" role="form" accept-charset="UTF-8" action="/process/user" method="post">
									<input type="hidden" value="updatePassword" name="action" id="action" /> <input type="hidden" name="formToken" id="formToken" value="{$token}" />
									<div class="form-group">
										<label for="password" class="col-sm-3 control-label">Current Password</label>
										<div class="col-sm-4">
											<input type="password" value="" class="form-control" id="old_password" name="old_password" autocomplete="off" required> <a class="showhide" style="line-height: 34px; position: absolute; right: 20px; top: 0;" href="javascript:void(0);"
												onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=old_password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=old_password]').get(0).type='password';$(this).html('Show'); }">Show</a> <span class="help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label for="password" class="col-sm-3 control-label">New Password</label>
										<div class="col-sm-4">
											<input type="password" value="" class="form-control" id="password2" name="password" autocomplete="off" required> <a class="showhide" style="line-height: 34px; position: absolute; right: 20px; top: 0;" href="javascript:void(0);"
												onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=password]').get(0).type='password';$(this).html('Show'); }">Show</a> <span class="help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label for="confirm_password" class="col-sm-3 control-label">Re-enter New Password</label>
										<div class="col-sm-4">
											<input type="password" class="form-control" value="" id="confirm_password2" name="confirm_password" autocomplete="off" required> <a class="showhide" style="line-height: 34px; position: absolute; right: 20px; top: 0;" href="javascript:void(0);"
												onclick="if($(this).html() == 'Show'){ $(this).closest('div').find('input[name=confirm_password]').get(0).type='text';$(this).html('Hide'); }else{ $(this).closest('div').find('input[name=confirm_password]').get(0).type='password';$(this).html('Show'); }">Show</a> <span class="help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<button type="submit" class="btn btn-primary">Update</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						{/if}
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
{/block} {* Place additional javascript here so that it runs after General JS includes *} {block name=tail}
<script type="text/javascript">
	$(document)
			.ready(
					function() {

						$('#update-pass-form').validate();
						$('#update-details-form').validate();

						$('#confirm_password2')
								.rules(
										"add",
										{
											required : true,
											equalTo : '#password2',
											messages : {
												equalTo : "The passwords you have entered do not match. Please check them."
											}
										});

					});
</script>
{/block}
