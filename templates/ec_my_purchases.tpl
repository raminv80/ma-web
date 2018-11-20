{block name=head}
<style type="text/css">
  .prod { min-height: 540px; }
  .order_detail { min-height: 84px; }
  .product-form { margin-top: 10px;}
</style>
{/block} {block name=body}


<div id="pagehead">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1>{$listing_name}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>


<div>
  <div class="container">
    <div class="row" id="products-wrapper">
         <div class="col-sm-12 text-center" id="noproducts" {if $products}style="display:none;"{/if}>You haven't ordered any products.</div>
          {if $products}
          {foreach $products as $item}
            <div class="col-sm-6 col-md-4 col-lg-4 prodout show-all type-{$item.product_associate1} range-{$item.price_range} {if $item.general_details.sale.flag eq 1}range-sale{/if} {foreach $item.general_details.materials as $matk => $matv}material-{$matk} {/foreach} {foreach $item.general_details.has_attributes as $attr}{foreach $attr as $valid => $valval} attrval-{$valid}{/foreach}{/foreach} split {foreach $item.general_details.has_parent_attributes as $valid} attrval-{$valid}{/foreach}" data-order="{$k}">
              <div class="prod">
              <a href="/{$item.product_url}" title="{$item.product_name}" {if $nofollowprod}rel="nofollow"{/if}>
                <img src="{if $item.gallery.0.gallery_link neq ''}{$item.gallery.0.gallery_link}?width=770&height=492&crop=1{else}/images/no-image-available.jpg?width=770&height=492&crop=1{/if}" alt="{$item.gallery.0.gallery_alt_tag}" title="{$item.gallery.0.gallery_title}" class="img-responsive prodimg" />
              </a>
              <div class="prod-labels">
                    {if $item.general_details.limitedstock.flag eq 1}
                    <div class="prod-label btn btn-white">Limited stock</div>
                    {/if}
                    {if $item.general_details.new.flag eq 1}
                <div class="prod-label btn btn-white">New</div>
                    {/if}
                    {if $item.general_details.sale.flag eq 1}
                <div class="prod-label btn btn-red">Sale</div>
                    {/if}
              </div>
              <div class="prod-info">
                <div class="prod-name">
                  <a href="/{$item.product_url}" title="{$item.product_name}" {if $nofollowprod}rel="nofollow"{/if}>{$item.product_name}</a>
                </div>
                <div class="prod-price">${$item.variant_price|number_format:0:'.':','}</div>
                <div class="order_detail">
                  {foreach $item.attributes as $k => $attr}
                    {$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}<br/>
                  {/foreach}
                </div>
                <div class="prod-wishlist"><a href="javascript:void(0)" title="Your wish list" data-pid="{$item.product_object_id}" class="prodwishlist prodwishlist-{$item.product_object_id}{if $item.product_object_id|in_array:$wishlist} active{/if}"><img src="/images/prod-wishlist{if $item.product_object_id|in_array:$wishlist}-selected{/if}.png" alt="Wishlist"></a></div>
                <form class="form-horizontal product-form" id="product-form-{$item.cartitem_id}" role="form" accept-charset="UTF-8" action="" method="post">
                  <input type="hidden" class="product_fields" value="{$item.cartitem_product_id}" name="product_id" id="product_id" />
                  <input type="hidden" class="product_fields" value="" name="listname" id="listname" />
                  <input type="hidden" class="product_fields" value="{$item.variant_price}" name="price" id="price" />
                  <input type="hidden" class="product_fields" value="{$item.cartitem_variant_id}" name="variant_id" id="variant_id" />
                  {foreach $item.attributes as $k => $attr}
                    <input type="hidden" class="product_fields" value="{$attr.cartitem_attr_attr_value_id}" name="attr[{$attr.cartitem_attr_attribute_id}][id]" />
                    {if $attr.cartitem_attr_attr_value_additional neq ''}
                      {foreach $attr.additionals as $k => $additional}
                        <input type="hidden" class="product_fields" value="{$additional}" name="attr[{$attr.cartitem_attr_attribute_id}][additional][{$k}]" />
                      {/foreach}
                    {/if}
                  {/foreach}
                  <input type="hidden" class="product_fields" value="" name="unused_field" id="attr_cnt" />
                  <button class="btn btn-red addtocart" type="button">Reorder</button>
                  <a href="/{$item.product_url}?cid={$item.cartitem_id}" class="btn btn-red review" >Review</a>
                </form>
              </div>
              </div>
            </div>
          {/foreach}
          {/if}
          <form class="form-horizontal" id="product-form" role="form" accept-charset="UTF-8" action="" method="post">
            <input type="hidden" value="ADDTOCART" name="action" id="action" />
            <input type="hidden" name="formToken" id="formToken" value="{$token}" />
            <button class="btn btn-red" type="submit" style="display:none;">Add to Cart</button>
          </form>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <a href="/products" title="Click to view our full product range" class="btn-red btn">Shop now</a>
        <br>
        <br>
      </div>
    </div>
  </div>
</div>

{if $banner1 || $banner2 || $banner3}
<div id="specialoffer">
  <div class="container">
  <br>
  <hr>
  <br>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>Special offers just for you</h3>
      </div>
    </div>
    <div class="row text-center">
      {if $banner1}
            <div class="col-sm-4 specials">
              <a href="{$banner1.0.banner_link}" {if $banner1.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner1.0.banner_name}">
                <img src="{$banner1.0.banner_image2}" alt="{$banner1.0.banner_name}" class="img-responsive" />
              </a>
      </div>
            {/if}
            {if $banner2}
            <div class="col-sm-4 specials">
              <a href="{$banner2.0.banner_link}" {if $banner2.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner2.0.banner_name}">
                <img src="{$banner2.0.banner_image2}" alt="{$banner2.0.banner_name}" class="img-responsive" />
              </a>
            </div>
            {/if}
            {if $banner3}
            <div class="col-sm-4 specials">
              <a href="{$banner3.0.banner_link}" {if $banner3.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner3.0.banner_name}">
                <img src="{$banner3.0.banner_image2}" alt="{$banner3.0.banner_name}" class="img-responsive" />
              </a>
            </div>
            {/if}
      
    </div>
  </div>
</div>
{/if}


{/block} {block name=tail}
<script src="/node_modules/isotope-layout/dist/isotope.pkgd.min.js"></script>
<script src="/node_modules/jquery-lazyload/jquery.lazyload.js"></script>
<script type="text/javascript">

  $(document).ready(function() {
    
  var $grid = $("#products-wrapper").isotope({
    itemSelector: '.prodout',
    layoutMode: 'fitRows'
   });
  
  $grid.on( 'arrangeComplete', function( event, filteredItems ) {
    $(window).trigger("scroll");
  });
  

  $('img.prodimg').lazyload({
    effect: "fadeIn",
         failure_limit: Math.max($('img.prodimg').length - 1, 0),
         event: "scroll click"
  });
  

  });

  function triggerIsotopeLayout(){
    $("#products-wrapper").isotope('layout');
   }
  
//REFRESH ISOTOPE WHEN SCROLLING UP/DOWN
  var minLastView = $(document).height();
  var maxLastView = 0;
  $(window).scroll(function() {
    var curHeight = $(window).scrollTop() + $(window).height();
    if(curHeight < minLastView && Math.abs(curHeight - minLastView) > 500){
      minLastView = curHeight;
      $("#products-wrapper").isotope('layout');
    }
    if(curHeight > maxLastView && Math.abs(curHeight - maxLastView) > 500){
      maxLastView = curHeight;
      $("#products-wrapper").isotope('layout');
    }
 });
  
  var RunningSend = false;
  function SendWishList() {
    if(RunningSend){ return false; }
    $('#sendwishlist-btn').hide();
    $('#response-msg').html('Sending...');
    RunningSend = true;
    $('body').css('cursor', 'wait');
    $.ajax({
      type: "POST",
      url: "/process/cart",
      cache: false,
      data: "action=sendWishList",
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.url){
            window.location.href = obj.url;
          }else if(obj.success){
            $('#response-msg').html('Your wish list was sent to your email address.');
            
          }else if(obj.error){
            $('#response-msg').html(obj.error);
          }
          RunningSend = false;
        }catch(err){
          console.log('TRY-CATCH error');
          RunningSend = false;
        }
        $('body').css('cursor', 'default');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('body').css('cursor', 'default');
        console.log('AJAX error:' + errorThrown);
      }
    });
  }
  
  $(document).on('click', '.addtocart', function(){
    var htm = $(this).parent().html();
    $('#product-form').find('.product_fields').remove();
    $('#product-form').append(htm);
    $('#product-form').find('.addtocart').remove();
    $('#product-form').find('.review').remove();
    $('#product-form').submit();
  });
  
  
  $('#product-form').validate({
    onkeyup: false,
    onclick: false,
    submitHandler: function(form) {
      console.log($(this).attr('id'));
      addCart($(form).attr('id'));
    }
  });
</script>
{/block}

