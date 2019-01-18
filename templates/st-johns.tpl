{block name=head}
<style type="text/css">
#heroimg{
background-image: url('{$listing_image}');
background-size: cover;
}
</style>
{/block}

{block name=head}
<style type="text/css">

</style>
{/block}

{block name=body}
<div id="heroimg" class="safe-return">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 hidden-xs herotext">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>
<div id="offer" class="visible-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

<div id="pagehead" class="safe-return">
  <div class="container">
    <div class="row">
      <div class="{if $listing_object_id eq 10}col-md-12{else}col-md-8 col-md-offset-2{/if} text-center">
        <h1>{if $listing_title}{$listing_title}{else}{$listing_name}{/if}</h1>
        {$listing_content2}
      </div>
    </div>
  </div>
</div>

{if $listing_object_id eq 822}

<div class="container">
  <div class="row">
    <div class="col-sm-12 text-center" id="cform" >
      <h2>Refer a friend today</h2>
      <p>If someone you know could benefit from a MedicAlert membership, don't wait until it's too late to tell them about it. Simply fill out the form below and we'll get in touch with them with more information.</p>
    </div>
  </div>

</div>
<div id="contact" class="refer">
  <div class="container">
   <div class="row">
    <div class="col-md-offset-1 col-md-10 text-center {if $error}visible visible-md{/if}" id="referfriend">
      <form id="refer_friend_form" accept-charset="UTF-8" method="post" action="/process/refer-friend-senior" novalidate="novalidate">
        <input type="hidden" name="formToken" id="formToken" value="{$token}" />
        <input type="hidden" value="Refer a friend senior" name="form_name" id="form_name" />
        <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
        <div class="row">
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="name">Your name<span>*</span>:</label>
            <input class="form-control" value="{if $post.name}{$post.name}{else}{$user.gname} {$user.surname}{/if}" type="text" name="name" id="name" required="">
            <div class="error-msg help-block"></div>
          </div>
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="email">Your email<span>*</span>:</label>
            <input class="form-control" value="{if $post.email}{$post.email}{else}{$user.email}{/if}" type="email" name="email" id="email" required="">
            <div class="error-msg help-block"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="memberno">Membership number:</label>
            <input class="form-control" value="{if $post.memberno}{$post.memberno}{else}{$user.id}{/if}" type="text" name="memberno" id="memberno">
          <div class="error-msg help-block"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <hr />
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="frname">Your friend's name<span>*</span>:</label>
            <input class="form-control" value="{$post.friendname}" type="text" name="friendname" id="frname" required="">
          <div class="error-msg help-block"></div>
          </div>
          <div class="col-sm-6 form-group">
            <label class="visible-ie-only" for="fremail">Your friend's email<span>*</span>:</label>
            <input class="form-control" value="{$post.friendemail}" type="email" name="friendemail" id="fremail" required="">
          <div class="error-msg help-block"></div>
          </div>
        </div>
        <div class="row error-msg" id="form-error" {if !$error}style="display:none"{/if}>{$error}</div>
        <div class="row">
          <div class="col-sm-12">
            <input type="submit" value="Refer your friend now" class="btn-red btn" id="fbsub">
          </div>
        </div>
      </form>
    </div>
    <br><br>
  </div>
  {if !$error}
  <div class="row referbottom">
    <div class="col-md-offset-1 col-md-10 text-center">
      <button class="btn-red btn referbtn">Refer your friend now</button>
    </div>
  </div>
  {/if}
</div>
</div>
  </div>
</div>
{/if}

{if $listing_content2}
<div id="cost-grey" class="pinkh4">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        {$listing_content3}
      </div>
    </div>
  </div>
</div>
{/if}

{if $popular_products && $listing_flag1}
<div id="popular">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
      <br>
        <h2>Popular Products</h2>
      </div>
    </div>
    <div class="row">
      <div id="popslide" class="flexslider">
        <ul class="slides">
    {foreach $popular_products as $item}
          <li>
            <div class="prod">
                <a href="/{$item.product_url}"> <img src="{$item.general_details.image}?width=770&height=492&crop=1" alt="{$item.product_name} image" class="img-responsive" title="{$item.product_name} image" />
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
          <a href="/{$item.product_url}">{$item.product_name}</a>
          </div>
          <div class="prod-price">${$item.general_details.price.min|number_format:0:'.':','}{if $item.general_details.price.min neq $item.general_details.price.max} - ${$item.general_details.price.max|number_format:0:'.':','}{/if}</div>
          {if $item.general_details.has_attributes.2}
          {$hasColour = 0}
          {foreach $item.general_details.has_attributes.2 as $colour}{if $colour.values.attr_value_image}{$hasColour = 1}{break}{/if}{/foreach}
          {if $hasColour eq 1}
          <div class="colours">Available colours
            <div class="colourbox">
            {foreach $item.general_details.has_attributes.2 as $colour}
            <img src="{$colour.values.attr_value_image}?height=22&width=22" alt="{$colour.values.attr_value_name}" title="{$colour.values.attr_value_name}" />
            {/foreach}
          </div>
          </div>
          {/if}{/if}
        <div class="prod-wishlist"><a href="javascript:void(0)" title="Your wish list" data-pid="{$item.product_object_id}" class="prodwishlist prodwishlist-{$item.product_object_id}{if $item.product_object_id|in_array:$wishlist} active{/if}"><img src="/images/prod-wishlist{if $item.product_object_id|in_array:$wishlist}-selected{/if}.png" alt="Wishlist"></a></div>
            </div>
          </li>
    {/foreach}
        </ul>
      </div>
    </div>
  </div>
</div>
{else}
<div id="products">
  <div class="container">
    {if $products}
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Select your MedicAlert ID</h2>
        Choose from our range of genuine IDs
      </div>
      </div>
      <div class="row" id="products-wrapper">
          {$nofollowprod = 1}
          {foreach $products as $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
      </div>
      <div class="row">
      {if $listing_object_id neq 822}
      <div class="col-sm-12 text-center small">
        {$listing_content4}
      </div>
      {/if}
      <div class="col-sm-12 text-center" id="moreprods">
        <p>Can't find a product you like? See the <a href="/products?setdc={$discount_code}" rel="nofollow" style="color:#e02445;" title="View our range">full product range here</a> or call <a href="tel:{$COMPANY.toll_free}" title="Give us a call" class="phone">{$COMPANY.toll_free}</a>.</p>
      </div>
    </div>
    {/if}
  </div>
</div>
{/if}
<div class="container" id="landing-newsart">
  <div class="row">
  {if $latest_news_articles}
  {foreach $latest_news_articles as $a}
    <div class="col-sm-6 col-md-4">
      <div class="newsres">
        <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}">
          <img src="{if $a.listing_image}{$a.listing_image}{else}/images/medic-alert-logo.jpg{/if}" alt="{$a.listing_name}" class="img-responsive fullwidth">
        </a>
        <div class="newsrestext">
          <div class="date">{$a.news_start_date|date_format:"%d %B %Y"}</div>
          <h3>
            <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}">{$a.listing_name}</a>
          </h3>
          <div class="newstext">
            <p>
              {$a.listing_content1}
            </p>
          </div>
          <a href="{if $a.parent_listing_url}/{$a.parent_listing_url}{/if}/{$a.listing_url}" class="readart">Read article</a>
        </div>
      </div>
    </div>
  {/foreach}
  {/if}
  </div>
</div>
{/block}

{* Place additional javascript here so that it runs after General JS includes *}
{block name=tail}
{printfile file='/includes/js/jquery.flexslider-min.js' type='script'}
{printfile file='/includes/js/isotope.pkgd.min.js' type='script'}
{printfile file='/includes/js/jquery.lazyload.min.js' type='script'}
<script type="text/javascript">

  (function() {

    // store the slider in a local variable
    var $window = $(window), flexslider;

    // tiny helper function to add breakpoints
    function getGridSize() {
      return (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 2 : 4;
    }

    $window.load(function() {
      $('.flexslider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 20,
        minItems: getGridSize(), // use function to pull in initial value
        maxItems: getGridSize(),
    start: function(slider){
      flexslider = slider;
    }
      });
    });

    // check grid size on resize event
    $window.resize(function() {
      var gridSize = getGridSize();

      flexslider.vars.minItems = gridSize;
      flexslider.vars.maxItems = gridSize;
    });
  }());


  $(document).ready(function() {
    var hash = window.location.hash.toString();
    if(hash === '#cform'){
      $('.referbtn').hide();
    } else {
      $('#referfriend').hide();
    }

  $('#refer_friend_form').validate();

  $('.referbtn').on('click', function(){
    $('#referfriend').slideDown("slow", function(){
    });
    $(this).hide();
    });

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
</script>
{/block}

