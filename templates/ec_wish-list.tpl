{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}




{$product_cnt = 0}

<div id="wish-list">
  <div class="container">
    <div class="row" id="products-wrapper">
          {if $products}
          {foreach $products as $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
          {else}
            <div class="col-sm-12">You still haven't selected any products.</div>
          {/if}
    </div>
  </div>
</div>

{if $banner1 || $banner2 || $banner3}
<div id="specialoffer">
  <div class="container">
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
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/isotope.pkgd.min.js"></script>

<script type="text/javascript">

  $(document).ready(function() {
    
  $("#products-wrapper").isotope({
	  itemSelector: '.prodout',
	  layoutMode: 'fitRows',
	  getSortData: {
		  price: function( itemElem ) { // function
			var pr = ($( itemElem ).find('.prod-price').text().replace (/,/g, "").match(/\d+\.\d+|\d+\b|\d+(?=\w)/g) || [] );
			pr1= pr.map(function (v) { return +v; } ).shift();
			return pr1;
		}
	  }
   });
    
  });
</script>
{/block}

