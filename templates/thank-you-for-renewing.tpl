{block name=head}
<style type="text/css"></style>{/block}{block name=body}
<div id="pagehead">
  <div class="bannerout"> <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" /> </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
        <div class="col-sm-12 text-center">
          {if $orderNumber}
          <p style="color:#e9003b">Your order ID is: {$orderNumber}</p>{/if}
          <br> {$listing_content1}
        </div>
        <br>
        {if $user.maf.main.lifetime eq 1}
          <div class="accrow">
            <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
                <input type="hidden" value="ADDTOCART" name="action" id="action" />
                <input type="hidden" name="formToken" id="formToken" value="{$token}" />
                <input type="hidden" value="{$CONFIG_VARS.membership_card_product_id}" name="product_id" id="product_id" />
                <input type="hidden" value="{$CONFIG_VARS.membership_card_variant_id}" data-value="{$CONFIG_VARS.membership_card_cost}" name="variant_id" id="variant-{$CONFIG_VARS.membership_card_variant_id}">
                <div class="col-sm-12">
                  If you'd like to order an additional membership card please <a href="javascript:void(0);" class="order_card">click here</a>.<br><br>
                </div>
            </form>
          </div>
          {/if}
      </div>
      <br>
      <div class="row">
        <div class="col-sm-12 text-center">
          <a class="btn-red btn" title="UPDATE MEMBERSHIP PROFILE" href="/products">START SHOPPING</a><br><br>
        </div>
      </div>
      <br><br>
    </div>
  </div>{if $listing_content2}
  <div id="cost-grey" class="howto">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center"> {$listing_content2} </div>
      </div>
    </div>
  </div>{/if}{if $listing_content3 || $listing_content4}
  <div>
    <div class="container"> <br>
      <div class="row">
        <div class="col-md-12 text-center"> {$listing_content3} </div>
      </div> <br>
      <div class="row">
        <div class="col-md-12 text-center"> {$listing_content4} </div>
      </div>
    </div>
  </div>{/if}{/block}{* Place additional javascript here so that it runs after General JS includes *}{block name=tail}
  <script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '.order_card', function(){
      $('#product-form').submit();
    });
    //Order card form
    $('#product-form').validate({
       submitHandler: function(form) {
         addCart($(form).attr('id'), true);
       }
     });
  });
  </script>{/block}
