{block name=head}
<style type="text/css">
</style>
{/block} {block name=body}


<div id="ad">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 visible-xs visible-sm">
        <a href="/products" class="breadcrumbs1" title="Back to collections">< Back to collections</a>
      </div>
      {if $banner}
      <div class="col-sm-12">
        <a href="{$banner.0.banner_link}" {if $banner.0.banner_link|strstr:"http"} target="_blank"{/if} title="{$banner.0.banner_name}">
          <img src="{$banner.0.banner_image1}" alt="{$banner.0.banner_name}" class="img-responsive hidden-xs" />
          <img src="{$banner.0.banner_image2}" alt="{$banner.0.banner_name}" class="img-responsive visible-xs" />
        </a>
      </div>
      {/if}
      <div class="col-sm-12 visible-xs visible-sm text-center" id="mobcont">
        <h1>
          Shop <span>{$listing_name}</span>
        </h1>
        {$listing_content1}
      </div>
    </div>
  </div>
</div>

{$product_cnt = 0}
{if $products && count($products)}{$product_cnt = count($products)}{/if}
<div id="prodcatdet" data-listing-object-id="{$listing_object_id}" data-saved-filters="{$tempvars.filters.$listing_object_id}">
  <div class="container">
    <div class="row">
	  <div class="col-sm-12">
		  <a href="/products" class="breadcrumbs hidden-xs hidden-sm" title="Back to collections">< Back to collections</a>
	  </div>
      <div class="col-md-3" id="collection-left">
        <!-- COLLECTIONS -->
		<div class="panel panel-default">
				<div class="panel-heading mainhead" role="tab" id="accordion1">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collections" aria-expanded="true" aria-controls="collections">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title">Collections</div>
							</div>
						</a>
					</h4>
				</div>
				<div id="collections" class="panel-collapse mainhead collapse" role="tabpanel" aria-labelledby="collections">
					<div class="panel-body">
						{foreach $collections as $c}
						<div class="collection-opts">
						<a href="/products/{$c.listing_url}" title="Click to view collection">{$c.listing_name}</a> ({$c.cnt} item{if $c.cnt gt 1}s{/if})
						</div>
						{/foreach}
					</div>
				</div>
		</div>

        <!-- FILTERS -->
		<div class="panel panel-default">
				<div class="panel-heading mainhead" role="tab" id="accordion2">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#narrow" aria-expanded="true" aria-controls="narrow">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title"><span id="prodcnt-mob" class="visible-xs visible-sm">Filter {$product_cnt} product{if $product_cnt gt 1}s{/if}</span><span class="hidden-xs hidden-sm">Narrow your results</span></div>
							</div>
						</a>
					</h4>
				</div>
				<div id="narrow" class="panel-collapse mainhead collapse prod-filters-wrapper" role="tabpanel" aria-labelledby="narrow">
					<div class="panel-body">
                        <a href="javascript:void(0)" onclick="ResetFilters()">Reset filters</a> <span>({$product_cnt} item{if $product_cnt gt 1}s{/if})</span>
            <!---Type-->
            <div class="prod-filters">
				<div class="panel-heading subhead" role="tab" id="accordion3">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion2" href="#type" aria-expanded="true" aria-controls="narrow">
							<i class="more-less glyphicon glyphicon-plus"></i>
							<div class="head-text">
								<div class="head-title">Type</div>
							</div>
						</a>
					</h4>
				</div>
				<div id="type" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="type">
					<div class="panel-body">
				        {foreach $ptypes as $k => $v}
				          <div class="">
				            <input type="checkbox" id="ptype{$k}" name="ptype[]" class="iso-filter" value="type-{$k}"> <label for="ptype{$k}">{$v.name}</label> (<span class="cnt-value">{$v.cnt} item{if $v.cnt gt 1}s{/if}</span>)
				          </div>
				        {/foreach}
					</div>
				</div>
            </div>
            <!---Material-->
            <div class="prod-filters">
              <div class="panel-heading subhead" role="tab" id="accordion4">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#material" aria-expanded="true" aria-controls="narrow">
                    <i class="more-less glyphicon glyphicon-plus"></i>
                    <div class="head-text">
                      <div class="head-title">Material</div>
                    </div>
                  </a>
                </h4>
              </div>
              <div id="material" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="material">
                <div class="panel-body">
                      {foreach $pmaterials as $k => $v}
                        <div class="">
                          <input type="checkbox" id="pmaterial{$k}" name="pmaterial[]" class="iso-filter" value="material-{$k}"> <label for="pmaterial{$k}">{$v.name}</label> (<span class="cnt-value">{$v.cnt} item{if $v.cnt gt 1}s{/if}</span>)
                        </div>
                      {/foreach}
                </div>
              </div>
            </div>
            <!---Colour-->
            <div class="prod-filters">
              <div class="panel-heading subhead" role="tab" id="accordion5">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#colour" aria-expanded="true" aria-controls="narrow">
                    <i class="more-less glyphicon glyphicon-plus"></i>
                    <div class="head-text">
                      <div class="head-title">Colour</div>
                    </div>
                  </a>
                </h4>
              </div>
              <div id="colour" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="colour">
                <div class="panel-body">
                      {foreach $attributes.2.values as $k => $v}
                        <div class="">
                          <input type="checkbox" id="colour{$k}" name="colour[]" class="iso-filter" value="attrval-{$k}"> <label for="colour{$k}">{$v.name}</label> (<span class="cnt-value">{$v.cnt} item{if $v.cnt gt 1}s{/if}</span>)
                        </div>
                      {/foreach}
                </div>
              </div>
            </div>
            <!---Price-->
            <div class="prod-filters">
              <div class="panel-heading subhead" role="tab" id="accordion6">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#price" aria-expanded="true" aria-controls="narrow">
                    <i class="more-less glyphicon glyphicon-plus"></i>
                    <div class="head-text">
                      <div class="head-title">Price</div>
                    </div>
                  </a>
                </h4>
              </div>
              <div id="price" class="panel-collapse collapse subhead" role="tabpanel" aria-labelledby="price">
                <div class="panel-body">
                      {foreach $prices as $k => $v}
                        <div class="">
                          <input type="checkbox" id="price{$k}" name="price[]" class="iso-filter" value="range-{$v.value}"> <label for="price{$k}">{$v.name}</label> (<span class="cnt-value">{$v.cnt} item{if $v.cnt gt 1}s{/if}</span>)
                        </div>
                      {/foreach}
                </div>
              </div>
              </div>

			</div>
		  </div>
		</div>


        <h3></h3>

      </div>

      <div class="col-md-9">
        <!-- CATEGORY HEADER -->
 	    <div class="row hidden-xs hidden-sm">
          <div class="col-sm-{if $listing_image}6{else}12{/if}">
            <h1>Shop <span>{$listing_name}</span></h1>
            {$listing_content1}
          </div>
          {if $listing_image}
          <div class="col-sm-6">
            <img src="{$listing_image}?width=800&height=440&crop=1" alt="{$listing_name} banner" class="img-responsive" />
          </div>
          {/if}
        </div>

        <!-- PRODUCTS -->
        <div class="row" id="products-head">
	        <div class="col-md-6 hidden-xs hidden-sm" id="prodcnt">
		        <span>{$product_cnt}</span> product{if $product_cnt gt 1}s{/if}
	        </div>
	        <div class="col-md-6 text-right">
		        <div class="sortlab">Sort by:</div>
		        <select id="sort">
			        <option value="">Please select</option>
			        <option value="price-low-high">Price (low to high)</option>
			        <option value="price-high-low">Price (high to low)</option>
		        </select>
	        </div>
        </div>
        <div class="row" id="products-wrapper">
          {if $products}
          {foreach $products as $k => $item}
            {include file='ec_product_list_struct.tpl'}
          {/foreach}
          {else}
            <div class="col-sm-12">There are no products for this collection.</div>
          {/if}
        </div>
      </div>
    </div>
  </div>
</div>

{if $recent_products}
<div id="recent">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Recently viewed items</h2>
      </div>
    </div>
    <div class="row">
      <div id="popslide" class="flexslider">
        <ul class="slides">
          {foreach $recent_products as $k => $item}
          <li>
            <div class="prod">
              <a href="/{$item.product_url}" title="{$item.product_name}"> <img src="{if $item.general_details.image}{$item.general_details.image}{else}/images/no-image-available.png{/if}?width=568&height=363&crop=1" alt="{$item.product_name}" class="img-responsive" />
              </a>
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
        <a href="/benefits-of-membership" title="Benefits of membership" class="btn btn-red">Learn more</a>
      </div>
    </div>
  </div>
</div>

<div id="categ-bot-mob" class="visible-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center">
        <a href="/benefits-of-membership" title="Benefits of membership">More than just medical identification jewellery <span>+</span></a>
      </div>
    </div>
  </div>
</div>

{/block} {block name=tail}
<script src="/includes/js/jquery-ui.js"></script>
<script src="/includes/js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript" src="/includes/js/jquery.flexslider-min.js"></script>
<script src="/includes/js/isotope.pkgd.min.js"></script>

<script type="text/javascript">
	function toggleIconMain(e) {
        $(e.target)
            .prev('.panel-heading.mainhead')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-collapse.mainhead').on('hidden.bs.collapse', toggleIconMain);
    $('.panel-collapse.mainhead').on('shown.bs.collapse', toggleIconMain);
    
    function toggleIconSub(e) {
      $(e.target)
          .prev('.panel-heading.subhead')
          .find(".more-less")
          .toggleClass('glyphicon-plus glyphicon-minus');
  	}
    $('.panel-collapse.subhead').on('hidden.bs.collapse', toggleIconSub);
    $('.panel-collapse.subhead').on('shown.bs.collapse', toggleIconSub);

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
  
  	if($('#prodcatdet').attr('data-saved-filters')){
  	  var filterArr = $('#prodcatdet').attr('data-saved-filters').split('.'); 
  	  $.each(filterArr, function(k1, v1){
  	    $('.iso-filter').each(function(k2, v2){
         if(v1 == $(v2).val()) {
           $(v2).attr('checked', 'checked');
           return false;
         }
       	});
  	  });
  	  filterOptions(true);
  	}

    refreshFiltersCount();

    $("select").selectBoxIt();

    if($(window).width() >992){
		$('.collapse').each(function (index) {
		    $(this).collapse("toggle");
		});
	}
    
    $('.iso-filter').click(function(){
      filterOptions();
    });
    

    $("#sort").val("price-low-high").change();

    $("#products-wrapper").isotope({ sortBy: "price",sortAscending: true } );
    
  });

  var runningIsotope = false;
  function filterOptions(SKIPSAVE){
    
    if(runningIsotope) {
      return false;
    }
    runningIsotope = true;
    
    var classesStr = '';
    //Check all values
    $('.iso-filter:checked').each(function(){
      classesStr += '.' + $(this).val();
    });
    
    if(!SKIPSAVE){
      SaveFiltersInSession(classesStr);  
    }
    
    
    $('.iso-filter').attr('disabled', 'disabled');
    
    var $grid = $("#products-wrapper").isotope({
   	  itemSelector: '.prodout',
   	  layoutMode: 'fitRows',
   	  filter: (classesStr ? classesStr: '.show-all')
    });
    
    
    $grid.on( 'arrangeComplete', function( event, filteredItems ) {
      //Update product count
      $('#prodcnt').html('<span>' + filteredItems.length + '</span> product' + (filteredItems.length > 1 ? 's' : ''));
      $('#prodcnt-mob').html('Filter ' + filteredItems.length + ' product' + (filteredItems.length > 1 ? 's' : ''));
      
      refreshFiltersCount();
      runningIsotope = false;
    });
    
  }
  function refreshFiltersCount(){
    $('.iso-filter').each(function(){
      var cnt = $('.prodout.' + $(this).val() + ':visible').length;
      if( cnt > 0){
        $(this).removeAttr('disabled');
        $(this).parent().fadeIn('slow');
      }else{
        $(this).attr('disabled', 'disabled');
        $(this).parent().fadeOut();
      }
      $(this).parent().find('.cnt-value').html(cnt + ' item' + (cnt > 1 ? 's' : ''));
    });
    
    $('.prod-filters').each(function(){
      if($(this).find('.iso-filter:enabled').length > 0){
        $(this).show();
      }else{
        $(this).hide();
      }
    });
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
  
  (function() {

    // store the slider in a local variable
    var $window = $(window), flexslider;

    // tiny helper function to add breakpoints
    function getGridSize() {
      return (window.innerWidth < 768) ? 1 : (window.innerWidth < 992) ? 4 : 6;
    }

    $("#sort").change(function(){
	    var str=$("#sort option:selected" ).val();
	    if(str == 'price-low-high'){
		    $("#products-wrapper").isotope({ sortBy: "price",sortAscending: true } );
		}
		else{
		    $("#products-wrapper").isotope({ sortBy: "price",  sortAscending: false } );
		}
	});


    $window.load(function() {
      $('.flexslider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 20,
        minItems: getGridSize(), // use function to pull in initial value
        maxItems: getGridSize()
      // use function to pull in initial value
      });
    });

    // check grid size on resize event
    $window.resize(function() {
      var gridSize = getGridSize();

      flexslider.vars.minItems = gridSize;
      flexslider.vars.maxItems = gridSize;
    });
  }());
  
  function ResetFilters(){
    $('.iso-filter:checked').removeAttr('checked');
    filterOptions();
  }
  
  function SaveFiltersInSession(FILTERS){
    $.ajax({
      type: "POST",
      url: "/process/cart",
      cache: false,
      data: 'action=SaveFiltersInSession&filters='+FILTERS+'&listing_object_id='+$('#prodcatdet').attr('data-listing-object-id'),
      dataType: "json",
      success: function(obj) {
        try{
          if(obj.success){
            console.log('filters-saved');
          }
        }catch(err){
          console.log('TRY-CATCH error');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log('AJAX error:' + errorThrown);
      }
    });
  }
</script>
{/block}

