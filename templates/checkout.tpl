{block name=body}
<div class="container_16">
        <div class="grid_16">
        	<div class="content-wrapper">
        	{$content}
        	</div>
        	<div class="cart-wrapper">
        	{include file='checkout-cart.tpl'}
        	</div>
            <form id="emailform" name="emailform" action="/includes/processes/processes-cart.php" method="post">
            	<table cellpadding="0" cellspacing="0" border="0" class="order">	
                	<tr>	
                    	<td class="checkout-title">Name*:</td>
                    	<td class="checkout-input"><input type="text" id="name" name="name" class="req" /></td>
                    </tr>
                	<tr>	
                    	<td class="checkout-title">Email*:</td>
                    	<td class="checkout-input"><input type="text" id="email" name="email" class="email req"/></td>
                    </tr>
                    <tr>	
                    	<td class="checkout-title">Company*:</td>
                    	<td class="checkout-input"><input type="text" id="company" name="company" class="req"/></td>
                    </tr>
                    <tr>	
                    	<td class="checkout-title">Telephone(Office)*:</td>
                    	<td class="checkout-input"><input type="text" id="tel_office" name="tel_office" class="req"/></td>
                    </tr>
                    <tr>	
                    	<td class="checkout-title">Telephone(Mobile)*:</td>
                    	<td class="checkout-input"><input type="text" id="tel_mobile" name="tel_mobile" class="req"/></td>
                    </tr>
                	<tr valign="top">	
                    	<td class="checkout-title">Additional notes:</td>
                    	<td class="checkout-input"><textarea id="notes" name="notes" ></textarea></td>
                    </tr>
                    <tr valign="top">	
                    	<td class="checkout-title">Method:</td>
                    	<td class="checkout-input"><select id="method" name="method" >{if $delivery}<option value="delivery">Delivery</option>{/if}<option value="pickup">Pickup</option></select></td>
                    </tr>
                    <tr valign="top">	
                    	<td class="checkout-title">Address:</td>
                    	<td class="checkout-input"><textarea id="address" name="address" ></textarea></td>
                    </tr>
                    <tr valign="top">	
                    	<td class="checkout-title">Store:</td>
                    	<td class="checkout-input">
                    	<select id="store" name="store" >
                    	{foreach key=key item=store from=$stores}
                    		<option value="{$store.id}">{$store.name}</option>
                    	{/foreach}
                    	</select>
                    	</td>
                    </tr>
                    <tr valign="top">	
                    	<td class="checkout-title">Delivery*:</td>
                    	<td class="checkout-input">Time: <input class="timepicker req" id="time" name="time"/> Date: <input class="datepicker req" id="date" name="date"/>
                    	<script type="text/javascript">
                    	$(document).ready(function(){
                    		$( '.timepicker' ).timepicker( { showPeriod: true, showLeadingZero: true } );
                    		$( '.datepicker' ).datepicker( { changeMonth: true, changeYear: true, yearRange: 'c-90:c+10', dateFormat: 'dd-mm-yy' } );
                        });
                    	</script>
                    	</td>
                    </tr>
                    <tr class="confirm-btn">
                    	<td>&nbsp;
                    	<input type="hidden" name="Action" value="EmailOrder" />
                    	</td>
                    	<td><a href="javascript:void(0);" class="submit submit-btn" id="submit-btn" title="Submit">Confirm</a></td>
                    </tr>
                    <tr>
                    	<td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </form>
            <script type="text/javascript">
            $(document).ready(function(){
				$('.submit-btn').click(function(){
					submitForm();
				});
            });
            function submitForm(){
                $('.submit-btn').unbind('click');
            	if(validateForm('#emailform')){
            		$('.submit-btn').click(function(){
    					submitForm();
    				});
            	}else{
					$('#emailform').submit();
            	}
            }
            </script>
            <script type="text/javascript" src="/includes/js/validation.js"></script>	
            <div class="clear"></div>
        </div><!-- end .grid_16 -->
        <div class="clear"></div>
    </div><!-- end .container_16 -->
{/block}