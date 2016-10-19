{block name=head}
<style type="text/css">
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


<div id="wish-list">
  <div class="container">
    <div class="row" id="products-wrapper">
         <div class="col-sm-12 text-center" id="noproducts" {if $products}style="display:none;"{/if}>You still haven't selected any products.</div>
          {if $products}
          {foreach $products as $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
          {/if}
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <a href="/products" title="Click to view our full product range" class="btn-red btn">Shop now</a>
        <br>
        <br>
        <b id="response-msg"></b>
        <a href="javascript:void(0)" onclick="SendWishList()" title="Send email" id="sendwishlist-btn" class="">Email me my wish list ></a>
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
<script src="/includes/js/isotope.pkgd.min.js"></script>
<script type="text/javascript">

  $(document).ready(function() {
    
  $("#products-wrapper").isotope({
	  itemSelector: '.prodout',
	  layoutMode: 'fitRows'
   });

  });
  
//REFRESH ISOTOPE WHEN SCROLLING UP/DOWN
  var minLastView = $(document).height();
  var maxLastView = 0;
  $(window).scroll(function() {
    var curHeight = $(window).scrollTop() + $(window).height();
    if(curHeight < minLastView && Math.abs(curHeight - minLastView) > 500){
      minLastView = curHeight;
      $("#products-wrapper").isotope('reloadItems' ).isotope();
    }
    if(curHeight > maxLastView && Math.abs(curHeight - maxLastView) > 500){
      maxLastView = curHeight;
      $("#products-wrapper").isotope('reloadItems' ).isotope();
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
</script>
{/block}

