{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}
<div id="pagehead">
  {if $listing_image}
  <div class="bannerout">
    <img src="{$listing_image}" alt="{$listing_name} banner" />
  </div>
  {/if}
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1>{$listing_name}</h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>


<div id="product-list">
  <div class="container" id="prolist">
    <div class="row">
      {if $data}
      <div id="categorycontainer">
        {assign var='count' value=0} {foreach $data.10 as $item}{if $item.listing_flag1 neq 1}{continue}{/if}
        <div class="{if $count <2}col-sm-6{else}col-sm-4{/if} prodcatout text-center">
          <div class="prodcat">
            <a href="{$listing_url}/{$item.listing_url}" title="View {$item.listing_name} Products">
              <div class="imgcont">
                <img src="{if $item.listing_image neq ''}{$item.listing_image}{else}/images/no-image-available.png{/if}?width=800&height=400&crop=1" alt="{$item.listing_name}" title="{$item.listing_name}" class="img-responsive">
              </div>
              <div class="prodcat-txt prodlt">
                Shop <span>{$item.listing_name}</span>
              </div>
            </a>
          </div>
        </div>
        {assign var='count' value=$count+1} {/foreach}
      </div>
      {/if}
    </div>
    <!-- <div class="row">
      <div class="col-sm-12 text-center">
        <div class="btn btn-red" id="showall">Show all collections</div>
      </div>
    </div> -->
  </div>
</div>

{if $popular_products}
<div id="popular">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
      <br><br>
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
					<div class="colours">Available colours
						<div class="colourbox">
						{foreach $item.general_details.has_attributes.2 as $colour}
						<img src="{$colour.values.attr_value_image}?height=22&width=22" alt="{$colour.values.attr_value_name}" title="{$colour.values.attr_value_name}" />
						{/foreach}
					</div>
					</div>
					{/if}
				<div class="prod-wishlist"><img src="/images/prod-wishlist.png" alt="Wishlist"></div>
            </div>
          </li>
		{/foreach}
        </ul>
      </div>
    </div>
  </div>
</div>
{/if}

<div id="categ-bot" class="hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-sm-7">
        <h2>More than just medical identification jewellery</h2>
        <p>When you purchase a genuine MedicAlert medical ID, you also invest in a vital MedicAlert membership. This gives you access to a range of exclusive benefits.</p>
        <a href="/benefits-of-membership" class="btn btn-red">Learn more</a>
      </div>
    </div>
  </div>
</div>

<div id="categ-bot-mob" class="visible-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        <a href="/benefits-of-membership">More than just medical identification jewellery <span>+</span></a>
      </div>
    </div>
  </div>
</div>

{/block} {block name=tail}
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#showall").click(function() {
      $(this).hide();
      $("#product-list #categorycontainer .prodcatout").css("display", "block !important");
    });
  });


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
</script>
{/block}

